<?php

namespace Devvly\Product\Repositories;


use Devvly\Product\Models\Category;
use Devvly\Product\Models\Distributor;
use Devvly\Product\Models\Image;
use Devvly\Product\Models\Product;
use Devvly\Product\Models\Manufacturer;
use Devvly\Product\Models\Inventory;
use Devvly\Product\Models\ExtraAttr;
use Devvly\Product\Models\Restriction;
use Devvly\Product\Models\Attribute;

use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Devvly\Product\Repositories\Repository;


class ProductRepository extends Repository {

  const limit = 100;

  /**
   * Specify Model class name
   *
   * @return mixed
   */
  public function model() {
    return Product::class;
  }


  public function store(array $data)
  {
    $product = $this->create($data);
    if(isset($data['inventory'])){
      $inventory=new Inventory();
      $inventory->pid=$product->id;
      $inventory->did=$data['inventory']['did'];
      $inventory->distributor=$data['inventory']['distributor'];
      $inventory->sku=$data['inventory']['sku'];
      $inventory->stock=$data['inventory']['stock'];
      $inventory->cost=$data['inventory']['cost'];
      $inventory->condition=$data['inventory']['condition'];
      $inventory->save();

      if(isset($data['inventory']['attributes'])){
        $attribute=new Attribute();
        $attribute->pid=$product->id;
        $attribute->description = $data['inventory']['attributes']['description'];
        $attribute->specifications = $data['inventory']['attributes']['specifications'];
        if(isset($data['inventory']['attributes']['teaser'])) {
          $attribute->teaser = $data['inventory']['attributes']['teaser'];
        }
        if(isset($data['inventory']['attributes']['caliber'])) {
          $attribute->caliber=$data['inventory']['attributes']['caliber'];
        }
        if(isset($data['inventory']['attributes']['manufacturer'])) {
          $attribute->manufacturer=$data['inventory']['attributes']['manufacturer'];
        }
        if(isset($data['inventory']['attributes']['color'])) {
          $attribute->color=$data['inventory']['attributes']['color'];
        }
        if(isset($data['inventory']['attributes']['man_part_num'])) {
          $attribute->man_part_num=$data['inventory']['attributes']['man_part_num'];
        }
        if(isset($data['inventory']['attributes']['model'])) {
          $attribute->model=$data['inventory']['attributes']['model'];
        }
        if(isset($data['inventory']['attributes']['lbs'])) {
          $attribute->lbs=$data['inventory']['attributes']['lbs'];
        }
        if(isset($data['inventory']['attributes']['oz'])) {
          $attribute->oz=$data['inventory']['attributes']['oz'];
        }
        if(isset($data['inventory']['attributes']['height'])) {
          $attribute->height=$data['inventory']['attributes']['height'];
        }
        if(isset($data['inventory']['attributes']['width'])) {
          $attribute->width=$data['inventory']['attributes']['width'];
        }
        if(isset($data['inventory']['attributes']['length'])) {
          $attribute->length=$data['inventory']['attributes']['length'];
        }
        if(isset($data['inventory']['attributes']['length'])) {
          $attribute->subcategory=$data['inventory']['attributes']['length'];
        }
        if(isset($data['inventory']['attributes']['subcategory'])) {
          $attribute->subcategory=$data['inventory']['attributes']['subcategory'];
        }
        if(isset($data['inventory']['attributes']['capacity'])) {
          $attribute->capacity=$data['inventory']['attributes']['capacity'];
        }
        $attribute->save();
      }

      if(isset($data['extra_attr'])){
        foreach ($data['extra_attr'] as $key => $attribute){
          if($attribute){
            $ExtraAttr= new ExtraAttr();
            $ExtraAttr->pid=$product->id;
            $ExtraAttr->type=$key;
            $ExtraAttr->value=$attribute;
            $ExtraAttr->distributor=$data['inventory']['did'];
            $ExtraAttr->save();
          }
        }
      }
      if(isset($data['restrictions'])){
        $Restriction=new Restriction();
        $Restriction->pid=$product->id;
        $Restriction->ground=$data['restrictions']['ground'];
        $Restriction->sig=$data['restrictions']['sig'];
        $Restriction->save();
      }
    }
    if(isset($data['images'])){
      if(is_array($data['images'])){
        foreach ($data['images'] as $productImage){
          $image=new Image();
          $image->path=$productImage;
          $image->pid=$product->id;
          $image->save();
        }
      }
    }
    return $product;
  }



