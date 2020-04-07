<?php
/**
 * Editor.md for Z-BlogPHP
 *
 * 插件嵌入页.
 *
 * @author 心扬 <chrishyze@163.com>
 */

// 注册插件
RegisterPlugin('Editormd', 'ActivePlugin_Editormd');

/**
 *  挂载插件接口.
 */
function ActivePlugin_Editormd()
{
    global $zbp;

    //接口：文章编辑页加载前处理内容，输出位置在<head>尾部
    Add_Filter_Plugin('Filter_Plugin_Edit_Begin', 'EditHead_Editormd');

    //接口：文章编辑页加载前处理内容，输出位置在<body>尾部
    Add_Filter_Plugin('Filter_Plugin_Edit_End', 'EditBody_Editormd');

    //1号输出接口，在内容文本框下方，用于存放Editor.md 转换的 HTML 源码，以及加载提示
    Add_Filter_Plugin('Filter_Plugin_Edit_Response', 'Response1_Editormd');

    //处理文章页模板接口
    Add_Filter_Plugin('Filter_Plugin_ViewPost_Template', 'ExtraSupport_Editormd');

    //接口：提交文章数据接管
    Add_Filter_Plugin('Filter_Plugin_PostArticle_Core', 'PostData_Editormd');

    //接口：提交文章数据接管
    Add_Filter_Plugin('Filter_Plugin_PostPage_Core', 'PostData_Editormd');

    //更新逻辑
    if (!$zbp->Config('Editormd')->HasKey('plugin')) { // v2.86之前版本
        ResetConfig_Editormd(true);
    } else { // v2.86及以后的版本
        if (json_decode($zbp->Config('Editormd')->plugin)->version < 2.91) {
            UpdateConfig_Editormd();
        }
    }
}

/**
 * 文章编辑页面 <head> 尾部
 * 引入 Editor.md 文件.
 */
function EditHead_Editormd()
{
    global $zbp;
    $editor_config = json_decode($zbp->Config('Editormd')->editor);

    echo '<link rel="stylesheet" type="text/css" href="' . $zbp->host . 'zb_users/plugin/Editormd/css/editormd.min.css">';
    echo '<script charset="utf-8" src="' . $zbp->host . 'zb_users/plugin/Editormd/editormd.min.js"></script>';
    if ($editor_config->editorstyle) {
        echo '<style>' . $editor_config->editorstyle . '</style>';
    }
}

/**
 * 文章编辑页面 <body> 尾部
 * 配置和启动 Editor.md.
 */
