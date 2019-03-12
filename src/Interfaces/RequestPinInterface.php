<?php

namespace GDPA\EwaysClient\Interfaces;

interface RequestPinInterface
{
    /**
     * Set password
     *
     * @param string $password
     * @return RequestPinInterface
     */
    public function setPassword(string $password) : RequestPinInterface;

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
    public function requestId(string $id) : RequestPinInterface;

    /**
     * Get Request ID
     *
     * @return string
     */
    public function getRequestId() : string;

    /**
     * Set Mobile
     *
     * @param string $mobile
     * @return RequestPinInterface
     */
    public function mobile(string $mobile) : RequestPinInterface;

    /**
     * Get Mobile
     *
     * @return string
     */
    public function getMobile() : string;

    /**
     * Set Email
     *
     * @param string $email
     * @return RequestPinInterface
     */
    public function email(string $email) : RequestPinInterface;

    /**
     * Get Email
     *
     * @return string
     */
    public function getEmail() : string;

    /**
     * Set RefUrl
     *
     * @param string $url
     * @return RequestPinInterface
     */
    public function refUrl(string $url) : RequestPinInterface;

    /**
     * Get RefUrl
     *
     * @return string
     */
    public function getRefUrl() : string;

    /**
     * Set OptionalParam
     *
     * @param $optional
     * @return RequestPinInterface
     */
    public function optional($optional) : RequestPinInterface;

    /**
     * Get OptionalParam
     * @return mixed
     */
    public function getOptional();

    /**
     * Set Quantity
     *
     * @param int $quantity
     * @return RequestPinInterface
     */
    public function quantity(int $quantity) : RequestPinInterface;

    /**
     * Get Quantity
     *
     * @return int
     */
    public function getQuantity() : int ;

    /**
     * Set ProductType
     *
     * @param int $productType
     * @return RequestPinInterface
     */
    public function productType(int $productType) : RequestPinInterface;

    /**
     * Get ProductType
     *
     * @return int
     */
    public function getProductType() : int ;

    /**
     * Call RequestPin SOAP
     *
     * @return RequestPinInterface
     */
    public function call() : RequestPinInterface;

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
     * Get OrderID from results
     *
     * @return string
     */
    public function orderId() : string;

    /**
     * Get Serial from results
     *
     * @return string
     */
    public function serial() : string;
}