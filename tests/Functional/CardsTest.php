<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Functional;

/**
 * @covers \Liweiyi\PinPayments\PinClient
 */
final class CardsTest extends PinClientTest
{
    /**
     * Test store a card.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testStoreCard(): void
    {
        $response = $this->pinClient->storeCard($this->validVisaCard);
        $this->assertSame(201, $response->getStatusCode());
        $this->assertStringContainsString('token', $response->getContent());
    }
}
