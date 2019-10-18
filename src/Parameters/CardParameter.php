<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters;

use Liweiyi\PinPayments\Parameters\Customers\CreateCustomerCardParameterInterface;

final class CardParameter implements CreateCustomerCardParameterInterface
{
    private $number;
    private $expiryMonth;
    private $expiryYear;
    private $cvc;
    private $name;
    private $addressLine1;
    private $addressLine2;
    private $city;
    private $country;
    private $postcode;
    private $state;
    private $publishableApiKey;

    public function __construct(
        string $number,
        string $expiryMonth,
        string $expiryYear,
        string $cvc,
        string $name,
        string $addressLine1,
        string $city,
        string $country,
        ?string $addressLine2 = null,
        ?string $postcode = null,
        ?string $state = null,
        ?string $publishableApiKey = null
    ) {
        $this->number = $number;
        $this->expiryMonth = $expiryMonth;
        $this->expiryYear = $expiryYear;
        $this->cvc = $cvc;
        $this->name = $name;
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->city = $city;
        $this->country = $country;
        $this->postcode = $postcode;
        $this->state = $state;
        $this->publishableApiKey = $publishableApiKey;
    }

    public function setAddressLine2(?string $addressLine2 = null): self
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    public function setPostcode(?string $postcode = null): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function setState(?string $state = null): self
    {
        $this->state = $state;

        return $this;
    }

    public function setPublishableApiKey(?string $publishableApiKey = null): self
    {
        $this->publishableApiKey = $publishableApiKey;

        return $this;
    }

    public function getPayload(): array
    {
        $payload = [
            'number' => $this->number,
            'expiry_month' => $this->expiryMonth,
            'expiry_year' => $this->expiryYear,
            'cvc' => $this->cvc,
            'name' => $this->name,
            'address_line1' => $this->addressLine1,
            'address_city' => $this->city,
            'address_country' => $this->country
        ];

        if (null !== $this->addressLine2) {
            $payload['address_line2'] = $this->addressLine2;
        }

        if (null !== $this->postcode) {
            $payload['address_postcode'] = $this->postcode;
        }

        if (null !== $this->state) {
            $payload['address_state'] = $this->state;
        }

        if (null !== $this->publishableApiKey) {
            $payload['publishable_api_key'] = $this->publishableApiKey;
        }

        return $payload;
    }
}
