<?php
/**
 * Created by PhpStorm.
 * User: khululekanimkhonza
 * Date: 04/12/2019
 * Time: 21:51
 */

namespace App\Contracts;


interface ValidateTransactionExists
{

    /**
     * Transaction in question identifier
     *
     * @return string
     */
    public function setTransactionId() : string;

    /**
     *
     * @return array object with transaction data
     * @throws \Exception
     */
    public function checkTransactionExists() : array;

    /**
     * This will be used for refund, and void optlions as we will need to check the amount being processed is not
     * greater than the total invoice amount.
     *
     * @return float
     */
    public function getTransactionAmount() : float;

}
