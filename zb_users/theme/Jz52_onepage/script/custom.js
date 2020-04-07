$(function() {
    //Gotop
	$(".Jz52-gotop").click(function(){
		$("html, body").animate({ scrollTop: 0 },300);
		return false;
	});
	var Sctop = $(window).scrollTop(), Snum = 500;
	if(Sctop > Snum){
		$(".Jz52-back, .Jz52-gotop").addClass("active");
	}
	$(window).scroll(function(){
		Sctop = $(window).scrollTop();
		if(Sctop > Snum){
			$(".Jz52-back, .Jz52-gotop").addClass("active");
		}else{
			$(".Jz52-back, .Jz52-gotop").removeClass("active");
		}		
	});
});
//Comments
zbp.plugin.unbind("comment.reply.start", "system");
zbp.plugin.on("comment.reply.start", "Jz52_onepage", function(i) {
	$("#inpRevID").val(i);
	var frm = $('#divCommentPost'),
		cancel = $("#cancel-reply"),
		temp = $('#temp-frm');
	var div = document.createElement('div');
	div.id = 'temp-frm';
	div.style.display = 'none';
	frm.before(div);
	$('#AjaxComment' + i).before(frm);
	frm.addClass("reply-frm");
	cancel.show();
	cancel.click(function() {
		$("#inpRevID").val(0);
		var temp = $('#temp-frm'),
			frm = $('#divCommentPost');
		if (!temp.length || !frm.length) return;
		temp.before(frm);
		temp.remove();
		$(this).hide();
		frm.removeClass("reply-frm");
		return false;
	});
	try {
		$('#txaArticle').focus();
	} catch (e) {}
	return false;
});
zbp.plugin.on("comment.get", "Jz52_onepage", function(postid, page) {
	$('span.commentspage').html("Waiting...");
});
zbp.plugin.on("comment.got", "Jz52_onepage", function(formData, data, textStatus, jqXhr) {
	$('#AjaxCommentBegin').nextUntil('#AjaxCommentEnd').remove();
	$('#AjaxCommentEnd').before(data);
	$("#cancel-reply").click();
});
zbp.plugin.on("comment.postsuccess", "Jz52_onepage", function () {
	$("#cancel-reply").click();
});