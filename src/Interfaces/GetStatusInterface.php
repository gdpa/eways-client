<?php

namespace GDPA\EwaysClient\Interfaces;

interface GetStatusInterface
{
    /**
     * Set Transaction ID
     *
     * @param string $id
     * @return GetStatusInterface
     */
    public function transactionId(string $id) : GetStatusInterface;

    /**
     * Get Transaction ID
     *
     * @return string
     */
    public function getTransactionId() : string ;

    /**
     * Set Request ID
     *
     * @param string $id
     * @return GetStatusInterface
     */
    public function requestId(string $id) : GetStatusInterface;

    /**
     * Get Request ID
     *
     * @return string
     */
    public function getRequestId() : string;

    /**
     * Call GetStatus SOAP on eways
     *
     * @return GetStatusInterface
     */
    public function call() : GetStatusInterface;

    /**
     * Get responses from calling GetStatus SOAP
     * @return array
     */
    public function getResponse() : array;

    /**
     * Get results from response
     *
     * @return array
     */
    public function result() : array;

    /**
     * Get PIN from results
     *
     * @return string
     */
    public function pin() : string;

    /**
     * Get Serial from results
     *
     * @return string
     */
    public function serial() : string;

    /**
     * Get PaymentID from results
     *
     * @return string
     */
    public function paymentId() : string;

    /**
     * Get Authority from results
     *
     * @return string
     */
    public function authority() : string;
}