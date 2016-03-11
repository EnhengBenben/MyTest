@extends('layouts.master')

@section('head')
    @parent
    <link href="/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@stop

@section('content')
            
<div style="margin-right: -15px">
    <div class="img hidden-print" style="background-image: url(/header.png);height: 130px;margin-left: -15px">


    </div>
    <form class="form-horizontal" action="/information" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PUT"/>
        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
        <input type="hidden" name="id" value={{$application->id}} />
    <div style="margin-left: -15px">

        <p class="text" style="margin: 16px 0 10px 38px">进修班</p>
        <img src="/line1.png">
    </div>
    <div>
        <select required="" id="course" name="class_id" style="width: 871px;height:30px;margin-top:15px;margin-left:-7px " >
            <option value="">请选择进修班</option>
            @foreach($courses as $course)
                <option value="{{$course->id}}">{{$course->name}}</option>
            @endforeach
        </select>
    </div>

    <div style="margin-left: -15px">
        <p class="text" style="margin: 16px 0 10px 38px">基本信息</p>
        <img src="/line1.png">
    </div>
    <div class="table-box">
        <table id="table0" cellpadding="0" cellspacing="0" class="table table-bordered" style="font-size: 12px;margin-left: -8px;table-layout: fixed">
            <tr>
                <td width="100px">姓名</td>
                <td ><input required="" class="validate[required]" id="oce_info_name" name="name" size="20" type="text" value="{{$application->name}}"/></td>
                <td width="150">性别</td>
                <td width="100"><select required="" id="gender" name="gender">
                        <option value="">请选择</option>
                        <option value="1">男</option>
                        <option value="2">女</option>
                    </select>
                </td>
                <td width="55">民族</td>
                <td><input required="" class="validate[required]" id="oce_info_race" name="nation" size="15" type="text" value="{{$application->nation}}"/></td>
                <td rowspan="4">
                    <img src='/photo/2015/{{$application->id}}' style="width: 66px;height: auto;margin-bottom: 10px">
                    <input class="hidden-print text-input" id="photo" name="photo" size="10" type="file" value="{{\Input::old('photo')}}"/><br/>
                </td>
            </tr>

            <tr>
                <td style="word-wrap: break-word" width="150px">出生日期</td>
                <td>
                    <span class="calendar-icon"></span>
                    <input required="" class="datepickers" id="birthday" name="birthday"  size="20" style="cursor:pointer" type="text" value="{{$application->birthday}}"/>

                </td>
                <td>手机号码</td>
                <td colspan="3"><input required="" class="validate[required]" id="oce_info_idno" name="phone_number" size="25" type="text" value="{{$application->phone_number}}" readonly/></td>
            </tr>
            <tr>
                <td >籍贯</td>
                <td><input required="" class="validate[required]" id="ooce_info_nativeplace" name="birthplace" size="20" type="text" value="{{$application->birthplace}}"/></td>
                <td>电子邮箱</td>
                <td colspan="3"><input required="" class="validate[required]" id="email" name="email" size="25" type="text" value="{{$application->email}}"/></td>
            </tr>
            <tr>
                <td style="word-wrap: break-word">身份证号码</td>
                <td colspan="5">
                    <input required="" class="validate[required]" id="id_num" name="id_no" size="45" type="text" value="{{$application->id_no}}" />
                </td>
            </tr>
            <tr>
                <td width="200px">家属姓名</td>
                <td>
                    <input required="" id="relation" name="relation" type="text" value="{{$relation->name}}"/>
                </td>
                <td>与本人关系</td>
                <td>
                    <input required="" id="relationship" name="relationship" type="text" size="11" value="{{$relation->relationship}}"/>
                </td>
                <td colspan="2">联系电话</td>
                <td >
                    <input required="" id="relation_phone" name="relation_phone" type="text" size="21" value="{{$relation->phone_number}}"/>
                </td>
            </tr>
            <tr>
                <td width="200px">单位主管姓名</td>
                <td>
                    <input required="" id="director" name="director" type="text" value="{{$director->name}}"/>
                </td>
                <td>主管职务</td>
                <td>
                    <input required="" id="director_duty" name="director_duty" type="text" size="11" value="{{$director->duty}}"/>
                </td>
                <td colspan="2">联系电话</td>
                <td >
                    <input required="" id="director_phone" name="director_phone" type="text" size="21" value="{{$director->phone_number}}"/>
                </td>
            </tr>
        </table>
    </div>

    <div >
    <div style="margin-left: -15px">
        <p class="text" style="margin: 16px 0 10px 38px">工作信息</p>
        <img src="/line1.png">
    </div>
    <table id="table1" cellpadding="0" cellspacing="0" class="table table-bordered" style="font-size: 12px;margin-left: -8px">
        <tr>
            <td width="250px">技术职称</td>
            <td width="120">
                <select required="" id="tech_duty_id" name="tech_duty_id" style="width: 120px">
                    <option value="">请选择</option>
                    @foreach($tech_duty as $tech)
                        <option value="{{$tech->id}}">{{$tech->name}}</option>
                    @endforeach
                </select>
            </td>
            <td width="120">行政职务</td>
            <td width="120">
                <select required="" id="admin_duty_id" name="admin_duty_id" style="width: 120px">
                    <option value="">请选择</option>
                    @foreach($admin_duty as $admin){
                        <option value="{{$admin->id}}">{{$admin->name}}</option>
                    }
                    @endforeach
                </select>
            </td>
            <td >学历</td>
            <td>
                <select required="" id="degree_id" name="degree_id" style="width: 120px">
                    <option value="">请选择</option>
                    @foreach($degrees as $degree)
                        <option value="{{$degree->id}}">{{$degree->name}}</option>
                    @endforeach
                </select>
            </td>
        </tr>

        <tr>
            <td width="150px">工作单位</td>
            <td colspan="3">
                <input required="" class="validate[required]" id="worksite" name="organization" size="60" style="cursor:pointer" type="text" value="{{$application->organization}}"/>
            </td>
            <td>单位级别</td>
            <td >
                <select required="" id="org_rank_id" name="org_rank_id" style="width: 120px">
                    <option value="">请选择</option>
                    @foreach($org_ranks as $org_rank)
                        <option value="{{$org_rank->id}}">{{$org_rank->name}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td>医师资格证书编码</td>
            <td colspan="2">
                <input required="" class="validate[required]" id="id" name="certificate_id" size="40" style="cursor:pointer" type="text" value="{{$application->certificate_id}}"/>
            </td>
            <td>从事专业</td>
            <td id="speciality" colspan="2">
                <select required="" id="major" name="speciality" style="width: 255px">
                    <option value="">请选择</option>
                    <option value="口腔综合">口腔综合</option>
                    <option value="口腔内科">口腔内科</option>
                    <option value="口腔修复">口腔修复</option>
                    <option value="口腔颌面外科">口腔颌面外科</option>
                    <option value="口腔正畸">口腔正畸</option>
                    <option value="口腔种植">口腔种植</option>
                    <option value="颌面影响诊断">颌面影响诊断</option>
                    <option value="口腔组织病理">口腔组织病理</option>
                    <option value="口腔修复技术">口腔修复技术</option>
                    <option value="口腔护理">口腔护理</option>
                    <option value="医院感染管理">医院感染管理</option>
                    <option value="其它">其它</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>单位所在地域</td>
            <td colspan="2">
                <select required="" id="region_id" name="region_id" style="width: 255px">
                    <option value="">请选择</option>
                    @foreach($regions as $region)
                        <option value="{{$region->id}}">{{$region->name}}</option>
                    @endforeach
                </select>
            </td>
            <td>邮政编码</td>
            <td colspan="2">
            <input required="" class="validate[required]" id="zip_code" name="zip_code" size="40" type="text" value="{{$application->zip_code}}"/>
            </td>
        </tr>
        <tr>
            <td >单位通讯地址</td>
            <td colspan="5"><input required="" class="validate[required]" id="address" name="address" size="60" type="text" value="{{$application->address}}"/></td>
        </tr>
        <tr>
            <td id="exp" rowspan="2" style="font-size: 15px">工作经历<br>
                <label onclick="addRows()" class="text3 hidden-print">添加行<span class="fa fa-plus-circle"></span></label><br>
                <label onclick="delRows()" class="text4 hidden-print">删除行<span class="fa fa-minus-circle"></span></label>
            </td>
            <td width="180">开始时间</td>
            <td width="180">结束时间</td>
            <td colspan="2">工作单位</td>
            <td >职务</td>
        </tr>
        <tr id="a7">
            <td>
                <input required="" class="datepickers" name="resume[0][start_date]" size="20" type="text" value="{{\Input::old('resume[start_date][0]')}}"/>
            </td>
            <td>
                <input required="" class="datepickers" name="resume[0][end_date]" size="20" type="text" value="{{\Input::old('resume[end_date][0]')}}"/>
            </td>
            <td colspan="2">
                <input required="" class="" name="resume[0][organization]" size="25" type="text" value="{{\Input::old('resume[organization][0]')}}"/>
            </td>
            <td>
                <input required="" class="validate[required]" name="resume[0][title]" size="20" type="text" value="{{\Input::old('resume[title][0]')}}"/>
            </td>
        </tr>

    </table>
        </div>

    <div style="margin-left: -15px">
        <p class="text" style="margin: 16px 0 10px 38px">教育背景</p>
        <img src="/line1.png">
    </div>
    <table id="table2" cellpadding="0" cellspacing="0" class="table table-bordered" style="font-size: 12px;margin-left: -8px">
        <tr>
            <td width="100px" rowspan="2" id="degr" style="font-size: 15px">学历<br>
                <label onclick="addRows_withInsert()" class="text3 hidden-print">添加行<span class="fa fa-plus-circle"></span></label><br>
                <label onclick="delRows2()" class="text4 hidden-print">删除行<span class="fa fa-minus-circle"></span></label>
            </td>
            <td width="80px">入学时间</td>
            <td width="80px">毕业时间</td>
            <td width="100">毕业院校</td>
            <td width="80">专业</td>
            <td width="80">学位</td>
        </tr>

        <tr id="b1">
            <td>
                <input required="" class="datepickers" id="graduate" name='education[0][start_date]' size="24" type="text" />
            </td>
            <td>
                <input required="" class="datepickers" id="graduate" name='education[0][graduate_date]' size="24" type="text" />
            </td>
            <td>
                <input required="" class="validate[required]" id="oce_info_birthday" name='education[0][school]' size="25" style="cursor:pointer" type="text" />
            </td>
            <td>
                <input required="" class="validate[required]" id="oce_info_birthday" name='education[0][major]' size="15" style="cursor:pointer" type="text" />
            </td>
            <td ><input required="" class="validate[required]" id="oce_info_idno" name='education[0][degree]' size="15" type="text" /></td>
        </tr>
        <tr>
            <td rowspan="2" id="research">
                主要论文/<br>科研情况<br>
                <label onclick="addRows_withInsert3()" class="text3 hidden-print">添加行<span class="fa fa-plus-circle"></span></label><br>
                <label onclick="delRows3()" class="text4 hidden-print">删除行<span class="fa fa-minus-circle"></span></label>
            </td>
            <td width="180" colspan="2">论文标题</td>
            <td width="180">发表时间</td>
            <td width="180" colspan="2">发表刊物</td>
        </tr>
        <tr id="c1">
            <td colspan="2">
                <input name="paper[0][name]" id="essay" size="55" type="text">
            </td>
            <td>
                <input class="datepickers" name="paper[0][publish_date]" id="del_time" size="25" type="text">
            </td>
            <td colspan="2">
                <input name="paper[0][journal]" id="magazine" size="35" type="text">
            </td>
        </tr>
    </table>
        <table id="table2" cellpadding="0" cellspacing="0" class="table table-bordered" style="font-size: 12px;margin-left: -8px;margin-top: -21px;">
            <tr>
                <td width="82">预定住宿</td>
                <td>
                    <select required="" id="accommodation" name="accommodation">
                        <option value="">请选择</option>
                        <option value="1">需要</option>
                        <option value="0">不需要</option>
                    </select>
                </td>
                <td>
                    说明：介绍入住距离口腔医学院一站地的魏公村小区内正欣宾馆，4人间，每月1150元
                </td>
            </tr>
        </table>
    <div class="btn-tri">
            <button class="btn hidden-print" style="margin-right:30px" type="button" onclick="window.location.href='/preview/'+{{$index}}">预览</button>
            <button class="btn hidden-print" id="oce_info_submit" name="commit" type="submit" >提交</button>
    </div>
    </form>
</div>


@stop

@section('foot')
    <script src="/assets/js/fckeditor.js"></script>
    @parent
    <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker-zh-cn.min.js"></script>
    <script>
        $(function(){
            //course selected by index $('#course').get(0).selectedIndex = '';
            $('#course').val({{$index}});
            //gender
            $('#gender').val({{$application->gender}});
            $('#tech_duty_id').val({{$application->tech_duty_id}});
            $('#admin_duty_id').val({{$application->admin_duty_id}});
            $('#degree_id').val({{$application->degree_id}});
            $('#org_rank_id').val({{$application->org_rank_id}});
            $('#region_id').val({{$application->region_id}});
            $('#accommodation').val({{$application->accommodation}});

            var values = $.map($('#major option'), function(e) { return e.value; });//get all options
            var major = '{{$application->speciality}}';
            var major_state = $('#major');
            if ($.inArray(major, values) == -1 || major == "其它") {
                major_state.val('其它');
                major_state.css('width','90px');
                major_state.attr('name','other');
                td.append("<input type='text' id='other' required name='speciality' size='23' placeholder='请输入从事专业'>");
                $('#other').val(major);
            } else {
                major_state.val(major);
            }

            console.log(values);
            //resumes
            var resumes = {!! json_encode($resumes) !!};
            for(var i in resumes){
                var id = parseInt(i) + 7;
                if (i > 0){
                    addRows();
                }
                var inputs1 = $("#a"+id+" td input");
                inputs1[0].value = resumes[i]['start_date'];
                inputs1[1].value = resumes[i]['end_date'];
                inputs1[2].value = resumes[i]['organization'];
                inputs1[3].value = resumes[i]['title'];
            }
            //education
            var educations = {!! json_encode($educations) !!};
            for(var k in educations){
                 id = parseInt(k) + 1;
                if (k > 0){
                    addRows_withInsert();
                }
                var inputs = $("#b"+id+" td input");
                inputs[0].value = educations[k]['start_date'];
                inputs[1].value = educations[k]['graduation_date'];
                inputs[2].value = educations[k]['school'];
                inputs[3].value = educations[k]['major'];
                inputs[4].value = educations[k]['degree'];
            }
        //papers
            var papers = {!! json_encode($papers) !!};
            for(var j in papers){
                id = parseInt(j) + 1;
                if (j > 0){
                    addRows_withInsert3();
                }
                var inputs2 = $("#c"+id+" td input");
                inputs2[0].value = papers[j]['name'];
                inputs2[1].value = papers[j]['publish_date'];
                inputs2[2].value = papers[j]['journal'];
            }

        });
    </script>
    <script>
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
            input1.setAttribute("class","datepickers");
            input2.setAttribute("class","datepickers");
            input3.setAttribute("size","25");
            input1.setAttribute("required","");
            input2.setAttribute("required","");
            input3.setAttribute("required","");
            input4.setAttribute("required","");
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
            if (id > 7) {
                tbl.deleteRow(document.getElementById('a' + id).rowIndex);
                var exp = document.getElementById("exp");
                exp.rowSpan -= 1;
            }
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
            input0.setAttribute("required","");
            input1.setAttribute("required","");
            input2.setAttribute("required","");
            input3.setAttribute("required","");
            input4.setAttribute("required","");
            input1.setAttribute("class","datepickers");
            input0.setAttribute("class","datepickers");
            input0.setAttribute("size","24");
            input1.setAttribute("size","24");
            input2.setAttribute("size","25");
            input3.setAttribute("size","15");
            input4.setAttribute("size","15");
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
            if (id > 2) {
                tbl.deleteRow(document.getElementById('b' + (id - 1)).rowIndex);
                td.rowSpan -= 1;
            }
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
            if (id > 2) {
                tbl.deleteRow(document.getElementById('c' + (id - 1)).rowIndex);
                td.rowSpan -= 1;
            }
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
        var major = $('#major');
        var td = $('#speciality');
        major.change(function(){
            $('#other').remove();
            major.css('width','255px');
            major.attr('name','speciality');
            if (major.val() == '其它') {
                major.css('width','90px');
                major.attr('name','other');
                td.append("<input type='text' id='other' required name='speciality' size='23' placeholder='请输入从事专业'>");
            }
        });
        $('select').css('height','23px');
        $('#course').css('height','30px');
    </script>
@stop