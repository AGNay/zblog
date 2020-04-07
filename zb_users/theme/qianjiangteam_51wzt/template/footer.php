	<!-- 底部 -->
	<footer class="container-fluid qjt-footer01">
	<div class="container">
			<div class="row col-sm-6 col-xs-12">
				{$zbp->Config('qianjiangteam_51wzt')->qjt_footer_left}
			</div>
			<div class="row col-sm-6 col-xs-12">
				{$zbp->Config('qianjiangteam_51wzt')->qjt_footer_right}
			</div>
			<div class="row col-sm-6 col-xs-12">
				{$zbp->Config('qianjiangteam_51wzt')->qjt_footer_copyright}
				版权版归属权：<a>zblog屋</a>基于<a rel="nofollow" href="http://www.zblogcn.com/" title="RainbowSoft Z-BlogPHP" target="_blank">Z-BlogPHP</a>搭建
			</div>
		</div>
	</footer>

	<!-- 回顶部 -->
	<div class="back-top hidden-xs hidden-sm">

		{if $zbp->Config('qianjiangteam_51wzt')->qjt_base_qq_switch > 0}
		<div class="items qq">
			<span class="icon-img"></span>
			<div class="left-space">
				<div class="left">
					<div class="contact-info">
						<p>在线咨询: 
							<a href="http://wpa.qq.com/msgrd?v=3&amp;uin={$zbp->Config('qianjiangteam_51wzt')->qjt_base_qq}&amp;site=qq&amp;menu=yes" target="_blank">
								<img src="{$host}zb_users/theme/{$theme}/needfile/images/qqchat.gif" alt="QQ交谈">
							</a>
						</p>
						<p>邮箱: {$zbp->Config('qianjiangteam_51wzt')->qjt_base_qq}@qq.com</p>	
					</div>
					<i></i>		        
				</div>
			</div>
		</div>
        {/if}
		
		{if $zbp->Config('qianjiangteam_51wzt')->qjt_base_weixin_switch > 0}
		<div class="items weixin">
			<span class="icon-img"></span>
			<div class="left-space">
				<div class="left">
					<img src="{$host}zb_users/theme/{$theme}/needfile/images/qianjiangmengqrcode.png" alt="联系方式">
					<p>扫一扫关注我</p>	
					<i></i>		        
				</div>
			</div>
		</div>
		{/if}

		{if $zbp->Config('qianjiangteam_51wzt')->qjt_base_weibo_switch > 0}
		<div class="items weibo">
			<a class="icon-img" href=" {$zbp->Config('qianjiangteam_51wzt')->qjt_base_weibo}" target="_blank"></a>
		</div>
		{/if}
		
		<div class="items top">
			<span class="icon-img"><span class="glyphicon glyphicon-menu-up"></span></span>
		</div>
	</div>

    <script src="{$host}zb_users/theme/{$theme}/needfile/js/bs.min.js" type="text/javascript"></script>
    <script src="{$host}zb_users/theme/{$theme}/needfile/js/qianjiangteamindex.js" type="text/javascript"></script>
</body>
</html>