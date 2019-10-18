<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Charges;

final class ChargeCustomerTokenParameter extends AbstractChargeParameter
{
    private $customerToken;

    public function __construct(
        string $email,
        string $description,
        int $amount,
        string $ipAddress,
        string $customerToken
    ) {
        parent::__construct($email, $description, $amount, $ipAddress);
        $this->customerToken = $customerToken;
    }

    public function getPayload(): array
    {
        $payload = parent::getPayload();
        $payload['customer_token'] = $this->customerToken;

        return $payload;
    }
}
