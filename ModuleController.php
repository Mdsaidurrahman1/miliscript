<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Session;
use App\SuperCategory;
use App\Category;
use App\SubCategory;
use App\ObjectCat;
use App\Module;

use App\Lib\Repository;


class ModuleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $mod;
    public function __construct(Module $link)
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
        $modulepack= $this->mod->getAll();
        return view('admin.module')->with('modulepack',$modulepack);
    }


    public function create(Request $request)
    {

        $this->validate($request, [
            'ModuleName' => 'required'
        ]);

        
       $sitepost = $this->mod->store($request->only($this->mod->getModel()->fillable));
       return redirect('/Objectcatsetup');
    }



    public function delete(Request $request)
    {
       $serviceDelete  =  $this->mod->getById($request->id);
       $serviceDelete->delete();
       return redirect('/Objectcatsetup');
    }


    
}
