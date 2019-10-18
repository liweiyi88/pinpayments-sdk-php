<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Customers;

use Liweiyi\PinPayments\Parameters\ParameterInterface;

final class CreateCardTokenCustomerParameter implements ParameterInterface
{
    private $email;
    private $cardToken;

    public function __construct(string $email, string $cardToken)
    {
        $this->email = $email;
        $this->cardToken = $cardToken;
    }

    public function getPayload(): array
    {
        return [
            'email' => $this->email,
            'card_token' => $this->cardToken
        ];
    }
}
