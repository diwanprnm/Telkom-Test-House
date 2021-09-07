<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TestController extends Controller
{
    //todo daniel delete (CLASS DELETE)
    
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $path = app_path('http/Controllers/Services/PDF/images/telkom-logo-square.jpg');
        $images = QrCode::format('png')->size(500)->merge('/app/Services/PDF/images/telkom-logo-square.png')->generate('google.com');
        return response($images, 200)->header('Content-Type', 'image/png');
    }
}
