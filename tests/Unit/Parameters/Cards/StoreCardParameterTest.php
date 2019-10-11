<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit\Parameters\Cards;

use Liweiyi\PinPayments\Parameters\CardParameter;
use Liweiyi\PinPayments\Parameters\Cards\StoreCardParameter;
use Liweiyi\PinPayments\Tests\TestCardsTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Liweiyi\PinPayments\Parameters\Cards\StoreCardParameter
 * @covers \Liweiyi\PinPayments\Parameters\CardParameter
 */
class StoreCardParameterTest extends TestCase
{
    use TestCardsTrait;

    public function testGetPayload(): void
    {
        $card = $this->createTestValidCard();

        $parameter = StoreCardParameter::createFromCardParameter($card)
            ->setPublishableApiKey('my_published_key');

        $expected = [
            'number' => '4200000000000000',
            'expiry_month' => '01',
            'expiry_year' => (string)((int)date('Y') + 1),
            'cvc' => '123',
            'name' => 'Julian Li',
            'address_line1' => '18 Lower Esplanade',
            'address_city' => 'Melbourne',
            'address_country' => 'Australia',
            'publishable_api_key' => 'my_published_key'
        ];

        $this->assertSame($expected, $parameter->getPayload());
    }
}
