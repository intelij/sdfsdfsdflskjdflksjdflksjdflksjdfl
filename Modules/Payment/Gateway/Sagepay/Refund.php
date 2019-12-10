<?php


namespace Modules\Payment\Gateway\Sagepay;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Modules\Payment\Exceptions\ObjectVerificationFailedException;
use Modules\Payment\Http\Requests\ClientRequestHandler;

class Refund extends PaymentGateway
{
    protected $merchantSessionKeyObject;
    protected $cardIdentifierObject;
    protected $threeDSecure;
    protected $transactionType;

    public function __construct()
    {

        $this->payload = \request()->all();
        $this->requestHeaders = apache_request_headers();
        $this->clientRequest = new ClientRequestHandler();

    }

    public function refundOrder($threeDSecure = false) {

        $this->threeDSecure = 'Disable';

        $this->transactionType = "Refund";

        return $this->payOrder();

    }

    protected function formatData() {

        $this->validateInput();

        return [
            'transactionType' => $this->transactionType,
            'referenceTransactionId' => $this->payload['referenceTransactionId'],
            'vendorTxCode' => $this->payload['vendorTxCode'] . time(),
            'amount' => $this->payload['amount'],
            'description' => $this->payload['description'],
        ];

    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateInput(): \Illuminate\Contracts\Validation\Validator
    {

        $rules = [
            'transactionType' => ['required'],
            'referenceTransactionId' => ['required'],
            'vendorTxCode' => ['required'],
            'amount' => ['required'],
            'description' => ['required'],

        ];

        $validator = Validator::make($this->payload, $rules);

        if ($validator->fails()) {
            throw new ObjectVerificationFailedException('The gateway response did not contain all the mandatory fields ['. implode(', ', array_keys($validator->errors()->getMessages())) .']');
        }

    }

}
