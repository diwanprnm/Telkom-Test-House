<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\STEL;
use App\STELSales;
use App\STELSalesAttach;
use App\STELSalesDetail;
use App\User;

use App\Services\Querys\QueryFilter;
use App\Services\Logs\LogService;
use App\Services\NotificationService;
use App\Services\SalesService;
use App\Services\FileService;

use Auth;
use Session; 
use Validator;
use Excel;
use Response;
use Storage;
use File;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Events\Notification;
use App\NotificationTable;

class SalesService
{
    protected const ADMIN_SALES = '/admin/sales';
    protected const AS_COMPANY_NAME = 'companies.name as company_name';
    protected const EDIT = '/edit';
    protected const ERROR = 'error';
    protected const FAKTUR_FILE = 'faktur_file';
    protected const KUITANSI_FILE = 'kuitansi_file';
    protected const MEDIA_STEL = '/media/stel/';
    protected const MINIO = 'minio';
    protected const QUERY = 'query';
    protected const SEARCH = 'search';
    protected const STEL_FILE = 'stel_file';
    protected const STELS_SALES_DETAIL_DOT_STELS_ID = 'stels_sales_detail.stels_id';
    protected const STELS_SALES_DETAIL_ID = 'stels_sales_detail_id';
    protected const STELS_SALES_DETAIL_STELS_ID = 'stels_sales_detail.stels_sales_id';
    protected const STELS_SALES_DOT_ID = 'stels_sales.id';
    protected const STELS_SALES_DOT_USER_ID = 'stels_sales.user_id';
    protected const STELS_SALES_DOT_PAYMENT_STATUS = 'stels_sales.payment_status';
    protected const STELS_SALES_INVOICE = 'stels_sales.invoice';
    protected const USER_COMPANIES_ID = 'users.company_id';
    protected const USER_ID = 'users.id';
    protected const V1_INVOICE = 'v1/invoices/';
    private const STELS_SALES_ATTACHMENT = 'stels_sales_attachment';
    
    public function getData(Request $request)
    {
        $logService = new LogService();
        //query awal untuk sales controller
        $dataSales = $this->initialQuery(); 
        //inisial service sales dan queryFilter
        $queryFilter = new QueryFilter($request, $dataSales);
        //filter search if has input create log.
        $searchFiltered = $this->search($request, $dataSales);
        if ($searchFiltered[self::SEARCH]!=''){
            $queryFilter = new QueryFilter($request, $searchFiltered['dataSales']);
            $logService->createLog('Search Sales','Sales',json_encode(array(self::SEARCH=>$searchFiltered[self::SEARCH])));
        }
        //filterquery
        $queryFilter
            ->paymentAllStatus()
            ->beforeDate(DB::raw('DATE(stels_sales.created_at)'))
            ->afterDate(DB::raw('DATE(stels_sales.created_at)'))
            ->getSortedAndOrderedData('stels_sales.created_at','desc')
        ;
        //get the data and sort them
        return array(
            'data' => $queryFilter->getQuery(),
            $this::SEARCH => $searchFiltered[$this::SEARCH],
            'paymentStatus' => $queryFilter->paymentStatus,
            'before' => $queryFilter->before,
            'after' => $queryFilter->after,
        );
    }

