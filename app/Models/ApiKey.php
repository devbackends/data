<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\Inventory as Contract;


/**
 * Devvly\Product\Models\ApiKey
 *
 * @property int $id
 * @property int $uid
 * @property string $name
 * @property string $type
 * @property int $api_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class ApiKey extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'api_keys';
  protected $fillable = ['uid','name','type','api_key'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
