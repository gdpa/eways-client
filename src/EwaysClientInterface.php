<?php

namespace GDPA\EwaysClient;

interface EwaysClientInterface
{
    /**
     * Set Transaction ID on GetStatus and GetProduct clients
     *
     * @param string $id
     * @return EwaysClientInterface
     */
    public function transactionId(string $id): EwaysClientInterface;

    /**
     * Get Transaction ID
     *
     * @return string
     */
    public function getTransactionId() : string;

    /**
     * Set Request ID on RequestPin and GetStatus clients
     *
     * @param string $requestId
     * @return EwaysClientInterface
     */
    public function requestId(string $requestId) : EwaysClientInterface;

    /**
     * Get Request ID
     *
     * @return string
     */
    public function getRequestId() : string;

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
    public function orderPin(string $transactionId, string $productId, string $mobile, string $quantity, ?string $email = '', $optional = '', ?string $refUrl = '') : array;

    /**
     * Access to GetProduct client
     *
     * @return GetProductInterface
     */
    public function getProductClient() : GetProductInterface;

    /**
     * Access to RequestPin client
     * @return RequestPinInterface
     */
    public function requestPinClient() : RequestPinInterface;

    /**
     * Access to GetStatus client
     *
     * @return GetStatusInterface
     */
    public function getStatusClient() : GetStatusInterface;

    /**
     * Set product on client
     *
     * @param array $product
     * @return EwaysClientInterface
     */
    public function setProduct(array $product) : EwaysClientInterface;

    /**
     * Get product from client
     *
     * @return array
     */
    public function getProduct() : array;

    /**
     * Set requestResponse
     *
     * @param array $response
     * @return EwaysClientInterface
     */
    public function setRequestResponse(array $response) : EwaysClientInterface;

    /**
     * Get requestResponse
     *
     * @return array
     */
    public function getRequestResponse() : array;

    /**
     * Get status from Get status client
     *
     * @param string $transactionId
     * @param string $requestID
     * @return array
     */
    public function getStatus(string $transactionId, string $requestID) : array;
}