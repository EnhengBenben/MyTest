<!DOCTYPE html>
<html>
<head>
    @section('head')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>@yield('title')</title>
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/favicon.ico">
        <link rel="apple-touch-icon-precomposed" href="/favicon.ico">
        <link rel="Bookmark" href="/favicon.ico" >

        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="/assets/css/animate.css" rel="stylesheet">
        <link href="/assets/css/style.min.css" rel="stylesheet">
        <link href="/assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
        <link href="/application.css" rel="stylesheet">
    @show
</head>
<body>
<div id="main-content">

    <div class="container-fluid main">
        @include('includes.message')
        @yield('content')
    </div>
    <div class="footer">
        <div align="center">
            <p>北京丛林网络技术有限责任公司</p>
            <p>服务电话：4000687626    服务邮件：service@conglinnet.com</p>
        </div>
    </div>
</div>
@section('foot')
    <!-- Mainly scripts -->
    <script src="/assets/js/jquery-2.1.1.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/assets/js/plugins/toastr/toastr.min.js"></script>
<script src="/assets/js/plugins/bootbox/bootbox.min.js"></script>
<script src="/assets/js/inspinia.js"></script>
<script src="/assets/js/plugins/pace/pace.min.js"></script>


@show
</body>
</html>
