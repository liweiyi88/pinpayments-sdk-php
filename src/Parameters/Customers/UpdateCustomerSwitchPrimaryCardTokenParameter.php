<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Customers;

final class UpdateCustomerSwitchPrimaryCardTokenParameter extends AbstractUpdateCustomerParameter
{
    private $primaryCardToken;

    public function __construct(string $primaryCardToken, ?string $email = null)
    {
        parent::__construct($email);

        $this->primaryCardToken = $primaryCardToken;
    }

    public function getPayload(): array
    {
        $payload = parent::getPayload();

        $payload['primary_card_token'] = $this->primaryCardToken;

        return $payload;
    }
}
