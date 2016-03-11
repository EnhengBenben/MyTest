@extends('layouts.datatable')

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>已取消申请列表</h5>
        </div>
        <div class="ibox-content">
            <table class="table table-striped table-bordered table-hover dataTable" id="dataTable" role="grid">
                <thead>
                <tr role="row">
                    <th>#</th>
                    <th>申请人</th>
                    <th>手机号</th>
                    <th>单位</th>
                    <th>进修班</th>
                    <th>申请时间</th>
                    <th>积分</th>
                    <th>推荐人</th>
                    <th>取消原因</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>申请人</th>
                    <th>手机号</th>
                    <th>单位</th>
                    <th>进修班</th>
                    <th>申请时间</th>
                    <th>积分</th>
                    <th>推荐人</th>
                    <th>取消原因</th>
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
                    url: '{{route('application_cancel-dt')}}',
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
                        "name": "name", "data": "name",
                        "render": function(data,type,full) {
                            return '<a href="/preview_admin/' + full["id"] + '">' + data + '</a>';
                        }
                    },
                    { "name": "phone_number", "data": "phone_number"
                        /*"render": function ( data, type, meta ) {
                            return (data==0)?'男':'女';
                        }*/
                    },
                    { "name": "organization", "data": "organization"},
                    { "name": "course", "data": "course" },
                    { "name": "submitted_at", "data": "submitted_at"},
                    { "name": "score", "data": "score","searchable":false
                    },
                    { "name": "recommender", "data": "recommender"
                    },
                    { "name": "cancel_reason", "data": "cancel_reason"
                    },
                    {
                        "targets": -1,
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "defaultContent": "\
                        <div class='btn-group'>\
                        <button type='button' class='edit btn btn-xs btn-white' data-toggle='tooltip' data-placement='bottom' title='查看申请表'>\
                        <i class='fa fa-folder-open-o'></i><span>电子申请表</span>\
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
                window.location = '/electronic_view/' + id;
            });


        });
    </script>
@stop
