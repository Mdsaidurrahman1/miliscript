<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Session;
use App\Linkrefference;
use App\Lib\Repository;
use App\SuperCategory;
use App\Category;
use App\SubCategory;
use App\ObjectCat;


class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $mod;
    public function __construct(Linkrefference $link)
    {
        $this->mod = new Repository($link);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supcate = (new Repository(new SuperCategory))->getAll() ; 
        $cate = (new Repository(new Category))->getAll() ; 
        $subcate = (new Repository(new SubCategory))->getAll() ; 
        $objcate = (new Repository(new ObjectCat))->getAll() ;      
        return view('setup.setup')->with('supcate',$supcate)
        ->with('cate', $cate)
        ->with('subcate', $subcate)
        ->with('objcate',$objcate);
    }


    public function create(Request $request)
    {
        $this->validate($request, [
            'SiteName' => 'required',
            'SiteCategory' => 'required',
            'SiteType' => 'required',
            'SiteUrl' => 'required'
        ]);

       $sitepost = $this->mod->store($request->only($this->mod->getModel()->fillable));
       return redirect('/linklib');
    }



    public function delete(Request $request)
    {
       $serviceDelete  =  $this->mod->getById($request->id);
       $serviceDelete->delete();
       return redirect('/linklib');
    }


    
}
