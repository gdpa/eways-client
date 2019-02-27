<?php

namespace GDPA\EwaysClient\Tests;

use GDPA\EwaysClient\Exceptions\ConnectionError;
use GDPA\EwaysClient\Exceptions\InvalidConfigurationError;
use GDPA\EwaysClient\GetProduct;
use GDPA\EwaysClient\Test\GetProductResponse;
use GDPA\EwaysClient\Test\GetStatusResponse;
use PHPUnit\Framework\TestCase;

class GetProductTest extends TestCase
{
    /** @test */
    public function it_set_soap_client()
    {
        $getProduct = new GetProduct('username');
        $this->assertEquals('username', $getProduct->getUsername());

        $this->assertEquals($getProduct->client->__getFunctions(),
            (new \SoapClient('http://core.eways.ir/WebService/Request.asmx?wsdl'))->__getFunctions());
    }

    /** @test */
    public function it_set_and_get_username()
    {
        $getProduct = new GetProduct('username');
        $this->assertEquals('username', $getProduct->getUsername());
        $getProduct->setUsername('other-username');
        $this->assertEquals('other-username', $getProduct->getUsername());
    }

    /** @test */
    public function it_set_and_get_transaction_id()
    {
        $transactionId = 1;
        $getProduct = new GetProduct('username');
        $getProduct->transactionId($transactionId);
        $this->assertEquals($transactionId, $getProduct->getTransactionId());
    }

    /** @test */
    public function it_should_call_get_product_on_soap()
    {
        $username = 'username';
        $transactionId = '1';
        $response = GetProductResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetProduct')
            ->with(['UserName' => $username, 'TransactionID' => $transactionId])
            ->willReturn($response);

        $getProduct = new GetProduct($username, $soapClientMock);
        $getProduct->transactionId($transactionId)->call();
        $this->assertEquals($response->GetProductResult->Status, $getProduct->getResponse()['GetProductResult']['Status']);
    }

    /** @test */
    public function it_throw_invalid_configuration_exception_if_transaction_id_is_not_set()
    {
        $this->expectException(InvalidConfigurationError::class);
        $getProduct = new GetProduct('username');
        $getProduct->call();
    }

    /** @test */
    public function it_throw_exception_if_status_is_not_zero()
    {
        $this->expectException(ConnectionError::class);
        $username = 'username';
        $transactionId = '1';
        $response = GetProductResponse::fail();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetProduct')
            ->with(['UserName' => $username, 'TransactionID' => $transactionId])
            ->willReturn($response);

        $getProduct = new GetProduct($username, $soapClientMock);
        $getProduct->transactionId($transactionId)->call();
        $this->assertEquals(1, $getProduct->getResponse()['GetProductResult']['Status']);
    }

    /** @test */
    public function it_return_decoded_result_from_get_product_response()
    {
        $username = 'username';
        $transactionId = '1';
        $response = GetProductResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetProduct')
            ->with(['UserName' => $username, 'TransactionID' => $transactionId])
            ->willReturn($response);

        $getProduct = new GetProduct($username, $soapClientMock);

        $this->assertArrayHasKey('Requirements', $getProduct->transactionId($transactionId)->result());
    }

    /** @test */
    public function it_throw_exception_if_result_xml_is_not_valid()
    {
        $this->expectException(ConnectionError::class);
        $username = 'username';
        $transactionId = '1';
        $response = GetProductResponse::notXml();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetProduct')
            ->with(['UserName' => $username, 'TransactionID' => $transactionId])
            ->willReturn($response);

        $getProduct = new GetProduct($username, $soapClientMock);
        $this->assertEquals(0, $getProduct->transactionId(1)->result()['GetProductResult']['Status']);
    }

    /** @test */
    public function it_return_request_id_from_get_product_response()
    {
        $username = 'username';
        $transactionId = '1';
        $response = GetProductResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetProduct')
            ->with(['UserName' => $username, 'TransactionID' => $transactionId])
            ->willReturn($response);

        $getProduct = new GetProduct($username, $soapClientMock);
        $requestID = $getProduct->transactionId($transactionId)->requestId();
        $this->assertEquals($getProduct->result()['Requirements']['Requirement'][7], $requestID);
    }

    /** @test */
    public function it_return_products_from_get_product_response()
    {
        $username = 'username';
        $transactionId = '1';
        $response = GetProductResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetProduct')
            ->with(['UserName' => $username, 'TransactionID' => $transactionId])
            ->willReturn($response);

        $getProduct = new GetProduct($username, $soapClientMock);
        $products = $getProduct->transactionId($transactionId)->products();
        $this->assertArrayHasKey('Operator', $products);
    }

    /** @test */
    public function it_return_operators_products_from_get_product_response()
    {
        $username = 'username';
        $transactionId = '1';
        $response = GetProductResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetProduct')
            ->with(['UserName' => $username, 'TransactionID' => $transactionId])
            ->willReturn($response);

        $getProduct = new GetProduct($username, $soapClientMock);
        $products = $getProduct->transactionId($transactionId)->products();
        $this->assertEquals($products['Operator'], $getProduct->operators());
    }

    /** @test */
    public function it_return_product_by_product_id()
    {
        $username = 'username';
        $transactionId = '1';
        $response = GetProductResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetProduct')
            ->with(['UserName' => $username, 'TransactionID' => $transactionId])
            ->willReturn($response);

        $getProduct = new GetProduct($username, $soapClientMock);
        $product = $getProduct->transactionId($transactionId)->find(1);
        $this->assertEquals(1, $product['CID']);
        $this->assertArrayHasKey('@attributes', $product);
    }

    /** @test */
    public function it_throw_exception_if_response_is_not_valid()
    {
        $this->expectException(ConnectionError::class);
        $username = 'username';
        $transactionId = '1';
        $response = GetStatusResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetProduct')
            ->with(['UserName' => $username, 'TransactionID' => $transactionId])
            ->willReturn($response);

        $getProduct = new GetProduct($username, $soapClientMock);
        $getProduct->transactionId($transactionId)->call();
    }

    /** @test */
    public function it_call_get_product_call_function_if_there_is_no_response()
    {
        $username = 'username';
        $transactionId = '1';
        $response = GetProductResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetProduct')
            ->with(['UserName' => $username, 'TransactionID' => $transactionId])
            ->willReturn($response);

        $getProduct = new GetProduct($username, $soapClientMock);
        $response = $getProduct->transactionId($transactionId)->getResponse();
        $this->assertArrayHasKey('GetProductResult', $response);
    }

    /** @test */
    public function get_response_return_empty_array_if_product_not_found_by_cid()
    {
        $username = 'username';
        $transactionId = '1';
        $response = GetProductResponse::success();
        $soapClientMock = $this->getMockFromWsdl('http://core.eways.ir/WebService/Request.asmx?wsdl');
        $soapClientMock
            ->method('GetProduct')
            ->with(['UserName' => $username, 'TransactionID' => $transactionId])
            ->willReturn($response);

        $getProduct = new GetProduct($username, $soapClientMock);
        $product = $getProduct->transactionId($transactionId)->find(555);
        $this->assertEquals([], $product);
    }
}