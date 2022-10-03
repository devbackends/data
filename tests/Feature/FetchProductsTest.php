<?php

namespace Tests\Feature;

use App\Box;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class FetchProductsTest extends TestCase
{
  # Test function for Box class
  public function testFetchProducts()
  {

    $response = $this->get('/test');
    $request = new \Illuminate\Http\Request();
    $request->merge([
      'distributor' => ["rsr","zanders"],
      "category" => ["Firearms"]
      ]);
    $result = app('Devvly\Product\Repositories\ProductRepository')->getAllProducts($request);
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

  # Test function for Box class
  public function testGetProductById()
  {
    $id=app('Devvly\Product\Repositories\ProductRepository')->all()->first()->id;
    $response = $this->get('/test');
    $request = new \Illuminate\Http\Request();

    $result = app('Devvly\Product\Repositories\ProductRepository')->findProductById($id);
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

  public function testFindProductImages()
  {
    $product_id=app('Devvly\Product\Repositories\ProductRepository')->all()->first()->id;
    $response = $this->get('/test');
    $request = new \Illuminate\Http\Request();
    $result = app('Devvly\Product\Repositories\ProductRepository')->findProductImages($product_id);
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

  public function testGetAllCategories()
  {

    $response = $this->get('/test');
    $request = new \Illuminate\Http\Request();
    $result = app('Devvly\Product\Repositories\ProductRepository')->getAllCategories();
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

  public function testGetCategoryById()
  {
    $category_id= \Devvly\Product\Models\Category::all()->first()->id;
    $response = $this->get('/test');
    $request = new \Illuminate\Http\Request();
    $result = app('Devvly\Product\Repositories\ProductRepository')->getCategoryById($category_id);
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

  public function testGetAllManufacturers()
  {
    $response = $this->get('/test');
    $request = new \Illuminate\Http\Request();
    $result = app('Devvly\Product\Repositories\ProductRepository')->getAllManufacturers();
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



  public function testGetManufacturerById()
  {
    $manufacturer_id= \Devvly\Product\Models\Manufacturer::all()->first()->id;
    $response = $this->get('/test');
    $request = new \Illuminate\Http\Request();
    $result = app('Devvly\Product\Repositories\ProductRepository')->getCategoryById($manufacturer_id);
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

  public function testGetStockOfProduct()
  {
    $id=app('Devvly\Product\Repositories\ProductRepository')->all()->first()->id;
    $response = $this->get('/test');
    $request = new \Illuminate\Http\Request();

    $result = app('Devvly\Product\Repositories\ProductRepository')->getStockOfProduct($id);
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


  public function testGetStockBySkuOfProduct()
  {
    $product_upc=app('Devvly\Product\Repositories\ProductRepository')->all()->first()->upc;
    $response = $this->get('/test');
    $request = new \Illuminate\Http\Request();

    $result = app('Devvly\Product\Repositories\ProductRepository')->getStockBySkuOfProduct($product_upc);
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

  public function testGetDistributors()
  {
    $response = $this->get('/test');
    $request = new \Illuminate\Http\Request();
    $result = app('Devvly\Product\Repositories\ProductRepository')->getDistributors();
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
}
