<?php

namespace Modules\User\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Modules\Market\Models\MarketSupplier;
use Modules\Mlm\Models\PackMlmModel;
use Modules\Mlm\Models\QualMlmModel;
use Modules\MlmBinary\Models\BinaryMlmModel;
use Modules\MlmNetwork\Models\NetworkMlmModel;
use Modules\User\Enums\UserGender;
use Modules\ZSupport\App\Models\Country;
use Spatie\Permission\Traits\HasRoles;

class UserModel extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'users';
    const STATUS_NOACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANED = -1;

    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;


    protected $fillable = ['login',
        'email',
        'password',
        'drupal_pass',
        'profile_first_name',
        'profile_last_name',
        'profile_last_name',
        'profile_birthday',
        'profile_phone',
        'avatar',
        'profile_gender',
        'country_id',
        'email_verified_at',
        'phone_verified_at',
        'pack_end_date',
        'pack_id',
        'phone_verify_session_id',
        'sponsor_id',
    ];

    protected $attributes = [
        //'notif_email_moderate_actions' => 1,
    ];


    protected $hidden = ['password', 'remember_token'];

}
