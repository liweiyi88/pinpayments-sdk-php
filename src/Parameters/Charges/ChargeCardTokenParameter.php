<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Charges;

final class ChargeCardTokenParameter extends BaseChargeParameter
{
    private $cardToken;

    public function __construct(string $email, string $description, int $amount, string $ipAddress, string $cardToken)
    {
        parent::__construct($email, $description, $amount, $ipAddress);
        $this->cardToken = $cardToken;
    }

    public function getPayload(): array
    {
        $payload = parent::getPayload();
        $payload['card_token'] = $this->cardToken;

        return $payload;
    }
}
