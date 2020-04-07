<?php
RegisterPlugin("live2d","ActivePlugin_live2d");

function ActivePlugin_live2d() {
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags','live2d_Zbp_MakeTemplatetags');
}

function live2d_Zbp_MakeTemplatetags() {
	global $zbp;
	$zbp->header .=  '<link rel="stylesheet" type="text/css" href="'.$zbp->host.'zb_users/plugin/live2d/assets/waifu.min.css?v=1.4.2"/>'."\n";
	$zbp->footer .=  '<div class="waifu">'."\n";
    $zbp->footer .=  '    <div class="waifu-tips"></div>'."\n";
    $zbp->footer .=  '    <canvas id="live2d" class="live2d"></canvas>'."\n";
    $zbp->footer .=  '    <div class="waifu-tool">'."\n";
    $zbp->footer .=  '        <span class="fui-home"></span>'."\n";
    $zbp->footer .=  '        <span class="fui-chat"></span>'."\n";
    $zbp->footer .=  '        <span class="fui-eye"></span>'."\n";
    $zbp->footer .=  '        <span class="fui-user"></span>'."\n";
    $zbp->footer .=  '        <span class="fui-photo"></span>'."\n";
    $zbp->footer .=  '        <span class="fui-info-circle"></span>'."\n";
    $zbp->footer .=  '        <span class="fui-cross"></span>'."\n";
    $zbp->footer .=  '    </div>'."\n";
    $zbp->footer .=  '</div>'."\n";
    $zbp->footer .=  '<script src="'.$zbp->host.'zb_users/plugin/live2d/assets/waifu-tips.min.js?v=1.4.2"></script>'."\n";
    $zbp->footer .=  '<script src="'.$zbp->host.'zb_users/plugin/live2d/assets/live2d.min.js?v=1.0.5"></script>'."\n";
    $zbp->footer .=  '<script type="text/javascript">initModel("'.$zbp->host.'zb_users/plugin/live2d/assets/waifu-tips.json?v=1.4.2")</script>'."\n";
}
