<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit\Parameters\Customers;

use Liweiyi\PinPayments\Parameters\Customers\CreateCardTokenCustomerParameter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Liweiyi\PinPayments\Parameters\Customers\CreateCardTokenCustomerParameter
 */
final class CreateCardTokenCustomerParameterTest extends TestCase
{
    public function testGetPayload(): void
    {
        $parameter = new CreateCardTokenCustomerParameter('test@example.com', 'card_token');

        $this->assertSame([
            'email' => 'test@example.com',
            'card_token' => 'card_token'
        ], $parameter->getPayload());
    }
}
