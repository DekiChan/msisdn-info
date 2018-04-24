<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Msisdn\IMsisdnService;
use App\Msisdn\Exceptions\MissingMsisdnException;

class MsisdnController extends AbstractController
{
    private $_msisdnInfo;

    public function __construct(IMsisdnService $msisdnService)
    {
        $this->_msisdnInfo = $msisdnService;
    }

    /**
     * Receiver msisdn as query parameter. 
     * Returns JSON object with the following properties:
     *  - mno_identifier
     *  - country_code
     *  - country_identifier
     *  - subscriber_number
     */
    public function transform(Request $request)
    {
        $msisdn = $request->query->get('msisdn');

        if (!$msisdn) {
            throw new MissingMsisdnException;
        }

        $parsed = $this->_msisdnInfo->parse($msisdn)->toArray();

        return $this->json($parsed);
    }
}
