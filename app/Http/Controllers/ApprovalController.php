<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;

use Auth;
use Session;

use App\Approval;
use App\ApproveBy;
use App\AuthentikasiEditor;

use App\Examination;
use App\ExaminationType;
use App\ExaminationLab;
use App\Company;
use App\Logs;
use App\Services\Logs\LogService;
use App\Services\Querys\QueryFilter;

use Storage;
use File;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ApprovalController extends Controller
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
        $message = null;
        $paginate = 10;
        $search = trim(strip_tags($request->input('search','')));
        // $before = null;
        // $after = null;
        // $type = '';
        // $filterCompany = '';
        // $lab = '';
        // $sort_by = 'questioner_date';
        // $sort_type = 'desc';
        $user_id = Auth::user()->id;
        // $query = Approval::with('approveBy')
        //     ->with('authentikasi')
        //     ->where(function($qry) use($user_id){
        //         $qry->whereHas('approveBy', function ($q) use ($user_id){
        //             return $q->where('user_id', $user_id);
        //         });
        //     })
        // ;
        $query = ApproveBy::with('approval')
            ->with('approval.authentikasi')
            ->with('approval.approveBy')
            ->where('user_id', $user_id)
        ;
        $logService = new LogService();
        
        if ($search){
            $query->whereHas('approval.authentikasi', function ($q) use ($search) {
                $q->where('name', 'like', '%'.strtolower($search).'%');
            })
            // ->orWhereHas('approval.authentikasi', function ($q) use ($search) {
            //     $q->orWhere('name', 'like', '%'.strtolower($search).'%');
            // })
            ;
            $logService->createLog('search', 'Approval', json_encode(array('search' => $search)) );
        }

        $data = $query->orderBy('created_at')->paginate($paginate);
        if (count($data) == 0){
            $message = 'Data not found';
        }
        
        return view('admin.approval.index')
            ->with('message', $message)
            ->with('data', $data)
            ->with('search', $search)
            // ->with('before_date', $before)
            // ->with('after_date', $after)
            // ->with('type', $examType)
            // ->with('filterType', $type)
            // ->with('company', $companies)
            // ->with('filterCompany', $filterCompany)
            // ->with('lab', $examLab)
            // ->with('filterLab', $lab)
            // ->with(self::SORT_BY, $sort_by)
            // ->with(self::SORT_TYPE, $sort_type)
        ;
    }

}