<?php

namespace App\Msisdn\Exceptions;

class InvalidMsisdnException extends \RuntimeException
{
    public static function E164Violation()
    {
        return new static('Format not ITU-T E.164');
    }

    public static function InvalidMNO()
    {
        return new static('Unknown mobile network operator.');
    }

    public static function Unparsable(\Exception $e)
    {
        $msg = $e->getMessage();
        throw new InvalidMsisdnException("Unable to parse provided MSISDN: $msg");
    }
}
