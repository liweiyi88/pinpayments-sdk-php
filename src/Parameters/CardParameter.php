<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters;

final class CardParameter implements ParameterInterface
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
        ?string $state = null
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
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getExpiryMonth(): string
    {
        return $this->expiryMonth;
    }

    public function getExpiryYear(): string
    {
        return $this->expiryYear;
    }

    public function getCvc(): string
    {
        return $this->cvc;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddressLine1(): string
    {
        return $this->addressLine1;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setAddressLine2(?string $addressLine2): self
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

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

        return $payload;
    }
}
