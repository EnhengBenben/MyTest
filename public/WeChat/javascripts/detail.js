var detail = JSON.parse(localStorage.getItem("detail"));
console.log(detail);
//顶部大盒子创建
var bigBox = document.createElement("div");
bigBox.className = "detail-top";
document.body.appendChild(bigBox);
//详情名称创建
var detailName = document.createElement("h3");
detailName.className = "detail-top-1";
detailName.innerHTML = detail.info.name;
bigBox.appendChild(detailName);
//申请截止日期创建
var deadline = document.createElement("p");
deadline.innerHTML = "申请截止：" + detail.info.application_deadline;
bigBox.appendChild(deadline);
//发榜日期创建
var announcement = document.createElement("p");
announcement.innerHTML = "发榜日期：" + detail.info.announcement_date;
bigBox.appendChild(announcement);
//报道日期创建
var enrollment = document.createElement("p");
enrollment.innerHTML = "报道日期：" + detail.info.enrollment_date;
bigBox.appendChild(enrollment);
//结业日期创建
var graduation = document.createElement("p");
graduation.innerHTML = "结业日期：" + detail.info.graduation_date;
bigBox.appendChild(graduation);
//进修时间创建
var period = document.createElement("p");
period.innerHTML = "进修时间：" + detail.info.period + "个月";
bigBox.appendChild(period);
//进修费用创建
var fee = document.createElement("p");
fee.innerHTML = "进修费用：" + detail.info.fee + "元";
bigBox.appendChild(fee);
if (detail.attachment_count != 0) {
    //创建附件
    var accessories = document.createElement("p");
    accessories.innerHTML = "附件：";
    bigBox.appendChild(accessories);
    for (var i = 0; i < detail.attachment_count; i++) {
//创建每一个附件的链接
        var aLink = document.createElement("a");
        aLink.href ="http://admin.applydemo.conglinnet.com/" + detail.attachment[i].relative_path;
        aLink.innerHTML = detail.attachment[i].name + "<br>";
        bigBox.appendChild(aLink);
    }
}
//创建详细内容区
var content = document.createElement("div");
content.className = "detail-content";
document.body.appendChild(content);
//创建内容区文字
var contentFont = document.createElement("div");
contentFont.innerHTML = detail.info.detail_info;
content.appendChild(contentFont);
