<?php

namespace Devvly\Core\Models;


use Devvly\Core\Contracts\CountryState as CountryStateContract;
use Illuminate\Database\Eloquent\Model;

class CountryState extends Model implements CountryStateContract
{
    const USA_COUNTRY_ID = 244;

    public $timestamps = false;


    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['default_name'] = $this->default_name;

        return $array;
    }

}