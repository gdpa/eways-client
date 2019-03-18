<?php

namespace GDPA\EwaysClient\Tests;

use GDPA\EwaysClient\Exceptions\ConnectionError;
use GDPA\EwaysClient\Exceptions\InvalidConfigurationError;
use GDPA\EwaysClient\RequestPin;
use GDPA\EwaysClient\Test\GetProductResponse;
use GDPA\EwaysClient\Test\RequestPinResponse;
use PHPUnit\Framework\TestCase;

class RequestPinTest extends TestCase
{
    /** @test */
    public function it_set_request_soap_client()
    {
        $requestPin = new RequestPin('password');
        $this->assertEquals('password', $requestPin->getPassword());

        $this->assertEquals($requestPin->client->__getFunctions(),
            (new \SoapClient('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl'))->__getFunctions());
    }

    /** @test */
    public function it_set_and_get_password()
    {
        $requestPin = new RequestPin('password');
        $this->assertEquals('password', $requestPin->getPassword());
        $requestPin->setPassword('other-password');
        $this->assertEquals('other-password', $requestPin->getPassword());
    }

    /** @test */
    public function it_set_and_get_request_id()
    {
        $requestId = 'abc123';
        $requestPin = new RequestPin('password');
        $requestPin->requestId($requestId);
        $this->assertEquals($requestId, $requestPin->getRequestId());
    }

    /** @test */
    public function it_set_and_get_mobile_number()
    {
        $requestPin = new RequestPin('password');
        $mobile = '+989999999999';
        $requestPin->mobile($mobile);
        $this->assertEquals($mobile, $requestPin->getMobile());
    }

    /** @test */
    public function it_set_and_get_email()
    {
        $requestPin = new RequestPin('password');
        $email = 'info@example.com';
        $requestPin->email($email);
        $this->assertEquals($email, $requestPin->getEmail());
    }

    /** @test */
    public function it_set_and_get_ref_url()
    {
        $requestPin = new RequestPin('password');
        $url = 'http://example.com';
        $requestPin->refUrl($url);
        $this->assertEquals($url, $requestPin->getRefUrl());
    }

    /** @test */
    public function it_set_and_get_optionals()
    {
        $requestPin = new RequestPin('password');
        $optional = 'optional parameter';
        $requestPin->optional($optional);
        $this->assertEquals($optional, $requestPin->getOptional());
    }

    /** @test */
    public function it_set_and_get_quantity()
    {
        $requestPin = new RequestPin('password');
        $quantity = 2;
        $requestPin->quantity($quantity);
        $this->assertEquals($quantity, $requestPin->getQuantity());
    }

    /** @test */
    public function it_set_and_get_product_type()
    {
        $requestPin = new RequestPin('password');
        $productType = 1;
        $requestPin->productType($productType);
        $this->assertEquals($productType, $requestPin->getProductType());
    }

