<?php

namespace GDPA\EwaysClient;

use GDPA\EwaysClient\Exceptions\ConnectionError;
use GDPA\EwaysClient\Exceptions\InvalidConfigurationError;

class GetStatus implements GetStatusInterface
{
    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var string
     */
    protected $requestId;

    /**
     * @var \SoapClient
     */
    public $client;

    /**
     * @var array
     */
    protected $response;

    /**
     * GetStatus constructor.
     * @param null $client
     */
    public function __construct($client = null)
    {
        $this->client = $client ?: new \SoapClient('http://core.eways.ir/WebService/Request.asmx?wsdl');
    }

    /**
     * Set Transaction ID
     *
     * @param string $id
     * @return GetStatusInterface
     */
    public function transactionId(string $id): GetStatusInterface
    {
        $this->transactionId = $id;

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
     * Set Request ID
     *
     * @param string $id
     * @return GetStatusInterface
     */
    public function requestId(string $id): GetStatusInterface
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
     * Call GetStatus SOAP on eways
     *
     * @return GetStatusInterface
     */
    public function call(): GetStatusInterface
    {
        if (is_null($this->transactionId)) {
            throw InvalidConfigurationError::transactionIdIsRequired();
        }

        if (is_null($this->requestId)) {
            throw InvalidConfigurationError::requestIdIsRequired();
        }

        $response = $this->client->GetStatus(['TransactionID' => $this->transactionId, 'RequestID' => $this->requestId]);

        $response = json_encode($response, JSON_FORCE_OBJECT);

        $response = json_decode($response, true);

        $this->response = $response;

        if (! array_key_exists('GetStatusResult', $this->response)
            || ! array_key_exists('ResultGetStatus', $this->response['GetStatusResult'])
            || ! array_key_exists('Status', $this->response['GetStatusResult']['ResultGetStatus'])) {
            throw ConnectionError::ewaysResponseError();
        }

        return $this;
    }

    /**
     * Get responses from calling GetStatus SOAP
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

        return $this->response['GetStatusResult']['ResultGetStatus'];
    }

    /**
     * Get PIN from results
     *
     * @return string
     */
    public function pin(): string
    {
        return $this->result()['Pin'];
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
     * Get PaymentID from results
     *
     * @return string
     */
    public function paymentId(): string
    {
        return $this->result()['PaymentID'];
    }

    /**
     * Get Authority from results
     *
     * @return string
     */
    public function authority(): string
    {
        return $this->result()['Authority'];
    }
}