<?php

namespace GDPA\EwaysClient;

use GDPA\EwaysClient\Exceptions\InvalidConfigurationError;
use GDPA\EwaysClient\Interfaces\RequestBillInterface;

class RequestBill extends Request implements RequestBillInterface
{
    /**
     * @var array
     */
    protected $errorCodes = [1, 2, 3, 4, 11, 12, 14, 21, 30, 31, 32, 33, 34, 35, 36, 37, 41, 43, 50, 51, 110, 111, 112,
        401, 402, 403, 404, 405, 406, 407, 408, 501, 502, 503, 504, 505, 506, 509, 601, 602, 603, 604, 605, 606, 607,
        608, 609, 701, 702, 703, 704, 705, 706, 707, 708, 709, 710, 711, 712, 801, 802, 803, 804, 805, 806, 807, 808,
        809, -100, 200, -12, -113, -1, -2, -3, -4, -5, -6, -7, -8, -9, -114];

    /**
     * @var string
     */
    protected $payId;

    /**
     * @var string
     */
    protected $billId;

    /**
     * Set Pay Id
     * @param string $payId
     * @return RequestBillInterface
     */
    public function payId(string $payId): RequestBillInterface
    {
        $this->payId = $payId;

        return $this;
    }

    /**
     * Get Pay Id
     * @return string
     */
    public function getPayId(): string
    {
        return $this->payId;
    }

    /**
     * Set Bill Id
     * @param string $billId
     * @return RequestBillInterface
     */
    public function billId(string $billId): RequestBillInterface
    {
        $this->billId = $billId;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillId(): string
    {
        return $this->billId;
    }

    /**
     * Get results from response
     *
     * @return array
     * @throws Exceptions\ConnectionError
     */
    public function result(): array
    {
        if (empty($this->response)) {
            $this->call();
        }

        return $this->response['RequestBillResult'];
    }

    /**
     * Validate calling RequestPin response
     */
    public function validate() : void
    {
        if (is_null($this->requestId)) {
            throw InvalidConfigurationError::requestIdIsRequired();
        }

        if (is_null($this->billId)) {
            throw InvalidConfigurationError::billIdIsRequired();
        }

        if (is_null($this->payId)) {
            throw InvalidConfigurationError::payIdIsRequired();
        }
    }

    /**
     * @return mixed
     */
    public function callSoap()
    {
        return $this->client->RequestBill([
            'RequestID' => $this->requestId,
            'SitePassword' => $this->password,
            'BillID' => $this->billId,
            'PayID' => $this->payId,
            'OptionalParam' => $this->optional,
        ]);
    }

    /**
     * Validate Response Structure
     *
     * @return bool
     */
    public function responseStructureIsWrong(): bool
    {
        return ! array_key_exists('RequestBillResult', $this->response)
            || ! array_key_exists('ResultCode', $this->response['RequestBillResult'])
            || ! array_key_exists('Message', $this->response['RequestBillResult']);
    }

    /**
     * Get Response Status
     *
     * @return int
     */
    public function getResponseStatus(): int
    {
        return $this->response['RequestBillResult']['ResultCode'];
    }

    /**
     * Get Response Message
     *
     * @return string
     */
    public function getResponseMessage(): string
    {
        return $this->response['RequestBillResult']['Message'];
    }
}