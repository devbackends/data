<?php

namespace Devvly\Product\Http\Controllers;

use Devvly\Product\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class ProductController extends Controller
{
  private $productRepository;

  public function __construct(ProductRepository $productRepository)
  {
    $this->productRepository = $productRepository;
    $this->_config = request('_config');
  }
  public function registerApi(){
      return view($this->_config['view']);
  }

  public function home(){
    return view($this->_config['view']);
  }

  public function getProductsPriceChange(){

    try {

      $productsPriceChange= \Devvly\Product\Models\ProductPriceChange::with(['product'])->orderBy('id', 'desc')->take(500)->get();
      return response()->json([
        'status'  => 200,
        'message' => 'success',
        'products' => $productsPriceChange,
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function testWebhook(){
    return   app("Devvly\Product\Repositories\ProductRepository")->sendInventoryWebhook(10781,59,'rsr');
  }

}
