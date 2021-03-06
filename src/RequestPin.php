<?php

namespace GDPA\EwaysClient;

use GDPA\EwaysClient\Exceptions\ConnectionError;
use GDPA\EwaysClient\Exceptions\InvalidConfigurationError;
use GDPA\EwaysClient\Interfaces\RequestInterface;
use GDPA\EwaysClient\Interfaces\RequestPinInterface;

class RequestPin extends Request implements RequestPinInterface
{
    /**
     * @var array
     */
    protected $errorCodes = [1, 2, 3, 4, 11, 12, 14, 21, 30, 31, 32, 33, 34, 35, 36, 37, 41, 43, 50, 51, 110, 111, 112,
        401, 402, 403, 404, 405, 406, 407, 408, 501, 502, 503, 504, 506, 509, 601, 602, 605, 606, 607, 608, 609, 701,
        702, 703, 704, 705, 706, 707, 708, 709, 710, 711, 712, 713, 801, 802, 803, 804, 805, 806, 807, 808, 809, -100,
        200, -12, -113, -1, -2, -3];

    /**
     * @var string
     */
    protected $mobile;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $refUrl;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var int
     */
    protected $productType;

    /**
     * Set Mobile
     *
     * @param string $mobile
     * @return RequestPinInterface
     */
    public function mobile(string $mobile): RequestPinInterface
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get Mobile
     *
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * Set Email
     *
     * @param string $email
     * @return RequestPinInterface
     */
    public function email(string $email): RequestPinInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get Email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set RefUrl
     *
     * @param string $url
     * @return RequestPinInterface
     */
    public function refUrl(string $url): RequestPinInterface
    {
        $this->refUrl = $url;

        return $this;
    }

    /**
     * Get RefUrl
     *
     * @return string
     */
    public function getRefUrl(): string
    {
        return $this->refUrl;
    }

    /**
     * Set Quantity
     *
     * @param int $quantity
     * @return RequestPinInterface
     */
    public function quantity(int $quantity): RequestPinInterface
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get Quantity
     *
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Set ProductType
     *
     * @param int $productType
     * @return RequestPinInterface
     */
    public function productType(int $productType): RequestPinInterface
    {
        $this->productType = $productType;

        return $this;
    }

    /**
     * Get ProductType
     *
     * @return int
     */
    public function getProductType(): int
    {
        return $this->productType;
    }

    /**
     * Get results from response
     *
     * @return array
     * @throws ConnectionError
     */
    public function result(): array
    {
        if (empty($this->response)) {
            $this->call();
        }

        return $this->response['RequestPinsResult']['RequestsPinsResponse'];
    }

    /**
     * Get OrderID from results
     *
     * @return string
     * @throws ConnectionError
     */
    public function orderId(): string
    {
        return $this->result()['OrderID'];
    }

    /**
     * Get Serial from results
     *
     * @return string
     * @throws ConnectionError
     */
    public function serial(): string
    {
        return $this->result()['Serial'];
    }

    /**
     * Validate calling RequestPin response
     */
    public function validate() : void
    {
        if (is_null($this->requestId)) {
            throw InvalidConfigurationError::requestIdIsRequired();
        }

        if (is_null($this->quantity) || $this->quantity < 0) {
            throw InvalidConfigurationError::quantityIsRequiredAndShouldBeUnsigned();
        }

        if (is_null($this->productType)) {
            throw InvalidConfigurationError::productTypeIsRequired();
        }

        if (is_null($this->mobile)) {
            throw InvalidConfigurationError::mobileNumberIsRequired();
        }
    }

    /**
     * Get Request Response Status
     *
     * @return int
     */
    public function getResponseStatus(): int
    {
        return $this->response['RequestPinsResult']['RequestsPinsResponse']['Status'];
    }

    /**
     * Get Request Response Message
     *
     * @return string
     */
    public function getResponseMessage(): string
    {
        return $this->response['RequestPinsResult']['RequestsPinsResponse']['Message'];
    }

    /**
     * Call Soap endpoint
     *
     * @return mixed
     */
    public function callSoap()
    {
        return $this->client->RequestPins([
            'RequestID' => $this->requestId,
            'SitePassword' => $this->password,
            'Count' => $this->quantity,
            'ProductType' => $this->productType,
            'Mobile' => $this->mobile,
            'Email' => $this->email,
            'OptionalParam' => $this->optional,
            'RefURL' => $this->refUrl,
        ]);
    }

    /**
     * Validate Response Structure
     */
    public function responseStructureIsWrong(): bool
    {
        return ! array_key_exists('RequestPinsResult', $this->response)
            || ! array_key_exists('RequestsPinsResponse', $this->response['RequestPinsResult'])
            || ! array_key_exists('Status', $this->response['RequestPinsResult']['RequestsPinsResponse']);
    }
}