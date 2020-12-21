<?php

namespace App\Http\Controllers;

use App\Certification;
use App\Company;
use App\Device;
use App\Examination;
use App\ExaminationAttach;
use App\Footer;
use App\Slideshow;
use App\STEL;
use App\STELSales;
use App\STELSalesAttach;
use App\STELSalesDetail;
use App\TempCompany;
use App\User;

class UploadProductionController extends Controller
{
    private const PROD_URL = 'https://telkomtesthouse.co.id/media/';
    
	/**
     * Create a new controller instance.
     *
     * @return void
     */

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload($dir,$name){
        // ini_set('memory_limit','-1');
        $stream_opts = [
            "ssl" => [
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ]
        ]; 
        $path = self::PROD_URL.$dir.rawurlencode($name);
        $headers = get_headers($path,false, stream_context_create($stream_opts));
        if(substr($headers[0], 9, 3) == 200){
            $file = file_get_contents($path,false, stream_context_create($stream_opts));  
            if($file){
                \Storage::disk('minio')->put($dir.$name, $file);
            }
        }
    }

    public function uploadCertification()
    {
        $data = Certification::where('type', 1)->get();
        foreach($data as $value){
            $this->upload('certification/',$value->image);
        }
    }

    public function uploadCompany()
    {
        $data = Company::all();
        foreach($data as $value){
            $this->upload('company/'.$value->id.'/',$value->npwp_file);
            $this->upload('company/'.$value->id.'/',$value->siup_file);
            $this->upload('company/'.$value->id.'/',$value->qs_certificate_file);
        }
    }

    public function uploadDevice()
    {
        $data = Device::where('certificate', '!=', '')->get();
        foreach($data as $value){
            $this->upload('device/'.$value->id.'/',$value->certificate);
        }
    }

    public function uploadExamination()
    {
        // dariTabel examination
        $data = Examination::where('attachment', '!=', '')->get();
        foreach($data as $value){
            $this->upload('examination/'.$value->id.'/',$value->attachment);
        }
    }

    public function uploadExaminationAttach()
    {
        // dariTabel examination_attachments
        $data = ExaminationAttach::where('attachment', '!=', '')->orderBy('examination_id')->skip(0)->take(1)->get();
        foreach($data as $value){
            $this->upload('examination/'.$value->examination_id.'/',$value->attachment);
        }
    }

    public function uploadFooter()
    {
        $data = Footer::all();
        foreach($data as $value){
            $this->upload('footer/',$value->image);
        }
    }

    public function uploadPopUpInformation()
    {
        $data = Certification::where('type', 0)->get();
        foreach($data as $value){
            $this->upload('popupinformation/',$value->image);
        }
    }

    public function uploadSlideshow()
    {
        $data = Slideshow::all();
        foreach($data as $value){
            $this->upload('slideshow/',$value->image);
        }
    }

    public function uploadStel()
    {
        // dariTabel stels
        $data = STEL::where('attachment', '!=', '')->get();
        foreach($data as $value){
            $this->upload('stel/',$value->attachment);
        }
    }

    public function uploadStelSales1()
    {
        // dariTabel stels_sales
        $data = STELSales::where('id_kuitansi', '!=', '')->orWhere('faktur_file', '!=', '')->get();
        foreach($data as $value){
            if($value->id_kuitansi != ''){
                $this->upload('stel/'.$value->id.'/',$value->id_kuitansi);
            }
            if($value->faktur_file != ''){
                $this->upload('stel/'.$value->id.'/',$value->faktur_file);
            }
        }
    }

    public function uploadStelSales2()
    {
        // dariTabel stels_sales_attachment
        $data = STELSalesAttach::all();
        foreach($data as $value){
            $this->upload('stel/'.$value->stel_sales_id.'/',$value->attachment);
        }
    }

    public function uploadStelAttach()
    {
        // dariTabel stels_sales_detail
        $data = STELSalesDetail::select('stels_sales_detail.id','stels_sales_detail.attachment')->where('stels_sales_detail.attachment', '!=', '')
            ->join('stels_sales','stels_sales.id', "=" ,'stels_sales_detail.stels_sales_id')
            ->get();
        foreach($data as $value){
            $this->upload('stelAttach/'.$value->id.'/',$value->attachment);
        }
    }

    public function uploadTempCompany()
    {
        $data = TempCompany::where('npwp_file', '!=', '')->orWhere('siup_file', '!=', '')->orWhere('qs_certificate_file', '!=', '')->get();
        foreach($data as $value){
            $this->upload('tempCompany/'.$value->company_id.'/'.$value->id.'/',$value->npwp_file);
            $this->upload('tempCompany/'.$value->company_id.'/'.$value->id.'/',$value->siup_file);
            $this->upload('tempCompany/'.$value->company_id.'/'.$value->id.'/',$value->qs_certificate_file);
        }
    }
    
    public function uploadUser()
    {
        $data = User::where('picture', '!=', '')->get();
        foreach($data as $value){
            $this->upload('user/'.$value->id.'/',$value->picture);
        }
    }

}
