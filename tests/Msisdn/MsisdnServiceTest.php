<?php

namespace App\Tests\Util;

use App\Msisdn\MsisdnService;
use PHPUnit\Framework\TestCase;
use libphonenumber\PhoneNumberUtil;
use App\Msisdn\Exceptions\InvalidMsisdnException;

class MsisdnServiceTest extends TestCase
{
    private $_msisdnService;

    public function setUp()
    {
        parent::setUp();

        $phoneUtil = PhoneNumberUtil::getInstance();
        $this->_msisdnService = new MsisdnService($phoneUtil);
    }


    /** @test */
    public function parse_method_returns_service_instance()
    {
        $msisdn = '+38640123456';

        $returned = $this->_msisdnService->parse($msisdn);

        $this->assertEquals($this->_msisdnService, $returned);
    }

    /** @test */
    public function parse_invalid_msisdn_throws_InvaliMsisdnException()
    {
        $this->expectException(InvalidMsisdnException::class);
        $invalidMsisdn = '-xxx3443553465';
        $this->_msisdnService->parse($invalidMsisdn);
    }
}
