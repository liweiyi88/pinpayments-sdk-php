<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests;

use Liweiyi\PinPayments\Parameters\CardParameter;

trait TestCardsTrait
{
    public function createTestValidCard(): CardParameter
    {
        return new CardParameter(
            '4200000000000000',
            '01',
            (string)((int)date('Y') + 1),
            '123',
            'Julian Li',
            '18 Lower Esplanade',
            'Melbourne',
            'Australia'
        );
    }
}
