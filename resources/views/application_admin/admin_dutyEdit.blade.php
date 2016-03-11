@extends('layouts.master_admin')

@section('head')
    @parent
@stop

@section('content')


<div class="row">
    <div class="col-xs-10 col-lg-offset-1">

        <!-- ibox -->
        <div class="ibox float-e-margins" style="margin-bottom:50px;">
            <div class="ibox-title">
                <h5>积分设置（行政职务）</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <!-- form began -->
                <form class="form-horizontal" action="/admin_duty/{{$admin_duty->id}}" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT"/>
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                    <div class="form-group"><label class="col-sm-2 control-label">行政职务</label>
                        <div class="col-sm-3"><input class="form-control" name="name" id="name" type="text" value="{{$admin_duty->name}}"></div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">积分</label>
                        <div class="col-sm-2">
                            <input class="form-control" type="text" name="score" id="score" value="{{$admin_duty->score}}"/>
                        </div>
                        <!--div class="col-sm-10"><div class="input-group m-b"><input class="form-control" name="price" id="price" type="text" value="{{\Input::old('price')}}"><span class="input-group-addon"></span></div></div-->
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">说明</label>

                        <div class="col-sm-8">
                            <textarea class="form-control" name="comment" id="comment" type="text" rows="8">{{$admin_duty->comment}}</textarea>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-8">
                            <button class="btn btn-primary" type="submit">保存修改</button>
                        </div>
                    </div>
                </form>
                <!-- form  end -->
            </div>
        </div>
            <!-- ibox end -->
    </div>
</div>
@stop

@section('foot')
    @parent

@stop