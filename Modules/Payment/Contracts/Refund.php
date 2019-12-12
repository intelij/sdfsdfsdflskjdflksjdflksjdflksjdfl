<?php


namespace Modules\Payment\Contracts;


use App\Contracts\ValidateTransactionExists;
use Modules\Payment\Exceptions\ObjectVerificationFailedException;
use Psr\Http\Message\ResponseInterface;

interface Refund
{

    /**
     * Check if the transaction in question is a valid transaction or not before voiding it
     *
     * @return ValidateTransactionExists false on failure json object on success
     *
     * @throws \Exception
     */
    public function validateTransaction() : ValidateTransactionExists;

    /**
     * @param bool $threeDSecure
     * @return mixed
     * @throws \Exception
     */
    public function refundOrder($threeDSecure = false);

    /**
     * Generate Merchant Session Key
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function getToken() : ResponseInterface;

    /**
     * Gracefully handles request errors
     *
     * @return array|false false on failure json object on success
     */
    public function validateResponse();


}
