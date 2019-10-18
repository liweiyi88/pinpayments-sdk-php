<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit\Parameters\Customers;

use Liweiyi\PinPayments\Parameters\Customers\CreateCardCustomerParameter;
use Liweiyi\PinPayments\Tests\TestCardsTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Liweiyi\PinPayments\Parameters\Customers\CreateCardCustomerParameter
 */
final class CreateCardCustomerParameterTest extends TestCase
{
    use TestCardsTrait;

    public function testGetPayload(): void
    {
        $card = $this->createTestValidVisaCard();
        $parameter = new CreateCardCustomerParameter('test@example.com', $card);
        $expected = [
            'email' => 'test@example.com',
            'card' => $card->getPayload()
        ];

        $this->assertSame($expected, $parameter->getPayload());
    }
}
