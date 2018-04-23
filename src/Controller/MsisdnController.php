<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Msisdn\IMsisdnService;

class MsisdnController extends AbstractController
{
    private $_msisdnInfo;

    public function __construct(IMsisdnService $msisdnService)
    {
        $this->_msisdnInfo = $msisdnService;
    }

    public function transform(Request $request)
    {
        $msisdn = $request->query->get('msisdn');

        // need proper error handling later
        if (!$msisdn) {
            return $this->json([
                'error' => 400,
                'message' => 'Missing required parameter: msisdn',
            ]);
        }

        $parsed = $this->_msisdnInfo->parse($msisdn)->toArray();

        return $this->json($parsed);
    }
}
