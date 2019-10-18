<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Customers;

final class CreateCustomerCardByTokenParameter implements CreateCustomerCardParameterInterface
{
    private $cardToken;

    public function __construct(string $cardToken)
    {
        $this->cardToken = $cardToken;
    }

    public function getPayload(): array
    {
        return [
            'card_token' => $this->cardToken
        ];
    }
}
