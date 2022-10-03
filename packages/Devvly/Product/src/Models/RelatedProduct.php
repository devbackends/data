<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\RelatedProduct as Contract;


/**
 * Devvly\Product\Models\RelatedProduct
 *
 * @property int $id
 * @property int $pid
 * @property int $reference
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class RelatedProduct extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'related_products';
  protected $fillable = ['pid','reference'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
