@extends('layouts.master')

@section('head')
    @parent
    <link href="/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@stop

@section('content')
            
<div style="margin-right: -15px">
    <div class="img" style="background-image: url(/header.png);height: 130px;margin-left: -15px">
            <button class="btn" style="margin:80px 0 20px 350px;width: 106px;font-size: 16px" onclick="window.location.href='/class'">进修班</button>
    </div>
    <div style="margin-left: -15px"><p style="display: inline-block;margin-left: 69px">您已申请进修</p>
        <select name="course_id" id="course_id" class="form-control" style="display: inline-block;width: 50%;margin:10px 0 0 10px ">
            @foreach($data as $index=>$single)
                <option value="{{$index}}">{{$single['course']->name}}</option>
            @endforeach
        </select>
        <a target="_blank" id="detail"><p class="text" style="margin: 16px 0 10px 10px;display: inline-block"><span style="font-size: 10px">进修班详情</span></p></a>
        <img src="/line2.png">
    </div>
    <div style="margin-top: 20px">
    <div style="display: inline-block;margin-left: 84px;margin-bottom: 40px">
        <img src="/flow.png">
    </div>
    <div style="display: inline-block;margin-top:-90px;margin-left:  20px;width: 650px;vertical-align: middle">
        <div style="margin-bottom: 55px;margin-top: 10px">
            <form id="edit_form" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                <input name="id" value={{$id}} type="hidden" />
                <input name="preview" id="preview" value="0" type="hidden" />
            <button class="btn btn1 pull-right" id="edit">编辑</button>
            </form>
            <h3>填写申请表</h3>
            <p>请您在下载申请表前确保所填写的申请信息正确</p>
        </div>
        <form name="form1" action="" id="form1" method="post" onsubmit="return check()" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
        <div style="margin-bottom: 55px;display: block">
            <span class="btn btn1 pull-right" style="position: relative;overflow: hidden;margin-bottom: 5px" id="fake_btn">
                <span id="upload_txt">上传申请表</span>
                <input type="file" name="file_path" id="file_path" onchange="checkImgType(this)" style="position: absolute;top: 0;right: 0;margin:0;opacity: 0;cursor: pointer;font-size: 200px">
            </span>

            <!--button class="btn btn1 pull-right" id="upload">上传</button-->
            <h3>上传申请表</h3>
            <p>请下载第一步的《进修申请表》并打印，交由单位盖章后扫描/拍照上传</p>
            <input class="pull-right" type="text" id="display" readonly style="margin-top: -15px;width: 100%;text-align: right;border:none">
        </div>


        <div style="margin-bottom: 40px;">
            <button class="btn btn1 btn2 pull-right" disabled id="submit">提交</button>
            <h3>提交申请表</h3>
            <p>注意：<label class="text1">提交申请表方完成申请</label>，您的申请表提交后不能修改</p>
        </div>
        </form>
        <div style="margin-bottom: -110px;margin-top: 52px;">
            <button class="btn btn1 btn2 pull-right" disabled id="check">等待审核</button>
            <h3>申请结果</h3>
            <p id="remind">您的申请结果将在发榜后显示</p>

        </div>

    </div>
        <div style="margin:15px 30px 30px 84px">
            <p><label class="text1">申请流程：</label>请<strong>【编辑】</strong>确认申请信息正确完整，打印并由单位盖章后，扫描/拍照<strong>【上传】</strong>电子版，并<strong>【提交】</strong>方可完成申请</p>
             </div>
    </div>
    <a id="enroll" class="pull-right" style="margin-right: 21px;margin-top: -100px;display: none" download="进修通知及报道须知.docx">下载《进修通知及报道须知》</a>
    <a id="application" style="position: relative;top: -402px;left:732px" download="进修申请表.docx">下载《进修申请表》</a>
        <div style="position: relative;top: -158px;left:675px" id="confirm">
            <button class="btn btn1" id="agree" type="submit">接受</button>
            <button class="btn btn1 btn3" style="width: 60px" id="refuse" type="submit">拒绝</button>
        </div>
</div>


@stop

@section('foot')
    @parent
    <script>
        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;
            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };
        var index = getUrlParameter('index');
        if (index != null) {
            $('#course_id').val(index);
        }
    </script>
