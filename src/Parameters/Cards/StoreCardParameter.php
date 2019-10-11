<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Cards;

use Liweiyi\PinPayments\Parameters\CardParameter;

final class StoreCardParameter extends CardParameter
{
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
        parent::__construct(
            $number,
            $expiryMonth,
            $expiryYear,
            $cvc,
            $name,
            $addressLine1,
            $city,
            $country,
            $addressLine2,
            $postcode,
            $state
        );

        $this->publishableApiKey = $publishableApiKey;
    }

    public static function createFromCardParameter(CardParameter $cardParameter): self
    {
        return new self(
            $cardParameter->getNumber(),
            $cardParameter->getExpiryMonth(),
            $cardParameter->getExpiryYear(),
            $cardParameter->getCvc(),
            $cardParameter->getName(),
            $cardParameter->getAddressLine1(),
            $cardParameter->getCity(),
            $cardParameter->getCountry(),
            $cardParameter->getAddressLine2(),
            $cardParameter->getPostcode(),
            $cardParameter->getState()
        );
    }

    public function setPublishableApiKey(?string $publishableApiKey = null): self
    {
        $this->publishableApiKey = $publishableApiKey;

        return $this;
    }

    public function getPayload(): array
    {
        $payload = parent::getPayload();

        if (null !== $this->publishableApiKey) {
            $payload['publishable_api_key'] = $this->publishableApiKey;
        }

        return $payload;
    }
}
