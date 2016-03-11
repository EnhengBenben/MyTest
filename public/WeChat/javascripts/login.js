/*登录页行为动作*/
function login() {
    var tel = document.getElementById("tel");
    var password = document.getElementById("password");
    // var inputVal = document.getElementsByClassName("_input");
    var wait = 2;
    var name = document.getElementById("submit-button");

    function time(o) {
        if (wait == 0) {
            o.children[0].style.opacity = "0";
            wait = 2;
        } else {
            o.children[0].style.opacity = "0.3";
            console.log(wait);
            wait--;
            setTimeout(function () {
                    time(o)
                },
                100)
        }
    }

    time(name);
    if (tel.value != "") {
        var reg = /^[0-9]{11}$/;
        if (tel.value.length != 11 || !reg.test(tel.value) || tel.value[0] != "1" || (tel.value[1] != "3" && tel.value[1] != "5" && tel.value[1] != "4" &&
            tel.value[1] != "7" && tel.value[1] != "8")) {
            alert("请输入正确的手机号");
            return false;
        }
    }
    else {
        alert("手机号为空，不能提交表单！");
        return false;
    }
    if (password.value == "") {
        alert("密码不能为空");
        return false;
    }
    var xml = null;
    if (window.XMLHttpRequest) {
        xml = new XMLHttpRequest();
    }
    else {
        xml = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xml.open("post", "/wx/log_in?phone_number=" + tel.value + "&password=" + password.value, true);
    xml.send();
    xml.onreadystatechange = function () {
        if (xml.readyState == 4)
            if (xml.status == 200) {
                if (xml.responseText == "false") {
                    alert("用户名或密码错误");
                    return false;
                }
                else if (xml.responseText == "") {
                    alert("登录失败");
                    return false;
                }
                else {
                    console.log(xml.responseText);
                    localStorage.setItem("id", xml.responseText);
                    window.location.href = "./views/condition.html";
                }
            }
            else {
                alert("登录失败");
                return false;
            }
    };
}
function logUp() {
    var wait = 2;
    var name = document.getElementsByClassName("register")[0];
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
    window.location.href = "./views/rejister.html";

}