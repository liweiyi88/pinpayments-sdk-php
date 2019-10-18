<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit\Parameters\Customers;

use Liweiyi\PinPayments\Parameters\Customers\UpdateCustomerPrimaryCardTokenParameter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Liweiyi\PinPayments\Parameters\Customers\UpdateCustomerPrimaryCardTokenParameter
 */
final class UpdateCustomerPrimaryCardTokenParameterTest extends TestCase
{
    public function testGetPayload(): void
    {
        $parameter = new UpdateCustomerPrimaryCardTokenParameter('card_token');

        $this->assertSame([
            'card_token' => 'card_token'
        ], $parameter->getPayload());
    }
}
