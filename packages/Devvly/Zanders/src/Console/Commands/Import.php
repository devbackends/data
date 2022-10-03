<?php

namespace Devvly\Zanders\Console\Commands;

use Devvly\Zanders\Models\ZandersProducts;
use Devvly\Zanders\Services\Validator;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use XPathSelector\Selector;
use DB;

class Import extends Command {


  protected $signature   = 'import-zanders';

  protected $description = 'Import Zanders Products';

  public function handle() {
    // Get data
    $this->comment('IMPORT | Fetch data');
    $this->setImportLog(0);
    $data = $this->getData();
    $this->comment('IMPORT | Data received successfully');
    $this->saveData($data);
    $this->comment('IMPORT | Data successfully saved in db');
    $this->comment('IMPORT | Products Imported to distributors table');
    $this->comment('IMPORT | Fetch Description');
    $descriptions = $this->getDescriptions();
    $this->saveDescriptions($descriptions);
    $this->comment('IMPORT | Descriptions Saved');
    $this->comment('IMPORT | Fetch extra attributes');
    $attributes = $this->getExtraAttributes();
    $this->comment('IMPORT | extra attributes received successfully');
    $this->saveExtraAttributes($attributes);
    $this->comment('IMPORT | Extra attributes Saved successfully');
    // import images
    $this->comment('IMPORT | Fetch Images');
    $attributes = $this->getImages();
    $this->comment('IMPORT | Images received successfully');
    $this->saveImages($attributes);
    $this->comment('IMPORT | Images Saved successfully');
    //end import images
    $this->info('IMPORT | DONE');
    Log::info('IMPORT | DONE');
    $this->setImportLog(1);
  }

  function setImportLog($value) {
    if ($value == 0) {
      DB::table('zanders_logs')
        ->where('id', 1)
        ->update(
          [
            'import_zanders'    => $value,
            'import_latest_run' => date("Y-m-d H:i:s"),
          ]
        )
      ;
    }
    else {
      $res = DB::table('zanders_logs')->where(['id' => 1])->first();
      if (!$res) {
        DB::table('zanders_logs')->insert(
          ['id' => 1, 'import_zanders' => $value]
        )
        ;
      }
      else {
        DB::table('zanders_logs')
          ->where('id', 1)
          ->update(['import_zanders' => $value])
        ;
      }
    }
  }

  /**
   * @return array|array[]
   */
  protected function getData() {
    return (new Validator('main'))->execute();
  }

  /**
   * @param $data
   *
   * @return mixed
   */
  protected function saveData($data) {
    ZandersProducts::truncate();
    // Prepare data for db insert
    $chunks = array_chunk($data, 10000);
    foreach ($chunks as $chunk) {
      $result = array_map(function ($item) {
        return [
          'item_number' => $item['itemnumber'],
          'data'        => json_encode($item),
        ];
      }, $chunk);
      // Create database and insert fresh data
      ZandersProducts::insert($result);
    }

    return TRUE;
  }

  protected function getDescriptions() {
    $descriptions = (new Validator('main'))->getDescriptions();
    return $descriptions;
  }

  protected function saveDescriptions($descriptions) {
    $this->output->progressStart(sizeof($descriptions));
    foreach ($descriptions as $description) {
      $zandersProducts = ZandersProducts::where('item_number', $description[0])
        ->first();
      if ($zandersProducts) {
        $data                         = $zandersProducts->data;
        $data                         = json_decode($data, TRUE);
        $data['detailed_description'] = $description;
        $zandersProducts->data        = $data;
        $zandersProducts->save();
      }
      $this->output->progressAdvance();
    }

    $this->output->progressFinish();
  }

  public function getExtraAttributes() {
    $attributes = (new Validator('main'))->getExtraAttributes();
    return $attributes;
  }

  public function saveExtraAttributes($extraAttrs) {
    $this->output->progressStart(sizeof($extraAttrs));
    $i = 0;
    foreach ($extraAttrs as $key => $extraAttr) {
      $zandersProducts = ZandersProducts::where('item_number', $key)->first();
      if ($zandersProducts) {
        try {
          $data = $zandersProducts->data;
          if ($data) {
            $data                  = json_decode($data, TRUE);
            $data['extra_attrs']   = [];
            $data['extra_attrs']   = $extraAttr;
            $zandersProducts->data = $data;
            $zandersProducts->save();
          }
        } catch (Exception $e) {
        }
      }
      $this->output->progressAdvance();
      $i += 1;
    }
    $this->output->progressFinish();
  }

  function getImages() {
    $images = (new Validator('main'))->getImages();
    return $images;
  }

  function saveImages($data) {
    $this->output->progressStart(sizeof($data));
    $i = 0;
    foreach ($data as $key => $images) {
      $zandersProducts = ZandersProducts::where('item_number', $key)->first();
      if ($zandersProducts) {
        try {
          $data = $zandersProducts->data;
          if ($data) {
            $data                  = json_decode($data, TRUE);
            $data['images']        = $images;
            $zandersProducts->data = $data;
            $zandersProducts->save();
          }
        } catch (Exception $e) {
        }
      }
      $this->output->progressAdvance();
      $i += 1;
    }
    $this->output->progressFinish();
  }


}
