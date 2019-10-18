<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit\Parameters\Customers;

use Liweiyi\PinPayments\Parameters\Customers\UpdateCustomerPrimaryCardParameter;
use Liweiyi\PinPayments\Tests\TestCardsTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Liweiyi\PinPayments\Parameters\Customers\AbstractUpdateCustomerParameter
 * @covers \Liweiyi\PinPayments\Parameters\Customers\UpdateCustomerPrimaryCardParameter
 */
final class UpdateCustomerPrimaryCardParameterTest extends TestCase
{
    use TestCardsTrait;

    public function testGetPayload(): void
    {
        $card = $this->createTestValidVisaCard();
        $parameter = new UpdateCustomerPrimaryCardParameter(
            $card,
            'test@example.com'
        );

        $this->assertSame([
            'email' => 'test@example.com',
            'card' => $card->getPayload()
        ], $parameter->getPayload());
    }
}
