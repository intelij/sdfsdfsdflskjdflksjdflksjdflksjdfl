<?php


namespace Modules\Payment\Gateway\Sagepay;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Payment\Contracts\PayableOrder;
use Modules\Payment\Contracts\Payment as PaymentGatewayInterface;
use Modules\Payment\Http\Requests\ClientRequestHandler;
use Psr\Http\Message\ResponseInterface;

class PaymentGateway implements PaymentGatewayInterface
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

    public function __construct()
    {

        $this->payload = \request()->all();
        $this->requestHeaders = apache_request_headers();
        $this->clientRequest = new ClientRequestHandler();

    }

    public function __toString()
    {
        return (string) $this->merchantSessionKeyObject;
    }

    public function getToken() {

        $requestHandler = new ClientRequestHandler();
        $apiEndpoint = env('SAGEPAY_BASEURL') . 'merchant-session-keys/';

        if (!array_key_exists('Authorization', $this->requestHeaders)) {
            throw new \Exception("Missing mandatory field");
        }

        $data = [
            'vendor_name' => $this->payload['vendorName']
        ];

        $response = $requestHandler->makeRequest($apiEndpoint, 'post', $data, $this->requestHeaders);

        if ($response instanceof ResponseInterface) {

            $this->merchantSessionKeyObject = json_decode($response->getBody()->__toString());

//            return $response;

        }

    }

    public function getCardAuthIdentifier() {

        $requestHandler = new ClientRequestHandler();

        $apiEndpoint = env('SAGEPAY_BASEURL') . 'card-identifiers';

        if (!array_key_exists('Authorization', $this->requestHeaders)) {
            throw new \Exception("Missing mandatory field");
        }

        $data = array_merge($this->payload, [
            'vendor_name' => $this->payload['vendorName']
        ]);

        $response = $requestHandler->makeRequest($apiEndpoint, 'post', $data, $this->requestHeaders);

        if ($response instanceof ResponseInterface) {

            $this->merchantSessionKeyObject = json_decode($response->getBody()->__toString());

        }

        return $response;

    }

    public function refreshToken() {
        /**
         * if token expired then generate a new one to use for the new transaction.
         */
        if ($this->merchantSessionKeyObject && $this->tokenExpired()) {

            $this->getToken();

        }
    }

    /**
     * Check to see if we have a valid token or need to generate a new one as it is valid for only 4min
     *
     * @return bool
     */
    protected function hasTokenExpired() {

        return (date('Y-m-d H:i:s') <= date('Y-m-d H:i:s', strtotime($this->merchantSessionKeyObject->expiry)));

    }

    public function createCardIdentifier() {

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

        $client = new Client(
            [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer ' . $this->merchantSessionKeyObject->merchantSessionKey
                ]
            ]
        );

        $response = $this->clientRequest->processRequest($apiEndPoint, $client, $postData);

        if ($response instanceof ResponseInterface) {

            $this->cardIdentifierObject = json_decode($response->getBody()->__toString());

        }

//        try {
//
//            $response = $client->post($apiEndPoint, ['json' => $postData]);
//
//            if ($response instanceof ResponseInterface) {
//
//                $this->cardIdentifierObject = json_decode($response->getBody()->__toString());
//
//            }
//
//        } catch (ClientException $clientException) {
//
//            $description = $this->setDescriptionHandler($clientException);
//
//            return response()->json(['error' => $description], $clientException->getCode());
//
//        } catch (ServerException $serverException) {
//
//            $description = $this->setDescriptionHandler($serverException);
//
//            return response()->json(['error' => $description], $serverException->getCode());
//
//        } catch (\Exception $exception) {
//
//            $description = $this->setDescriptionHandler($exception);
//
//            return response()->json(['error' => $description], $exception->getCode());
//        }

    }

    /**
     * @param \Exception $exception
     * @return string|null
     */
    private function setDescriptionHandler(\Exception $exception)
    {

        $description = $exception->getMessage();

        if ($exception->hasResponse()) {

            $response = json_decode($exception->getResponse()->getBody()->getContents(), true);

            $description = array_key_exists('description', $response) ? $response['description'] : null;

            if (array_key_exists('errors', $response)) {
                $description = $response['errors'][0]['description'];
                if (array_key_exists('property', $response['errors'][0])) {
                    $description .= ' {' . $response['errors'][0]['property'] . '}';
                }
            }

        }

        return $description;
    }

    /**
     * @param $order
     *
     */
    public function setOrder($order)
    {
        // TODO: Implement setOrder() method.
    }

    /**
     * @return mixed
     */
    public function getAcsUrl(): string
    {
        // TODO: Implement getAcsUrl() method.
    }

    /**
     * Gracefully handles request errors
     *
     * @return void false on failure json object on success
     * @throws \Exception
     */
    public function validateResponse()
    {
        // TODO: Implement validateResponse() method.
        $this->getToken();
        $this->createCardIdentifier();
    }

    /**
     * @return mixed
     */
    public function getPaymentResult()
    {
        // TODO: Implement getPaymentResult() method.
    }

    /**
     * @param bool $threeDSecure
     * @return mixed
     * @throws \Exception
     */
    public function payOrder($threeDSecure = false)
    {

        $postData = $this->formatData();

        $apiEndpoint = env('SAGEPAY_BASEURL') . 'transactions';

        $response = $this->clientRequest->makeRequest($apiEndpoint, 'post', $postData, $this->requestHeaders);

        if ($response instanceof ResponseInterface) {

            return json_decode($response->getBody()->__toString());

        }

        return $response;

    }

}
