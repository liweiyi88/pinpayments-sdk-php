<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Customers;

use Liweiyi\PinPayments\Parameters\ParameterInterface;

abstract class AbstractUpdateCustomerParameter implements ParameterInterface
{
    protected $email;

    public function __construct(?string $email)
    {
        $this->email = $email;
    }

    public function getPayload(): array
    {
        $payload = [];

        if (null !== $this->email) {
            $payload['email'] = $this->email;
        }

        return $payload;
    }
}
