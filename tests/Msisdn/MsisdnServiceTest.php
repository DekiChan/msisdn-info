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
    public function parse_msisdn_without_plus_prefix_returns_correct_result()
    {
        $msisdn = '38640123456';

        $result = $this->_msisdnService->parse($msisdn)->toArray();

        $expectedResult = [
            'mno_identifier' => 'A1',
            'country_code' => 386,
            'country_identifier' => 'SI',
            'subscriber_number' => '123456',
        ];

        $this->assertEquals($expectedResult, $result);
    }

    /** 
     * @test 
     * @dataProvider invalidMsisdnProvider
     */
    public function parse_invalid_msisdn_throws_InvaliMsisdnException($msisdn)
    {
        $this->expectException(InvalidMsisdnException::class);
        $this->_msisdnService->parse($msisdn);
    }

    public function invalidMsisdnProvider()
    {
        return [
            ['-xxx3443553465'], // invalid characters
            ['123456'], // too short
            ['1234567890123456'], // too long
            ['99940123456'], // inexistent country code
            ['38629123456'], // inexistent MNO
        ];
    }
}
