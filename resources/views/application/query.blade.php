@extends('layouts.master')

@section('head')
    @parent
@stop

@section('content')
            
<div style="margin-right: -15px">
    <div class="img" style="background-image: url(/header.png);height: 130px;margin-left: -15px">
            <button class="btn" style="margin:80px 0 20px 350px;width: 106px;font-size: 16px" onclick="window.location.href='/class'">进修班</button>
            <button class="btn" style="margin:80px -240px 20px 34px;width: 106px;font-size: 16px" onclick="window.location.href='/info'">立即申请</button>

    </div>
    <div style="margin-left: -15px">
        <p class="text" style="margin: 16px 0 10px 35px">申请查询</p>
        <img src="/line1.png">
    </div>
    <form action="/query" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
    <div align="center">
        <div style="margin-top:25px">
        <p class="text1" style="display:none" id="warning">系统中未识别到您的申请信息，请确认身份信息正确</p>
        </div>
        <input type="hidden" name="from" id="from" value="">
        <div style="display: inline-block;margin-right: 12px;margin-top: 50px">
            <p>姓名</p>
        </div>
        <input type="text" required="" class="input" name="name" value={{\Input::old("name")}}  ><br>
        <!--div style="display: inline-block;margin-right: 12px;margin-top: 45px;margin-left: -14px">
            <p>手机号</p>
        </div>
        <input type="text" required="" class="input" name="phone_number"  ><br-->
        <div style="display: inline-block;margin-right: 12px;margin-top: 45px;margin-left: -27px">
            <p>身份证号</p>
        </div>
        <input type="text" required="" class="input" name="id_no" value={{\Input::old("id_no")}} ><br>
        <!--div style="display: inline-block;margin-right: 12px;margin-top: 25px;margin-left: -14px">
            <p>验证码</p>
        </div>
        <input type="text" class="input"><br-->
        <!--input type="checkbox" name="first_login" value=true style="margin: 10px 0 0 -117px;">首次申请<br-->
        <button class="btn" style="margin-top: 40px">查询</button>
        <p style="font-size: 14px;margin-top:10px;margin-bottom: 90px">说明：请输入申请人的姓名和身份证号码，方可查询和处理申请信息</p>
    </div>
        </form>

</div>


@stop

@section('foot')
    @parent
    <script>
        $(document).ready(function(){
            $('.footer').css("position","relative");
        });
        $(function(){
            var status = {!! $status !!};
            //console.log(status);
        if (status == 0){
            $("#warning").css("display","block");
        }
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
        $('#from').val(from);
    </script>
@stop