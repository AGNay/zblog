<?php
/**
* 网易云音乐 插件配置页
* NeteaseCloudMusic for Z-BlogPHP
*
* @author  心扬 <chrishyze@163.com>
*/

//系统初始化
require_once __DIR__ . '/../../../zb_system/function/c_system_base.php';
//后台初始化
require_once __DIR__ . '/../../../zb_system/function/c_system_admin.php';

$zbp->Load(); //加载系统

//检测权限
if (!$zbp->CheckRights('root')) {
    $zbp->ShowError(6);
    die();
}
//检测主题/插件启用状态
if (!$zbp->CheckPlugin('NeteaseCloudMusic')) {
    $zbp->ShowError(48);
    die();
}

//后台<head>
require_once __DIR__ . '/../../../zb_system/admin/admin_header.php';
//后台顶部
require_once __DIR__ . '/../../../zb_system/admin/admin_top.php';

// 读取数据库配置项
$article_conf = json_decode($zbp->Config('NeteaseCloudMusic')->article, true);
$global_conf  = json_decode($zbp->Config('NeteaseCloudMusic')->global, true);
$user_conf    = json_decode($zbp->Config('NeteaseCloudMusic')->user, true);
?>

<style>
@import url('<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/static/thirdparty/layui/css/layui.css');
</style>

