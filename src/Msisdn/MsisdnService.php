<?php

namespace App\Msisdn;

use libphonenumber\PhoneNumberUtil;

class MsisdnService implements IMsisdnService
{
    private $_msisdn;
    private $_phoneNumberUtil;

    public function __construct(PhoneNumberUtil $util) {
        $this->_phoneNumberUtil = $util;
    }

    public function parse(string $msisdn): IMsisdnService
    {
        $this->_msisdn = $msisdn;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'parsed' => true,
            'msisdn' => $this->_msisdn,
        ];
    }
}
