<?php
/**
* Neditor 插件嵌入页
*
* Neditor 是基于 UEditor 的一个更为美观、强大的现代化编辑器。
* 本插件基于 UEditor(@zsx) 和 Neditor(https://github.com/notadd/neditor) 制作
*
* @author  心扬 <chrishyze@163.com>
*/

// 注册插件
RegisterPlugin('Neditor', 'ActivePlugin_Neditor');

/**
 * 挂载系统接口
 */
function ActivePlugin_Neditor()
{
    global $zbp;

    //接口：文章编辑页加载前处理内容，输出位置在<head>尾部
    Add_Filter_Plugin('Filter_Plugin_Edit_Begin', 'HeadScript_Neditor');

    //接口：文章编辑页加载前处理内容，输出位置在<body>尾部
    Add_Filter_Plugin('Filter_Plugin_Edit_End', 'BodyScript_Neditor');

    //接口：c_html_js_add.php脚本调用，前台脚本接口
    Add_Filter_Plugin('Filter_Plugin_Html_Js_Add', 'ForeScript_Neditor');

    //接口：zbp核心加载
    Add_Filter_Plugin('Filter_Plugin_CSP_Backend', 'CSP_Neditor');

    //更新逻辑
    if (!$zbp->Config('Neditor')->HasKey('plugin')) { // v2.24之前版本
        ResetConfig_Neditor(true);
    } else { // v2.24及以后的版本
        if (json_decode($zbp->Config('Neditor')->plugin)->version < 2.31) {
            UpdateConfig_Neditor();
        }
    }
}

/**
 * 添加 Content Security Policy 规则
 */
function CSP_Neditor(&$defaultCSP)
{
    global $zbp;
    $csp = json_decode($zbp->Config('Neditor')->plugin)->csp;
    foreach ($csp as $key => $value) {
        if (array_key_exists($key, $defaultCSP)) {
            foreach (explode(' ', $value) as $directive) {
                if (false === strpos($defaultCSP[$key], $directive)) {
                    $defaultCSP[$key] .= ' ' . $directive;
                }
            }
        } else {
            $defaultCSP[$key] = $value;
        }
    }
}

/**
 * 文章编辑页面<head>尾部插入
 * 用于引入 Neditor Script 文件
 */
function HeadScript_Neditor()
{
    global $zbp;
    //Neditor JS 配置文件
    echo '<script src="' . $zbp->host . 'zb_users/plugin/Neditor/neditor.config.min.php"></script>';
    //Neditor 主体文件
    echo '<script src="' . $zbp->host . 'zb_users/plugin/Neditor/neditor.all.min.js"></script>';
    //Neditor JS 上传服务文件
    echo '<script src="' . $zbp->host . 'zb_users/plugin/Neditor/neditor.service.min.js"></script>';
    echo '<script src="' . $zbp->host . 'zb_users/plugin/Neditor/third-party/browser-md5-file.min.js"></script>';
}

/**
 * 文章编辑页面<body>尾部插入
 * 用于处理ZBlog的编辑器API
 */
