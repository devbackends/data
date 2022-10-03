<?php

namespace Devvly\Product\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\Inventory as Contract;


/**
 * Devvly\Product\Models\DistAccount
 *
 * @property int $id
 * @property int $uid
 * @property int $did
 * @property string $distributor
 * @property booelan $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class DistAccount extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'dist_accounts';
  protected $fillable = ['uid','did','distributor','active'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function distributor()
  {
    return $this->belongsTo(Distributor::class);
  }
  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
