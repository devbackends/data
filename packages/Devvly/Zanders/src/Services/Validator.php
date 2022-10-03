<?php

namespace Devvly\Zanders\Services;

use DB;
use Devvly\Product\Models\Category;
use Devvly\Product\Models\Distributor;
use Devvly\Product\Models\Manufacturer;
use Devvly\Product\Models\Caliber;
use Devvly\Product\Models\MapCategory;


class Validator {

  public    $attributes;

  /**
   * @var Distributor
   */
  protected $distributor;

  /**
   * @var array
   */
  protected $fileConfig;

  /**
   * Validator constructor.
   *
   * @param  string  $fileConfigType
   */
  public function __construct(string $fileConfigType) {
    $this->distributor = app('Devvly\Zanders\Services\Zanders');
    $this->fileConfig  = config('zandersimport.files.' . $fileConfigType);
    $this->config      = config('zandersimport.customValidatorsConfigs');
  }

  /**
   * @return array|array[]
   */
  public function execute() {
    $keys   = $this->fileConfig['keys'];
    $values = $this->getValues($this->fileConfig['zanders-products']);

    // Combine
    $data = array_map(function ($value) use ($keys) {
      $x = $value;
      /*          if (count($value) !== count($keys)) {
                  dump($value);
                  dump($keys);exit;
                  $diff = count($value) - count($keys);
                  $value = array_slice($value, 0, count($value) - $diff);
                }*/
      return array_combine($keys, $value);
    }, $values);


    return $data;
  }

  /**
   * Parse remote values file
   *
   * @param  string  $path
   *
   * @return array
   */
  protected function getValues(string $path) {
    // Get remote content
    $content = trim($this->distributor->get($path));
    // Parse file by lines
    $content = explode("\n", $content);
    // Parse line by values
    return array_map(function ($value) {
      $value = str_replace('"', '', $value);
      return explode(',', $value);
    }, $content);
  }

  public function getExtraAttributes() {
    $extra_attributes = $this->distributor->get(
      $this->fileConfig['zanders-extra-attributes']
    );
    $arr              = explode("\n", $extra_attributes);
    $results          = [];
    foreach ($arr as $value) {
      $value     = str_replace('"', '', $value);
      $recordArr = explode(",", $value);
      if (sizeof($recordArr) == 4) {
        if (isset($results[$recordArr[0]])) {
          $results[$recordArr[0]][$recordArr[2]] = $recordArr[3];
        }
        else {
          $results[$recordArr[0]][$recordArr[2]] = $recordArr[3];
        }
      }
    }
    return $results;
  }

  public function getImages() {
    $images  = $this->distributor->get($this->fileConfig['zanders-images']);
    $arr     = explode("\n", $images);
    $results = [];
    foreach ($arr as $value) {
      $value     = str_replace('"', '', $value);
      $recordArr = explode(",", $value);
      if (sizeof($recordArr) == 2) {
        if (isset($results[$recordArr[0]])) {
          $results[$recordArr[0]][] = $recordArr[1];
        }
        else {
          $results[$recordArr[0]][] = $recordArr[1];
        }
      }
    }
    return $results;
  }

  public function getRestrictions() {
    // Get remote content
    $content = trim(
      app('Devvly\Zanders\Services\Zanders')->get(
        config('zandersimport.files.main.restrictions')
      )
    );

    // Parse file by lines
    $content = explode("\n", $content);

    return array_map(function ($value) {
      return explode(";", $value);
    }, $content);
  }

  public function getProductWarnings() {
    // Get remote content
    $content = trim(
      app('Devvly\Zanders\Services\Zanders')->get(
        $this->fileConfig['product-warnings']
      )
    );

    // Parse file by lines
    $content = explode("\n", $content);

    return array_map(function ($value) {
      return explode(";", $value);
    }, $content);
  }

  public function getDescriptions() {
    $description = $this->distributor->get(
      $this->fileConfig['zanders-descriptions']
    );
    $arr         = explode("\n", $description);
    return array_map(function ($value) {
      return explode("~", $value);
    }, $arr);
  }

  function xml2array($xmlObject, $out = []) {
    foreach ((array) $xmlObject as $index => $node) {
      $out[$index] = (is_object($node)) ? xml2array($node) : $node;
    }
    return $out;
  }

  public function validateProductAttribute($rsr_attribute, $attribute_name) {
    if (!empty($rsr_attribute)) {
      $rsr_attribute   = addslashes($rsr_attribute);
      $attributeOption = DB::select(
        "select * from " . $attribute_name
        . " where lower(name)='$rsr_attribute'"
      );
      if ($attributeOption) {
        return $attributeOption[0]->id;
      }
      else {
        return $this->addProductOption($rsr_attribute, $attribute_name);
      }
    }
    return 0;
  }

  public function addProductOption($rsr_attribute, $attribute_name) {
    switch ($attribute_name) {
      case 'caliber':
        $caliber       = new Caliber();
        $caliber->name = $rsr_attribute;
        $caliber->save();
        return $caliber->id;
      case 'manufacturer':
        $manufacturer       = new Manufacturer();
        $manufacturer->name = $rsr_attribute;
        $manufacturer->save();
        return $manufacturer->id;
    }
  }

  public function validateCategory($zander_category) {
    if (!empty($zander_category)) {
      $zanderDistributor = Distributor::where('name', 'zanders')->first();
      $mapCategory       = MapCategory::where('did', $zanderDistributor->id)->where('value', $zander_category)->first();
      if ($mapCategory) {
        return $mapCategory->cid;
      }else{
        $category= new Category();
        $category->name=$zander_category;
        $category->save();
        $mapCategory =new MapCategory();
        $mapCategory->cid=$category->id;
        $mapCategory->did=$zanderDistributor->id;
        $mapCategory->value=$zander_category;
        $mapCategory->save();
        return $mapCategory->cid;
      }
    }
    return '';
  }

}