@extends('layouts.master')

@section('head')
    @parent
@stop

@section('content')
            
<div>
    <div class="img">
        <img src="/index.png" style="margin-left: -15px">
    </div>
    <div class="btn-tri">
        <button class="btn" onclick="window.location.href='/class'">进修班</button>
        <button class="btn" onclick="window.location.href='/query'">登录查询</button>
    </div>
    <div align="right" style="color: #17996E;font-size: 15px;margin-bottom: 10px">建议使用Chrome浏览器</div>
</div>


@stop

@section('foot')
    @parent
    <script>
        $(document).ready(function(){
            $('.footer').css("position","relative").addClass("hidden-print");
        });
    </script>
@stop