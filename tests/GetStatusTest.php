<?php

namespace GDPA\EwaysClient\Tests;

use GDPA\EwaysClient\Exceptions\ConnectionError;
use GDPA\EwaysClient\Exceptions\InvalidConfigurationError;
use GDPA\EwaysClient\GetStatus;
use GDPA\EwaysClient\Test\GetStatusResponse;
use GDPA\EwaysClient\Test\RequestPinResponse;
use PHPUnit\Framework\TestCase;

class GetStatusTest extends TestCase
{
    /** @test */
    public function it_set_and_get_transaction_id()
    {
        $transactionId = 1;
        $getStatus = new GetStatus();
        $getStatus->transactionId($transactionId);
        $this->assertEquals($transactionId, $getStatus->getTransactionId());
    }

    /** @test */
    public function it_set_and_get_request_id()
    {
        $requestId = 'abcd';
        $getStatus = new GetStatus();
        $getStatus->requestId($requestId);
        $this->assertEquals($requestId, $getStatus->getRequestId());
    }

    /** @test */
    public function it_set_soap_client()
    {
        $getStatus = new GetStatus();

        $this->assertEquals($getStatus->client->__getFunctions(),
            (new \SoapClient('http://core.eways.ir/WebService/Request.asmx?wsdl'))->__getFunctions());
    }

    /** @test */
    public function it_should_call_get_status_on_soap()
    {
        $transactionId = '1';
        $requestId = 'uuid';
        $response = GetStatusResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetStatus')
            ->with(['TransactionID' => $transactionId, 'RequestID' => $requestId])
            ->willReturn($response);

        $getStatus = new GetStatus($soapClientMock);
        $getStatus->transactionId($transactionId)->requestId($requestId)->call();
        $this->assertEquals($response->GetStatusResult->ResultGetStatus->Status,
            $getStatus->getResponse()['GetStatusResult']['ResultGetStatus']['Status']);
    }

    /** @test */
    public function it_throw_invalid_configuration_exception_if_transaction_id_is_not_set()
    {
        $this->expectException(InvalidConfigurationError::class);
        $getStatus = new GetStatus();
        $getStatus->requestId('uuid')->call();
    }

    /** @test */
    public function it_throw_invalid_configuration_exception_if_request_id_is_not_set()
    {
        $this->expectException(InvalidConfigurationError::class);
        $getStatus = new GetStatus();
        $getStatus->transactionId('123')->call();
    }

    /** @test */
    public function it_can_return_result_from_get_status_soap_call()
    {
        $transactionId = '1';
        $requestId = 'uuid';
        $response = GetStatusResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetStatus')
            ->with(['TransactionID' => $transactionId, 'RequestID' => $requestId])
            ->willReturn($response);

        $getStatus = new GetStatus($soapClientMock);
        $results = $getStatus->transactionId($transactionId)->requestId($requestId)->result();
        $this->assertEquals($results['Status'],
            GetStatusResponse::success()->GetStatusResult->ResultGetStatus->Status);
        $this->assertEquals($results['Message'],
            GetStatusResponse::success()->GetStatusResult->ResultGetStatus->Message);
    }

    /** @test */
    public function it_can_return_pin_from_get_status_result()
    {
        $transactionId = '1';
        $requestId = 'uuid';
        $response = GetStatusResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetStatus')
            ->with(['TransactionID' => $transactionId, 'RequestID' => $requestId])
            ->willReturn($response);

        $getStatus = new GetStatus($soapClientMock);
        $pin = $getStatus->transactionId($transactionId)->requestId($requestId)->pin();
        $this->assertEquals($pin, GetStatusResponse::success()->GetStatusResult->ResultGetStatus->Pin);
    }

    /** @test */
    public function it_can_return_serial_from_get_status_result()
    {
        $transactionId = '1';
        $requestId = 'uuid';
        $response = GetStatusResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetStatus')
            ->with(['TransactionID' => $transactionId, 'RequestID' => $requestId])
            ->willReturn($response);

        $getStatus = new GetStatus($soapClientMock);
        $serial = $getStatus->transactionId($transactionId)->requestId($requestId)->serial();
        $this->assertEquals($serial, GetStatusResponse::success()->GetStatusResult->ResultGetStatus->Serial);
    }

    /** @test */
    public function it_can_return_payment_id_from_get_status_result()
    {
        $transactionId = '1';
        $requestId = 'uuid';
        $response = GetStatusResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetStatus')
            ->with(['TransactionID' => $transactionId, 'RequestID' => $requestId])
            ->willReturn($response);

        $getStatus = new GetStatus($soapClientMock);
        $paymentId = $getStatus->transactionId($transactionId)->requestId($requestId)->paymentId();
        $this->assertEquals($paymentId, GetStatusResponse::success()->GetStatusResult->ResultGetStatus->PaymentID);
    }

    /** @test */
    public function it_can_return_authority_from_get_status_result()
    {
        $transactionId = '1';
        $requestId = 'uuid';
        $response = GetStatusResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetStatus')
            ->with(['TransactionID' => $transactionId, 'RequestID' => $requestId])
            ->willReturn($response);

        $getStatus = new GetStatus($soapClientMock);
        $authority = $getStatus->transactionId($transactionId)->requestId($requestId)->authority();
        $this->assertEquals($authority, GetStatusResponse::success()->GetStatusResult->ResultGetStatus->Authority);
    }

    /** @test */
    public function it_throw_exception_if_response_is_not_get_status_response()
    {
        $this->expectException(ConnectionError::class);

        $transactionId = '1';
        $requestId = 'uuid';
        $response = RequestPinResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetStatus')
            ->with(['TransactionID' => $transactionId, 'RequestID' => $requestId])
            ->willReturn($response);

        $getStatus = new GetStatus($soapClientMock);
        $getStatus->transactionId($transactionId)->requestId($requestId)->call();
    }

    /** @test */
    public function get_response_call_get_status_call_function_if_there_is_no_response()
    {
        $transactionId = '1';
        $requestId = 'uuid';
        $response = GetStatusResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetStatus')
            ->with(['TransactionID' => $transactionId, 'RequestID' => $requestId])
            ->willReturn($response);

        $getStatus = new GetStatus($soapClientMock);
        $results = $getStatus->transactionId($transactionId)->requestId($requestId)->getResponse();
        $this->assertArrayHasKey('GetStatusResult', $results);
    }
}