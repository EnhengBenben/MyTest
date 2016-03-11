@extends('layouts.datatable')
@section('head')
    @parent
    <link href="/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@stop
@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>学员通讯录</h5>
        </div>
        <div id="tools" style="text-align: right"></div>
        <div class="ibox-content">
            <form class="form-inline">
                <div class="form-group">
                    <label class="font-normal" for="submitted_at">年份</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control datepickers dt-filter" name="announcement" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-normal" for="submitted_at">进修班</label>
                    <div class="input-group date">
                        <!--input type="text" class="form-control dt-filter" name="course" value=""-->
                        <select class="form-control dt-filter" id="course" name="course_id">
                            <option value="" selected="selected">全部进修班</option>
                            @foreach($courses as $course)
                                <option value="{{$course->id}}">{{$course->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
            <div class="hr-line-dashed"></div>

            <table class="table table-striped table-bordered table-hover dataTable" id="dataTable" role="grid">
                <thead>
                <tr role="row">
                    <th>#</th>
                    <th>申请人</th>
                    <th>手机号</th>
                    <th>单位</th>
                    <th>职称</th>
                    <th>进修班</th>
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
                    <th>职称</th>
                    <th>进修班</th>
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
                    url: '{{route('contact-dt')}}',
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
                    { "name": "phone_number", "data": "phone_number"
                        /*"render": function ( data, type, meta ) {
                            return (data==0)?'男':'女';
                        }*/
                    },
                    { "name": "organization", "data": "organization"},
                    {"name":"tech_duty","data":"tech_duty"},
                    { "name": "course", "data": "course" },
                    {
                        "targets": -1,
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "defaultContent": "\
                        <div class='btn-group'>\
                        <button type='button' class='edit btn btn-xs btn-white' data-toggle='tooltip' data-placement='bottom' title='查看信息'>\
                        <i class='fa fa-folder-open-o'></i><span>查看信息</span>\
                        </button> \
                        </div>"
                    }
                ]
                //responsive: true,


            });
            $.fn.DataTable.TableTools.classes.buttons.normal = "btn btn-primary btn-sm m-r-xs";
            var tableTools = new $.fn.dataTable.TableTools( table, {
                "aButtons": [
                    {
                        "sExtends":    "download",
                        "sButtonText": "<i class='fa fa-cloud-download'></i> 导出过滤结果",
                        "sUrl":        "{{route('admin.contact.index_export')}}"
                    }
                ]
            } );
            $( tableTools.fnContainer()).children().insertBefore('#tools');

            //region 工具列
            var tableBody = $('.dataTable tbody');
            tableBody.on('click', 'button.edit', function () {
                var data = table.row( $(this).parents('tr') ).data();
                var id = data['id'];
                window.location = '/preview_admin/' + id;
            });

            $('.dt-filter').change(function() {
                table.draw();
            });
        });

        $('.datepickers').datetimepicker({
            format: 'yyyy',
            language:'zh-CN',
            fontAwesome: true,
            startView: 4,
            autoclose:true,
            minView: 4

        });
        $('.datetimepicker').css('margin-top','-28px');
    </script>

@stop
