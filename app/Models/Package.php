<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\Package as Contract;


/**
 * Devvly\Product\Models\Package
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property float $cpr
 * @property int $max_requests
 * @property float $exceeding_max_requests_fees
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Package extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'packages';
  protected $fillable = ['name','price','cpr','max_requests'];

}
