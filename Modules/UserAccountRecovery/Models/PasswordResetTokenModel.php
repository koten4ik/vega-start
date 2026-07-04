<?php

namespace Modules\UserAccountRecovery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Modules\UserSubscribe\Models\UserSubscribeAuthorRelationModel;

class PasswordResetTokenModel extends Authenticatable
{
    use HasFactory;


    // Указываем таблицу, если имя не по стандарту (password_reset_tokens)
    protected $table = 'password_reset_tokens';

    // Указываем, что первичный ключ — email, и он не числовой
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    // Включаем только created_at (поля updated_at в этой таблице обычно нет)
    public $timestamps = false;
    protected $fillable = ['email', 'token', 'created_at'];
}
