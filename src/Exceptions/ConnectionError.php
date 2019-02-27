<?php

namespace GDPA\EwaysClient\Exceptions;

class ConnectionError extends \Exception
{
    public static function soapError($response)
    {
        return new static('Something is not right: `'.$response.'`');
    }

    public static function ewaysError($status, $message)
    {
        return new static("Eways status $status: $message");
    }

    public static function ewaysResponseError()
    {
        return new static("Eways response does not match defined structure.");
    }
}