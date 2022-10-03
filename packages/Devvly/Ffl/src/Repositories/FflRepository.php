<?php

namespace Devvly\Ffl\Repositories;


use Devvly\Ffl\Models\Ffl;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Devvly\Core\Eloquent\Repository;


class FflRepository extends Repository {

  /**
   * Specify Model class name
   *
   * @return mixed
   */
  public function model() {
    return Ffl::class;
  }

  public function findFfls($request) {
    $data = $request->all();
    try {
      if (isset($data['zip_code'])) {
        $zip_code = $data['zip_code'];
        [$lat, $lng] = $this->findCoordinatesForLocation(
          $zip_code
        );
      }
      if (isset($data['radius'])) {
      }
      /** @var Ffl $first */
      $first = $this->makeModel()->newQuery();
      if (isset($data['zip_code'])) {
        $first = $first->where('zip_code', 'like', $zip_code . '%');
      }
      $first = $first->first();
      if (!$first) {
        return [];
      }
      $qb = $this
        ->makeModel()
        ->newQuery()
        ->selectRaw(
          '*, sqrt(POW(69.1 * (ffl.latitude - ' . $lat . '), 2) + pow(69.1 * ('
          . $lng
          . ' - ffl.longitude) * cos(ffl.latitude / 57.3), 2)) as `distance`,sqrt(POW(69.1 * (ffl.latitude - '
          . $first['latitude'] . '), 2) + pow(69.1 * (' . $first['longitude']
          . ' - ffl.longitude) * cos(ffl.latitude / 57.3), 2)) as `radius`'
        )
        ->where('ffl.zip_code', 'like', $zip_code . '%');
      $qb = $qb
        ->orderBy('ffl.license_type', 'Asc')
        ->orderBy('distance', 'Asc');
      if (isset($data['radius'])) {
        $qb = $qb->having('radius', '<', $data['radius']);
      }
      $qb = $qb->get();
      return response()->json([
        'status'  => 200,
        'message' => 'success',
        'ffls'    => $qb,
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  /**
   * @param  string  $location
   *
   * @return array
   */
  public static function findCoordinatesForLocation(string $location)
  : array {
    $httpClient = new Client();
    $options    = [
      'address' => $location,
      'key'     => 'AIzaSyDnUKjS6BAjGhL8XL8SacAYmDQMpWEchXs',
    ];

    $response = $httpClient->get(
      'https://maps.googleapis.com/maps/api/geocode/json?'
      . http_build_query($options)
    );
    $result   = json_decode($response->getBody(), 1);
    if ($result['status'] === 'ZERO_RESULTS') {
      return [NULL, NULL];
    }

    if (sizeof($result['results']) > 0) {
      return [
        $result['results'][0]['geometry']['location']['lat'],
        $result['results'][0]['geometry']['location']['lng']
      ];
    }

    return [NULL, NULL];
  }

  public function findFflById($id) {
    try {
      $first = $this->makeModel()
        ->newQuery()
        ->select('*', 'ffl.id as id')
        ->where('ffl.id', $id);
      $first = $first->first();
      if($first){
        $ffl_info = $first->info()->where('ffl_id', $id)->first();
        if ($ffl_info) {
          $first->license_file=$ffl_info->license_file;
        }
      }
      return response()->json([
        'status'  => 200,
        'message' => 'success',
        'ffl'     => $first,
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function saveFflInfo($id) {
    /** @var \Devvly\Ffl\Models\Ffl $model */
    try {
      $data = request()->all();
      $ffl = $this->find($id);
      if ($ffl) {
        if (isset($data['phone'])) {
          $ffl->phone = $data['phone'];
          $ffl->save();
        }
        if (isset($data['email'])) {
          $ffl->email = $data['email'];
          $ffl->save();
        }
        if (isset($data['license_file'])) {
          $ffl_info = $ffl->info()->where('ffl_id', $id)->first();
          if ($ffl_info) {
            $ffl_info->license_file = $data['license_file'];
            $ffl_info->save();
          } else {
            $ffl_info=$ffl->info()->create(['license_file' => $data['license_file']]);
          }
          $ffl->license_file=$ffl_info->license_file;

        }
        return response()->json(['status' => 200,
          'message' => 'success',
          'ffl' => $ffl,], 200);
      } else {
        return response()->json(['status' => 400,
          'message' => "ffl id not found",], 400);
      }
    } catch (Exception $exception) {
      return response()->json(['status' => 400,
        'message' => $exception->getMessage(),], 400);
    }
  }

  public function findFflByLicense($license) {
    try {
      $first = $this->makeModel()
        ->newQuery()
        ->select('*', 'ffl.id as id','ffl_info.license_file')
        ->leftJoin('ffl_info','ffl.id','=','ffl_info.ffl_id')
        ->where('ffl.license_number', $license);
      $first = $first->first();
      return response()->json([
        'status'  => 200,
        'message' => 'success',
        'ffl'     => $first,
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function findFflByPartialLicense($license) {
    try {
      $strArr = explode('-', $license);
      if (sizeof($strArr) == 3) {
        $first = $this->makeModel()
          ->newQuery()
          ->select('*', 'ffl.id as id')
          ->selectRaw('(select id from country_states where country_states.code=ffl.state and country_code="US") as state_id')
          ->leftJoin('ffl_info','ffl.id','=', 'ffl_info.ffl_id')
          ->where(
            'ffl.license_number',
            'like',
            $strArr[0] . '-' . $strArr[1] . '%' . $strArr[2]
          );
        $first = $first->first();
        return response()->json([
          'status'  => 200,
          'message' => 'success',
          'ffl'     => $first,
        ], 200);
      }
      return response()->json([
        'status'  => 400,
        'message' => 'The FFl license number you have entered is wrong, enter the following format X-XX-XXXXX',
      ], 400);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }
  public function logFflCall(){
    $data=request()->all();
    if(isset($data['ffl_api_key'])){
      $api_key=\App\Models\ApiKey::Where('api_key',$data['ffl_api_key'])->get()->first();
      if($api_key){
        $subscription= \App\Models\Subscription::where('uid',$api_key->uid)->where('package','like','Ffl%')->first();
        if($subscription){
          $subscription->calls_number=$subscription->calls_number + 1;
          $subscription->save();
        }
      }
    }
  }

  /**
   * @param array $attributes
   * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed|void
   * @throws \Prettus\Repository\Exceptions\RepositoryException
   */
  public function create(array $attributes)
  {
    /** @var \Devvly\Ffl\Models\Ffl $model */

    $model = $this->makeModel();
    return DB::transaction(function () use ($model, $attributes) {
      $fillable=$model->getFillable();
      foreach ($attributes as $key => $attribute) {
        if (in_array($key, $fillable)) {
          $model[$key] = $attributes[$key];
        }
      }
      $model->save();
      $attributes['ffl_id']=$model->id;
      $model->info()->create($attributes);
      return $model;
    });
  }

  public function getWassabiKeys(){

    $wassabi_access_key=getenv('WAS_ACCESS_KEY_ID');
    $wassabi_secret_key=getenv('WAS_SECRET_ACCESS');
    if($wassabi_secret_key && $wassabi_access_key){
      return response()->json([
        'status'  => 200,
        'wassabi_access_key' => $wassabi_access_key,
        'wassabi_secret_key' => $wassabi_secret_key
      ], 400);
    }else{
      return response()->json([
        'status'  => 400,
        'message' => 'The FFl license number you have entered is wrong, enter the following format X-XX-XXXXX',
      ], 400);
    }
  }
}
