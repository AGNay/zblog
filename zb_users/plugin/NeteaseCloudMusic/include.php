<?php
/**
* 网易云音乐 插件嵌入页
*
* NeteaseCloudMusic for Z-BlogPHP
*
* @author  心扬 <chrishyze@163.com>
*/

//注册插件
RegisterPlugin('NeteaseCloudMusic', 'ActivePlugin_NeteaseCloudMusic');

/**
 * 挂载系统接口
 */
function ActivePlugin_NeteaseCloudMusic()
{
    global $zbp;

    //3号输出接口：文章编辑页右侧底部
    Add_Filter_Plugin('Filter_Plugin_Edit_Response3', 'EditWidget_NeteaseCloudMusic');

    //接口：文章编辑页加载前处理内容，输出位置在<head>尾部
    Add_Filter_Plugin('Filter_Plugin_Edit_Begin', 'EditHead_NeteaseCloudMusic');

    //接口：文章编辑页加载前处理内容，输出位置在<body>尾部
    Add_Filter_Plugin('Filter_Plugin_Edit_End', 'EditBody_NeteaseCloudMusic');

    //接口：处理文章页&搜索页模板
    Add_Filter_Plugin('Filter_Plugin_ViewPost_Template', 'ViewPost_NeteaseCloudMusic');

    // 接口：处理首页、列表页模板
    Add_Filter_Plugin('Filter_Plugin_ViewList_Template', 'ViewList_NeteaseCloudMusic');

    //接口：自定义CSP接口
    Add_Filter_Plugin('Filter_Plugin_CSP_Backend', 'CSP_NeteaseCloudMusic');

    // 旧版 ZBP 实现无缝播放的临时解决方案
    // 接口：首页、列表页起始处
    Add_Filter_Plugin('Filter_Plugin_ViewList_Begin', 'ViewListBegin_NeteaseCloudMusic');
    // 接口：前台自动视图尾部
    // Add_Filter_Plugin('Filter_Plugin_ViewAuto_End', 'ViewAutoEnd_NeteaseCloudMusic', PLUGIN_EXITSIGNAL_RETURN);

    //更新逻辑
    if (!$zbp->Config('NeteaseCloudMusic')->HasKey('plugin')) { // v1 版本
        ResetConfig_NeteaseCloudMusic(true);
    } else { // v2 及以后的版本
        if (json_decode($zbp->Config('NeteaseCloudMusic')->plugin)->version < 2.13) {
            UpdateConfig_NeteaseCloudMusic();
        }
    }
}

/**
 * 添加 Content Security Policy 规则
 */
function CSP_NeteaseCloudMusic(&$defaultCSP)
{
    $addition = [];
    $policies = ['worker-src', 'frame-src'];
    foreach ($policies as $policy) {
        if (array_key_exists($policy, $defaultCSP)) {
            if (false === stripos($defaultCSP[$policy], 'self')) {
                $addition[$policy] = '\'self\' music.163.com ' . $defaultCSP[$policy];
            } else {
                $addition[$policy] = $defaultCSP[$policy] . ' music.163.com';
            }
        } else {
            $addition[$policy] = '\'self\' music.163.com';
        }
    }
    $defaultCSP = array_merge($defaultCSP, $addition);
}

/**
 * <head>末尾引入资源
 */
function EditHead_NeteaseCloudMusic()
{
    global $zbp;
    $config = json_decode($zbp->Config('NeteaseCloudMusic')->player);

    echo '<link rel="stylesheet" href="' . $zbp->host . 'zb_users/plugin/NeteaseCloudMusic/static/thirdparty/layui/css/layui.css">';

    echo '<link rel="stylesheet" href="' . $zbp->host . 'zb_users/plugin/NeteaseCloudMusic/static/style/widget.min.css">';

    echo '<link rel="stylesheet" href="' . $zbp->host . 'zb_users/plugin/NeteaseCloudMusic/static/style/edit.min.css">';
}

/**
 * 右侧边栏页面控件
 */
function EditWidget_NeteaseCloudMusic()
{
    global $zbp;

    $config_player = json_decode($zbp->Config('NeteaseCloudMusic')->player); ?>

    <div id="ncmWrapper" class="ncm-wrapper <?php if (!$config_player->article->widgetSize) echo 'ncm-wrapper_mini'; ?>">
        <div id="ncmWidget" class="ncm-widget <?php if (!$config_player->article->widgetSize) echo 'ncm-widget_mini'; ?>">
            <div class="ncmTopbar">
                <div id="ncmWidgetLogo" title="网易云音乐"></div>
                <div id="ncmBtnSetting" title="设置"></div>
            </div>
            <div id="ncmBtnsGroup">
                <div id="ncmBtnInsert" title="插入歌曲"></div>
                <div id="ncmBtnSearch" title="搜索歌曲"></div>
            </div>
        </div>
    </div>
    <style>
    /* 按钮图标 */
    #ncmWidgetLogo {
        background-image: url(<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/images/topbar.png);
    }
    #ncmBtnSetting {
        background-image: url(<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/images/settings.svg);
    }
    #ncmBtnInsert {
        background-image: url(<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/images/add.svg);
    }
    #ncmBtnSearch {
        background-image: url(<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/images/search.svg);
    }
    </style>
    <script>
    $(function() {
        // 最小化按钮
        $("#ncmWidgetLogo").click(function() {
            $("#ncmWidget").toggleClass("ncm-widget_mini");
            $("#ncmWrapper").toggleClass("ncm-wrapper_mini");
        });
        //设置按钮
        $("#ncmBtnSetting").click(function() {
            window.location.href = "<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/main.php";
        });
    });
    </script>

    <?php
}