function EditBody_Editormd()
{
    global $zbp;

    $plugin_url = $zbp->host . 'zb_users/plugin/Editormd';
    $csrf_token = $zbp->GetCSRFToken('Editormd');

    /**
     * 判断URL是否包含id，若包含则为重新编辑文章，否则为新建文章.
     */
    if (isset($_GET['id'])) {
        $act     = 1; //编辑文章标记，与前端交互
        $article = new Post;
        $article->LoadInfoByID((integer) $_GET['id']);

        //判断是否为Editormd创建的文章
        if (null === $article->Metas->md_content) {
            /**
             * 非 Editormd 创建的文章，需要先将 HTML 转为 markdown
             * 使用 HTML To Markdown for PHP.
             */
            // Composer Autoload
            include_once __DIR__ . '/vendor/autoload.php';
            $converter = new League\HTMLToMarkdown\HtmlConverter();
            //正文markdown
            try {
                $md_content = json_encode($converter->convert($article->Content));
            } catch (Exception $e) {
                $md_content = '';
            }
            //摘要markdown
            try {
                $md_intro = @json_encode($converter->convert($article->Intro));
            } catch (Exception $e) {
                $md_intro = '';
            }
        } else {
            //Editormd创建或编辑过的文章
            $md_content = json_encode($article->Metas->md_content);
            $md_intro   = json_encode($article->Metas->md_intro);
        }
    } else { //新建文章
        $act        = 0;
        $md_content = '';
        $md_intro   = '';
    }

    // 配置项
    $editor     = json_decode($zbp->Config('Editormd')->editor);
    $preview    = $editor->preview ? 'true' : 'false';  // 实时预览设置
    $autoheight = $editor->autoheight ? 'true' : 'false';  // 编辑器自动长高
    $emoji      = $editor->emoji ? 'true' : 'false';  //  emoji
    $intro      = $editor->intro;  // 是否预显示摘要编辑器
    // HTML 解析
    if (1 == $editor->htmldecode) {
        $htmlDecode = 'htmlDecode: true,';
    } elseif (2 == $editor->htmldecode) {
        $htmlDecode = 'htmlDecode: "' . $editor->htmlfilter . '",';
    } else {
        $htmlDecode = 'htmlDecode: false,';
    }
    // 扩展支持
    if ($editor->extras) {
        $tocm      = $editor->tocm ? 'true' : 'false';       //  tocm列表设置
        $tasklist  = $editor->tasklist ? 'true' : 'false';   //  GFM 任务列表设置
        $flowchart = $editor->flowchart ? 'true' : 'false';  // 流程图设置
        $katex     = $editor->katex ? 'true' : 'false';      //  Tex 科学公式语言设置
        $sdiagram  = $editor->sdiagram ? 'true' : 'false';   // 时序图/序列图设置
    } else {
        $tocm      = 'false';
        $tasklist  = 'false';
        $flowchart = 'false';
        $katex     = 'false';
        $sdiagram  = 'false';
    }
    $texurl = $editor->texurl; // Tex路径
    // 滚动
    if (1 == $editor->scrolling) {
        $scrolling = '"single"';
    } elseif (2 == $editor->scrolling) {
        $scrolling = 'true';
    } else {
        $scrolling = 'false';
    }

    // 动态主题 js 函数
    if ($editor->dynamictheme) {
        $dynamicfunction = '
function themeSelect(id, themes, lsKey, callback) {
    var select = $("#" + id);

    for (var i = 0, len = themes.length; i < len; i ++) {
        var theme    = themes[i];
        var selected = (localStorage[lsKey] == theme) ? " selected=\"selected\"" : "";
        select.append("<option value=\"" + theme + "\"" + selected + ">" + theme + "</option>");
    }

    select.bind("change", function(){
        var theme = $(this).val();
        if (theme === ""){
            alert("theme == \"\"");
            return false;
        }
        localStorage[lsKey] = theme;
        callback(select, theme);
    });

    return select;
}';
        $themeconfig = 'theme : (localStorage.theme) ? localStorage.theme : "' . $editor->toolbartheme .
            '",previewTheme : (localStorage.previewTheme) ? localStorage.previewTheme : "' . $editor->previewtheme .
            '",editorTheme : (localStorage.editorTheme) ? localStorage.editorTheme : "' . $editor->editortheme . '"';
        $select      = '$("span#msg").html(\'<span id="theme-select">动态主题：<select id="editormd-theme-select"><option selected="selected" value="">选择工具栏主题</option></select>&emsp;<select id="editor-area-theme-select"><option selected="selected" value="">选择编辑器主题</option></select>&emsp;<select id="preview-area-theme-select"><option selected="selected" value="">选择实时预览主题</option></select></span><a href="' . $plugin_url . '/main.php#tabs=editor" style="border:solid 1px rgb(221,221,221);padding:4px 10px;margin-left:15px;">设 置</a>\');';
        $themeSelect = 'themeSelect("editormd-theme-select", editormd.themes, "theme", function($this, theme){
        ContentEditor.setTheme(theme);
        IntroEditor.setTheme(theme);
    });

    themeSelect("editor-area-theme-select", editormd.editorThemes, "editorTheme", function($this, theme) {
        ContentEditor.setCodeMirrorTheme(theme);
        IntroEditor.setCodeMirrorTheme(theme);
        // or ContentEditor.setEditorTheme(theme);
    });

    themeSelect("preview-area-theme-select", editormd.previewThemes, "previewTheme", function($this, theme) {
        ContentEditor.setPreviewTheme(theme);
        IntroEditor.setPreviewTheme(theme);
    });';
    } else {
        $dynamicfunction = '';
        $themeconfig     = 'theme : "' . $editor->toolbartheme .
            '",previewTheme : "' . $editor->previewtheme .
            '",editorTheme : "' . $editor->editortheme . '"';
        $select      = '$("span#msg").html(\'<a href="' . $plugin_url . '/main.php#tabs=editor" style="border:solid 1px rgb(221,221,221);padding:4px 10px;position:relative;bottom:5px;float:right;">设 置</a>\');';
        $themeSelect = '';
    }

    /**
     * 预处理数据和界面
     * 定义并启动编辑器.
     */
    $script = <<<EOF