function BodyScript_Neditor()
{
    global $zbp;

    $plugin_config = json_decode($zbp->Config('Neditor')->plugin, true);
    $editor_config = json_decode($zbp->Config('Neditor')->editor, true);
    $csrf_token    = $zbp->GetCSRFToken('Neditor');
    $intro         = $editor_config['intro'];

    //Heredoc
    $script = <<<EOF
<script>
var EditorIntroOption = {
    toolbars: [["Source", "Undo", "Redo", "|", "bold", "italic", "underline", "forecolor", "backcolor", "|", "link", "insertimage"]],
    autoHeightEnabled: false,
    initialFrameHeight: 200
};
// 重新定义 editor_init 初始化函数, 用于支持 editor_api
function editor_init() {
    editor_api.editor.content.obj = UE.getEditor("editor_content");//内容编辑器对象
    editor_api.editor.intro.obj = UE.getEditor("editor_intro", EditorIntroOption);//摘要编辑器对象
    //内容编辑器api方法
    editor_api.editor.content.get = function () { return this.obj.getContent(); };//获取编辑器所有内容
    editor_api.editor.content.put = function (str) { return this.obj.setContent(str); };//设置编辑器的内容
    editor_api.editor.content.focus = function () { return this.obj.focus(true); };//让编辑器获得尾部焦点
    editor_api.editor.content.insert = function (str) { return this.obj.execCommand("insertHtml", str); };//在光标处插入内容
    editor_api.editor.content.obj.ready(function () {
        sContent = editor_api.editor.content.get();
    });
    //摘要编辑器api方法
    editor_api.editor.intro.get = function () { return this.obj.getContent(); };
    editor_api.editor.intro.put = function (str) { return this.obj.setContent(str); };
    editor_api.editor.intro.focus = function () { return this.obj.focus(true); };
    editor_api.editor.intro.insert = function (str) { return this.obj.execCommand("insertHtml", str); };
    editor_api.editor.intro.obj.ready(function () {
        sIntro = editor_api.editor.intro.get();
    });

    $(function () {
        editor_api.editor.content.obj.ready(function () {
            editor_api.editor.content.obj.execCommand("serverparam", "csrfToken", window.UEDITOR_CONFIG.csrfToken);
        });
        $("form#edit").submit(function () {
            if (editor_api.editor.content.obj.queryCommandState("source") == 1) {
                editor_api.editor.content.obj.execCommand("source");
            }
            if (editor_api.editor.intro.obj.queryCommandState("source") == 1) {
                editor_api.editor.intro.obj.execCommand("source");
            }
        });

        if ((bloghost).indexOf(location.host.toLowerCase()) < 0) {
            alert("您设置了域名固化，请使用" + bloghost + "访问或进入后台修改域名，否则图片无法上传。");
        }
    });
}
$(function () {
    var intro = $("#tarea");
    var intro_visible = function () {
        return intro.is(":visible");
    }
    var scroll_focus = function () {
        $("html, body").animate({ scrollTop: $("#divIntro").offset().top }, "fast");
        editor_api.editor.intro.focus();
    }
    $("#divIntro").show(); // 显示摘要区块
    $("#insertintro").html(""); // 清除摘要提示内容
    $("#theader").append("<span id=\"GenIntro\">生成摘要</span><span id=\"GenIntroHintBtn\">?</span>" +
        " <span id=\"GenIntroHint\">点击“摘要”，切换摘要编辑器的显示状态（可在Neditor配置中设定默认状态）；点击“生成摘要”，将会提取正文中首条分隔符以上的内容将作为摘要。（更多详情请查看Neditor帮助）</span>"); // 指示符和生成按钮
    // 根据用户设置隐藏摘要编辑器
    if (!$intro) {
        $("#tarea").hide();
    }
    $("#theader > .editinputname").click(function () {
        if (intro_visible()) {
            intro.hide();
        } else {
            intro.show();
            scroll_focus();
        }
    });
    $("#GenIntro").click(function () {
        if (!intro_visible()) {
            intro.show();
        }
        var s = editor_api.editor.content.get();
        if (s.indexOf("<hr class=\"more\" />") > -1) {
            editor_api.editor.intro.put(s.split("<hr class=\"more\" />")[0]);
        } else {
            if (s.indexOf("<hr class=\"more\"/>") > -1) {
                editor_api.editor.intro.put(s.split("<hr class=\"more\"/>")[0]);
            } else {
                i = 250;
                s = s.replace(/<[^>]+>/g, "");
                editor_api.editor.intro.put(s.substring(0, i));
            }
        }
        scroll_focus();
    });
    $("#GenIntroHintBtn").click(function () {
        $("#GenIntroHint").toggle();
    });
});
</script>
<style>
#GenIntro,
#GenIntroHintBtn {
    cursor: pointer;
    display: inline-block;
    color: #6d6d6d;
    line-height: 24px;
    height: 24px;
    text-align: center;
    border-color: #CCCCCC;
    border-style: solid;
}
#GenIntro {
    padding: 0 10px;
    border-width: 1px;
    border-radius: 5px 0 0 5px;
}
#GenIntroHintBtn {
    width: 24px;
    border-left-width: 0;
    border-top-width: 1px;
    border-bottom-width: 1px;
    border-right-width: 1px;
    border-radius: 0 5px 5px 0;
}
#GenIntroHint {
    color: #6d6d6d;
    display: none;
}
</style>
EOF;

    if ($plugin_config['notify']) {
        $php_alert = version_compare(PHP_VERSION, '5.3.29', '<') ? '<p style="color:red">【重要提示】<br>您的PHP版本过低，Neditor不再支持PHP 5.3.29以下的版本，请及时升级PHP，强烈建议使用PHP7</p><br>' : '';
        $script .= '
<script>
//更新提示
$(function () {
    editor_api.editor.content.obj.ready(function () {
        $("body").css("overflow", "hidden");
        $("#neditor-dialog").dialog({
            width: 500,
            modal: true,
            resizable: false,
            beforeClose: function (event, ui) {
                $(this).dialog("destroy");
                $("body").css("overflow", "auto");
            },
            buttons: [{
                text: "确认（此版本不再提示）",
                click: function () {
                    $(this).dialog("close");
                    $.get("' . $zbp->host . 'zb_users/plugin/Neditor/php/config.php?action=offnotify&csrfToken=' . $csrf_token . '");
                }
            }]
        });
    });
});
</script>
<div id="neditor-dialog" title="Neditor ' . $plugin_config['version'] . ' 更新提示" style="display:none;z-index:9999;">' . $php_alert . '<p><strong>【更新内容】</strong></p><p>● 改进 CSP 添加逻辑</p><p>● 弃用百度图片搜索功能</p><p>● 视频模块移除Flash的embed代码，外站视频全部转为iframe</p><p>● 支持优酷、腾讯视频、Bilibili、AcFun、YouTube 的地址直接插入</p><p>● 其他改进和优化</p></div>';
    }

    echo $script;
}

