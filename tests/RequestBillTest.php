<?php

namespace GDPA\EwaysClient\Tests;

use GDPA\EwaysClient\Exceptions\ConnectionError;
use GDPA\EwaysClient\Exceptions\InvalidConfigurationError;
use GDPA\EwaysClient\RequestBill;
use GDPA\EwaysClient\Test\GetBillStatusResponse;
use GDPA\EwaysClient\Test\RequestBillResponse;
use PHPUnit\Framework\TestCase;

class RequestBillTest extends TestCase
{
    /** @test */
    public function it_set_request_soap_client()
    {
        $requestBill = new RequestBill('password');
        $this->assertEquals('password', $requestBill->getPassword());

        $this->assertEquals($requestBill->client->__getFunctions(),
            (new \SoapClient('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl'))->__getFunctions());
    }

    /** @test */
    public function it_set_and_get_password()
    {
        $requestBill = new RequestBill('password');
        $this->assertEquals('password', $requestBill->getPassword());
        $requestBill->setPassword('other-password');
        $this->assertEquals('other-password', $requestBill->getPassword());
    }

    /** @test */
    public function it_set_and_get_request_id()
    {
        $requestId = 'abc123';
        $requestBill = new RequestBill('password');
        $requestBill->requestId($requestId);
        $this->assertEquals($requestId, $requestBill->getRequestId());
    }

    /** @test */
    public function it_set_and_get_pay_id()
    {
        $payId = 'abc123';
        $requestBill = new RequestBill('password');
        $requestBill->payId($payId);
        $this->assertEquals($payId, $requestBill->getPayId());
    }

    /** @test */
    public function it_set_and_get_bill_id()
    {
        $billId = 'abc123';
        $requestBill = new RequestBill('password');
        $requestBill->billId($billId);
        $this->assertEquals($billId, $requestBill->getBillId());
    }

    /** @test */
    public function it_set_and_get_optionals()
    {
        $requestBill = new RequestBill('password');
        $optional = 'optional parameter';
        $requestBill->optional($optional);
        $this->assertEquals($optional, $requestBill->getOptional());
    }

    /** @test */
    public function it_throw_invalid_configuration_exception_if_request_id_is_not_set()
    {
        $this->expectException(InvalidConfigurationError::class);

        $billId = '123';
        $payId = '111';

        $requestBill = new RequestBill('password');
        $requestBill->billId($billId)->payId($payId)->call();
    }

    /** @test */
    public function it_throw_invalid_configuration_exception_if_bill_id_is_not_set()
    {
        $this->expectException(InvalidConfigurationError::class);

        $requestId = 'uuid';
        $payId = '111';

        $requestBill = new RequestBill('password');
        $requestBill->payId($payId)->requestId($requestId)->call();
    }

    /** @test */
    public function it_throw_invalid_configuration_exception_if_pay_id_is_not_set()
    {
        $this->expectException(InvalidConfigurationError::class);

        $requestId = 'uuid';
        $billId = '111';

        $requestBill = new RequestBill('password');
        $requestBill->billId($billId)->requestId($requestId)->call();
    }