/**
 * 音乐添加器窗口
 * 后台文章编辑页面
 */
function EditBody_NeteaseCloudMusic()
{
    global $zbp;

    $csrf_token         = $zbp->GetCSRFToken('NeteaseCloudMusic');
    $config_player_json = $zbp->Config('NeteaseCloudMusic')->player;
    $plugin_config      = json_decode($zbp->Config('NeteaseCloudMusic')->plugin); ?>

    <div class="display-none" id="ncmModalInserter">
        <div class="ncm-ins-topbar">
            <div class="ncm-ins-logo"></div>
            <div id="ncmInsTitle"></div>
            <div id="ncmInsClose" title="关闭"></div>
        </div>
        <div id="ncmInsContent">
            <div class="layui-tab" lay-filter="action-tabs">
                <ul class="layui-tab-title action-tabs">
                    <li lay-id="add" class="layui-this">添加</li>
                    <li lay-id="search">搜索</li>
                    <li id="officialLink">云音乐官网</li>
                </ul>

                <div class="layui-tab-content" id="ncmMainContent">
                    <div class="layui-tab-item layui-show">
                        <form action="" class="layui-form" lay-filter="ncmControlForm">
                            <div class="layui-form-item">
                                <label class="layui-form-label">播放器</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="articlePlayerType" value="0" title="网易官方播放器" lay-filter="articlePlayerType" checked>
                                    <input type="radio" name="articlePlayerType" value="1" title="APlayer" lay-filter="articlePlayerType">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" id="ncmResLabel">音乐ID</label>
                                <div class="layui-input-inline" style="width: 85px; margin-right: -1px">
                                    <select name="articleResourceType" lay-filter="articleResourceType">
                                        <option value="">请选择</option>
                                        <option value="2" selected>单曲</option>
                                        <option value="0">歌单</option>
                                        <option value="1">专辑</option>
                                    </select>
                                </div>
                                <div class="layui-input-inline" style="width: 300px">
                                    <input type="text" name="resid" lay-verify="required|number" placeholder="请输入音乐资源ID..." class="layui-input" value="" style="width: 370px">
                                </div>
                                <div class="layui-input-inline" style="width: 420px">
                                    <button class="layui-btn ncm-submit" lay-submit lay-filter="analyzeMusic">解析</button>
                                    <button class="layui-btn ncm-submit display-none" id="addMusic" lay-submit lay-filter="addMusic">追加</button>
                                    <div class="display-none" id="ncmGenLoading">
                                        <i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i> 解析中...
                                    </div>
                                </div>

                                <div id="ncmControlBtns">
                                    <button class="layui-btn ncm-submit display-none" id="ncmRegenerate">重新生成</button>
                                    <button class="layui-btn ncm-submit display-none" id="ncmInsert">插入音乐</button>
                                </div>
                            </div>
                        </form>

                        <form action="" class="layui-form" id="ncmPlayerForm" lay-filter="ncmPlayerForm">
                            <div class="ncm-preview-area">
                                <div class="ncm-player-preview" id="ncmPlayerPreview">
                                    <div class="ncm-preview-holder">播放器预览区域</div>
                                </div>
                            </div>
                            <div class="ncm-control-area">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">自动播放</label>
                                    <div class="layui-input-inline checkbox-inline">
                                        <input type="checkbox" name="ncmPlayerAuto" lay-skin="switch" lay-filter="ncmPlayerAuto">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">尺寸</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="ncmPlayerHeight" value="32" title="小" lay-filter="ncmPlayerHeight">
                                        <input type="radio" name="ncmPlayerHeight" value="66" title="中" lay-filter="ncmPlayerHeight" checked>
                                        <input type="radio" name="ncmPlayerHeight" value="90" title="大" lay-filter="ncmPlayerHeight">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">宽度</label>
                                    <div class="layui-input-inline">
                                        <div class="slider" id="ncmPlayerCustomWidth"></div>
                                    </div>
                                </div>
                                <blockquote class="layui-quote-nm" style="padding: 5px;">
                                    <p><b>提示：</b>一些音乐由于受到版权保护，将无法通过官方播放器播放。</p>
                                </blockquote>
                            </div>
                        </form>

                        <form action="" class="layui-form" id="aPlayerForm" lay-filter="aPlayerForm">
                            <div class="ncm-preview-area">
                                <div class="ncm-player-preview" id="aPlayerPreview">
                                <div class="ncm-preview-holder">播放器预览区域</div>
                                </div>
                            </div>
                            <div class="ncm-control-area">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">自动播放</label>
                                    <div class="layui-input-inline checkbox-inline">
                                        <input type="checkbox" name="aPlayerAuto" lay-skin="switch" lay-filter="aPlayerAuto">
                                    </div>
                                    <label class="layui-form-label">最小化</label>
                                    <div class="layui-input-inline checkbox-inline">
                                        <input type="checkbox" name="aPlayerMini" lay-skin="switch" lay-filter="aPlayerMini">
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">收起音乐列表</label>
                                    <div class="layui-input-inline checkbox-inline">
                                        <input type="checkbox" name="aPlayerListFolded" lay-skin="switch" lay-filter="aPlayerListFolded">
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">循环模式</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="aPlayerLoop" value="0" title="不循环" lay-filter="aPlayerLoop" checked>
                                        <input type="radio" name="aPlayerLoop" value="1" title="单曲循环" lay-filter="aPlayerLoop">
                                        <input type="radio" name="aPlayerLoop" value="2" title="全部循环" lay-filter="aPlayerLoop">
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">随机播放</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="aPlayerOrder" value="0" title="顺序播放" lay-filter="aPlayerOrder" checked>
                                        <input type="radio" name="aPlayerOrder" value="1" title="随机播放" lay-filter="aPlayerOrder">
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">预加载</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="aPlayerPreload" value="0" title="无" lay-filter="aPlayerPreload" checked>
                                        <input type="radio" name="aPlayerPreload" value="1" title="元信息" lay-filter="aPlayerPreload">
                                        <input type="radio" name="aPlayerPreload" value="2" title="全部" lay-filter="aPlayerPreload">
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">默认音量</label>
                                    <div class="layui-input-inline">
                                        <div class="slider" id="aPlayerVolume"></div>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">宽度</label>
                                    <div class="layui-input-inline">
                                        <div class="slider" id="aPlayerWidth"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="layui-tab-item">
                        <form action="" class="layui-form" lay-filter="ncmSearchForm">
                            <div class="layui-form-item">
                                <label class="layui-form-label">关键词</label>
                                <div class="layui-input-inline" style="width: 300px">
                                    <input type="text" name="keywords" lay-verify="required" placeholder="多个关键词请用空格隔开" class="layui-input" style="width: 370px">
                                </div>
                                <div class="layui-input-inline">
                                    <button class="layui-btn ncm-submit" lay-submit lay-filter="ncmSearchSubmit">搜索</button>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">类型</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="type" lay-filter="ncmSearchType" value="1" title="单曲" checked>
                                        <input type="radio" name="type" lay-filter="ncmSearchType" value="1000" title="歌单">
                                        <input type="radio" name="type" lay-filter="ncmSearchType" value="10" title="专辑">
                                    </div>
                                    <div class="layui-form-mid layui-word-aux" id="ncmSearchTips"></div>
                                </div>
                            </div>
                        </form>
                        <div id="ncmSearchResult"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
    .ncm-ins-logo {
        background-image: url(<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/images/topbar.png);
    }
    #ncmInsClose {
        background-image: url(<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/images/close.svg);
    }
    ul.action-tabs li:nth-child(1) {
        background-image: url(<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/images/add.svg);
    }
    ul.action-tabs li:nth-child(2) {
        background-image: url(<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/images/search.svg);
    }
    ul.action-tabs li#officialLink {
        background-image: url(<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/images/link.svg);
        width: 122px;
        float: right;
        margin-right: 20px;
    }
    </style>
    <script>
    // 网易云音乐插件后台全局变量
    const NCMUSIC_ADMIN = {
        HOME_URL: "<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>",
        CSRF_TOKEN: "<?php echo $csrf_token; ?>",
        PLAYER_CONFIG: <?php echo $config_player_json; ?>,
        livePlayer: null,
        Functions: {}
    };
    </script>
    <script src="<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/thirdparty/layui/layui.js"></script>
    <script src="<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/script/admin-public.min.js"></script>
    <script src="<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic'; ?>/static/script/admin-edit.min.js"></script>

    <?php if ($plugin_config->notify): ?>
    <script>
    //更新提示
    $(function(){
        $("body").css("overflow", "hidden");
        $("#ncm-dialog").dialog({
            width: 500,
            modal: true,
            resizable: false,
            beforeClose: function(event, ui) {
                $(this).dialog("destroy");
                $("body").css("overflow", "auto");
            },
            buttons: [{
                text: "确认（此版本不再提示）",
                click: function() {
                    $(this).dialog("close");
                    $.get("<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic/php/config.php?action=offnotify&csrfToken=' . $csrf_token; ?>");
                }
            }]
        });
    });
    </script>
    <div id="ncm-dialog" title="网易云音乐插件 v<?php echo $plugin_config->version; ?> 更新提示" style="display:none;z-index:9999;">
        <p><strong>【更新内容】</strong></p>
        <p>● 修复 ZBP 1.6 更新后属性名称变动导致的BUG</p>
    </div>

    <?php endif;
}