<script>
var ContentEditor, IntroEditor;

//动态主题函数
$dynamicfunction

$(function() {
    var intro = $("#tarea");

    // 内容编辑器初始化, 用于支持 editor_api
    function content_editor_init(obj) {
        editor_api.editor.content.obj=obj;//内容编辑器对象
        //内容编辑器api方法
        editor_api.editor.content.get=function(){return this.obj.getValue()};//获取编辑器所有内容
        editor_api.editor.content.put=function(str){return this.obj.setValue(str)};//设置编辑器的内容
        editor_api.editor.content.focus=function(){return this.obj.focus()};//让编辑器获得尾部焦点
        editor_api.editor.content.insert=function(str){return this.obj.insertValue(str);}//在光标处插入内容
        sContent=obj.getValue();
    }

    // 摘要编辑器初始化, 用于支持 editor_api
    function intro_editor_init(obj) {
        editor_api.editor.intro.obj=obj;//摘要编辑器对象
        //摘要编辑器api方法
        editor_api.editor.intro.get=function(){return this.obj.getValue()};
        editor_api.editor.intro.put=function(str){return this.obj.setValue(str)};
        editor_api.editor.intro.focus=function(){return this.obj.focus()};
        editor_api.editor.intro.insert=function(str){return this.obj.insertValue(str);}
        sIntro=obj.getValue();
    }

    // 检查摘要编辑器可见状态
    function intro_visible() {
        return intro.is(":visible");
    }

    // 使摘要编辑器获取焦点
    function scroll_intro_focus() {
        $("html, body").animate({scrollTop: $("#divIntro").offset().top}, "fast");
        editor_api.editor.intro.focus();
    }

    if ($act==1) { //编辑文章
        $("textarea#editor_content").val($md_content);
        $("textarea#editor_intro").val($md_intro);
    }

    localStorage["theme"]        = "$editor->toolbartheme";
    localStorage["editorTheme"]  = "$editor->editortheme";
    localStorage["previewTheme"] = "$editor->previewtheme";

    //编辑器上方动态主题下拉选择框
    $select

    // 自定义 Emoji 的 url 路径
    editormd.emoji = {
        path : "$plugin_url/images/github-emojis/",
        ext  : ".png"
    };

    // 自定义 Katex 地址
    editormd.katexURL = {
        js  : "$texurl",
        css : "$texurl"
    };

    // 内容编辑器
    ContentEditor = editormd("carea", {
        width: "100%",
        height: 640,
        path : "$plugin_url/lib/",
        toolbarIcons : function() {
            return editormd.toolbarModes["full"]; // full, simple, mini
        },
        $themeconfig,
        codeFold : true,
        syncScrolling : $scrolling,
        saveHTMLToTextarea : true,    // 保存 HTML 到 Textarea
        searchReplace : true,
        autoHeight : $autoheight,
        watch : $preview,  // 实时预览
        $htmlDecode // HTML 标签解析
        emoji : $emoji,
        taskList : $tasklist,  // Github Flavored Markdown 任务列表
        toc : $tocm,
        tocm : $tocm,         // Using [TOCM]
        tex : $katex,                   // 科学公式TeX语言支持，默认关闭
        flowChart : $flowchart,             // 流程图支持，默认关闭
        sequenceDiagram : $sdiagram,       // 时序/序列图支持，默认关闭,
        imageUpload : true,
        imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
        imageUploadURL : "$plugin_url/php/upload.php",
        crossDomainUpload : false,
        autoFocus : false,
        onfullscreen : function() {
            this.editor.css("margin-top", "0");
        },
        onfullscreenExit : function() {
            this.editor.css("margin-top", "5px");
        }
    });

    // 动态主题
    $themeSelect

    // 摘要编辑器
    $("#divIntro").show(); // 显示摘要区块
    $("#insertintro").html(""); // 清除摘要提示内容
    $("#theader").append("<span id=\"GenIntro\">生成摘要</span><span id=\"GenIntroHintBtn\">?</span>" +
        " <span id=\"GenIntroHint\">点击“摘要”，切换摘要编辑器的显示状态（可在Editormd配置中设定默认状态）；点击“生成摘要”，将会提取正文中首条分隔符以上的内容将作为摘要。（更多详情请查看Editormd帮助）</span>"); // 指示符和生成按钮
    // 动态生成摘要编辑器
    IntroEditor = editormd("tarea", {
        width: "100%",
        height: 300,
        path: "$plugin_url/lib/",
        saveHTMLToTextarea: true,
        onload: function() {
            if (this.id == "carea") {
                content_editor_init(this);
            } else if (this.id == "tarea") {
                this.config({
                    toolbarIcons: function() {
                        return ["undo", "redo", "|", "bold", "del", "italic", "|", "h1", "h2", "h3", "h4", "h5", "h6", "|", "list-ul", "list-ol", "|","watch"];
                    }
                });
                intro_editor_init(this);
                $("#emdLoadError").hide();
                // 根据用户设置隐藏摘要编辑器
                if (!$intro) {
                    $("#tarea").hide();
                }
            }
        }
    });
    $("#theader > .editinputname").click(function(){
        if (intro_visible()) {
            intro.hide();
        } else {
            intro.show();
            scroll_intro_focus();
        }
    });
    $("#GenIntro").click(function(){
        if (!intro_visible()) {
            intro.show();
        }
        var s = ContentEditor.getValue();
        var hr_index = s.indexOf("------------");
        if(hr_index == -1) {
            // 若没有横线，则截取前 250 个字符
            hr_index = 250;
        }
        IntroEditor.setValue(s.substr(0, hr_index));
        scroll_intro_focus();
    });
    $("#GenIntroHintBtn").click(function(){
        $("#GenIntroHint").toggle();
    });

    // 更新提示
    if ($("#editormd-dialog").length > 0) {
        $("body").css("overflow", "hidden");
        $("#editormd-dialog").dialog({
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
                    $.get("$plugin_url/php/config.php?action=offnotify&csrfToken=$csrf_token");
                }
            }]
        });
    }

    //保存 HTML 源码
    $("form#edit").submit(function(e){
        $("textarea[name='carea-html-code']").val(ContentEditor.getHTML());
        if(IntroEditor!=undefined){
            $("textarea[name='tarea-html-code']").val(IntroEditor.getHTML());
        }
        //event.preventDefault();
    });
});
//重定义原有函数
function editor_init(){}
</script>
<script src="$plugin_url/plugins/paste-upload.js"></script>
<style>
#carea {
    z-index: 100;
}
#taera {
    z-index: 99;
}
#divMain a, #divMain2 a {
    color: #666;
}
#divMain a:hover, #divMain2 a:hover {
    color: #666;
}
#divMain .editormd-preview-container a {
    color: #4183c4;
}
#divMain .editormd-preview-container ul,
#divMain .editormd-preview-container ul li {
    list-style-type: disc;
}
#divMain .editormd-preview-container ul ul,
#divMain .editormd-preview-container ul ul li {
    list-style-type: circle;
}
#divMain .editormd-preview-container ol,
#divMain .editormd-preview-container ol li {
    list-style-type: decimal;
}
.markdown-body ol {
    padding-left: 0;
}
#divMain .editormd-toc-menu ul,
#divMain .editormd-toc-menu ul li,
#divMain .editormd-toc-menu ul ul,
#divMain .editormd-toc-menu ul ul li {
    list-style-type: none;
}
.editormd-html-textarea {
    display: none;
}
span#theme-select select {
    height: 29px;
}
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

    echo $script;
}

