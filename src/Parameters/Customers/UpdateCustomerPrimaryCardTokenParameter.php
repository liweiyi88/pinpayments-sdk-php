<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Customers;

final class UpdateCustomerPrimaryCardTokenParameter extends AbstractUpdateCustomerParameter
{
    private $cardToken;

    public function __construct(string $cardToken, ?string $email = null)
    {
        parent::__construct($email);

        $this->cardToken = $cardToken;
    }

    public function getPayload(): array
    {
        $payload = parent::getPayload();

        $payload['card_token'] = $this->cardToken;

        return $payload;
    }
}
