<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Session;
use App\Linkrefference;
use App\SuperCategory;
use App\SubCategory;
use App\ObjectReference;
use App\Category;
use App\ObjectCat;
use App\Property;
use App\ActionLib;
use App\ObjectEn;
use DB;
use App\Lib\Repository;


class ObjectController extends Controller
{

    protected $mod;
    public function __construct(ObjectEn $link)
    {
        $this->mod = new Repository($link);
    }



    public function index()
    {

        $catmod = new Repository(new SuperCategory); 
        $catpack = $catmod->getAll() ;

        $objpack  =  $this->mod->getAll();
        return view('object.objectlibrary')->with('objpack',$objpack)->with('catpack', $catpack);
    }


        // echo $request->SiteName;
        // echo $request->SiteUrl;
        // echo $request->SiteDesc;
        // echo $request->supercategory;
        // echo $request->category;
        // echo $request->subcategory;




    public function create(Request $request)
    {

        $this->validate($request, [
            'ObjectName' => 'required',
            'supercategory' => 'required',
            'scategory' => 'required',
            'subcategory' => 'required',
            'objcategory' =>'required'
        ]);

       $supercat = ((new Repository(new SuperCategory))->getById($request->supercategory))->only('SuperCateName'); 
       $cat = ((new Repository(new Category))->getById($request->scategory))->only('CategoryName'); 
       $subcat = ((new Repository(new SubCategory))->getById($request->subcategory))->only('SubCatName');
       $objcat = ((new Repository(new ObjectCat))->getById($request->objcategory))->only('ObjectCatName');
    
       $ObjCatDetail = $supercat['SuperCateName'].'/'.$cat['CategoryName'].'/'.$subcat['SubCatName'].'/'.$objcat['ObjectCatName'];

        $request->merge(['ObjectType' =>  $ObjCatDetail]);
        $request->merge(['ObjectCateId' => $request->objcategory]);


       $request->merge(['SupCatId' =>  $request->supercategory]);
       $request->merge(['CatId' =>  $request->scategory]);
       $request->merge(['SubCatId' =>  $request->subcategory]);
       $request->merge(['ObjectCateId' =>  $request->objcategory]);
        
       $sitepost = $this->mod->store($request->only($this->mod->getModel()->fillable));
       return redirect('/object');
    }



    public function detail(Request $request)
    {
        $actionset = (new Repository(new ActionLib))->getByKyeId('ObjectId',$request->id);
        $objRef = (new Repository(new ObjectReference))->getByKyeId('ObjectId',$request->id);
        $object =  $this->mod->getById($request->id);

        $SupCatId = $object->SupCatId;
        $CatId = $object->CatId;
        $SubCatId = $object->SubCatId;
        $ObjCatId = $object->ObjectCateId;
        $property = DB::select('select * from properties where SupCatId = ? and CatId=? and SubCatId = ? and ObjCatId=?',[$SupCatId, $CatId,$SubCatId,$ObjCatId]);

        return view('object.objectdetail')
        ->with('object',$object)
        ->with('property',$property)
        ->with('objRef',$objRef)
        ->with('actionset',$actionset);
    }



    public function pptinsert(Request $request)
    {
        $this->validate($request, [
            'PropertyName' => 'required',
            'PropertyCategory' => 'required'
        ]);

        $property = DB::select('select * from properties where PropertyName = ? and PropertyCategory=?',[$request->PropertyName,$request->PropertyCategory]);
        if($property == null)
        {
            DB::select('Insert Into properties (PropertyName,PropertyCategory) Values (?,?)',[$request->PropertyName,$request->PropertyCategory]);
            return redirect()->route('objectdetail', ['id' => $request->id]);
        }else
        {
            $foundmsg = "Property Found";
             return redirect()->route('objectdetail', ['id' => $request->id])->with('foundmsg', $foundmsg);
        }

    }
        












    public function delete(Request $request)
    {
       $serviceDelete  =  $this->mod->getById($request->id);
       $serviceDelete->delete();
       return redirect('/object');
    }



    
}
