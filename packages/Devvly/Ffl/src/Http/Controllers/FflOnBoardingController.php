<?php

namespace Devvly\Ffl\Http\Controllers;

use Devvly\Ffl\Http\Requests\StoreFflForm;
use Devvly\Ffl\Models\Ffl;
use Devvly\Ffl\Models\FflInfo;
use Devvly\Ffl\Repositories\FflRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use stringEncode\Exception;

use Devvly\Core\Repositories\CountryStateRepository;
use Devvly\Core\Models\CountryState;
use Illuminate\Support\Facades\Mail;
use Devvly\Ffl\Mail\NewFflNotification;


class FflOnBoardingController extends Controller
{
    /**
     * @var array|\Illuminate\Contracts\Foundation\Application|Request|string
     */
    protected $_config;



    /**
     * @var FflRepository
     */
    private $fflRepository;

    /**
     * @var CountryStateRepository
     */
    private $countryStateRepository;

    /**
     * FflOnBoardingController constructor.
     * @param FflRepository $fflRepository
     * @param CountryStateRepository $countryStateRepository
     */
    public function __construct( FflRepository $fflRepository, CountryStateRepository $countryStateRepository)
    {
        $this->_config = request('_config');
        $this->fflRepository = $fflRepository;
        $this->countryStateRepository = $countryStateRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish()
    {
        return view($this->_config['view']);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form()
    {
        /** @var Collection $state */
        $states = CountryState::where('country_id', CountryState::USA_COUNTRY_ID)->get();
        return view($this->_config['view'], ['states' => $states]);
    }

    /**
     * @param StoreFflForm $request
     * @return JsonResponse
     */
    public function store(StoreFflForm $request)
    {

        $data=request()->all();
        try{
          /** saving license image */
          $licensePath='';
          if(isset($data['license_image']['file'])){
            if($data['license_image']['file']){
              $licensePath = FflInfo::STORAGE_FOLDER . microtime() . $request->input('license_image.name');
              Storage::disk('wassabi')->put(
                $licensePath,
                base64_decode($request->input('license_image.file'))
              );
            }
          }
          $checkIfFflExist=$this->fflRepository->findFflByLicense($request->input('license_number'));
          if($checkIfFflExist->getData()->status==200){
            if($checkIfFflExist->getData()->ffl){
              $ffl_id=$checkIfFflExist->getData()->ffl->id;
              $ffl=$this->fflRepository->update($this->mapRequestToAttributes($request, $licensePath),$ffl_id);
              //internal email is sent her which includes a time stamp, date, and contact information for follow up.
              $request->merge(['ffl_id' => $ffl->id]);
              return new JsonResponse([]);
            }
          }
            $ffl=$this->fflRepository->create($this->mapRequestToAttributes($request, $licensePath));
            //internal email is sent her which includes a time stamp, date, and contact information for follow up.
            $request->merge(['ffl_id' => $ffl->id]);
            return new JsonResponse([]);

        }catch (\Exception $e){
          return response()->json([
            'status'  => 210,
            'message' => 'Duplicate Entry for license number',
            'exception' => $e->getMessage()
          ], 200);
        }
    }


    /**
     * @param StoreFflForm $request
     * @param string $licensePath
     * @return array
     */
    private function mapRequestToAttributes(StoreFflForm $request, string $licensePath): array
    {

        $x= [
          'license_number'       => $request->input('license_number'),
          'license_region'       => $request->input('license_number_parts.first'),
          'license_district'     => $request->input('license_number_parts.second'),
          'license_fips'         => $request->input('license_number_parts.third'),
          'license_type'         => $request->input('license_number_parts.fourth'),
          'license_expire_date'  => $request->input('license_number_parts.fifth'),
          'license_sequence'     => $request->input('license_number_parts.sixth'),
          'license_name'         => $request->input('license_name'),
          'business_name'        => $request->input('company_name'),
          'street_address'       => $request->input('street_address'),
          'city'                 => $request->input('city'),
          'zip_code'             => $request->input('zip_code'),
          'email'                => $request->input('email'),
          'phone'                => $request->input('phone'),
          'latitude'             => $request->input('position.lat'),
          'longitude'            => $request->input('position.lng'),
          'preferred'            => 1,
          'website'              => $request->input('website_link')
        ];

        $state = \Devvly\Core\Models\CountryState::find($request->input('mailing_state'));
        $x['state']=$state->code;
        if($licensePath){
          $x['license_file']= $licensePath;
        }
        return $x;
    }
    public function checkFflExist($ffl_number){
      $x=explode('-',$ffl_number);
      if(sizeof($x)==3){
        return $this->fflRepository->findFflByPartialLicense($ffl_number);
      }
      return response()->json([
        'status'  => 400,
        'message' => 'The FFl license number you have entered is wrong, enter the following format X-XX-XXXXX',
      ], 400);
    }
}