<div id="divMain">
    <div class="layui-tab" lay-filter="main-tabs">
        <ul class="layui-tab-title" id="main-tabs">
            <div class="m-logo"></div>
            <li lay-id="setting" class="layui-this">
                设置
                <i class="cor arrow"></i>
            </li>
            <li lay-id="help">
                帮助
                <i class="cor"></i>
            </li>
            <li lay-id="about">
                关于
                <i class="cor"></i>
            </li>
        </ul>

        <div class="layui-tab-content">
            <div class="layui-tab-item with-subbar layui-show">
                <div class="layui-tab" lay-filter="setting-tabs">
                    <ul class="layui-tab-title sub-tabs">
                        <li lay-id="set-player" class="layui-this">文章播放器</li>
                        <li lay-id="set-g-player">全站播放器</li>
                        <li lay-id="set-others">其他</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <form action="" class="layui-form" lay-filter="article">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">解析 URL</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox" name="analyzeUrl" lay-skin="switch">
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">控件样式</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="widgetSize" value="1" title="正常">
                                        <input type="radio" name="widgetSize" value="0" title="最小化" checked>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">播放器类型</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="articlePlayerType" value="0" title="网易官方播放器" lay-filter="articlePlayerType" checked>
                                        <input type="radio" name="articlePlayerType" value="1" title="APlayer" lay-filter="articlePlayerType">
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn ncm-submit" lay-submit lay-filter="articleForm">保存配置</button>
                                    </div>
                                </div>

                                <blockquote class="layui-elem-quote layui-quote-nm">
                                    <b>解析 URL：</b>可将正文中网易云音乐单曲、专辑、歌单页面的链接解析转换为播放器。具体细节详见帮助。<br>
                                    <b>控件样式：</b>控制文章编辑页面右侧的小控件展开或收起。<br>
                                    <b>播放器类型：</b>文章编辑页面音乐添加器的默认播放器，以及解析 URL 时转换的播放器类型。<br>
                                </blockquote>
                            </form>
                        </div>

                        <div class="layui-tab-item">
                            <form action="" class="layui-form" lay-filter="global">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">是否启用</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox" name="status" lay-skin="switch" checked>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">无缝播放</label>
                                    <div class="layui-input-inline">
                                        <select name="gapless" lay-filter="gapless">
                                            <option value="">请选择</option>
                                            <option value="0" selected>关闭</option>
                                            <option value="1">启用 (iframe)</option>
                                        </select>
                                    </div>
                                </div>

                                <fieldset class="layui-elem-field control-fieldset">
                                    <legend>播放的音乐</legend>
                                    <div class="layui-field-box">
                                        <div class="layui-tab layui-tab-brief" lay-filter="gPlayerControlTabs">
                                            <ul class="layui-tab-title">
                                                <li class="layui-this" lay-id="gPlayerConfig">参数</li>
                                                <li lay-id="gPlayerSearch">搜索</li>
                                            </ul>
                                            <div class="layui-tab-content">
                                                <div class="layui-tab-item layui-show">
                                                    <div class="layui-form-item">
                                                        <label class="layui-form-label">音乐ID</label>
                                                        <div class="layui-input-inline" style="width: 85px; margin-right: -1px">
                                                            <select name="resourceType" lay-filter="resourceType">
                                                                <option value="">请选择</option>
                                                                <option value="2" selected>单曲</option>
                                                                <option value="0">歌单</option>
                                                                <option value="1">专辑</option>
                                                            </select>
                                                        </div>
                                                        <div class="layui-input-inline" style="width: 300px">
                                                            <input type="text" name="resid" placeholder="请输入音乐资源ID..." class="layui-input" value="" style="width: 370px">
                                                        </div>
                                                        <div class="layui-input-inline" style="width: 250px">
                                                            <button class="layui-btn ncm-submit" lay-submit lay-filter="analyzeMusic">解析</button>
                                                            <button class="layui-btn ncm-submit display-none" id="addMusic" lay-submit lay-filter="addMusic">追加</button>
                                                            <button class="layui-btn ncm-submit display-none" id="ncmRegenerate">重新生成</button>
                                                            <div class="display-none" id="ncmGenLoading">
                                                                <i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i> 解析中...
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="layui-form-item">
                                                        <label class="layui-form-label">自动播放</label>
                                                        <div class="layui-input-inline checkbox-inline">
                                                            <input type="checkbox" name="aPlayerAuto" lay-skin="switch" lay-filter="aPlayerAuto">
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
                                                </div>
                                                <div class="layui-tab-item">
                                                    <label class="layui-form-label">关键词</label>
                                                    <div class="layui-input-inline" style="width: 305px">
                                                        <input type="text" name="keywords" placeholder="多个关键词请用空格隔开" class="layui-input" style="width: 370px">
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
                                                    <div id="ncmSearchResult"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn ncm-submit" lay-submit lay-filter="globalForm">保存配置</button>
                                    </div>
                                </div>

                                <blockquote class="layui-elem-quote layui-quote-nm">
                                    <b>提示：</b>全站播放器是自动向前台页面的左下方添加的一个播放器，可以与文章播放器共存。<br>
                                    <b>无缝播放：</b>支持前台页面之间无缝播放，详情查看帮助。<br>
                                </blockquote>
                            </form>
                        </div>

                        <div class="layui-tab-item">
                            <form action="" class="layui-form" lay-filter="others">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">默认搜索类型</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="searchType" value="1" title="单曲" checked>
                                        <input type="radio" name="searchType" value="1000" title="歌单">
                                        <input type="radio" name="searchType" value="10" title="专辑">
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">卸载时保留配置</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox" name="keepconfig" lay-skin="switch">
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn ncm-submit" lay-submit lay-filter="othersForm">保存配置</button>
                                        <button class="layui-btn layui-btn-sm" style="background-color: #FF5722" lay-submit lay-filter="reset">重置配置</button>
                                        <button class="layui-btn layui-btn-sm" style="background-color: #28CC75" lay-submit lay-filter="clearcache">清空缓存</button>
                                        <button class="layui-btn layui-btn-sm" style="background-color: #1874F1" lay-submit lay-filter="migrate">从 v1.x 版本迁移数据</button>
                                    </div>
                                </div>

                                <blockquote class="layui-elem-quote layui-quote-nm">
                                    <b>默认搜索类型：</b>设置文章编辑页面及本页面搜索功能的默认搜索类型。<br>
                                    <b>卸载时保留配置：</b>停用或删除插件时是否保留插件的配置信息。<br>
                                    <b>清空缓存：</b>本插件使用了缓存机制，详见帮助，此按钮可以清空所有缓存。<br>
                                    <b>迁移数据：</b>适合从 1.x 版本升级上来的用户，由于 2.x 版本将自带播放器更换为 APlayer ，之前使用自带播放器添加的歌曲将失效，可以通过此功能将文章中的播放器迁移转换至 APlayer，迁移后播放器可正常使用。本功能只需运行一次即可，以往添加的歌曲越多，运行时间就越长，请耐心等待。<br>
                                </blockquote>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-tab-item">
                <fieldset class="layui-elem-field">
                    <legend>什么是音乐资源 ID ？</legend>
                    <div class="layui-field-box">
                        <p>进入网易云音乐中某个单曲、歌单、专辑页面中，其对应的网址形如：</p>
                        <p>https://music.163.com/#/song?id=857896</p>
                        <p>“?id=”后面的数字就是该音乐资源的 ID。</p>
                    </div>
                </fieldset>
                <fieldset class="layui-elem-field">
                    <legend>搜索功能的使用</legend>
                    <div class="layui-field-box">
                        <p>输入关键词搜索出结果后，每条可用的结果前会有一个➕；</p>
                        <p>点击➕，会自动将该结果的资源 id 填到解析输入框中，资源类型也会自动切换；</p>
                        <p>此时只需点击“解析”或者“追加”按钮即可解析该资源。</p>
                    </div>
                </fieldset>
                <fieldset class="layui-elem-field">
                    <legend>解析音乐网址</legend>
                    <div class="layui-field-box">
                        <p>本功能可将正文中网易云音乐单曲、专辑、歌单页面的链接解析转换为播放器。</p>
                        <p>进入网易云音乐官网的单曲页面，复制该页面的网址，例如：</p>
                        <p>https://music.163.com/#/song?id=857896</p>
                        <p>将该网址作为一个链接放到文章正文当中，即形如以下的链接：</p>
                        <p>&lt;a href="https://music.163.com/#/song?id=857896"&gt;这是一首歌...可以是任意文字&lt;/a&gt;</p>
                        <p>对于一般的编辑器，在粘贴网址后回车，编辑器会自动将网址解析为链接。</p>
                        <p>在设置开启解析音乐网址功能，即可将每篇文章中的网易云音乐网址为播放器。</p>
                        <p>播放器的类型可在后台设置，播放器的具体配置（如是否自动播放、循环等）由最近一次添加音乐时的播放器配置决定。</p>
                        <p>处于性能和可用性的考虑，建议只用于单曲，对于专辑、歌单等资源建议手动添加。</p>
                    </div>
                </fieldset>
                <fieldset class="layui-elem-field">
                    <legend>官方播放器与 APlayer 的区别</legend>
                    <div class="layui-field-box">
                        <p>官方播放器是网易云音乐官方的外链播放器，由官方支持，功能稳定，可以长期使用。在停用甚至卸载本插件后，已添加的官方音乐播放器仍旧可以使用。</p>
                        <p>APlayer 是本插件内置的第三方播放器，可定制化程度高，在外观和使用上的体验较好，但其可用性依赖于底层接口。在停用本插件后，已添加的 APlayer 播放器仍旧可以使用；卸载本插件后，已添加 APlayer 播放器将无法使用。</p>
                    </div>
                </fieldset>
                <fieldset class="layui-elem-field">
                    <legend>音乐解析与缓存机制</legend>
                    <div class="layui-field-box">
                        <p>使用 APlayer 添加音乐时，插件会通过内部接口解析资源，由于解析是一首接一首地进行的，因此像歌单、专辑这种包含多首歌曲的资源，歌曲越多，解析时间越长。</p>
                        <p>插件采用了基于文件的缓存机制，每次解析资源都会缓存至本地，缓存机制能显著提高响应，大幅减轻服务器压力，缓存的默认有效时间为25分钟。</p>
                    </div>
                </fieldset>
                <fieldset class="layui-elem-field">
                    <legend>无缝播放</legend>
                    <div class="layui-field-box">
                        <p>无缝播放能使前台全站播放器在切换页面时，保持播放不间断，仅在前台页面生效。</p>
                        <p>iframe 形式的无缝播放对浏览器的兼容性最佳，技术上是通过给当前页面套一个播放器，并在 iframe 中引用当前页面来实现，在一定程度上会增加服务器负担，并有可能影响 SEO 效果。</p>
                    </div>
                </fieldset>
            </div>
            <div class="layui-tab-item">
                <fieldset class="layui-elem-field">
                    <legend>声明</legend>
                    <div class="layui-field-box">
                        <p>● 本插件并非网易云音乐官方插件。</p>
                        <p>● 本插件的音乐、MV及歌词等全部源自网易公司旗下网易云音乐(https://music.163.com/)，本插件不提供任何媒体资源内容。</p>
                        <p>● 因本插件的特殊性，开发者不对插件的可用性做任何保证。</p>
                    </div>
                </fieldset>
                <fieldset class="layui-elem-field">
                    <legend>关于</legend>
                    <div class="layui-field-box">
                        <div class="center">
                            <p><img src="<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/logo.png" alt="logo"></p>
                            <br>
                            <p>NeteaseCloudMusic v<?php $app=new App;$app->LoadInfoByXml('plugin', 'NeteaseCloudMusic');echo $app->version; ?></p>
                            <br>
                            <br>
                            <p>插件作者：心扬</p>
                            <p>联系方式：chrishyze@163.com</p>
                            <p>欢迎通过邮件反馈 BUG 或建议</p>
                            <p>项目地址：<a href="https://gitee.com/chrishyze/zbp_neteasecloudmusic" target="_blank">ZBP-NeteaseCloudMusic</a></p>
                            <hr class="layui-bg-gray">
                        </div>
                        <p><strong>开源项目</strong></p>
                        <p><a href="https://github.com/kilingzhang/NeteaseCloudMusicApi" target="_blank">kilingzhang/NeteaseCloudMusicApi</a> (The MIT License)</p>
                        <p><a href="https://gitee.com/chrishyze/NeteaseCloudMusicSDK" target="_blank">chrishyze/NeteaseCloudMusicSDK</a> (The MIT License)</p>
                        <p><a href="https://github.com/MoePlayer/APlayer" target="_blank">MoePlayer/APlayer</a> (The MIT License)</p>
                        <p><a href="https://github.com/sentsin/layui" target="_blank">sentsin/layui</a> (The MIT License)</p>
                        <p><a href="https://github.com/necolas/normalize.css" target="_blank">necolas/normalize.css</a> (The MIT License)</p>
                    </div>
                </fieldset>
            </div>
        </div>
    </div><!--/ div.layui-tab -->
</div><!--/ div#main -->

<div id="aPlayerPreview"></div>

<style>
@import url(<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/static/thirdparty/aplayer/APlayer.min.css);
@import url(<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/static/style/main.min.css);
div.m-logo {
    background-image: url(<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/static/images/topbar.png);
}
i.arrow {
    background-image: url(<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/static/images/topbar.png) ;
}
</style>

<script>
// 网易云音乐插件后台全局变量
const NCMUSIC_ADMIN = {
    HOME_URL: "<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic",
    CSRF_TOKEN: "<?php echo $zbp->GetCSRFToken('NeteaseCloudMusic'); ?>",
    PLAYER_CONFIG: <?php echo $zbp->Config('NeteaseCloudMusic')->player; ?>,
    PLUGIN_CONFIG: <?php echo $zbp->Config('NeteaseCloudMusic')->plugin; ?>,
    livePlayer: null,
    Functions: {}
};
</script>
<script src="<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/static/thirdparty/aplayer/APlayer.min.js"></script>
<script src="<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/static/thirdparty/layui/layui.js" charset="utf-8"></script>
<script src="<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/static/script/admin-public.min.js" charset="utf-8"></script>
<script src="<?php echo $zbp->host; ?>zb_users/plugin/NeteaseCloudMusic/static/script/admin-setting.min.js" charset="utf-8"></script>
<?php
//后台底部
require_once __DIR__ . '/../../../zb_system/admin/admin_footer.php';
RunTime(); //显示运行时间
?>
