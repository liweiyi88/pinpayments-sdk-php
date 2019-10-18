<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Functional;

use Liweiyi\PinPayments\Parameters\CardParameter;
use Liweiyi\PinPayments\Parameters\Customers\CreateCardCustomerParameter;
use Liweiyi\PinPayments\PinClient;
use Liweiyi\PinPayments\Tests\TestCardsTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

abstract class PinClientTest extends TestCase
{
    use TestCardsTrait;

    /**
     * @var \Liweiyi\PinPayments\PinClient
     */
    protected $pinClient;

    /**
     * @var \Liweiyi\PinPayments\Parameters\CardParameter
     */
    protected $validVisaCard;

    /**
     * @var \Liweiyi\PinPayments\Parameters\CardParameter
     */
    protected $validMasterCard;

    public function setUp(): void
    {
        parent::setUp();

        $apiKey = (string)\getenv('PIN_PAYMENTS_API_KEY');
        $this->pinClient = new PinClient($apiKey, HttpClient::create());
        $this->validVisaCard = $this->getValidCardParameter('visa');
        $this->validMasterCard = $this->getValidCardParameter('master');
    }

    /**
     * @param string $email
     * @param string $cardType
     *
     * @return string
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function createCardCustomerToken(string $email, string $cardType): string
    {
        $cardCustomer = new CreateCardCustomerParameter(
            $email,
            $this->getValidCardParameter($cardType)
        );

        $response = $this->pinClient->createCustomerByCard($cardCustomer)->toArray();
        return $response['response']['token'];
    }

    /**
     * @param string $cardType
     *
     * @return string
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function createCardToken(string $cardType): string
    {
        $card = $this->getValidCardParameter($cardType);
        $cardResponse = $this->pinClient->storeCard($card)->toArray();
        return $cardResponse['response']['token'];
    }

    private function getValidCardParameter(string $cardType): CardParameter
    {
        if ($cardType !== 'visa' && $cardType !== 'master') {
            throw new \InvalidArgumentException('invalid card type');
        }

        if ($cardType === 'visa') {
            return $this->createTestValidVisaCard();
        }

        return $this->createTestValidMasterCard();
    }
}
