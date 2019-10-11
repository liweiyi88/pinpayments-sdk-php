<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Charges;

use Liweiyi\PinPayments\Parameters\CardParameter;

final class ChargeCardParameter extends BaseChargeParameter
{
    private $card;

    public function __construct(string $email, string $description, int $amount, string $ipAddress, CardParameter $card)
    {
        parent::__construct($email, $description, $amount, $ipAddress);
        $this->card = $card;
    }

    public function getPayload(): array
    {
        $payload = parent::getPayload();
        $payload['card'] = $this->card->getPayload();

        return $payload;
    }
}
