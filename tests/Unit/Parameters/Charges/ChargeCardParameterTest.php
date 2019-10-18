<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit\Parameters\Charges;

use Liweiyi\PinPayments\Parameters\Charges\ChargeCardParameter;
use Liweiyi\PinPayments\Tests\TestCardsTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Liweiyi\PinPayments\Parameters\Charges\ChargeCardParameter
 * @covers \Liweiyi\PinPayments\Parameters\Charges\AbstractChargeParameter
 */
final class ChargeCardParameterTest extends TestCase
{
    use TestCardsTrait;

    public function testGetPayload(): void
    {
        $card = $this->createTestValidVisaCard();

        $parameter = (new ChargeCardParameter(
            'test@example.com',
            'Unit Test',
            100,
            '127.0.0.1',
            $card
        ))->setCurrency('USD')
            ->setCapture(false)
            ->setMetadata(['Meta' => 'metadata']);

        $expected = [
            'email' => 'test@example.com',
            'description' => 'Unit Test',
            'amount' => 100,
            'ip_address' => '127.0.0.1',
            'currency' => 'USD',
            'capture' => 'false',
            'metadata' => ['Meta' => 'metadata'],
            'card' => $card->getPayload()
        ];

        $this->assertSame($expected, $parameter->getPayload());
    }
}
