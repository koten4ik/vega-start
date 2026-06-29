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

class UserModel extends Authenticatable implements FilamentUser
{
    use Notifiable, HasRoles;

    protected $table = 'users';
    protected $guard_name = 'web';
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'profile_birthday' => 'date',
            'profile_gender' => UserGender::class,
        ];
    }

    protected static function booted()
    {
        static::created(function ($item) {
            $item->assignRole('user');
        });
        static::deleting(function ($item) {
            if ($item->avatar) {
                Storage::delete($item->avatar);
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can('admin');
    }

    public function getNameAttribute(): string
    {
        return (string)(
            $this->login
            ?? $this->profile_first_name
            ?? $this->email
            ?? ''
        );
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function pack(): BelongsTo
    {
        return $this->belongsTo(PackMlmModel::class, 'pack_id', 'pack_id');
    }

    public function qual(): BelongsTo
    {
        return $this->belongsTo(QualMlmModel::class, 'qual_id', 'qual_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(MarketSupplier::class, 'supplier_id');
    }

    public function network(): HasOne
    {
        return $this->hasOne(NetworkMlmModel::class, 'uid', 'id');
    }

    public function binary(): HasOne
    {
        return $this->hasOne(BinaryMlmModel::class, 'uid', 'id');
    }

    public function getNameForDisplayAttribute(): string
    {
        if ($this->profile_first_name != '') {
            return $this->profile_first_name;
        }
        if ($this->login != '') {
            return $this->login;
        }
        return '';
    }

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'sponsor_id');
    }
    /*public function sponsor()
    {
        return $this->hasOneThrough(
            __CLASS__,
            NetworkMlmModel::class,
            'uid', // foreign key в network_mlm_models
            'id', // foreign key в users
            'id', // local key users
            'uid_sponsor' // local key network_mlm_models
        );
    }*/
}