// 前台脚本内容
function ForeScript_Neditor()
{
    global $zbp;

    // 判断网站的代码高亮设置及插件自带代码高亮设置
    $plugin_config = json_decode($zbp->Config('Neditor')->plugin);
    if ($zbp->option['ZC_SYNTAXHIGHLIGHTER_ENABLE'] && $plugin_config->heightlight) {
        echo 'document.writeln(
        "<script src=\'' . $zbp->host . 'zb_users/plugin/Neditor/third-party/prism/prism.js\' type=\'text/javascript\'></script>",
        "<link rel=\'stylesheet\' type=\'text/css\' href=\'' . $zbp->host . 'zb_users/plugin/Neditor/third-party/prism/prism.css\'>"
        );
        $(function(){var compatibility={as3:"actionscript","c#":"csharp",delphi:"pascal",html:"markup",xml:"markup",vb:"basic",js:"javascript",plain:"markdown",pl:"perl",ps:"powershell"};var runFunction=function(doms,callback){doms.each(function(index,unwrappedDom){var dom=$(unwrappedDom);var codeDom=$("<code>");if(callback)callback(dom);var languageClass="prism-language-"+function(classObject){if(classObject===null)return"markdown";var className=classObject[1];return compatibility[className]?compatibility[className]:className}(dom.attr("class").match(/prism-language-([0-9a-zA-Z]+)/));codeDom.html(dom.html()).addClass("prism-line-numbers").addClass(languageClass);dom.html("").addClass(languageClass).append(codeDom)})};runFunction($("pre.prism-highlight"));runFunction($("pre[class*=\"brush:\"]"),function(preDom){var original;if((original=preDom.attr("class").match(/brush:([a-zA-Z0-9\#]+);/))!==null){preDom.get(0).className="prism-highlight prism-language-"+original[1]}});Prism.highlightAll()});';
    }
}

/**
 * 更新配置
 *
 * @return void
 */
function UpdateConfig_Neditor()
{
    global $zbp;

    // 获取旧配置
    $editor            = json_decode($zbp->Config('Neditor')->editor, true);
    $plugin            = json_decode($zbp->Config('Neditor')->plugin, true);
    $plugin['version'] = 2.31;
    $plugin['notify']  = 1;

    // v2.25 新增
    if (!array_key_exists('autosave', $editor)) {
        $editor['autosave'] = 5;
    }
    if (!array_key_exists('zindex', $editor)) {
        $editor['zindex'] = 999;
    }
    // v2.26 新增
    if (!array_key_exists('customtoolbar', $editor)) {
        $editor['customtoolbar'] = '';
    }
    // v2.27 新增
    if (!array_key_exists('xss', $editor)) {
        $editor['xss'] = 0;
    }
    // v2.28 新增
    if (!array_key_exists('intro', $editor)) {
        $editor['intro'] = 0;
    }
    // v2.29 新增
    if (!array_key_exists('heightlight', $plugin)) {
        $plugin['heightlight'] = 1;
    }
    if (!array_key_exists('initstyle', $editor)) {
        $editor['initstyle'] = '';
    }
    if (!array_key_exists('catchimg', $editor)) {
        $editor['catchimg'] = 0;
    }
    // v2.30 新增
    if (!array_key_exists('emotion', $plugin)) {
        $plugin['emotion'] = 0;
    }
    if (!array_key_exists('emotionurl', $plugin)) {
        $plugin['emotionurl'] = '';
    }
    if (!array_key_exists('csp', $plugin)) {
        $plugin['csp'] = array();
    }

    $zbp->Config('Neditor')->editor = json_encode($editor);
    $zbp->Config('Neditor')->plugin = json_encode($plugin);

    $zbp->SaveConfig('Neditor');
}

/**
 * 重置设置
 *
 * @param boolean $del 是否删除已有配置
 */
function ResetConfig_Neditor($del = false)
{
    global $zbp;

    if ($del) {
        $zbp->DelConfig('Neditor');
    }

    // 编辑器配置
    $zbp->Config('Neditor')->editor = json_encode(array(
        'fontfamily'    => '微软雅黑,Microsoft YaHei', // 默认字体
        'fontsize'      => '16px', // 默认字号
        'toolbar'       => 0, // 默认按钮排版，0默认，1完整版，2自定义
        'listfilecount' => 20, //在线文件、图片管理中每页列出的文件、图片数量
        'divtop'        => 0, //规范化外来标签
        'autosave'      => 5, //自动保存时间间隔，分钟
        'zindex'        => 999, //堆叠顺序
        'xss'           => 0,  // xss 过滤机制
        'customtoolbar' => '', //自定义工具栏排版
        'intro'     => 0, // 是否显示摘要编辑器
        'initstyle' => '', // 编辑区域初始化样式，用户附加样式，优先级比 iframe.css 高
        'catchimg'  => 0 // 是否抓取远程图片到本地保存
    ), JSON_UNESCAPED_UNICODE);

    // 插件配置
    $zbp->Config('Neditor')->plugin = json_encode(array(
        'version'       => 2.31, //版本号
        'notify'        => 1, //更新提示
        'keepconfig'    => 1, //卸载时保留配置
        'heightlight'   => 1, // 是否使用编辑器自带的代码高亮
        'emotion'       => 0, // 表情包位置，0本地，1远程
        'emotionurl'    => '', // 表情包远程地址
        'csp'           => array() // 自定义CSP规则
    ));

    $zbp->SaveConfig('Neditor');
}

//插件安装激活时执行函数
function InstallPlugin_Neditor()
{
    global $zbp;

    // 若不存在配置则初始化配置
    if (!$zbp->HasConfig('Neditor')) {
        ResetConfig_Neditor(false);
    }
}

//插件卸载时执行函数
function UninstallPlugin_Neditor()
{
    global $zbp;

    // 删除配置
    if (!json_decode($zbp->Config('Neditor')->plugin)->keepconfig) {
        $zbp->DelConfig('Neditor');
    }
}
