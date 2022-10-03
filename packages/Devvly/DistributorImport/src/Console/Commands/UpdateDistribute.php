<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Devvly\Product\Models\ExtraAttr;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use XPathSelector\Selector;
use Devvly\Product\Models\RestrictionState;
use DB;

class UpdateDistribute extends Command
{


    protected $signature = 'update-rsr-distribute';

    protected $description = 'Update products distributor feed';

    public function handle()
    {
        $checkProductsImported=DB::table('rsr_logs')->select('*')->where('id', 1)->get();
        if(isset($checkProductsImported[0])){
            $log=$checkProductsImported[0];
            if($log->import_distribute==1){
                // Get data
                $this->setUpdateLog(0);
                $this->comment('Update | Start Updating');
                $call = $this->call('update-distribute');

                // Call next commands
                $this->comment('Update | Fetch Restrictions on products');
                $restrictions = $this->getRestrictions();
                $this->comment('Update | Add Restrictions on products');
                $restrictions = $this->group_by(0, $restrictions);
                $this->addProductRestriction($restrictions);
                $this->comment('Update | Finish adding Restrictions on products');

                $this->comment('Update | Fetch product Warnings on products');
                $productWarnings = $this->getProductWarnings();
                $this->comment('Update | Add Warnings on products');
                $this->addProductWarnings($productWarnings);
                $this->comment('Update | Finish adding Warnings on products');

                $this->comment('Update | start updating inventory');
                $call = $this->call('update-inventory');
                $this->comment('Update | end updating inventory');

                $this->info('Update | DONE');
                Log::info('Update | DONE');
                $this->setUpdateLog(1);
            }else{
                $this->info('Update rsr script cannot be run because the import is not finished');
                Log::info('Update rsr script cannot be run because the import is not finished');
            }
        }else{
            $this->info('Update rsr script cannot be run because the import is not finished');
            Log::info('Update rsr script cannot be run because the import is not finished');
        }
    }



    protected function getRestrictions()
    {
        $restrictions = (new Validator('main'))->getRestrictions();
        return $restrictions;
    }

    protected function getProductWarnings()
    {
        $productWarnings = (new Validator('main'))->getProductWarnings();
        return $productWarnings;
    }


    public function addProductRestriction($data)
    {
        $this->output->progressStart(count($data));
        foreach ($data as $i => $i_value) {
            foreach ($i_value as $j => $j_value) {
              $rsr_id = $j_value[0];
              $distributorProduct = DB::SELECT("SELECT *,JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) as upcCode  FROM distributor_products where rsr_id ='" . $rsr_id . "'");
                if (isset($distributorProduct[0])) {
                  $distributorProduct = $distributorProduct[0];
                  $product = app("Devvly\Product\Repositories\ProductRepository")->findwhere(['upc' => $distributorProduct->upcCode])->first();
                  if (isset($product->id)) {
                    //check record is already imported
                    $check_RestrictionState = RestrictionState::where('pid',$product->id)->where('state',$j_value[2])->where('municipality',$j_value[1])->where('type',$j_value[4])->first();
                        // code below is to add unavailable states to the product attribute values
                        if (!$check_RestrictionState) {
                          $RestrictionState=RestrictionState::where('pid',$product->id)->where('state',$j_value[2])->where('municipality',$j_value[1])->first();
                          if($RestrictionState){
                            $RestrictionState->type=$j_value[4];
                            $RestrictionState->save();
                          }else{
                            $RestrictionState=new RestrictionState();
                            $RestrictionState->pid=$product->id;
                            $RestrictionState->state=$j_value[2];
                            $RestrictionState->municipality=$j_value[1];
                            $RestrictionState->type=$j_value[4];
                            $RestrictionState->save();
                          }
                        }
                    }
                }
            }
            $this->output->progressAdvance();

        }
        $this->output->progressFinish();
    }

  public function addProductWarnings($data)
  {
    $rsrDistributor= \Devvly\Product\Models\Distributor::where('name','rsr')->first();
    if($rsrDistributor){
      $this->output->progressStart(count($data));
      foreach ($data as $i => $item) {
        if ($i > 0) {
          $upcCode = $item[1];
          $product = app("Devvly\Product\Repositories\ProductRepository")->findwhere(['upc' => $upcCode])->first();
          if (isset($product->id)) {
            //check record is already imported
            $checked_product_attribute_value = ExtraAttr::where('pid', $product->id)->where('type',$item[3])->first();
            // code below is to add unavailable states to the product attribute values
            if (!$checked_product_attribute_value) {
              $ExtraAttr=new ExtraAttr();
              $ExtraAttr->pid=$product->id;
              $ExtraAttr->type=$item[3];
              $ExtraAttr->value=$item[4];
              $ExtraAttr->distributor=$rsrDistributor->id;
              $ExtraAttr->save();
            }else{
              $checked_product_attribute_value->value=$item[4];
              $checked_product_attribute_value->save();
            }
          }
        }
        $this->output->progressAdvance();
      }
      $this->output->progressFinish();
    }
  }

    /**
     * Function that groups an array of associative arrays by some key.
     *
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    function group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }
    public function validateProductAttribute($attribute, $attribute_name)
    {

        if (!empty($attribute)) {
            return (new Validator('main'))->validateProductAttribute($attribute, $attribute_name);
        }

        return '';
    }
    function setUpdateLog($value){
        if($value==0){
            DB::table('rsr_logs')
                ->where('id',1)
                ->update(['update_rsr_distribute' =>$value,'update_latest_run' => date("Y-m-d H:i:s")]);
        }else{
            DB::table('rsr_logs')
                ->where('id',1)
                ->update(['update_rsr_distribute' =>$value]);
        }
    }

}
