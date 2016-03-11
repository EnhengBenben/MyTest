@extends('layouts.datatable')

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>积分设置</h5>


        </div>


        <div class="ibox-content">
            <div class="tabs-container">
                <button class="btn pull-right" style="background-color: #18a689;color: #FFFFFE" onclick="window.location.href='/region/add'">添加地域</button>
                <ul class="nav nav-tabs">
                    <li class=""><a href="/score/tech_duty">技术职称</a></li>
                    <li class=""><a href="/score/degree">学历</a></li>
                    <li class=""><a href="/score/org_rank">医院级别</a></li>
                    <li class=""><a href="/score/admin_duty">行政职务</a></li>
                    <li class="active"><a href="#">地域</a></li>
                    <!--li class=""><a href="#">论文数量</a></li-->
                </ul>

                <div class="tab-content" style="margin-top: 20px;">
                <table class="table table-striped table-bordered table-hover dataTable" id="dataTable" role="grid">
                <thead>
                <tr role="row">
                    <th>#</th>
                    <th>地域</th>
                    <th>积分</th>
                    <th>说明</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>地域</th>
                    <th>积分</th>
                    <th>说明</th>
                    <th>操作</th>
                </tr>
                </tfoot>

            </table>
                </div>
        </div>
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
                    url: '{{route('region-dt')}}',
                    type: 'POST',
                    "data": function(d){
                        return $.extend({},d,{});
                    }
                },
                createdRow: function(row, data, dataIndex) {
                    $('td:eq(0)', row).html( table.page.info().start + dataIndex + 1 );
                },
                columns: [
                    { "name": "id", "data":"id" },
                    {
                        "name": "name", "data": "name"
                    },
                    { "name": "score", "data": "score"

                    },
                    { "name": "comment", "data": "comment"},

                    {
                        "targets": -1,
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "defaultContent": "\
                        <div class='btn-group'>\
                        <button type='button' class='edit btn btn-xs btn-white' data-toggle='tooltip' data-placement='bottom' title='修改'>\
                        <i class='fa fa-edit'></i><span>编辑</span>\
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
                window.location = '/region/edit/' + id;
            });
            /*var tableBody = $('.dataTable tbody');
            tableBody.on('click', 'button.delete', function () {
                var data = table.row( $(this).parents('tr') ).data();
                var id = data['id'];
                window.location = '/tech_duty/delete/' + id;
            });*/


        });
    </script>
@stop
