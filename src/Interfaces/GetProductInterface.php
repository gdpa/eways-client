<?php

namespace GDPA\EwaysClient\Interfaces;

interface GetProductInterface
{
    /**
     * Set username
     *
     * @param string $username
     * @return GetProductInterface
     */
    public function setUsername(string $username) : GetProductInterface;

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername() : string;

    /**
     * Set Transaction ID
     *
     * @param string $id
     * @return GetProductInterface
     */
    public function transactionId(string $id) : GetProductInterface;

    /**
     * Get Transaction ID
     *
     * @return string
     */
    public function getTransactionId() : string ;

    /**
     * Call Eways GetProduct SOAP
     *
     * @return GetProductInterface
     */
    public function call() : GetProductInterface;

    /**
     * Get response from calling GetProduct SOAP
     *
     * @return array
     */
    public function getResponse() : array;

    /**
     * Get results as array from calling GetProduct SOAP
     *
     * @return array
     */
    public function result() : array;

    /**
     * Get RequestID from calling GetProduct SOAP results
     *
     * @return string
     */
    public function requestId() : string;

    /**
     * Get products from results
     *
     * @return array
     */
    public function products() : array ;

    /**
     * Get Products which have operator
     *
     * @return array
     */
    public function operators() : array ;

    /**
     * Find product by CID from operator products
     *
     * @param string $id
     * @return array
     */
    public function find(string $id) : array;
}