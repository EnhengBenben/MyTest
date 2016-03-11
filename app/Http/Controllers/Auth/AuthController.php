<?php

namespace App\Http\Controllers\Auth;


use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    //use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /*
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function getLogin()
    {
        $originUrl = \Input::get('origin_url');
        //dd($originUrl);
        return view('auth.login', ['originUrl' => $originUrl]);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'phone_number' => 'required',
            'password' => 'required'
        ]);
        $ok = \Auth::attempt(['phone_number' => $request['phone_number'], 'password' => $request['password']]);
        if ($ok) {
            $originUrl = $request->get('origin_url','/');
            $application = Application::where('phone_number',$request['phone_number'])->get();
            $request->session()->put('application_id',$application[0]['id']);
            $request->session()->put('phone_number',$request['phone_number']);
            return redirect($originUrl);
        } else {
            return redirect()->back()->with('danger', '请输入正确的用户名密码!');
        }
    }
    public function getLogout()
    {
        \Auth::logout();
        session()->flush();
        return redirect(route('login'));
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */

}
