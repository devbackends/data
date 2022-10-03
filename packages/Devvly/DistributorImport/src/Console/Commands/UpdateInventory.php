<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Devvly\Product\Models\Inventory;
use Devvly\Product\Models\Distributor;
use DB;

class UpdateInventory extends Command
{
    protected $signature = 'update-inventory';

    protected $description = 'Update products inventories from distributor feed';

    public function handle()
    {

        Log::info("Start Updating Inventory");
        // Get data


            $this->comment('Start Fetch data');
            $data = $this->getData();

            $this->comment('Data received successfully');

            $this->comment('Start updating inventory');
            // Update Inventory data
            $update=$this->updateInventory($data);
            $this->comment('Inventories Updated successfully');
        

        Log::info("Finish Updating Inventory");

    }

    protected function getData(){
        // Get remote content
        $content = trim(app('Devvly\DistributorImport\Services\Distributor')->get(config('distimport.files.main.inventory')));

        // Parse file by lines
        $content = explode("\n", $content);

        return $content;
    }
    protected function updateInventory($data){

        $this->output->progressStart(count($data));
        $rsrDistributor= \Devvly\Product\Models\Distributor::where('name','rsr')->first();
        if($rsrDistributor){
          foreach ($data as $item) {
            $x=explode(',',$item);
            $rsr_id=$x[0];
            $quantity=$x[1];
            $distributorProduct=DB::SELECT("SELECT *,JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) as upcCode  FROM distributor_products where rsr_id ='" . $rsr_id . "'");
            if(isset($distributorProduct[0])) {
              $distributorProduct=$distributorProduct[0];
              $product = app("Devvly\Product\Repositories\ProductRepository")->findwhere(['upc'=>$distributorProduct->upcCode])->first();
              if($product){
                $inventory=Inventory::where('pid',$product->id)->where('did',$rsrDistributor->id)->first();
                if($inventory){
                  $old_quantity=$inventory->stock;
                  $inventory->stock=$quantity;
                  $inventory->save();
                  if($old_quantity != (int) $quantity){
                    $inventories=Inventory::where('pid',$product->id);
                    $totalStock=0;
                    foreach ($inventories as $inv){
                      $totalStock+=$inv->stock;
                    }
                    app("Devvly\Product\Repositories\ProductRepository")->sendInventoryWebhook($product->id,$totalStock,$rsrDistributor->name);
                  }
                }else{
                  $inventory=new Inventory();
                  $inventory->pid=$product->id;
                  $inventory->did=$rsrDistributor->id;
                  $inventory->distributor=$rsrDistributor->name;
                  $inventory->sku=$distributorProduct->upcCode;
                  $inventory->stock=$quantity;
                  $inventory->save();
                }
                }
            }
            $this->output->progressAdvance();
          }
        }
    }


}
