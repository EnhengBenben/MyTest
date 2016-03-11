@extends('layouts.master')

@section('head')
    @parent
@stop

@section('content')
    <div>
<div style="margin-right: -15px">
    <div class="img" style="background-image: url(/header.png);height: 130px;margin-left: -15px">
            <button class="btn" style="margin:80px 0 20px 350px;width: 106px;font-size: 16px" onclick="window.location.href='/class'">进修班</button>
            <button class="btn" style="margin:80px -240px 20px 34px;width: 106px;font-size: 16px" onclick="window.location.href='/info/{{$course->id}}'">立即申请</button>

    </div>
    <div style="margin-left: 0">
        <p style="margin: 16px 0 10px 0;text-align: center;font-size: 20px"><strong>《{{$course->name}}》报到须知</strong></p>
        <p style="text-align: center"><small>{{$course->published_at}}</small></p>
    </div>
    <div>
        <div style="margin-left: 22px" id="info">


        </div>

    </div>

</div>
</div>

@stop

@section('foot')
    @parent
    <script>
        $(document).ready(function(){
            $('.footer').css("position","relative");
        });
        $('#info').html('{!! $course->enrollment_info !!}');
    </script>
@stop