  public function updateProduct(array $data)
  {
    $product = $this->findWhere(['upc'=>$data['upc']])->first();
    $data['active']=1;
    if(!isset($data['msrp'])){
      $data['msrp']=0;
    }
    if(!isset($data['map'])){
      $data['map']=0;
    }
    if( $product->map != $data['map'] || $product->msrp != $data['msrp']){
      $productPriceChange= \Devvly\Product\Models\ProductPriceChange::where('upc',$data['upc'])->first();
      if($productPriceChange){
        $productPriceChange->old_map=$product->map;
        $productPriceChange->old_msrp=$product->msrp;
        $productPriceChange->map=$data['map'];
        $productPriceChange->msrp=$data['msrp'];
        $productPriceChange->save();
      }else{
        $productPriceChange= new \Devvly\Product\Models\ProductPriceChange();
        $productPriceChange->product_id=$product->id;
        $productPriceChange->upc=$product->upc;
        $productPriceChange->old_map=$product->map;
        $productPriceChange->old_msrp=$product->msrp;
        $productPriceChange->map=$data['map'];
        $productPriceChange->msrp=$data['msrp'];
        $productPriceChange->save();
      }
      $this->sendPriceWebhook($product->id,$data['map'],$data['msrp'],'rsr');
    }


    if($product){
      $product->update($data);
      if(isset($data['inventory'])){
        if(isset($data['inventory']['attributes'])){
          $attribute= Attribute::where('pid',$product->id)->first();
          if($attribute){
            $attribute->description = $data['inventory']['attributes']['description'];
            $attribute->specifications = $data['inventory']['attributes']['specifications'];
            if(isset($data['inventory']['attributes']['teaser'])){
              $attribute->teaser= $data['inventory']['attributes']['teaser'];
            }
            if(isset($data['inventory']['attributes']['caliber'])){
              $attribute->caliber=$data['inventory']['attributes']['caliber'];
            }
            if(isset($data['inventory']['attributes']['manufacturer'])){
              $attribute->manufacturer=$data['inventory']['attributes']['manufacturer'];
            }
            if(isset($data['inventory']['attributes']['color'])){
              $attribute->color=$data['inventory']['attributes']['color'];
            }
            if(isset($data['inventory']['attributes']['man_part_num'])){
              $attribute->man_part_num=$data['inventory']['attributes']['man_part_num'];
            }
            if(isset($data['inventory']['attributes']['model'])){
              $attribute->model=$data['inventory']['attributes']['model'];
            }
            if(isset($data['inventory']['attributes']['lbs'])){
              $attribute->lbs=$data['inventory']['attributes']['lbs'];
            }
            if(isset($data['inventory']['attributes']['oz'])){
              $attribute->oz=$data['inventory']['attributes']['oz'];
            }
            if(isset($data['inventory']['attributes']['height'])){
              $attribute->height=$data['inventory']['attributes']['height'];
            }
            if(isset($data['inventory']['attributes']['width'])){
              $attribute->width=$data['inventory']['attributes']['width'];
            }
            if(isset($data['inventory']['attributes']['length'])){
              $attribute->length=$data['inventory']['attributes']['length'];
            }
            if(isset($data['inventory']['attributes']['subcategory'])){
              $attribute->subcategory=$data['inventory']['attributes']['subcategory'];
            }
            if(isset($data['inventory']['attributes']['capacity'])){
              $attribute->capacity=$data['inventory']['attributes']['capacity'];
            }
            $attribute->save();
          }else{
            $attribute=new Attribute();
            $attribute->pid=$product->id;
            $attribute->description = $data['inventory']['attributes']['description'];
            $attribute->specifications = $data['inventory']['attributes']['specifications'];
            if(isset($data['inventory']['attributes']['teaser'])){
              $attribute->teaser= $data['inventory']['attributes']['teaser'];
            }
            if(isset($data['inventory']['attributes']['caliber'])){
              $attribute->caliber=$data['inventory']['attributes']['caliber'];
            }
            if(isset($data['inventory']['attributes']['manufacturer'])){
              $attribute->manufacturer=$data['inventory']['attributes']['manufacturer'];
            }
            if(isset($data['inventory']['attributes']['color'])){
              $attribute->color=$data['inventory']['attributes']['color'];
            }
            if(isset($data['inventory']['attributes']['man_part_num'])){
              $attribute->man_part_num=$data['inventory']['attributes']['man_part_num'];
            }
            if(isset($data['inventory']['attributes']['model'])){
              $attribute->model=$data['inventory']['attributes']['model'];
            }
            if(isset($data['inventory']['attributes']['lbs'])){
              $attribute->lbs=$data['inventory']['attributes']['lbs'];
            }
            if(isset($data['inventory']['attributes']['oz'])){
              $attribute->oz=$data['inventory']['attributes']['oz'];
            }
            if(isset($data['inventory']['attributes']['height'])){
              $attribute->height=$data['inventory']['attributes']['height'];
            }
            if(isset($data['inventory']['attributes']['width'])){
              $attribute->width=$data['inventory']['attributes']['width'];
            }
            if(isset($data['inventory']['attributes']['length'])){
              $attribute->length=$data['inventory']['attributes']['length'];
            }
            if(isset($data['inventory']['attributes']['subcategory'])){
              $attribute->subcategory=$data['inventory']['attributes']['subcategory'];
            }
            if(isset($data['inventory']['attributes']['capacity'])){
              $attribute->capacity=$data['inventory']['attributes']['capacity'];
            }
            $attribute->save();
          }
        }

        if(isset($data['extra_attr'])){
          foreach ($data['extra_attr'] as $key => $attribute){
            if($attribute){
              $ExtraAttr= ExtraAttr::where('pid',$product->id)->where('type',$key)->first();
              if($ExtraAttr){
                $ExtraAttr->value=$attribute;
                $ExtraAttr->save();
              }else{
                $ExtraAttr= new ExtraAttr();
                $ExtraAttr->pid=$product->id;
                $ExtraAttr->type=$key;
                $ExtraAttr->value=$attribute;
                $ExtraAttr->distributor=$data['inventory']['did'];
                $ExtraAttr->save();
              }
            }
          }
        }
        if(isset($data['restrictions'])){
          $Restriction=Restriction::where('pid',$product->id)->first();
          if($Restriction){
            $Restriction->ground=$data['restrictions']['ground'];
            $Restriction->sig=$data['restrictions']['sig'];
            $Restriction->save();
          }else{
            $Restriction=new Restriction();
            $Restriction->pid=$product->id;
            $Restriction->ground=$data['restrictions']['ground'];
            $Restriction->sig=$data['restrictions']['sig'];
            $Restriction->save();
          }
        }
    }
    }
    return $product;
  }




