<?php

namespace Modules\SitePage\Enums;

enum PageDomain: string
{
    case NOWECS = 'nowecs.com';
    case MARKET = 'nowecs.market';

    public function label(): string
    {
        return match ($this) {
            self::NOWECS => 'nowecs.com',
            self::MARKET => 'nowecs.market',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
