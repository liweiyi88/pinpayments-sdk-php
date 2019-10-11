<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit\Parameters\Charges;

use Liweiyi\PinPayments\Parameters\Charges\ChargeCardTokenParameter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Liweiyi\PinPayments\Parameters\Charges\ChargeCardTokenParameter
 */
class ChargeCardTokenParameterTest extends TestCase
{
    public function testGetPayload(): void
    {
        $parameter = new ChargeCardTokenParameter(
            'test@example.com',
            'Unit Test',
            100,
            '127.0.0.1',
            'card_token_1234567'
        );

        $expected = [
            'email' => 'test@example.com',
            'description' => 'Unit Test',
            'amount' => 100,
            'ip_address' => '127.0.0.1',
            'currency' => 'AUD',
            'capture' => 'true',
            'card_token' => 'card_token_1234567'
        ];

        $this->assertSame($expected, $parameter->getPayload());
    }
}
