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

    public function history_uf()
    {
        return $this->hasMany('App\HistoryUF', 'examination_id')->where('function_test_TE', 2)->orderBy('created_at', 'ASC');
    }

    public function examinationCancel()
    {
        return $this->hasMany('App\ExaminationCancel');
    }
}
