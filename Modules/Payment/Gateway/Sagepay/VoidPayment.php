<?php


namespace Modules\Payment\Gateway\Sagepay;

use App\Contracts\ValidateTransactionExists;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Modules\Payment\Exceptions\ObjectVerificationFailedException;
use Modules\Payment\Http\Requests\ClientRequestHandler;
use \Modules\Payment\Contracts\VoidTransaction as PaymentContractInterface;

class VoidPayment extends PaymentGateway implements PaymentContractInterface
{
    protected $merchantSessionKeyObject;
    protected $cardIdentifierObject;
    protected $transactionType;

    public function __construct()
    {

        $this->payload = \request()->all();
        $this->requestHeaders = $this->request_headers();
        $this->clientRequest = new ClientRequestHandler();

    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function voidOrder() {

        $this->transactionType = "void";

        return $this->processVoidOrder();

    }

    /**
     * @return array
     * @throws ObjectVerificationFailedException
     */
    protected function preparePayload() : array {

        $this->validateInput();

        return [
            'instructionType' => $this->transactionType
        ];

    }

    /**
     * @return void
     * @throws ObjectVerificationFailedException
     */
    protected function validateInput()
    {

        $rules = [
            'transactionId' => ['required'],
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
