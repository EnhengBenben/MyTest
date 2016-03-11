@extends('layouts.master_admin')

@section('head')
    @parent
    <link href="/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="/assets/summerNote/summernote.css" rel="stylesheet">
@stop

@section('content')


<div class="row">
    <div class="col-xs-10 col-lg-offset-1">

        <!-- ibox -->
        <div class="ibox float-e-margins" style="margin-bottom:50px;">
            <div class="ibox-title">
                <h5>编辑进修班</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <!-- form began -->
                <form class="form-horizontal" action="/course/{{$course->id}}" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT"/>
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                    <div class="form-group"><label class="col-sm-2 control-label">进修班名称</label>
                        <div class="col-sm-5"><input class="form-control" name="name" id="name" type="text" value="{{$course->name}}"></div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">申请截止日期</label>
                        <div class="col-sm-5">
                            <input class="form-control datepickers" type="text" name="application_deadline" id="application_deadline" value="{{$course->application_deadline}}"/>
                        </div>
                        <!--div class="col-sm-10"><div class="input-group m-b"><input class="form-control" name="price" id="price" type="text" value="{{\Input::old('price')}}"><span class="input-group-addon"></span></div></div-->
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">发榜日期</label>

                        <div class="col-sm-5">
                            <input class="form-control datepickers" name="announcement_date" id="announcement_date" type="text" value="{{$course->announcement_date}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">报到日期</label>
                        <div class="col-sm-5">
                            <input class="form-control datepickers" name="enrollment_date" id="enrollment_date" type="text" value="{{$course->enrollment_date}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">结业日期</label>
                        <div class="col-sm-5">
                            <input class="form-control datepickers" name="graduation_date" id="graduation_date" type="text" value="{{$course->graduation_date}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">进修时间</label>

                        <div class="col-sm-5">
                            <input class="form-control" type="text" name="period"  value="{{$course->period}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">招生学员人数</label>

                        <div class="col-sm-5">
                            <input class="form-control" type="text" name="student_max" id="student_max" value="{{$course->student_max}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">进修费用</label>

                        <div class="col-sm-5">
                            <div class="input-group m-b">
                                <input class="form-control" name="fee" id="fee" type="text" value="{{$course->fee}}">
                                <span class="input-group-addon">元</span>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">详细信息</label>
                        <div class="col-sm-8">
                            <textarea class="form-control summernote" rows="6" name="detail_info" id="detail_info" >{{$course->detail_info}}</textarea>
                        </div>
                    </div>

                    <!--div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">报到须知</label>
                        <div class="col-sm-8">
                            <textarea class="form-control summernote" rows="8" name="enrollment_info" id="enroll_info" >{{$course->enrollment_info}}</textarea>
                        </div>
                    </div-->
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">已有附件</label>
                        <div>
                             @foreach($attachments as $attachment)
                                <p style="margin-left: 155px;margin-top: 6px;margin-bottom: -20px">{{$attachment->name}}&nbsp;&nbsp;<i class='fa fa-close' onclick="window.location.href=('/course/delete_attachment/{{$course->id}}/{{$attachment->name}}')"></i></p><br>
                            @endforeach
                        </div>
                        </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">上传附件</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="attachment[]" id="attachment" type="file" multiple>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-8">
                            <button class="btn btn-primary" type="submit">保存修改</button>
                        </div>
                    </div>
                </form>
                <!-- form  end -->
            </div>
        </div>
            <!-- ibox end -->
    </div>
</div>
@stop

@section('foot')
    @parent
    <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker-zh-cn.min.js"></script>
    <script type="text/javascript" src="/assets/summerNote/summernote.min.js"></script>
    <script>
        $('.datepickers').datetimepicker({
            format: 'yyyy-mm-dd',
            language:'zh-CN',
            fontAwesome: true,
            startView: 4,
            autoclose:true,
            minView: 2

        });
        $('.datetimepicker').css('margin-top','-28px');
        $(document).ready(function() {
            $('.summernote').summernote();
        });
    </script>

@stop