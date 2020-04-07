$(function () {
    var sidebarHeight = $('.js-sider-bar').height();
    var mainDivHeight = $('.js-main-div').height();
    if(parseInt(sidebarHeight)>parseInt(mainDivHeight)) {
        $('.js-main-div').height(sidebarHeight+10)
	}
	window.onresize = function(){
		sidebarHeight = $('.js-sider-bar').height();
		mainDivHeight = $('.js-main-div').height();
		if(parseInt(sidebarHeight)>parseInt(mainDivHeight) && parseInt($(window).width())>992) {
			$('.js-main-div').height(sidebarHeight+10)
		}
	}

    $('.navbar-nav a').click(function(){
        //console.log($(this).attr('href'));
        window.open($(this).attr('href'), '_blank').location;
    });
    $('.navbar-nav .dropdown').mouseover(function(){
        $(this).addClass("open");
    }).mouseout(function(){
        $(this).removeClass("open");
	});


    $(window).scroll(function() {
        if ($(window).scrollTop() > 50) {
          $(".back-top .items.top").fadeIn(200);
        } else {
			$(".back-top .items.top").fadeOut(200);
        }
	});
	$('.back-top .items .icon-img').each(function(index,ele){
		if($(this).parent().hasClass('qq')){
			$(this).mouseover(function(){
				$(this).parent().find('.left-space').fadeIn(200);
			})
		}else{
			$(this).mouseover(function(){
				$(this).parent().find('.left-space').fadeIn(200);
			}).mouseout(function(){
				$(this).parent().find('.left-space').hide();
			});
		}
	});
	$('.back-top .items.qq .left').mouseover(function(){
		$(this).parent().show();
	}).mouseout(function(){
		$(this).parent().hide();
	});
	$('.back-top .items.weixin .icon-img').mouseover(function(){
		$('.back-top .items.qq .left-space').hide();
	})
	$(".back-top .items.top").click(function(){
		$("body, html").animate({
			scrollTop: 0
		});
	});

	var _url = window.location.href;
	var _title = $('.article .title').html();
	$('.share .weibo').click(function(){
		var _shareUrl = 'http://s.share.baidu.com/?click=1&uid=0&to=tsina&type=text';
        _shareUrl += '&url='+ encodeURIComponent(_url);
        _shareUrl += '&title=' + encodeURIComponent(_title);
        _shareUrl += '&content=' + 'utf-8';
        window.open(_shareUrl,'_blank');
	});
	$('.share .qq').click(function(){
		var _shareUrl = 'https://connect.qq.com/widget/shareqq/index.html?';
		_shareUrl += 'url=' + encodeURIComponent(_url);
		_shareUrl += '&title=' + encodeURIComponent(_title);
		window.open(_shareUrl,'_blank');
	});
	$('.share .qzone').click(function(){
		var _shareUrl = 'https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?';
			_shareUrl += 'url=' + encodeURIComponent(_url);
			_shareUrl += '&title=' + encodeURIComponent(_title);
			window.open(_shareUrl,'_blank');
	});

});
