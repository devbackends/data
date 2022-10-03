<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FflTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
  public function testFetchFfls()
  {
    $response = $this->get('/test');
    $request = new \Illuminate\Http\Request();
    $request->merge([
      "zip_code"=>38401,
      "radius"=>100,
    ]);
    $result = app('Devvly\Ffl\Repositories\FflRepository')->findFfls($request);
    $result=json_decode($result->getContent(),true);
    if(isset($result['status'])){
      if($result['status']==200){
        $response->assertStatus(200);
      }else{
        $response->assertStatus(500);
      }
    }else{
      $response->assertStatus(500);
    }
  }
  public function testFindFflById(){
    $response = $this->get('/test');
    $id=app('Devvly\Ffl\Repositories\FflRepository')->all()->first()->id;
    $request = new \Illuminate\Http\Request();
    $request->merge([
      "zip_code"=>38401,
      "radius"=>100,
    ]);
    $result = app('Devvly\Ffl\Repositories\FflRepository')->findFflById($id);
    $result=json_decode($result->getContent(),true);
    if(isset($result['status'])){
      if($result['status']==200){
        $response->assertStatus(200);
      }else{
        $response->assertStatus(500);
      }
    }else{
      $response->assertStatus(500);
    }
  }

  public function testFindFflByLicense(){
    $response = $this->get('/test');
    $license=app('Devvly\Ffl\Repositories\FflRepository')->all()->first()->license_number;
    $request = new \Illuminate\Http\Request();
    $result = app('Devvly\Ffl\Repositories\FflRepository')->findFflByLicense($license);
    $result=json_decode($result->getContent(),true);
    if(isset($result['status'])){
      if($result['status']==200){
        $response->assertStatus(200);
      }else{
        $response->assertStatus(500);
      }
    }else{
      $response->assertStatus(500);
    }
  }

  public function testFindFflByPartialLicense(){
    $response = $this->get('/test');
    $license=app('Devvly\Ffl\Repositories\FflRepository')->all()->first()->license_number;
    $x=explode('-',$license);
    $license=$x[0].'-'.$x[1].'-'.$x['5'];
    $request = new \Illuminate\Http\Request();
    $result = app('Devvly\Ffl\Repositories\FflRepository')->findFflByPartialLicense($license);
    $result=json_decode($result->getContent(),true);
    if(isset($result['status'])){
      if($result['status']==200){
        $response->assertStatus(200);
      }else{
        $response->assertStatus(500);
      }
    }else{
      $response->assertStatus(500);
    }
  }

  public function testFflSubmitForm(){
    $response = $this->get('/test');
    $body=
      [
        'license_number'       => "0-00-000-00-00-00000",
        'license_region'       => "0",
        'license_district'     => "00",
        'license_fips'         => "000",
        'license_type'         => "00",
        'license_expire_date'  => "00",
        'license_sequence'     => "00000",
        'license_name'         => "license name",
        'business_name'        => "business name",
        'street_address'       => "22 Public Square STE 11",
        'city'                 => "Columbia",
        'state'                => 56,
        'zip_code'             => 38401,
        'email'                => "mkabalane@gmail.com",
        'phone'                => "9315482480",
        'latitude'             => "0.000000",
        'longitude'            => "0.000000",
        'preferred'            => 1,
        'website'              => "2agunshow.com"

      ];
    $ffl = app('Devvly\Ffl\Repositories\FflRepository')->create($body);
    if(isset($ffl->id)){
      $ffl->delete();
      $response->assertStatus(200);
    }else{
      $response->assertStatus(500);
    }
  }

}
