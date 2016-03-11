@extends('layouts.master')

@section('head')
    @parent
@stop

@section('content')
            
<div style="margin-right: -15px">
    <div style="margin-left: -15px">
        <p class="text" style="margin: 16px 0 10px 35px">立即注册</p>
        <img src="/line1.png">
    </div>
    <form action="/new_user" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
    <div align="center">
        <div style="margin-top:25px">
        <p class="text1" style="display:none" id="warning">系统中未识别到您的申请信息，请确认登录信息正确，或者先申请进修班</p>
        </div>
        <input type="hidden" name="from" id="from" value="">
        <input type="hidden" name="index" id="index" value="">
        <!--div style="display: inline-block;margin-right: 12px;margin-top: 50px">
            <p>姓名</p>
        </div>
        <input type="text" required="" class="input" name="name" value={{\Input::old("name")}}  ><br-->
        <div style="display: inline-block;margin-right: 12px;margin-top: 45px;margin-left: -14px">
            <p>手机号</p>
        </div>
        <input type="text" required="" class="input" name="phone_number" id = "phone" value={{\Input::old("phone_number")}} ><br>
        <div style="display: inline-block;margin-right: 12px;margin-top: 45px;margin-left: -27px">
            <p>密码</p>
        </div>
        <input type="text" required="" class="input" name="password" value={{\Input::old("password")}} ><br>
        <div style="display: inline-block;margin-right: 12px;margin-top: 45px;margin-left: -27px">
            <p>验证码</p>
        </div>
        <input type="text" required="" class="input" name="code" value={{\Input::old("code")}} ><br>
        <!--div style="display: inline-block;margin-right: 12px;margin-top: 25px;margin-left: -14px">
            <p>验证码</p>
        </div>
        <input type="text" class="input"><br-->
        <!--input type="checkbox" name="first_login" value=true style="margin: 10px 0 0 -117px;">首次申请<br-->
        <button class="btn" style="margin-top: 40px">继续</button>
        <p style="font-size: 14px;margin-top:10px;margin-bottom: 90px">说明:输入正确的手机号与密码</p>
    </div>
        </form>

    <a href="javascript:void(0);" onclick="get_captcha()" class="btn btn-default btn6" id="for_captcha" >获取验证码</a>
</div>


@stop

@section('foot')
    @parent
    <script>
        $(document).ready(function(){
            $('.footer').css("position","relative");
        });
    </script>
    <script>
        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;
            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };
        var from = getUrlParameter('from');
        var index = getUrlParameter('index');
        $('#from').val(from);
        $('#index').val(index);
    </script>

    <script type="text/javascript">
        var url = "/get_code?";
        var i = 0;
        function count_down(){
            i = i - 1;
            $('#for_captcha').attr('disabled','disabled');
            $('#for_captcha').html('剩余' + i + '秒..');
            if(i <= 0){
                $('#for_captcha').removeAttr('disabled','disabled');
                $('#for_captcha').html('获取验证码');
                window.clearTimeout(countdown);
            }else{
                var countdown = setTimeout('count_down()',1000);
            }
        }

        function get_captcha(){
            var phone = $('#phone').val();
            re = /^1\d{10}$/;
            if(re.test(phone)) {
                $.post(url,{
                    phone_number:phone,
                    _token: '{{csrf_token()}}'
                });
                i = 60;
                count_down();
            } else {
                alert("手机号码不正确");
                return false;
            }
        }
    </script>
@stop