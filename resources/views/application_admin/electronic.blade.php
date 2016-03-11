@extends('layouts.master_admin')

@section('head')
    @parent
@stop

@section('content')


<div class="row">
    <div class="col-xs-10 col-lg-offset-1">

        <!-- ibox -->
        <div class="ibox float-e-margins" style="margin-bottom:50px;">
            <div class="ibox-title">
                <h5>审核申请表</h5>

            </div>

            <div class="ibox-content">
                <!-- form began -->
                <div style="display: block">
                    <label class="col-sm-2 control-label">申请者姓名</label>
                    <p>{{$application->name}}</p>
                    <label class="col-sm-2 control-label">申请进修班名称</label>
                    <p>{{$course->name}}</p>
                    <label class="col-sm-2 control-label">学历</label>
                    <p>{{$degree}}</p>
                    <label class="col-sm-2 control-label">职称</label>
                    <p>{{$tech_duty}}</p>
                    <label class="col-sm-2 control-label">积分</label>
                    <p>{{$application->score}}</p>
                </div>
                <div class="hr-line-dashed"></div>
                    <form class="form-horizontal" action="/application/operate/{{$id}}" method="post" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                        <div class="form-group" id="target"><label style="margin-left: 17px">请选择审核操作</label>
                        <select required="" id="operation" name="operation" class="form-control" style="width: 90%;margin-left: 17px">
                            <option value="">请选择操作</option>
                                <option value="pass">通过</option>
                                <option value="refuse">拒绝</option>
                                <option value="transfer">调班</option>
                                <option value="postpone">延期</option>
                        </select>

                        </div>
                        <label>审核前状态：{{$status}}</label>
                        <button class="btn btn-primary pull-right" type="submit">确认审核</button>
                    </form>
                <div class="hr-line-dashed"></div>
                    <div>
                        <img src="/electronic_table/{{$id}}" style="width: 100%;height: auto;text-align: center">
                    </div>
                <!-- form  end -->
            </div>
        </div>
            <!-- ibox end -->
    </div>
</div>
@stop

@section('foot')
    @parent
    <script>
        //initial
        var item = {!!json_encode($item)!!};
        if (item['passed_at'] != null) {
            $('#operation').val('pass');
        } else if (item['rejected_at'] != null) {
            $('#operation').val('refuse');
        } else if (item['transfer_course_id'] != null || item['postpone_course_id'] != null) {
            $('#target').append('<select required="" id="course_id" name="course_id" class="form-control" style="width: 90%;margin-left: 17px">\
                        <option value="">请选择操作</option>\
                        @foreach($courses as $course)
                            <option value="{{$course->id}}">{{$course->name}}</option>\
                        @endforeach
                    </select>');
            if (item['transfer_course_id'] != null) {
                $('#operation').val('transfer');
                $('#course_id').val(item['transfer_course_id']);
            }
            if (item['postpone_course_id'] != null) {
                $('#operation').val('postpone');
                $('#course_id').val(item['postpone_course_id']);
            }
        }
        //change
        $('#operation').change(function(){
            if ($('#course_id').length)
                $('#course_id').remove();
            if ($('#operation').val() == 'transfer' || $('#operation').val() == 'postpone'){

                $('#target').append('<select required="" id="course_id" name="course_id" class="form-control" style="width: 90%;margin-left: 17px">\
                        <option value="">请选择操作</option>\
                        @foreach($courses as $course)
                            <option value="{{$course->id}}">{{$course->name}}</option>\
                        @endforeach
                    </select>');
            }
        });
    </script>
@stop