/**
 * 在内容文本框下方插入，
 * 用于存放 Editor.md 转换的 HTML 源码、更新提示
 * 以及加载提示，每次加载成功后会将此内容隐藏.
 */
function Response1_Editormd()
{
    global $zbp;

    $plugin_config = json_decode($zbp->Config('Editormd')->plugin);
    $content       = '
    <textarea class="editormd-html-textarea" name="carea-html-code"></textarea>
    <textarea class="editormd-html-textarea" name="tarea-html-code"></textarea>
    <div id="emdLoadError">
        <div style="font-size: 20px">Editormd 编辑器启动中……</div>
        <div style="color: #646464">如果这条消息一直显示，说明启动失败，请<a href="' . $zbp->host . 'zb_users/plugin/Editormd/main.php#tabs=help" target="_blank" style="text-decoration:underline">点击此处查看解决方案</a></div>
    </div>';

    if ($plugin_config->notify) {
        $php_alert = version_compare(PHP_VERSION, '5.3.29', '<') ? '<p style="color:red">【重要提示】<br>您的 PHP 版本过低，Editormd 不再支持 PHP 5.3.29 以下的版本，请及时升级PHP，强烈建议使用 PHP7</p><br>' : '';
        $content .= '<div id="editormd-dialog" title="Editormd ' . $plugin_config->version . ' 更新提示" style="z-index:9999;display:none">' . $php_alert . '<p><strong>【更新内容】</strong></p><p>● 新增自定义编辑页面附加样式</p><p>● 编辑器默认字体大小改为16px</p><p>● 升级 html-to-markdown 至 4.9.1</p><p>● 升级 Parsedown 至 1.7.4</p></div>';
    }

    echo $content;
}

