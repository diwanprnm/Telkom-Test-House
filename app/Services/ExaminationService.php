<?php

namespace App\Services;

use Auth;
use App\Examination;
use App\User;
use App\Logs;
use App\LogsAdministrator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Facades\DB;

class ExaminationService
{

    public function getDetailDataFromOTR($client, $id = '', $spk_code = '')
    {
        $query_lab = "SELECT action_date FROM equipment_histories WHERE location = 3 AND examination_id = '".$id."' ORDER BY created_at DESC LIMIT 1";
		$data_lab = DB::select($query_lab);
		
		$query_gudang = "SELECT action_date FROM equipment_histories WHERE location = 2 AND examination_id = '".$id."' ORDER BY created_at DESC LIMIT 2";
		$data_gudang = DB::select($query_gudang);
		
		$res_exam_schedule = $client->get('spk/searchData?spkNumber='.$spk_code)->getBody();
		$exam_schedule = json_decode($res_exam_schedule);
		
		$res_exam_approve_date = $client->get('spk/searchHistoryData?spkNumber='.$spk_code)->getBody();
        $exam_approve_date = json_decode($res_exam_approve_date);
        
        return array($data_lab, $data_gudang, $exam_schedule, $exam_approve_date);
    }

}