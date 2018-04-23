<?php

namespace App\Msisdn;

interface IMsisdnService
{
    public function parse(string $msisdn): IMsisdnService;
    public function toArray(): array;
}
