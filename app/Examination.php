<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Examination extends Model
{      
    protected $table = 'examinations';
    public $incrementing = false;

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function examinationType()
    {
        return $this->belongsTo('App\ExaminationType');
    }

    public function examinationLab()
    {
        return $this->belongsTo('App\ExaminationLab');
    }

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function equipment()
    {
        return $this->hasMany('App\Equipment');
    }

    public function questioner()
    {
        return $this->hasMany('App\Questioner');
    }

    public function questionerdynamic()
    {
        return $this->hasMany('App\QuestionerDynamic');
    }

    public function examinationHistory()
    {
        return $this->hasMany('App\ExaminationHistory');
    }

    public function media()
    {
        return $this->hasMany('App\ExaminationAttach', 'examination_id')->orderBy('created_at', 'DESC');
    }

    public static function getUnpaidSpbByDate($date)
    {
        return DB::table('examinations')
            ->select(
                'users.name as customerName',
                'users.email as customerEmail',
                'examinations.spb_number as spbNumber',
                'examinations.VA_expired as expiredDate',
                'examinations.payment_method as paymentMethod',
                'examinations.price as price',
                'examinations.include_pph as includePPH'
                )
            ->join('companies', 'examinations.company_id', '=', 'companies.id')
            ->join('users', 'users.company_id', '=', 'companies.id')
            ->whereDate('spb.created_at', '=' ,$date)
            ->where('examinations.payment_status', '!=' , 1)
            ->where('examinations.VA_expired', '!=' , null)
            ->get()
        ;
    }
}