/**
 * 全站播放器脚本
 *
 * @param $config string  APlayer 配置
 *
 * @return string
 */
function GlobalPlayerScript_NeteaseCloudMusic($config)
{
    global $zbp; ?>

    <script>
    ;((window) => {
    const documentReady = () => {
        const config = <?php echo $config; ?>;
        if (config.audioSto) {
            const audio = [];
            const promises = [];
            const audioRes = config.audioSto.split("_");
            const requestAudioRes = (resId) => {
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    const type = resId.substr(0, 1) === "p" ? "playlist" : resId.substr(0, 1) === "a" ? "album" : "song";
                    xhr.onload = (e) => {
                        if (e.currentTarget.readyState === 4 && e.currentTarget.status === 200) {
                            const response = JSON.parse(e.currentTarget.response);
                            const artists = [];
                            const pushAudio = (song) => {
                                for (const k in song.ar) {
                                    artists.push(song.ar[k].name);
                                }
                                audio.push({
                                    name: song.name,
                                    artist: artists.join("/"),
                                    url: song.url.url !== undefined ? song.url.url : song.url,
                                    cover: song.al.picUrl
                                });
                            };
                            if (type === "playlist") {
                                response.playlist.tracks.forEach((song) => {
                                    pushAudio(song);
                                    artists.splice(0, artists.length);
                                });
                            } else if (type === "album") {
                                response.songs.forEach((song) => {
                                    pushAudio(song);
                                    artists.splice(0, artists.length);
                                });
                            } else {
                                pushAudio(response.songs[0]);
                            }
                            resolve();
                        } else {
                            reject(e.currentTarget);
                        }
                    };
                    xhr.open("POST", "<?php echo $zbp->host . 'zb_users/plugin/NeteaseCloudMusic/php/api.php'; ?>");
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.send(`csrfToken=<?php echo $zbp->GetCSRFToken('NeteaseCloudMusic'); ?>&action=${type}&request=url&id=${resId.substr(1)}`);
                });
            }
            audioRes.forEach((resId) => {
                promises.push(requestAudioRes(resId));
            });
            Promise.all(promises)
            .then(() => {
                const aplayer = new APlayer({
                    container: document.getElementById("aplayer"),
                    fixed: true,
                    autoplay: config.autoplay === 1,
                    theme: config.theme,
                    loop: config.loop === 2 ? 'all' : config.loop === 1 ? 'one' : 'none',
                    order: config.order === 1 ? 'random' : 'list',
                    preload: config.preload === 2 ? 'auto' : config.preload === 1 ? 'metadata' : 'none',
                    volume: config.volume,
                    mutex: 1,
                    lrcType: 0,
                    audio: audio
                });
            }, (e) => {
                console.error('网易云音乐插件请求出错！', e);
            });
        }

        // 动态替换浏览器地址为 iframe 地址
        document.getElementById("MainPage").addEventListener("load", function () {
            let iframeContent = (this.contentWindow || this.contentDocument);
            if (iframeContent.document) {
                iframeContent = iframeContent.document;
            }
            if (typeof URLSearchParams !== "undefined") {
                let params = new URLSearchParams(iframeContent.location.href.split("?")[1]);
                params.delete("ncminframe");
                if (params.toString().length > 0) {
                    window.history.replaceState({}, iframeContent.title, "<?php echo $zbp->host; ?>?" + params.toString());
                } else {
                    window.history.replaceState({}, iframeContent.title, "<?php echo $zbp->host; ?>");
                }
            } else {
                window.history.replaceState({}, iframeContent.title, iframeContent.location.href.replace("?ncminframe=1", "").replace("&ncminframe=1", ""));
            }
        });
    };
    if (document.readyState === "complete") {
        documentReady();
    } else {
        document.addEventListener("DOMContentLoaded", documentReady);
    }})(window)
    </script>

    <?php
}

