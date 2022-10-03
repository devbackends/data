<?php

namespace Devvly\Ffl\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Devvly\Ffl\Models\FflInfo
 *
 * @property int $id
 * @property timestamp|null $date
 * @property string|null $zip_code
 * @property string|null $radius
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class FflCache extends Model
{
  /**
   * @var string
   */
  protected $table = 'ffl_cache';

  /**
   * @var string[]
   */
  protected $fillable = ['data', 'zip_code', 'radius', 'data'];

}
