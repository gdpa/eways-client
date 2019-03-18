<?php

namespace GDPA\EwaysClient\Interfaces;

interface RequestPinInterface extends RequestInterface
{
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