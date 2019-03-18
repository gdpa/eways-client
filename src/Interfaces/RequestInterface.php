<?php

namespace GDPA\EwaysClient\Interfaces;

interface RequestInterface
{
    /**
     * Set password
     *
     * @param string $password
     * @return RequestPinInterface
     */
    public function setPassword(string $password) : RequestInterface;

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() : string ;

    /**
     * Set Request ID
     *
     * @param string $id
     * @return RequestPinInterface
     */
    public function requestId(string $id) : RequestInterface;

    /**
     * Get Request ID
     *
     * @return string
     */
    public function getRequestId() : string;

    /**
     * Set OptionalParam
     *
     * @param $optional
     * @return RequestInterface
     */
    public function optional($optional) : RequestInterface;

    /**
     * Get OptionalParam
     *
     * @return mixed
     */
    public function getOptional();

    /**
     * Call RequestPin SOAP
     *
     * @return RequestPinInterface
     */
    public function call() : RequestInterface;

    /**
     * Get response from calling SOAP
     *
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
     * Get response Status
     *
     * @return int
     */
    public function getResponseStatus() : int;

    /**
     * Get Response Message
     *
     * @return string
     */
    public function getResponseMessage() : string;
}