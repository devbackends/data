<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\Card as Contract;


/**
 * Devvly\Product\Models\Card
 *
 * @property int $id
 * @property int $uid
 * @property string $token
 * @property string $exp
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Card extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'cards';
  protected $fillable = ['uid','token','exp'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
