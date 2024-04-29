<?php

namespace App\Enums;

enum IdentifierType: string
{
    case Passport = 'passport';
    case DrivingLicense = 'driving_license';

    /**
     * @param IdentifierType $type
     * @return string
     */
    public static function title(IdentifierType $type): string
    {
        return match ($type) {
            self::Passport => 'Passport',
            self::DrivingLicense => 'Driving license',
        };
    }

    /**
     * @return string[]
     */
    public static function toArray(): array
    {
        return array_column(IdentifierType::cases(), 'value');
    }
}