/**
 * 全站无缝播放器
 */
function GlobalGaplessPlayer_NeteaseCloudMusic()
{
    global $zbp;

    // 解析原网页内容
    include_once __DIR__ . '/php/simple_html_dom.php';
    $html = str_get_html($zbp->template->Output());

    // 根据 URL query 标识判断是否在 iframe 内
    if (false === stripos($_SERVER['REQUEST_URI'], 'ncminframe=1')) {
        $config = json_decode($zbp->Config('NeteaseCloudMusic')->player);

        if (parse_url($zbp->fullcurrenturl, PHP_URL_QUERY)) {
            $zbp->fullcurrenturl .= '&ncminframe=1';
        } else {
            $zbp->fullcurrenturl .= '?ncminframe=1';
        }
        // SEO 优化，继承原网页内容
        $title       = $html->find('title', 0);
        $title       = $title ? $title->plaintext : $GLOBALS['blogname'] . ' - ' . $GLOBALS['blogsubname'];
        $keywords    = $html->find('meta[name=keywords]', 0);
        $keywords    = $keywords ? $keywords->content : $GLOBALS['blogname'];
        $description = $html->find('meta[name=description]', 0);
        $description = $description ? $description->content : $GLOBALS['blogsubname'];
        $author      = $html->find('meta[name=author]', 0);
        $author      = $author ? $author->content : $GLOBALS['blogname'];

        // 移除挂载的插件函数
        $GLOBALS['hooks']['Filter_Plugin_Index_End'] = array(); ?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="author" content="<?php echo $author; ?>">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
    <meta name="renderer" content="webkit">
    <meta name="generator" content="Z-BlogPHP <?php echo ZC_VERSION_DISPLAY; ?>">
    <link rel="stylesheet" href="<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/static/thirdparty/aplayer/APlayer.min.css">
    <style>
        html,
        body {
            overflow: hidden;
        }
        iframe#MainPage {
            margin: 0;
            padding: 0;
            overflow: auto;
            border: none;
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
    <!--[if IE]>
    <script type="text/javascript">
    alert('本站音乐插件不支持 Internet Explorer (IE) 浏览器，请使用最新版的谷歌 Chrome、火狐 Firefox、Opera 或 Safari 等现代浏览器');
    </script>
    <![endif]-->
</head>
<body>
    <iframe id="MainPage" src="<?php echo $zbp->fullcurrenturl; ?>"></iframe>
    <div id="aplayer"></div>
    <script src="<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/static/thirdparty/aplayer/APlayer.min.js"></script>
    <?php GlobalPlayerScript_NeteaseCloudMusic(json_encode($config->global->APlayer)); ?>
</body>
</html>

    <?php
    } else {
        foreach ($html->find('a') as $element) {
            if (false !== stripos($element->href, $zbp->host) || 0 === stripos($element->href, '#')) {
                // 后台在新窗口打开
                if (false !== stripos($element->href, 'zb_system/')) {
                    $element->target = '_blank';
                    continue;
                }
                // 添加位于 iframe 内部的标识
                $url = parse_url($element->href);
                $host = $zbp->currenturl;
                $path = '';
                $query = '';
                $fragment = '';
                if (isset($url['host'])) {
                    $host = $url['scheme'] . '://' . $url['host'] . '/';
                }
                if (isset($url['path'])) {
                    $path = substr($url['path'], 1) . '?';
                }
                if (false === stripos($host, 'ncminframe')) {
                    if (isset($url['query'])) {
                        $query = $url['query'] . '&ncminframe=1';
                    } else {
                        $query = 'ncminframe=1';
                    }
                }
                if (isset($url['fragment'])) {
                    $fragment = '#' . $url['fragment'];
                }
                $element->href = $host . $path . $query . $fragment;
            } else {
                // 外链在新窗口打开
                $element->target = '_blank';
            }
        }
        echo $html;
    }
}

