<?php

namespace App\Tests\Util;

use App\Msisdn\MsisdnService;
use PHPUnit\Framework\TestCase;
use libphonenumber\PhoneNumberUtil;
use App\Msisdn\Exceptions\InvalidMsisdnException;
use libphonenumber\PhoneNumberToCarrierMapper;

class MsisdnServiceTest extends TestCase
{
    private $_msisdnService;

    public function setUp()
    {
        parent::setUp();

        $phoneUtil = PhoneNumberUtil::getInstance();
        $mapper = PhoneNumberToCarrierMapper::getInstance();

        $this->_msisdnService = new MsisdnService($phoneUtil, $mapper);
    }


    /** @test */
    public function parse_method_returns_service_instance()
    {
        $msisdn = '+38640123456';

        $result = $this->_msisdnService->parse($msisdn);

        $this->assertEquals($this->_msisdnService, $result);
    }

    /** @test */
    public function parse_valid_msisdn_returns_correct_result()
    {
        $msisdn = '+38640123456';

        $result = $this->_msisdnService->parse($msisdn)->toArray();

        $expectedResult = [
            'mno_identifier' => 'A1',
            'country_code' => 386,
            'country_identifier' => 'SI',
            'subscriber_number' => '123456',
        ];

        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function parse_invalid_msisdn_throws_InvaliMsisdnException()
    {
        $this->expectException(InvalidMsisdnException::class);
        $invalidMsisdn = '-xxx3443553465';
        $this->_msisdnService->parse($invalidMsisdn);
    }
}
