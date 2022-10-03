<?php

namespace Devvly\Core\Repositories;

use Devvly\Core\Eloquent\Repository;
use Devvly\Core\Models\Country;

class CountryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
      return Country::class;
    }
}