<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\ObjectEn;
use App\SubCategory;
use App\Lib\Repository;
use App\Linkrefference;
use Session;
use Redirect;

class SearchController extends Controller
{

    public function __construct()
    {
        //$this->middleware('checkauthencation');
    }

//______________________ Methods to handle Setup module requests




//________________________ Methods to handle Object Module requests

    public function getObjBySupCat (Request $request)
    {
      $obj = (new Repository(new Linkrefference))->getByKyeId('SupCatId',$request->key);
       return response()->json($obj);
    }


    public function getObjByCat (Request $request)
    {
      $linkset = DB::select('select * from linkrefferences where SupCatId = ? and CatId = ?',[$request->supCkey,$request->catkey]);
       return response()->json($linkset);
    }


    public function getObjBySubCat (Request $request)
    {
      $linkSubset = DB::select('select * from linkrefferences where SupCatId = ? and CatId = ? and SubCatId= ?',[$request->supCkey,$request->catkey,$request->subckey]);
       return response()->json($linkSubset);
    }

    public function getObjByObjCat (Request $request)
    {
      $linkSubset = DB::select('select * from linkrefferences where SupCatId = ? and CatId = ? and SubCatId= ? and ObjCatId =?',[$request->supCkey,$request->catkey,$request->subckey,$request->objcategory]);
       return response()->json($linkSubset);
    }












    public function SuperCatRequest (Request $request)
    {
       $obj = (new Repository(new ObjectEn))->getByKyeId('SupCatId',$request->key);
       return response()->json($obj);
    }


    public function CatRequest (Request $request)
    {
        $Objectset = DB::select('select * from object_ens where SupCatId = ? and CatId = ?',[$request->SupCatekey,$request->Catekey]);
       return response()->json($Objectset);
    }


    public function SubCatRequest (Request $request)
    {
        $Objectset = DB::select('select * from object_ens where SupCatId = ? and CatId = ? and SubCatId=?',[$request->SupCatekey,$request->Catekey,$request->SubCatkey]);
       return response()->json($Objectset);
    }

    public function ObjCatRequest (Request $request)
    {
        $Objectset = DB::select('select * from object_ens where SupCatId = ? and CatId = ? and SubCatId=? and ObjectCateId=?',[$request->SupCatekey,$request->Catekey,$request->SubCatkey,$request->ObjCatId]);
       return response()->json($Objectset);
    }

    // 



}
