<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\Subscription as Contract;


/**
 * Devvly\Product\Models\Subscription
 *
 * @property int $id
 * @property int $uid
 * @property string $token
 * @property string $package
 * @property string $end
 * @property int $calls_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Subscription extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'subscription';
  protected $fillable = ['uid','token','package','end','calls_number'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
