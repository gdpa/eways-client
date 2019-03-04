<?php

namespace GDPA\EwaysClient\Tests;

use GDPA\EwaysClient\EwaysClient;
use GDPA\EwaysClient\Exceptions\InvalidConfigurationError;
use GDPA\EwaysClient\GetProduct;
use GDPA\EwaysClient\GetStatus;
use GDPA\EwaysClient\RequestPin;
use GDPA\EwaysClient\Test\GetProductClientResponse;
use GDPA\EwaysClient\Test\GetStatusClientResponse;
use GDPA\EwaysClient\Test\RequestPinClientResponse;
use PHPUnit\Framework\TestCase;

class EwaysClientTest extends TestCase
{
    /** @test */
    public function it_set_transaction_id()
    {
        $transactionId = 1;

        $getProductMock = \Mockery::mock(GetProduct::class);
        $requestPinMock = \Mockery::mock(RequestPin::class);
        $getStatusMock = \Mockery::mock(GetStatus::class);

        $getProductMock->shouldReceive('transactionId')
            ->with($transactionId)
            ->once()
            ->andReturn($getProductMock);

        $getStatusMock->shouldReceive('transactionId')
            ->with($transactionId)
            ->once()
            ->andReturn($getStatusMock);

        $client = new EwaysClient('username', 'password', $getProductMock, $requestPinMock, $getStatusMock);
        $client->transactionId($transactionId);
        $this->assertEquals($transactionId, $client->getTransactionId());
    }

    /** @test */
    public function it_set_request_id()
    {
        $requestId = 'uuid';

        $getProductMock = \Mockery::mock(GetProduct::class);
        $requestPinMock = \Mockery::mock(RequestPin::class);
        $getStatusMock = \Mockery::mock(GetStatus::class);

        $requestPinMock->shouldReceive('requestId')
            ->with($requestId)
            ->once()
            ->andReturn($requestPinMock);

        $getStatusMock->shouldReceive('requestId')
            ->with($requestId)
            ->once()
            ->andReturn($getStatusMock);

        $client = new EwaysClient('username', 'password', $getProductMock, $requestPinMock, $getStatusMock);
        $client->requestId($requestId);
        $this->assertEquals($requestId, $client->getRequestId());
    }
    
    /** @test */
    public function it_get_access_to_get_product_client()
    {
        $getProductMock = \Mockery::mock(GetProduct::class);
        $requestPinMock = \Mockery::mock(RequestPin::class);
        $getStatusMock = \Mockery::mock(GetStatus::class);
        $client = new EwaysClient('username', 'password', $getProductMock, $requestPinMock, $getStatusMock);
        $this->assertEquals($getProductMock, $client->getProductClient());
    }

    /** @test */
    public function it_get_access_to_request_pin_client()
    {
        $getProductMock = \Mockery::mock(GetProduct::class);
        $requestPinMock = \Mockery::mock(RequestPin::class);
        $getStatusMock = \Mockery::mock(GetStatus::class);
        $client = new EwaysClient('username', 'password', $getProductMock, $requestPinMock, $getStatusMock);
        $this->assertEquals($requestPinMock, $client->requestPinClient());
    }

    /** @test */
    public function it_get_access_to_get_status_client()
    {
        $getProductMock = \Mockery::mock(GetProduct::class);
        $requestPinMock = \Mockery::mock(RequestPin::class);
        $getStatusMock = \Mockery::mock(GetStatus::class);
        $client = new EwaysClient('username', 'password', $getProductMock, $requestPinMock, $getStatusMock);
        $this->assertEquals($getStatusMock, $client->getStatusClient());
    }

