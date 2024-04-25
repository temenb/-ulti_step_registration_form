<?php

namespace App\Enums;

enum IdentifierType: string
{
    case passport = 'Passport';
    case driving_license = 'Driving license';

    /**
     * @return string[]
     */
    static public function enum(): array
    {
        return [
            IdentifierType::passport->name => IdentifierType::passport->value,
            IdentifierType::driving_license->name => IdentifierType::driving_license->value,
        ];
    }
}
