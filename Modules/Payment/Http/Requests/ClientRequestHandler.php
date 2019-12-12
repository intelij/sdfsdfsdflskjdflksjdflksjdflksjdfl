<?php


namespace Modules\Payment\Http\Requests;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class ClientRequestHandler
{

    /**
     * @param string $apiEndPoint
     * @param string $method
     * @param array $data
     * @param array $headers
     * @param string $vendorTxtCode
     * @return ResponseInterface
     * @throws \Exception
     */
    public function makeRequest(string $apiEndPoint, string $method = 'post', array $data = [], $headers = [], $vendorTxtCode = '')
    {

        $postData = [
            'vendorName' => env('SAGEPAY_VENDORNAME'),
            'vendorTxCode' => $vendorTxtCode,
        ];

        $postData = array_merge($postData, $data);

        $client = new Client(
            [
                RequestOptions::HEADERS => $headers
            ]
        );

        return $this->processRequest($apiEndPoint, $client, $postData);

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
     * @param string $apiEndPoint
     * @param Client $client
     * @param array $postData
     * @return \Illuminate\Http\JsonResponse|ResponseInterface
     */
    public function processRequest(string $apiEndPoint, Client $client, array $postData)
    {
        try {


            $response = $client->post($apiEndPoint, ['json' => $postData]);

            return $response;

        } catch (ClientException $clientException) {

            $description = $this->setDescriptionHandler($clientException);

            return response()->json(['error' => $description], $clientException->getCode());

        } catch (ServerException $serverException) {

            $description = $this->setDescriptionHandler($serverException);

            return response()->json(['error' => $description], $serverException->getCode());

        } catch (\Exception $exception) {

            $description = $this->setDescriptionHandler($exception);

            return response()->json(['error' => $description], $exception->getCode());
        }
    }

}
