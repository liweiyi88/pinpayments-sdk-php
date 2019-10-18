<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Customers;

use Liweiyi\PinPayments\Parameters\CardParameter;
use Liweiyi\PinPayments\Parameters\ParameterInterface;

final class CreateCardCustomerParameter implements ParameterInterface
{
    private $email;
    private $card;

    public function __construct(string $email, CardParameter $card)
    {
        $this->email = $email;
        $this->card = $card;
    }

    public function getPayload(): array
    {
        return [
            'email' => $this->email,
            'card' => $this->card->getPayload()
        ];
    }
}