    /** @test */
    public function it_should_call_request_pin_on_soap()
    {
        $password = 'password';
        $requestId = 'uuid';
        $quantity = 2;
        $productId = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';
        $response = RequestPinResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestPins')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'Count' => $quantity,
                'ProductType' => $productId,
                'Mobile' => $mobile,
                'Email' => $email,
                'OptionalParam' => $optional,
                'RefURL' => $refUrl,
            ])
            ->willReturn($response);

        $requestPin = new RequestPin($password, $soapClientMock);
        $requestPin->requestId($requestId)->quantity($quantity)->productType($productId)->mobile($mobile)->email($email)
            ->optional($optional)->refUrl($refUrl)->call();

        $this->assertEquals($response->RequestPinsResult->RequestsPinsResponse->Status,
            $requestPin->getResponse()['RequestPinsResult']['RequestsPinsResponse']['Status']);
    }

    /** @test */
    public function it_throw_invalid_configuration_exception_if_request_id_is_not_set()
    {
        $this->expectException(InvalidConfigurationError::class);

        $quantity = 2;
        $productId = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';

        $requestPin = new RequestPin('password');
        $requestPin->quantity($quantity)->productType($productId)->mobile($mobile)->email($email)
            ->optional($optional)->refUrl($refUrl)->call();
    }

    /** @test */
    public function it_throw_invalid_configuration_exception_if_quantity_is_not_set()
    {
        $this->expectException(InvalidConfigurationError::class);

        $requestId = 'uuid';
        $productId = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';

        $requestPin = new RequestPin('password');
        $requestPin->requestId($requestId)->productType($productId)->mobile($mobile)->email($email)
            ->optional($optional)->refUrl($refUrl)->call();
    }

    /** @test */
    public function it_throw_invalid_configuration_exception_if_product_id_is_not_set()
    {
        $this->expectException(InvalidConfigurationError::class);

        $requestId = 'uuid';
        $quantity = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';

        $requestPin = new RequestPin('password');
        $requestPin->requestId($requestId)->quantity($quantity)->mobile($mobile)->email($email)
            ->optional($optional)->refUrl($refUrl)->call();
    }

    /** @test */
    public function it_throw_invalid_configuration_exception_if_mobile_number_is_not_set()
    {
        $this->expectException(InvalidConfigurationError::class);

        $requestId = 'uuid';
        $quantity = 10;
        $productId = 10;
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';

        $requestPin = new RequestPin('password');
        $requestPin->requestId($requestId)->quantity($quantity)->productType($productId)->email($email)
            ->optional($optional)->refUrl($refUrl)->call();
    }

    /** @test */
    public function it_throw_exception_if_status_is_in_error_codes()
    {
        $this->expectException(ConnectionError::class);
        $password = 'password';
        $requestId = 'uuid';
        $quantity = 2;
        $productId = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';
        $response = RequestPinResponse::credential();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestPins')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'Count' => $quantity,
                'ProductType' => $productId,
                'Mobile' => $mobile,
                'Email' => $email,
                'OptionalParam' => $optional,
                'RefURL' => $refUrl,
            ])
            ->willReturn($response);

        $requestPin = new RequestPin($password, $soapClientMock);
        $requestPin->requestId($requestId)->quantity($quantity)->productType($productId)->mobile($mobile)->email($email)
            ->optional($optional)->refUrl($refUrl)->call();

        $this->assertEquals(200,
            $requestPin->getResponse()['RequestPinsResult']['RequestsPinsResponse']['Status']);
    }

    /** @test */
    public function it_can_return_result_from_request_pin_soap_call()
    {
        $password = 'password';
        $requestId = 'uuid';
        $quantity = 2;
        $productId = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';
        $response = RequestPinResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestPins')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'Count' => $quantity,
                'ProductType' => $productId,
                'Mobile' => $mobile,
                'Email' => $email,
                'OptionalParam' => $optional,
                'RefURL' => $refUrl,
            ])
            ->willReturn($response);

        $requestPin = new RequestPin($password, $soapClientMock);
        $results = $requestPin->requestId($requestId)->quantity($quantity)->productType($productId)->mobile($mobile)->email($email)
            ->optional($optional)->refUrl($refUrl)->call()->result();
        $this->assertEquals($results['Status'],
            RequestPinResponse::success()->RequestPinsResult->RequestsPinsResponse->Status);
        $this->assertEquals($results['Message'],
            RequestPinResponse::success()->RequestPinsResult->RequestsPinsResponse->Message);
        $this->assertEquals($results['OrderID'],
            RequestPinResponse::success()->RequestPinsResult->RequestsPinsResponse->OrderID);

    }

    /** @test */
    public function it_can_return_order_id_from_request_pin_result()
    {
        $password = 'password';
        $requestId = 'uuid';
        $quantity = 2;
        $productId = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';
        $response = RequestPinResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestPins')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'Count' => $quantity,
                'ProductType' => $productId,
                'Mobile' => $mobile,
                'Email' => $email,
                'OptionalParam' => $optional,
                'RefURL' => $refUrl,
            ])
            ->willReturn($response);

        $requestPin = new RequestPin($password, $soapClientMock);
        $orderId = $requestPin->requestId($requestId)->quantity($quantity)->productType($productId)->mobile($mobile)->email($email)
            ->optional($optional)->refUrl($refUrl)->orderId();

        $this->assertEquals($orderId, RequestPinResponse::success()->RequestPinsResult->RequestsPinsResponse->OrderID);
    }

    /** @test */
    public function it_can_return_serial_from_request_pin_result()
    {
        $password = 'password';
        $requestId = 'uuid';
        $quantity = 2;
        $productId = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';
        $response = RequestPinResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestPins')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'Count' => $quantity,
                'ProductType' => $productId,
                'Mobile' => $mobile,
                'Email' => $email,
                'OptionalParam' => $optional,
                'RefURL' => $refUrl,
            ])
            ->willReturn($response);

        $requestPin = new RequestPin($password, $soapClientMock);
        $serial = $requestPin->requestId($requestId)->quantity($quantity)->productType($productId)->mobile($mobile)->email($email)
            ->optional($optional)->refUrl($refUrl)->serial();

        $this->assertEquals($serial, RequestPinResponse::success()->RequestPinsResult->RequestsPinsResponse->Serial);
    }

    /** @test */
    public function it_throw_exception_if_response_is_not_request_pin_response()
    {
        $this->expectException(ConnectionError::class);
        $password = 'password';
        $requestId = 'uuid';
        $quantity = 2;
        $productId = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';
        $response = GetProductResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestPins')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'Count' => $quantity,
                'ProductType' => $productId,
                'Mobile' => $mobile,
                'Email' => $email,
                'OptionalParam' => $optional,
                'RefURL' => $refUrl,
            ])
            ->willReturn($response);

        $requestPin = new RequestPin($password, $soapClientMock);
        $requestPin->requestId($requestId)->quantity($quantity)->productType($productId)->mobile($mobile)->email($email)
            ->optional($optional)->refUrl($refUrl)->call();
    }

    /** @test */
    public function get_response_calls_request_pin_if_there_is_no_response()
    {
        $password = 'password';
        $requestId = 'uuid';
        $quantity = 2;
        $productId = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';
        $response = RequestPinResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/BackEndRequest.asmx?wsdl');
        $soapClientMock
            ->method('RequestPins')
            ->with([
                'RequestID' => $requestId,
                'SitePassword' => $password,
                'Count' => $quantity,
                'ProductType' => $productId,
                'Mobile' => $mobile,
                'Email' => $email,
                'OptionalParam' => $optional,
                'RefURL' => $refUrl,
            ])
            ->willReturn($response);

        $requestPin = new RequestPin($password, $soapClientMock);
        $results = $requestPin->requestId($requestId)->quantity($quantity)->productType($productId)->mobile($mobile)->email($email)
            ->optional($optional)->refUrl($refUrl)->getResponse();

        $this->assertArrayHasKey('RequestPinsResult', $results);
    }
}