<?php


namespace Modules\Payment\Gateway\Sagepay;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Modules\Payment\Http\Requests\ClientRequestHandler;

class Deferred extends PaymentGateway
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
        $this->validateResponse();

    }

    /**
     * @param bool $threeDSecure
     * @return mixed
     * @throws \Exception
     */
    public function deferredOrder($threeDSecure = false) {

        if ($threeDSecure === true) {
            $this->threeDSecure = 'Force';
        } else {
            $this->threeDSecure = 'Disable';
        }

        $this->transactionType = "Deferred";

        return $this->payOrder();

    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function formatData() {

        $avsCheck = 'UseMSPSetting';

        $validator = $this->validateInput();

        if ($validator->fails()) {
            $fields = implode(', ', array_keys($validator->errors()->getMessages()));
            throw new \Exception("Missing some mandatory fields [$fields]");
        }

        return [
            'transactionType' => $this->transactionType,
            'paymentMethod' => [
                'card' => [
                    'merchantSessionKey' => $this->merchantSessionKeyObject->merchantSessionKey,
                    'cardIdentifier' => $this->cardIdentifierObject->cardIdentifier,
                    'save' => false
                ]
            ],
            'vendorTxCode' => $this->payload['vendorTxCode'] . time(), // @TODO REMOVE TIME
            'amount' =>  $this->payload['amount'],
            'currency' =>  $this->payload['currency'],
            'description' =>  $this->payload['description'],
            'apply3DSecure' =>  $this->threeDSecure,
            'applyAvsCvcCheck' =>  $avsCheck,
            'customerFirstName' =>  $this->payload['customerFirstName'],
            'customerLastName' =>  $this->payload['customerLastName'],
            'customerEmail' =>  $this->payload['customerEmail'],
            'billingAddress' => [
                'address1' =>  $this->payload['billingAddress']['address1'],
                'city' =>  $this->payload['billingAddress']['city'],
                'postalCode' =>  $this->payload['billingAddress']['postalCode'],
                'country' =>  $this->payload['billingAddress']['country'],
            ],
            'entryMethod' =>  $this->payload['entryMethod']
        ];

    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateInput(): \Illuminate\Contracts\Validation\Validator
    {

        return Validator::make($this->payload, [
            'transactionType' => ['max:40'],
            'cardDetails.cardholderName' => ['required', 'max:40'],
            'cardDetails.cardNumber' => ['required', 'max:16'],
            'cardDetails.expiryDate' => ['required', 'max:4'],
            'cardDetails.securityCode' => ['required', 'max:3'],
            'vendorTxCode' => ['required', 'max:40'],
            'apply3DSecure' => [
                'required',
                Rule::in(["UseMSPSetting", "Force", "Disable", "ForceIgnoringRules"]),
            ],
            'customerFirstName' => ['required'],
            'customerFirstName' => ['required'],
            'billingAddress.address1' => ['required'],
            'billingAddress.city' => ['required'],
            'billingAddress.postalCode' => ['required'],
            'billingAddress.country' => ['required']
        ]);

    }

}
