<?php

namespace Devvly\Product\Models;

use Devvly\Product\Contracts\MapCategory;
use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\Category as Contract;
use Illuminate\Support\Collection;

/**
 * Devvly\Product\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Category extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'categories';
  protected $primaryKey = 'id';
  protected $fillable = ['name','parent'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function products()
  {
    return $this->hasMany(Product::class);
  }
  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function map_categories()
  {
    return $this->hasMany(MapCategory::class);
  }
  /**
   * Get the child item.
   */
  public function child()
  {
    return $this->belongsTo(static::class, 'id', 'parent');
  }

  /**
   * Get the parent item record associated with the cart item.
   */
  public function parent()
  {
    return $this->belongsTo(self::class, 'parent');
  }

  /**
   * Get the children items.
   */
  public function children()
  {
    return $this->hasMany(self::class, 'parent');
  }

  public function getAllChildren ()
  {
    $sections = new Collection();

    foreach ($this->children as $section) {
      $sections->push($section);
      $sections = $sections->merge($section->getAllChildren());
    }

    return $sections;
  }

}