/**
 * 前台扩展语言支持.
 */
function ExtraSupport_Editormd(&$template)
{
    global $zbp,
        $action,
        $mip_start;

    //搜索页直接返回
    if ('search' == $action) {
        return;
    }

    $plugin  = json_decode($zbp->Config('Editormd')->plugin);
    $article = $template->GetTags('article');

    if (empty($article->Content)) {
        $article->Content = '<!-- Editormd: Content is empty! -->';

        return;
    }

    $doc = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $doc->loadHTML($article->Content); //加载文章正文内容
    $xpath = new DOMXPath($doc);

    // 检测当前主题是否启用了官方MIP插件依赖，并兼容第三方MIP主题
    if ($mip_start || $plugin->mipsupport) {
        if (false !== strpos($article, '[TOC]') || false !== strpos($article, '[TOCM]')) {
            $titles_html = '<div class="emd-toc"><div class="emd-toc-title">内容导航</div>';
            $headings    = $xpath->query('//h1 | //h2 | //h3 | //h4 | //h5 | //h6');
            foreach ($headings as $head) {
                $titles_html .= '<div class="emd-toc-item emd-toc-h' . substr($head->tagName, 1) . '"><a href="#' . trim($head->textContent) . '">' . $head->textContent . '</a></div>';
            }
            $titles_html .= '</div>';
            $zbp->header .= '<link rel="stylesheet" type="text/css" href="' . str_replace('mip/', '', substr($zbp->host, 5)) . 'zb_users/plugin/Editormd/css/mipsupport.css">';
            $article->Content = str_replace('[TOCM]', '', str_replace('[TOC]', '', $article->Content));
            $article->Content = $titles_html . $article->Content;
        }

        return;
    }
    //配置项
    $editor            = json_decode($zbp->Config('Editormd')->editor);
    $editor->emoji     = $editor->emoji ? 'true' : 'false';
    $editor->tocm      = $editor->tocm ? 'true' : 'false';
    $editor->tasklist  = $editor->tasklist ? 'true' : 'false';
    $editor->katex     = $editor->katex ? 'true' : 'false';
    $editor->flowchart = $editor->flowchart ? 'true' : 'false';
    $editor->sdiagram  = $editor->sdiagram ? 'true' : 'false';

    if (1 == $editor->htmldecode) {
        $editor->htmldecode = 'htmlDecode: true';
    } elseif (2 == $editor->htmldecode) {
        $editor->htmldecode = 'htmlDecode: "' . $editor->htmlfilter . '"';
    } else {
        $editor->htmldecode = 'htmlDecode: false';
    }

    // 扩展功能支持
    if ($editor->extras && null !== $article->Metas->md_content) {
        // 自带扩展样式引入
        if ($plugin->extstyle) {
            $zbp->header .= '<link rel="stylesheet" href="' . $zbp->host . 'zb_users/plugin/Editormd/css/editormd.preview.min.css">';
        }
        // 用户自定义扩展样式引入
        $zbp->header = $zbp->header . '<style>.editormd-html-preview{width:100%;margin:0;padding:0;}' . $plugin->cextstyle . '</style>';
        // 替换文章内容为 Markdown 原文
        $article->Content = '<div id="editormdContent"><textarea id="editormdTextarea" style="display:none;">' . $article->Metas->md_content . '</textarea></div>';
        // 动态渲染脚本
        $zbp->footer .= '<script src="' . $zbp->host . 'zb_users/plugin/Editormd/editormd.min.js"></script>
        <script src="' . $zbp->host . 'zb_users/plugin/Editormd/lib/marked.min.js"></script>
        <script src="' . $zbp->host . 'zb_users/plugin/Editormd/lib/prettify.min.js"></script>
        <script src="' . $zbp->host . 'zb_users/plugin/Editormd/lib/raphael.min.js"></script>';
        if ($editor->sdiagram) {
            $zbp->footer .= '<script src="' . $zbp->host . 'zb_users/plugin/Editormd/lib/underscore.min.js"></script>
            <script src="' . $zbp->host . 'zb_users/plugin/Editormd/lib/sequence-diagram.min.js"></script>';
        }
        if ($editor->flowchart) {
            $zbp->footer .= '<script src="' . $zbp->host . 'zb_users/plugin/Editormd/lib/flowchart.min.js"></script>
            <script src="' . $zbp->host . 'zb_users/plugin/Editormd/lib/jquery.flowchart.min.js"></script>';
        }
        $zbp->footer .= '<script>editormd.emoji={path: "' . $zbp->host . 'zb_users/plugin/Editormd/images/github-emojis/",ext:".png"};';
        if ($editor->katex) {
            $zbp->footer .= 'editormd.katexURL={js:"' . $editor->texurl . '",css:"' . $editor->texurl . '"};';
        }
        $zbp->footer .= '$(function(){
            editormd.markdownToHTML("editormdContent", {
                emoji           : ' . $editor->emoji . ',
                toc             : ' . $editor->tocm . ',
                tocm            : ' . $editor->tocm . ',
                taskList        : ' . $editor->tasklist . ',
                tex             : ' . $editor->katex . ',
                flowChart       : ' . $editor->flowchart . ',
                sequenceDiagram : ' . $editor->sdiagram . ',
                ' . $editor->htmldecode . '
            });
        });</script>';
    } elseif (
        $zbp->option['ZC_SYNTAXHIGHLIGHTER_ENABLE'] &&
        $xpath->query('//pre')->length > 0 &&
        'off' != $plugin->codetheme
    ) {
        //使用插件自带代码高亮
        $codetheme = 'prettifylight';
        $linenums  = '';
        if (false !== stristr($plugin->codetheme, 'dark')) {
            $codetheme = 'prettifymonokai';
        }
        if (false !== stristr($plugin->codetheme, '0')) {
            $linenums = '<style>pre ol.linenums,pre ol.linenums li{list-style:none !important;margin-left:0 !important;}</style>';
        }
        $zbp->header .= '<link rel="stylesheet" href="' . $zbp->host . 'zb_users/plugin/Editormd/css/' . $codetheme . '.css"><script src="' . $zbp->host . 'zb_users/plugin/Editormd/lib/prettify.min.js"></script>' . $linenums;
        $zbp->footer .= '<script>$(function(){ $("pre").addClass("prettyprint linenums"); prettyPrint(); });</script>';
    }
}

