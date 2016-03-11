@extends('layouts.datatable')
@section('head')
@parent
<link href="/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@stop
@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>申请列表</h5>
        </div>
        <div class="ibox-content">
            <form class="form-inline">

                <div class="form-group">
                    <label class="font-normal" for="course">进修班</label>
                    <select class="form-control dt-filter" id="course" name="course_id">
                        <option value="" selected="selected">全部进修班</option>
                        @foreach($courses as $course)
                            <option value="{{$course->id}}">{{$course->name}}</option>
                        @endforeach
                    </select>
                </div>
                &nbsp;
                <div class="form-group">
                    <label class="font-normal" for="submitted_at">申请时间</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control datepickers dt-filter" id="submitted_at" name="submitted_at" value="">
                    </div>
                </div>

                &nbsp;
                <div class="form-group">
                    <label class="font-normal" for="course">是否有推荐人</label>
                    <select class="form-control dt-filter" id="recommend" name="recommend">
                        <option value="" selected="selected">请选择</option>
                        <option value="yes">有</option>
                        <option value="no">无</option>

                    </select>
                </div>
                &nbsp;
                <div class="form-group">
                    <label class="font-normal" for="course">审核状态</label>
                    <select class="form-control dt-filter" id="status" name="status">
                        <option value="" selected="selected">所有状态</option>
                        <option value="no">未审核</option>
                        <option value="reject">拒绝</option>
                        <option value="to_pass">待确认通过</option>
                        <option value="passed">已确认通过</option>
                        <option value="no_pass">已拒绝通过</option>
                        <option value="to_transfer">待确认调班</option>
                        <option value="transfered">已确认调班</option>
                        <option value="no_transfer">已拒绝调班</option>
                        <option value="to_postpone">待确认延期</option>
                        <option value="postponed">已确认延期</option>
                        <option value="no_postpone">已拒绝延期</option>
                    </select>
                </div>
            </form>
            <div class="hr-line-dashed"></div>
            <table class="table table-striped table-bordered table-hover dataTable" id="dataTable" role="grid">
                <thead>
                <tr role="row">
                    <th>#</th>
                    <th>姓名</th>
                    <th>手机号</th>
                    <th>单位</th>
                    <th>学历</th>
                    <th>职称</th>
                    <th>进修班</th>
                    <th>申请时间</th>
                    <th>积分</th>
                    <th>推荐人</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>姓名</th>
                    <th>手机号</th>
                    <th>单位</th>
                    <th>学历</th>
                    <th>职称</th>
                    <th>进修班</th>
                    <th>申请时间</th>
                    <th>积分</th>
                    <th>推荐人</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </tfoot>

            </table>
        </div>
    </div>
    
    <div style="height:50px;"></div>
@stop
@section("foot")
    @parent
    <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker-zh-cn.min.js"></script>
    <script>
        $(function(){
            $('#course').val({{$id}});
        });
        $(document).ready(function(){
            var table = $('.dataTable').DataTable({
                ajax: {
                    url: '{{route('application-dt')}}',
                    type: 'POST',
                    "data": function(d){
                        var data = {};
                        $('.dt-filter').each(function(index, ele) {
                            data[$(ele).attr('name')] = $(ele).val();
                        });
                        return $.extend(data, d);
                    }
                },
                createdRow: function(row, data, dataIndex) {
                    $('td:eq(0)', row).html( table.page.info().start + dataIndex + 1 );
                },
                columns: [
                    { "name": "index", "searchable": false, "orderable": false, "targets": 0, "defaultContent": '' },
                    {
                        "name": "name", "data": "name",
                        "render": function(data,type,full) {
                            return '<a href="/preview_admin/' + full["application_id"] + '/' + full["course"] +'">' + data + '</a>';
                        }
                    },
                    { "name": "phone_number", "data": "phone_number"
                    },
                    { "name": "organization", "data": "organization"},
                    { "name": "degree", "data": "degree"},
                    { "name": "tech_duty", "data": "tech_duty"},
                    { "name": "course", "data": "course" },
                    { "name": "submitted_at", "data": "submitted_at"},
                    { "name": "score", "data": "score"
                    },
                    { "name": "recommender", "data": "recommender"
                    },
                    { "name": "status", "data": "status","searchable": false,'orderable':false
                    },
                    {
                        "targets": -1,
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "defaultContent": "\
                        <div class='btn-group'>\
                        <button type='button' class='edit btn btn-xs btn-white' data-toggle='tooltip' data-placement='bottom' title='查看申请表'>\
                        <i class='fa fa-folder-open-o'></i><span>审核申请表</span>\
                        </button> \
                        <button type='button' class='recommender btn btn-xs btn-white' data-toggle='tooltip' data-placement='bottom' title='添加推荐人'>\
                        <i class='fa fa-plus'></i><span>添加推荐人</span>\
                        </button> \
                        </div>"
                    }
                ]
            });

            //region 工具列
            var tableBody = $('.dataTable tbody');

            tableBody.on('click', 'button.edit', function () {
                var data = table.row( $(this).parents('tr') ).data();
                var id = data['id'];
                var status = data['status'];
                window.location = '/electronic_view/' + id + '/' + status;
            });

            tableBody.on('click', 'button.recommender', function () {
                var data = table.row( $(this).parents('tr') ).data();
                var id = data['application_id'];
                window.location = '/recommender/' + id;
            });
            //region 自动重新搜索
            $('.dt-filter').change(function() {
                table.draw();
            });
            //endregion
        });
        $('.datepickers').datetimepicker({
            format: 'yyyy-mm-dd',
            language:'zh-CN',
            fontAwesome: true,
            startView: 4,
            autoclose:true,
            minView: 2

        });
        $('.datetimepicker').css('margin-top','-28px');
    </script>
@stop
