<?php


namespace Modules\Payment\Contracts;


use App\Contracts\ValidateTransactionExists;

interface VoidTransaction
{

    /**
     * Instruction to cancel the order
     *
     * @return mixed
     */
    public function voidOrder();

    /**
     * Check if the transaction in question is a valid transaction or not before voiding it
     *
     * @return ValidateTransactionExists false on failure json object on success
     */
    public function validateTransaction() : ValidateTransactionExists;

    /**
     * Gracefully handles request errors
     *
     * @return array|false false on failure json object on success
     */
    public function validateResponse();

}
