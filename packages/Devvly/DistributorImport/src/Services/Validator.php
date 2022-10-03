<?php

namespace Devvly\DistributorImport\Services;

use DB;
use Devvly\Product\Models\Category;
use Devvly\Product\Models\Manufacturer;
use Devvly\Product\Models\Caliber;
use Devvly\Product\Models\MapCategory;


class Validator
{

    /**
     * @var Distributor
     */
    protected $distributor;

    /**
     * @var array
     */
    protected $fileConfig;
    public $attributes;

    /**
     * Validator constructor.
     * @param string $fileConfigType
     */
    public function __construct(string $fileConfigType)
    {
        $this->distributor = app('Devvly\DistributorImport\Services\Distributor');
        $this->fileConfig = config('distimport.files.' . $fileConfigType);
        $this->config = config('distimport.customValidatorsConfigs');
    }

    /**
     * @return array|array[]
     */
    public function execute()
    {
        $keys = $this->fileConfig['keys'];

        $values = $this->getValues($this->fileConfig['content']);

        //filter through our data only
        foreach ($values as $key => $value){
          if(sizeof($value)>=3){
            if (!in_array($value[3],['1','01','2','02','3','03','5','05','7','07','18','23']) ){
              unset($values[$key]);
            }
          }
        }
        // Combine

        $data = array_map(function ($value) use ($keys) {
            if (count($value) !== count($keys)) {
                $diff = count($value) - count($keys);
                $value = array_slice($value, 0, count($value) - $diff);
            }
            return array_combine($keys, $value);
        }, $values);

        if (isset($this->fileConfig['customValidator'])) {
            $data = (new $this->fileConfig['customValidator']($data))->execute();
        }

        return $data;
    }

    /**
     *
     * Parse remote values file
     *
     * @param string $path
     * @return array
     */
    protected function getValues(string $path)
    {
        // Get remote content
        $content = trim($this->distributor->get($path));

        // Parse file by lines
        $content = explode("\n", $content);
        // Parse line by values
        return array_map(function ($value) {
            return explode(';', $value);
        }, $content);
    }

    public function getAttributes()
    {
        $attributes_keys = config('distimport.customValidatorsConfigs.attributes_keys');

        $lines = explode("\n", trim($this->distributor->get($this->fileConfig['attributes'])));
        $i = 0;
        foreach ($lines as $line) {
            $arr[$i] = explode(";", $line);
            if (sizeof($arr[$i]) == sizeof($attributes_keys))
                $result[$i] = array_combine($attributes_keys, $arr[$i]);
            $i++;
        }
        return $result;
    }
    public function getRestrictions()
    {
        // Get remote content
        $content = trim(app('Devvly\DistributorImport\Services\Distributor')->get(config('distimport.files.main.restrictions')));

        // Parse file by lines
        $content = explode("\n", $content);

        return array_map(function ($value) {
            return explode(";", $value);
        }, $content);
    }

    public function getProductWarnings(){
        // Get remote content
        $content = trim(app('Devvly\DistributorImport\Services\Distributor')->get($this->fileConfig['product-warnings']));

        // Parse file by lines
        $content = explode("\n", $content);

        return array_map(function ($value) {
            return explode(";", $value);
        }, $content);
    }

    public function getDescriptions()
    {
        return $this->distributor->get($this->fileConfig['descriptions']);
    }

    function xml2array($xmlObject, $out = array())
    {
        foreach ((array)$xmlObject as $index => $node)
            $out[$index] = (is_object($node)) ? xml2array($node) : $node;
        return $out;
    }

    public function validateProductAttribute($rsr_attribute, $attribute_name)
    {
        if (!empty($rsr_attribute)) {
          $rsr_attribute=str_replace('"','in',$rsr_attribute);
          $rsr_attribute=addslashes($rsr_attribute);
            $attributeOption = DB::select("select * from ".$attribute_name." where lower(name)=lower('$rsr_attribute')");
            if (isset($attributeOption[0]->id)) {
                return $attributeOption[0]->id;
            }else{
                return $this->addProductOption($rsr_attribute, $attribute_name);
            }
        }
        return 0;
    }
    public function addProductOption($rsr_attribute,$attribute_name){
      switch ($attribute_name) {
        case 'caliber':
          $caliber = new Caliber();
          $caliber->name = $rsr_attribute;
          $caliber->save();
          return $caliber->id;
        case 'manufacturer':
          $manufacturer = new Manufacturer();
          $manufacturer->name = $rsr_attribute;
          $manufacturer->save();
          return $manufacturer->id;
      }
    }
   public function validateCategory($rsr_category){
     if (!empty($rsr_category)) {
       $productCategory = $this->config['Devvly\DistributorImport\CustomValidators\InvFileValidator']['departmentToCategory'][$rsr_category]['category'];
      return $productCategory;
       /*       $rsrDistributor= \Devvly\Product\Models\Distributor::where('name','rsr')->first();
       $mapCategory = MapCategory::where('did',$zanderDistributor->id)->where('value',$rsr_category)->first();
       if($mapCategory){
         return $mapCategory->cid;
       }*/
     }
     return '';
   }
  public function getRsrDeletedProducts(){
    $values = $this->getValues($this->fileConfig['deleteRsrProducts']);
    return $values;
  }

}