<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case TerminalAdmin = 'terminal_admin';
    case Admin = 'admin';
    case Passenger = 'passenger';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::TerminalAdmin => 'Terminal / Adda Admin',
            self::Admin => 'Bus Stand Admin',
            self::Passenger => 'Passenger',
        };
    }

    public static function panelRoles(): array
    {
        return [self::SuperAdmin, self::TerminalAdmin, self::Admin];
    }
}
