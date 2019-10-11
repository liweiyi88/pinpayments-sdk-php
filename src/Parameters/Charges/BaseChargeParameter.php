<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters\Charges;

use Liweiyi\PinPayments\Parameters\ParameterInterface;

abstract class BaseChargeParameter implements ParameterInterface
{
    private $email;
    private $description;
    private $amount;
    private $ipAddress;
    private $currency;
    private $capture;
    private $metadata;

    public function __construct(string $email, string $description, int $amount, string $ipAddress)
    {
        $this->email = $email;
        $this->description = $description;
        $this->amount = $amount;
        $this->ipAddress = $ipAddress;
        $this->currency = 'AUD';
        $this->capture = true;
        $this->metadata = [];
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function setCapture(bool $capture): self
    {
        $this->capture = $capture;

        return $this;
    }

    public function setMetadata(array $metadata = []): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getPayload(): array
    {
        $payload = [
            'email' => $this->email,
            'description' => $this->description,
            'amount' => $this->amount,
            'ip_address' => $this->ipAddress,
            'currency' => $this->currency,
            'capture' => $this->capture !== true ? 'false' : 'true'
        ];

        if (\count($this->metadata) > 0) {
            $payload['metadata'] = $this->metadata;
        }

        return $payload;
    }
}
