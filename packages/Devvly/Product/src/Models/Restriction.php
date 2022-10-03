<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\Restriction as Contract;


/**
 * Devvly\Product\Models\Restriction
 *
 * @property int $id
 * @property int $pid
 * @property int $ground
 * @property int $sig
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Restriction extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'restrictions';
  protected $fillable = ['pid','ground','sig'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

}
