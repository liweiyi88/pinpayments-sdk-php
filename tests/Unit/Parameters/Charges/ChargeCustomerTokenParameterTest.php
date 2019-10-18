<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit\Parameters\Charges;

use Liweiyi\PinPayments\Parameters\Charges\ChargeCustomerTokenParameter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Liweiyi\PinPayments\Parameters\Charges\ChargeCustomerTokenParameter
 */
final class ChargeCustomerTokenParameterTest extends TestCase
{
    public function testGetPayload(): void
    {
        $parameter = new ChargeCustomerTokenParameter(
            'test@example.com',
            'Unit Test',
            100,
            '127.0.0.1',
            'customer_token_1234567'
        );

        $expected = [
            'email' => 'test@example.com',
            'description' => 'Unit Test',
            'amount' => 100,
            'ip_address' => '127.0.0.1',
            'currency' => 'AUD',
            'capture' => 'true',
            'customer_token' => 'customer_token_1234567'
        ];

        $this->assertSame($expected, $parameter->getPayload());
    }
}
