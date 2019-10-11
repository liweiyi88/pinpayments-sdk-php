<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Functional;

use Liweiyi\PinPayments\Parameters\CardParameter;
use Liweiyi\PinPayments\Parameters\Cards\StoreCardParameter;
use Liweiyi\PinPayments\Parameters\Charges\ChargeCardParameter;
use Liweiyi\PinPayments\PinClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @covers \Liweiyi\PinPayments\PinClient
 */
final class PinClientTest extends TestCase
{
    private $pinClient;
    private $validCard;

    public function setUp(): void
    {
        parent::setUp();

        $apiKey = (string)\getenv('PIN_PAYMENTS_API_KEY');
        $this->pinClient = new PinClient($apiKey, HttpClient::create());
        $this->validCard = new CardParameter(
            '4200000000000000',
            '01',
            (string)((int)date('Y') + 1),
            '123',
            'Julian Li',
            '18 Lower Esplanade',
            'Melbourne',
            'Australia'
        );
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