    public function initialQuery()
    {
        $select = array(self::STELS_SALES_DOT_ID,"stels_sales.created_at",self::STELS_SALES_INVOICE, self::STELS_SALES_DOT_PAYMENT_STATUS,"stels_sales.payment_method","stels_sales.VA_name","stels_sales.total","stels_sales.cust_price_payment",self::AS_COMPANY_NAME,
        DB::raw('stels_sales.id as _id,
                (
                    SELECT GROUP_CONCAT(stels.name , ",")
                    FROM
                        stels,
                        stels_sales_detail
                    WHERE
                        stels_sales_detail.stels_sales_id = stels_sales.id
                    AND
                        stels_sales_detail.stels_id = stels.id
                ) as stel_name
                ,(
                    SELECT GROUP_CONCAT(stels.code,", ")
                    FROM
                        stels,
                        stels_sales_detail
                    WHERE
                        stels_sales_detail.stels_sales_id = stels_sales.id
                    AND
                        stels_sales_detail.stels_id = stels.id
                ) as stel_code')
        ); 

        return STELSales::select($select)->distinct()->whereNotNull('stels_sales.created_at')
            ->join("users", self::USER_ID,"=", self::STELS_SALES_DOT_USER_ID)
            ->join("companies",self::USER_COMPANIES_ID,"=","companies.id")
            ->join("stels_sales_detail", self::STELS_SALES_DETAIL_STELS_ID,"=",self::STELS_SALES_DOT_ID)
            ->join("stels","stels.id","=",self::STELS_SALES_DETAIL_DOT_STELS_ID);
    }

    public function search(Request $request, $dataSales)
    {
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        if($search!=''){
            $dataSales = $dataSales->where('invoice','like','%'.$search.'%')
            ->orWhere('companies.name', 'like', '%'.$search.'%')
            ->orWhere('stels.name', 'like', '%'.$search.'%')
            ->orWhere('stels.code', 'like', '%'.$search.'%');
        }
        return array(
            'dataSales' => $dataSales,
            $this::SEARCH => $search,
        );
    }

    public function saveFakturPajak($status_invoice, $invoice, $filename, $request, $client, $INVOICE_ID, $STELSales )
    {
        $this->validate($request, [
            'id' => self::REQUIRED,
        ]);

        if($status_invoice == "approved"){
            $status_faktur = $invoice->data->status_faktur;
            if($status_faktur == "received"){
                /*SAVE FAKTUR PAJAK*/
                $name_file = 'faktur_stel_'.$filename.'.pdf';
                $path_file = storage_path('tmp/');
                if (!file_exists($path_file)) {
                    mkdir($path_file, 0775);
                }

                $response = $client->request('GET', self::V1_INVOICE.$INVOICE_ID.'/taxinvoice/pdf');
                $stream = (String)$response->getBody();

                if(file_put_contents($path_file.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment; ".$stream)){
                    $fileFaktur = Storage::disk('tmp')->get($name_file);
                    Storage::disk(self::MINIO)->put("stel/$request->input('id')/$name_file", $fileFaktur );
                    File::delete(storage_path("tmp/$name_file"));

                    $STELSales->faktur_file = $name_file;
                    $STELSales->save();
                    $result = "Faktur Pajak Berhasil Disimpan.";
                }else{
                    $result = "Gagal Menyimpan Faktur Pajak!";
                }
            }else{
                $result = $invoice->data->status_faktur;
            }
        }else{
            switch ($status_invoice) {
                case 'invoiced':
                    $result = "Faktur Pajak belum Tersedia, karena Invoice Baru Dibuat.";
                    break;
                
                case 'returned':
                    $result = $invoice->data->$status_invoice->message;
                    break;
                
                default:
                $result = "Faktur Pajak belum Tersedia. Invoice sudah dikirim ke DJP.";
                    break;
            }
        }

        return $result;
    }

    public function saveKuitansi($invoice, $INVOICE_ID, $request, $client, $STELSales)
    {
        $this->validate($request, [
            'id' => self::REQUIRED,
        ]);

        $status_invoice = $invoice->data->status_invoice;
        if($status_invoice == "approved"){
            $status_faktur = $invoice->data->status_faktur;
            if($status_faktur == "received"){
                /*SAVE KUITANSI*/
                $name_file = 'kuitansi_stel_'.$INVOICE_ID.'.pdf';
                $path_file = storage_path('tmp/');
                if (!file_exists($path_file)) {
                    mkdir($path_file, 0775);
                }
                $response = $client->request('GET', self::V1_INVOICE.$INVOICE_ID.'/exportpdf');
                $stream = (String)$response->getBody();

                if(file_put_contents($path_file.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment; ".$stream)){
                    $fileKuitansi = Storage::disk('tmp')->get($name_file);
                    Storage::disk(self::MINIO)->put("stel/$request->input('id')/$name_file", $fileKuitansi );
                    File::delete(storage_path("tmp/$name_file"));

                    $STELSales->id_kuitansi = $name_file;
                    $STELSales->save();
                    $result = "Kuitansi Berhasil Disimpan.";
                }else{
                    $result = "Gagal Menyimpan Kuitansi!";
                }
            }else{
                return $invoice->data->status_faktur;
            }
        }else{
            switch ($status_invoice) {
                case 'invoiced':
                    $result = "Invoice Baru Dibuat.";
                    break;
                
                case 'returned':
                    $result = $invoice->data->$status_invoice->message;
                    break;
                
                default:
                    $result = "Invoice sudah dikirim ke DJP.";
                    break;
            }
        }

        return $result;
    }


    //Save Receipt files *************************************************************************
    public function saveReceiptAndInvoiceFiles($request, $STELSales)
    {
        //Save Kuitansi files *************************************************************************
        if ($request->hasFile(self::KUITANSI_FILE)) {
            try {
                $fileService = new FileService();  
                $file = $fileService->uploadFile($request->file(self::KUITANSI_FILE), 'kuitansi_stel_', "/".self::MEDIA_STEL.$STELSales->id."/");  
                $STELSales->id_kuitansi = $file;
            } catch (\Exception $e) {
                Session::flash(self::ERROR, 'Save Receipt to directory failed');
                return redirect(self::ADMIN_SALES.'/'.$STELSales->id.self::EDIT);
            }
        }
    
        //Save Invoice files *************************************************************************
        if ($request->hasFile(self::FAKTUR_FILE)) {
            try {
                $fileService = new FileService();  
                $file = $fileService->uploadFile($request->file(self::FAKTUR_FILE), 'faktur_stel_', "/".self::MEDIA_STEL.$STELSales->id."/");  
                $STELSales->faktur_file = $file;
            } catch (\Exception $e) {
                Session::flash(self::ERROR, 'Save Invoice to directory failed');
                return redirect(self::ADMIN_SALES.'/'.$STELSales->id.self::EDIT);
            }
        }

        return array(
            'STELSales' => $STELSales,
        );
    }

    public function saveSTELFiles($request, $STELSales, $data)
    {
        
        $notifUploadSTEL = null;
        $attachment_count = 0;
        $success_count = 0;
        for($i=0;$i<count((array)$request->input(self::STELS_SALES_DETAIL_ID));$i++){
            $STELSalesDetail = STELSalesDetail::find($request->input(self::STELS_SALES_DETAIL_ID)[$i]);
            $attachment = $request->input(self::STELS_SALES_ATTACHMENT)[$i];
            if(!$attachment){$attachment_count++;}
            if (@$request->file(self::STEL_FILE)[$i]) {

                $file = $request->file(self::STEL_FILE)[$i];
                $name_file = 'stel_file_'.$request->file(self::STEL_FILE)[$i]->getClientOriginalName();

                $is_uploaded = Storage::disk(self::MINIO)->put("stelAttach/".$STELSalesDetail->id."/$name_file", file_get_contents($file));
                $path_file = Storage::disk(self::MINIO)->url("stelAttach/".$STELSalesDetail->id."/$name_file");

                if($is_uploaded){
                    $STELSalesDetail->attachment = $name_file;
                    $STELSalesDetail->save();
                    $notifUploadSTEL = 1;
                    $success_count++;

                    /*TPN api_upload*/
                    $data [] = [
                            'name' => "file",
                            'contents' => fopen($path_file, 'r'),
                            'filename' => $request->file(self::STEL_FILE)[$i]->getClientOriginalName()
                        ];
                }else{
                    Session::flash(self::ERROR, 'Save STEL to directory failed');
                    return redirect(self::ADMIN_SALES.'/'.$STELSales->id.self::EDIT);
                }
            }
        }

        return array(
            'attachment_count' => $attachment_count,
            'success_count' => $success_count,
            'notifUploadSTEL' => $notifUploadSTEL,
            'data' => $data,
        );
    } 
}