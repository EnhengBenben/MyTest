@extends('layouts.datatable')
@section('head')
    @parent
    <link href="/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@stop
@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>进修班列表</h5>
        </div>
        <div class="ibox-content">
            <form class="form-inline">
                <div class="form-group">
                    <label class="font-normal" for="submitted_at">截止日期</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control datepickers dt-filter" id="start" name="start_date" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-normal" for="submitted_at">—</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control datepickers dt-filter" id="submitted_at" name="end_date" value="">
                    </div>
                </div>
            </form>
            <div class="hr-line-dashed"></div>
            <table class="table table-striped table-bordered table-hover dataTable" id="dataTable" role="grid">
                <thead>
                <tr role="row">
                    <th>#</th>
                    <th>进修班</th>
                    <th>截止日期</th>
                    <th>发榜日期</th>
                    <th>进修时间</th>
                    <th>招生人数</th>
                    <th>申请</th>
                    <th>学员</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>进修班</th>
                    <th>截止日期</th>
                    <th>发榜日期</th>
                    <th>进修时间</th>
                    <th>招生人数</th>
                    <th>申请</th>
                    <th>学员</th>
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
        $(document).ready(function(){
            var table = $('.dataTable').DataTable({
                ajax: {
                    url: '{{route('course-dt')}}',
                    type: 'POST',
                    "data": function(d){
                        var data = {};
                        $('.dt-filter').each(function(index, ele) {
                            data[$(ele).attr('name')] = $(ele).val();
                        });
                        return $.extend(data,d);
                    }
                },
                createdRow: function(row, data, dataIndex) {
                    $('td:eq(0)', row).html( table.page.info().start + dataIndex + 1 );
                },
                columns: [
                    { "name": "index", "searchable": false, "orderable": false, "targets": 0, "defaultContent": '' },
                    {
                        "name": "name", "data": "name"
                    },
                    { "name": "application_deadline", "data": "application_deadline"},
                    { "name": "announcement_date", "data": "announcement_date" },
                    {"name": "period","data":"period"},
                    { "name": "student_max", "data": "student_max"},
                    { "name": "appliers", "data": "appliers","searchable":false,"orderable":false,
                        "render": function(data,type,full) {
                            return '<a href="/appliers/' + full["id"] + '">' + data + '</a>';
                        }
                    },
                    { "name": "students", "data": "students","searchable":false,"orderable":false,
                        "render": function(data,type,full) {
                            return '<a href="/students/' + full["id"] + '">' + data + '</a>';
                        }
                    },
                    {
                        "targets": -1,
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "defaultContent": "\
                        <div class='btn-group'>\
                        <button type='button' class='edit btn btn-xs btn-white' data-toggle='tooltip' data-placement='bottom' title='编辑'>\
                        <i class='fa fa-edit'></i><span>编辑</span>\
                        </button> \
                        <button type='button' class='unpublish btn btn-xs btn-white' data-toggle='tooltip' data-placement='bottom' title='取消发布'>\
                        <i class='fa fa-close'></i><span>取消发布</span>\
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
                window.location = '/course/' + 'edit/' + id;
            });
            var tableBody = $('.dataTable tbody');
            tableBody.on('click', 'button.unpublish', function () {
                var data = table.row( $(this).parents('tr') ).data();
                var id = data['id'];
                window.location = '/course/' + 'unpublish/' + id;
            });
            //region 自动重新搜索
            $('.dt-filter').change(function() {
                table.draw();
            });


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
