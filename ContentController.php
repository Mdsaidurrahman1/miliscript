<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use Session;
use Redirect;

class ContentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servicepack = Service::all();
        return view('service')->with('servicepack',$servicepack);
    }





    public function insertvideo( Request $request)
    {
        $this->validate($request, [
            'videoclip' => 'required'
        ]);


      $file = $request->file('videoclip');
      $photoTitle = $file->getClientOriginalName();
      $fileExtention = $file->getClientOriginalExtension();
      if($fileExtention!='mp4' and $fileExtention!='3gp' and $fileExtention!='avi' )
      {
        Session::flash('message', "Only mp4 or 3gp or avi are allow to upload");
          return Redirect::back();
      }

      //Move Uploaded File
      $destinationPath = 'uploadedloc';
      $file->move($destinationPath,$file->getClientOriginalName());
      $fileUrl = $destinationPath.'/'.$photoTitle;

        $service = new Service;
        $service->Service_name = $request->servicename;
        $service->Price = $request->serviceprice;
        $service->Service_desc = $request->servicedesc;
        $service->service_picture = $fileUrl;
        $service->save();

        return redirect('/adminservice');
    }

    public function delete(Request $request)
    {
       $serviceDelete = Service::find($request->id);
       $serviceDelete->delete();
       return redirect('/adminservice');
    }


    
}
