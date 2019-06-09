<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\Work;
use Session;
use Redirect;
use PDF;


class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('checkauthencation');
    }

    public function report()
    {
        return view('report');
    }


    public function showreport()
    {
        
        $pdf = PDF::loadView('report');
        return $pdf->stream();
    }



}
