<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Http\Requests;
use App\Chamber;
use App\Chamber_detail;
use Auth;

class ChamberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = "pengujian";
        return view('client.process.rent_chamber')
            ->with('page', $page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dates = (json_decode( $request->dates ?? ''));
        $currentUser = Auth::user();
        if (!$currentUser){ return redirect('login');}

        $rentChamber = [];
        foreach ($dates as $date){
            array_push( $rentChamber, [
                'date' => Carbon::createFromFormat('Ymd', $date)->format('Y-m-d'),
                'user_id' => $currentUser->id
            ]);
        }

        if (count($rentChamber)){
            Chamber::insert($rentChamber);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function testForm(request $request)
    {
        $dates = (json_decode( $request->dates));
        $currentUser = Auth::user();
        if (!$currentUser){ return redirect('login');}

        $chamber = new Chamber;
        $chamber->id = Uuid::uuid4();
        $chamber->user_id = $currentUser->id;
        $chamber->company_id = $currentUser->company_id;
        $chamber->invoice = $this->getNextInvoice();
        $chamber->start_date = $dates[0];
        $chamber->end_date = end($dates);
        $chamber->duration = count($dates);
        $chamber->created_by = $currentUser->id;
        $chamber->save();

        $chamberDetails = [];
        foreach ($dates as $date){
            $chamberDetail = new Chamber_detail;
            $chamberDetail->date = Carbon::createFromFormat('Ymd', $date)->format('Y-m-d');
            $chamberDetail->chamber_id = $chamber->id;
            $chamberDetail->save();
        }
        dd('ok');
    }


    private function getNextInvoice()
    {
        $record = 1;
        $year = date("Y");        
        $months = ['','I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $romanstMonth = $months[(int) date('m')];

        $lastChamber = Chamber::orderBy('created_at', 'desc')->first();
        if ( $lastChamber && $year == substr($lastChamber->invoice, -4) ){
            $record = (int) substr($lastChamber->invoice, 4,4)+1;
        }

        //format = CMB 0001/VII/2021
        return "CMB ".sprintf('%04d', $record)."/$romanstMonth/$year";
    }
}
