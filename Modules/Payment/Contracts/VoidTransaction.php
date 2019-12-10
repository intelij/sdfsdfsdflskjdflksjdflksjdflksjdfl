<?php


namespace Modules\Payment\Contracts;


use App\Contracts\ValidateTransactionExists;

interface VoidTransaction
{

    /**
     * @param $instructionType
     * @param bool $abort
     * @return mixed
     */
    public function void($instructionType, $abort = false);

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
