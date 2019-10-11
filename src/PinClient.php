<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments;

use Liweiyi\PinPayments\Parameters\Cards\StoreCardParameter;
use Liweiyi\PinPayments\Parameters\Charges\ChargeCardParameter;
use Liweiyi\PinPayments\Parameters\Charges\ChargeCardTokenParameter;
use Liweiyi\PinPayments\Parameters\Charges\ChargeCustomerTokenParameter;
use Liweiyi\PinPayments\Parameters\ParameterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class PinClient
{
    public const API_VERSION = '1';

    /**
     * Pin Payments test endpoint
     *
     * @var string
     */
    private $testEndpoint = 'https://test-api.pinpayments.com/';

    /**
     * Pin Payments live endpoint
     *
     * @var string
     */
    private $liveEndpoint = 'https://api.pinpayments.com/';

    /**
     * The Symfony http client.
     *
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private $httpClient;

    /**
     * The Pin Payments api key.
     *
     * @var string
     */
    private $apiKey;

    /**
     * Test mode or not.
     *
     * @var bool
     */
    private $isTestMode;

    public function __construct(string $apiKey, HttpClientInterface $httpClient, bool $isTestMode = true)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->isTestMode = $isTestMode;
    }

    /**
     * Capture a charge token.
     *
     * @param string $chargeToken
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function capture(string $chargeToken): ResponseInterface
    {
        $url = $this->getEndpoint() . '/charges/' . $chargeToken . '/capture';

        return $this->sendRequest('PUT', $url);
    }

    /**
     * @param \Liweiyi\PinPayments\Parameters\Charges\ChargeCardParameter $parameter
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function chargeCard(ChargeCardParameter $parameter): ResponseInterface
    {
        return $this->sendRequest('POST', $this->getEndpoint() . '/charges', $parameter);
    }

    /**
     * Charge customer token.
     *
     * @param \Liweiyi\PinPayments\Parameters\Charges\ChargeCustomerTokenParameter $parameter
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function chargeCustomerToken(ChargeCustomerTokenParameter $parameter): ResponseInterface
    {
        return $this->sendRequest('POST', $this->getEndpoint() . '/charges', $parameter);
    }

    /**
     * Charge credit card token.
     *
     * @param \Liweiyi\PinPayments\Parameters\Charges\ChargeCardTokenParameter $parameter
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function chargeCardToken(ChargeCardTokenParameter $parameter): ResponseInterface
    {
        return $this->sendRequest('POST', $this->getEndpoint() . '/charges', $parameter);
    }

    /**
     * Get paginated list of all charges.
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function listCharges(): ResponseInterface
    {
        return $this->sendRequest('GET', $this->getEndpoint() . '/charges');
    }

    /**
     * Store credit card in Pin Payments.
     *
     * @param \Liweiyi\PinPayments\Parameters\Cards\StoreCardParameter $parameter
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function storeCard(StoreCardParameter $parameter): ResponseInterface
    {
        return $this->sendRequest('POST', $this->getEndpoint() . '/cards', $parameter);
    }

    /**
     * Get api endpoint based on mode.
     *
     * @return string
     */
    private function getEndpoint(): string
    {
        $base = $this->isTestMode === true ? $this->testEndpoint : $this->liveEndpoint;

        return $base . self::API_VERSION;
    }

    /**
     * Send api request to Pin Payments.
     *
     * @param string $method
     * @param string $url
     * @param null|ParameterInterface $parameter
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function sendRequest(string $method, string $url, ?ParameterInterface $parameter = null): ResponseInterface
    {
        $options = [
            'auth_basic' => [$this->apiKey]
        ];

        if (null !== $parameter) {
            $options['body'] = $parameter->getPayload();
        }

        return $this->httpClient->request($method, $url, $options);
    }
}
