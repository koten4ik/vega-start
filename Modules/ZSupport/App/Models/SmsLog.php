<?php

namespace Modules\ZSupport\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\UserModel;

class SmsLog extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'message',
        'message',
        'sent',
        'error',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }
}
