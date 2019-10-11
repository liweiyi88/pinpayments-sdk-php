<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Parameters;

interface ParameterInterface
{
    public function getPayload(): array;
}
