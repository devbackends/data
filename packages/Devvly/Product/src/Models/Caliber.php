<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\Caliber as Contract;


/**
 * Devvly\Product\Models\Caliber
 *
 * @property int $id
 * @property string $name
 * @property int $num
 * @property int $gauge
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Caliber extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'caliber';
  protected $fillable = ['name','num','gauge'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function attribute()
  {
    return $this->belongsTo(Attribute::class);
  }


  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function mapCalibers() {
    return $this->hasMany(MapCaliber::class);
  }


}
