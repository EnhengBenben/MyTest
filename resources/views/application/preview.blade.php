@extends('layouts.master')

@section('head')
    @parent
    <link href="/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@stop

@section('content')
            
<div style="margin-right: -15px">
    <p style="text-align: center;font-size: 22px;margin-bottom: 30px;margin-top: 60px;"><strong>进修申请表</strong></p>
    <div class="table-box">
        <table id="table0" cellpadding="0" cellspacing="0" class="table table-bordered" style="font-size: 14px;margin-left: -8px">
            <tr>
                <td class="font_preview" style="width: 150px;height: auto">姓名</td>
                <td style="width: 150px;">{{$application->name}}</td>
                <td class="font_preview" width="150">性别</td>
                <td width="100">
                    {{$gender}}
                </td>
                <td class="font_preview" width="100px">民族</td>
                <td width="50px">{{$application->nation}}</td>
                <td rowspan="4" style="width: 120px;text-align: center;vertical-align: middle">
                    <img src="/photo/2015/{{$application->id}}" style="width: 100px;height: auto">
                </td>
            </tr>

            <tr>
                <td class="font_preview" style="word-wrap: break-word" width="150px">出生日期</td>
                <td>
                    {{$application->birthday}}
                </td>
                <td class="font_preview">手机号码</td>
                <td colspan="3" width="250px">{{$application->phone_number}}</td>
            </tr>
            <tr>
                <td class="font_preview">籍贯</td>
                <td>{{$application->birthplace}}</td>
                <td class="font_preview">电子邮箱</td>
                <td colspan="3">{{$application->email}}</td>
            </tr>
            <tr>
                <td class="font_preview" style="word-wrap: break-word">身份证号码</td>
                <td colspan="5">
                   {{$application->id_no}}
                </td>
            </tr>
        </table>
    </div>
    <div>
        <table cellpadding="0" cellspacing="0" class="table table-bordered" style="font-size: 14px;margin-left: -8px;margin-top:-21px;table-layout: fixed">
            <tr>
                <td class="font_preview" style="width: 159px;">家属姓名</td>
                <td style="width: 160px;">
                    {{$relation->name}}
                </td>
                <td class="font_preview" style="width: 159px">与本人关系</td>
                <td style="width: 143px;">
                    {{$relation->relationship}}
                </td>
                <td colspan="2" class="font_preview" style="width: 121px;">联系电话</td>
                <td >
                    {{$relation->phone_number}}
                </td>
            </tr>
            <tr>
                <td class="font_preview">单位主管姓名</td>
                <td>
                    {{$director->name}}
                </td>
                <td class="font_preview">主管职务</td>
                <td>
                    {{$director->duty}}
                </td>
                <td colspan="2" class="font_preview">联系电话</td>
                <td >
                    {{$director->phone_number}}
                </td>
            </tr>
        </table>
    </div>
    <div >
    <table id="table1" cellpadding="0" cellspacing="0" class="table table-bordered" style="font-size: 14px;margin-left: -8px;margin-top:-21px;table-layout: fixed">
        <tr>
            <td class="font_preview" style="width: 159px;">技术职称</td>
            <td style="width: 160px;">
                {{$tech_duty->name}}
            </td>
            <td class="font_preview" style="width: 159px;">行政职务</td>
            <td style="width: 143px;">
                {{$admin_duty->name}}
            </td>
            <td class="font_preview" style="width: 121px">学历</td>
            <td style="width: 128px">
                {{$degree->name}}
            </td>
        </tr>

        <tr>
            <td class="font_preview" width="150px">工作单位</td>
            <td colspan="3">
                {{$application->organization}}
            </td>
            <td class="font_preview">单位级别</td>
            <td >
                {{$org_rank->name}}
            </td>
        </tr>
        <tr>
            <td class="font_preview">医师资格证书编码</td>
            <td colspan="2">
                {{$application->certificate_id}}
            </td>
            <td class="font_preview">从事专业</td>
            <td colspan="2">
                {{$application->speciality}}
            </td>
        </tr>
        <tr>
            <td class="font_preview">单位所在地域</td>
            <td colspan="2">
                {{$region->name}}
            </td>
            <td class="font_preview">邮政编码</td>
            <td colspan="2">
            {{$application->zip_code}}
            </td>
        </tr>
        <tr>
            <td class="font_preview">单位通讯地址</td>
            <td colspan="2">{{$application->address}}</td>
            <td class="font_preview">住宿需求</td>
            <td colspan="2">
                {{$accommodation}}
            </td>
        </tr>
        <tr>
            <td class="font_preview" id="exp" rowspan="2" >工作经历<br>
            </td>
            <td class="font_preview" width="180">开始时间</td>
            <td class="font_preview" width="180">结束时间</td>
            <td class="font_preview" colspan="2">工作单位</td>
            <td class="font_preview">职务</td>
        </tr>
        <tr id="a7">
            <td></td>
            <td></td>
            <td colspan="2"></td>
            <td></td>
        </tr>

    </table>
        </div>

    <table id="table2" cellpadding="0" cellspacing="0" class="table table-bordered" style="font-size: 14px;margin-left: -8px;margin-top: -21px;table-layout: fixed">
        <tr>
            <td class="font_preview" style="width: 159px" rowspan="2" id="degr" >学历
            </td>
            <td class="font_preview" style="width: 160px">入学时间</td>
            <td class="font_preview" style="width: 159px">毕业时间</td>
            <td class="font_preview" style="width: 142px">毕业院校</td>
            <td class="font_preview" style="width: 122px">专业</td>
            <td class="font_preview" style="width: 128px">学位</td>
        </tr>

        <tr id="b1">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td ></td>
        </tr>
        <tr>
            <td class="font_preview" rowspan="2" id="research">
                主要论文/<br>科研情况<br>

            </td>
            <td class="font_preview" width="180" colspan="2">论文标题</td>
            <td class="font_preview" width="180">发表时间</td>
            <td class="font_preview" width="180" colspan="2">发表刊物</td>
        </tr>
        <tr id="c1">
            <td colspan="2" style="height: 38px">

            </td>
            <td>

            </td>
            <td colspan="2">

            </td>
        </tr>

    </table>
    <table id="table3" cellpadding="0" cellspacing="0" class="table table-bordered" style="font-size: 14px;margin-left: -8px;margin-top: -21px;table-layout: fixed">
        <tr>
            <td class="font_preview" style="width: 159px">本人拟<br>参加的进修班</td>
            <td colspan="5" style="vertical-align: middle">{{$course->name}}</td>
        </tr>
        <tr>
            <td class="font_preview">选送单位意见</td>
            <td colspan="5" style="height: 120px"><p class="font_preview" style="margin:80px 0 10px 68%">(单位盖章)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日</td>
        </tr>
    </table>
    <div style="text-align: center;margin-bottom: 20px">
        <input type="text" style="display: none" value={{$flag}} id="print"/>
    <!--button class="btn hidden-print" style="margin-left: 30px;" onclick="window.print()" type="button" id="button">打印申请表</button-->
        </div>