    /** @test */
    public function it_orders_pin()
    {
        $transactionId = 1;
        $requestId = 'uuid';
        $quantity = 2;
        $productId = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';

        $getProductMock = \Mockery::mock(GetProduct::class);
        $requestPinMock = \Mockery::mock(RequestPin::class);
        $getStatusMock = \Mockery::mock(GetStatus::class);

        $getProductMock->shouldReceive('transactionId')
            ->with($transactionId)
            ->once()
            ->andReturn($getProductMock);

        $getStatusMock->shouldReceive('transactionId')
            ->with($transactionId)
            ->once()
            ->andReturn($getStatusMock);

        $getProductMock->shouldReceive('result')
            ->once()
            ->andReturn([]);

        $getProductMock->shouldReceive('find')
            ->with($productId)
            ->once()
            ->andReturn(GetProductClientResponse::findResponse());

        $getProductMock->shouldReceive('requestId')
            ->once()
            ->andReturn($requestId);

        $requestPinMock->shouldReceive('requestId')
            ->with($requestId)
            ->once()
            ->andReturn($requestPinMock);

        $requestPinMock->shouldReceive('quantity')
            ->with($quantity)
            ->once()
            ->andReturn($requestPinMock);

        $requestPinMock->shouldReceive('productType')
            ->with($productId)
            ->once()
            ->andReturn($requestPinMock);

        $requestPinMock->shouldReceive('mobile')
            ->with($mobile)
            ->once()
            ->andReturn($requestPinMock);

        $requestPinMock->shouldReceive('email')
            ->with($email)
            ->once()
            ->andReturn($requestPinMock);

        $requestPinMock->shouldReceive('optional')
            ->with($optional)
            ->once()
            ->andReturn($requestPinMock);

        $requestPinMock->shouldReceive('refUrl')
            ->with($refUrl)
            ->once()
            ->andReturn($requestPinMock);

        $requestPinMock->shouldReceive('result')
            ->once()
            ->andReturn(RequestPinClientResponse::successResult());

        $getStatusMock->shouldReceive('requestId')
            ->with($requestId)
            ->once()
            ->andReturn($getStatusMock);

        $getStatusMock->shouldReceive('result')
            ->once()
            ->andReturn(GetStatusClientResponse::successResult());

        $client = new EwaysClient('username', 'password', $getProductMock, $requestPinMock, $getStatusMock);
        $results = $client->orderPin($transactionId, $productId, $mobile, $quantity, $email, $optional, $refUrl);
        $this->assertEquals($results, GetStatusClientResponse::successResult());
    }

    /** @test */
    public function it_throw_exception_if_product_not_found()
    {
        $this->expectException(InvalidConfigurationError::class);
        $transactionId = 1;
        $quantity = 2;
        $productId = 10;
        $mobile = '+989999999999';
        $email = 'info@example.com';
        $optional = 'optional';
        $refUrl = 'http://example.com';

        $getProductMock = \Mockery::mock(GetProduct::class);
        $requestPinMock = \Mockery::mock(RequestPin::class);
        $getStatusMock = \Mockery::mock(GetStatus::class);

        $getProductMock->shouldReceive('transactionId')
            ->with($transactionId)
            ->once()
            ->andReturn($getProductMock);

        $getStatusMock->shouldReceive('transactionId')
            ->with($transactionId)
            ->once()
            ->andReturn($getStatusMock);

        $getProductMock->shouldReceive('result')
            ->once()
            ->andReturn([]);

        $getProductMock->shouldReceive('find')
            ->with($productId)
            ->once()
            ->andReturn([]);
        $client = new EwaysClient('username', 'password', $getProductMock, $requestPinMock, $getStatusMock);
        $client->orderPin($transactionId, $productId, $mobile, $quantity, $email, $optional, $refUrl);
    }

    /** @test */
    public function it_can_set_and_get_product_property()
    {
        $ewaysClient = new EwaysClient('username', 'password');
        $product = ['id' =>  1];
        $this->assertEquals([], $ewaysClient->getProduct());
        $ewaysClient->setProduct($product);
        $this->assertEquals($product, $ewaysClient->getProduct());
    }

    /** @test */
    public function it_can_set_and_get_request_response()
    {
        $ewaysClient = new EwaysClient('username', 'password');
        $response = ['Status' =>  500];
        $this->assertEquals([], $ewaysClient->getRequestResponse());
        $ewaysClient->setRequestResponse($response);
        $this->assertEquals($response, $ewaysClient->getRequestResponse());
    }

    /** @test */
    public function it_calls_result_on_get_status_for_getting_status()
    {
        $transactionId = 1;
        $requestId = 'uuid';

        $getProductMock = \Mockery::mock(GetProduct::class);
        $requestPinMock = \Mockery::mock(RequestPin::class);
        $getStatusMock = \Mockery::mock(GetStatus::class);

        $getProductMock->shouldReceive('transactionId')
            ->with($transactionId)
            ->once()
            ->andReturn($getProductMock);

        $getStatusMock->shouldReceive('transactionId')
            ->with($transactionId)
            ->once()
            ->andReturn($getStatusMock);
        $getStatusMock->shouldReceive('requestId')
            ->with($requestId)
            ->once()
            ->andReturn($getStatusMock);
        $requestPinMock->shouldReceive('requestId')
            ->with($requestId)
            ->once()
            ->andReturn($requestPinMock);
        $getStatusMock->shouldReceive('result')
            ->once()
            ->andReturn(GetStatusClientResponse::successResult());

        $client = new EwaysClient('username', 'password', $getProductMock, $requestPinMock, $getStatusMock);
        $result = $client->getStatus($transactionId, $requestId);
        $this->assertArrayHasKey('Status', $result);
    }
}