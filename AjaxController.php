<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\Work;
use App\ObjectCat;
use App\SubCategory;
use Session;
use Redirect;

class AjaxController extends Controller
{

    public function __construct()
    {
        //$this->middleware('checkauthencation');
    }


    public function objectcateReq (Request $request)
    {

        // $objcatmod = (new Repository(new ObjectCat))->getByKyeId('SubCatId',$request->key);
        $subcatpack  =  (new Repository(new SubCategory))->getAll(); 
       return response()->json($subcatpack);

    }







}
