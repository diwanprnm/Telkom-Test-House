<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Approval;
use App\Examination;

class AuthentikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $approval = Approval::where('id', $id)->with('approveBy')->with('approveBy.user')->with('approveBy.user.role')->with('authentikasi')->first();
        $examination = Examination::where('device_id', $approval->reference_id)->with('device')->with('company')->first();
        $data['name'] = $approval->authentikasi->name;
        $data['attachment'] = $approval->attachment;
        $data['document_code'] = $examination->device->cert_number;
        $data['company_name'] = $examination->company->name;
        $data['device_name'] = $examination->device->name;
        $data['mark'] = $examination->device->mark;
        $data['model'] = $examination->device->model;
        $data['capacity'] = $examination->device->capacity;
        $data['serial_number'] = $examination->device->serial_number;
        $data['valid_thru'] = $examination->device->valid_thru;
        $data['approveBy'] = $approval->approveBy;
        return view($approval->authentikasi->dir_name)->with('data', $data);
    }
}
