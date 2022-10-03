<?php

namespace Devvly\Core\Models;

use Devvly\Core\Contracts\Country as CountryContract;
use Illuminate\Database\Eloquent\Model;

class Country extends Model implements CountryContract
{
    public $timestamps = false;


}