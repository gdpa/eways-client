<?php

namespace GDPA\EwaysClient;

use GDPA\EwaysClient\Exceptions\ConnectionError;
use GDPA\EwaysClient\Exceptions\InvalidConfigurationError;
use GDPA\EwaysClient\Interfaces\GetProductInterface;

class GetProduct implements GetProductInterface
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var \SoapClient
     */
    public $client;

    /**
     * @var array
     */
    protected $response;

    /**
     * @var array
     */
    protected $result;

    /**
     * GetProduct constructor.
     * @param string $username
     * @param null $client
     */
    public function __construct(string $username, $client = null)
    {
        $this->setUsername($username);
        $this->client = $client ?: new \SoapClient('http://core.eways.ir/WebService/Request.asmx?wsdl');
    }

    /**
     * Set username
     *
     * @param string $username
     * @return GetProductInterface
     */
    public function setUsername(string $username): GetProductInterface
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set Transaction ID
     *
     * @param string $id
     * @return GetProductInterface
     */
    public function transactionId(string $id): GetProductInterface
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
     * Call Eways GetProduct SOAP
     *
     * @return GetProductInterface
     */
    public function call(): GetProductInterface
    {
        if (is_null($this->transactionId)) {
            throw InvalidConfigurationError::transactionIdIsRequired();
        }

        $response = $this->client->GetProduct(['UserName' => $this->username, 'TransactionID' => $this->transactionId]);

        $response = json_encode($response, JSON_FORCE_OBJECT);

        $response = json_decode($response, true);

        $this->response = $response;

        if (! array_key_exists('GetProductResult', $this->response)
            || ! array_key_exists('Status', $this->response['GetProductResult'])) {
            throw ConnectionError::ewaysResponseError();
        }

        if ($this->response['GetProductResult']['Status'] != 0) {
            throw ConnectionError::ewaysError($this->response['GetProductResult']['Status'],
                $this->response['GetProductResult']['Message']);
        }

        return $this;
    }

    /**
     * Get response from calling GetProduct SOAP
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
     * Get results as array from calling GetProduct SOAP
     *
     * @return array
     */
    public function result(): array
    {
        if (empty($this->response)) {
            $this->call();
        }

        $result = $this->getResponse()['GetProductResult']['Result'];
        $xml = @simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);

        if (! $xml) {
            throw ConnectionError::soapError('Invalid xml returned for result.');
        }

        $this->result = json_decode(json_encode($xml, true), true);

        return $this->result;
    }

    /**
     * Get RequestID from calling GetProduct SOAP results
     *
     * @return string
     */
    public function requestId() : string
    {
        return $this->result()['Requirements']['Requirement'][7];
    }

    /**
     * Get products from results
     *
     * @return array
     */
    public function products() : array
    {
        return $this->result()['Products'];
    }

    /**
     * Get Products which have operator
     *
     * @return array
     */
    public function operators(): array
    {
        return $this->result()['Products']['Operator'];
    }

    /**
     * Find product by CID from operator products
     *
     * @param string $id
     * @return array
     */
    public function find(string $id): array
    {
        $product = [];

        $operators = $this->operators();
        foreach ($operators as $operator) {
            if (array_key_exists('PINs', $operator) && array_key_exists('PIN', $operator['PINs'])) {
                foreach ($operator['PINs']['PIN'] as $pin) {
                    if (is_array($pin) && array_key_exists('CID', $pin) && $pin['CID'] == $id) {
                        return $pin;
                    }
                }
            }
        }

        return $product;
    }
}