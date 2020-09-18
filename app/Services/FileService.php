<?php

namespace App\Services;

use Image;
use Storage;

class FileService
{
    public function uploadFile($file,$prefix = '',$path){
        $allowedImage = ['jpeg','jpg','png'];
        $allowedFile = ['pdf'];
        
        $ext = $file->getClientOriginalExtension(); 
        $file_name = $prefix.$file->getClientOriginalName();
        
        $is_upload = false;
        if (in_array($ext, $allowedImage)){ 
            $image = Image::make($file);   
            $is_upload = Storage::disk('minio')->put($path."/$file_name",(string)$image->encode()); 
        }else{
            $is_upload = Storage::disk('minio')->put($path."/$file_name", file_get_contents($file));
        }
        return $is_upload ? $file_name : NULL;
    }

    public function deleteFile($path){
        $isFileExist = Storage::disk('minio')->exists($path);
		if(!$isFileExist){
            Storage::disk('minio')->delete($path);
        }
    }
}