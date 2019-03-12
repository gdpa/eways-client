<?php

namespace GDPA\EwaysClient;

use GDPA\EwaysClient\Exceptions\ConnectionError;
use GDPA\EwaysClient\Exceptions\InvalidConfigurationError;
use GDPA\EwaysClient\Interfaces\RequestPinInterface;

class RequestPin implements RequestPinInterface
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
    protected $password;

    /**
     * @var string
     */
    protected $requestId;

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
     * @var string
     */
    protected $optional;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var int
     */
    protected $productType;

    /**
     * @var \SoapClient
     */
    public $client;

    /**
     * @var array
     */
    protected $response;

    /**
     * RequestPin constructor.
     * @param string $password
     * @param null $client
     */
    public function __construct(string $password, $client = null)
    {
        $this->setPassword($password);
        $this->client = $client ?: new \SoapClient('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
    }

    /**
     * Set password
     *
     * @param string $password
     * @return RequestPinInterface
     */
    public function setPassword(string $password): RequestPinInterface
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set Request ID
     *
     * @param string $id
     * @return RequestPinInterface
     */
    public function requestId(string $id): RequestPinInterface
    {
        $this->requestId = $id;

        return $this;
    }

    /**
     * Get Request ID
     *
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

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
     * Set OptionalParam
     *
     * @param $optional
     * @return RequestPinInterface
     */
    public function optional($optional): RequestPinInterface
    {
        $this->optional = $optional;

        return $this;
    }

    /**
     * Get OptionalParam
     * @return mixed
     */
    public function getOptional()
    {
        return $this->optional;
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
     * Call RequestPin SOAP
     *
     * @return RequestPinInterface
     */
    public function call(): RequestPinInterface
    {
        $this->validate();

        $response = $this->client->RequestPins([
            'RequestID' => $this->requestId,
            'SitePassword' => $this->password,
            'Count' => $this->quantity,
            'ProductType' => $this->productType,
            'Mobile' => $this->mobile,
            'Email' => $this->email,
            'OptionalParam' => $this->optional,
            'RefURL' => $this->refUrl,
        ]);

        $response = json_encode($response, JSON_FORCE_OBJECT);

        $response = json_decode($response, true);

        $this->response = $response;

        if (! array_key_exists('RequestPinsResult', $this->response)
            || ! array_key_exists('RequestsPinsResponse', $this->response['RequestPinsResult'])
            || ! array_key_exists('Status', $this->response['RequestPinsResult']['RequestsPinsResponse'])) {
            throw ConnectionError::ewaysResponseError();
        }

        if (in_array($this->response['RequestPinsResult']['RequestsPinsResponse']['Status'], $this->errorCodes)) {
            throw ConnectionError::ewaysError($this->response['RequestPinsResult']['RequestsPinsResponse']['Status'],
                $this->response['RequestPinsResult']['RequestsPinsResponse']['Message']);
        }

        return $this;
    }

    /**
     * Get response from calling SOAP
     *
     * @return array
     */
    public function getResponse(): array
    {
        if (empty($this->response)) {
            $this->call();
        }

        return $this->response;
    }

    /**
     * Get results from response
     *
     * @return array
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
     */
    public function orderId(): string
    {
        return $this->result()['OrderID'];
    }

    /**
     * Get Serial from results
     *
     * @return string
     */
    public function serial(): string
    {
        return $this->result()['Serial'];
    }

    /**
     * Validate calling RequestPin response
     */
    protected function validate()
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
}