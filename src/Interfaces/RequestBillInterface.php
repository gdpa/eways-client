<?php

namespace GDPA\EwaysClient\Interfaces;

interface RequestBillInterface extends RequestInterface
{
    /**
     * Set Pay Id
     * @param string $payId
     * @return RequestBillInterface
     */
    public function payId(string $payId) : RequestBillInterface;

    /**
     * Get Pay Id
     * @return string
     */
    public function getPayId() : string;

    /**
     * Set Bill Id
     * @param string $billId
     * @return RequestBillInterface
     */
    public function billId(string $billId) : RequestBillInterface;

    /**
     * @return string
     */
    public function getBillId() : string;
}