<script>
    function show(data,edit_state, submit_state,check_state,index){
        //编辑状态
        if (edit_state == 1){
            $("#edit").text("预览");
            $('#preview').val("1");
        }
        else{
            $("#edit").text("编辑");
            $('#preview').val("0");
        }
        //提交状态
        if(submit_state == 1){//禁用状态
            $("#upload_txt").text("已上传");
            $('#fake_btn').attr("disabled","disabled").addClass('btn2');
            $("#file_path").attr("disabled","disabled");
            $("#submit").text("已提交");
        }
        else{
            $("#upload_txt").text("上传申请表");
            $("#file_path").removeAttr("disabled");
            $('#fake_btn').removeClass('btn2').removeAttr('disabled');
            $("#submit").text("提交");
        }
        //审核状态
        if (check_state == 2){
            //$('#check').removeAttr("disabled").removeClass("btn2").text("申请通过");
            $('#check').css('display','none');
            $('#confirm').css('display','block');
            $('#agree').text('接受录取').click(function(){
                window.location.href = '/agree/' + index;
            });
            $('#refuse').click(function(){
                window.location.href = '/refuse/' + index;
            });
            $('#remind').text("恭喜您被录取为进修学员，请确认接受录取，拒绝将无法参加进修");
            //$('#enroll').css('display','block');
        }else if(check_state == 1){
            $('#confirm').css('display','none');
            $('#check').removeAttr("disabled").removeClass("btn2").text("申请其他进修班").css({'font-size':'12px','display':'block'});
            $('#remind').text("很遗憾您的申请未通过审核，请继续关注下期及其他进修班");
        } else if (check_state == 0) {
            $('#confirm').css('display','none');
            $('#check').text("等待审核").css('display','block');
            $('#remind').text("感谢您的申请，申请结果公布日期：" + data[index]['course']['announcement_date']);
        } else if (check_state == 3) {//待确认调班
            $('#check').css('display','none');
            $('#confirm').css('display','block');
            $('#agree').text('接受调班').click(function(){
                window.location.href = '/agree/' + index;
            });
            $('#refuse').click(function(){
                window.location.href = '/refuse/' + index;
            });
            var url0 = "course/description/" + data[index]['course']['transfer_course_name_id'];
            $('#remind').html("恭喜您获得调班机会，由于申请较多，我们把您调班到以下班，请确认" + '\<br>'+'<a href='+url0+'>'+ "《"+data[index]['course']['transfer_course_name']+"》"+'<\a>');
        } else if (check_state == 4) {
            $('#check').css('display','none');
            $('#confirm').css('display','block');
            $('#agree').text('接受延期').click(function(){
                window.location.href = '/agree/' + index;
            });
            $('#refuse').click(function(){
                window.location.href = '/refuse/' + index;
            });
            var url = "course/description/" + data[index]['course']['postpone_course_name_id'];
            $('#remind').html("恭喜您获得延期机会，由于申请较多，我们把您延期到以下班，请确认"+'\<br>'+ '<a href='+url+'>'+"《"+data[index]['course']['postpone_course_name']+"》"+"\</a>");
        } else if (check_state == 20) {//拒绝通过
            $('#check').css('display','block').text("已拒绝");
            $('#confirm').css('display','none');
            $('#remind').text("非常遗憾您不能来我院进修，欢迎您申请其它进修班");
        } else if (check_state == 21) {//同意通过
            $('#check').css('display','block').text("已录取");
            $('#confirm').css('display','none');
            $('#remind').text("欢迎您到我院进修，请下载《进修通知及报道须知》，按照要求准时报道");
            $('#enroll').css('display','block');
        } else if (check_state == 30) {
            $('#check').css('display','block').text("已拒绝");
            $('#confirm').css('display','none');
            $('#remind').text("非常遗憾您不能来我院进修，欢迎您申请其它进修班");
        } else if (check_state == 31) {
            $('#check').css('display','block').text("已录取");
            $('#confirm').css('display','none');
            $('#remind').text("欢迎您到我院进修，请下载《进修通知及报道须知》，按照要求准时报道");
            $('#enroll').css('display','block');
        } else if (check_state == 40) {
            $('#check').css('display','block').text("已拒绝");
            $('#confirm').css('display','none');
            $('#remind').text("非常遗憾您不能来我院进修，欢迎您申请其它进修班");
        } else if (check_state == 41) {
            $('#check').css('display','block').text("已录取");
            $('#confirm').css('display','none');
            $('#remind').text("欢迎您到我院进修，请下载《进修通知及报道须知》，按照要求准时报道");
            $('#enroll').css('display','block');
        }
    }
    $(document).ready(function(){
        $('.footer').css("position","relative");
    });
    var data = {!! json_encode($data) !!};//数组传播
    console.log(data);
    //initial
    var course_select = $('#course_id');
    var index = course_select.val();
    var edit_state = data[index]['edit_state'];
    var submit_state = data[index]['submit_state'];
    var check_state =data[index]['check_state'];
    show(data,edit_state, submit_state, check_state,index);
    $('#detail').attr('href','/course/description/' + data[index]['course']['id']);
    $('#form1').attr('action','upload/'+index);
    $('#enroll').attr('href','/download_enrollment/'+index);
    $('#application').attr('href','/download_application/'+index);
    $('#edit_form').attr('action','edit/'+data[index]['course']['id']);
    course_select.change(function(){
        index = course_select.val();
        edit_state = data[index]['edit_state'];
        submit_state = data[index]['submit_state'];
        check_state =data[index]['check_state'];
        $("#check").css('font-size','16px').attr('disabled','disabled').addClass('btn2');
        $('#enroll').attr('href','/download_enrollment/'+index).css('display','none');
       show(data,edit_state, submit_state, check_state,index);
        $('#detail').attr('href','/course/description/' + data[index]['course']['id']);
        $('#form1').attr('action','upload/'+index);
        $('#application').attr('href','/download_application/'+index);
        $('#edit_form').attr('action','edit/'+data[index]['course']['id']);
    });
    $('#file_path').change(function(){
        if($('#file_path').val()!=""){
            $("#submit").removeClass("btn2").removeAttr("disabled");
        }
    });



function check() {
    return (confirm("确认提交表单吗？"));
}
</script>
    <script>
        /*
         * 判断图片类型
         *
         * @param ths
         *          type="file"的javascript对象
         * @return true-符合要求,false-不符合
         */
        function checkImgType(ths){
            $("#display").val(ths.files[0].name);
            console.log(ths.files[0].name);
            if (ths.value == "") {
                alert("请上传文件");
                return false;
            } else {
                if (!/\.(jpg|jpeg|png|JPG|PNG|bmp|pdf|PDF|doc|docx)$/.test(ths.value)) {
                    alert("文件类型必须是jpeg,jpg,png,bmp,pdf,doc,docx中的一种");
                    ths.value = "";
                    return false;
                }
                else
                {
                    var size = ths.files[0].size/1024;
                    if(size>0){
                        if(size>40*1024){
                            alert("图片不能大于40M");
                            ths.value = "";
                            return false;
                        }
                    }
                }
            }
            return true;
        }
    </script>

@stop