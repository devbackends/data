<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\Image as Contract;


/**
 * Devvly\Product\Models\Image
 *
 * @property int $id
 * @property int $pid
 * @property int $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Image extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'images';
  protected $fillable = ['pid','path'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
