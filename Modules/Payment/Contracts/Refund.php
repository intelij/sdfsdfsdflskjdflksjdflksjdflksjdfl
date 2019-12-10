<?php


namespace Modules\Payment\Contracts;


use App\Contracts\ValidateTransactionExists;

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
     * @param $transactionId
     * @param $amount
     * @return mixed
     */
    public function refund($transactionId, $amount);

}
