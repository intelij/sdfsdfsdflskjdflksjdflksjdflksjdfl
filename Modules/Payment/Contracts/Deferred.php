<?php


namespace Modules\Payment\Contracts;


interface Deferred
{

    /**
     * @param $order
     *
     * @return Payment
     */
    public function setOrder(PayableOrder $order): Payment;

    /**
     * @return mixed
     */
    public function getAcsUrl() : string;

    /**
     * @return mixed
     */
    public function validateResponse();

    /**
     * @return mixed
     */
    public function getPaymentResult();

    /**
     * @param $threeDSecure
     * @return mixed
     */
    public function deferOrder($threeDSecure = false);

}
