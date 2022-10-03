<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\ProductPriceChange as Contract;


/**
 * Devvly\Product\Models\ProductPriceChange
 *
 * @property int                             $id
 * @property int                             $product_id
 * @property string                          $upc
 * @property float|null                      $old_msrp
 * @property float|null                      $old_map
 * @property int|null                        $msrp
 * @property int                             $map
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class ProductPriceChange extends Model implements Contract {

  /**
   * @var string
   */
  protected $table    = 'product_price_changes';
  protected $primaryKey = 'id';
  protected $fillable = ['product_id','upc', 'old_msrp', 'old_map', 'msrp', 'map'];

  public function product(){
    return $this->belongsTo('\Devvly\Product\Models\Product');
  }


}
