<?php
/**
* 插件配置页
*
* @author  心扬 <chrishyze@163.com>
*/

//系统初始化
require_once __DIR__.'/../../../zb_system/function/c_system_base.php';
//后台初始化
require_once __DIR__.'/../../../zb_system/function/c_system_admin.php';

$zbp->Load(); //加载系统

//检测权限
if (!$zbp->CheckRights('root')) {
    $zbp->ShowError(6);
    die();
}
//检测主题/插件启用状态
if (!$zbp->CheckPlugin('IframeVO')) {
    $zbp->ShowError(48);
    die();
}

$blogtitle = 'iframe嵌入视频优化';

//后台<head>
require_once __DIR__.'/../../../zb_system/admin/admin_header.php';
//后台顶部
require_once __DIR__.'/../../../zb_system/admin/admin_top.php';

if ('POST' === strtoupper($_SERVER['REQUEST_METHOD'])) {
    // csp 规则
    $csp = GetVars('csp', 'POST');
    $cspConfig = [];
    for ($i = 0; $i < count($csp); $i = $i + 1) {
        if (!empty($csp[$i])) {
            $cspConfig[] = trim($csp[$i]);
        }
    }
    $zbp->Config('IframeVO')->csp = json_encode($cspConfig, JSON_UNESCAPED_SLASHES);
    $zbp->Config('IframeVO')->keepconfig = GetVars('keepconfig', 'POST') ? 1 : 0;
    $zbp->SaveConfig('IframeVO');
}
?>

<div id="divMain">
    <div class="divHeader"><?php echo $blogtitle; ?></div>
    <div class="SubMenu">
        <a href="#" class="SubMenu__tab"><span class="m-left m-now">配置</span></a>
        <a href="#" class="SubMenu__tab"><span class="m-left">批量优化</span></a>
        <a href="#" class="SubMenu__tab"><span class="m-left">帮助</span></a>
        <a href="#" class="SubMenu__tab"><span class="m-left">关于</span></a>
    </div>
    <div id="divMain2">
        <div class="divMain2__item divMain2__item_show">
            <form name="config" method="post" action="main.php">
                <p>卸载保留配置 <input name="keepconfig" class="checkbox" type="text" value="<?php echo $zbp->Config('IframeVO')->keepconfig; ?>"></p>
                <p>
                    CSP 规则：<input type="text" name="csp[]" placeholder="域名或网址"" autocomplete="off" style="width:300px">
                    <span id="addCsp" title="添加规则"></span>
                </p>
                <br>
                <input type="hidden" name="csrfToken" value="<?php echo $zbp->GetCSRFToken('IframeVO'); ?>">
                <p>
                    <input type="submit" class="button" value="保存配置">
                </p>
            </form>
        </div>
        <div class="divMain2__item divMain2__item_hide">
            <form name="batch">
                <p>可以批量优化那些未通过本插件添加的视频。注意：仅优化包含 CSP 规则网址的 iframe，以免误操作。</p>
                <div class="iframevo__item">
                    宽高比例：
                    <input type="radio" name="iframeVORatio" value="75" checked> 4:3 &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="iframeVORatio" value="56.25"> 16:9 &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="iframeVORatio" value="62.5"> 16:10
                </div>
                <div class="iframevo__item">
                    <input type="checkbox" id="iframeVOHTML5Format" name="iframeVOHTML5Format" checked> HTML5 格式化
                </div>
                <br>
                <input type="hidden" name="csrfToken" value="<?php echo $zbp->GetCSRFToken('IframeVO'); ?>">
                <p>
                    <input type="submit" id="batchSubmit" class="button" value="开始批量优化">
                </p>
                <br>
                <div id="batchResult"></div>
            </form>
        </div>
        <div class="divMain2__item divMain2__item_hide">
            <h3>如何使用？</h3>
            <p>启用插件后，在文章编辑页面右侧栏中找到“添加 iframe 视频”按钮，点击后弹出对话框，在对话框中粘贴复制好的 iframe 代码，选择要展示的宽高比例、以及是否进行 HTML5 格式化，点击“插入”即可。如果无法预览视频，请到设置中添加对应的 CSP 域名。</p>
            <p>展示宽高比例是指 iframe 的宽高比，而非视频的宽高比，因为有的视频网站的播放器中控制条覆盖在视频上面，控制条本身并不占据 iframe 的高度（比如优酷），此时 iframe 展示的宽高比就等于视频本身的宽高比，而有的网站的播放器控制条单独占有高度（比如哔哩哔哩），那么 iframe 展示的比例就不等于视频的比例。</p>
            <p>一般来说，对于哔哩哔哩，推荐 4:3 比例；对于优酷，根据视频的宽高比来确定比例；其他网站以此类推。实在不懂的选 4:3 就行。</p>
            <br>
            <h3>HTML5 格式化了哪些内容？</h3>
            <p>1. 将 http 或 // 转为 https。</p>
            <p>2. 移除 iframe 元素在 HTML5 中被废弃的属性，参考：<a href="https://developer.mozilla.org/zh-CN/docs/Web/HTML/Element/iframe" target="_blank">&lt;iframe&gt;</a>，仅保留 src、allow、allowfullscreen 和 style 属性。</p>
            <p>（特例：搜狐视频保留 scrolling 属性，以解决出现滚动条的问题。）</p>
            <br>
            <h3>CSP 规则</h3>
            <p>添加 CSP 规则可以解决无法预览视频的问题。CSP 即 Content Security Policy，中文名“内容安全策略”，参考：<a href="https://developer.mozilla.org/zh-CN/docs/Web/HTTP/CSP" target="_blank">Content Security Policy (CSP)介绍</a>，本插件仅使用 frame-src 这一策略规则，因此只需填写域名或网址即可。</p>
            <p>例如，某个 iframe 的地址为 https://v.abc.com/xyz?id=123，那么只需添加 v.abc.com 或者 https://v.abc.com/ 即可。</p>
            <br>
            <h3>其他</h3>
            <p>仅支持能通过 iframe 分享的视频平台。</p>
            <p>已测试视频平台：哔哩哔哩、Youtube、爱奇艺视频、腾讯视频、优酷视频、搜狐视频。</p>
            <p>不支持使用 Flash，其他未经测试的视频平台均不保证支持。</p>
        </div>
        <div class="divMain2__item divMain2__item_hide">
            <div class="divMain2__item-about">
                <p><img src="<?php echo $zbp->host; ?>zb_users/plugin/IframeVO/logo.png" alt="logo"></p>
                <p>IframeVO v<?php $app = new \App();
                $app->LoadInfoByXml('plugin', 'IframeVO');
                echo $app->version; ?></p>
                <br>
                <br>
                <p>插件作者：心扬</p>
                <p>邮箱：chrishyze@163.com</p>
                <p>欢迎通过邮件反馈 BUG 或建议，感谢支持！</a></p>
                <br>
                <p>项目源码：<a href="https://gitee.com/chrishyze/zbp_iframevo" target="_blank">chrishyze/zbp_iframevo</a>
                </p>
                <br>
                <p>作者的其他作品：<a href="<?php echo $zbp->host; ?>zb_users/plugin/AppCentre/main.php?auth=2ffbff0a-1207-4362-89fb-d9a780125e0a">应用中心</a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.divMain2__item_show {
    display: block;
}
.divMain2__item_hide {
    display: none;
}
.divMain2__item-about {
    text-align: center;
}
#addCsp,
.del-csp {
    display: inline-block;
    width: 26px;
    height: 26px;
    background-position: center center;
    background-repeat: no-repeat;
    cursor: pointer;
    vertical-align: middle;
}
#addCsp {
    background-image: url(<?php echo $zbp->host; ?>zb_users/plugin/IframeVO/images/add.svg);
    background-size: 24px 24px;
}
.del-csp {
    background-image: url(<?php echo $zbp->host; ?>zb_users/plugin/IframeVO/images/delete.svg);
    background-size: 20px 20px;
}
.added-csp {
    margin-left: 72px;
}
.iframevo__item {
    padding: 5px 0 10px 0;
}
</style>

