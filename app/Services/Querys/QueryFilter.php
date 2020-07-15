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

    //chain 
    public $request;
    public $query;
    public $before;
    public $after;
    public $data;
    public $sort_by;
    public $sort_type;
    public $examination_type;
    public $examination_lab;

    public function __construct(Request $request, $query)
    {
        $this->request = $request;
        $this->query = $query;
    }

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

        if ($request->has(self::PAYMENT_STATUS)){
            $filterPayment_status = $request->get(self::PAYMENT_STATUS);
            if($request->input(self::PAYMENT_STATUS) != 'all'){
                $query->where(self::PAYMENT_STATUS, $request->get(self::PAYMENT_STATUS));
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

    //CHAINABLE FUNCTION, FROM NOW ON.
    public function beforeDate($date_variable)
    {
        if ($date_variable && $this->request->has(self::BEFORE_DATE)) {
            $this->query->where($date_variable, '<=', $this->request->get(self::BEFORE_DATE));
            $this->before = $this->request->get(self::BEFORE_DATE);
        }

        return $this;
    }

    public function afterDate($date_variable)
    {
        if ($date_variable && $this->request->has(self::AFTER_DATE)) {
            $this->query->where($date_variable, '>=', $this->request->get(self::AFTER_DATE));
            $this->after = $this->request->get(self::AFTER_DATE);
        }

        return $this;
    }

    public function getSortedAndOrderedData($sort_by = false, $sort_type = 'desc')
    {
        $this->sort_by = $sort_by;
        $this->sort_type = $sort_type;

        if ($this->request->has(self::SORT_BY)){
            $this->sort_by = $this->request->input(self::SORT_BY);
        }
        if ($this->request->has(self::SORT_TYPE)){
            $this->sort_type = $this->request->input(self::SORT_TYPE);
        }

        if ($this->sort_by){
            $this->query = $this->query->orderBy($this->sort_by, $this->sort_type);
        }
        
        return $this;
    }

    public function examination_type($requestName = 'type')
    {
        if ($this->request->has($requestName)){
            $this->examination_type = $this->request->get($requestName);
            if($this->request->input($requestName) != 'all'){
                $this->query->where('examination_type_id', $this->request->get($requestName));
            }
        }

        return $this;
    }

    public function examination_lab($requestName = 'lab')
    {
        if ($this->request->has($requestName)){
            $this->examination_lab = $this->request->get($requestName);
            if($this->request->input($requestName) != 'all'){
                $this->query->where('examination_lab_id', $this->request->get($requestName));
            }
        }

        return $this;
    }

    public function updateQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

}