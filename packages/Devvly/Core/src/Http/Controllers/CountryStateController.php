<?php

namespace Devvly\Core\Http\Controllers;

use Devvly\Core\Repositories\CountryRepository;
use Devvly\Core\Repositories\CountryStateRepository;

class CountryStateController extends Controller
{

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CountryRepository object
     *
     * @var \Devvly\Core\Repositories\CountryRepository
     */
    protected $countryRepository;

    /**
     * CountryStateRepository object
     *
     * @var Devvly\Core\Repositories\CountryStateRepository
     */
    protected $countryStateRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Devvly\Core\Repositories\CountryRepository       $countryRepository
     * @param  \Devvly\Core\Repositories\CountryStateRepository  $countryStateRepository
     * @return void
     */
    public function __construct(
        CountryRepository $countryRepository,
        CountryStateRepository $countryStateRepository
    )
    {
        $this->countryRepository = $countryRepository;

        $this->countryStateRepository = $countryStateRepository;

        $this->_config = request('_config');
    }

}