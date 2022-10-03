<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\Product as Contract;


/**
 * Devvly\Product\Models\Product
 *
 * @property int                             $id
 * @property int                             $upc
 * @property string                          $title
 * @property float|null                      $msrp
 * @property float|null                      $map
 * @property int|null                        $category
 * @property int                             $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Product extends Model implements Contract {

  /**
   * @var string
   */
  protected $table    = 'products';
  protected $primaryKey = 'id';
  protected $fillable = ['upc', 'title', 'msrp', 'map', 'category', 'active'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function inventories() {
    return $this->hasMany(Inventory::class,'pid');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function category() {
    return $this->belongsTo(Category::class,'category')->with('parent');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function images() {
    return $this->hasMany(Image::class,'pid');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function relatedProducts() {
    return $this->hasMany(RelatedProduct::class,'pid');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function restrictions() {
    return $this->hasMany(Restriction::class,'pid');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function restrictionStates() {
    return $this->hasMany(RestrictionState::class,'pid');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function attributes() {
    return $this->hasMany(Attribute::class,'pid');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function extraAttrs() {
    return $this->hasMany(ExtraAttr::class,'pid');
  }


}
