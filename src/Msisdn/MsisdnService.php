<?php

namespace App\Msisdn;

class MsisdnService implements IMsisdnService
{
    private $_msisdn;

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
