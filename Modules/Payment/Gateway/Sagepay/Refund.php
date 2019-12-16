<?php


namespace Modules\Payment\Gateway\Sagepay;

use App\Contracts\ValidateTransactionExists;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Modules\Payment\Exceptions\ObjectVerificationFailedException;
use Modules\Payment\Http\Requests\ClientRequestHandler;
use \Modules\Payment\Contracts\Refund as PaymentContractInterface;

class Refund extends PaymentGateway implements PaymentContractInterface
{
    protected $merchantSessionKeyObject;
    protected $cardIdentifierObject;
    protected $threeDSecure;
    protected $transactionType;

    public function __construct(Request $request)
    {

        parent::__construct($request);

//        $this->validateResponse();

    }

    /**
     * @param bool $threeDSecure
     * @return mixed
     * @throws \Exception
     */
    public function refundOrder($threeDSecure = false) {

        $this->threeDSecure = 'Disable';

        $this->transactionType = "Refund";

        return $this->processOrder();

    }

    /**
     * @return array
     * @throws ObjectVerificationFailedException
     */
    protected function preparePayload() : array {

        $this->validateInput();

        return [
            'transactionType' => $this->transactionType,
            'referenceTransactionId' => $this->payload['referenceTransactionId'],
            'vendorTxCode' => $this->payload['vendorTxCode'] . time(),
            'amount' => $this->payload['amount'],
            'description' => $this->payload['description']
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
            'description' => ['required']
        ];

        $validator = Validator::make($this->payload, $rules);

        if ($validator->fails()) {
            throw new ObjectVerificationFailedException('The gateway response did not contain all the mandatory fields ['. implode(', ', array_keys($validator->errors()->getMessages())) .']');
        }

    }

    /**
     * Check if the transaction in question is a valid transaction or not before voiding it
     *
     * @return ValidateTransactionExists false on failure json object on success
     *
     * @throws \Exception
     */
    public function validateTransaction(): ValidateTransactionExists
    {
        // TODO: Implement validateTransaction() method.
    }
}
