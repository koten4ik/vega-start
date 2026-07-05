<?php

namespace Modules\ShopOrder\Enums;

enum OrderStatus: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Новый',
            self::PAID => 'Оплачен',
            self::CANCELLED => 'Отменён',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
