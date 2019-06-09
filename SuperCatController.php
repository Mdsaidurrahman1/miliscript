<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Session;
use App\SuperCategory;
use App\Lib\Repository;
use DB;


class SuperCatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $mod;
    public function __construct(SuperCategory $link)
    {
        $this->mod = new Repository($link);
    }


    public function index()
    {
        $supercatpack  =  $this->mod->getAll();
        return view('setup.supercatsetup')->with('supercatpack',$supercatpack);
    }

















    public function create(Request $request)
    {
        $this->validate($request, [
            'SuperCateName' => 'required'
        ]);

        $superCat = DB::select('select * from supercategories where SuperCateName = ?',[$request->SuperCateName]);
        if($superCat == null)
        {
           $sitepost = $this->mod->store($request->only($this->mod->getModel()->fillable));
           return redirect('/Supercatsetup');
        }else
        {
            $foundmsg = "Super Category exist.";
            return redirect('/Supercatsetup')->with('foundmsg', $foundmsg);
        }





    }







    public function delete(Request $request)
    {
       $serviceDelete  =  $this->mod->getById($request->id);
       $serviceDelete->delete();
       return redirect('/Supercatsetup');
    }


    
}
