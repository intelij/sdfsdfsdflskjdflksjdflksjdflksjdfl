<?php


namespace Modules\Payment\Gateway\Sagepay;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Modules\Payment\Exceptions\ObjectVerificationFailedException;
use Modules\Payment\Http\Requests\ClientRequestHandler;
use \Modules\Payment\Contracts\Payment as PaymentContractInterface;

class Payment extends PaymentGateway implements PaymentContractInterface
{
    protected $merchantSessionKeyObject;
    protected $cardIdentifierObject;
    protected $threeDSecure;
    protected $transactionType;

    public function __construct(Request $request)
    {


        $this->payload = \request()->all();
        $this->requestHeaders = $this->request_headers();
        $this->request = $request;
        $this->clientRequest = new ClientRequestHandler();
        $this->validateResponse();

    }

    /**
     * @param bool $threeDSecure
     * @return mixed
     * @throws \Exception
     */
    public function paymentOrder($threeDSecure = false) {

        if ($threeDSecure === true) {
            $this->threeDSecure = 'Force';
        } else {
            $this->threeDSecure = 'Disable';
        }

        $this->transactionType = "Payment";

        return $this->processOrder();

    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function preparePayload() {

        $avsCheck = 'UseMSPSetting';

        $this->validateInput();

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
     * @return void
     * @throws ObjectVerificationFailedException
     */
    protected function validateInput()
    {

        $rules = [
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
        ];

        $validator = Validator::make($this->payload, $rules);

        if ($validator->fails()) {
            throw new ObjectVerificationFailedException('The gateway response did not contain all the mandatory fields ['. implode(', ', array_keys($validator->errors()->getMessages())) .']');
        }

    }

}
