<?php

namespace Devvly\Core\Repositories;

use Devvly\Core\Eloquent\Repository;
use Devvly\Core\Models\CountryState;

class CountryStateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
      return CountryState::class;
    }

}
