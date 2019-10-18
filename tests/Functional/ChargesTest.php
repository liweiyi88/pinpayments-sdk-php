<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Functional;

use Liweiyi\PinPayments\Parameters\Charges\ChargeCardParameter;
use Liweiyi\PinPayments\Parameters\Charges\ChargeCardTokenParameter;
use Liweiyi\PinPayments\Parameters\Charges\ChargeCustomerTokenParameter;

/**
 * @covers \Liweiyi\PinPayments\PinClient
 */
final class ChargesTest extends PinClientTest
{
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
        $card = new ChargeCardParameter(
            'test@example.com',
            'Test Capture',
            100,
            '127.0.0.1',
            $this->validVisaCard
        );

        $card->setCapture(false)
            ->setCurrency('USD')
            ->setMetadata(['Meta1' => 'meta']);

        $chargeResponse = $this->pinClient->charge($card)->toArray();

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
        $card = new ChargeCardParameter(
            'test@example.com',
            'Test charge a card',
            100,
            '127.0.0.1',
            $this->validVisaCard
        );

        $card->setCapture(true)
            ->setCurrency('USD')
            ->setMetadata(['Meta1' => 'meta']);

        $response = $this->pinClient->charge($card);
        $this->assertSame(201, $response->getStatusCode());
        $this->assertStringContainsString('success', $response->getContent());
        $this->assertTrue($response->toArray()['response']['captured']);
    }

    /**
     * Test charge a customer token.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testChargeCustomerToken(): void
    {
        $customerToken = $this->createCardCustomerToken('test_card@example.com', 'visa');

        $customerTokenParameter = new ChargeCustomerTokenParameter(
            'charge_customer@examp.e.com',
            'Test charge a customer token',
            100,
            '127.0.0.1',
            $customerToken
        );

        $response = $this->pinClient->charge($customerTokenParameter);
        $this->assertSame(201, $response->getStatusCode());

        $response = $this->pinClient->deleteCustomer($customerToken);
        $this->assertSame(204, $response->getStatusCode());
    }

    /**
     * Test charge a card token.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testChargeCardToken(): void
    {
        $cardResponse = $this->pinClient->storeCard($this->validVisaCard)->toArray();

        $cardTokenParameter = new ChargeCardTokenParameter(
            'charge_card_token@example.com',
            'Test charge card token',
            100,
            '127.0.0.1',
            $cardResponse['response']['token']
        );

        $response = $this->pinClient->charge($cardTokenParameter);
        $this->assertSame(201, $response->getStatusCode());
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
     * Test get a charge details.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testGetChargeDetails(): void
    {
        $parameter = new ChargeCardParameter(
            'test@example.com',
            'Test get a charge details',
            100,
            '127.0.0.1',
            $this->validVisaCard
        );

        $parameter->setCapture(true)
            ->setCurrency('USD')
            ->setMetadata(['Meta1' => 'meta']);

        $response = $this->pinClient->charge($parameter)->toArray(false);
        $chargeToken = $response['response']['token'];

        $response = $this->pinClient->getChargeDetails($chargeToken);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($chargeToken, $response->toArray()['response']['token']);
    }
}
