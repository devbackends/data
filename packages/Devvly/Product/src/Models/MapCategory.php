<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\MapCategory as Contract;


/**
 * Devvly\Product\Models\MapCategory
 *
 * @property int $id
 * @property int $cid
 * @property string $did
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class MapCategory extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'map_categories';
  protected $fillable = ['cid','did','value'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function category()
  {
    return $this->belongsTo(Category::class);
  }
}
