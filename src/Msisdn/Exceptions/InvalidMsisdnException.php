<?php

namespace App\Msisdn\Exceptions;

use App\Exceptions\ResponsableException;

class InvalidMsisdnException extends ResponsableException
{
    public static function E164Violation() : InvalidMsisdnException
    {
        return new static('Format not ITU-T E.164', 400);
    }

    public static function InvalidMNO() : InvalidMsisdnException
    {
        return new static('Unknown mobile network operator.', 400);
    }

    public static function Unparsable(\Exception $e) : InvalidMsisdnException
    {
        $msg = $e->getMessage();
        throw new InvalidMsisdnException("Unable to parse provided MSISDN: $msg", 400);
    }
}
