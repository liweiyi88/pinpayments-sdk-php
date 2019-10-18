<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Functional;

use Liweiyi\PinPayments\Parameters\Customers\CreateCardCustomerParameter;
use Liweiyi\PinPayments\Parameters\Customers\CreateCardTokenCustomerParameter;
use Liweiyi\PinPayments\Parameters\Customers\CreateCustomerCardByTokenParameter;
use Liweiyi\PinPayments\Parameters\Customers\UpdateCustomerPrimaryCardTokenParameter;
use Liweiyi\PinPayments\Parameters\Customers\UpdateCustomerPrimaryCardParameter;
use Liweiyi\PinPayments\Parameters\Customers\UpdateCustomerSwitchPrimaryCardTokenParameter;

/**
 * @covers \Liweiyi\PinPayments\PinClient
 */
final class CustomersTest extends PinClientTest
{
    /**
     * Test create a customer by card and delete a customer.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testCreateCustomerByCard(): void
    {
        $parameter = new CreateCardCustomerParameter('test@example.com', $this->validVisaCard);

        $response = $this->pinClient->createCustomerByCard($parameter);
        $this->assertSame(201, $response->getStatusCode());
        $this->assertArrayHasKey('response', $response->toArray());

        $response = $this->pinClient->deleteCustomer($response->toArray()['response']['token']);
        $this->assertSame(204, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }

    /**
     * Test create a customer by card token.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testCreateCustomerByCardToken(): void
    {
        $cardToken = $this->createCardToken('visa');

        $parameter = new CreateCardTokenCustomerParameter('test_card_token@example.com', $cardToken);
        $response = $this->pinClient->createCustomerByCardToken($parameter);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertArrayHasKey('response', $response->toArray());

        $response = $this->pinClient->deleteCustomer($response->toArray()['response']['token']);
        $this->assertSame(204, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }

    /**
     * Test get a paginated list of all customers.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testListCustomers(): void
    {
        $response = $this->pinClient->listCustomers();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertArrayHasKey('response', $response->toArray());
    }

    /**
     * Test get a customer details.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testGetCustomerDetails(): void
    {
        $customerToken = $this->createCardCustomerToken('test_get_customer_detail@example.com', 'visa');

        $customerResponse = $this->pinClient->getCustomerDetails($customerToken);
        $this->assertSame(200, $customerResponse->getStatusCode());
        $this->assertSame('test_get_customer_detail@example.com', $customerResponse->toArray()['response']['email']);

        $this->pinClient->deleteCustomer($customerToken);
    }

    /**
     * Test update a customer's primary card.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testUpdateCustomerPrimaryCard(): void
    {
        $customerToken = $this->createCardCustomerToken('test_update_customer_card@example.com', 'visa');

        $parameter = new UpdateCustomerPrimaryCardParameter(
            $this->createTestValidMasterCard(),
            'new_email@example.com'
        );

        $response = $this->pinClient->updateCustomer($customerToken, $parameter);

        $this->assertSame('master', $response->toArray()['response']['card']['scheme']);
        $this->assertSame('new_email@example.com', $response->toArray()['response']['email']);
        $this->pinClient->deleteCustomer($customerToken);
    }

    /**
     * Test update a customer's primary card by card token.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testUpdateCustomerPrimaryCardToken(): void
    {
        $cardToken = $this->createCardToken('master');

        $customerToken = $this->createCardCustomerToken('test_update_customer_card@example.com', 'visa');

        $parameter = new UpdateCustomerPrimaryCardTokenParameter($cardToken);
        $response = $this->pinClient->updateCustomer($customerToken, $parameter);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('master', $response->toArray()['response']['card']['scheme']);

        $this->pinClient->deleteCustomer($customerToken);
    }

    /**
     * Test switch a customer's primary card by token.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testUpdateCustomerSwitchPrimaryCardToken(): void
    {
        $masterCardToken = $this->createCardToken('master');

        $visaCardCustomerToken = $this->createCardCustomerToken(
            'test_update_customer_switch_primary_card@example.com',
            'visa'
        );

        $parameter = new UpdateCustomerSwitchPrimaryCardTokenParameter($masterCardToken);
        $response = $this->pinClient->updateCustomer($visaCardCustomerToken, $parameter);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('master', $response->toArray()['response']['card']['scheme']);

        $cardsOfCustomer = $this->pinClient->listCustomerCards($visaCardCustomerToken)->toArray()['response'];
        $this->assertCount(2, $cardsOfCustomer);

        $this->pinClient->deleteCustomer($visaCardCustomerToken);
    }

    /**
     * Test list customer charges.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testListCustomerCharges(): void
    {
        $customerToken = $this->createCardCustomerToken('test_list_customer_charge@example.com', 'visa');
        $response = $this->pinClient->listCustomerCharges($customerToken);

        $this->assertSame(200, $response->getStatusCode());

        $this->pinClient->deleteCustomer($customerToken);
    }

    /**
     * Test list customer cards.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testListCustomerCards(): void
    {
        $customerToken = $this->createCardCustomerToken('test_list_customer_charge@example.com', 'visa');
        $response = $this->pinClient->listCustomerCards($customerToken);

        $this->assertSame(200, $response->getStatusCode());

        $this->pinClient->deleteCustomer($customerToken);
    }

    /**
     * Test create a new card for customer.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testCreateCustomerCard(): void
    {
        $customerToken = $this->createCardCustomerToken('test_list_customer_charge@example.com', 'visa');
        $this->pinClient->createCustomerCard($customerToken, $this->validMasterCard);

        $response = $this->pinClient->listCustomerCards($customerToken)->toArray();
        $this->assertCount(2, $response['response']);

        $this->pinClient->deleteCustomer($customerToken);
    }

    /**
     * Test create a new card for customer by card token.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testCreateCustomerCardByCardToken(): void
    {
        $customerToken = $this->createCardCustomerToken('test_list_customer_charge@example.com', 'visa');
        $cardToken = $this->createCardToken('master');
        $this->pinClient->createCustomerCard($customerToken, new CreateCustomerCardByTokenParameter($cardToken));

        $response = $this->pinClient->listCustomerCards($customerToken)->toArray();
        $this->assertCount(2, $response['response']);

        $this->pinClient->deleteCustomer($customerToken);
    }

    /**
     * Test delete a customer non-primary card.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testDeleteCustomerNonPrimaryCard(): void
    {
        $masterCardToken = $this->createCardToken('master');
        $visaCardToken = $this->createCardToken('visa');

        $customerParameter = new CreateCardTokenCustomerParameter(
            'test_delete_customer_non_primary_card@example.com',
            $visaCardToken
        );

        $visaCardCustomerToken = $this->pinClient
            ->createCustomerByCardToken($customerParameter)
            ->toArray()['response']['token'];

        $parameter = new UpdateCustomerSwitchPrimaryCardTokenParameter($masterCardToken);
        $this->pinClient->updateCustomer($visaCardCustomerToken, $parameter);

        $response = $this->pinClient->deleteCustomerNonPrimaryCard($visaCardCustomerToken, $visaCardToken);
        $this->assertSame(204, $response->getStatusCode());

        $this->pinClient->deleteCustomer($visaCardCustomerToken);
    }

    /**
     * Test list customer subscriptions.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testListCustomerSubscriptions(): void
    {
        $customerToken = $this->createCardCustomerToken('test_list_customer_subscription@example.com', 'visa');
        $response = $this->pinClient->listCustomerSubscription($customerToken);
        $this->assertSame(200, $response->getStatusCode());
        $this->pinClient->deleteCustomer($customerToken);
    }
}
