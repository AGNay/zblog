<?php
/**
* IframeVO 插件嵌入页
*
* @author  心扬 <chrishyze@163.com>
*/

// 注册插件
RegisterPlugin('IframeVO', 'ActivePlugin_IframeVO');

/**
 * 挂载系统接口
 */
function ActivePlugin_IframeVO()
{
    //3号输出接口：文章编辑页右侧底部
    Add_Filter_Plugin('Filter_Plugin_Edit_Response3', 'AddScript_IframeVO');

    //接口：自定义 CSP
    Add_Filter_Plugin('Filter_Plugin_CSP_Backend', 'CSP_IframeVO');
}

/**
 * 添加 Content Security Policy 规则
 *
 * @param array $defaultCSP
 */
function CSP_IframeVO(&$defaultCSP)
{
    global $zbp;

    $csp = json_decode($zbp->Config('IframeVO')->csp);
    if (!array_key_exists('frame-src', $defaultCSP)) {
        $defaultCSP['frame-src'] = '';
    }
    // 添加 self
    if (false === stripos($defaultCSP['frame-src'], 'self')) {
        $defaultCSP['frame-src'] .= ' \'self\'';
    }
    // 添加用户自定义规则
    foreach ($csp as $value) {
        if (false === stripos($defaultCSP['frame-src'], $value)) {
            $defaultCSP['frame-src'] .= ' '.$value;
        }
    }
}

/**
 * 添加 iframe 的脚本代码
 */
