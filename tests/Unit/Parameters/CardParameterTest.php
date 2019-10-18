<?php

declare(strict_types=1);

namespace Liweiyi\PinPayments\Tests\Unit\Parameters;

use Liweiyi\PinPayments\Tests\TestCardsTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Liweiyi\PinPayments\Parameters\CardParameter
 */
final class CardParameterTest extends TestCase
{
    use TestCardsTrait;

    public function testPayload(): void
    {
        $cardParameter = $this->createTestValidVisaCard()
            ->setPostcode('3000')
            ->setState('VIC')
            ->setAddressLine2('a secret place')
            ->setPublishableApiKey('my_publish_key');

        $expected = [
            'number' => '4200000000000000',
            'expiry_month' => '01',
            'expiry_year' => (string)((int)date('Y') + 1),
            'cvc' => '123',
            'name' => 'Julian Li',
            'address_line1' => '18 Lower Esplanade',
            'address_city' => 'Melbourne',
            'address_country' => 'Australia',
            'address_line2' => 'a secret place',
            'address_postcode' => '3000',
            'address_state' => 'VIC',
            'publishable_api_key' => 'my_publish_key'
        ];

        $this->assertSame($expected, $cardParameter->getPayload());
    }
}
