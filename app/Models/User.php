<?php

namespace App\Models;

use Devvly\Product\Models\ApiKey;
use Devvly\Product\Models\Card;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

  protected $table = 'users';
  protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'webhook_url'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function cards() {
    return $this->hasMany(Card::class);
  }
  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function subscriptions() {
    return $this->hasMany(Subscription::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function apiKeys() {
    return $this->hasMany(ApiKey::class);
  }

}
