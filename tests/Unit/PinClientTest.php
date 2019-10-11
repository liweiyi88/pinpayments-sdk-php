<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit;

use Liweiyi\PinPayments\PinClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * @covers \Liweiyi\PinPayments\PinClient
 */
final class PinClientTest extends TestCase
{
    /**
     * Test capture charge token request.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testCapture(): void
    {
        $responses = [
            new MockResponse(
                '{"error":"not_found","error_description":"The requested resource could not be found."}',
                ['http_code' => 404]
            ),
            new MockResponse('', ['http_code' => 201])
        ];

        $client = new MockHttpClient($responses);
        $pinClient = new PinClient('test_api_key', $client);

        $notFoundResponse = $pinClient->capture('not_found_token');
        $expected = '{"error":"not_found","error_description":"The requested resource could not be found."}';

        $this->assertSame($expected, $notFoundResponse->getContent(false));
        $this->assertSame(404, $notFoundResponse->getStatusCode());

        $successResponse = $pinClient->capture('not_found_token');
        $this->assertArrayHasKey('response', $successResponse->toArray());
        $this->assertSame(201, $successResponse->getStatusCode());
    }
}
