@extends('layouts.master_admin')

@section('head')
    @parent
    <link href="/assets/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/assets/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
    <link href="/assets/css/plugins/dataTables/extensions/tableTools/dataTables.tableTools.min.css" rel="stylesheet">
@stop

@section('foot')
    @parent
    <script src="/assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/assets/js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="/assets/js/plugins/dataTables/extensions/tableTools/dataTables.tableTools.min.js" chartset="utf8"></script>
    <script src="/assets/js/plugins/dataTables/extensions/tableTools/dataTables.tableTools.download.js"></script>
    <script type="text/javascript">
        $(function() {
            $.extend( $.fn.dataTable.defaults, {
                pageLength: 50,
                processing: true,
                serverSide: true,
                responsive: true,
                language: {
                    "url": "/assets/js/plugins/dataTables/Chinese.json"
                },
                "order": [[ 1, 'asc' ]]
            } );
        });
    </script>
@stop