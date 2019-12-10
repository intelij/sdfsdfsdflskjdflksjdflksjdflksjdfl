<?php

namespace Modules\Payment\Contracts;


use Modules\Payment\Contracts\Address;
use Modules\Payment\Contracts\Card;

interface PayableOrder
{

    /**
     * This can be either a transactionId / referenceTransactionId
     * @return string | null
     */
    public function getPaymentOrderId();

    /**
     * Should be in pence for most payments providers.
     *
     * @return float
     */
    public function getPaymentAmount();

    /**
     * @return string
     */
    public function getPaymentDescription();

    /**
     * @return string
     */
    public function getCustomerEmail();

    /**
     * Billing Address/Shipping Address need to be defined for any transactions.
     *
     * @return Address
     */
    public function getAddress() : Address;

    /**
     * Card Object attached to the request
     *
     * @return Card
     */
    public function getCard() : Card;

    /**
     * Client PaymentGateway Key
     *
     * dzlSN2ZSOWYxenhnYXNTNWVjNDZia05vaTFsekFDNGlrV1pxa2gxZnFFa1Z6RkxsS0M6enVBRnVpckM1UEc0bEoyMlQzcmxCdDRXY1NmcTRpOWdyblJOcWpHWktYVGhDOFMwVmkwakt3V21tMHc2RGhZd2Q=
     *
     * @return string
     */
    public function getKey() : string;

}
