<?php


namespace Modules\Payment\Gateway\Sagepay;


use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Modules\Payment\Contracts\PayableOrder;
use Modules\Payment\Http\Requests\ClientRequestHandler;
use Psr\Http\Message\ResponseInterface;

class PaymentGateway
{
    protected $merchantSessionKeyObject;
    protected $cardIdentifierObject;
    protected $requestHeaders;
    protected $clientRequest;
    protected $threeDSecure;
    protected $transactionType;

    /**
     * @var PayableOrder $payload
     */
    protected $payload;
    protected $request;

//    /**
//     * PaymentGateway constructor.
//     * @param Request $request
//     */
//    public function __construct(Request $request)
//    {
//
//        $this->payload = $this->request = $request;
//        $this->clientRequest = new ClientRequestHandler();
//
//    }

//    protected $merchantSessionKeyObject;
//    protected $cardIdentifierObject;
//    protected $threeDSecure;
//    protected $transactionType;

    public function __construct(Request $request)
    {

        $this->payload = $request->request->all();
        $this->request = $request;

        $headers = [];
        foreach ($request->header() as $k => $v) {
            $headers[$k] = $v[0];
        }

        $this->requestHeaders = $headers;
        $this->clientRequest = new ClientRequestHandler();

    }

    /**
     * Generate Merchant Session Key
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function getToken() : ResponseInterface {

        $requestHandler = new ClientRequestHandler();
        $apiEndpoint = env('SAGEPAY_BASEURL') . 'merchant-session-keys/';

        $data = [
            'vendor_name' => $this->request->headers->get('authorization')
        ];

        $response = $requestHandler->makeRequest($apiEndpoint, 'post', $data, $this->request->headers->all());

        if ($response instanceof ResponseInterface) {

            $this->merchantSessionKeyObject = json_decode($response->getBody()->__toString());

            return $response;

        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse|ResponseInterface
     * @throws \Exception
     */
    public function createCardIdentifier() : ResponseInterface {

        $cardDetails = [
            "cardDetails" => [
                "cardholderName" => $this->payload['cardDetails']['cardholderName'],
                "cardNumber" => $this->payload['cardDetails']['cardNumber'],
                "expiryDate" => $this->payload['cardDetails']['expiryDate'],
                "securityCode" => $this->payload['cardDetails']['securityCode']
            ]
        ];

        $apiEndPoint = $this->payload['baseUrl'] . 'card-identifiers';

        $postData = [
            'vendorName' => $this->payload['vendorName'],
            'vendorTxCode' => null,
        ];

        $postData = array_merge($postData, $cardDetails);

        $merchantSessionKey = json_decode($this->getToken()->getBody()->__toString())->merchantSessionKey; //$this->merchantSessionKeyObject->merchantSessionKey;

        $client = new Client(
            [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer ' . $merchantSessionKey
                ]
            ]
        );


        $response = $this->clientRequest->processRequest($apiEndPoint, $client, $postData);

        if ($response instanceof ResponseInterface) {

            $this->cardIdentifierObject = json_decode($response->getBody()->__toString());

            return $response;

        }

    }

//    /**
//     * @param \Exception $exception
//     * @return string|null
//     */
//    private function setDescriptionHandler(\Exception $exception) : string
//    {
//
//        $description = $exception->getMessage();
//
//        if ($exception->hasResponse()) {
//
//            $response = json_decode($exception->getResponse()->getBody()->getContents(), true);
//
//            $description = array_key_exists('description', $response) ? $response['description'] : null;
//
//            if (array_key_exists('errors', $response)) {
//                $description = $response['errors'][0]['description'];
//                if (array_key_exists('property', $response['errors'][0])) {
//                    $description .= ' {' . $response['errors'][0]['property'] . '}';
//                }
//            }
//
//        }
//
//        return $description;
//    }

    /**
     * Gracefully handles request errors
     *
     * @return void false on failure json object on success
     * @throws \Exception
     */
    public function validateResponse()
    {
        $this->getToken();
        $this->createCardIdentifier();
    }

    /**
     * @param bool $threeDSecure
     * @return mixed
     * @throws \Exception
     */
    public function processOrder($threeDSecure = false)
    {

        $postData = $this->preparePayload();

        $apiEndpoint = env('SAGEPAY_BASEURL') . 'transactions';

        $response = $this->clientRequest->makeRequest($apiEndpoint, 'post', $postData, $this->requestHeaders);

        if ($response instanceof ResponseInterface) {

            return json_decode($response->getBody()->__toString());

        }

        return $response;

    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function processVoidOrder()
    {

        $postData = $this->preparePayload();

        $apiEndpoint = env('SAGEPAY_BASEURL') . 'transactions/' . $this->payload['transactionId'] . '/instructions';

        $response = $this->clientRequest->makeRequest($apiEndpoint, 'post', $postData, $this->requestHeaders);

        if ($response instanceof ResponseInterface) {

            return json_decode($response->getBody()->__toString());

        }

        return $response;

    }

//    protected function request_headers()
//    {
//        if(function_exists("apache_request_headers"))
//        {
//            if($headers = apache_request_headers())
//            {
//                return $headers;
//            }
//        }
//        $headers = array();
//        foreach(array_keys($_SERVER) as $skey)
//        {
//            if(substr($skey, 0, 5) == "HTTP_")
//            {
//                $headername = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($skey, 0, 5)))));
//                $headers[$headername] = $_SERVER[$skey];
//            }
//        }
//        return $headers;
//    }

}
