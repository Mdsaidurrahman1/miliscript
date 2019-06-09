<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Redirect,Session;
use App\LinkDetail;

use App\Lib\Repository;


class LinkDetailController extends Controller
{
    
    protected $mod;
    public function __construct(LinkDetail $link)
    {
        $this->mod = new Repository($link);
    }



    public function addlinkRef(Request $request)
    {
        $this->validate($request, [
        'propertyName' => 'required',
        'propertyValue' => 'required'
        ]);

        $sitepost = $this->mod->store($request->only($this->mod->getModel()->fillable));
        return redirect()->route('linkdetail', ['id' => $request->linkId]);
    }
}