<script>
// 插件后台全局变量
let IFRAMEVO_ADMIN = {
    HOME_URL: "<?php echo $zbp->host; ?>zb_users/plugin/IframeVO",
    CSRF_TOKEN: "<?php echo $zbp->GetCSRFToken('IframeVO'); ?>",
    csp: <?php echo $zbp->Config('IframeVO')->csp; ?>
};
$(function() {
    $(".SubMenu__tab").click(function() {
        var $content = $(".divMain2__item:nth-child(" + ($(this).index() + 1) + ")");
        $(this).siblings().children().removeClass("m-now");
        $(this).children().addClass("m-now");
        $content.siblings().removeClass("divMain2__item_show").addClass("divMain2__item_hide");
        $content.removeClass("divMain2__item_hide").addClass("divMain2__item_show");
    });

    // 添加 csp 规则
    var addCspRule = function(domain) {
        $("#addCsp").parent().after('<div class="added-csp"><input type="text" name="csp[]" placeholder="域名或网址"" autocomplete="off" style="width:300px" value="' + domain + '"><span class="del-csp" title="删除规则"></span></div>');

        // 删除 csp 规则
        $(".del-csp").click(function() {
            $(this).parent().remove();
        });
    };
    // 监听添加按钮
    $("#addCsp").click(function() {
        var $inputs = $("input[name='csp[]']");
        addCspRule($inputs[0].value);
        $inputs[0].value = "";
    });
    // 现存的 csp 规则
    for (var key in IFRAMEVO_ADMIN.csp) {
        addCspRule(IFRAMEVO_ADMIN.csp[key]);
    }

    // 批量优化视频
    $("#batchSubmit").click(function(e) {
        $("#batchResult").html("后台优化中，请稍候...");
        $.post("php/batching.php", {
            ratio: $("input[name=\"iframeVORatio\"]:checked").val(),
            format: $("#iframeVOHTML5Format").is(":checked") ? 1 : 0,
            csrfToken: $("input[name=\"csrfToken\"]").val()
        }, function(res) {
            $("#batchResult").html(res[1]);
        });

        e.preventDefault();
        return false;
    });
});
</script>
<?php
//后台底部
require_once __DIR__.'/../../../zb_system/admin/admin_footer.php';
RunTime(); //显示运行时间
