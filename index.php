<?php

require './vendor/autoload.php';

$client = new \GDPA\EwaysClient\EwaysClient('18J2$30e93', '18J2$30e93');
$client->transactionId('1')->getProductClient()->result();
$requestId = $client->getProductClient()->requestId();
$client->requestId($requestId);
$billId = '9442371604125';
$payId = '46470682';
var_dump($requestId);
var_dump($client->transactionId(1)->requestBillClient()->payId($payId)->billId($billId)->getResponse());
var_dump($client->getStatus(1, $requestId));

// My bill
//var_dump($client->getStatus(1, 'fdc1c95b-6d82-4839-9a2a-9c7340dbfaca'));
// ERfan mobile
//var_dump($client->getStatus(1, 'b295cf9e-ab29-4484-b4fa-ea14fa7642bb'));