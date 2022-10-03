<?php

namespace Devvly\Ffl\Http\Controllers;

use Devvly\Ffl\Repositories\FflRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class FflController extends Controller
{
  private $fflRepository;

  public function __construct(FflRepository $fflRepository)
  {
    $this->fflRepository = $fflRepository;
    $this->_config = request('_config');
  }

}
