<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Functional;

use Liweiyi\PinPayments\Parameters\Cards\StoreCardParameter;
use Liweiyi\PinPayments\Parameters\Charges\ChargeCardParameter;
use Liweiyi\PinPayments\PinClient;
use Liweiyi\PinPayments\Tests\TestCardsTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @covers \Liweiyi\PinPayments\PinClient
 */
final class PinClientTest extends TestCase
{
    use TestCardsTrait;

    private $pinClient;
    private $validCard;

    public function setUp(): void
    {
        parent::setUp();

        $apiKey = (string)\getenv('PIN_PAYMENTS_API_KEY');
        $this->pinClient = new PinClient($apiKey, HttpClient::create());
        $this->validCard = $this->createTestValidCard();
    }

    /**
     * Test capture a token.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testCapture(): void
    {
        $parameter = (new ChargeCardParameter(
            'test@example.com',
            'Charge Function Test',
            100,
            '127.0.0.1',
            $this->validCard
        ))->setCapture(false)
            ->setCurrency('USD')
            ->setMetadata(['Meta1' => 'meta']);

        $chargeResponse = $this->pinClient->chargeCard($parameter)->toArray();

        $response = $this->pinClient->capture($chargeResponse['response']['token']);
        $this->assertSame(201, $response->getStatusCode());
        $this->assertStringContainsString('success', $response->getContent(false));
    }

    /**
     * Test charge a card.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testChargeCard(): void
    {
        $parameter = (new ChargeCardParameter(
            'test@example.com',
            'Charge Function Test',
            100,
            '127.0.0.1',
            $this->validCard
        ))->setCapture(true)
        ->setCurrency('USD')
        ->setMetadata(['Meta1' => 'meta']);

        $response = $this->pinClient->chargeCard($parameter);
        $this->assertSame(201, $response->getStatusCode());
        $this->assertStringContainsString('success', $response->getContent());
        $this->assertTrue($response->toArray()['response']['captured']);
    }

    /**
     * Test list paginated list of charges.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testListCharges(): void
    {
        $response = $this->pinClient->listCharges();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('pagination', $response->getContent());
    }

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
        $response = $this->pinClient->storeCard(StoreCardParameter::createFromCardParameter($this->validCard));
        $this->assertSame(201, $response->getStatusCode());
        $this->assertStringContainsString('token', $response->getContent());
    }
}
