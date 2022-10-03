<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\RestrictionState as Contract;


/**
 * Devvly\Product\Models\RestrictionState
 *
 * @property int $id
 * @property int $pid
 * @property string $state
 * @property string $municipality
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class RestrictionState extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'restriction_states';
  protected $fillable = ['pid','state','municipality','type'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

}
