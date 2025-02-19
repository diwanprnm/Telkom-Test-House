<?php

namespace App\Services\Querys;

use Illuminate\Http\Request;
use App\Http\Requests;

class QueryFilter
{
    protected const AFTER_DATE = 'after_date';
    protected const BEFORE_DATE = 'before_date';
    protected const COMPANY = 'company';
    protected const QUERY = 'query';
    protected const PAYMENT_STATUS = 'payment_status';
    protected const SEARCH = 'search';
    protected const SORT_BY = 'sort_by';
    protected const SORT_TYPE = 'sort_type';
    protected const SPB_NUMBER = 'spb_number';

    //chain 
    public $request;
    public $query;
    public $before = '';
    public $after = '';
    public $data = '';
    public $sort_by = '';
    public $sort_type = '';
    public $spbNumber = '';
    public $spkNumber = '';
    public $status = '';
    public $testingType = '';
    public $examination_type = '';
    public $examination_lab = '';
    public $labCode = '';
    public $noGudang = '';
    public $companyName = '';
    public $paymentStatus = '';
    public $paymentAllStatus = '';

    public function __construct(Request $request, $query)
    {
        $this->request = $request;
        $this->query = $query;
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

    public function testingType($requestName = 'type', $searchName='TESTING_TYPE')
    {
        if ($this->request->has($requestName)){
            $this->testingType = $this->request->get($requestName);
            if($this->request->input($requestName) != 'all'){
                $this->query->where($searchName, 'like', '%'.$this->request->get($requestName).'%');
            }
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

    public function labCode($requestName = 'lab', $searchName='tb_m_spk.LAB_CODE')
    {
        if ($this->request->has($requestName)){
            $this->labCode = $this->request->get($requestName);
            if($this->request->input($requestName) != 'all'){
                $this->query->where($searchName, $this->request->get($requestName));
            }
        }
        return $this;
    }

    public function noGudang($requestName = 'nogudang', $searchName='no'){

        if ($this->request->has($requestName)){
            $this->noGudang = $this->request->get($requestName);
            if($this->request->input($requestName) != 'all'){
                $this->query->where($searchName, $this->request->get($requestName));
            }
        }
        return $this;
    }

    public function companyName($requestName = 'company', $searchName='companies.name'){

        if ($this->request->has($requestName)){
            $this->companyName = $this->request->get($requestName);
            if($this->request->input($requestName) != 'all'){
                $this->query->where($searchName, $this->request->get($requestName));
            }
        }
        return $this;
    }

    public function paymentStatus($requestName = self::PAYMENT_STATUS, $searchName=self::PAYMENT_STATUS)
    {
        if ($this->request->has($requestName)){
            $this->paymentStatus = $this->request->get($requestName);
            if($this->request->input($requestName) != 'all'){
                $this->request->input($requestName) == '1' ? $this->query->where($searchName, '=', 1) : $this->query->where($searchName, '!=', 1);
            }
        }
        return $this;
    }

    public function paymentAllStatus($requestName = self::PAYMENT_STATUS, $searchName=self::PAYMENT_STATUS)
    {
        if ($this->request->has($requestName)){
            $this->paymentAllStatus = $this->request->get($requestName);
            if($this->request->input($requestName) != 'all'){
                $this->query->where($searchName, $this->request->get($requestName));
            }
        }
        return $this;
    }

    public function spbNumber($requestName = 'spb', $searchName='spb_number')
    {
        if ($this->request->has($requestName )){
            $this->spbNumber = $this->request->get($requestName );
            if($this->request->input($requestName ) != 'all'){
                $this->query->where($searchName, $this->request->get($requestName ));
            }
        }
        return $this;
    }

    public function spkNumber($requestName = 'spk', $searchName='SPK_NUMBER')
    {
        if ($this->request->has($requestName )){
            $this->spkNumber = $this->request->get($requestName );
            if($this->request->input($requestName ) != 'all'){
                $this->query->where($searchName, $this->request->get($requestName ));
            }
        }
        return $this;
    }

    public function status($requestName = 'status', $searchName='status')
    {
        if ($this->request->has($requestName )){
            $this->status = $this->request->get($requestName );
            if($this->request->input($requestName ) != 'all'){
                $this->query->where($searchName, $this->request->get($requestName ));
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