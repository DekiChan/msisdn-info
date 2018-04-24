<?php

namespace App\Msisdn;

use libphonenumber\PhoneNumberUtil;
use App\Msisdn\Exceptions\InvalidMsisdnException;

class MsisdnService implements IMsisdnService
{
    private $_msisdn;

    private $_phoneNumberUtil;

    public function __construct(PhoneNumberUtil $util)
    {
        $this->_phoneNumberUtil = $util;
    }

    /**
     * Take a number in MSISDN format, validate it and extract:
     *  - mno identifier
     *  - country dialling code
     *  - country identifier in ISO 3166-1 alpha-2
     *  - subscriber number
     *
     * @param string $msisdn
     * @return IMsisdnService Instance of this class
     */
    public function parse(string $msisdn): IMsisdnService
    {
        $this->saveAsE164($msisdn);
        
        return $this;
    }

    /**
     * Saves msisdn as E.164 formatted number.
     * Throws an exception if msisdn is invalid
     *
     * @param string $msisdn
     * @return void
     */
    protected function saveAsE164(string $msisdn)
    {
        if (!$this->isValid($msisdn)) {
            throw new InvalidMsisdnException;
        }

        $this->_msisdn = $this->toE164($msisdn);
    }

    /**
     * Checks whether msisdn is in ITU-T E.164 recommendation format.
     * See https://en.wikipedia.org/wiki/E.164
     *
     * @param string $msisdn
     * @return bool
     */
    protected function isValid(string $msisdn) : bool
    {
        // will match 7 to 15 digits, optionally prefixed with '+'
        // ^\+?[0-9]{7,15}$
        return preg_match('/^\+?[0-9]{7,15}$/', $msisdn) === 1;
    }

    /**
     * Prepend user supplied msisdn with '+' if missing.
     *
     * @param string $msisdn
     * @return string
     */
    protected function toE164(string $msisdn) : string
    {
        // prepend $msisdn with '+' if missing
        return $msisdn[0] === '+' ? $msisdn : "+$msisdn";
    }

    /**
     * Returns parsed msisdn values as array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'parsed' => true,
            'msisdn' => $this->_msisdn,
        ];
    }
}
