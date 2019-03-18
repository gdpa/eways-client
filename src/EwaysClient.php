<?php

namespace GDPA\EwaysClient;

use GDPA\EwaysClient\Exceptions\InvalidConfigurationError;
use GDPA\EwaysClient\Interfaces\EwaysClientInterface;
use GDPA\EwaysClient\Interfaces\GetProductInterface;
use GDPA\EwaysClient\Interfaces\GetStatusInterface;
use GDPA\EwaysClient\Interfaces\RequestBillInterface;
use GDPA\EwaysClient\Interfaces\RequestPinInterface;

class EwaysClient implements EwaysClientInterface
{
    /**
     * @var GetProductInterface
     */
    protected $getProductClient;

    /**
     * @var RequestPinInterface
     */
    protected $requestPinClient;

    /**
     * @var GetStatusInterface
     */
    protected $getStatusClient;

    /**
     * @var RequestBillInterface
     */
    protected $requestBillClient;

    /**
     * @var string
     */
    protected $transactionId = '';

    /**
     * @var string
     */
    protected $requestId = '';

    /**
     * @var array
     */
    protected $product = [];

    /**
     * @var array
     */
    protected $requestResponse = [];

    /**
     * Client constructor.
     * @param string $username
     * @param string $password
     * @param GetProductInterface|null $getProduct
     * @param RequestPinInterface|null $requestPin
     * @param GetStatusInterface|null $getStatus
     * @param RequestBillInterface|null $requestBill
     */
    public function __construct(string $username, string $password, ?GetProductInterface $getProduct = null,
        ?RequestPinInterface $requestPin = null, ?GetStatusInterface $getStatus = null, ?RequestBillInterface $requestBill = null)
    {
        $this->getProductClient = $getProduct ?: new GetProduct($username);
        $this->requestPinClient = $requestPin ?: new RequestPin($password);
        $this->getStatusClient = $getStatus ?: new GetStatus();
        $this->requestBillClient = $requestBill ?: new RequestBill($password);
    }

    /**
     * Set Transaction ID on GetStatus and GetProduct clients
     *
     * @param string $id
     * @return EwaysClientInterface
     */
    public function transactionId(string $id): EwaysClientInterface
    {
        $this->transactionId = $id;
        $this->getProductClient->transactionId($this->transactionId);
        $this->getStatusClient->transactionId($this->transactionId);

        return $this;
    }

    /**
     * Get Transaction ID
     *
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * Set Request ID on RequestPin and GetStatus clients
     *
     * @param string $requestId
     * @return EwaysClientInterface
     */
    public function requestId(string $requestId): EwaysClientInterface
    {
        $this->requestId = $requestId;
        $this->requestPinClient->requestId($this->requestId);
        $this->getStatusClient->requestId($this->requestId);
        $this->requestBillClient->requestId($this->requestId);

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
     * Order a product from GetProduct products list
     *
     * @param string $transactionId
     * @param string $productId
     * @param string $mobile
     * @param string $quantity
     * @param string|null $email
     * @param string $optional
     * @param string|null $refUrl
     * @return array
     */
    public function orderPin(string $transactionId, string $productId, string $mobile, string $quantity, ?string $email = '', $optional = '', ?string $refUrl = ''): array
    {
        $this->transactionId($transactionId);

        $this->getProductClient->result();

        $this->setProduct($this->getProductClient->find($productId));

        if (empty($this->product)) {
            throw InvalidConfigurationError::productNotFound($productId);
        }

        $this->requestId($this->getProductClient->requestId());

        $this->setRequestResponse($this->requestPinClient->quantity($quantity)->productType($productId)->mobile($mobile)->email($email)
            ->optional($optional)->refUrl($refUrl)->result());

        return $this->getStatusClient->result();
    }

    /**
     * Access to GetProduct client
     *
     * @return GetProductInterface
     */
    public function getProductClient(): GetProductInterface
    {
        return $this->getProductClient;
    }

    /**
     * Access to RequestPin client
     * @return RequestPinInterface
     */
    public function requestPinClient(): RequestPinInterface
    {
        return $this->requestPinClient;
    }

    /**
     * Access to GetStatus client
     *
     * @return GetStatusInterface
     */
    public function getStatusClient(): GetStatusInterface
    {
        return $this->getStatusClient;
    }

    /**
     * Access to RequestBill client
     * @return RequestBillInterface
     */
    public function requestBillClient(): RequestBillInterface
    {
        return $this->requestBillClient;
    }

    /**
     * Set product on client
     *
     * @param array $product
     * @return EwaysClientInterface
     */
    public function setProduct(array $product): EwaysClientInterface
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product from client
     *
     * @return array
     */
    public function getProduct(): array
    {
        return $this->product;
    }

    /**
     * Set requestResponse
     *
     * @param array $response
     * @return EwaysClientInterface
     */
    public function setRequestResponse(array $response): EwaysClientInterface
    {
        $this->requestResponse = $response;

        return $this;
    }

    /**
     * Get requestResponse
     *
     * @return array
     */
    public function getRequestResponse(): array
    {
        return $this->requestResponse;
    }

    /**
     * Get status from Get status client
     *
     * @param string $transactionId
     * @param string $requestID
     * @return array
     */
    public function getStatus(string $transactionId, string $requestID): array
    {
        $this->transactionId($transactionId);
        $this->requestId($requestID);

        return $this->getStatusClient->result();
    }

    /**
     * Pay bill
     *
     * @param string $transactionId
     * @param string $billId
     * @param string $payId
     * @param string $optional
     * @return array
     */
    public function payBill(string $transactionId, string $billId, string $payId, $optional = ''): array
    {
        $this->transactionId($transactionId);

        $this->getProductClient->result();

        $this->requestId($this->getProductClient->requestId());

        $this->setRequestResponse($this->requestBillClient->billId($billId)->payId($payId)->optional($optional)->result());

        return $this->requestResponse;
    }
}