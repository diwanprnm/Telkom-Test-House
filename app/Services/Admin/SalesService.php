<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;


use App\STELSales;
use App\Services\Logs\LogService;

use App\Services\Querys\QueryFilter;

class SalesService
{
    protected const SEARCH = 'search';
    
    public function getData(Request $request)
    {

        //inisial service sales dan queryFilter
        $queryFilter = new QueryFilter();

        //query awal untuk sales controller
        $dataSales = $this->initialQuery();       
        
        //filter search if has input create log.
        $searchFiltered = $this->search($request, $dataSales);
        if ($searchFiltered['search']!=''){
            LogService::createLog("Search Sales","Sales",array("search"=>$search));
        }
        $dataSales = $searchFiltered['dataSales'];
        
        //data filtered by paymet_status
        $paymentFiltered = $queryFilter->paymentStatusAll($request, $dataSales);
        $dataSales = $paymentFiltered['query'];

        //filter beforeDate
        $beforeFiltered = $queryFilter->beforeDate($request, $dataSales, DB::raw('DATE(stels_sales.created_at)'));
        $dataSales = $beforeFiltered['query'];

        //filter afterDate
        $afterFiltered = $queryFilter->afterDate($request, $dataSales, DB::raw('DATE(stels_sales.created_at)'));
        $dataSales = $afterFiltered['query'];

        //get the data and sort them
        return array(
            'data' => $queryFilter->getSortedAndOrderedData($request, $dataSales, 'created_at')['data'],
            'search' => $searchFiltered['search'],
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
            'search' => $search,
        );
        
    }







}