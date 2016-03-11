<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.ico">
    <link rel="apple-touch-icon-precomposed" href="/favicon.ico">
    <link rel="Bookmark" href="/favicon.ico" >

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="/assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/assets/css/animate.css" rel="stylesheet">
    <link href="/assets/css/style.min.css" rel="stylesheet">
    <style>
        .dataTables_processing {margin-left: 15px;}
    </style>
    @yield('head')
</head>

<body >

<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="profile-element" style="color: #ffffff">
                        医院进修申请管理系统
                    </div>
                    <div class="logo-element">
                        <i class="fa fa-hospital-o"></i>
                    </div>
                </li>
                @foreach($menus as $name => $menu)
                <li>
                    @if (isset($menu['sub_menus']))
                        <a href="javascript:void(null);">
                            {!! $menu['icon'] !!}
                            <span class="nav-label">{{ $name }}</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            @foreach($menu['sub_menus'] as $name => $menu)
                                <li><a href="{{ $menu['url'] }}">{{ $name }}</a></li>
                            @endforeach
                        </ul>
                    @else
                        <a href="{{ $menu['url'] }}">{{ $name }}</a>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    @section('navbar_top_links')
                        <li>
                            <span class="m-r-sm text-muted welcome-message">{{\Auth::user()->username}}，您好</span>
                        </li>
                        <li>
                            <a href="{{route('logout')}}">
                                <i class="fa fa-sign-out"></i> 注销登录
                            </a>
                        </li>
                    @show
                </ul>

            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="" style="padding-top: 10px;">
                    @include('includes.message')
                    @yield('content')
                </div>
            </div>
        </div>
        <div class="footer">
            <div>
                <strong>Copyright</strong> 北京丛林网络技术有限责任公司 &copy; 2014-2015
            </div>
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

<!-- Custom and plugin javascript -->
<script src="/assets/js/plugins/bootbox/bootbox.min.js"></script>
<script src="/assets/js/inspinia.js"></script>
<script src="/assets/js/plugins/pace/pace.min.js"></script>
<script>
    (function() {
        if (typeof String.prototype.startsWith != 'function') {
            // see below for better implementation!
            String.prototype.startsWith = function (str){
                return this.indexOf(str) == 0;
            };
        }
        var url = window.location.href;
        // Will also work for relative and absolute hrefs
        var activeMenu = $('#side-menu a').filter(function() {
            return ($(this).attr('href') != null && $(this).attr('href').length > 0) && url == $(this).attr('href');
        });
        activeMenu.parents('nav li').addClass('active');
        activeMenu.parents('nav .collapse').addClass('in');
    })();
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "7000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>
@show

</body>

</html>
