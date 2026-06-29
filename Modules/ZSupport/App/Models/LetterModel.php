<?php

namespace Modules\ZSupport\App\Models;

class LetterModel extends VegaModel
{
    protected $table = 'letters';
    protected $fillable = [
        'sender',
        'sender_name',
        'receiver',
        'subject',
        'text',
        'date',
        'date_sent',
    ];
}
