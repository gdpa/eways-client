# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gdpa/eways-client.svg?style=flat-square)](https://packagist.org/packages/gdpa/eways-client)
[![Build Status](https://img.shields.io/travis/gdpa/eways-client/master.svg?style=flat-square)](https://travis-ci.org/gdpa/eways-client)
[![Quality Score](https://img.shields.io/scrutinizer/g/gdpa/eways-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/gdpa/eways-client)
[![Total Downloads](https://img.shields.io/packagist/dt/gdpa/eways-client.svg?style=flat-square)](https://packagist.org/packages/gdpa/eways-client)

This library make it easy to use eways apis for using GetProducts, RequestPins and GetStatus SOAPs.

## Installation

You can install the package via composer:

```bash
composer require gdpa/eways-client
```

## Usage

``` php
// Order a pin or top up in one shot
$ewaysClient = new EwaysClient('username', 'password');
$ewaysClient->orderPin($transactionId, $productId, $mobile, $quantity, $email, $optional, $refUrl);

// Pay a bill
$ewaysClient = new EwaysClient('username', 'password');
$ewaysClient->payBill($transactionId, $billId, $payId, $optional);

// Check order status in one shot
$ewaysClient = new EwaysClient('username', 'password');
$ewaysClient->getStatus('transactionID', 'requestID');

// Get products
$getProducts = new GetProduct('username');
$getProducts->products();

// Find product by CID
// For example MTN Top Up CID 40
$getProducts = new GetProduct('username');
$product = $getProducts->find(40);

// Call RequestPin
$requestPin = new RequestPin($password);
$requestPin->requestId($requestId)->quantity($quantity)->productType($productId)->mobile($mobile)->email($email)
    ->optional($optional)->refUrl($refUrl)->result();
    
// Get order status
$getStatus = new GetStatus();
$result = $getStatus->transactionId($transactionId)->requestId($requestId)->result();
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email morteza.poussaneh@gmail.com instead of using the issue tracker.

## Credits

- [Morteza Poussaneh](https://github.com/gdpa)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).