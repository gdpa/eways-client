<?php

namespace GDPA\EwaysClient\Test;

use PHPUnit\Framework\TestCase;

class GetProductResponseTest extends TestCase
{
    /** @test */
    public function test_get_product_success_response()
    {
        $response = GetProductResponse::success();
        $this->assertObjectHasAttribute('GetProductResult', $response);
        $this->assertObjectHasAttribute('Status', $response->GetProductResult);
        $this->assertEquals(0, $response->GetProductResult->Status);
        $this->assertObjectHasAttribute('Message', $response->GetProductResult);
        $this->assertObjectHasAttribute('Result', $response->GetProductResult);
    }

    /** @test */
    public function test_get_product_fail_response()
    {
        $response = GetProductResponse::fail();
        $this->assertObjectHasAttribute('GetProductResult', $response);
        $this->assertObjectHasAttribute('Status', $response->GetProductResult);
        $this->assertEquals(1, $response->GetProductResult->Status);
        $this->assertObjectHasAttribute('Message', $response->GetProductResult);
    }

    /** @test */
    public function test_get_product_cant_sale_response()
    {
        $response = GetProductResponse::cantSale();
        $this->assertObjectHasAttribute('GetProductResult', $response);
        $this->assertObjectHasAttribute('Status', $response->GetProductResult);
        $this->assertEquals(2, $response->GetProductResult->Status);
        $this->assertObjectHasAttribute('Message', $response->GetProductResult);
    }

    /** @test */
    public function test_get_product_credential_response()
    {
        $response = GetProductResponse::credential();
        $this->assertObjectHasAttribute('GetProductResult', $response);
        $this->assertObjectHasAttribute('Status', $response->GetProductResult);
        $this->assertEquals(3, $response->GetProductResult->Status);
        $this->assertObjectHasAttribute('Message', $response->GetProductResult);
    }

    /** @test */
    public function test_get_product_duplicate_response()
    {
        $response = GetProductResponse::duplicateTransaction();
        $this->assertObjectHasAttribute('GetProductResult', $response);
        $this->assertObjectHasAttribute('Status', $response->GetProductResult);
        $this->assertEquals(4, $response->GetProductResult->Status);
        $this->assertObjectHasAttribute('Message', $response->GetProductResult);
    }

    /** @test */
    public function test_get_status_response_success()
    {
        $response = GetStatusResponse::success();
        $this->assertObjectHasAttribute('GetStatusResult', $response);
        $this->assertObjectHasAttribute('ResultGetStatus', $response->GetStatusResult);
        $this->assertObjectHasAttribute('Status', $response->GetStatusResult->ResultGetStatus);
        $this->assertEquals(40, $response->GetStatusResult->ResultGetStatus->Status);
        $this->assertObjectHasAttribute('Message', $response->GetStatusResult->ResultGetStatus);
    }

    /** @test */
    public function test_get_status_response_failed()
    {
        $response = GetStatusResponse::failed();
        $this->assertObjectHasAttribute('GetStatusResult', $response);
        $this->assertObjectHasAttribute('ResultGetStatus', $response->GetStatusResult);
        $this->assertObjectHasAttribute('Status', $response->GetStatusResult->ResultGetStatus);
        $this->assertEquals(43, $response->GetStatusResult->ResultGetStatus->Status);
        $this->assertObjectHasAttribute('Message', $response->GetStatusResult->ResultGetStatus);
    }

    /** @test */
    public function test_get_product_client_find_response()
    {
        $response = GetProductClientResponse::findResponse();
        $this->assertArrayHasKey('@attributes', $response);
        $this->assertArrayHasKey('CID', $response);
    }

    /** @test */
    public function test_request_pin_success_result()
    {
        $response = RequestPinClientResponse::successResult();
        $this->assertArrayHasKey('Status', $response);
        $this->assertArrayHasKey('Message', $response);
        $this->assertArrayHasKey('Serial', $response);
        $this->assertArrayHasKey('OrderID', $response);
    }

    /** @test */
    public function test_get_status_client_success_response()
    {
        $response = GetStatusClientResponse::successResult();
        $this->assertArrayHasKey('Status', $response);
        $this->assertArrayHasKey('Serial', $response);
        $this->assertArrayHasKey('ProductCode', $response);
    }
}

class GetProductResponse
{
    public static function success()
    {
        $products = file_get_contents(__DIR__ . '/ProductXML.txt');

        $obj = new \stdClass();
        $obj->GetProductResult = new \stdClass();
        $obj->GetProductResult->Status = 0;
        $obj->GetProductResult->Message = 'درخواست اولیه خرید محصول با موفقیت انجام شد.';
        $obj->GetProductResult->Result = $products;
        return $obj;
    }

    public static function fail()
    {
        $obj = new \stdClass();
        $obj->GetProductResult = new \stdClass();
        $obj->GetProductResult->Status = 1;
        $obj->GetProductResult->Message = 'درخواست با شکست مواجه شد.';

        return $obj;
    }

    public static function cantSale()
    {
        $obj = new \stdClass();
        $obj->GetProductResult = new \stdClass();
        $obj->GetProductResult->Status = 2;
        $obj->GetProductResult->Message = 'امکان فروش این محصول برای شما وجود ندارد.';

        return $obj;
    }

    public static function credential()
    {
        $obj = new \stdClass();
        $obj->GetProductResult = new \stdClass();
        $obj->GetProductResult->Status = 3;
        $obj->GetProductResult->Message = 'پارامترهای ارسال شده نامعتبر می باشد.';

        return $obj;
    }

    public static function duplicateTransaction()
    {
        $obj = new \stdClass();
        $obj->GetProductResult = new \stdClass();
        $obj->GetProductResult->Status = 4;
        $obj->GetProductResult->Message = 'کد درخواست تکراری است.';

        return $obj;
    }

