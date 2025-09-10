<?php

declare(strict_types=1);

namespace App\Validation;

class Validator
{
    public static function required(array $data, array $fields): array
    {
        $errors = [];
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                $errors[$field] = 'Required';
            }
        }
        return $errors;
    }
}
