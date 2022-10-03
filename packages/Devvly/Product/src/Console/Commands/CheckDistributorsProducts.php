<?php

namespace Devvly\Product\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;

class CheckDistributorsProducts extends Command
{


  protected $signature = 'check-distributors-products';

  protected $description = 'Check if products is not found anymore in the distributors products';

  public function handle()
  {

    $this->comment('Checking products');
    // Save data , here we are truncating table and inserting data everytime
    $this->checkProducts();
  }

  public function checkProducts(){
    $products= app("Devvly\Product\Repositories\ProductRepository")->all();
    $this->output->progressStart(sizeof($products));
    foreach ($products as $product){
      $found=0;
      $checkRsrUpc=\Devvly\DistributorImport\Models\DistributorProducts::where(DB::raw('JSON_EXTRACT(`data`, "$.upcCode")',$product->upc))->first();
      if($checkRsrUpc){
        $found=1;
      }

      $checkRsrId=\Devvly\DistributorImport\Models\DistributorProducts::where('rsr_id',$product->upc)->first();
      if($checkRsrId){
        $found=1;
      }

      $checkZandersUpc=\Devvly\Zanders\Models\ZandersProducts::where(DB::raw('JSON_EXTRACT(`data`, "$.upc")',$product->upc))->first();
      if($checkZandersUpc){
        $found=1;
      }

      $checkZandersItemNumber=\Devvly\Zanders\Models\ZandersProducts::where('item_number',$product->upc)->first();
      if($checkZandersItemNumber) {
        $found = 1;
      }

      if($found==0){
        dump($product->id." is not found");
        $product->active=0;
        $product->save();
      }
      $this->output->progressAdvance();
    }
    $this->output->progressFinish();

  }


}
