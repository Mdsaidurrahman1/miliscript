<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Session;
use App\Linkrefference;
use App\SuperCategory;
use App\SubCategory;
use App\ObjectCat;
use App\Property;
use App\Category;
use App\LinkDetail;
use DB;
use App\Lib\Repository;


class LinkController extends Controller
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





    public function linkDetail(Request $request)
    {
        $link  =  $this->mod->getById($request->id);
        $linkdata = ((new Repository(new LinkDetail))->getByKyeId('linkId',$request->id));
        $supcat = ((new Repository(new SuperCategory))->getAll()); 

        $SupCatId = $link->SupCatId;
        $CatId = $link->CatId;
        $SubCatId = $link->SubCatId;
        $ObjCatId = $link->ObjCatId;
        $property = DB::select('select * from properties where SupCatId = ? and CatId=? and SubCatId = ? and ObjCatId=?',[$SupCatId, $CatId,$SubCatId,$ObjCatId]);
        //$property = ((new Repository(new Property))->getAll());      

        return view('link.linkdetail')
        ->with('link', $link)
        ->with('supcat',$supcat)
        ->with('property',$property)
        ->with('linkdata',$linkdata);
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
            return redirect()->route('linkdetail', ['id' => $request->id]);
        }else
        {
            $foundmsg = "Property Found";
             return redirect()->route('linkdetail', ['id' => $request->id])->with('foundmsg', $foundmsg);
        }

    }
        






    public function index()
    {

        $catmod = new Repository(new SuperCategory); 
        $catpack = $catmod->getAll() ;

        $linkpack  =  $this->mod->getAll();
        return view('link.linklibrary')->with('linkpack',$linkpack)->with('catpack', $catpack);
    }


    public function webdirectory()
    {
                $catmod = new Repository(new SuperCategory); 
        $catpack = $catmod->getAll() ;

        $linkpack  =  $this->mod->getAll();

        return view('link.linkdir')->with('linkpack',$linkpack)->with('catpack', $catpack);
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
            'SiteName' => 'required',
            'SiteUrl' => 'required',
            'supercategory' => 'required',
            'scategory' => 'required',
            'subcategory' => 'required'
        ]);

        $supercat = ((new Repository(new SuperCategory))->getById($request->supercategory))->only('SuperCateName'); 
        $cat = ((new Repository(new Category))->getById($request->scategory))->only('CategoryName'); 
        $subcat = ((new Repository(new SubCategory))->getById($request->subcategory))->only('SubCatName');
        
        $objcat = ((new Repository(new ObjectCat))->getById($request->ObjCatId))->only('ObjectCatName');
        $type = $supercat['SuperCateName'].'/'.$cat['CategoryName'].'/'.$subcat['SubCatName'].'/'.$objcat['ObjectCatName'];
                $request->merge(['SupCatId' => $request->supercategory]);
                $request->merge(['CatId' => $request->scategory]);
                $request->merge(['SubCatId' => $request->subcategory]);

        $request->merge(['SiteType' => $type]);
        $request->merge(['SiteCategory' => $subcat['SubCatName']]);
        
       $sitepost = $this->mod->store($request->only($this->mod->getModel()->fillable));
       return redirect('/linklib');
    }





    public function delete(Request $request)
    {
       $serviceDelete  =  $this->mod->getById($request->id);
       $serviceDelete->delete();
       return redirect('/linklib');
    }



    public function ajaxcall(Request $request)
    {
        $catmod = new Repository(new SubCategory); 
        $category = $catmod->getByKyeId('CategoryId',$request->key);

       return response()->json($category);
    }
    
}
