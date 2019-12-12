<?php


namespace Modules\Payment\Contracts;


use Dividebuy\Payment\Address;
use Psr\Http\Message\ResponseInterface;

interface Payment
{

    /**
     * @param bool $threeDSecure
     * @return mixed
     * @throws \Exception
     */
    public function processOrder($threeDSecure = false);

    /**
     * @return \Illuminate\Http\JsonResponse|ResponseInterface
     */
    public function createCardIdentifier() : ResponseInterface;

    /**
     * Generate Merchant Session Key
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function getToken() : ResponseInterface;

    /**
     * @param bool $threeDSecure
     * @return mixed
     * @throws \Exception
     */
    public function paymentOrder($threeDSecure = false);

    /**
     * Gracefully handles request errors
     *
     * @return array|false false on failure json object on success
     */
     public function validateResponse();

}
