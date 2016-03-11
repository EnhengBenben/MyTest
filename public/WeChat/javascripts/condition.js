function upDatePage(id) {  //更新此页面跳转至详情页
    var xml = null;
    if (window.XMLHttpRequest) {
        xml = new XMLHttpRequest();
    }
    else {
        xml = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xml.open("put", "/wx/course/?course_id=" + id, true);
    xml.send();
    xml.onreadystatechange = function () {
        if (xml.readyState == 4)
            if (xml.status == 200) {
                console.log(JSON.parse(xml.responseText));//   接收返回值并跳转到详情页
                localStorage.setItem("detail", xml.responseText);
                window.location.href = "detail.html";
            }
    }
}
function goDetail(yn, id) {   //接受进修时更新此页面信息
    var xml = null;
    if (window.XMLHttpRequest) {
        xml = new XMLHttpRequest();
    }
    else {
        xml = new ActiveXObject("Microsoft.XMLHTTP");
    }

    if (yn == "y") {
        xml.open("put", "/wx/confirm_accept?result_id=" + id, true);
    }
    else {
        xml.open("put", "/wx/confirm_reject?result_id=" + id, true);
    }
    xml.send();
    xml.onreadystatechange = function () {
        if (xml.readyState == 4)
            if (xml.status == 200) {
                //location.replace(location.href);
                window.location.href = "condition.html";
            }
    }

}
var wxFooter = "北京积水潭医院";
$.get("/wx/footer",function(data){
    wxFooter = data;
});
console.log(wxFooter);

var xml = null;
if (window.XMLHttpRequest) {
    xml = new XMLHttpRequest();
}
else {
    xml = new ActiveXObject("Microsoft.XMLHTTP");
}
xml.open("put", "/wx/result?applier_id=" + localStorage.getItem("id"), true);
xml.send();
xml.onreadystatechange = function () {
    if (xml.readyState == 4)
        if (xml.status == 200) {
            if (xml.responseText == "[]") {
                //创建最外层div
                var bigBox = document.createElement("div");
                bigBox.className = "condition-content";
                bigBox.onclick = function () {
                    window.location.href = "http://mp.weixin.qq.com/s?__biz=MzA4MTQxOTYzMg==&mid=411340321&idx=1&sn=10539e2a0ee05d609d385b98b7f435bf&scene=0&previewkey=Nl2ygyg2Qj4tjmsjP3MhDcNS9bJajjJKzz%2F0By7ITJA%3D#wechat_redirect";   //待更改首页跳转地址*********************
                };
                document.body.appendChild(bigBox);
//创建头部标题div
                var contentBox = document.createElement("div");
                contentBox.className = "condition-title";
                bigBox.appendChild(contentBox);
//创建头部标题文字
                var h = document.createElement("h4");
                h.className = "title-font";
                h.innerHTML = "查询医院申请网站首页";
                contentBox.appendChild(h);
//创建头部图片按钮
                var image = document.createElement("img");
                image.className = "title-icon";
                image.src = "../images/icon-1@2x.png";
                contentBox.appendChild(image);
//创建内容详细信息
                var key = document.createElement("div");
                key.className = "key";
                bigBox.appendChild(key);
//创建详细记录的头
                var title = document.createElement("h2");
                title.className = "title-content";
                title.innerHTML = "没有申请记录";
                key.appendChild(title);
//创建没有申请记录的提示跳转文字
                var prompt = document.createElement("div");
                prompt.className = "content-box";
                key.appendChild(prompt);
                var promptFont = document.createElement("p");
                promptFont.className = "in-apply";
                promptFont.innerHTML = "欢迎您进入医院申请网站申请进修班";
                prompt.appendChild(promptFont);
//创建footer
                var fo = document.createElement("div");
                fo.className = "key-footer";
                key.appendChild(fo);
                var foFont = document.createElement("p");
                foFont.innerHTML = "医生进修申请服务";
                fo.appendChild(foFont);
            }
            else {
                var obj = JSON.parse(xml.responseText);
                //console.log(xml.responseText);
                console.log(obj);
                idd = [];
                courseidd = [];
                for (i = 0; i < obj.length; i++) {
                    idd[i] = obj[i].id;
                    courseidd[i] = obj[i].course_id;
                    if (obj[i].submitted_at == null) {       //未提交申请
                        //创建最外层div
                        var bigBox = document.createElement("div");
                        bigBox.className = "condition-content";
                        document.body.appendChild(bigBox);
//创建头部标题div
                        var contentBox = document.createElement("div");
                        contentBox.className = "condition-title";
                        contentBox.name = obj[i].course.id;
                        console.log(contentBox.name);
                        bigBox.appendChild(contentBox);
                        contentBox.onclick = function () {
                            upDatePage(this.name);
                        };
//创建头部标题文字
                        var h = document.createElement("h4");
                        h.className = "title-font";
                        h.innerHTML = obj[i].course.name;
                        contentBox.appendChild(h);
//创建头部图片按钮
                        var image = document.createElement("img");
                        image.className = "title-icon";
                        image.src = "../images/icon-1@2x.png";
                        contentBox.appendChild(image);
//创建内容详细信息
                        var key = document.createElement("div");
                        key.className = "key";
                        bigBox.appendChild(key);
//创建详细记录的头
                        var title = document.createElement("h2");
                        title.className = "title-content";
                        title.innerHTML = "未提交申请";
                        key.appendChild(title);
//创建没有申请记录的提示跳转文字
                        var prompt = document.createElement("div");
                        prompt.className = "content-box";
                        key.appendChild(prompt);
                        //申请截止日期
                        var submitData = document.createElement("p");
                        submitData.className = "until-date";
                        submitData.innerHTML = "截止日期：" + obj[i].rejected_at;
                        prompt.appendChild(submitData);
                        var promptFont = document.createElement("p");
                        promptFont.className = "in-apply";
                        promptFont.innerHTML = "请登录申请网站上传电子申请表";
                        prompt.appendChild(promptFont);
//创建footer
                        var fo = document.createElement("div");
                        fo.className = "key-footer";
                        key.appendChild(fo);
                        var foFont = document.createElement("p");
                        foFont.innerHTML = wxFooter;
                        fo.appendChild(foFont);
                    }
                    else if (obj[i].isannouncement == 0) {
                        //创建最外层div
                        var bigBox = document.createElement("div");
                        bigBox.className = "condition-content";
                        document.body.appendChild(bigBox);
//创建头部标题div
                        var contentBox = document.createElement("div");
                        contentBox.className = "condition-title";
                        contentBox.name = obj[i].course.id;
                        console.log(contentBox.name);
                        bigBox.appendChild(contentBox);
                        contentBox.onclick = function () {
                            upDatePage(this.name);
                        };
//创建头部标题文字
                        var h = document.createElement("h4");
                        h.className = "title-font";
                        h.innerHTML = obj[i].course.name;
                        contentBox.appendChild(h);
//创建头部图片按钮
                        var image = document.createElement("img");
                        image.className = "title-icon";
                        image.src = "../images/icon-1@2x.png";
                        contentBox.appendChild(image);
//创建内容详细信息
                        var key = document.createElement("div");
                        key.className = "key";
                        bigBox.appendChild(key);
//创建详细记录的头
                        var title = document.createElement("h2");
                        title.className = "title-content";
                        title.innerHTML = "您的申请正在审核";
                        key.appendChild(title);
//创建没有申请记录的提示跳转文字
                        var prompt = document.createElement("div");
                        prompt.className = "content-box";
                        key.appendChild(prompt);
                        //申请截止日期
                        var submitData = document.createElement("p");
                        submitData.className = "until-date";
                        submitData.innerHTML = "开榜日期：" + obj[i].course.announcement_date;
                        prompt.appendChild(submitData);

//创建footer
                        var fo = document.createElement("div");
                        fo.className = "key-footer";
                        key.appendChild(fo);
                        var foFont = document.createElement("p");
                        foFont.innerHTML = wxFooter;
                        fo.appendChild(foFont);

                    }
                    else if (obj[i].isannouncement == 1) {

                        if (obj[i].passed_at != null && obj[i].confirm == null) {//申请通过
                            //创建最外层div
                            var bigBox = document.createElement("div");
                            bigBox.className = "condition-content";
                            document.body.appendChild(bigBox);
//创建头部标题div
                            var contentBox = document.createElement("div");
                            contentBox.className = "condition-title";
                            contentBox.name = obj[i].course.id;
                            console.log(contentBox.name);
                            bigBox.appendChild(contentBox);
                            contentBox.onclick = function () {
                                upDatePage(this.name);
                            };
//创建头部标题文字
                            var h = document.createElement("h4");
                            h.className = "title-font";
                            h.innerHTML = obj[i].course.name;
                            contentBox.appendChild(h);
//创建头部图片按钮
                            var image = document.createElement("img");
                            image.className = "title-icon";
                            image.src = "../images/icon-1@2x.png";
                            contentBox.appendChild(image);
//创建内容详细信息
                            var key = document.createElement("div");
                            key.className = "key";
                            bigBox.appendChild(key);
//创建详细记录的头
                            var title = document.createElement("h2");
                            title.className = "title-content";
                            title.innerHTML = "申请已通过";
                            key.appendChild(title);

                            var prompt = document.createElement("div");
                            prompt.className = "study-content-box";
                            key.appendChild(prompt);

                            var promptFont = document.createElement("p");
                            promptFont.className = "until-date";
                            promptFont.innerHTML = "请尽快确认是否进修";
                            prompt.appendChild(promptFont);

                            var Noconfirm = document.createElement("div");
                            Noconfirm.className = "study study-no";
                            Noconfirm.innerHTML = "暂不进修";
                            Noconfirm.name = obj[i].id;
                            prompt.appendChild(Noconfirm);
                            Noconfirm.onclick = function () {
                                goDetail("n",this.name);

                            };


                            var black = document.createElement("div");
                            black.className = "black-model";
                            Noconfirm.appendChild(black);

                            var confirm = document.createElement("div");
                            confirm.className = "study study-yes";
                            confirm.innerHTML = "确认进修";
                            confirm.name = obj[i].id;
                            prompt.appendChild(confirm);
                            confirm.onclick = function () {
                                goDetail("y",this.name);

                            };

                            var yBlack = document.createElement("div");
                            yBlack.className = "black-model";
                            confirm.appendChild(yBlack);

                            var fo = document.createElement("div");
                            fo.className = "key-footer";
                            key.appendChild(fo);
                            var foFont = document.createElement("p");
                            foFont.innerHTML = wxFooter;
                            fo.appendChild(foFont);

                        } else if (obj[i].rejected_at != null && obj[i].confirm == null) {//申请未通过
                            var bigBox = document.createElement("div");
                            bigBox.className = "condition-content";
                            document.body.appendChild(bigBox);
//创建头部标题div
                            var contentBox = document.createElement("div");
                            contentBox.className = "condition-title";
                            contentBox.name = obj[i].course.id;
                            console.log(contentBox.name);
                            bigBox.appendChild(contentBox);
                            contentBox.onclick = function () {
                                upDatePage(this.name);
                            };
//创建头部标题文字
                            var h = document.createElement("h4");
                            h.className = "title-font";
                            h.innerHTML = obj[i].course.name;
                            contentBox.appendChild(h);
//创建头部图片按钮
                            var image = document.createElement("img");
                            image.className = "title-icon";
                            image.src = "../images/icon-1@2x.png";
                            contentBox.appendChild(image);
//创建内容详细信息
                            var key = document.createElement("div");
                            key.className = "key";
                            bigBox.appendChild(key);
//创建详细记录的头
                            var title = document.createElement("h2");
                            title.className = "title-content";
                            title.innerHTML = "申请未通过";
                            key.appendChild(title);

                            var prompt = document.createElement("div");
                            prompt.className = "study-content-box";
                            key.appendChild(prompt);

                            var promptFont = document.createElement("p");
                            promptFont.className = "until-date";
                            promptFont.innerHTML = "感谢您的申请，欢迎申请其他进修班，谢谢";
                            prompt.appendChild(promptFont);

                            var fo = document.createElement("div");
                            fo.className = "key-footer";
                            key.appendChild(fo);
                            var foFont = document.createElement("p");
                            foFont.innerHTML = wxFooter;
                            fo.appendChild(foFont);

                        } else if (obj[i].transfer_course_id != null && obj[i].confirm == null) {//调课
                            var bigBox = document.createElement("div");
                            bigBox.className = "condition-content";
                            document.body.appendChild(bigBox);
//创建头部标题div
                            var contentBox = document.createElement("div");
                            contentBox.className = "condition-title";
                            contentBox.name = obj[i].course.id;
                            console.log(contentBox.name);
                            bigBox.appendChild(contentBox);
                            contentBox.onclick = function () {
                                upDatePage(this.name);
                            };

//创建头部标题文字
                            var h = document.createElement("h4");
                            h.className = "title-font";
                            h.innerHTML = obj[i].course.name;
                            contentBox.appendChild(h);
//创建头部图片按钮
                            var image = document.createElement("img");
                            image.className = "title-icon";
                            image.src = "../images/icon-1@2x.png";
                            contentBox.appendChild(image);
//创建内容详细信息
                            var key = document.createElement("div");
                            key.className = "key";
                            bigBox.appendChild(key);
//创建详细记录的头
                            var title = document.createElement("h2");
                            title.className = "title-content";
                            title.innerHTML = "被调课到以下进修班";
                            key.appendChild(title);

                            var prompt = document.createElement("div");
                            prompt.className = "study-content-box";
                            key.appendChild(prompt);

                            var promptFont = document.createElement("p");
                            promptFont.className = "until-date";
                            promptFont.innerHTML = obj[i].transfer_course.name;
                            prompt.appendChild(promptFont);

                            var Noconfirm = document.createElement("div");
                            Noconfirm.className = "study study-no";
                            Noconfirm.innerHTML = "拒绝调班";
                            Noconfirm.name = obj[i].id;
                            prompt.appendChild(Noconfirm);
                            Noconfirm.onclick = function () {
                                goDetail("n",this.name);

                            };

                            var black = document.createElement("div");
                            black.className = "black-model";
                            Noconfirm.appendChild(black);

                            var confirm = document.createElement("div");
                            confirm.className = "study study-yes";
                            confirm.innerHTML = "同意调班";
                            confirm.name = obj[i].id;
                            prompt.appendChild(confirm);
                            confirm.onclick = function () {
                                goDetail("y",this.name);

                            };

                            var yBlack = document.createElement("div");
                            yBlack.className = "black-model";
                            confirm.appendChild(yBlack);

                            var fo = document.createElement("div");
                            fo.className = "key-footer";
                            key.appendChild(fo);
                            var foFont = document.createElement("p");
                            foFont.innerHTML = wxFooter;
                            fo.appendChild(foFont);

                        }/* else if (obj[i].postpone_course_id != null && obj[i].confirm == null) {//延期
                            var bigBox = document.createElement("div");
                            bigBox.className = "condition-content";
                            document.body.appendChild(bigBox);
//创建头部标题div
                            var contentBox = document.createElement("div");
                            contentBox.className = "condition-title";
                            contentBox.name = obj[i].course.id;
                            console.log(contentBox.name);
                            bigBox.appendChild(contentBox);
                            contentBox.onclick = function () {
                                upDatePage(this.name);
                            };

//创建头部标题文字
                            var h = document.createElement("h4");
                            h.className = "title-font";
                            h.innerHTML = obj[i].course.name;
                            contentBox.appendChild(h);
//创建头部图片按钮
                            var image = document.createElement("img");
                            image.className = "title-icon";
                            image.src = "../images/icon-1@2x.png";
                            contentBox.appendChild(image);
//创建内容详细信息
                            var key = document.createElement("div");
                            key.className = "key";
                            bigBox.appendChild(key);
//创建详细记录的头
                            var title = document.createElement("h2");
                            title.className = "title-content";
                            title.innerHTML = "被延期到以下进修班";
                            key.appendChild(title);

                            var prompt = document.createElement("div");
                            prompt.className = "study-content-box";
                            key.appendChild(prompt);

                            var promptFont = document.createElement("p");
                            promptFont.className = "until-date";
                            promptFont.innerHTML = obj[i].postpone_course.name;
                            prompt.appendChild(promptFont);

                            var Noconfirm = document.createElement("div");
                            Noconfirm.className = "study study-no";
                            Noconfirm.innerHTML = "拒绝延期";
                            Noconfirm.name = obj[i].id;
                            prompt.appendChild(Noconfirm);
                            Noconfirm.onclick = function () {
                                goDetail("n",this.name);

                            };

                            var black = document.createElement("div");
                            black.className = "black-model";
                            Noconfirm.appendChild(black);

                            var confirm = document.createElement("div");
                            confirm.className = "study study-yes";
                            confirm.innerHTML = "同意延期";
                            confirm.name = obj[i].id;
                            prompt.appendChild(confirm);
                            confirm.onclick = function () {
                                goDetail("y",this.name);

                            };

                            var yBlack = document.createElement("div");
                            yBlack.className = "black-model";
                            confirm.appendChild(yBlack);

                            var fo = document.createElement("div");
                            fo.className = "key-footer";
                            key.appendChild(fo);
                            var foFont = document.createElement("p");
                            foFont.innerHTML = "北京积水潭医院";
                            fo.appendChild(foFont);

                        }*/ else if (obj[i].confirm == 0) {
                            var bigBox = document.createElement("div");
                            bigBox.className = "condition-content";
                            document.body.appendChild(bigBox);
//创建头部标题div
                            var contentBox = document.createElement("div");
                            contentBox.className = "condition-title";
                            if (obj[i].transfer_course_id == null){
                                contentBox.name = obj[i].course.id;
                            }else {
                                contentBox.name = obj[i].transfer_course.id;
                            }
                            console.log(contentBox.name);
                            bigBox.appendChild(contentBox);
                            contentBox.onclick = function () {
                                upDatePage(this.name);
                            };
//创建头部标题文字
                            var h = document.createElement("h4");
                            h.className = "title-font";
                            if (obj[i].transfer_course_id == null){
                                h.innerHTML = obj[i].course.name;
                            }else {
                                h.innerHTML = obj[i].transfer_course.name;
                            }

                            contentBox.appendChild(h);
//创建头部图片按钮
                            var image = document.createElement("img");
                            image.className = "title-icon";
                            image.src = "../images/icon-1@2x.png";
                            contentBox.appendChild(image);
//创建内容详细信息
                            var key = document.createElement("div");
                            key.className = "key";
                            bigBox.appendChild(key);
//创建详细记录的头
                            var title = document.createElement("h2");
                            title.className = "title-content";
                            title.innerHTML = "您已拒绝进修";
                            key.appendChild(title);

                            var prompt = document.createElement("div");
                            prompt.className = "study-content-box";
                            key.appendChild(prompt);

                            var promptFont = document.createElement("p");
                            promptFont.className = "until-date";
                            promptFont.innerHTML = "感谢您的申请，欢迎申请其他进修班，谢谢";
                            prompt.appendChild(promptFont);

                            var fo = document.createElement("div");
                            fo.className = "key-footer";
                            key.appendChild(fo);
                            var foFont = document.createElement("p");
                            foFont.innerHTML = wxFooter;
                            fo.appendChild(foFont);
                        } else if (obj[i].confirm == 1) {
                            //创建最外层div
                            var bigBox = document.createElement("div");
                            bigBox.className = "condition-content";
                            document.body.appendChild(bigBox);
//创建头部标题div
                            var contentBox = document.createElement("div");
                            contentBox.className = "condition-title";
                            if (obj[i].transfer_course_id == null){
                                contentBox.name = obj[i].course.id;
                            }else {
                                contentBox.name = obj[i].transfer_course.id;
                            }
                            console.log(contentBox.name);
                            bigBox.appendChild(contentBox);
                            contentBox.onclick = function () {
                                upDatePage(this.name);
                            };
//创建头部标题文字
                            var h = document.createElement("h4");
                            h.className = "title-font";
                            if (obj[i].transfer_course_id == null){
                                h.innerHTML = obj[i].course.name;
                            }else {
                                h.innerHTML = obj[i].transfer_course.name;
                            }
                            contentBox.appendChild(h);
//创建头部图片按钮
                            var image = document.createElement("img");
                            image.className = "title-icon";
                            image.src = "../images/icon-1@2x.png";
                            contentBox.appendChild(image);
//创建内容详细信息
                            var key = document.createElement("div");
                            key.className = "key";
                            bigBox.appendChild(key);
//创建详细记录的头
                            var title = document.createElement("h2");
                            title.className = "title-content";
                            title.innerHTML = "您已确认进修";
                            key.appendChild(title);
//创建没有申请记录的提示跳转文字
                            var prompt = document.createElement("div");
                            prompt.className = "content-box";
                            key.appendChild(prompt);
                            //申请截止日期
                            var submitData = document.createElement("p");
                            submitData.className = "until-date";
                            submitData.innerHTML = "报道日期：" + obj[i].course.enrollment_date;
                            prompt.appendChild(submitData);
                            var promptFont = document.createElement("p");
                            promptFont.className = "until-date";
                            promptFont.innerHTML = "结业日期：" + obj[i].course.graduation_date;
                            prompt.appendChild(promptFont);
//创建footer
                            var fo = document.createElement("div");
                            fo.className = "key-footer";
                            key.appendChild(fo);
                            var foFont = document.createElement("p");
                            foFont.innerHTML = wxFooter;
                            fo.appendChild(foFont);

                        }
                    }
                }
            }
            var pageFoot = document.createElement("div");
            pageFoot.className = "apply-footer";
            pageFoot.style.color = "#FFF";
            document.body.appendChild(pageFoot);
            if(JSON.parse(xml.responseText).length > 1){
                pageFoot.style.position = "relative";
            }else {
                pageFoot.style.position = "absolute";
                pageFoot.style.bottom = "40px";
            }
            console.log(window.screen.height);

            if(parseInt(window.screen.width) <= 320){
                console.log(111);
                pageFoot.removeAttribute("top");
            }
            var page1 = document.createElement("p");
            page1.innerHTML = "北京丛林网络技术有限责任公司";
            pageFoot.appendChild(page1);
            var page2 = document.createElement("p");
            page2.innerHTML = "服务电话: 4000687626 服务邮件: service@conglinnet.com";
            pageFoot.appendChild(page2);
        }
};

