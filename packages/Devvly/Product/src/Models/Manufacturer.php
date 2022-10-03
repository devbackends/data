<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\Manufacturer as Contract;


/**
 * Devvly\Product\Models\Manufacturer
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Manufacturer extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'manufacturer';
  protected $fillable = ['name'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function attribute()
  {
    return $this->belongsTo(Attribute::class);
  }

}
