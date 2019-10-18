<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit\Parameters\Customers;

use Liweiyi\PinPayments\Parameters\Customers\CreateCustomerCardByTokenParameter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Liweiyi\PinPayments\Parameters\Customers\CreateCustomerCardByTokenParameter
 */
final class CreateCustomerCardByTokenParameterTest extends TestCase
{
    public function testGetPayload(): void
    {
        $parameter = new CreateCustomerCardByTokenParameter('card_token');

        $this->assertSame([
            'card_token' => 'card_token'
        ], $parameter->getPayload());
    }
}