  public function getAllProducts($request) {
    try {
      $data = $request->all();

      $results = $this->with(['inventories','category','images','relatedProducts','restrictions','restrictionStates','attributes','extraAttrs'])->scopeQuery(function ($query) use ($data) {
        $qb = $query->distinct()
          ->addSelect('products.*')
          ->where('active',1);

        if (isset($data['category'])) {
          $ids        = [];
          $categories = Category::whereIn(
            'name',
            $data['category']
          )->with(['children'])->get();
          if ($categories) {
            if(sizeof($categories) > 0){
              $ids = $categories[0]->getAllChildren()->pluck('id')->toArray();
              array_push($ids, $categories[0]->id);
            }
          }

          $qb = $qb->whereIn('products.category', $ids);
        }

        if (isset($data['distributor'])) {
          $qb = $qb->leftJoin(
            'inventories',
            'products.id',
            '=',
            'inventories.pid'
          )
            ->whereIn('inventories.distributor', $data['distributor']);
        }

        return $qb;
      })->paginate(isset($data['limit']) ? $data['limit'] : 12);
      return response()->json([
        'status'   => 200,
        'message'  => 'success',
        'products' => $results,
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function findProductById($id) {
    try {
      $product = $this->find($id);
      if ($product) {
        $product->category          = $product->category()->get();
        $product->inventory         = $product->inventories()->get();
        $product->images            = $product->images()->get();
        $product->relatedProducts   = $product->relatedProducts()->get();
        $product->restrictions      = $product->restrictions()->get();
        $product->restrictionStates = $product->restrictionStates()->get();
        $product->attributes        = $product->attributes()->get();
        $product->extraAttrs        = $product->extraAttrs()->get();
        return response()->json([
          'status'  => 200,
          'message' => 'success',
          'product' => $product,
        ], 200);
      }else{
        return response()->json([
          'status'  => 500,
          'message' => 'product not found',
        ], 200);
      }
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function findProductImages($product_id) {
    try {
      $productImages = Image::where('pid', $product_id)
        ->get();
      return response()->json([
        'status'  => 200,
        'message' => 'success',
        'images'  => $productImages,
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function getAllCategories() {
    try {
      $categories = Category::all();
      return response()->json([
        'status'     => 200,
        'message'    => 'success',
        'categories' => $categories,
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function getCategoryById($id) {
    try {
      $category = Category::find($id);
      return response()->json([
        'status'     => 200,
        'message'    => 'success',
        'category' => $category,
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }


  public function getAllManufacturers() {
    try {
      $manufacturers = Manufacturer::all();
      return response()->json([
        'status'     => 200,
        'message'    => 'success',
        'manufacturers' => $manufacturers,
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function getManufacturerById($id) {
    try {
      $manufacturer = Manufacturer::find($id);
      return response()->json([
        'status'     => 200,
        'message'    => 'success',
        'manufacturer' => $manufacturer,
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function getStockOfProduct($product_id){
    try {
      $total=0;
      $inventories = Inventory::where('pid',$product_id)->get();
      foreach ($inventories as $inventory){
        $total+=$inventory->stock;
      }
      return response()->json([
        'status'     => 200,
        'message'    => 'success',
        'inventories' => $inventories,
        'total'=>$total
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function getStockBySkuOfProduct($sku){
    try {
      $total=0;
      $product = $this->findWhere(['upc'=>$sku])->first();
      if($product){
        $inventories = Inventory::where('pid',$product->id)->get();
        foreach ($inventories as $inventory){
          $total+=$inventory->stock;
        }
        return response()->json([
          'status'     => 200,
          'message'    => 'success',
          'inventories' => $inventories,
          'total'=>$total
        ], 200);
      }else{
        return response()->json([
          'status'     => 400,
          'message'    => 'success'
        ], 200);
      }

    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function getDistributors(){
    try {
      $distributors = Distributor::all();
      return response()->json([
        'status'     => 200,
        'message'    => 'success',
        'distributors' => $distributors
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 400);
    }
  }

  public function sendInventoryWebhook($product_id,$totalStock,$source){
    $product=$this->find($product_id);
    $product->category          = $product->category()->get();
    $product->inventories         = $product->inventories()->get();
    $product->images            = $product->images()->get();
    $product->relatedProducts   = $product->relatedProducts()->get();
    $product->restrictions      = $product->restrictions()->get();
    $product->restrictionStates = $product->restrictionStates()->get();
    $product->attributes        = $product->attributes()->get();
    $product->extraAttrs        = $product->extraAttrs()->get();
    $users= \App\Models\User::All();
    foreach ($users as $user){
     if($user->webhook_url){
       $data = [
         'status_code' => 200,
         'status' => 'success',
         'type' => 'inventory',
         'inventory' =>  $totalStock,
         'source' =>  $source,
         'product' =>  $product
       ];
       $json_array = json_encode($data);
       $curl = curl_init();
       $headers = ['Content-Type: application/json'];

       curl_setopt($curl, CURLOPT_URL, $user->webhook_url.'/?adata_up_inv=1');
       curl_setopt($curl, CURLOPT_POST, 1);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $json_array);
       curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_HEADER, 1);
       curl_setopt($curl, CURLOPT_TIMEOUT, 30);

       $response = curl_exec($curl);
       $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

       curl_close($curl);

       if ($http_code >= 200 && $http_code < 300) {
         return response()->json([
           'message' => 'webhook sent successfully.'
         ]);
       } else {
         return response()->json([
           'message' => 'webhook failed.'
         ]);
       }
     }
    }
    return response()->json([
      'message' => 'webhook sent successfully.'
    ]);
  }

  public function sendPriceWebhook($product_id,$map,$msrp,$source){

    $product=$this->find($product_id);
    $users=\App\Models\User::All();
    foreach ($users as $user){
      if($user->webhook_url){
        $data = [
          'status_code' => 200,
          'type' => 'price',
          'status' => 'success',
          'map' =>  $map,
          'msrp' =>  $msrp,
          'source' =>  $source,
          'product' =>  $product
        ];
        $json_array = json_encode($data);
        $curl = curl_init();
        $headers = ['Content-Type: application/json'];

        curl_setopt($curl, CURLOPT_URL, $user->webhook_url.'/?adata_up_price=1');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_array);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($http_code >= 200 && $http_code < 300) {
          return response()->json([
            'message' => 'webhook sent successfully.'
          ]);
        } else {
          return response()->json([
            'message' => 'webhook failed.'
          ]);
        }
      }
    }
    return response()->json([
      'message' => 'webhook sent successfully.'
    ]);

  }

  public function sendDeleteProductWebhook($upc,$source){

    $users= \App\Models\User::All();
    foreach ($users as $user){
      if($user->webhook_url){
        $data = [
          'status_code' => 200,
          'status' => 'success',
          'type' => 'delete-product',
          'source' =>  $source,
          'upc' =>  $upc
        ];
        $json_array = json_encode($data);
        $curl = curl_init();
        $headers = ['Content-Type: application/json'];

        curl_setopt($curl, CURLOPT_URL, $user->webhook_url.'/?adata_up_delete_product=1');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_array);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($http_code >= 200 && $http_code < 300) {
          return response()->json([
            'message' => 'webhook sent successfully.'
          ]);
        } else {
          return response()->json([
            'message' => 'webhook failed.'
          ]);
        }
      }
    }
    return response()->json([
      'message' => 'webhook sent successfully.'
    ]);
  }

}
