<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use Response;
use Session; 


use App\STELSales;
use App\STELSalesDetail;
use App\Services\Logs\LogService;
use App\Services\Querys\QueryFilter;

class SalesService
{
    protected const ADMIN_SALES = '/admin/sales';
    protected const EDIT = '/edit';
    protected const ERROR = 'error';
    protected const FAKTUR_FILE = 'faktur_file';
    protected const KUITANSI_FILE = 'kuitansi_file';
    protected const MEDIA_STEL = '/media/stel/';
    protected const QUERY = 'query';
    protected const SEARCH = 'search';
    protected const STEL_FILE = 'stel_file';
    protected const STELS_SALES_DETAIL_ID = 'stels_sales_detail_id';

    
    public function getData(Request $request)
    {

        //inisial service sales dan queryFilter
        $queryFilter = new QueryFilter();

        //query awal untuk sales controller
        $dataSales = $this->initialQuery();       
        
        //filter search if has input create log.
        $searchFiltered = $this->search($request, $dataSales);
        if ($searchFiltered[self::SEARCH]!=''){
            LogService::createLog("Search Sales","Sales",array("search"=>$searchFiltered[self::SEARCH]));
        }
        $dataSales = $searchFiltered['dataSales'];
        
        //data filtered by paymet_status
        $paymentFiltered = $queryFilter->paymentStatusAll($request, $dataSales);
        $dataSales = $paymentFiltered[self::QUERY];

        //filter beforeDate
        $beforeFiltered = $queryFilter->beforeDate($request, $dataSales, DB::raw('DATE(stels_sales.created_at)'));
        $dataSales = $beforeFiltered[self::QUERY];

        //filter afterDate
        $afterFiltered = $queryFilter->afterDate($request, $dataSales, DB::raw('DATE(stels_sales.created_at)'));
        $dataSales = $afterFiltered[self::QUERY];

        //get the data and sort them
        return array(
            'data' => $queryFilter->getSortedAndOrderedData($request, $dataSales, 'created_at')['data'],
            $this::SEARCH => $searchFiltered[$this::SEARCH],
            'paymentStatus' => $paymentFiltered['filterPayment_status'],
            'before' => $beforeFiltered['before'],
            'after' => $afterFiltered['after'],
        );
    }

    public function initialQuery()
    {
        $select = array("stels_sales.id","stels_sales.created_at","stels_sales.invoice","stels_sales.payment_status","stels_sales.payment_method","stels_sales.total","stels_sales.cust_price_payment","companies.name as company_name",
        DB::raw('stels_sales.id as _id,
                (
                    SELECT GROUP_CONCAT(stels.name SEPARATOR ", ")
                    FROM
                        stels,
                        stels_sales_detail
                    WHERE
                        stels_sales_detail.stels_sales_id = _id
                    AND
                        stels_sales_detail.stels_id = stels.id
                ) as stel_name
                ,(
                    SELECT GROUP_CONCAT(stels.code SEPARATOR ", ")
                    FROM
                        stels,
                        stels_sales_detail
                    WHERE
                        stels_sales_detail.stels_sales_id = _id
                    AND
                        stels_sales_detail.stels_id = stels.id
                ) as stel_code')
        ); 

        return STELSales::select($select)->distinct()->whereNotNull('stels_sales.created_at')
            ->join("users","users.id","=","stels_sales.user_id")
            ->join("companies","users.company_id","=","companies.id")
            ->join("stels_sales_detail","stels_sales_detail.stels_sales_id","=","stels_sales.id")
            ->join("stels","stels.id","=","stels_sales_detail.stels_id");
    }

    public function search(Request $request, $dataSales)
    {
        $request->has(self::SEARCH) ? $search = trim($request->input(self::SEARCH)) : $search = '';

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
        if($status_invoice == "approved"){
            $status_faktur = $invoice->data->status_faktur;
            if($status_faktur == "received"){
                /*SAVE FAKTUR PAJAK*/
                $name_file = 'faktur_stel_'.$filename.'.pdf';

                $path_file = public_path().self::MEDIA_STEL.$request->input('id');
                if (!file_exists($path_file)) {
                    mkdir($path_file, 0775);
                }

                $response = $client->request('GET', 'v1/invoices/'.$INVOICE_ID.'/taxinvoice/pdf');
                $stream = (String)$response->getBody();

                if(file_put_contents($path_file.'/'.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment ".$stream)){
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

                $path_file = public_path().self::MEDIA_STEL.$request->input('id');
                if (!file_exists($path_file)) {
                    mkdir($path_file, 0775);
                }
                $response = $client->request('GET', 'v1/invoices/'.$INVOICE_ID.'/exportpdf');
                $stream = (String)$response->getBody();

                if(file_put_contents($path_file.'/'.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment ".$stream)){
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
        if ($request->hasFile(self::KUITANSI_FILE)) {
            $name_file = 'kuitansi_stel_'.$request->file(self::KUITANSI_FILE)->getClientOriginalName();
            $path_file = public_path().self::MEDIA_STEL.$STELSales->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::KUITANSI_FILE)->move($path_file,$name_file)){
                $STELSales->id_kuitansi = $name_file;					
            }else{
                Session::flash(self::ERROR, 'Save Receipt to directory failed');
                return redirect(self::ADMIN_SALES.'/'.$STELSales->id.self::EDIT);
            }
        }
    
        //Save Invoice files *************************************************************************
        if ($request->hasFile(self::FAKTUR_FILE)) {
            $name_file = 'faktur_stel_'.$request->file(self::FAKTUR_FILE)->getClientOriginalName();
            $path_file = public_path().self::MEDIA_STEL.$STELSales->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::FAKTUR_FILE)->move($path_file,$name_file)){
                $STELSales->faktur_file = $name_file;					
            }else{
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
        $attachment_count = 0;
        $success_count = 0;
        for($i=0;$i<count($request->input(self::STELS_SALES_DETAIL_ID));$i++){
            $attachment = $request->input('stels_sales_attachment')[$i];
            if(!$attachment){$attachment_count++;}
            if ($request->file(self::STEL_FILE)[$i]) {
                $name_file = 'stel_file_'.$request->file(self::STEL_FILE)[$i]->getClientOriginalName();
                $path_file = public_path().'/media/stelAttach/'.$request->input(self::STELS_SALES_DETAIL_ID)[$i];
                if (!file_exists($path_file)) {
                    mkdir($path_file, 0775);
                }
                if($request->file(self::STEL_FILE)[$i]->move($path_file,$name_file)){
                    $STELSalesDetail = STELSalesDetail::find($request->input(self::STELS_SALES_DETAIL_ID)[$i]);
                    $STELSalesDetail->attachment = $name_file;
                    $STELSalesDetail->save();
                    $notifUploadSTEL = 1;
                    $success_count++;

                    /*TPN api_upload*/
                    $data [] = [
                            'name' => "file",
                            'contents' => fopen($path_file.'/'.$name_file, 'r'),
                            'filename' => $request->file(self::STEL_FILE)[$i]->getClientOriginalName()
                        ];
                }else{
                    Session::flash(self::ERROR, 'Save STEL to directory failed');
                    return redirect(self::ADMIN_SALES.'/'.$STELSales->id.self::EDIT);
                }
            }
        }

        return array(
            'STELSalesDetail' => $STELSalesDetail,
            'attachment_count' => $attachment_count,
            'success_count' => $success_count,
            'notifUploadSTEL' => $notifUploadSTEL,
            'data' => $data,
        );
    } 








}