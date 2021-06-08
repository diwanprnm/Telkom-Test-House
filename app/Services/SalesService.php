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
    protected const SEARCH2 = 'search2';
    protected const SEARCH3 = 'search3';
    protected const AFTER_DATE = 'after_date';
    protected const BEFORE_DATE = 'before_date';
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
    protected const V1_INVOICE = 'v3/invoices/';
    private const STELS_SALES_ATTACHMENT = 'stels_sales_attachment';
    
    public function getData(Request $request)
    {
        $search = $request->input('search') ? $request->input('search') : NULL;
        $before = $request->input('before') ? $request->input('before') : NULL;
        $after = $request->input('after') ? $request->input('after') : NULL;
        $logService = new LogService();
        //query awal untuk sales controller
        $dataSales = $this->initialQuery(); 
        //inisial service sales dan queryFilter
        $queryFilter = new QueryFilter($request, $dataSales);
        //filter search if has input create log.
        $searchFiltered = $this->search($search, $before, $after, $dataSales);
        if ($searchFiltered[self::SEARCH]!=''){
            $queryFilter = new QueryFilter($request, $searchFiltered['dataSales']);
            $logService->createLog('Search Sales','Sales',json_encode(array(self::SEARCH=>$searchFiltered[self::SEARCH])));
        }

        $query = $queryFilter->getQuery()->where("payment_status", $status);

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

    public function getDataByStatus(Request $request, $status = 0)
    {
        switch ($status) {
            case 1:
                $search = $request->input('search2');
                $before = $request->input('before2');
                $after = $request->input('after2');
                break;
            case 3:
                $search = $request->input('search3');
                $before = $request->input('before3');
                $after = $request->input('after3');
                break;
            default:
                $search = $request->input('search');
                $before = $request->input('before');
                $after = $request->input('after');
                break;
        }
        $logService = new LogService();
        $dataSales = $this->initialQuery(); 
        $queryFilter = new QueryFilter($request, $dataSales);
        //filter search if has input create log.
        $searchFiltered = $this->search($search, $before, $after, $dataSales);
        if ($searchFiltered[self::SEARCH]!=''){
            $queryFilter = new QueryFilter($request, $searchFiltered['dataSales']);
            $logService->createLog('Search Sales','Sales',json_encode(array(self::SEARCH=>$searchFiltered[self::SEARCH])));
        }

        $query = $queryFilter->getQuery()->where("payment_status", $status);

        $queryFilter
            ->updateQuery($query)
            ->getSortedAndOrderedData('stels_sales.created_at','desc');

        return array(
            'data' => $queryFilter->getQuery(),
            $this::SEARCH => $search,
            'before' => $before,
            'after' => $after,
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

    public function search($search, $before, $after, $dataSales)
    {
        $search = trim(strip_tags($search));

        if($search){
            $dataSales = $dataSales->where(function($q) use ($search){
                $q->where('invoice','like','%'.$search.'%')
                    ->orWhere('companies.name', 'like', '%'.$search.'%')
                    ->orWhere('stels.name', 'like', '%'.$search.'%')
                    ->orWhere('stels.code', 'like', '%'.$search.'%')
                ;
            });
        }

        if ($before) {
            $dataSales = $dataSales->where(DB::raw('DATE(stels_sales.created_at)'), '<=', $before);
        }

        if ($after) {
            $dataSales = $dataSales->where(DB::raw('DATE(stels_sales.created_at)'), '>=', $after);
        }
        
        return array(
            'dataSales' => $dataSales,
            $this::SEARCH => $search,
            $this::BEFORE_DATE => $before,
            $this::AFTER_DATE => $after,
        );
    }

    public function saveFakturPajak($status_invoice, $invoice, $filename, $request, $client, $INVOICE_ID, $STELSales )
    {
        if($status_invoice == "approved"){
            $status_faktur = $invoice->data->status_faktur;
            if($status_faktur == "received"){
                /*SAVE FAKTUR PAJAK*/
                $name_file = 'faktur_stel_'.$filename.'.pdf';
                $path_file = "stel/".$request->input('id')."/";
                $response = $client->request('GET', self::V1_INVOICE.$INVOICE_ID.'/taxinvoice/pdf');
                $stream = (String)$response->getBody();

                $fileService = new FileService();
                $fileProperties = array(
                    'path' => $path_file,
                    'fileName' => $name_file
                );
                $isUploaded = $fileService->uploadFromStream($stream, $fileProperties);

                if($isUploaded){
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
        $status_invoice = $invoice->data->status_invoice;
        if($status_invoice == "approved"){
            $status_faktur = $invoice->data->status_faktur;
            if($status_faktur == "received"){
                /*SAVE KUITANSI*/
                $name_file = 'kuitansi_stel_'.$INVOICE_ID.'.pdf';
                $path_file = "stel/".$request->input('id')."/";
                $response = $client->request('GET', self::V1_INVOICE.$INVOICE_ID.'/exportpdf');
                $stream = (String)$response->getBody();
                
                $fileService = new FileService();
                $fileProperties = array(
                    'path' => $path_file,
                    'fileName' => $name_file
                );

                $isUploaded = $fileService->uploadFromStream($stream, $fileProperties);

                if($isUploaded){
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
                $STELSales->id_kuitansi = $file ? $file : '';
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
                $STELSales->faktur_file = $file ? $file : '';
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