<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('qianjiangteam_51wzt')) {$zbp->ShowError(48);die();}


require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

$csrfToken = '';
if (function_exists('CheckIsRefererValid')) {
    $csrfToken =  $zbp->GetCSRFToken();
}

$qianjiangteam = $zbp->Config('qianjiangteam_51wzt');

?>

<link href="./needfile/css/qianjiangteamadmin.css" rel="stylesheet">
<div class="container-fluid" id="main_container">
    <div class="row top-title">
        <div class="col-xs-12">
            <div class="div-header">主题设置</div>
        </div>
    </div>
    <div class="row mian-set">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#base-set" aria-controls="base-set" role="tab" data-toggle="tab">基础设置</a>
            </li>
            <li role="presentation">
                <a href="#seo-set" aria-controls="seo-set" role="tab" data-toggle="tab">SEO设置</a>
            </li>
            <li role="presentation">
                <a href="#img-set" aria-controls="img-set" role="tab" data-toggle="tab">广告设置</a>
            </li>
            <li role="presentation">
                <a id="js-help-tab" href="#help-me" style="color: red;font-weight:bold" aria-controls="help-me" role="tab" data-toggle="tab">主题帮助</a>
            </li>
        </ul>

        <div class="tab-content">
            <input type="hidden" name="csrfToken" id="csrfToken" value="<?php echo $csrfToken;?>">
            <!-- 基本设置 -->
            <div role="tabpanel" class="tab-pane active" id="base-set">
                <div class="row">
                    <form action="" class="form-inline" enctype="multipart/form-data" > 
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>名称</th>
                                <th>设置</th>
                                <th>说明</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>LOGO图片(180*60)</td>
                                <td class="align-l">
                                    <input type="file" class="input-file" id="qjt_logo_img" value="">
                                    <button type="button" class="btn btn-primary" onclick="save_my_logo()">保存</button>
                                </td>
                                <td>
                                    <img id="now-logo-img" src="./needfile/images/front-logo.png" width="150" height="40" alt="" srcset="">
                                </td>
                            </tr>
                            <tr>
                                <td>favicon图标(32*32)</td>
                                <td class="align-l">
                                    <input type="file" class="input-file" id="qjt_icon_img">
                                    <button type="button" class="btn btn-primary" onclick="save_my_favicon()">保存</button>
                                </td>
                                <td>
                                    <img id="now-icon-img"  src="./needfile/images/favicon.ico" width="20" height="20" alt="" srcset="">
                                </td>
                            </tr>
                            <tr>
                                <td>首页顶部轮播信息</td>
                                <td class="align-l">
                                    <div class="form-group">
                                        <textarea id="qjt_carousel_img" style="width: 80%" class="form-control" rows="3" cols="40"><?php echo $qianjiangteam->qjt_carousel_img;?></textarea>
                                    </div>
                                </td>
                                <td>
                                    多条轮播信息用 “|” 进行分割，<br/>如：轮播信息1|轮播信息2|轮播信息3
                                </td>
                            </tr>
                            <tr>
                                <td>友情连接</td>
                                <td class="align-l">
                                    <a href="/zb_system/admin/module_edit.php?act=ModuleEdt&id=10">→→点我快速进入【友情连接管理】</a>
                                </td>
                                <td><div onclick="goto_help()" style="cursor:pointer;color:red;font-weight:700;">【查看帮助】</div></td>
                            </tr>
                            <tr>
                                <td>返回顶部QQ、微信、<br />微博设置</td>
                                <td class="align-l">
                                <div class="form-group">
                                    <div> 
                                        <span class="setinfo-span">QQ:</span> 
                                            <?php if ($qianjiangteam->qjt_base_qq_switch >0){?>
                                                <input type="checkbox" class="switch" id="qjt_base_qq_switch" checked>
                                            <?php }else{?>
                                                <input type="checkbox" class="switch" id="qjt_base_qq_switch">
                                            <?php }?>
                                        <span class="setinfo-span">微信:</span> 
                                        <?php if ($qianjiangteam->qjt_base_weixin_switch >0){?>
                                                <input type="checkbox" class="switch" id="qjt_base_weixin_switch" checked>
                                            <?php }else{?>
                                                <input type="checkbox" class="switch" id="qjt_base_weixin_switch">
                                            <?php }?>
                                        <span class="setinfo-span">微博:</span> 
                                            <?php if ($qianjiangteam->qjt_base_weibo_switch >0){?>
                                                <input type="checkbox" class="switch" id="qjt_base_weibo_switch" checked>
                                            <?php }else{?>
                                                <input type="checkbox" class="switch" id="qjt_base_weibo_switch">
                                            <?php }?>
                                    </div>
                                    <div style="overflow: hidden; margin-top:10px">
                                        <label for="" style="float: left;margin-right:10px">QQ:&nbsp;&nbsp;</label>
                                        <input type="text" value="<?php echo $qianjiangteam->qjt_base_qq;?>" style="width: 80%" class="form-control" id="qjt_base_qq" placeholder="请输入QQ号">
                                    </div>
                                    <div style="overflow: hidden; margin-top:10px">
                                        <label for="" style="float: left;margin-right:10px">微信:</label>
                                        <input type="file" class="input-file" id="qjt_base_weixin_img" value="">
                                        <button type="button" class="btn btn-primary" onclick="save_my_weixin();">保存</button>
                                    </div>
                                    <div style="overflow: hidden; margin-top:10px">
                                        <label for="" style="float: left;margin-right:10px">微博:</label>
                                        <input type="text" value="<?php echo $qianjiangteam->qjt_base_weibo;?>" style="width: 80%" class="form-control" id="qjt_base_weibo" placeholder="请输入微博连接地址">
                                    </div>
                                </div>
                                </td>
                                <td>
                                    <img id="now-weixin-img"  src="./needfile/images/qianjiangmengqrcode.png" width="120" height="120" alt="" srcset="">
                                </td>
                            </tr>
                            <tr>
                                <td>底部左边信息</td>
                                <td class="align-l">
                                <div class="form-group">
                                        <textarea id="qjt_footer_left" style="width: 80%" class="form-control" rows="3" cols="40"><?php echo $qianjiangteam->qjt_footer_left;?></textarea>
                                    </div>
                                </td>
                                <td><div onclick="goto_help()" style="cursor:pointer;color:red;font-weight:700;">【查看帮助】</div></td>
                            </tr>
                            <tr>
                                <td>底部右边信息</td>
                                <td class="align-l">
                                <div class="form-group">
                                        <textarea id="qjt_footer_right" style="width: 80%" class="form-control" rows="3" cols="40"><?php echo $qianjiangteam->qjt_footer_right;?></textarea>
                                    </div>
                                </td>
                                <td><div onclick="goto_help()" style="cursor:pointer;color:red;font-weight:700;">【查看帮助】</div></td>
                            </tr>
                            <tr>
                                <td>底部版权信息</td>
                                <td class="align-l">
                                <div class="form-group">
                                        <textarea id="qjt_footer_copyright" style="width: 80%" class="form-control" rows="3" cols="40"><?php echo $qianjiangteam->qjt_footer_copyright;?></textarea>
                                    </div>
                                </td>
                                <td><div onclick="goto_help()" style="cursor:pointer;color:red;font-weight:700;">【查看帮助】</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="row">
                    <div class="col-xs-12 saveinfo">
                        <button type="button" class="btn btn-primary" onclick="save_base_set()">保存</button>
                    </div>
                </div>
            </div>
            <!-- SEO设置 -->
            <div role="tabpanel" class="tab-pane" id="seo-set">
                <div class="row">
                    <form action="" class="form-inline">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>名称</th>
                                <th>设置</th>
                                <th>说明</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>全局SEO开关</td>
                                    <td class="align-l">
                                        <?php if ($qianjiangteam->qjt_seo_switch >0){?>
                                            <input type="checkbox" class="switch" id="qjt_seo_switch" checked>
                                        <?php }else{?>
                                            <input type="checkbox" class="switch" id="qjt_seo_switch">
                                        <?php }?>
                                    </td>
                                    <td>@开启后以下功能才能生效</td>
                                </tr>
                                <tr>
                                    <td>网站标题</td>
                                    <td class="align-l">
                                        <input type="text" value="<?php echo $qianjiangteam->qjt_seo_site_title;?>" style="width: 80%" class="form-control" id="qjt_seo_site_title" placeholder="网站标题">
                                    </td>
                                    <td>@填写网站首页标题</td>
                                </tr>
                                <tr>
                                    <td>网站关键词</td>
                                    <td class="align-l">
                                        <input type="text" value="<?php echo $qianjiangteam->qjt_seo_site_keywords;?>" style="width: 80%" class="form-control" id="qjt_seo_site_keywords" placeholder="站点网站关键词标题">
                                    </td>
                                    <td>@填写网站首页关键词，<br />多个英文逗号隔开</td>
                                </tr>
                                <tr>
                                    <td>网站描述</td>
                                    <td class="align-l">
                                        <div class="form-group">
                                            <textarea name="" style="width: 80%" class="form-control" rows="3" id="qjt_seo_site_discribe"><?php echo $qianjiangteam->qjt_seo_site_discribe;?></textarea>
                                        </div>
                                    </td>
                                    <td>@填写网站首页描述</td>
                                </tr>
                                </tbody>
                        </table>

                    </form>
                </div>
                <div class="row">
                    <div class="col-xs-12 saveinfo">
                        <button type="button" class="btn btn-primary" onclick="save_seo_set()">保存</button>
                    </div>
                </div>
            </div>
            <!-- 广告设置 -->
            <div role="tabpanel" class="tab-pane" id="img-set">
            <div class="row">
                    <form action="" class="form-inline">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>名称</th>
                                <th>设置</th>
                                <th>说明</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>全局广告开关</td>
                                    <td class="align-l">
                                        <?php if ($qianjiangteam->qjt_adv_switch >0){?>
                                            <input type="checkbox" class="switch" id="qjt_adv_switch" checked>
                                        <?php }else{?>
                                            <input type="checkbox" class="switch" id="qjt_adv_switch">
                                        <?php }?>
                                    </td>
                                    <td><div onclick="goto_help()" style="cursor:pointer;color:red;font-weight:700;">【使用帮助】</div></td>
                                </tr>
                                <tr>
                                    <td>首页PC端顶部<br>广告位</td>
                                    <td class="align-l">
                                        <div class="form-group">
                                            <textarea name="" style="width: 100%" class="form-control" rows="3" id="qjt_adv_pc_head"><?php echo $qianjiangteam->qjt_adv_pc_head;?></textarea>
                                        </div>
                                    </td>
                                    <td><div onclick="goto_help()" style="cursor:pointer;color:red;font-weight:700;">【使用帮助】</div></td>
                                </tr>
                                <tr>
                                    <td>首页手机端顶部<br>广告位</td>
                                    <td class="align-l">
                                        <div class="form-group">
                                            <textarea name="" style="width: 100%" class="form-control" rows="3" id="qjt_adv_mobile_head"><?php echo $qianjiangteam->qjt_adv_mobile_head;?></textarea>
                                        </div>
                                    </td>
                                    <td><div onclick="goto_help()" style="cursor:pointer;color:red;font-weight:700;">【使用帮助】</div></td>
                                </tr>
                                <!-- <tr>
                                    <td>首页PC端底部<br>广告位</td>
                                    <td class="align-l">
                                        <div class="form-group">
                                            <textarea name="" style="width: 100%" class="form-control" rows="3" id="qjt_adv_pc_footer"></textarea>
                                        </div>
                                    </td>
                                    <td><div onclick="goto_help()" style="cursor:pointer;color:red;font-weight:700;">【使用帮助】</div></td>
                                </tr>
                                <tr>
                                    <td>首页手机端底部<br>广告位</td>
                                    <td class="align-l">
                                        <div class="form-group">
                                            <textarea name="" style="width: 100%" class="form-control" rows="3" id="qjt_adv_mobile_footer"></textarea>
                                        </div>
                                    </td>
                                    <td><div onclick="goto_help()" style="cursor:pointer;color:red;font-weight:700;">【使用帮助】</div></td>
                                </tr> -->
                                </tbody>
                        </table>

                    </form>
                </div>
                <div class="row">
                    <div class="col-xs-12 saveinfo">
                        <button type="button" class="btn btn-primary" onclick="save_adv_set()">保存</button>
                    </div>
                </div>
            </div>
            <!-- 主题帮助 -->
            <div role="tabpanel" class="tab-pane" id="help-me">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 300px">相关内容</th><th>使用说明</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>侧边栏调用说明:</td>
                            <td class="align-l">
                                <div>默认侧边栏：首页</div>
                                <div>侧边栏2：分类页、tag页</div>
                                <div>侧边栏3：文章页</div>
                            </td>
                        </tr>
                        <tr>
                        <td>主题演示以及更多主题:</td>
                            <td class="align-l">
                                <a href="http://www.qianjiangteam.com">去演示站点</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                更多免费主题、<br>
                                <b style="color: red">该最新主题升级、功能修复/增加</b>
                                请关注：
                            </td>
                            <td class="align-l">
                                <div><img width="130" src="./needfile/images/qianjiangmengqrcode.png" alt="" srcset=""></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                作者(问题反馈)联系方式:
                            </td>
                            <td class="align-l">
                                QQ/微信：727677752
                            </td>
                        </tr>
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- <script src="./needfile/js/jq.min.js"></script> -->
<script src="./needfile/js/bs.min.js"></script>
<script src="./needfile/js/qianjiangteam.js"></script>



<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>