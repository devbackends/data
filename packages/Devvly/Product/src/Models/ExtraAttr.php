<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\ExtraAttr as Contract;


/**
 * Devvly\Product\Models\ExtraAttr
 *
 * @property int $id
 * @property int $pid
 * @property int $type
 * @property string $distributor
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class ExtraAttr extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'extra_attr';
  protected $fillable = ['pid','type','distributor','value'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
