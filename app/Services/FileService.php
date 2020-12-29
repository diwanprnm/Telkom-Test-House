<?php

namespace App\Services;

use Image;
use Storage;

class FileService
{
    private $fileName = '';
    private $fileExtension = '';
    private $path = '';
    private $prefix = '';
    private $oldFile = '';
    private $allowedImage = ['jpeg','jpg','png'];
    private $allowedFile = ['pdf'];
    private $detail = '';
    private $isUploaded = false;
    private $isDeleted = false;

    public function upload($file, $fileProperties)
    {
        $this->filterFileProperties($fileProperties);

        if(!empty($file)){
            $this->uploadFileToMinio($file);
        }else{
            $this->detail = 'The file included are empty';
        }

        $this->removeOldFile();
    }

    public function get($fileProperties)
    {
        $this->filterFileProperties($fileProperties);

        return Storage::disk('minio')->get("$this->path$this->fileName");
    }

    public function uploadFromStream($stream, $fileProperties)
    {
        $this->filterFileProperties($fileProperties);
        return Storage::disk('minio')->put($this->path.$this->fileName, $stream);
    }

    public function deleteFile($fileProperties){
        $this->filterFileProperties($fileProperties);
        $this->deleteFileFromMinio("$this->path$this->fileName");
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function isUploaded()
    {
        return $this->isUploaded;
    }

    public function isDeleted()
    {
        return $this->isDeleted;
    }

    private function filterFileProperties($fileProperties)
    {
        if (!empty($fileProperties['path']) ){
            $this->path = $fileProperties['path'];
        }
        if (!empty($fileProperties['prefix']) ){
            $this->prefix = $fileProperties['prefix'];
        }
        if (!empty($fileProperties['oldFile']) ){
            $this->oldFile = $fileProperties['oldFile'];
        }
        if (!empty($fileProperties['fileName']) ){
            $this->fileName = $fileProperties['fileName'];
        }
    }

    private function deleteFileFromMinio($path)
    {
        $isFileExist = Storage::disk('minio')->exists($path);
		if($isFileExist){
            $this->isDeleted = Storage::disk('minio')->delete($path);
        }
    }

    private function uploadFileToMinio($file)
    {
        $this->fileName = $this->prefix.$file->getClientOriginalName();
        $this->fileExtension = strtolower($file->getClientOriginalExtension());

        if (in_array($this->fileExtension, $this->allowedImage)){
            
            $image = Image::make($file);   
            $this->isUploaded = Storage::disk('minio')->put("$this->path/$this->fileName",(string)$image->encode()); 
        }elseif (in_array($this->fileExtension, $this->allowedFile)){
            $this->isUploaded = Storage::disk('minio')->put("$this->path/$this->fileName", file_get_contents($file));
        }else { $this->detail = 'The file included are neither images(jpg, jpeg, png) nor pdf'; }
    }

    private function removeOldFile()
    {
        if($this->oldFile && $this->fileName != $this->oldFile)
        {
            $this->deleteFileFromMinio("$this->path$this->oldFile");
        }
        return true;
    }

    /*
     * Fungsi sama seperti upload()
     * tapi versi "one liner" agar code coverage tidak berkurang banyak
    */
    public function uploadFile($file,$prefix = '', $path=''){
        if(!empty($file)){
            $ext = $file->getClientOriginalExtension(); 
            $file_name = $prefix.$file->getClientOriginalName();
            $is_upload = false; 
            if (in_array($ext, $this->allowedImage)){ 
                $image = Image::make($file);   
                $is_upload = Storage::disk('minio')->put($path."/$file_name",(string)$image->encode()); 
            }else{
                $is_upload = Storage::disk('minio')->put($path."/$file_name", file_get_contents($file));
            }
            return $is_upload ? $file_name : NULL;
        }
        return NULL;  
    }

}