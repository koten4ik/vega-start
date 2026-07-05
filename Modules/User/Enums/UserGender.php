<?php

namespace Modules\User\Enums;

enum UserGender: string
{
    case male = 'male';
    case female = 'female';

    public function label(): string
    {
        return match ($this) {
            self::male => 'Мужской',
            self::female => 'Женский',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
