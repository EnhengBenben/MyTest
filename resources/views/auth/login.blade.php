@extends('layouts.master')
@section('head')
@parent
@stop

        <!--body class="gray-bg"-->
@section('content')
    <div style="margin-right: -15px">
        <div class="img" style="background-image: url(/header.png);height: 130px;margin-left: -15px">
            <button class="btn" style="margin:80px 0 20px 350px;width: 106px;font-size: 16px" onclick="window.location.href='/class'">进修班</button>

        </div>
        <div style="margin-left: -15px">
            <p class="text" style="margin: 16px 0 10px 35px">申请查询</p>
            <img src="/line1.png">
        </div>
        <form class="m-t" role="form" action="{{url('/auth/login', [], false)}}" method="post">
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
            <input type="hidden" name="origin_url" value={{\Input::get('origin_url')}}  ><br>
            <div align="center">
                <div style="margin-top:25px">
                    <p class="text1" style="display:none" id="warning">系统中未识别到您的申请信息，请确认身份信息正确</p>
                </div>
                <input type="hidden" name="from" id="from" value="">
                <div style="display: inline-block;margin-right: 12px;margin-top: 50px">
                    <p>手机号</p>
                </div>
                <input type="text" required="" class="input" name="phone_number" value={{\Input::old("phone_number")}}  ><br>
                <div style="display: inline-block;margin-right: 12px;margin-top: 45px;margin-left: 3px">
                    <p>密&nbsp;&nbsp;&nbsp;码</p>
                </div>
                <input type="text" required="" class="input" name="password" value={{\Input::old("password")}}  ><br>
                <button class="btn" style="margin-top: 40px">登录</button>
                <p style="font-size: 14px;margin-top:10px;margin-bottom: 90px">
                    没有帐号，<a href="/newuser_signup">注册账户</a></p>
            </div>
        </form>
    </div>
    @stop
    @section('foot')
            <!-- Mainly scripts -->
    <script src="/assets/js/jquery-2.1.1.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.footer').css("position","relative");
        });
    </script>
    @stop
            <!--/body-->

