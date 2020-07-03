<?php

namespace App\Services\Querys;

use Illuminate\Http\Request;
use App\Http\Requests;

class QueryFilter
{
    protected const BEFORE_DATE = 'before_date';
    protected const AFTER_DATE = 'after_date';
    protected const QUERY = 'query';
    protected const SEARCH = 'search';
    protected const COMPANY = 'company';
    protected const SPB_NUMBER = 'spb_number';
    protected const PAYMENT_STATUS = 'payment_status';
    protected const SORT_BY = 'sort_by';
    protected const SORT_TYPE = 'sort_type';

    public function search(Request $request, $query, $number)
    {
        $isNull = true;
        $request->has(self::SEARCH) ? $search = trim($request->input(self::SEARCH)) : $search = '';
        if (!$number) {
            return false;
        }

        if ($search != null){
            $query->where(function($qry) use($search){
                $qry->whereHas('device', function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhereHas(self::COMPANY, function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhere($number, 'like', '%'.strtolower($search).'%');
            });
            $isNull = false;
        }

        return array(
            self::QUERY => $query,
            self::SEARCH => $search,
            'isNull' => $isNull
        );
    }

    public function beforeDate(Request $request, $query, $date_variable)
    {
        $before = '';
        if (!$date_variable) {
            return false;
        }

        if ($request->has(self::BEFORE_DATE)){
            $query->where($date_variable, '<=', $request->get(self::BEFORE_DATE));
            $before = $request->get(self::BEFORE_DATE);
        }

        return array(
            self::QUERY => $query,
            'before' => $before
        );
    }

    public function afterDate(Request $request, $query, $date_variable)
    {
        $after = null;
        if (!$date_variable) {
            return false;
        }

        if ($request->has(self::AFTER_DATE)){
            $query->where($date_variable, '>=', $request->get(self::AFTER_DATE));
            $after = $request->get(self::AFTER_DATE);
        }

        return array(
            self::QUERY => $query,
            'after' => $after
        );
    }

    public function spb(Request $request, $query)
    {
        $filterSpb = '';

        if ($request->has('spb')){
            $filterSpb = $request->get('spb');
            if($request->input('spb') != 'all'){
                $query->where(self::SPB_NUMBER, $request->get('spb'));
            }
        }

        return array(
            self::QUERY => $query,
            'filterSpb' => $filterSpb
        );
    }

    public function spk(Request $request, $query)
    {
        $filterSpk = '';

        if ($request->has('spk')){
            $filterSpk = $request->get('spk');
            if($request->input('spk') != 'all'){
                $query->where('SPK_NUMBER', $request->get('spk'));
            }
        }

        return array(
            self::QUERY => $query,
            'filterSpk' => $filterSpk,
        );
    }

    public function type(Request $request, $query, $kind_of_type)
    {
        $type = '';
        if (!$kind_of_type) {
            return false;
        }

        if ($request->has('type')){
            $type = $request->get('type');
            if($request->input('type') != 'all'){
                $query->where($kind_of_type, $request->get('type'));
            }
        }

        return array(
            self::QUERY => $query,
            'type' => $type
        );
    }

    public function company(Request $request, $query, $company_variable)
    {
        $filterCompany = '';
        if (!$company_variable) {
            return false;
        }

        if ($request->has(self::COMPANY)){
            $filterCompany = $request->get(self::COMPANY);
            if($request->input(self::COMPANY) != 'all'){
                $query->whereHas(self::COMPANY, function ($q) use ($request){
                    return $q->where($company_variable, 'like', '%'.$request->get(self::COMPANY).'%');
                });
            }
        }

        return array(
            self::QUERY => $query,
            'filterCompany' => $filterCompany
        );
    }

    public function paymentStatus(Request $request, $query)
    {
        $filterPayment_status = '';

        if ($request->has(self::PAYMENT_STATUS)){
            $filterPayment_status = $request->get(self::PAYMENT_STATUS);
            if($request->input(self::PAYMENT_STATUS) != 'all'){
                $request->input(self::PAYMENT_STATUS) == '1' ? $query->where(self::PAYMENT_STATUS, '=', 1) : $query->where(self::PAYMENT_STATUS, '!=', 1);
            }
        }

        return array(
            self::QUERY => $query,
            'filterPayment_status' => $filterPayment_status
        );
    }

    public function paymentStatusAll(Request $request,$query)
    {
        $filterPayment_status = '';

        if ($request->has('payment_status')){
            $filterPayment_status = $request->get('payment_status');
            if($request->input('payment_status') != 'all'){
                $query->where('payment_status', $request->get('payment_status'));
            }
        }

        return array(
            self::QUERY => $query,
            'filterPayment_status' => $filterPayment_status
        );
    }

    public function lab(Request $request, $query, $lab_variable)
    {
        $lab='';
        if (!$lab_variable) {
            return false;
        }

        if ($request->has('lab')){
            $lab = $request->get('lab');
            if($request->input('lab') != 'all'){
                $query->where($lab_variable, $request->get('lab'));
            }
        }

        return array(
            self::QUERY => $query,
            'lab' => $lab
        );

    }

    public function getSortedAndOrderedData($request, $query, $sort_by = false)
    {
        $sort_type = 'desc';

        if ($request->has(self::SORT_BY)){
            $sort_by = $request->get(self::SORT_BY);
        }
        if ($request->has(self::SORT_TYPE)){
            $sort_type = $request->get(self::SORT_TYPE);
        }

        $data = $query->orderBy($sort_by, $sort_type);

        return array(
            'data' => $data,
            self::SORT_BY => $sort_by,
            self::SORT_TYPE=> $sort_type
        );
    }




}