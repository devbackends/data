<?php

namespace Devvly\Ffl\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Ffl\Contracts\Ffl as Contract;


/**
 * Devvly\Ffl\Models\Ffl
 *
 * @property int $id
 * @property string|null $license_number
 * @property int|null $license_region
 * @property int|null $license_district
 * @property string|null $license_fips
 * @property string|null $license_type
 * @property char|null $license_expire_date
 * @property char|null $license_sequence
 * @property string|null $license_name
 * @property string|null $business_name
 * @property string $street_address
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property string $email
 * @property string $phone
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int $preferred
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Devvly\Ffl\Models\FflInfo|null $info
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl whereSource($value)
 */
class Ffl extends Model implements Contract
{


  const ON_BOARDING_FORM = 'on_boarding_form';

  const ON_BOARDING_ADMIN = 'merchant_seller';

    /**
     * @var string
     */
    protected $table = 'ffl';

    protected $fillable = ['license_number','license_region','license_district','license_fips','license_type','license_expire_date','license_sequence','license_name','business_name','street_address','city','state','zip_code','email','phone','latitude','longitude','preferred'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function info()
    {
        return $this->hasOne(FflInfo::class);
    }
}
