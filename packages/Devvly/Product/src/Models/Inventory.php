<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\Inventory as Contract;


/**
 * Devvly\Product\Models\Inventory
 *
 * @property int $id
 * @property int $pid
 * @property int $did
 * @property string $distributor
 * @property string|null $sku
 * @property int $stock
 * @property float|null $cost
 * @property string|null $condition
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Inventory extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'inventories';
  protected $fillable = ['pid','did','distributor','sku','stock','cost','condition'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function distributor()
  {
    return $this->belongsTo(Distributor::class);
  }
}