function AddScript_IframeVO()
{
    global $zbp;

    echo '
<div id="iframeVOWidget">
    <div id="iframeVOAdd">
        添加 iframe 视频
    </div>
    <div id="iframeVOSetting"></div>
</div>
<div id="iframeVOAddDialog" title="添加 iframe 视频" style="display:none;z-index:9999;">
    <div class="iframevo__item">
        宽高比例：
        <input type="radio" name="iframeVORatio" value="75" checked> 4:3 &nbsp;&nbsp;&nbsp;
        <input type="radio" name="iframeVORatio" value="56.25"> 16:9 &nbsp;&nbsp;&nbsp;
        <input type="radio" name="iframeVORatio" value="62.5"> 16:10
    </div>
    <div class="iframevo__item">
        <input type="checkbox" id="iframeVOHTML5Format" name="iframeVOHTML5Format" checked> HTML5 格式化
    </div>
    <div class="iframevo__item">
        <textarea id="iframeVOText" placeholder="请输入 iframe 代码"></textarea>
    </div>
</div>
<style>
#iframeVOWidget {
    position: relative;
    width: 100%;
    height: 38px;
    margin: 10px 0;
}
#iframeVOAdd,
#iframeVOSetting {
    position: absolute;
    border: 1px solid #707070;
    cursor: pointer;
    height: 36px;
}
#iframeVOAdd {
    right: 36px;
    border-right: none;
    border-radius: 8px 0 0 8px;
    width: 150px;
    line-height: 36px;
    text-align: center;
}
#iframeVOSetting {
    right: 0;
    border-radius: 0 8px 8px 0;
    width: 36px;
    background-position: center center;
    background-repeat: no-repeat;
    background-image: url('.$zbp->host.'zb_users/plugin/IframeVO/images/setting.svg);
    background-size: 30px 30px;
}
.iframevo__item {
    padding: 5px 0 10px 0;
}
textarea#iframeVOText {
    max-width: 100%;
    min-width: 100%;
    min-height: 170px;
    max-height: 450px;
    width: 100%;
    height: 170px;
    padding: 10px;
    font-size: 16px;
    line-height: 150%;
}
.ui-dialog-titlebar-close,
#iframeVOHTML5Format {
    outline: 0 !important;
}
</style>
<script>
$(function(){
    // 监听视频比例及格式化
    $("input[name=\"iframeVORatio\"], input[name=\"iframeVOHTML5Format\"]").on("change", function(e) {
        var key, value, config;
        if (e.currentTarget.type === "radio") {
            key = "ratio";
            value = e.currentTarget.value;
        } else if (e.currentTarget.type === "checkbox") {
            key = "format";
            value = e.currentTarget.checked;
        }
        if (typeof localStorage !== "undefined") {
            if (localStorage.getItem("iframeVO")) {
                config = JSON.parse(localStorage.getItem("iframeVO"));
            } else {
                config = {
                    ratio: 75,
                    format: true
                };
            }
            config[key] = value;
            localStorage.setItem("iframeVO", JSON.stringify(config));
        }
    });
    // 跳转至设置页面
    $("#iframeVOSetting").click(function() {
        window.location.href = "'.$zbp->host.'zb_users/plugin/IframeVO/main.php";
    });
    // 弹窗添加框
    $("#iframeVOAdd").click(function() {
        /**
         * 将文本框中的内容解析为 iframe 节点数组
         *
         * @param {boolean} format  是否进行 HTML5 格式化
         * @return {array}
         */
        function parseToIframeNodes(format) {
            var iframes = [],
                nodes = $.parseHTML($("#iframeVOText").val());

            if (!nodes) {
                return [];
            }

            for (var i = 0; i < nodes.length; i++) {
                if (nodes[i].nodeType === Node.ELEMENT_NODE && nodes[i].nodeName === "IFRAME") {
                    var node = nodes[i];
                    if (format) {
                        // HTML5 格式化
                        var iframe = document.createElement("IFRAME");
                        if (node.src.toLowerCase().indexOf("http:") === 0) {
                            iframe.setAttribute("src", node.src.replace("http:", "https:"));
                        } else if (node.src.indexOf("//") === 0) {
                            iframe.setAttribute("src", "https:" + node.src);
                        } else {
                            iframe.setAttribute("src", node.src);
                        }
                        if (node.allow) {
                            if (node.allowFullscreen && node.allow.toLowerCase().indexOf("fullscreen") === -1) {
                                iframe.setAttribute("allow", node.allow + " fullscreen");
                            } else {
                                iframe.setAttribute("allow", node.allow);
                            }
                        } else {
                            if (node.allowFullscreen) {
                                iframe.setAttribute("allow", "fullscreen");
                            }
                        }
                        if (node.allowFullscreen) {
                            iframe.setAttribute("allowfullscreen", "");
                        }
                        iframes.push(iframe);
                    } else {
                        iframes.push(node);
                    }
                }
            }

            return iframes;
        }
        /**
         * 生成插入的内容
         */
        var generateHTML = function() {
            var html = "",
                iframes = parseToIframeNodes($("#iframeVOHTML5Format").is(":checked"));
            for (var i = 0; i < iframes.length; i++) {
                if (iframes[i].src.toLowerCase().indexOf("tv.sohu.com") !== -1) {
                    // 搜狐视频屏蔽滚动条
                    iframes[i].setAttribute("scrolling", "no");
                }
                if (iframes[i].src.toLowerCase().indexOf("player.bilibili.com") !== -1) {
                    // 哔哩哔哩添加外阴影
                    iframes[i].setAttribute("style", "position:absolute;width:100%;height:100%;left:0;top:0;border:none;box-shadow:0 0 8px #e5e9ef;");
                } else {
                    iframes[i].setAttribute("style", "position:absolute;width:100%;height:100%;left:0;top:0;border:none;");
                }
                html = html + "<div style=\"position:relative;width:100%;height:0;padding-bottom:" + $("input[name=\"iframeVORatio\"]:checked").val() + "%;\">" + iframes[i].outerHTML + "</div>";
            }
            return html;
        };
        // 视频比例与格式化的初始化
        if (typeof localStorage !== "undefined") {
            if (localStorage.getItem("iframeVO")) {
                var config = JSON.parse(localStorage.getItem("iframeVO")),
                    $ratio = $("input[name=\"iframeVORatio\"]"),
                    $format = $("input[name=\"iframeVOHTML5Format\"]");
                $ratio.removeAttr("checked");
                if (config.ratio === "56.25") {
                    $ratio.eq(1).prop("checked", "checked");
                } else if (config.ratio === "62.5") {
                    $ratio.eq(2).prop("checked", "checked");
                } else {
                    $ratio.eq(0).prop("checked", "checked");
                }
                if (config.format) {
                    $format.attr("checked", "");
                } else {
                    $format.removeAttr("checked");
                }
            }
        }
        $("body").css("overflow", "hidden");
        $("#iframeVOAddDialog").dialog({
            width: 640,
            modal: true,
            resizable: false,
            draggable: false,
            beforeClose: function() {
                $(this).dialog("destroy");
                $("body").css("overflow", "auto");
            },
            buttons: [{
                text: "添加至光标处",
                click: function() {
                    editor_api.editor.content.insert(generateHTML());
                    $(this).dialog("close");
                }
            }, {
                text: "添加至文章末尾",
                click: function() {
                    if (typeof UE !== "undefined") {
                        editor_api.editor.content.put("<p><br></p>" + editor_api.editor.content.get() + generateHTML() + "<p><br></p>");
                    } else {
                        editor_api.editor.content.put(editor_api.editor.content.get() + generateHTML());
                    }
                    $(this).dialog("close");
                    editor_api.editor.content.focus();
                }
            }]
        });
        // 打开弹窗自动获取焦点
        $("#iframeVOText").focus();
    });
});
</script>
    ';
}

/**
 * 插件安装激活时执行函数
 */
function InstallPlugin_IframeVO()
{
    global $zbp;

    // 若不存在配置则初始化配置
    if (!$zbp->HasConfig('IframeVO')) {
        $zbp->Config('IframeVO')->keepconfig = 1;
        $zbp->Config('IframeVO')->csp = json_encode([
            'player.bilibili.com',
            'www.youtube.com',
            'v.qq.com',
            'open.iqiyi.com',
            'player.youku.com',
            'tv.sohu.com',
        ], JSON_UNESCAPED_SLASHES); // 只考虑 frame-src
        $zbp->SaveConfig('IframeVO');
    }
}

/**
 * 插件卸载时执行函数
 */
function UninstallPlugin_IframeVO()
{
    global $zbp;

    // 删除配置
    if ($zbp->HasConfig('IframeVO') && !$zbp->Config('IframeVO')->keepconfig) {
        $zbp->DelConfig('IframeVO');
    }
}
