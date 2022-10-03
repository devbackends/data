<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\MapCaliber as Contract;


/**
 * Devvly\Product\Models\MapCaliber
 *
 * @property int $id
 * @property string $cid
 * @property string $distributor
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class MapCaliber extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'map_caliber';
  protected $fillable = ['cid','distributor','value'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function caliber()
  {
    return $this->belongsTo(Caliber::class);
  }

}
