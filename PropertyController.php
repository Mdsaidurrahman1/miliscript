<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Session;
use App\Property;
use App\SuperCategory;
use App\Category;
use App\SubCategory;
use App\ObjectCat;
use App\Lib\Repository;
use DB;


class PropertyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $mod;
    public function __construct(Property $link)
    {
        $this->mod = new Repository($link);
    }


    public function index()
    {
        $supcatpack = (new Repository(new SuperCategory))->getAll(); 
        $catpack  =  (new Repository(new Category))->getAll(); 
        $subcatpack  =  (new Repository(new SubCategory))->getAll(); 

        $pptPack= $this->mod->getAll();

        return view('property.property')
                            ->with('supcatpack',$supcatpack)
                            ->with('catpack',$catpack)
                            ->with('subcatpack',$subcatpack)
                            ->with('pptPack',$pptPack);

    }


    public function propertyInput(Request $request)
    {
        $this->validate($request, [
            'PropertyName' => 'required'
        ]);

        $property = DB::select('select * from properties where PropertyName = ? and SupCatId=? and CatId=? and SubCatId=? and ObjCatId=?',[$request->PropertyName,$request->SupCatId,$request->CatId,$request->SubCatId,$request->ObjCatId]);
        if($property == null)
        {
            $sitepost = $this->mod->store($request->only($this->mod->getModel()->fillable));
            return redirect()->route('property');
        }else
        {
            $foundmsg = "Property with category exist.";
             return redirect()->route('property')->with('foundmsg', $foundmsg);
        }
    }
        












    public function propertyinsert(Request $request)
    {
        $this->validate($request, [
            'PropertyName' => 'required',
            'PropertyCategory' => 'required'
        ]);

        $property = DB::select('select * from properties where PropertyName = ?',[$request->PropertyName]);
        if($property == null)
        {
            $property = $this->mod->store($request->only($this->mod->getModel()->fillable));
            return redirect()->route('objectdetail', ['id' => $request->objectid]);
        }else
        {
            $foundmsg = "Property Found";
             return redirect()->route('objectdetail', ['id' => $request->objectid])->with('foundmsg', $foundmsg);
        }


      

    }
        




    public function delete(Request $request)
    {
       $serviceDelete  =  $this->mod->getById($request->id);
       $serviceDelete->delete();
       return redirect('/categorysetup');
    }


    
}
