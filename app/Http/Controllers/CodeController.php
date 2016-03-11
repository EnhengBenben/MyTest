<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Application;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendVerifyCode(Request $request)
    {
        $this->validate($request,[
            "phone_number"=>"required|size:11",
        ],[],[
            "phone_number"=>"手机号码",
        ]);
        $phoneNumber = $request->phone_number;
        $request->session()->put('phone_number_signup',$phoneNumber);
        $application = Application::all();
        foreach($application as $value) {
            if ($value['phone_number'] == $phoneNumber) {
                return ('used');
            }
        }
        $verifyCode = $this->getVerifyCode($phoneNumber);
        $url = 'http://sms.miyx.cn/api/v1/tasks?appid=t6XHGDckQ6fTPhVP';
        $client = new Client();
        $response = $client->post($url, [
            'json' => [
                "callback_url" => "",
                "template_id" => 8,
                "receivers"=> [[
                    "phone_number"=> $phoneNumber,
                "data"=> [
                    "verifyCode"=> $verifyCode
                ]
                 ]]
        ]])->json();
        return ('ok');
    }
	
	public function sendVerifyCode_change(Request $request)//更改密码
    {
        $this->validate($request,[
            "phone_number"=>"required|size:11",
        ],[],[
            "phone_number"=>"手机号码",
        ]);
        $phoneNumber = $request->phone_number;
        $request->session()->put('phone_number_signup',$phoneNumber);
        $verifyCode = $this->getVerifyCode($phoneNumber);
        $url = 'http://sms.miyx.cn/api/v1/tasks?appid=t6XHGDckQ6fTPhVP';
        $client = new Client();
        $response = $client->post($url, [
            'json' => [
                "callback_url" => "",
                "template_id" => 8,
                "receivers"=> [[
                    "phone_number"=> $phoneNumber,
                "data"=> [
                    "verifyCode"=> $verifyCode
                ]
                 ]]
        ]])->json();
        return ('ok');
    }

    private function getVerifyCode($phoneNumber)
    {
        $code = \Session::get('code');
        if (empty($code)) {
            $code = '' . rand(100000, 999999);
        }
        \Session::put('code',$code);
        return $code;
    }

public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
