<?php


namespace Modules\Payment\Contracts;


use Psr\Http\Message\ResponseInterface;

interface Deferred
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
     * Gracefully handles request errors
     *
     * @return array|false false on failure json object on success
     */
    public function validateResponse();

    /**
     * @param bool $threeDSecure
     * @return mixed
     * @throws \Exception
     */
    public function deferredOrder($threeDSecure = false);

}