    /** @test */
    public function it_should_call_request_bill_on_soap()
    {
        $password = 'password';
        $requestId = 'uuid';
        $billId = '123';
        $payId = '1234';
        $optional = 'optional';

        $response = RequestBillResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestBill')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'BillID' => $billId,
                'PayID' => $payId,
                'OptionalParam' => $optional,
            ])
            ->willReturn($response);

        $requestBill = new RequestBill($password, $soapClientMock);
        $requestBill->billId($billId)->payId($payId)->optional($optional)->requestId($requestId)->call();

        $this->assertEquals($response->RequestBillResult->ResultCode,
            $requestBill->getResponse()['RequestBillResult']['ResultCode']);
    }

    /** @test */
    public function it_should_throw_connection_exception_if_response_structure_changes()
    {
        $this->expectException(ConnectionError::class);
        $password = 'password';
        $requestId = 'uuid';
        $billId = '123';
        $payId = '1234';
        $optional = 'optional';

        $response = new \stdClass();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestBill')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'BillID' => $billId,
                'PayID' => $payId,
                'OptionalParam' => $optional,
            ])
            ->willReturn($response);

        $requestBill = new RequestBill($password, $soapClientMock);
        $requestBill->billId($billId)->payId($payId)->optional($optional)->requestId($requestId)->call();

        $response = new \stdClass();
        $response->RequestBillResult = new \stdClass();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestBill')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'BillID' => $billId,
                'PayID' => $payId,
                'OptionalParam' => $optional,
            ])
            ->willReturn($response);

        $requestBill = new RequestBill($password, $soapClientMock);
        $requestBill->billId($billId)->payId($payId)->optional($optional)->requestId($requestId)->call();

        $response = new \stdClass();
        $response->RequestBillResult = new \stdClass();
        $response->RequestBillResult = new \stdClass();
        $response->RequestBillResult->ResultCode = '114';
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestBill')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'BillID' => $billId,
                'PayID' => $payId,
                'OptionalParam' => $optional,
            ])
            ->willReturn($response);

        $requestBill = new RequestBill($password, $soapClientMock);
        $requestBill->billId($billId)->payId($payId)->optional($optional)->requestId($requestId)->call();

        $response = new \stdClass();
        $response->RequestBillResult = new \stdClass();
        $response->RequestBillResult = new \stdClass();
        $response->RequestBillResult->Message = 'درخواست با موفقیت انجام شد';
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestBill')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'BillID' => $billId,
                'PayID' => $payId,
                'OptionalParam' => $optional,
            ])
            ->willReturn($response);

        $requestBill = new RequestBill($password, $soapClientMock);
        $requestBill->billId($billId)->payId($payId)->optional($optional)->requestId($requestId)->call();
    }

    /** @test */
    public function it_should_throw_connection_exception_if_response_code_is_in_error_codes()
    {
        $this->expectException(ConnectionError::class);
        $password = 'password';
        $requestId = 'uuid';
        $billId = '123';
        $payId = '1234';
        $optional = 'optional';

        $response = RequestBillResponse::failed();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestBill')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'BillID' => $billId,
                'PayID' => $payId,
                'OptionalParam' => $optional,
            ])
            ->willReturn($response);

        $requestBill = new RequestBill($password, $soapClientMock);
        $requestBill->billId($billId)->payId($payId)->optional($optional)->requestId($requestId)->call();
    }

    /** @test */
    public function it_can_return_result_from_request_bill_soap_call()
    {
        $password = 'password';
        $requestId = 'uuid';
        $billId = '123';
        $payId = '1234';
        $optional = 'optional';

        $response = RequestBillResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestBill')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'BillID' => $billId,
                'PayID' => $payId,
                'OptionalParam' => $optional,
            ])
            ->willReturn($response);

        $requestBill = new RequestBill($password, $soapClientMock);
        $results = $requestBill->billId($billId)->payId($payId)->optional($optional)->requestId($requestId)->call()->result();

        $this->assertEquals($results['ResultCode'],
            RequestBillResponse::success()->RequestBillResult->ResultCode);
        $this->assertEquals($results['Message'],
            RequestBillResponse::success()->RequestBillResult->Message);
    }

    /** @test */
    public function it_call_soap_request_bill_on_result_if_response_is_empty()
    {
        $password = 'password';
        $requestId = 'uuid';
        $billId = '123';
        $payId = '1234';
        $optional = 'optional';

        $response = RequestBillResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestBill')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'BillID' => $billId,
                'PayID' => $payId,
                'OptionalParam' => $optional,
            ])
            ->willReturn($response);

        $requestBill = new RequestBill($password, $soapClientMock);
        $results = $requestBill->billId($billId)->payId($payId)->optional($optional)->requestId($requestId)->result();

        $this->assertEquals($results['ResultCode'],
            RequestBillResponse::success()->RequestBillResult->ResultCode);
        $this->assertEquals($results['Message'],
            RequestBillResponse::success()->RequestBillResult->Message);
    }
}