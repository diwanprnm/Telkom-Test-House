<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;

use App\Logs;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SPKController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();

        if ($currentUser){
            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));
            
            if ($search != null){
                $client = new Client([
					'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
					// Base URI is used with relative requests
					// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					'base_uri' => config("app.url_api_bsp"),
					// You can set any number of default request options.
					'timeout'  => 60.0,
				]);
				
				// $res_exam_schedule = $client->post('notification/notifToTE?lab='.$exam->examinationLab->lab_code)->getBody();
				$res_exam_schedule = $client->get('spk/searchData?find='.$search)->getBody();
				$exam_schedule = json_decode($res_exam_schedule);
            }else{
                $client = new Client([
					'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
					// Base URI is used with relative requests
					// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					'base_uri' => config("app.url_api_bsp"),
					// You can set any number of default request options.
					'timeout'  => 60.0,
				]);
				
				// $res_exam_schedule = $client->post('notification/notifToTE?lab='.$exam->examinationLab->lab_code)->getBody();
				$res_exam_schedule = $client->get('spk/searchData')->getBody();
				$exam_schedule = json_decode($res_exam_schedule);
            }
            
            if (count($exam_schedule) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.spk.index')
                ->with('message', $message)
                ->with('data', $exam_schedule)
                ->with('search', $search);
        }
    }

    public function show($id)
    {
		$client = new Client([
			'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
			// Base URI is used with relative requests
			// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
			'base_uri' => config("app.url_api_bsp"),
			// You can set any number of default request options.
			'timeout'  => 60.0,
		]);
		
		// $res_exam_schedule = $client->post('notification/notifToTE?lab='.$exam->examinationLab->lab_code)->getBody();
		$res_exam_approve_date = $client->get('spk/searchHistoryData?id='.$id)->getBody();
		$exam_approve_date = json_decode($res_exam_approve_date);
		
		return view('admin.spk.show')
                ->with('data', $exam_approve_date);
    }
}
