<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class ChamberAdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        //initial var
        $paginate = 10;
        $search = trim(strip_tags($request->input('search','')));
        $dataNotFound='';
        $data = (object) [];

        
        return view('admin.chamber.index')->with('data', $data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

}
