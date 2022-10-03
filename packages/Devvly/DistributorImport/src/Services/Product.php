<?php

namespace Devvly\DistributorImport\Services;

use Devvly\DistributorImport\Models\DistributorProducts;
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
     * @param $rsr_id
     * @return false|\Devvly\Product\Contracts\Product
     */
    public function createProduct($rsr_id)
    {
        $distributorProduct = $this->getDistributorProduct($rsr_id);

        if ($distributorProduct) {
            $data=$this->getUpdateOptions($distributorProduct,$rsr_id);
            // Create product
            $product = $this->create($data);
          // Upload product image
          if($distributorProduct['image']) {
            try{
              $this->uploadImage($product, $distributorProduct['image']);
            }catch(\Exception $e){
            }
          }
            return $product;
        }

        return false;
    }

    public function create($data){
      return $this->productRepository->store($data);
    }

    public function updateProducts($rsr_id)
    {
        $distributorProduct = $this->getDistributorProduct($rsr_id);
        if ($distributorProduct) {
            $upc=!empty($distributorProduct['upcCode']) ? $distributorProduct['upcCode'] : $distributorProduct['rsrStockId'] ;
            if($upc){
              $product = $this->productRepository->findWhere(['upc' => $upc])->first();
              if($product){
                $data=$this->getUpdateOptions($distributorProduct,$rsr_id);
                // Update product
                $product = $this->update($product->id,$data);

                // Upload product image
                if(sizeof($product->images)==0){
                  if($distributorProduct['image']){
                    try {
                      $this->uploadImage($product, $distributorProduct['image']);
                    }catch (\Exception $e){
                    }
                  }
                }
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
      $imageService = app('Devvly\DistributorImport\Services\Image');

      return $imageService->execute($remoteImageName, $product->id);
    }

    /**
     * @param $distributorProduct
     * @return array
     */
    protected function getUpdateOptions($distributorProduct,$rsr_id)
    {
      $rsr_price=isset($distributorProduct['rsrPrice']) ? $distributorProduct['rsrPrice'] : 0;
      $productData = [
        'upc' => !empty($distributorProduct['upcCode']) ? $distributorProduct['upcCode'] : $distributorProduct['rsrStockId'],
        'title'  =>  $this->validateProductName($distributorProduct['description']),
        'map'    => $this->getRetailMap($distributorProduct) ,
        'msrp' =>   !isset($distributorProduct['retailPrice']) ? isset($distributorProduct['retailMap']) ? (float)$distributorProduct['retailMap'] : (float)$rsr_price  : (float)$distributorProduct['retailPrice'] ,
        'category' => $this->validateCategory($distributorProduct['departmentId']),
        'active' => 1
      ];
        $rsrInfo=$this->getRsrInfo();

        if($rsrInfo){
          $productData['inventory']=[
            'did' => $rsrInfo->id,
            'distributor'=> $rsrInfo->name,
            'sku' => !empty($distributorProduct['upcCode']) ? $distributorProduct['upcCode'] : $distributorProduct['rsrStockId'],
            'stock' =>  isset($distributorProduct['quantity']) ? $distributorProduct['quantity'] : 0,
            'cost' =>   !isset($distributorProduct['rsrPrice']) ? !isset($distributorProduct['retailMap']) ? isset($distributorProduct['retailPrice']) ? $distributorProduct['retailPrice'] : ''  : $distributorProduct['retailMap']  : $distributorProduct['rsrPrice'],
            'condition' => $this->getCondition(isset($distributorProduct)),
          ];

          $productData['extra_attr']=[
            'accessories' => isset($distributorProduct['attributes']['accessories']) ? $distributorProduct['attributes']['accessories']: '' ,
            'action' => isset($distributorProduct['attributes']['action']) ? $distributorProduct['attributes']['action'] : '',
            'type_of_barrel' => isset($distributorProduct['attributes']['type_of_barrel']) ? $distributorProduct['attributes']['type_of_barrel'] : '', //map rsr type_of_barrel to 2agun type_of_barrel
            'chamber' => isset($distributorProduct['attributes']['chamber']) ? $distributorProduct['attributes']['chamber'] : '', //map rsr chamber to 2agun chamber
            'chokes' => isset($distributorProduct['attributes']['chokes']) ? $distributorProduct['attributes']['chokes'] : '',
            'condition' => $this->getCondition(isset($distributorProduct)), //(isset($distributorProduct['attributes']['condition'])) ? $this->validateProductAttribute($distributorProduct['attributes']['condition'], 'condition') : '',
            'capacity' => isset($distributorProduct['attributes']['capacity']) ? $distributorProduct['attributes']['capacity']: '',
            'dram' => isset($distributorProduct['attributes']['dram']) ? $distributorProduct['attributes']['dram'] : '',
            'edge' => isset($distributorProduct['attributes']['edge']) ? $distributorProduct['attributes']['edge'] : '', //map rsr edge to 2agun edge
            'firing_casing' => isset($distributorProduct['attributes']['firing_casing']) ? $distributorProduct['attributes']['firing_casing'] : '',
            'finish' => isset($distributorProduct['attributes']['finish']) ? $distributorProduct['attributes']['finish'] : '', //map rsr finish to 2agun finish
            'fit' =>  isset($distributorProduct['attributes']['fit']) ? $distributorProduct['attributes']['caliber'] : ((isset($distributorProduct['attributes']['fit1'])) ? $distributorProduct['attributes']['fit1'] : ''),
            'feet_per_second' => isset($distributorProduct['attributes']['feet_per_second']) ? $distributorProduct['attributes']['feet_per_second'] : '',
            'frame' => isset($distributorProduct['attributes']['frame']) ? $distributorProduct['attributes']['frame'] : '',
            'grain_weight' => isset($distributorProduct['attributes']['grain_weight']) ? $distributorProduct['attributes']['grain_weight'] : '',
            'grips' => isset($distributorProduct['attributes']['grips']) ? $distributorProduct['attributes']['grips'] : '',
            'hand' => isset($distributorProduct['attributes']['hand']) ? $distributorProduct['attributes']['hand'] : '', //map rsr finish to 2agun finish
            'moa' => isset($distributorProduct['attributes']['moa']) ? $distributorProduct['attributes']['moa'] : '',
            'new_stock_number' => isset($distributorProduct['attributes']['new_stock_number']) ? $distributorProduct['attributes']['new_stock_number'] : '',
            'objective' => isset($distributorProduct['attributes']['objective']) ? $distributorProduct['attributes']['objective'] : '',
            'ounce_of_shot' => isset($distributorProduct['attributes']['ounce_of_shot']) ? $distributorProduct['attributes']['ounce_of_shot'] : '',
            'power' => isset($distributorProduct['attributes']['power']) ? $distributorProduct['attributes']['power'] : '',
            'reticle' => isset($distributorProduct['attributes']['reticle']) ? $distributorProduct['attributes']['reticle'] : '',
            'safety' => isset($distributorProduct['attributes']['safety']) ? $distributorProduct['attributes']['safety'] : '',
            'Sights' => isset($distributorProduct['attributes']['sights']) ? $distributorProduct['attributes']['sights'] : '',
            'size' => isset($distributorProduct['attributes']['size']) ? $distributorProduct['attributes']['size'] : '',
            'type' => isset($distributorProduct['attributes']['type']) ? $distributorProduct['attributes']['type'] : '',
            'units_per_box' => isset($distributorProduct['attributes']['units_per_box']) ? $distributorProduct['attributes']['units_per_box'] : '',
            'units_per_case' => isset($distributorProduct['attributes']['units_per_case']) ? $distributorProduct['attributes']['units_per_case'] : '',
            'wt_characteristics' => isset($distributorProduct['attributes']['wt_characteristics']) ? $distributorProduct['attributes']['wt_characteristics'] : '',
            'diameter' => isset($distributorProduct['attributes']['diameter']) ? $distributorProduct['attributes']['diameter'] : '',
            'color' => isset($distributorProduct['attributes']['color']) ? $distributorProduct['attributes']['color'] : '',
            'material' => isset($distributorProduct['attributes']['material']) ? $distributorProduct['attributes']['material'] : '',
            'stock' => isset($distributorProduct['attributes']['stock']) ? $distributorProduct['attributes']['stock'] : '',
            'lens_color' => isset($distributorProduct['attributes']['lens_color']) ? $distributorProduct['attributes']['lens_color'] : '',
            'handle_color' => isset($distributorProduct['attributes']['handle_color']) ? $distributorProduct['attributes']['handle_color'] : '',
            'caliber' => isset($distributorProduct['attributes']['caliber1']) ? $this->validateProductAttribute($distributorProduct['attributes']['caliber1'], 'caliber') : '',
          ];
           $productData['restrictions']=[
             'ground' => isset($distributorProduct['groundShipmentsOnly']) ?  $distributorProduct['groundShipmentsOnly']=='Y'? 1 : 0 : 0,
             'sig' =>  isset($distributorProduct['adultSignatureRequired']) ? $distributorProduct['adultSignatureRequired']=='Y'? 1 : 0 : 0,
           ];

          $productData['inventory']['attributes']=[
            'description' => json_encode(addslashes($this->validateProductShortDescription($distributorProduct['shortDescription']))) ,
            'specifications' => json_encode(''),
            'teaser' =>  addslashes($this->validateProductDescription($distributorProduct)),
            'caliber' => isset($distributorProduct['attributes']['caliber']) ? $this->validateProductAttribute($distributorProduct['attributes']['caliber'], 'caliber') : ((isset($distributorProduct['attributes']['caliber1'])) ? $this->validateProductAttribute($distributorProduct['attributes']['caliber1'], 'caliber') : 0),
            'manufacturer' => isset($distributorProduct['attributes']['manufacturer']) ? $this->validateProductAttribute($distributorProduct['attributes']['manufacturer'],'manufacturer') : ((isset($distributorProduct['manufacturerName'])) ?  $this->validateProductAttribute($distributorProduct['manufacturerName'],'manufacturer') : ''),
            'color' => isset($distributorProduct['attributes']['color']) ? $distributorProduct['attributes']['color'] : '',
            'man_part_num' => isset($distributorProduct['attributes']['manufacturer_part_number']) ? $distributorProduct['attributes']['manufacturer_part_number'] : ((isset($distributorProduct['manufacturerPartNumber'])) ? $distributorProduct['manufacturerPartNumber'] : ''),
            'model' => isset($distributorProduct['attributes']['model']) ? $distributorProduct['attributes']['model'] : ((isset($distributorProduct['model'])) ? $distributorProduct['model'] : ''),
            'lbs' => number_format((float)$distributorProduct['weight'] / 16, 2, '.', ''), //we divided here over 16 to convert from OZ to Pounds
            'oz' => number_format((float)$distributorProduct['weight'] , 2, '.', ''),
            'barrel_length' => isset($distributorProduct['attributes']['barrel_length']) ? $distributorProduct['attributes']['barrel_length'] : '',
            'height' => isset($distributorProduct['height']) ? $distributorProduct['height'] : 0,
            'width' =>  isset($distributorProduct['width']) ? $distributorProduct['width'] : 0 ,
            'length' => isset($distributorProduct['length']) ? $distributorProduct['length'] : 0,
            'subcategory' => isset($distributorProduct['attributes']['subcategory']) ? $distributorProduct['attributes']['subcategory'] : '',
            'capacity' => isset($distributorProduct['attributes']['capacity']) ?  (int) filter_var( $distributorProduct['attributes']['capacity'], FILTER_SANITIZE_NUMBER_INT)  : 0
          ];
        }else{
          return '';
        }
        return $productData;

    }

    protected function getRsrInfo(){
      $rsrDistributor= \Devvly\Product\Models\Distributor::where('name','rsr')->first();
      return $rsrDistributor;
    }
    /**
     * @param $id
     * @return false|array
     */
    protected function getDistributorProduct($id)
    {
        // Try to fetch product from db
        $result = DistributorProducts::where('rsr_id', $id)->get();

        // Parse product data
        if (isset($result[0])) {
            $product = $result[0];
            $productData = json_decode($product->data, 1);
            $productData['quantity'] = $product->quantity;
            return $productData;
        }

        return false;
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



    public function getCondition($distributorProduct)
    {
      if(isset($distributorProduct['attributes']['condition'])){
           if($distributorProduct['attributes']['condition']=='refurbished'){
             return 'refurbished';
           }else{
             return 'used';
           }
      }else{
        return 'new';
      }
    }

    public function validateProductName($description)
    {
        $description=str_replace('"',' ',$description);
        $description =explode(',',$description)[0];
        if (strpos($description, ', Fits') !== false) {
            return explode(', Fits', $description)[0];
        }
        if (strpos($description, '. Fits') !== false) {
            return explode('. Fits', $description)[0];
        }
        if (strpos($description, '/') !== false) {
            return explode('/', $description)[0];
        }
        return $description;

    }

    public function validateProductShortDescription($description){
        $description=str_replace('"',' ',$description);
        return $description;
    }

  public function deleteRsrProduct($upc)
  {
    $product = $this->productRepository->findWhere(['upc' => $upc])->first();
    if (isset($product->id)) {
      $this->productRepository->delete($product->id);
      return array('status'=>'success','action'=>'delete');
    }
    return array('status'=>'error');
  }

  public function getPrice($distributorProduct){
    $price= (float)$distributorProduct['rsrPrice'] + 0.15 * (float)$distributorProduct['rsrPrice'];
    $add_shipping_fee=0;
    if($distributorProduct['departmentId'] == '01' || $distributorProduct['departmentId'] == '02' || $distributorProduct['departmentId'] == '03' || $distributorProduct['departmentId'] == '05' || $distributorProduct['departmentId'] == '07'){
      if((float)$distributorProduct['rsrPrice'] < 500){
        $add_shipping_fee=20;
      }
      $price= $add_shipping_fee + (float)$distributorProduct['rsrPrice'] + 0.1 * (float)$distributorProduct['rsrPrice'];
    }
    if(isset($distributorProduct['retailMap'])){
      if((float)$distributorProduct['retailMap'] > 0){
        $price=$distributorProduct['retailMap'];
        if($price < 500){
          $add_shipping_fee=20;
          $price=$price + $add_shipping_fee;
        }
      }
    }
    return $price;
  }

  public function getRetailMap($distributorProduct){
      if(isset($distributorProduct['retailMap'])){
        if((float)$distributorProduct['retailMap'] > 0){
          return (float)$distributorProduct['retailMap'];
        }
      }
    if(isset($distributorProduct['retailPrice'])){
      if((float)$distributorProduct['retailPrice'] > 0){
        return (float)$distributorProduct['retailPrice'];
      }
    }
    if(isset($distributorProduct['rsrPrice'])){
      if((float)$distributorProduct['rsrPrice'] > 0){
        return (float)$distributorProduct['rsrPrice'];
      }
    }
    return 0;
  }
}