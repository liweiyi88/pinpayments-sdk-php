<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Customers;

use Liweiyi\PinPayments\Parameters\CardParameter;

final class UpdateCustomerPrimaryCardParameter extends AbstractUpdateCustomerParameter
{
    private $card;

    public function __construct(CardParameter $card, ?string $email = null)
    {
        parent::__construct($email);
        $this->card = $card;
    }

    public function getPayload(): array
    {
        $payload = parent::getPayload();
        $payload['card'] = $this->card->getPayload();

        return $payload;
    }
}
