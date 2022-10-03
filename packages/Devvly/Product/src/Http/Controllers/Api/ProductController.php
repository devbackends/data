<?php

namespace Devvly\Product\Http\Controllers\Api;

use Devvly\Product\Http\Controllers\Controller;
use Devvly\Product\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller {


  private $productRepository;

  public function __construct(ProductRepository $productRepository) {
    $this->productRepository = $productRepository;
  }

  public function getAllProducts(Request $request) {
    return $this->productRepository->getAllProducts($request);
  }

  public function findProductById($id) {
    return $this->productRepository->findProductById($id);
  }

  public function findProductImages($product_id) {
    return $this->productRepository->findProductImages($product_id);
  }

  public function getAllCategories() {
    return $this->productRepository->getAllCategories();
  }

  public function getCategoryById($id) {
    return $this->productRepository->getCategoryById($id);
  }

  public function getAllManufacturers() {
    return $this->productRepository->getAllManufacturers();
  }

  public function getManufacturerById($id) {
    return $this->productRepository->getManufacturerById($id);
  }

  public function getStockOfProduct($product_id){
    return $this->productRepository->getStockOfProduct($product_id);
  }

  public function getStockBySkuOfProduct($sku){
    return $this->productRepository->getStockBySkuOfProduct($sku);
  }
  public function getDistributors(){
    return $this->productRepository->getDistributors();
  }

}
