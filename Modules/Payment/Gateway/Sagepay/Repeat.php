<?php


namespace Modules\Payment\Gateway\Sagepay;

use Illuminate\Support\Facades\Validator;
use Modules\Payment\Exceptions\ObjectVerificationFailedException;
use Modules\Payment\Http\Requests\ClientRequestHandler;

class Repeat extends PaymentGateway
{
    protected $merchantSessionKeyObject;
    protected $cardIdentifierObject;
    protected $threeDSecure;
    protected $transactionType;

    public function __construct()
    {

        $this->payload = \request()->all();
        $this->requestHeaders = $this->request_headers();
//        $this->requestHeaders = apache_request_headers();
        $this->clientRequest = new ClientRequestHandler();

    }

    public function repeatOrder($threeDSecure = false) {

        $this->threeDSecure = 'Disable';
        $this->transactionType = "Repeat";
        return $this->processOrder();

    }

    /**
     * Prepare the payload expected by the Payment API
     *
     * @return array
     * @throws ObjectVerificationFailedException
     */
    protected function preparePayload() {

        $this->validateInput();

        return [
            'transactionType' => $this->transactionType,
            'referenceTransactionId' => $this->payload['referenceTransactionId'],
            'vendorTxCode' => $this->payload['vendorTxCode'] . time(),
            'amount' => $this->payload['amount'],
            'currency' => $this->payload['currency'],
            'description' => $this->payload['description'],
            'shippingDetails' => [
                'recipientFirstName' => $this->payload['shippingDetails']['recipientFirstName'],
                'recipientLastName' => $this->payload['shippingDetails']['recipientLastName'],
                'shippingAddress1' => $this->payload['shippingDetails']['shippingAddress1'],
                'shippingAddress2' => $this->payload['shippingDetails']['shippingAddress2'],
                'shippingCity' => $this->payload['shippingDetails']['shippingCity'],
                'shippingPostalCode' => $this->payload['shippingDetails']['shippingPostalCode'],
                'shippingCountry' => $this->payload['shippingDetails']['shippingCountry'],
            ],
        ];

    }

    /**
     * @return void
     * @throws ObjectVerificationFailedException
     */
    protected function validateInput()
    {

        $rules = [
            'transactionType' => ['required'],
            'referenceTransactionId' => ['required'],
            'vendorTxCode' => ['required'],
            'amount' => ['required'],
            'description' => ['required'],
            'shippingDetails.recipientFirstName' => ['required'],
            'shippingDetails.recipientLastName' => ['required'],
            'shippingDetails.shippingAddress1' => ['required'],
            'shippingDetails.shippingAddress2' => ['required'],
            'shippingDetails.shippingCity' => ['required'],
            'shippingDetails.shippingPostalCode' => ['required'],
            'shippingDetails.shippingCountry' => ['required']
        ];

        $validator = Validator::make($this->payload, $rules);

        if ($validator->fails()) {
            throw new ObjectVerificationFailedException('The gateway response did not contain all the mandatory fields ['. implode(', ', array_keys($validator->errors()->getMessages())) .']');
        }

    }

}
