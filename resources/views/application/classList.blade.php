@extends('layouts.master')
@section('head')
    @parent
    <link href="/assets/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/assets/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
    <link href="/assets/css/sweetalert.min.css" rel="stylesheet">
    <link href="/assets/css/plugins/dataTables/extensions/tableTools/dataTables.tableTools.min.css" rel="stylesheet">
@stop
@section('content')
    <div style="margin-right: -15px">
        <div class="img" style="background-image: url(/header.png);height: 130px;margin-left: -15px">
            <button class="btn" style="margin:80px -240px 20px 34px;width: 106px;font-size: 16px" onclick="window.location.href='/query'">申请查询</button>
        </div>
    </div>
        <div class="ibox float-e-margins" style="margin-top: 0px;">
        <div class="ibox-title" style="text-align: center">
            <strong style="font-size: 18px">进修班列表</strong>
        </div>
        <div class="ibox-content">

            <table class="table table-striped table-bordered table-hover dataTable" id="dataTable" role="grid">
                <thead>
                <tr role="row">
                    <th>#</th>
                    <th>进修班</th>
                    <th>申请截止日期</th>
                    <th>进修时间</th>
                    <th>招生人数</th>
                    <th>进修费用</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                </tbody>

            </table>
        </div>
    </div>

    <div style="height:50px;"></div>
@stop
@section("foot")
    @parent
    <script src="/assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/assets/js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="/assets/js/plugins/dataTables/extensions/tableTools/dataTables.tableTools.min.js" chartset="utf8"></script>
    <script src="/assets/js/plugins/dataTables/extensions/tableTools/dataTables.tableTools.download.js"></script>
    <script src="/assets/js/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $.extend( $.fn.dataTable.defaults, {
                pageLength: 10,
                processing: true,
                serverSide: true,
                responsive: true,
                language: {
                    "url": "/assets/js/plugins/dataTables/Chinese.json"
                },
                "order": [[ 2, 'asc' ]]
            } );
        });
    </script>
    <script>
        $(document).ready(function(){
            var table = $('.dataTable').DataTable({
                ajax: {
                    url: '{{route('class-dt')}}',
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
                    { "name": "index", "searchable": false, "orderable": false, "targets": 0, "defaultContent": ''},
                    {
                        "name": "name", "data": "name",
                        "render": function(data,type,full) {
                            return '<a href="/course/description/' + full["id"] + '">' + data + '</a>';
                        }
                    },
                    { "name": "application_deadline", "data": "application_deadline"},
                    {"name": "period","data":"period"},
                    { "name": "student_max", "data": "student_max"},
                    { "name": "fee", "data": "fee"},
                    {
                        "targets": -1,
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "defaultContent": "\
                        <div class='btn-group'>\
                        <button type='button' style='font-size: 12px;color:grey;width: auto;background-color: #FFFFFE;' class='edit btn btn-xs btn-white' data-toggle='tooltip' data-placement='bottom' title='申请'>\
                        <i class='fa fa-edit'></i><span>申请</span>\
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
                window.location = '/info?index=' + id;
            });
            //

            //region 自动重新搜索
            $('.dt-filter').change(function() {
                table.draw();
            });

        });
        $(document).ready(function(){
            $('.footer').css("position","relative");
        });

    </script>
    <script>
        var flag = '{!! $alert !!}';
        if (flag == '1') {
            swal({
                title: "无法申请",
                text: "十分抱歉，您所选的课程与已申请课程时间冲突",
                type: "warning"
            });
        } else if (flag == '2') {
            swal({
                title: "无可选课程",
                text: "十分抱歉，您已申请课程与所有课程进修时间冲突",
                type: "warning"
            });
        }


    </script>

@stop
