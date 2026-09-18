<?php

use PHPUnit\Framework\TestCase;

final class ValidationTest extends TestCase
{
    public function testValidShippingFields(): void
    {
        $this->assertTrue(valid_name('Ada Lovelace'));
        $this->assertTrue(valid_address('10 Main St.'));
        $this->assertTrue(valid_email('ada@example.com'));
        $this->assertTrue(valid_zip('12345-6789'));
        $this->assertTrue(valid_phone('+1 555-123-4567'));
    }

    public function testInvalidShippingFields(): void
    {
        $this->assertFalse(valid_name('<script>'));

        $this->assertFalse(
            valid_address("10 Main St'; DROP TABLE food;--")
        );

        $this->assertFalse(valid_email('not-an-email'));
        $this->assertFalse(valid_zip('1234'));
        $this->assertFalse(valid_phone('abc'));
    }

    public function testShippingValidationReturnsErrors(): void
    {
        $errors = validate_shipping_data([
            'first_name' => '',
            'last_name' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'email' => '',
            'phone' => '',
        ]);

        $this->assertArrayHasKey('first_name', $errors);
        $this->assertArrayHasKey('last_name', $errors);
        $this->assertArrayHasKey('street', $errors);
        $this->assertArrayHasKey('city', $errors);
        $this->assertArrayHasKey('state', $errors);
        $this->assertArrayHasKey('zip', $errors);
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('phone', $errors);
    }

    public function testShippingValidationAcceptsValidData(): void
    {
        $errors = validate_shipping_data([
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'street' => '10 Main St.',
            'city' => 'London',
            'state' => 'CA',
            'zip' => '12345',
            'email' => 'ada@example.com',
            'phone' => '+1 555-123-4567',
        ]);

        $this->assertSame([], $errors);
    }
}
