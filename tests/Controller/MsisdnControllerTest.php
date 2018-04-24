<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/** @group functional */
class MsisdnControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    /** @test */
    public function valid_request_yields_full_response()
    {
        $response = $this->get('/transform?msisdn=38640123456');

        $expectedCode = 200;
        $expectedResponse = '{"mno_identifier":"A1","country_code":386,"country_identifier":"SI","subscriber_number":"123456"}';

        $this->assertResponse($expectedCode, $expectedResponse, $response);
    }

    /** @test */
    public function missing_msisdn_yields_400()
    {
        $response = $this->get('/transform?msisdn');

        $expectedCode = 400;
        $expectedResponse = '{"message":"Missing required parameter: msisdn"}';

        $this->assertResponse($expectedCode, $expectedResponse, $response);
    }

    /** @test */
    public function requesting_inexistent_route_yields_404()
    {
        $response = $this->get('/inexistent-route');

        $expectedCode = 404;
        $expectedPayload = '{"message":"Oops, requested resource doesn\u0027t exist."}';

        $this->assertResponse($expectedCode, $expectedPayload, $response);
    }

    // missing test for 500 internal error, but don't know how to do it

    private function get(string $url)
    {
        $this->client->request('GET', $url);
        return $this->client->getResponse();
    }

    private function assertResponse($expectedCode, $expectedResponse, $response)
    {
        $this->assertEquals($expectedCode, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());
    }
}

