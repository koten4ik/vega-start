<?php

namespace Modules\ZSupport\App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'rank',
        'display',
        'cyrillic_only',
    ];
}
