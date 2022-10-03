<?php

namespace Devvly\Zanders\Services;

use Devvly\Zanders\Models\ZandersProducts;
use Devvly\Product\Repositories\ProductRepository;

use DB;

class Product
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * Product constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param $item_number
     * @return false|\Devvly\Product\Contracts\Product
     */
    public function createProduct($item_number)
    {
        $zanderProduct = $this->getZanderProduct($item_number);
        if ($zanderProduct) {
            $data=$this->getUpdateOptions($zanderProduct);
            // Create product
            $product = $this->create($data);
            return $product;
        }

        return false;
    }

    public function create($data){
      $product = $this->productRepository->store($data);
    }

    public function updateProducts($item_number)
    {
        $zanderProduct = $this->getZanderProduct($item_number);
        if ($zanderProduct) {
            $upc=!empty($zanderProduct['upc']) ? $zanderProduct['upc'] : $zanderProduct['itemnumber'] ;
            if($upc){
              $product = $this->productRepository->findWhere(['upc' => $upc])->first();
              if($product){
                $data=$this->getUpdateOptions($zanderProduct);
                // Update product
                $product = $this->update($product->id,$data);

                return $product;
              }
            }
        }
        return false;
    }



    /**
     * @param $productId
     * @param $options
     * @return \Devvly\Product\Contracts\Product
     */
    protected function update($productId, $options)
    {
        // Update product
        return $this->productRepository->updateProduct($options, $productId);
    }

    /**
     * @param $product
     * @param $remoteImageName
     * @return mixed
     */
    protected function uploadImage($product, $remoteImageName)
    {
        $imageService = app('Devvly\Zanders\Services\Image');

        return $imageService->execute($remoteImageName, $product->id);
    }

    /**
     * @param $zanderProduct
     * @return array
     */
    protected function getUpdateOptions($zanderProduct)
    {
      $zander_price= isset($zanderProduct['price1']) ? $zanderProduct['price1'] : 0;
      $productData = [
          'upc' => !empty($zanderProduct['upc']) ? (string)$zanderProduct['upc'] : (string)$zanderProduct['itemnumber'],
          'title'  =>  $this->validateProductName($zanderProduct),
          'map'    => $this->getRetailMap($zanderProduct) ,
          'msrp'    => !isset($zanderProduct['msrp']) ? isset($zanderProduct['mapprice']) ? $zanderProduct['mapprice'] : $zander_price  : (int) filter_var( $zanderProduct['msrp']) ,
          'category' => $this->validateCategory($zanderProduct['category']),
          'active' => 1
      ];
        $zanderInfo=$this->getZanderInfo();
        if($zanderInfo){
          $productData['inventory']=[
            'did' => $zanderInfo->id,
            'distributor'=> $zanderInfo->name,
            'sku' => $zanderProduct['itemnumber'],
            'stock' =>  isset($zanderProduct['qty1']) ? $zanderProduct['qty1'] : '',
            'cost' =>   !isset($zanderProduct['price1']) ? !isset($zanderProduct['mapprice']) ? isset($zanderProduct['msrp']) ? $zanderProduct['msrp'] : 0  : $zanderProduct['mapprice']  : $zanderProduct['price1'],
            'condition' => 'new',
          ];

          $productData['extra_attr']=isset($zanderProduct['extra_attrs']) ? $zanderProduct['extra_attrs']: [];

          $productData['inventory']['attributes']=[
            'description' => isset($zanderProduct['detailed_description'][1]) ? json_encode(addslashes($zanderProduct['detailed_description'][1])) : json_encode($this->validateProductName($zanderProduct)) ,
            'specifications' => json_encode($this->getSpecifications($zanderProduct)),
            'color' => isset($zanderProduct['extra_attrs']['COLOR']) ? $zanderProduct['extra_attrs']['COLOR'] : '',
            'lbs' => number_format((float)$zanderProduct['weight'] / 16, 2, '.', ''), //we divided here over 16 to convert from OZ to Pounds
            'oz' => number_format((float)$zanderProduct['weight'] , 2, '.', ''),
            'height' => isset($zanderProduct['extra_attrs']['Height']) ? (int) filter_var( $zanderProduct['extra_attrs']['Height'], FILTER_SANITIZE_NUMBER_INT)  : 0,
            'width' =>  isset($zanderProduct['extra_attrs']['Width']) ? (int) filter_var( $zanderProduct['extra_attrs']['Width'], FILTER_SANITIZE_NUMBER_INT) : 0 ,
            'length' => isset($zanderProduct['extra_attrs']['Length']) ? (int) filter_var( $zanderProduct['extra_attrs']['Length'], FILTER_SANITIZE_NUMBER_INT) : 0,
            'capacity' => isset($zanderProduct['extra_attrs']['capacity']) ?  (int) filter_var( $zanderProduct['extra_attrs']['capacity'], FILTER_SANITIZE_NUMBER_INT)  : 0
          ];
          if(isset($zanderProduct['images'])){
            $productData['images'] =$zanderProduct['images'];
          }
        }
        return $productData;

    }
    protected function getZanderInfo(){
      $zanderDistributor= \Devvly\Product\Models\Distributor::where('name','zanders')->first();
      return $zanderDistributor;
    }
    /**
     * @param $id
     * @return false|array
     */
    protected function getZanderProduct($id)
    {
        // Try to fetch product from db
        $result = ZandersProducts::where('item_number', $id)->get();
        // Parse product data
        if (isset($result[0])) {
            $product = $result[0];
            $productData = json_decode($product->data, 1);
            return $productData;
        }
        return false;
    }

   protected function getSpecifications($zanderProduct){
      if(isset($zanderProduct['extra_attrs']['OTHER FEATURES'])){
         return explode("\n", $zanderProduct['extra_attrs']['OTHER FEATURES']);
      }
      return [];
   }

    public function validateProductAttribute($attribute, $attribute_name)
    {
        if (!empty($attribute)) {
            return (new Validator('main'))->validateProductAttribute($attribute, $attribute_name);
        }
        return 0;
    }
    public function validateCategory($rsr_category){
      if (!empty($rsr_category)) {
        return (new Validator('main'))->validateCategory($rsr_category);
      }
      return '';
    }

    public function validateProductDescription($data)
    {
        $description='';
        if(isset($data['attributes'])){
            $attributes = (array)$data['attributes'];
            $description = (isset($attributes['description'])) ? (!empty($attributes['description'])) ? $attributes['description'] : $data['description'] : $data['description'];
            if (isset($attributes['accessories'])) {
                if (!empty($attributes['accessories'])) {
                    $description = $description . " ,<br> Accesories :" . $attributes['accessories'];
                }
            }
            if (isset($attributes['diameter'])) {
                if (!empty($attributes['diameter'])) {
                    $description = $description . " ,<br> Diameter :" . $attributes['diameter'];
                }
            }
            if (isset($attributes['dram'])) {
                if (!empty($attributes['dram'])) {
                    $description = $description . " ,<br> Dram :" . $attributes['dram'];
                }
            }
        }else{
            if(isset($data['description'])){
                $description=str_replace('"',' ',$data['description']);;
            }
        }
        return $description;
    }



    public function getCondition($zanderProduct)
    {
      if(isset($zanderProduct['attributes']['condition'])){
           if($zanderProduct['attributes']['condition']=='refurbished'){
             return 'refurbished';
           }else{
             return 'used';
           }
      }else{
        return 'new';
      }
    }

    public function validateProductName($zanderProduct)
    {
      $title = '';
      if (isset($zanderProduct['desc1'])) {
        $title = $zanderProduct['desc1'];
      }
      if (isset($zanderProduct['desc2'])) {
        if($title) {
          $title = $title . ', ' . $zanderProduct['desc2'];
        }else{
            $title = $zanderProduct['desc2'];
          }
        }
      return ucfirst(strtolower($title));
    }

    public function validateProductShortDescription($description){
        $description=str_replace('"',' ',$description);
        return ucfirst(strtolower($description));
    }

  public function getRetailMap($zanderProduct){
    if(isset($zanderProduct['mapprice'])){
      if((float)$zanderProduct['mapprice'] > 0){
        return (float)$zanderProduct['mapprice'];
      }
    }
    if(isset($zanderProduct['msrp'])){
      if( (int) filter_var( $zanderProduct['msrp']) > 0){
        return  (int) filter_var( $zanderProduct['msrp']);
      }
    }
    if(isset($zanderProduct['price1'])){
      if((float)$zanderProduct['price1'] > 0){
        return (float)$zanderProduct['price1'];
      }
    }
    return 0;
  }

}