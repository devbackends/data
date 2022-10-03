<?php

namespace Devvly\Ffl\Http\Controllers\Api;

use Devvly\Ffl\Http\Controllers\Controller;
use Devvly\Ffl\Repositories\FflRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FflController extends Controller
{
    const LIMIT = 100;

    private $fflRepository;

    public function __construct(FflRepository $fflRepository)
    {
        $this->fflRepository = $fflRepository;
    }
    public function findFfls(Request $request){
       $this->fflRepository->logFflCall();
        return  $this->fflRepository->findFfls($request);
    }
    public function findFflById($id){
      $this->fflRepository->logFflCall();
      return $this->fflRepository->findFflById($id);
    }

    public function saveFflInfo($id){
      $this->fflRepository->logFflCall();
      return $this->fflRepository->saveFflInfo($id);
    }

    public function findFflByLicense($license){
      $this->fflRepository->logFflCall();
      return $this->fflRepository->findFflByLicense($license);
    }
    public function findFflByPartialLicense($license){
      $this->fflRepository->logFflCall();
      return $this->fflRepository->findFflByPartialLicense($license);
    }
    public function getWassabiKeys(){
      $this->fflRepository->logFflCall();
      return $this->fflRepository->getWassabiKeys();
    }
}
