<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Session;
use App\Category;
use App\SuperCategory;
use DB;
use App\Lib\Repository;



class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $mod;
    public function __construct(Category $link)
    {
        $this->mod = new Repository($link);
    }




    public function createByAjax(Request $request)
    {
        return $request;
       // return response();
       //  $this->validate($request, [
       //      'CategoryName' => 'required',
       //      'SuperCateId' => 'required'
       //  ]);

       // $sitepost = $this->mod->store($request->only($this->mod->getModel()->fillable));
       // return redirect('/categorysetup');
    }













    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function ajaxcall(Request $request)
    {
       $category = $this->mod->getByKyeId('SuperCateId',$request->key);
       return response()->json($category);
    }



    public function index()
    {
        
        $supcatmod = new Repository(new SuperCategory); 
        $supcatpack = $supcatmod->getAll() ;
        $categorypack  =  $this->mod->getAll();
        return view('setup.categorysetup')->with('categorypack',$categorypack)->with('supcatpack',$supcatpack);
    }




    public function create(Request $request)
    {
        $this->validate($request, [
            'CategoryName' => 'required',
            'SuperCateId' => 'required'
        ]);

        $cat = DB::select('select * from categories where CategoryName = ? and SuperCateId = ?',[$request->CategoryName, $request->SuperCateId]);
        if($cat == null)
        {
             $sitepost = $this->mod->store($request->only($this->mod->getModel()->fillable));
             return redirect('/categorysetup');
        }else
        {
            $foundmsg = "Category exist.";
             return redirect('/categorysetup')->with('foundmsg', $foundmsg);
        }
    }



    public function delete(Request $request)
    {
       $serviceDelete  =  $this->mod->getById($request->id);
       $serviceDelete->delete();
       return redirect('/categorysetup');
    }


    
}
