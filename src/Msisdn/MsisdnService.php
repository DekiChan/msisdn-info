<?php

namespace App\Msisdn;

use libphonenumber\PhoneNumberUtil;
use App\Msisdn\Exceptions\InvalidMsisdnException;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

class MsisdnService implements IMsisdnService
{
    private $_msisdn;

    private $_phoneNumberUtil;
    private $_phoneNumber;

    private $_phoneToCarrierMapper;

    private $_countryIdentifier;
    private $_mnoIdentifier;
    private $_subscriberNumber;

    public function __construct(PhoneNumberUtil $util, PhoneNumberToCarrierMapper $mapper)
    {
        $this->_phoneNumberUtil = $util;
        $this->_phoneToCarrierMapper = $mapper;
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

        try {
            $this->_phoneNumber 
                = $this->_phoneNumberUtil->parse($this->_msisdn);
        } catch (NumberParseException $e) {
            $msg = $e->getMessage();
            throw new InvalidMsisdnException("Unable to parse provided MSISDN: $msg");
        }
        
        $this->extractCountryIdentifier();
        $this->extractMnoIdentifier();
        $this->extractSubscriberNumber();

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
            throw new InvalidMsisdnException('Format not ITU-T E.164');
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
     * Extracts country identifier and writes it to _countryIdentifier prop
     * 
     * @return void
     */
    protected function extractCountryIdentifier()
    {
        $this->_countryIdentifier
            = $this->_phoneNumberUtil->getRegionCodeForNumber($this->_phoneNumber);
    }

    /**
     * Finds MNO identifier (name) and writes it to _mnoIdentifier prop
     * 
     * @return void
     */
    protected function extractMnoIdentifier()
    {
        $this->_mnoIdentifier
            = $this->_phoneToCarrierMapper->getNameForNumber($this->_phoneNumber, 'en');
        
        // let's trust the library and invalidate the result if MNO identifier not found
        if ($this->_mnoIdentifier === '') {
            throw new InvalidMsisdnException('Unknown mobile network operator.');
        }
    }

    /**
     * Extracts subscriber number (removes country and carrier codes) and
     * writes it to _subscriberNumber prop.
     * 
     * @return void
     */
    protected function extractSubscriberNumber()
    {
        // international code will give format (whitespace is significant):
        // "+<country_code> <carrier_code> <anything else>"
        $intl = $this->_phoneNumberUtil->format($this->_phoneNumber, PhoneNumberFormat::INTERNATIONAL);
        $intlSplit = explode(' ', $intl);
        $prefix = $intlSplit[0].$intlSplit[1];

        // escape the '+' sign for regex
        $prefixRe = '\\'.$prefix;

        // will replace only first occurence
        $this->_subscriberNumber 
            = preg_replace('/'.$prefixRe.'/', '', $this->_msisdn, 1);
    }

    /**
     * Returns parsed msisdn values as array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'mno_identifier' => $this->_mnoIdentifier,
            'country_code' => $this->_phoneNumber->getCountryCode(),
            'country_identifier' => $this->_countryIdentifier,
            'subscriber_number' => $this->_subscriberNumber,
        ];
    }
}