/**
 * 文章内容提交处理.
 */
function PostData_Editormd(&$article)
{
    // 保存原始markdown数据至扩展元数据
    $article->Metas->md_content = $article->Content;
    $article->Metas->md_intro   = $article->Intro;

    //获取正文的HTML源码
    $html_content     = $_POST['carea-html-code'];
    $article->Content = $html_content;

    //获取摘要的HTML源码
    if (empty($_POST['tarea-html-code'])) {
        // 处理系统自动生成的摘要
        // Composer Autoload
        include_once __DIR__ . '/vendor/autoload.php';
        $Parsedown      = new Parsedown();
        $article->Intro = $Parsedown->text($article->Intro);
        // 检查分隔符
        if (preg_match('/<hr.*?>/i', $article->Intro)) {
            $intro_arr      = preg_split('/<hr.*?>/i', $article->Intro);
            $article->Intro = $intro_arr[0];
        }
        // 将<!--autointro-->标记放到最后
        if (false !== stripos($_POST['Intro'], '<!--autointro-->')) {
            $article->Intro = str_replace('<!--autointro-->', '', $article->Intro);
            $article->Intro = $article->Intro . '<!--autointro-->';
        }
    } else {
        $article->Intro = $_POST['tarea-html-code'];
    }
    $article->Save();
}

