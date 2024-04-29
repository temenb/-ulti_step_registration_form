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
    static public function title(IdentifierType $type): string
    {
        return match ($type) {
            self::Passport => 'Passport',
            self::DrivingLicense => 'Driving license',
        };
    }

    public static function toArray(): array
    {
        return array_column(IdentifierType::cases(), 'value');
    }
}
