<?php


namespace Modules\Payment\Contracts;


use Dividebuy\Payment\Address;

interface Payment
{

    /**
     * @param $order
     *
     * @return Payment
     */
    public function setOrder(PayableOrder $order);

    /**
     * @return mixed
     */
    public function getAcsUrl() : string;

    /**
     * Gracefully handles request errors
     *
     * @return array|false false on failure json object on success
     */
    public function validateResponse();

    /**
     * @return mixed
     */
    public function getPaymentResult();

    /**
     * @param bool $threeDSecure
     * @return mixed
     */
    public function payOrder($threeDSecure = false);

}
