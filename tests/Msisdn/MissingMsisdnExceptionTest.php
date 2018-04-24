<?php

namespace App\Tests\Msisdn;

use PHPUnit\Framework\TestCase;
use App\Msisdn\Exceptions\MissingMsisdnException;
use Symfony\Component\HttpFoundation\JsonResponse;

/** @group unit */
class MissingMsisdnExceptionTest extends TestCase
{
    /** @test */
    public function correct_status_code_and_message()
    {
        // implements responsable exception...
        $exception = new MissingMsisdnException;

        $this->assertEquals(400, $exception->recommendedHttpStatusCode());
        $this->assertEquals('Missing required parameter: msisdn', $exception->getMessage());
    }

    // ---------------------------------------------------------------------- */
    // -- THE FOLLOWING TESTS IMPLICITLY TEST ResponsableException ---------- */

    /** @test */
    public function can_convert_to_array()
    {
        $exception = new MissingMsisdnException;

        $expectedArray = [
            'message' => 'Missing required parameter: msisdn',
        ];

        $this->assertEquals($expectedArray, $exception->toArray());
    }

    /** @test */
    public function can_convert_to_JsonResponse()
    {
        $exception = new MissingMsisdnException;
        $response = $exception->toJsonResponse();
        
        $expected = new JsonResponse([
            'message' => 'Missing required parameter: msisdn',
        ], 400);

        $this->assertEquals($expected, $response);
    }

}
