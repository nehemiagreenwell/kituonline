<?php

function valid_name(string $value): bool
{
    return (bool) preg_match(
        "/^[a-zA-Z' -]{1,50}$/",
        trim($value)
    );
}

function valid_address(string $value): bool
{
    return (bool) preg_match(
        "/^[a-zA-Z0-9.,' -]{1,100}$/",
        trim($value)
    );
}

function valid_email(string $value): bool
{
    return filter_var(trim($value), FILTER_VALIDATE_EMAIL) !== false;
}

function valid_zip(string $value): bool
{
    return (bool) preg_match(
        '/^[0-9]{5}(-[0-9]{4})?$/',
        trim($value)
    );
}

function valid_phone(string $value): bool
{
    return (bool) preg_match(
        '/^[0-9) (xX+.-]{7,20}$/',
        trim($value)
    );
}

function validate_shipping_data(array $data): array
{
    $errors = [];

    if (!valid_name((string) ($data['first_name'] ?? ''))) {
        $errors['first_name'] = 'Enter a valid first name.';
    }

    if (!valid_name((string) ($data['last_name'] ?? ''))) {
        $errors['last_name'] = 'Enter a valid last name.';
    }

    if (!valid_address((string) ($data['street'] ?? ''))) {
        $errors['street'] = 'Enter a valid street address.';
    }

    if (!valid_address((string) ($data['city'] ?? ''))) {
        $errors['city'] = 'Enter a valid city.';
    }

    if (trim((string) ($data['state'] ?? '')) === '') {
        $errors['state'] = 'Enter a state.';
    }

    if (!valid_zip((string) ($data['zip'] ?? ''))) {
        $errors['zip'] = 'Enter a valid ZIP code.';
    }

    if (!valid_email((string) ($data['email'] ?? ''))) {
        $errors['email'] = 'Enter a valid email address.';
    }

    if (!valid_phone((string) ($data['phone'] ?? ''))) {
        $errors['phone'] = 'Enter a valid phone number.';
    }

    return $errors;
}
