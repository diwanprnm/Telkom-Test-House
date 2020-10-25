<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class SPB extends Model
{
    protected $table = 'spb';

    public function examination()
    {
        return $this->belongsTo('App\Examination', 'examination_id');
    }

    public function user()
    {
        return $this->hasManyThrough('App\User', 'App\Examination', 'user_id', 'examination_id');
    }

    public static function getSPBFromDate($date)
    {
        return DB::table('spb')
            ->select(
                'users.name as customerName',
                'examinations.spb_number as spbNumber',
                'spb.created_at as createdAt',
                'examinations.payment_method as paymentMethod',
                'examinations.price as price',
                'examinations.include_pph as includePPH'
                )
            ->join('examinations', 'examinations.id', '=', 'spb.examination_id')
            ->join('companies', 'examinations.company_id', '=', 'companies.id')
            ->join('users', 'users.company_id', '=', 'companies.id')
            ->whereDate('spb.created_at', '=' ,$date)
            ->get()
        ;
    }
}
