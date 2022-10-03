<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\Attribute as Contract;


/**
 * Devvly\Product\Models\Attribute
 *
 * @property int $id
 * @property int $pid
 * @property string|null $description
 * @property string|null $specifications
 * @property string|null $teaser
 * @property int|null $caliber
 * @property int|null $manufacturer
 * @property string|null $color
 * @property string|null $man_part_num
 * @property string|null $model
 * @property float|null $lbs
 * @property float|null $oz
 * @property float|null $height
 * @property float|null $width
 * @property float|null $length
 * @property string|null $subcategory
 * @property int|null $capacity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Attribute extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'attributes';
  protected $fillable = ['pid','description','specifications','teaser','caliber','manufacturer','color','man_part_num','model','lbs','oz','height','width','length','subcategory','capacity'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function calibers() {
    return $this->hasMany(Caliber::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function manufacturers() {
    return $this->hasMany(Manufacturer::class);
  }


}
