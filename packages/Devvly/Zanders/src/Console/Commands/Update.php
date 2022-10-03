<?php
namespace Devvly\Zanders\Console\Commands;
use Devvly\Zanders\Models\ZandersProducts;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
class Update extends Command {
  protected $signature = 'update-zanders';
  protected $description = 'Update products in our db with products exported from remote server';

  public function handle() {
    $this->comment('UPDATE | Start');
    $this->info('UPDATE | Start');
    $zandersProducts = ZandersProducts::all();
    $this->output->progressStart(sizeof($zandersProducts));
    foreach ($zandersProducts as $key => $zanderProduct) {
      if (isset($zanderProduct->data)) {
        if ($zanderProduct->data) {
          if (isset(json_decode($zanderProduct->data)->upc)) {
            if (json_decode($zanderProduct->data)->upc) {
              $upc = json_decode($zanderProduct->data)->upc;
              //below code is written to check the case when sometime an rsr products has no upc and after that they add a upc , in this case we need to update upc
              if (app("Devvly\Product\Repositories\ProductRepository")
                ->findWhere(['upc' => json_decode($zanderProduct->data)->itemnumber])
                ->first()) {
                $product = app("Devvly\Product\Repositories\ProductRepository")
                  ->findWhere(['upc' => json_decode($zanderProduct->data)->itemnumber])
                  ->first();
                $product->upc = $upc;
                $product->save();
              }
              $product = app("Devvly\Product\Repositories\ProductRepository")->findWhere(['upc' => $upc])->first();
              if ($product) {
                if ($zanderProduct['item_number'] != 'itemnumber') {
                  $this->updateProduct($zanderProduct->item_number);
                }
              } else {
                if ($zanderProduct['item_number'] != 'itemnumber') {
                  if(json_decode($zanderProduct->data)->category){
                    $this->addNewProduct($zanderProduct->item_number);
                  }
                }
              }
            }
          } elseif (isset(json_decode($zanderProduct->data)->itemnumber)) {
            $itemnumber = json_decode($zanderProduct->data)->itemnumber;
            $product = app("Devvly\Product\Repositories\ProductRepository")->findWhere(['upc' => $itemnumber])->first();
            if ($product) {
              $this->updateProduct($itemnumber);
            } else {
              if(json_decode($zanderProduct->data)->category){
                $this->addNewProduct($itemnumber);
              }
            }
          }
        }
      }
      $this->output->progressAdvance();
    }
    $this->output->progressFinish();
    $this->comment('UPDATE | DONE');
    $this->info('UPDATE | DONE');
  }

  /**
   * @param $products
   */
  protected function updateProduct($item_number) {
    if ($item_number) {
      app('Devvly\Zanders\Services\Product')->updateProducts($item_number);
    }
  }

  /**
   * @param $products
   */
  protected function addNewProduct($item_number) {
    if ($item_number) {
      app('Devvly\Zanders\Services\Product')->createProduct($item_number);
    }
  }
}