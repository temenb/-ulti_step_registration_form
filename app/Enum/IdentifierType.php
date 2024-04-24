<?php

namespace App\Enum;

class IdentifierType
{
    const PASSPORT = 'passport';
    const DRIVING_LICENSE = 'driving_license';

    const ENUM = [
        self::PASSPORT => 'Passport',
        self::DRIVING_LICENSE => 'Driving license',
    ];
}
