<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Validator;
use App\User;
use App\Lib\Repository;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $_userModel;
    public function __construct(User $user)
    {
        $this->_userModel = new Repository($user);
    }


    public function registerpost(Request $request)
    {

            $this->validate($request, [
                'username' => 'required',
                'password' => 'required',
                'email'=>'required'
            ]);

        $this->_userModel->store($request->only($this->_userModel->getModel()->fillable));
            Session::flash('Successmessage', "Register successfull! please login");
            return Redirect::route('userlogin');
        //return Redirect::route('userlogin');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function login()
    {
        return view('login');
    }


    public function loginpost(Request $request)
    {

        $this->validate($request, [
            'username' => 'required',
            'userpassword' => 'required'
        ]);

        $name = $request->username;
        $password = $request->userpassword;


        $loginstatus = DB::select('select * from users where username = ? and password = ?',[$name,$password]);
        if($loginstatus != null)
        {
            $username = $loginstatus[0]->username;

            Session::put('username',$username);
            Session::put('loggedin','true');

            return redirect('/admin');

        }else
        {
            Session::flash('message', "User name and/or Password does not match");
            return Redirect::back();
        }

    }

    public function register()
    {
        return view('register');
    }



    public function signout()
    {
        session::flush();
        return Redirect::route('userlogin');

    }


}
