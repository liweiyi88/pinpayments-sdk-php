<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit\Parameters\Customers;

use Liweiyi\PinPayments\Parameters\Customers\UpdateCustomerSwitchPrimaryCardTokenParameter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Liweiyi\PinPayments\Parameters\Customers\UpdateCustomerSwitchPrimaryCardTokenParameter
 */
final class UpdateCustomerSwitchPrimaryCardTokenParameterTest extends TestCase
{
    public function testGetPayload(): void
    {
        $parameter = new UpdateCustomerSwitchPrimaryCardTokenParameter('card_token');

        $this->assertSame([
            'primary_card_token' => 'card_token'
        ], $parameter->getPayload());
    }
}