</div>


@stop

@section('foot')
    <script src="/assets/js/fckeditor.js"></script>
    @parent
    <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker-zh-cn.min.js"></script>
    <script>
        $(function(){
            if($("#print").val()=="1"){
                $("#button").click();
            }
        });
    </script>
    <script>
        $(function(){
            //resumes
            var resumes = {!! json_encode($resumes) !!};
            for(var i in resumes){
                var id = parseInt(i) + 7;
                if (i > 0){
                    addRows();
                }
                var tds = $("#a"+id+" td");
                tds[0].innerText = resumes[i]['start_date'];
                tds[1].innerText = resumes[i]['end_date'];
                tds[2].innerText = resumes[i]['organization'];
                tds[3].innerText = resumes[i]['title'];
            }
            //education
            var educations = {!! json_encode($educations) !!};
            for(var k in educations){
                id = parseInt(k) + 1;
                if (k > 0){
                    addRows_withInsert();
                }
                tds = $("#b"+id+" td");
                tds[0].innerText = educations[k]['start_date'];
                tds[1].innerText = educations[k]['graduation_date'];
                tds[2].innerText = educations[k]['school'];
                tds[3].innerText = educations[k]['major'];
                tds[4].innerText = educations[k]['degree'];
            }
            //papers
            var papers = {!! json_encode($papers) !!};
            for(var j in papers){
                id = parseInt(j) + 1;
                if (j > 0){
                    addRows_withInsert3();
                }
                tds = $("#c"+id+" td");
                tds[0].innerText = papers[j]['name'];
                tds[1].innerText = papers[j]['publish_date'];
                tds[2].innerText = papers[j]['journal'];
            }
        });
        function addRows(){
            var tbl = document.getElementById("table1");
            var tr = document.createElement('tr');
            var id = tbl.rows.length +1;
            tr.id = 'a' + id;
            var td1 = document.createElement('td');
            var td2 = document.createElement('td');
            var td3 = document.createElement('td');
            var td4 = document.createElement('td');
            td3.colSpan = 2;
            var input1 = document.createElement('input');
            var input2 = document.createElement('input');
            var input3 = document.createElement('input');
            var input4 = document.createElement('input');
            input1.setAttribute("required","");
            input2.setAttribute("required","");
            input3.setAttribute("required","");
            input4.setAttribute("required","");
            input1.setAttribute("class","datepickers");
            input2.setAttribute("class","datepickers");
            input3.setAttribute("size","25");
            //give name to input
            input1.setAttribute("name","resume["+ (id - 7) +"][start_date]");
            input2.setAttribute("name","resume["+ (id - 7) +"][end_date]");
            input3.setAttribute("name","resume["+ (id - 7) +"][organization]");
            input4.setAttribute("name","resume["+ (id - 7) +"][title]");
            var exp = document.getElementById('exp');
            exp.rowSpan += 1;
            td1.appendChild(input1);
            td2.appendChild(input2);
            td3.appendChild(input3);
            td4.appendChild(input4);
            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            tr.appendChild(td4);
            var tbdy = tbl.lastChild;
            tbdy.appendChild(tr);
            $('.datepickers').datetimepicker({
                format: 'yyyy-mm-dd',
                language:'zh-CN',
                fontAwesome: true,
                startView: 4,
                autoclose:true,
                minView: 2
            });
        }
        function delRows(){
            var tbl = document.getElementById("table1");
            var id = tbl.rows.length;
            tbl.deleteRow(document.getElementById('a'+id).rowIndex);
            var exp = document.getElementById("exp");
            exp.rowSpan -= 1;
        }
        function addRows_withInsert(){
            var tbl = document.getElementById("table2");
            var td = document.getElementById("degr");
            var id = td.rowSpan;
            td.rowSpan += 1;
            var input0 = document.createElement('input');
            var input1 = document.createElement('input');
            var input2 = document.createElement('input');
            var input3 = document.createElement('input');
            var input4 = document.createElement('input');
            input1.setAttribute("class","datepickers");
            input0.setAttribute("class","datepickers");
            input0.setAttribute("size","24");
            input1.setAttribute("size","24");
            input2.setAttribute("size","25");
            input3.setAttribute("size","15");
            input4.setAttribute("size","15");
            input0.setAttribute("required","");
            input1.setAttribute("required","");
            input2.setAttribute("required","");
            input3.setAttribute("required","");
            input4.setAttribute("required","");
            //give name
            input0.setAttribute("name",'education['+(id-1)+'][start_date]');
            input1.setAttribute("name",'education['+(id-1)+'][graduate_date]');
            input2.setAttribute("name",'education['+(id-1)+'][school]');
            input3.setAttribute("name",'education['+(id-1)+'][major]');
            input4.setAttribute("name",'education['+(id-1)+'][degree]');
            var row = tbl.insertRow(id);
            row.id = 'b'+id;
            var td1 = row.insertCell(0);
            var td2 = row.insertCell(1);
            var td3 = row.insertCell(2);
            var td4 = row.insertCell(3);
            var td5 = row.insertCell(4);
            td1.appendChild(input0);
            td2.appendChild(input1);
            td3.appendChild(input2);
            td4.appendChild(input3);
            td5.appendChild(input4);
            $('.datepickers').datetimepicker({
                format: 'yyyy-mm-dd',
                language:'zh-CN',
                fontAwesome: true,
                startView: 4,
                autoclose:true,
                minView: 2

            });
        }

        function delRows2(){
            var tbl = document.getElementById("table2");
            var td = document.getElementById("degr");
            var id = td.rowSpan;
            tbl.deleteRow(document.getElementById('b'+(id-1)).rowIndex);
            td.rowSpan -= 1;
        }

        function addRows_withInsert3(){
            var tbl = document.getElementById("table2");
            var td = document.getElementById("research");
            var id = td.rowSpan;
            td.rowSpan += 1;
            var input1 = document.createElement('input');
            var input2 = document.createElement('input');
            var input3 = document.createElement('input');
            input1.setAttribute("size","55");
            input2.setAttribute("size","25");
            input2.setAttribute("class","datepickers");
            input3.setAttribute("size","35");
            //give name
            input1.setAttribute("name","paper["+ (id - 1) +"][name]");
            input2.setAttribute("name","paper["+ (id - 1) +"][publish_date]");
            input3.setAttribute("name","paper["+ (id - 1) +"][journal]");

            var index = tbl.rows.length;
            var row = tbl.insertRow(index);
            row.id = 'c'+id;
            var td1 = row.insertCell(0);
            var td2 = row.insertCell(1);
            var td3 = row.insertCell(2);
            td1.colSpan = 2;
            td3.colSpan = 2;
            td1.appendChild(input1);
            td2.appendChild(input2);
            td3.appendChild(input3);
            $('.datepickers').datetimepicker({
                format: 'yyyy-mm-dd',
                language:'zh-CN',
                fontAwesome: true,
                startView: 4,
                autoclose:true,
                minView: 2

            });
        }
        function delRows3(){
            var tbl = document.getElementById("table2");
            var td = document.getElementById("research");
            var id = td.rowSpan;
            tbl.deleteRow(document.getElementById('c'+(id-1)).rowIndex);
            td.rowSpan -= 1;
        }
    </script>

    <script>
        $('#birthday').datetimepicker({
            format: 'yyyy-mm-dd',
            language:'zh-CN',
            fontAwesome: true,
            startView: 4,
            autoclose:true,
            minView: 2,
            pickPosition: 'bottom-left'
        });
        $('.datepickers').datetimepicker({
            format: 'yyyy-mm-dd',
            language:'zh-CN',
            fontAwesome: true,
            startView: 4,
            autoclose:true,
            minView: 2

        });
        $(document).ready(function(){
            $('.footer').css("position","relative").addClass("hidden-print");

        });
    </script>
@stop