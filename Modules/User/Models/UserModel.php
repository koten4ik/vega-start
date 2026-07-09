<?php

namespace Modules\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    const STATUS_NOACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANED = -1;

    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;


    protected $fillable = ['login',
        'email',
        'password',
        'avatar',
        'email_verified_at',
    ];

    protected $attributes = [
    ];


    protected $hidden = ['password', 'remember_token'];

}
