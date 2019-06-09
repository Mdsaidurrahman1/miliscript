<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Session;
use App\Property;
use App\ObjectReference;
use App\ActionLib;
use App\Lib\Repository;
use DB;


class ObjectRefController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $mod;
    public function __construct(ObjectReference $link)
    {
        $this->mod = new Repository($link);
    }





    public function propertyEntry(Request $request)
    {
        $this->validate($request, [
            'ObjectId'=> 'required',
            'Property' => 'required',
            'Value'=> 'required'
        ]);

        $property = $this->mod->store($request->only($this->mod->getModel()->fillable));
        return redirect()->route('objectdetail', ['id' => $request->ObjectId]);
    }
        


    public function actionEntry(Request $request)
    {
        $this->validate($request, [
            'ObjectId'=> 'required',
            'ActionName' => 'required'

        ]);

        $property = $this->mod->store($request->only($this->mod->getModel()->fillable));
        return redirect()->route('objectdetail', ['id' => $request->ObjectId]);
    }










    

    public function delete(Request $request)
    {
       $serviceDelete  =  $this->mod->getById($request->id);
       $serviceDelete->delete();
       return redirect('/categorysetup');
    }


    
}
