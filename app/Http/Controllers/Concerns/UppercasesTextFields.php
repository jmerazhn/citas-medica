<?php

namespace App\Http\Controllers\Concerns;

trait UppercasesTextFields
{
    /**
     * Trim and convert to uppercase the given string fields in $data.
     */
    protected function uppercase(array $data, array $fields): array
    {
        foreach ($fields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = mb_strtoupper(trim($data[$field]));
            }
        }

        return $data;
    }
}
