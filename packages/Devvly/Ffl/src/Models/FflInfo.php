<?php

namespace Devvly\Ffl\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Devvly\Ffl\Models\FflInfo
 *
 * @property int $id
 * @property int $ffl_id
 * @property string|null $website
 * @property string|null $license_file

 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Devvly\Ffl\Models\Ffl $ffl
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereBusinessHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereFflId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereImporterExporter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereRetailStoreFront($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereStreetAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflInfo whereZipCode($value)
 * @mixin \Eloquent
 */
class FflInfo extends Model
{

  const STORAGE_FOLDER = 'licenses/';

    /**
     * @var string
     */
    protected $table = 'ffl_info';

    /**
     * @var string[]
     */
    protected $fillable = ['website', 'license_file'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ffl()
    {
        return $this->belongsTo(Ffl::class);
    }

}
