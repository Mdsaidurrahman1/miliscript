<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Session;
use App\SuperCategory;
use App\SubCategory;
use DB;

use App\Lib\Repository;


class SubCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $mod;
    public function __construct(SubCategory $link)
    {
        $this->mod = new Repository($link);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function ajaxcall(Request $request)
    {
       $category = $this->mod->getByKyeId('CategoryId',$request->key);
       return response()->json($category);
    }


    
    public function index()
    {
        $supcatmod = new Repository(new SuperCategory); 
        $supcatpack = $supcatmod->getAll() ;
        $subcatpack  =  $this->mod->getAll();
        return view('setup.subcategorysetup')->with('subcatpack',$subcatpack)->with('supcatpack',$supcatpack);
    }


    public function create(Request $request)
    {
        $this->validate($request, [
            'SubCatName' => 'required',
            'CategoryId' => 'required'
    ]);

        $subcat = DB::select('select * from subcategories where SubCatName = ? and CategoryId = ? ',[$request->SubCatName, $request->CategoryId]);
        if($subcat == null)
        {
               $sitepost = $this->mod->store($request->only($this->mod->getModel()->fillable));
               return redirect('/subcategorysetup');
        }else
        {
            $foundmsg = "Sub Category exist.";
            return redirect('/subcategorysetup')->with('foundmsg', $foundmsg);
        }


    }



    public function delete(Request $request)
    {
       $serviceDelete  =  $this->mod->getById($request->id);
       $serviceDelete->delete();
       return redirect('/subcategorysetup');
    }


    
}