    public static function notXml()
    {
        $obj = new \stdClass();
        $obj->GetProductResult = new \stdClass();
        $obj->GetProductResult->Status = 0;
        $obj->GetProductResult->Message = 'درخواست اولیه خرید محصول با موفقیت انجام شد.';
        $obj->GetProductResult->Result = '&lt;xml&gt;';
        return $obj;
    }
}

class RequestPinResponse
{
    public static function success()
    {
        $obj = new \stdClass();
        $obj->RequestPinsResult = new \stdClass();
        $obj->RequestPinsResult->RequestsPinsResponse = new \stdClass();
        $obj->RequestPinsResult->RequestsPinsResponse->Status = '500';
        $obj->RequestPinsResult->RequestsPinsResponse->Message = 'درخواست با موفقیت انجام شد';
        $obj->RequestPinsResult->RequestsPinsResponse->OrderID = '1000';
        $obj->RequestPinsResult->RequestsPinsResponse->PaymentValue = '20000';
        $obj->RequestPinsResult->RequestsPinsResponse->Serial = '53641105';
        $obj->RequestPinsResult->RequestsPinsResponse->PinType = 'شارژ 2000 تومانی';
        $obj->RequestPinsResult->RequestsPinsResponse->ChargeWay = '';

        return $obj;
    }

    public static function credential()
    {
        $obj = new \stdClass();
        $obj->RequestPinsResult = new \stdClass();
        $obj->RequestPinsResult->RequestsPinsResponse = new \stdClass();
        $obj->RequestPinsResult->RequestsPinsResponse->Status = '200';
        $obj->RequestPinsResult->RequestsPinsResponse->Message = 'شما اجازه استفاده از این سرویس را ندارید';
        $obj->RequestPinsResult->RequestsPinsResponse->OrderID = '0';
        $obj->RequestPinsResult->RequestsPinsResponse->PaymentValue = '0';
        $obj->RequestPinsResult->RequestsPinsResponse->ChargeWay = '127.0.0.1';
        $obj->RequestPinsResult->RequestsPinsResponse->ID = '0';

        return $obj;
    }
}

class GetStatusResponse
{
    public static function success()
    {
        $obj = new \stdClass();
        $obj->GetStatusResult = new \stdClass();
        $obj->GetStatusResult->ResultGetStatus = new \stdClass();
        $obj->GetStatusResult->ResultGetStatus->Status = '40';
        $obj->GetStatusResult->ResultGetStatus->Message = 'your request recognized and the result sent گردید ارسال نتیجه و شناسایی شما درخواست';
        $obj->GetStatusResult->ResultGetStatus->Pin = '9211192093011300';
        $obj->GetStatusResult->ResultGetStatus->Serial = 'IR135122977617';
        $obj->GetStatusResult->ResultGetStatus->ProductCode = '15911811';
        $obj->GetStatusResult->ResultGetStatus->ChargeType = 'Irancell 10000 Rials تومانی 1111 ایرانسل';
        $obj->GetStatusResult->ResultGetStatus->Price = '10000';
        $obj->GetStatusResult->ResultGetStatus->Operator = 'MTN';
        $obj->GetStatusResult->ResultGetStatus->SiteName = 'your site name';
        $obj->GetStatusResult->ResultGetStatus->GroupID = '0';
        $obj->GetStatusResult->ResultGetStatus->PaymentID = '43082397';
        $obj->GetStatusResult->ResultGetStatus->Authority = '4506514';
        $obj->GetStatusResult->ResultGetStatus->MobileNo = '';
        $obj->GetStatusResult->ResultGetStatus->ExpireDate = '';

        return $obj;
    }

    public static function failed()
    {
        $obj = new \stdClass();
        $obj->GetStatusResult = new \stdClass();
        $obj->GetStatusResult->ResultGetStatus = new \stdClass();
        $obj->GetStatusResult->ResultGetStatus->Status = '43';
        $obj->GetStatusResult->ResultGetStatus->Message = 'درخواست شما ناموفق است';

        return $obj;
    }
}

class GetProductClientResponse
{
    public static function findResponse()
    {
        return [
            '@attributes' => [
                'Price' => '10000',
                'available' => '1',
                'isWholeSale' => '0',
            ],
            'CID' => 51,
            'PersianTitle' => 'شارژ مستقیم 10000 ریالی',
            'EnglishTitle' => 'MCI Topup 10000 Rial',
        ];
    }
}

class RequestPinClientResponse
{
    public static function successResult()
    {
        $data['Status'] = '500';
        $data['Message'] = 'درخواست با موفقیت انجام شد';
        $data['OrderID'] = '1000';
        $data['PaymentValue'] = '20000';
        $data['Serial'] = '53641105';
        $data['PinType'] = 'شارژ 2000 تومانی';
        $data['ChargeWay'] = '';

        return $data;
    }
}

class GetStatusClientResponse
{
    public static function successResult()
    {
        $data['Status'] = '40';
        $data['Message'] = 'your request recognized and the result sent گردید ارسال نتیجه و شناسایی شما درخواست';
        $data['Pin'] = '9211192093011300';
        $data['Serial'] = 'IR135122977617';
        $data['ProductCode'] = '15911811';
        $data['ChargeType'] = 'Irancell 10000 Rials تومانی 1111 ایرانسل';
        $data['Price'] = '10000';
        $data['Operator'] = 'MTN';
        $data['SiteName'] = 'your site name';
        $data['GroupID'] = '0';
        $data['PaymentID'] = '43082397';
        $data['Authority'] = '4506514';
        $data['MobileNo'] = '';
        $data['ExpireDate'] = '';
        return $data;
    }
}