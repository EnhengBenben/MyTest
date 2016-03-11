@extends('layouts.datatable')

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>未发布进修班列表</h5>
        </div>
        <div class="ibox-content">
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
    <script>
        $(document).ready(function(){
            var table = $('.dataTable').DataTable({
                ajax: {
                    url: '{{route('course_unpub-dt')}}',
                    type: 'POST',
                    "data": function(d){
                        return $.extend({},d,{});
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
                    { "name": "period", "data": "period" },
                    { "name": "student_max", "data": "student_max"},
                    { "name": "appliers", "data": "appliers","searchable":false,"orderable":false
                    },
                    { "name": "students", "data": "students","searchable":false,"orderable":false
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
                        <button type='button' class='publish btn btn-xs btn-white' data-toggle='tooltip' data-placement='bottom' title='发布'>\
                        <i class='fa fa-check'></i><span>发布</span>\
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
                window.location = '/course/edit/' + id;
            });
            var tableBody = $('.dataTable tbody');
            tableBody.on('click', 'button.publish', function () {
                var data = table.row( $(this).parents('tr') ).data();
                var id = data['id'];
                window.location = '/course/publish/' + id;
            });

        });
    </script>
@stop
