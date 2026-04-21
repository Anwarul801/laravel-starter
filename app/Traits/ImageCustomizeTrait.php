<?php

namespace App\Traits;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic;
use Illuminate\Support\Facades\File;


trait ImageCustomizeTrait{

    /**
     * @param $img_name
     * @param null $attribute
     * @param int $width
     * @param string $file_extension
     * @return null|string
     */


    public static function deleteImage($image){
        if ($image == '') {
            return null;
        }
        if (file_exists(public_path()."/".$image)) {
            unlink(public_path()."/$image");
        }
    }

//    public static function uploadImage($image, $path, $width = null, $height = null)
//    {
//        $image_name = $image->store("$path", 'public');
//        $image_public_path = public_path('storage/' . $image_name);
//        if ($width != null && $height != null){
//            Image::make($image_public_path)->resize($width, $height)->save();
//        }
//        $image_path = "storage/$image_name";
//        return $image_path;
//    }


    public static function uploadImage($image, $path, $width = null, $height = null)
    {
        // 1️⃣ Store original image
        $image_name = $image->store($path, 'public');
        $image_public_path = public_path('storage/' . $image_name);

        // 2️⃣ Resize if width & height provided
        if ($width !== null && $height !== null) {

            $manager = new ImageManager(new Driver());

            $img = $manager->read($image_public_path);
            $img->resize($width, $height);
            $img->save($image_public_path);
        }

        // 3️⃣ Return path for DB
        return "storage/$image_name";
    }


    public static function uploadImageFromBase64($base64Image, $path, $width = null, $height = null)
    {
        $imageName = uniqid() . '.png';
        $imagePath = public_path("storage/$path/$imageName");

        // Check if folder exists, if not, create it
        if (!file_exists(public_path("storage/$path"))) {
            mkdir(public_path("storage/$path"), 0777, true);
        }

        $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

        // Store and resize
        file_put_contents($imagePath, $decodedImage);
        if ($width && $height) {
            Image::make($imagePath)->resize($width, $height)->save();
        }

        return "storage/$path/$imageName";
    }


}
