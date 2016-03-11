<script>
    var notificationManager = {};
    notificationManager.showSuccess = function(message) {
        toastr.success(message);
    };
    notificationManager.showInfo = function(message) {
        toastr.info(message);
    };
    notificationManager.showWarning = function(message) {
        toastr.warning(message);
    };
    notificationManager.showDanger = function(message) {
        toastr.error(message);
    };
</script>

@if (Session::has('success'))
@section('foot')
    @parent
    <script>
        notificationManager.showSuccess("{{Session::get('success')}}");
    </script>
@stop
@endif

@if (Session::has('info'))
@section('foot')
    @parent
    <script>
        notificationManager.showInfo("{{Session::get('info')}}");
    </script>
@stop
@endif

@if (Session::has('warning'))
@section('foot')
    @parent
    <script>
        notificationManager.showWarning("{{Session::get('warning')}}");
    </script>
@stop
@endif

@if ($errors->first())
@section('foot')
    @parent
    <script>
        notificationManager.showDanger("{{ $errors->first() }}");

    </script>
@stop
@endif

@if (Session::has('danger'))
@section('foot')
    @parent
    <script>
        notificationManager.showDanger("{{ Session::get('danger') }}");
    </script>
@stop
@endif
