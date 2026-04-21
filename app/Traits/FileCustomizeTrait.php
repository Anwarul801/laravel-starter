<?php

namespace App\Traits;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManagerStatic;
use Illuminate\Support\Facades\File;


trait FileCustomizeTrait{

    /**
     * @param $img_name
     * @param null $attribute
     * @param int $width
     * @param string $file_extension
     * @return null|string
     */
    public static function storeImgWithConvertEncodeToDecode($img_name, $destination = null, $file_extension = 'png', $watermark = null, $width = 100){

        if($img_name == null){
            return null;
        }
        $unique_name = uniqid().time().uniqid().".$file_extension";

        if(!$destination){
            $destinationPath = public_path().'/storages/random'.$unique_name;
        }else{
            $destinationPath = public_path().$destination.$unique_name;
        }

        $img = ImageManagerStatic::make($img_name)->insert(public_path().$watermark, 'center',100, 100)->encode('png')->save($destinationPath);

        return $destination.$unique_name;
    }


    public static function deleteFile($img){
        if ($img == '') {
            return null;
        }


        if (file_exists(public_path()."/".$img)) {
            unlink(public_path()."/".$img);

        }

    }


    public function img_manupulate_from_base64($data, $destination = null, $extension = 'png'){
        $image_array_1 = explode(";", $data);

        $image_array_2 = explode(",", $image_array_1[1]);

        $data = base64_decode($image_array_2[1]);

        $temp = tmpfile();
        fwrite($temp, $data);

        // Get the path of the temp file.
        $tempPath = stream_get_meta_data($temp)['uri'];

        // Initialize the UploadedFile.
        $imageName = uniqid().time().uniqid().".$extension";
        $file = new \Illuminate\Http\UploadedFile($tempPath, $imageName, null, null, true);

        if(!$destination){
            $destinationPath = public_path().'/storages/random';
        }else{
            $destinationPath = public_path().$destination;
        }

        $file->move($destinationPath, $imageName);

        // Delete the temp file.
        fclose($temp);

        return $destination.$imageName;
    }


    public static function img_resize($data, $destination = null,$width,$height){  
        $imageName =uniqid().time().uniqid().'.'.$data->extension();
        if(!$destination){
            $destinationPath = public_path().'/storages/random';
        }else{
            $destinationPath = public_path().$destination;
        }
        $img = Image::make($data->path());
        $img->resize($width,$height)->save($destinationPath.'/'.$imageName);
     
        return $destination.$imageName;
    }




}