/**
 * 首页、列表页面实现无缝播放
 * 替换系统函数 ViewList() 逻辑
 * 因旧版 ZBP 中不支持退出信号
 * 此为实现无缝播放的临时解决方案
 *
 * @param array $args viewList() 原函数的参数 [BUG]传过来的 $args 总是为 NULL
 */
function ViewListBegin_NeteaseCloudMusic($args)
{
    global $zbp;
    $config = json_decode($zbp->Config('NeteaseCloudMusic')->player);

    if ($config->global->status && 1 == $config->global->gapless) {
        // 因系统传过来的 $args 总是为 null，这里再获取一遍参数
        $page      = GetVars('page', 'GET');
        $cate      = GetVars('cate', 'GET');
        $auth      = GetVars('auth', 'GET');
        $date      = GetVars('date', 'GET');
        $tags      = GetVars('tags', 'GET');
        $isrewrite = ('ACTIVE' === $zbp->option['ZC_STATIC_MODE'] || isset($_GET['rewrite']));

        $type = 'index';
        if (null !== $cate) {
            $type = 'category';
        }

        if (null !== $auth) {
            $type = 'author';
        }

        if (null !== $date) {
            $type = 'date';
        }

        if (null !== $tags) {
            $type = 'tag';
        }

        $category     = null;
        $author       = null;
        $datetime     = null;
        $tag          = null;
        $w            = array();
        $w[]          = array('=', 'log_IsTop', 0);
        $w[]          = array('=', 'log_Status', 0);
        $page         = 0 == (int) $page ? 1 : (int) $page;
        $articles     = array();
        $articles_top = array();

        switch ($type) {
            case 'index':
                $pagebar        = new Pagebar($zbp->option['ZC_INDEX_REGEX'], true, true);
                $pagebar->Count = $zbp->cache->normal_article_nums;
                $template       = $zbp->option['ZC_INDEX_DEFAULT_TEMPLATE'];
                if (1 == $page) {
                    $zbp->title = $zbp->subname;
                } else {
                    $zbp->title = str_replace('%num%', $page, $zbp->lang['msg']['number_page']);
                }
                break;
            case 'category':
                $pagebar  = new Pagebar($zbp->option['ZC_CATEGORY_REGEX']);
                $category = new Category();

                if (!is_array($cate)) {
                    $cateId = $cate;
                    $cate   = array();
                    if (false !== strpos($zbp->option['ZC_CATEGORY_REGEX'], '{%id%}')) {
                        $cate['id'] = $cateId;
                    }
                    if (false !== strpos($zbp->option['ZC_CATEGORY_REGEX'], '{%alias%}')) {
                        $cate['alias'] = $cateId;
                    }
                }
                if (isset($cate['id'])) {
                    $category = $zbp->GetCategoryByID($cate['id']);
                } else {
                    $category = $zbp->GetCategoryByAlias($cate['alias']);
                }

                if ('' == $category->ID) {
                    if (true == $isrewrite) {
                        return false;
                    }
                    $zbp->ShowError(2, __FILE__, __LINE__);
                }
                if (1 == $page) {
                    $zbp->title = $category->Name;
                } else {
                    $zbp->title = $category->Name . ' ' . str_replace('%num%', $page, $zbp->lang['msg']['number_page']);
                }
                $template = $category->Template;

                if (!$zbp->option['ZC_DISPLAY_SUBCATEGORYS']) {
                    $w[]            = array('=', 'log_CateID', $category->ID);
                    $pagebar->Count = $category->Count;
                } else {
                    $arysubcate   = array();
                    $arysubcate[] = array('log_CateID', $category->ID);
                    foreach ($zbp->categories[$category->ID]->ChildrenCategories as $subcate) {
                        $arysubcate[] = array('log_CateID', $subcate->ID);
                    }
                    $w[] = array('array', $arysubcate);
                }
                $pagebar->UrlRule->Rules['{%id%}']    = $category->ID;
                $pagebar->UrlRule->Rules['{%alias%}'] = '' == $category->Alias ? rawurlencode($category->Name) : $category->Alias;
                break;
            case 'author':
                $pagebar = new Pagebar($zbp->option['ZC_AUTHOR_REGEX']);
                $author  = new Member();

                if (!is_array($auth)) {
                    $authId = $auth;
                    $auth   = array();
                    if (false !== strpos($zbp->option['ZC_AUTHOR_REGEX'], '{%id%}')) {
                        $auth['id'] = $authId;
                    }
                    if (false !== strpos($zbp->option['ZC_AUTHOR_REGEX'], '{%alias%}')) {
                        $auth['alias'] = $authId;
                    }
                }
                if (isset($auth['id'])) {
                    $author = $zbp->GetMemberByID($auth['id']);
                } else {
                    $author = $zbp->GetMemberByNameOrAlias($auth['alias']);
                }

                if ('' == $author->ID) {
                    if ($isrewrite) {
                        return false;
                    }
                    $zbp->ShowError(2, __FILE__, __LINE__);
                }
                if (1 == $page) {
                    $zbp->title = $author->StaticName;
                } else {
                    $zbp->title = $author->StaticName . ' ' . str_replace('%num%', $page, $zbp->lang['msg']['number_page']);
                }
                    $template                             = $author->Template;
                    $w[]                                  = array('=', 'log_AuthorID', $author->ID);
                    $pagebar->Count                       = $author->Articles;
                    $pagebar->UrlRule->Rules['{%id%}']    = $author->ID;
                    $pagebar->UrlRule->Rules['{%alias%}'] = '' == $author->Alias ? rawurlencode($author->Name) : $author->Alias;
                break;
            case 'date':
                $pagebar = new Pagebar($zbp->option['ZC_DATE_REGEX']);

                if (!is_array($date)) {
                    $datetime = $date;
                } else {
                    $datetime = $date['date'];
                }

                $dateregex_ymd = '/[0-9]{1,4}-[0-9]{1,2}-[0-9]{1,2}/i';
                $dateregex_ym  = '/[0-9]{1,4}-[0-9]{1,2}/i';

                if (0 == preg_match($dateregex_ymd, $datetime) && 0 == preg_match($dateregex_ym, $datetime)) {
                    return false;
                }
                $datetime_txt = $datetime;
                $datetime     = strtotime($datetime);
                if (false == $datetime) {
                    return false;
                }

                if (0 != preg_match($dateregex_ymd, $datetime_txt) && isset($zbp->lang['msg']['year_month_day'])) {
                    $datetitle = str_replace(array('%y%', '%m%', '%d%'), array(date('Y', $datetime), date('n', $datetime), date('j', $datetime)), $zbp->lang['msg']['year_month_day']);
                } else {
                    $datetitle = str_replace(array('%y%', '%m%'), array(date('Y', $datetime), date('n', $datetime)), $zbp->lang['msg']['year_month']);
                }

                if (1 == $page) {
                    $zbp->title = $datetitle;
                } else {
                    $zbp->title = $datetitle . ' ' . str_replace('%num%', $page, $zbp->lang['msg']['number_page']);
                }

                $zbp->modulesbyfilename['calendar']->Content = ModuleBuilder::Calendar(date('Y', $datetime) . '-' . date('n', $datetime));
                $template                                    = $zbp->option['ZC_INDEX_DEFAULT_TEMPLATE'];

                if (0 != preg_match($dateregex_ymd, $datetime_txt)) {
                    $w[]                                 = array('BETWEEN', 'log_PostTime', $datetime, strtotime('+1 day', $datetime));
                    $pagebar->UrlRule->Rules['{%date%}'] = date('Y-n-j', $datetime);
                } else {
                    $w[]                                 = array('BETWEEN', 'log_PostTime', $datetime, strtotime('+1 month', $datetime));
                    $pagebar->UrlRule->Rules['{%date%}'] = date('Y-n', $datetime);
                }

                $datetime = Metas::ConvertArray(getdate($datetime));
                break;
            case 'tag':
                $pagebar = new Pagebar($zbp->option['ZC_TAGS_REGEX']);
                $tag     = new Tag();

                if (!is_array($tags)) {
                    $tagId = $tags;
                    $tags  = array();
                    if (false !== strpos($zbp->option['ZC_TAGS_REGEX'], '{%id%}')) {
                        $tags['id'] = $tagId;
                    }
                    if (false !== strpos($zbp->option['ZC_TAGS_REGEX'], '{%alias%}')) {
                        $tags['alias'] = $tagId;
                    }
                }
                if (isset($tags['id'])) {
                    $tag = $zbp->GetTagByID($tags['id']);
                } else {
                    $tag = $zbp->GetTagByAliasOrName($tags['alias']);
                }

                if (0 == $tag->ID) {
                    if (true == $isrewrite) {
                        return false;
                    }
                    $zbp->ShowError(2, __FILE__, __LINE__);
                }

                if (1 == $page) {
                    $zbp->title = $tag->Name;
                } else {
                    $zbp->title = $tag->Name . ' ' . str_replace('%num%', $page, $zbp->lang['msg']['number_page']);
                }

                $template                             = $tag->Template;
                $w[]                                  = array('LIKE', 'log_Tag', '%{' . $tag->ID . '}%');
                $pagebar->UrlRule->Rules['{%id%}']    = $tag->ID;
                $pagebar->UrlRule->Rules['{%alias%}'] = '' == $tag->Alias ? rawurlencode($tag->Name) : $tag->Alias;
                break;
            default:
                throw new Exception('Unknown type');
        }

        $pagebar->PageCount                  = $zbp->displaycount;
        $pagebar->PageNow                    = $page;
        $pagebar->PageBarCount               = $zbp->pagebarcount;
        $pagebar->UrlRule->Rules['{%page%}'] = $page;

        foreach ($GLOBALS['hooks']['Filter_Plugin_ViewList_Core'] as $fpname => &$fpsignal) {
            $fpname($type, $page, $category, $author, $datetime, $tag, $w, $pagebar, $template);
        }

        if (false == $zbp->option['ZC_LISTONTOP_TURNOFF']) {
            $articles_top_notorder = $zbp->GetTopArticle();
            foreach ($articles_top_notorder as $articles_top_notorder_post) {
                if ('global' == $articles_top_notorder_post->TopType) {
                    $articles_top[] = $articles_top_notorder_post;
                }
            }

            if ('index' == $type && 1 == $page) {
                foreach ($articles_top_notorder as $articles_top_notorder_post) {
                    if ('index' == $articles_top_notorder_post->TopType) {
                        $articles_top[] = $articles_top_notorder_post;
                    }
                }
            }

            if ('category' == $type) {
                foreach ($articles_top_notorder as $articles_top_notorder_post) {
                    if ('category' == $articles_top_notorder_post->TopType && $articles_top_notorder_post->CateID == $category->ID) {
                        $articles_top[] = $articles_top_notorder_post;
                    }
                }
            }
        }

        $select = '*';
        $order  = array('log_PostTime' => 'DESC');
        $limit  = array(($pagebar->PageNow - 1) * $pagebar->PageCount, $pagebar->PageCount);
        $option = array('pagebar' => $pagebar);

        foreach ($GLOBALS['hooks']['Filter_Plugin_LargeData_Article'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname($select, $w, $order, $limit, $option);
        }

        $articles = $zbp->GetArticleList(
            $select,
            $w,
            $order,
            $limit,
            $option,
            true
        );

        if (count($articles) <= 0 && $page > 1) {
            $zbp->ShowError(2, __FILE__, __LINE__);
        }

        $zbp->template->SetTags('title', $zbp->title);
        $zbp->template->SetTags('articles', array_merge($articles_top, $articles));

        if (0 == $pagebar->PageAll) {
            $pagebar = null;
        }

        $zbp->template->SetTags('pagebar', $pagebar);
        $zbp->template->SetTags('type', $type);
        $zbp->template->SetTags('page', $page);
        $zbp->template->SetTags('date', $datetime);
        $zbp->template->SetTags('tag', $tag);
        $zbp->template->SetTags('author', $author);
        $zbp->template->SetTags('category', $category);

        if ($zbp->template->hasTemplate($template)) {
            $zbp->template->SetTemplate($template);
        } else {
            $zbp->template->SetTemplate('index');
        }

        foreach ($GLOBALS['hooks']['Filter_Plugin_ViewList_Template'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname($zbp->template);
        }

        GlobalGaplessPlayer_NeteaseCloudMusic();
        // 阻止输出原页面
        $GLOBALS['hooks']['Filter_Plugin_ViewList_Begin']['ViewListBegin_NeteaseCloudMusic'] = PLUGIN_EXITSIGNAL_RETURN;
    }

    return true;
}

/**
 * 全站播放器（单页）
 */
function GlobalPlayer_NeteaseCloudMusic()
{
    global $zbp;
    $config = json_decode($zbp->Config('NeteaseCloudMusic')->player);

    $zbp->header .= '<link rel="stylesheet" href="' . $zbp->host . 'zb_users/plugin/NeteaseCloudMusic/static/thirdparty/aplayer/APlayer.min.css">';
    $zbp->footer .= '<div id="NCMPlayerContainer"></div><script src="' . $zbp->host . 'zb_users/plugin/NeteaseCloudMusic/static/thirdparty/aplayer/APlayer.min.js"></script>';
    $zbp->footer .= GlobalPlayerScript_NeteaseCloudMusic(json_encode($config->global->APlayer));
}

/**
 * 首页、列表页面模板处理
 *
 * - 实现全站播放器
 *
 * @param object $template 模板实例
 */
function ViewList_NeteaseCloudMusic(&$template)
{
    global $zbp;
    $config = json_decode($zbp->Config('NeteaseCloudMusic')->player);

    //全站播放器
    if ($config->global->status) {
        if (0 == $config->global->gapless) {
            GlobalPlayer_NeteaseCloudMusic();
        }
        /* 适用于新版本ZBP，旧版不支持退出信号
         elseif (1 == $config->global->gapless) {
            GlobalGaplessPlayer_NeteaseCloudMusic();
            $GLOBALS['hooks']['Filter_Plugin_ViewList_Template']['ViewList_NeteaseCloudMusic'] = PLUGIN_EXITSIGNAL_RETURN;

            return true;
        }*/
    }

    return true;
}

/**
 * 前台文章页面处理
 *
 * - 解析音乐网址
 * - 实现全站播放器
 *
 * @param object $template 模板实例
 */
function ViewPost_NeteaseCloudMusic(&$template)
{
    global $zbp, $action;
    $config = json_decode($zbp->Config('NeteaseCloudMusic')->player);

    //解析音乐网址
    if ($config->article->analyzeUrl && 'search' != $action) {
        include_once __DIR__ . '/php/simple_html_dom.php'; // 引入simplehtmldom
        $article  = $template->GetTags('article'); // 获取文章内容对象
        $html     = str_get_html($article->Content);
        $hasmusic = false;
        foreach ($html->find('a') as $element) {
            if (false !== stripos($element->href, '//music.163.com/#/')) {
                $id = substr(stristr($element->href, '='), 1);
                if (false !== stripos($element->href, 'playlist')) {
                    $resid = 'p' . $id;
                    $type  = 0;
                } elseif (false !== stripos($element->href, 'album')) {
                    $resid = 'a' . $id;
                    $type  = 1;
                } else {
                    $resid = 's' . $id;
                    $type  = 2;
                }
                if ($config->article->playerType) {
                    // APlayer
                    $element->tag       = 'div';
                    $element->href      = null;
                    $element->innertext = '<iframe border="0" width="' . $config->article->APlayer->width . '" height="76" src="' . $zbp->host . 'zb_users/plugin/NeteaseCloudMusic/aplayer.php?m=' . $config->article->APlayer->mini . '&a' . $config->article->APlayer->autoplay . '&t=' . str_replace('#', '%23', $config->article->APlayer->theme) . '&l=' . $config->article->APlayer->loop . '&o=' . $config->article->APlayer->order . '&p=' . $config->article->APlayer->preload . '&v=' . $config->article->APlayer->volume . '&lf=' . $config->article->APlayer->listFolded . '&lm=' . $config->article->APlayer->listMaxHeight . '&i=' . $resid . '" style="border:none;height:76px;width:' . $config->article->APlayer->width . 'px"></iframe>';
                } else {
                    // 官方播放器
                    $element->tag       = 'div';
                    $element->href      = null;
                    $element->innertext = '<iframe border="0" width="' . $config->article->NCMPlayer->width . '" height="' . $config->article->NCMPlayer->height . '" src="//music.163.com/outchain/player?type=' . $type . '&id=' . $id . '&auto=' . $config->article->NCMPlayer->auto . '&height=' . $config->article->NCMPlayer->height . '" style="border:none;width:' . $config->article->NCMPlayer->width . 'px;height:' . ((int) $config->article->NCMPlayer->height + 20) . 'px;"></iframe>';
                }
                $hasmusic = true;
            }
        }
        if ($hasmusic) {
            $article->Content = $html;
        }
    }

    //全站播放器
    if ($config->global->status) {
        if (0 == $config->global->gapless) {
            GlobalPlayer_NeteaseCloudMusic();
        } elseif (1 == $config->global->gapless) {
            GlobalGaplessPlayer_NeteaseCloudMusic();
            $GLOBALS['hooks']['Filter_Plugin_ViewPost_Template']['ViewPost_NeteaseCloudMusic'] = PLUGIN_EXITSIGNAL_RETURN;
        }
    }

    return true;
}

/**
 * 更新配置
 */
function UpdateConfig_NeteaseCloudMusic()
{
    global $zbp;

    // 获取旧配置
    $player            = json_decode($zbp->Config('NeteaseCloudMusic')->player, true);
    $plugin            = json_decode($zbp->Config('NeteaseCloudMusic')->plugin, true);
    $plugin['version'] = 2.13;
    $plugin['notify']  = 1;

    // 2.10 新增
    if (!array_key_exists('gapless', $player['global'])) {
        $player['global']['gapless'] = 0;
    }

    $zbp->Config('NeteaseCloudMusic')->player = json_encode($player);
    $zbp->Config('NeteaseCloudMusic')->plugin = json_encode($plugin);
    $zbp->SaveConfig('NeteaseCloudMusic');
}

/**
 * 重置设置
 *
 * @param boolean $del 是否删除已有配置
 */
function ResetConfig_NeteaseCloudMusic($del = false)
{
    global $zbp;

    if ($del) {
        $zbp->DelConfig('NeteaseCloudMusic');
    }

    // 插件配置
    $zbp->Config('NeteaseCloudMusic')->plugin = json_encode(array(
        'version'        => 2.13, //版本号
        'notify'         => 1, //更新提示
        'keepconfig'     => 1 //卸载时保留配置
    ));

    // 播放器配置
    $zbp->Config('NeteaseCloudMusic')->player = json_encode(array(
        'searchType' => 1, //默认搜索类型，1:单曲；1000:歌单；10:专辑
        'article'    => array( // 文章播放器
            'widgetSize' => 0, // 控件初始状态, 0：最小化；1：正常
            'editorType' => 'html', // 文章编辑器类型，html或者markdown
            'analyzeUrl' => 0, //解析 url 网址
            'ubb'        => 0, // 解析文章中的播放器 ubb 代码
            'playerType' => 0, // 播放器类型，0:官方播放器；1:APlayer
            'NCMPlayer'  => array( //网易官方播放器配置
                'type'    => 2, //播放资源类型
                'auto'    => 0, //自动播放
                'height'  => 66, //播放器高度
                'width'   => 700 //播放器宽度
            ),
            'APlayer' => array( //APlayer配置，参考：https://aplayer.js.org/#/home
                'type'          => 2, //播放资源类型
                'mini'          => 0, // 最小化，0|1
                'autoplay'      => 0, //自动播放，0|1
                'theme'         => '#DF2D2D', //主题色
                'loop'          => 0, //循环模式，2:'all'全部循环, 1:'one'单曲循环, 0:'none'无
                'order'         => 0, // 播放顺序，0:'list'顺序播放, 1:'random'随机播放
                'preload'       => 0, //预加载，0:'none'无, 1:'metadata'仅预加载元信息, 2:'auto'预加载
                'volume'        => 0.7, // 默认音量
                'mutex'         => 1, // 禁止多个播放器实例同时播放，0|1
                'lrcType'       => 0, // 歌词类型，0:不使用歌词，1:文本型，2:HTML型，3:lrc文件型
                'listFolded'    => 0, // 是否默认收起音乐列表，0|1
                'listMaxHeight' => 640, // 列表最大高度
                'width'         => 700 //播放器宽度
            )
        ),
        'global' => array( // 全站播放器
            'status'  => 0, //播放器开关
            'gapless' => 0, //无缝播放，0:关闭；1:iframe
            'APlayer' => array( //APlayer配置
                'type'          => 2, //播放资源类型
                'autoplay'      => 0, //自动播放，0|1
                'theme'         => '#DF2D2D', //主题色
                'loop'          => 0, //循环模式，2:'all'全部循环, 1:'one'单曲循环, 0:'none'无
                'order'         => 0, // 播放顺序，0:'list'顺序播放, 1:'random'随机播放
                'preload'       => 0, //预加载，0:'none'无, 1:'metadata'仅预加载元信息, 2:'auto'预加载
                'volume'        => 0.7, // 默认音量
                'lrcType'       => 0, // 歌词类型，0:不使用歌词，1:文本型，2:HTML型，3:lrc文件型
                'audioSto'      => ''  // 储存的音乐资源
            )
        )
    ));

    $zbp->SaveConfig('NeteaseCloudMusic');
}

/**
 * 插件安装激活时执行函数
 */
function InstallPlugin_NeteaseCloudMusic()
{
    global $zbp;

    // 若不存在配置则初始化配置
    if (!$zbp->HasConfig('NeteaseCloudMusic')) {
        ResetConfig_NeteaseCloudMusic(false);
    }
}

/**
 * 插件卸载时执行函数
 */
function UninstallPlugin_NeteaseCloudMusic()
{
    global $zbp;

    // 删除配置
    if (!json_decode($zbp->Config('NeteaseCloudMusic')->plugin)->keepconfig) {
        $zbp->DelConfig('NeteaseCloudMusic');
    }
}