/**
 * 更新配置
 *
 * @return void
 */
function UpdateConfig_Editormd()
{
    global $zbp;

    // 获取旧配置
    $editor            = json_decode($zbp->Config('Editormd')->editor, true);
    $plugin            = json_decode($zbp->Config('Editormd')->plugin, true);
    $plugin['version'] = 2.91;
    $plugin['notify']  = 1;

    // v2.88 新增
    if (!array_key_exists('extstyle', $plugin)) {
        $plugin['extstyle'] = 1;
    }
    if (!array_key_exists('cextstyle', $plugin)) {
        $plugin['cextstyle'] = '';
    }
    if (!array_key_exists('intro', $editor)) {
        $editor['intro'] = 0;
    }

    // v2.91 新增
    if (!array_key_exists('editorstyle', $editor)) {
        $editor['editorstyle'] = '/* 编辑区域 */.editormd .CodeMirror pre {font-size: 16px;} /* 预览区域 */.editormd-preview-container p {font-size: 16px;}';
    }

    $zbp->Config('Editormd')->editor = json_encode($editor);
    $zbp->Config('Editormd')->plugin = json_encode($plugin);

    $zbp->SaveConfig('Editormd');
}

/**
 * 重置设置
 *
 * @param boolean $del 是否删除已有配置
 */
function ResetConfig_Editormd($del = false)
{
    global $zbp;

    if ($del) {
        $zbp->DelConfig('Editormd');
    }

    // 编辑器配置
    $zbp->Config('Editormd')->editor = json_encode(array(
        'toolbartheme' => 'default',  // 工具栏主题设置
        'editortheme'  => 'default',  // 编辑区主题设置
        'previewtheme' => 'default',  // 预览区主题设置
        'preview'      => 1,     // 实时预览
        'autoheight'   => 0,    // 编辑器自动长高
        'scrolling'    => 2,     // 编辑器滚动，0禁用，1单向，2双向
        'dynamictheme' => 1,     // 动态主题
        'emoji'        => 0,    // emoji 配置
        'editorstyle'  => '/* 编辑区域 */.editormd .CodeMirror pre {font-size: 16px;} /* 预览区域 */.editormd-preview-container p {font-size: 16px;}',   // 编辑页面附加样式
        'extras'       => 0,    // 扩展支持
        'htmldecode'   => 0,    // HTML 解析，0关闭，1开启，2规则
        'tocm'         => 0,    // TOCM 列表
        'tasklist'     => 0,    // GFM 任务列表
        'flowchart'    => 0,    // 流程图
        'katex'        => 0,    // Tex 科学公式语言
        'sdiagram'     => 0,    // 时序图/序列图
        'htmlfilter'   => 'style,script,iframe|on*', // HTML 解析过滤标签
        'texurl'       => $zbp->host . 'zb_users/plugin/Editormd/lib/katex/katex.min',  // Katex路径
        'intro'        => 0  // 是否显示摘要编辑器
    ));

    // 插件配置
    $zbp->Config('Editormd')->plugin = json_encode(array(
        'version'       => 2.91, //版本号
        'notify'        => 1, //更新提示
        'keepconfig'    => 1, //卸载时保留配置
        'mipsupport'    => 0,    // 兼容第三方 MIP 主题
        'codetheme'     => 'light_0',  // 前台代码主题
        'keepmeta'      => 1,  // 默认保存扩展元数据
        'extstyle'      => 1,  // 自带扩展样式
        'cextstyle'     => ''  // 自定义扩展样式
    ));

    $zbp->SaveConfig('Editormd');
}

/**
 * 插件安装激活时执行函数.
 */
function InstallPlugin_Editormd()
{
    global $zbp;

    // 若不存在配置则初始化配置
    if (!$zbp->HasConfig('Editormd')) {
        ResetConfig_Editormd(false);
    }
}

/**
 * 插件卸载时执行函数
 */
function UninstallPlugin_Editormd()
{
    global $zbp;

    // 删除配置
    if (!json_decode($zbp->Config('Editormd')->plugin)->keepconfig) {
        $zbp->DelConfig('Editormd');
    }
}
