<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\Work;
use App\Module;
use Session;
use Redirect;
use App\Lib\Repository;

class AdminController extends Controller
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


    public function admin()
    {
        
        $modulepack = (new Repository(new Module))->getAll() ; 
        return view('admin')->with('modulepack',$modulepack);
    }



    public function service()
    {
        $servicepack = Service::all();
        return view('admin.service')->with('servicepack',$servicepack);
    }

    

    public function work()
    {
        $workpack = Work::all();
        return view('admin.product')->with('workpack',$workpack);
        
    }

    public function news()
    {
        $message = Message::all();
        return view('admin.message')->with('message',$message);
    }

    public function content()
    {
        $message = Message::all()->where('Type','Notice');
        return view('admin.content')->with('message',$message);
    }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
  


}
