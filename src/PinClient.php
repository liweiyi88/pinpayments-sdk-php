<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments;

use Liweiyi\PinPayments\Parameters\CardParameter;
use Liweiyi\PinPayments\Parameters\Charges\AbstractChargeParameter;
use Liweiyi\PinPayments\Parameters\Customers\AbstractUpdateCustomerParameter;
use Liweiyi\PinPayments\Parameters\Customers\CreateCardCustomerParameter;
use Liweiyi\PinPayments\Parameters\Customers\CreateCardTokenCustomerParameter;
use Liweiyi\PinPayments\Parameters\Customers\CreateCustomerCardParameterInterface;
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
     * @param \Liweiyi\PinPayments\Parameters\Charges\AbstractChargeParameter $parameter
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function charge(AbstractChargeParameter $parameter): ResponseInterface
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
     * Get the details of a charge.
     *
     * @param string $chargeToken
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getChargeDetails(string $chargeToken): ResponseInterface
    {
        return $this->sendRequest('GET', $this->getEndpoint() . '/charges/' . $chargeToken);
    }

    /**
     * Create a customer by card.
     *
     * @param \Liweiyi\PinPayments\Parameters\Customers\CreateCardCustomerParameter $parameter
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function createCustomerByCard(CreateCardCustomerParameter $parameter): ResponseInterface
    {
        return $this->sendRequest('POST', $this->getEndpoint() . '/customers', $parameter);
    }

    /**
     * Create a customer by card token.
     *
     * @param \Liweiyi\PinPayments\Parameters\Customers\CreateCardTokenCustomerParameter $parameter
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function createCustomerByCardToken(CreateCardTokenCustomerParameter $parameter): ResponseInterface
    {
        return $this->sendRequest('POST', $this->getEndpoint() . '/customers', $parameter);
    }

    /**
     * Get a paginated list of all customers.
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function listCustomers(): ResponseInterface
    {
        return $this->sendRequest('GET', $this->getEndpoint() . '/customers');
    }

    /**
     * Get a customer details.
     *
     * @param string $customerToken
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getCustomerDetails(string $customerToken): ResponseInterface
    {
        return $this->sendRequest('GET', $this->getEndpoint() . '/customers/' . $customerToken);
    }

    /**
     * @param string $customerToken
     * @param \Liweiyi\PinPayments\Parameters\Customers\AbstractUpdateCustomerParameter $parameter
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function updateCustomer(string $customerToken, AbstractUpdateCustomerParameter $parameter): ResponseInterface
    {
        return $this->sendRequest('PUT', $this->getEndpoint() . '/customers/' . $customerToken, $parameter);
    }

    /**
     * Delete a customer with its all cards.
     *
     * @param string $customerToken
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function deleteCustomer(string $customerToken): ResponseInterface
    {
        return $this->sendRequest('DELETE', $this->getEndpoint() . '/customers/' . $customerToken);
    }

    /**
     * Get a paginated list of a customer's charge.
     *
     * @param string $customerToken
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function listCustomerCharges(string $customerToken): ResponseInterface
    {
        return $this->sendRequest('GET', $this->getEndpoint() . '/customers/' . $customerToken . '/charges');
    }

    /**
     * Get a paginated list of a customer's cards.
     *
     * @param string $customerToken
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function listCustomerCards(string $customerToken): ResponseInterface
    {
        return $this->sendRequest('GET', $this->getEndpoint() . '/customers/' . $customerToken . '/cards');
    }

    /**
     * Create a new card for customer.
     *
     * @param string $customerToken
     * @param \Liweiyi\PinPayments\Parameters\Customers\CreateCustomerCardParameterInterface $parameter
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function createCustomerCard(
        string $customerToken,
        CreateCustomerCardParameterInterface $parameter
    ): ResponseInterface {
        return $this->sendRequest('POST', $this->getEndpoint() . '/customers/' . $customerToken . '/cards', $parameter);
    }

    /**
     * Delete a non primary card of a customer.
     *
     * @param string $customerToken
     * @param string $cardToken
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function deleteCustomerNonPrimaryCard(string $customerToken, string $cardToken): ResponseInterface
    {
        return $this->sendRequest(
            'DELETE',
            $this->getEndpoint() . '/customers/' . $customerToken . '/cards/' . $cardToken
        );
    }

    /**
     * List a customer subscriptions.
     *
     * @param string $customerToken
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function listCustomerSubscription(string $customerToken): ResponseInterface
    {
        return $this->sendRequest('GET', $this->getEndpoint() . '/customers/' . $customerToken . '/subscriptions');
    }

    /**
     * Store credit card in Pin Payments.
     *
     * @param \Liweiyi\PinPayments\Parameters\CardParameter $parameter
     *
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function storeCard(CardParameter $parameter): ResponseInterface
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
