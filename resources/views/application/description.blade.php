@extends('layouts.master')

@section('head')
    @parent
@stop

@section('content')
    <div>
<div style="margin-right: -15px">
    <div class="img" style="background-image: url(/header.png);height: 130px;margin-left: -15px">
            <button class="btn" style="margin:80px 0 20px 350px;width: 106px;font-size: 16px" onclick="window.location.href='/class'">进修班</button>

    </div>
    <div style="margin-left: 0">
        <p style="margin: 16px 0 10px 0;text-align: center;font-size: 20px"><strong>{{$course->name}}</strong></p>
        <p style="text-align: center"><small>{{$course->published_at}}</small></p>
    </div>
    <div>
        <div style="margin-left: 22px;margin-bottom: 20px">
            <p><strong>申请截止：</strong>{{$course->application_deadline}}</p>
            <p><strong>发榜日期：</strong>{{$course->announcement_date}}</p>
            <p><strong>报到日期：</strong>{{$course->enrollment_date}}</p>
            <p><strong>结业日期：</strong>{{$course->graduation_date}}</p>
            <p><strong>进修时间：</strong>{{$course->period}}</p>
            <p><strong>进修费用：</strong>{{$course->fee}}元</p>
            <strong>详细介绍：</strong><div id="info">{!! $course->detail_info !!}</div>
            <p><strong>附件：</strong></p>
                @foreach($attachments as $attachment)
                    <a style="margin-left: 35px" href="{{config('app.bdkq_admin_url') . '/attachment?url=' . $attachment->relative_path}}"
                       download="{{$attachment->name}}">{{$attachment->name}}</a><br>
                @endforeach

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
    </script>
@stop