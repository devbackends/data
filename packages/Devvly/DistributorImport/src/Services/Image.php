<?php

namespace Devvly\DistributorImport\Services;

use Illuminate\Support\Facades\Storage;
use File;
use Devvly\Product\Models\Image as ProductImage;

class Image
{
    /**
     * @var Distributor;
     */
    protected $distributor;

    public function __construct()
    {
        $this->distributor = app('Devvly\DistributorImport\Services\Distributor');


    }

    /**
     * @param $remoteImageName
     * @param $productId
     * @return bool
     */
    public function execute($remoteImageName, $productId)
    {

        // Get image from remote server
        $imagePattern=$this->initiateImagePattern($remoteImageName);
        foreach ($imagePattern as $key => $pattern){
            $image = $this->getImage($pattern);
            if ($image) {
                $destinationPath = public_path('/storage/app/public/product/rsr/' . $productId);
                $input['large_image'] = 'large_image_' . rand(10000000000, 1000000000000000000) . '.jpg';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                file_put_contents($destinationPath.'/' . $input['large_image'],$image);
                $width = 2000; // your max width
                $height = 2000; // your max height
                $large_image = \Intervention\Image\Facades\Image::make($destinationPath . '/' . $input['large_image']);
                $large_image->height() > $large_image->width() ? $width=null : $height=null;
                $large_image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['large_image']);

                // Save image locally
                Storage::disk('wassabi')->put('rsr/products/' . $productId . '/' . $input['large_image'], File::get($destinationPath . '/' . $input['large_image']));

                unlink($destinationPath .'/' . $input['large_image']);
                // Update product with new image
                 $this->updateProductImage($input['large_image'], $productId);
            }
        }
        return true;
    }


    public function initiateImagePattern($remoteImageName){
        $imageNameArr = explode('.',$remoteImageName);
        $imageName=$imageNameArr[0];
        $newstring = substr($imageName, -2);
        $fixedString = substr($imageName, 0,-2);
        $pattern=[$remoteImageName];
        if($newstring=='_1'){
            $pattern=[];
            for ($i=1;$i < 4;$i++){
                array_push($pattern,$fixedString.'_'.$i);
            }
        }

        return $pattern;
    }

    /**
     * @param $name
     * @return false|mixed|string
     */
    protected function getImage($name)
    {
        $image = $this->getLocalImage($name.'.jpg');

        if (!$image) {
            foreach (config('distimport.remoteImagesFolders') as $folder) {

                if($folder=='ftp_highres_images/rsr_number/#/'){
                    $image = $this->distributor->get($folder.$name.'_HR.jpg');
                }elseif($folder=='ftp_highres_images/rsr_number/'){
                   if(ctype_alpha(substr($name,0,1))){
                       $image = $this->distributor->get($folder.strtolower(substr($name,0,1)).'/'.$name.'_HR.jpg');
                   }
                }elseif($folder=='ftp_images/rsr_number/#/'){
                    $image = $this->distributor->get($folder.$name.'.jpg');
                }else{
                    if(ctype_alpha(substr($name,0,1))){
                        $image = $this->distributor->get($folder.strtolower(substr($name,0,1)).'/'.$name.'.jpg');
                    }
                }

                if ($image) break;
            }
        }

        return $image;
    }

    /**
     * @param $name
     * @return false|string
     */
    protected function getLocalImage($name)
    {
        $filePath = storage_path('app/public/').config('distimport.localImagesFolder').$name;
        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
        return false;
    }

    /**
     * @param $path
     * @param $productId
     * @return bool
     */
    protected function updateProductImage($large_image, $productId) {
      $image=new ProductImage();
      $image->path='https://s3.us-east-1.wasabisys.com/2adata/rsr/products/' . $productId . '/' . $large_image;
      $image->pid=$productId;
      $image->save();
    }
}