/*
For: 运行代码演示[ZBLOG-PHP插件] https://app.zblogcn.com/?auth=3ec7ee20-80f2-498a-a5dd-fda19b198194
Author: 尔今
Author Email: erx@qq.com
Author URL: http://www.yiwuku.com/
*/

document.writeln('<link href="'+ bloghost +'zb_users/plugin/Codemo/codemo.css?v=1.3" rel="stylesheet">');
var preNum = 0;
$("pre[class*='html'],pre[class*='markup'],pre[class*='js'],pre[class*='javascript'],pre[class*='css'],pre>code[class*='html'],pre>code[class*='javascript']").each(function () {
	yid = "pre_" + preNum;
	precode = $(this).html();
	runbtn = "<input type='button' value='运行代码' onclick=\"runCode('" + yid + "')\" class='runbtn' title='Plugins by Yiwuku.com'>";
	copybtn = "<input type='button' value='复制' data-tid='t"+yid+"' class='runbtn copybtn' title='Plugins by Yiwuku.com'><span class='codemo-tip'>复制成功！</span>";
	$(this).after(runbtn+copybtn).attr("id", yid);
	$(this).after("<textarea id='t"+yid+"' class='codemo-code'>"+precode+"</textarea>");
	preNum++
});
//erx:Run
function runCode(con) {
	var c = window.open("", "", "");
	c.document.open("text/html", "replace");
	c.opener = null;
	c.document.write($("#" + con).next("textarea").html()
		.replace(/&lt;/g, "<")
		.replace(/&gt;/g, ">")
		.replace(/&nbsp;/g, " ")
		.replace(/&quot;/g, '"')
		.replace(/&amp;/g, "&")
	);
	c.document.close();
}
$(function(){
	//erx:Copy
	$("body").on('click','.copybtn', function(){
		var tid = $(this).attr("data-tid");
		$("#"+tid).select();
		try{
			document.execCommand("Copy");
		}catch(e){
			$(".codemo-tip").text("浏览器不支持！").addClass("erxerror");
		}
		$(".codemo-tip").addClass("erxact");
		codemeCtip();
	});
	function codemeCtip(){
		setTimeout(function(){
			$(".codemo-tip").removeClass("erxact");
		},3000);
	}
});

//若无代码基础，切勿修改以上代码，以防出错 https://app.zblogcn.com/?auth=3ec7ee20-80f2-498a-a5dd-fda19b198194