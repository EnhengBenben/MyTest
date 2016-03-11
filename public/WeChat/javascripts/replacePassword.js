
var tel = document.getElementById("tel");
var password = document.getElementById("password");
var code = document.getElementById("code");
function relativeFoot(){
    var foot = document.getElementsByClassName("apply-footer")[0];
    foot.style.position = "relative";
}
var wait = 60;
function time(o) {
    if (wait == 0) {
        o.removeAttribute("disabled");
       // o.removeAttribute("backgroundColor");
        o.style.backgroundColor = "#29bc98";
        o.innerHTML = "获取验证码";
        wait = 60;
    } else {
         o.setAttribute("disabled", true);
        o.style.backgroundColor = "gray";
        //o.setAttribute("backgroundColor","gray");
       // o.style.backgroundColor = "gray";
        o.innerHTML = "重新发送(" + wait + ")";
         wait--;
         setTimeout(function () {
         time(o)
         },
         1000)
    }
}
function getCode() {
    if (tel.value != "") {
        var reg = /^[0-9]{11}$/;
        if (tel.value.length != 11 || !reg.test(tel.value) || tel.value[0] != "1" || (tel.value[1] != "3" && tel.value[1] != "5" && tel.value[1] != "4" &&
            tel.value[1] != "7" && tel.value[1] != "8")) {
            alert("请输入正确的手机号");
            return false;
        }
    }
    else {
        alert("请输入手机号");
        return false;
    }
    //获取验证码
    var xml = null;
    if (window.XMLHttpRequest) {
        xml = new XMLHttpRequest();
    }
    else {
        xml = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xml.open("post", "/wx/code_change?phone_number=" + tel.value, true);
    xml.send();
    xml.onreadystatechange = function () {
        if (xml.readyState == 4)
            if (xml.status == 200) {
                    var bt =  document.getElementById("btn");
                    time(bt);
            }
            else {

                alert("获取失败");
                return false;
            }
    };

}
function login() {
   // e.preventDefault();
    var wait = 2;
    var name = document.getElementById("submit-button");
    function time(o) {
        if (wait == 0) {
            o.children[0].style.opacity = "0";
            // o.removeAttribute("backgroundColor");
            wait = 2;
        } else {
            o.children[0].style.opacity = "0.3";
            //o.setAttribute("backgroundColor","gray");
            // o.style.backgroundColor = "gray";
            console.log(wait);
            wait--;
            setTimeout(function () {
                    time(o)
                },
                100)
        }
    }
    time(name);
    // var inputVal = document.getElementsByClassName("_input");
    console.log(tel.value);
    console.log(password.value);
    if (tel.value != "") {
        var reg = /^[0-9]{11}$/;
        if (tel.value.length != 11 || !reg.test(tel.value) || tel.value[0] != "1" || (tel.value[1] != "3" && tel.value[1] != "5" && tel.value[1] != "4" &&
            tel.value[1] != "7" && tel.value[1] != "8")) {
            alert("请输入正确的手机号");
            return false;
        }
    }
    else {
        alert("手机号为空，请输入手机号！");
        return false;
    }
    if (password.value == "") {
        alert("密码不能为空");
        return false;
    }
    if (code.value == "") {
        alert("请输入验证码");
        return false;
    }
    //发送修改信息
    var xml = null;
    if (window.XMLHttpRequest) {
        xml = new XMLHttpRequest();
    }
    else {
        xml = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xml.open("post", "/wx/change?phone_number=" + tel.value + "&password=" + password.value + "&code="+code.value, true);
    xml.send();
    xml.onreadystatechange = function () {
        if (xml.readyState == 4)
            if (xml.status == 200) {
                if (xml.responseText == "ok")
                    window.location.href = "../index.html";
                else if (xml.responseText == "false")
                    alert("验证码错误");
            }
            else {
                alert("注册失败");
            }
    };

}