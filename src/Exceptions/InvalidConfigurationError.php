<?php

namespace GDPA\EwaysClient\Exceptions;

class InvalidConfigurationError extends \Exception
{
    public static function transactionIdIsRequired()
    {
        return new static('Transaction ID is required to make a request.');
    }

    public static function requestIdIsRequired()
    {
        return new static('Request ID is required to make a request.');
    }

    public static function quantityIsRequiredAndShouldBeUnsigned()
    {
        return new static('Quantity is required and should be a number greater than 0 to make a request.');
    }

    public static function productTypeIsRequired()
    {
        return new static('Product Type is required to make a request.');
    }

    public static function mobileNumberIsRequired()
    {
        return new static('Mobile number is required to make a request.');
    }

    public static function productNotFound($productId)
    {
        return new static("It seems there is no product with id $productId .");
    }
}