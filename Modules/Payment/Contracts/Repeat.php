<?php


namespace Modules\Payment\Contracts;


use App\Contracts\ValidateTransactionExists;

interface Repeat
{

    /**
     * Reference Transaction ID that needs to be repeated.
     *
     * @return string
     */
    public function getReferenceTransactionId() : string;

    /**
     * Gracefully handles request errors
     *
     * @return ValidateTransactionExists false on failure json object on success
     */
    public function validateTransaction() : ValidateTransactionExists;

    /**
     * @return mixed
     */
    public function getPaymentResult();

    /**
     * This will return a billable address object attached to the card
     *
     * @param PayableOrder $order
     */
    public function setOrder(PayableOrder $order);

    /**
     * @param bool $threeDSecure
     * @return array
     */
    public function repeatOrder($threeDSecure = false);

}
