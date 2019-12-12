<?php


namespace Modules\Payment\Contracts;


use Carbon\Exceptions\InvalidDateException;

interface Card
{

    /**
     * @return string
     */
    public function getCardHolderName() : string;

    /**
     * @return string
     */
    public function getCardNumber() : string;

    /**
     * @return string
     *
     * @throws InvalidDateException
     */
    public function getCardExpiryDate() : string;

    /**
     * This is a numerical value btwn 3-4 chars long, depending on the implementing contract
     *
     * @return int
     */
    public function getCardSecurityCode() : int;

}
