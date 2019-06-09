<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Session;
use App\SuperCategory;
use App\Category;
use App\SubCategory;
use App\ObjectCat;
use DB;

use App\Lib\Repository;


class ObjectCatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $mod;
    public function __construct(ObjectCat $link)
    {
        $this->mod = new Repository($link);
    }


    public function ajaxcall(Request $request)
    {
       $category = $this->mod->getByKyeId('SubCatId',$request->key);
       return response()->json($category);
    }


    public function index()
    {
        $supcatpack = (new Repository(new SuperCategory))->getAll(); 
        $catpack  =  (new Repository(new Category))->getAll(); 
        $subcatpack  =  (new Repository(new SubCategory))->getAll(); 

        $objectpack= $this->mod->getAll();

        return view('setup.objectcatsetup')
                            ->with('supcatpack',$supcatpack)
                            ->with('catpack',$catpack)
                            ->with('subcatpack',$subcatpack)
                            ->with('objectpack',$objectpack);
    }


    public function create(Request $request)
    {

        //echo $request->subcategory;
        $this->validate($request, [
            'ObjectCatName' => 'required'
        ]);
        $request->merge(['SubCatId' => $request->subcategory]);


        $objcat = DB::select('select * from object_cats where ObjectCatName = ? and SubCatId = ? ',[$request->ObjectCatName, $request->SubCatId]);
        if($objcat == null)
        {
               $sitepost = $this->mod->store($request->only($this->mod->getModel()->fillable));
               return redirect('/Objectcatsetup');

        }else
        {
            $foundmsg = "Object Category exist.";
             return redirect('/Objectcatsetup')->with('foundmsg', $foundmsg);
        }



    }



    public function delete(Request $request)
    {
       $serviceDelete  =  $this->mod->getById($request->id);
       $serviceDelete->delete();
       return redirect('/Objectcatsetup');
    }


    
}
