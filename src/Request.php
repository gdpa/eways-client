<?php

namespace GDPA\EwaysClient;

use GDPA\EwaysClient\Exceptions\ConnectionError;
use GDPA\EwaysClient\Interfaces\RequestInterface;

abstract class Request implements RequestInterface
{
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
    protected $optional;

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
    protected $errorCodes = [];

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
     * @return RequestInterface
     */
    public function setPassword(string $password): RequestInterface
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
     * @return RequestInterface
     */
    public function requestId(string $id): RequestInterface
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
     * Set OptionalParam
     *
     * @param $optional
     * @return RequestInterface
     */
    public function optional($optional): RequestInterface
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
     * Get response from calling SOAP
     *
     * @return array
     * @throws ConnectionError
     */
    public function getResponse(): array
    {
        if (empty($this->response)) {
            $this->call();
        }

        return $this->response;
    }

    /**
     * Call RequestPin SOAP
     *
     * @return RequestInterface
     * @throws ConnectionError
     */
    public function call(): RequestInterface
    {
        $this->validate();

        $response = $this->callSoap();

        $response = json_encode($response, JSON_FORCE_OBJECT);

        $response = json_decode($response, true);

        $this->response = $response;

        if ($this->responseStructureIsWrong()) {
            throw ConnectionError::ewaysResponseError();
        }

        if (in_array($this->getResponseStatus(), $this->errorCodes)) {
            throw ConnectionError::ewaysError($this->getResponseStatus(), $this->getResponseMessage());
        }

        return $this;
    }

    /**
     * Validate if parameters are set correctly
     *
     * @throws ConnectionError
     * @return void
     */
    abstract public function validate() : void;

    /**
     * Call Soap endpoint
     *
     * @return mixed
     */
    abstract public function callSoap();

    /**
     * Validate Response Structure
     */
    abstract public function responseStructureIsWrong() : bool;

    /**
     * Get Response Status
     *
     * @return int
     */
    abstract public function getResponseStatus(): int;

    /**
     * Get Response Message
     *
     * @return string
     */
    abstract public function getResponseMessage(): string;
}