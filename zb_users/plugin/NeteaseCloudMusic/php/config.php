<?php
/**
* 处理后台配置请求
*
* @author 心扬 <chrishyze@163.com>
*/

//引入预处理与公共函数
require_once __DIR__ . '/common.php';

//检测管理员权限
if (!$zbp->CheckRights('root')) {
    reject('没有访问权限!');
}

//判断请求类型
if ('POST' == strtoupper($_SERVER['REQUEST_METHOD'])) {
    // 获取配置类型
    $type    = (string) $_POST['type'];
    $config  = json_decode($zbp->Config('NeteaseCloudMusic')->$type, true);
    if ($config) {
        foreach ($_POST['config'] as $conf) {
            $keys = explode('.', $conf['key']);
            if (false !== stripos($conf['type'], 'int')) {
                $value = (int) $conf['value'];
            } elseif (false !== stripos($conf['type'], 'float')) {
                $value = (float) $conf['value'];
            } else {
                $value = (string) $conf['value'];
            }
            if (3 == count($keys)) {
                $config[$keys[0]][$keys[1]][$keys[2]] = $value;
            } elseif (2 == count($keys)) {
                $config[$keys[0]][$keys[1]] = $value;
            } elseif (1 == count($keys)) {
                $config[$keys[0]] = $value;
            } else {
                reject('配置键名错误!');
            }
        }

        $zbp->Config('NeteaseCloudMusic')->$type = json_encode($config);
        $zbp->SaveConfig('NeteaseCloudMusic');

        echo jsonResponse(array(true, '配置保存成功!'));
    } else {
        reject('配置类型参数错误!');
    }
} elseif ('GET' == strtoupper($_SERVER['REQUEST_METHOD'])) {
    if ('offnotify' == $_GET['action']) {
        // 隐藏更新提示
        $config                                   = json_decode($zbp->Config('NeteaseCloudMusic')->plugin);
        $config->notify                           = 0;
        $zbp->Config('NeteaseCloudMusic')->plugin = json_encode($config);
        $zbp->SaveConfig('NeteaseCloudMusic');
    } elseif ('reset' == $_GET['action']) {
        //调用重置函数
        ResetConfig_NeteaseCloudMusic(true);
        echo jsonResponse(array(true, '重置配置成功!'));
    } elseif ('clearcache' == $_GET['action']) {
        // 清除缓存
        $cache_dir = __DIR__ . '/../cache';
        if (is_dir($cache_dir)) {
            $objects = scandir($cache_dir);
            foreach ($objects as $object) {
                if ('.' != $object && '..' != $object) {
                    unlink($cache_dir . '/' . $object);
                }
            }
        }
        echo jsonResponse(array(true, '成功清除全部缓存!'));
    } elseif ('migrate' == $_GET['action']) {
        // 从 v1.* 版本迁移数据
        include_once __DIR__ . '/simple_html_dom.php';
        // 遍历所有文章页面
        $post_count  = 0;
        $music_count = 0;
        $post_arr    = [
            $zbp->GetArticleList('', '1=1', null, null, null, false),
            $zbp->GetPageList('', '1=1')
        ];
        foreach ($post_arr as $posts) {
            foreach ($posts as $key => $value) {
                $hasmusic = false;
                $html     = str_get_html($value->Content);
                foreach ($html->find('iframe') as $element) {
                    if (false !== stripos($element->src, 'Music/player.php')) {
                        if (isset($element->id)) {
                            $element->id = null;
                        }
                        if (isset($element->frameborder)) {
                            $element->frameborder = null;
                        }
                        if (isset($element->marginwidth)) {
                            $element->marginwidth = null;
                        }
                        if (isset($element->marginheight)) {
                            $element->marginheight = null;
                        }
                        $element->style  = 'border:none;height:76px;width:' . $element->width;
                        $element->width  = str_replace('px', '', $element->width);
                        $element->height = '76';
                        $query           = explode('&', parse_url($element->src, PHP_URL_QUERY));
                        $queries         = [];
                        foreach ($query as $q) {
                            $kv              = explode('=', $q);
                            $queries[$kv[0]] = $kv[1];
                        }
                        $element->src = $zbp->host . 'zb_users/plugin/NeteaseCloudMusic/aplayer.php?' .
                            '?a=' . $queries['auto'] .
                            '&l=' . $queries['loop'] .
                            '&p=' . $queries['pre'] .
                            '&i=s' . $queries['id'];
                        $music_count++;
                        $hasmusic = true;
                    }
                }
                if ($hasmusic) {
                    $post          = GetPost((int) $value->ID);
                    $post->Content = $html;
                    $post->Save();
                    $post_count++;
                }
            }
        }
        echo jsonResponse([true, '运行完毕，已成功迁移 ' . $post_count . ' 篇文章中的 ' . $music_count . ' 首音乐！']);
    } else {
        reject('参数错误!');
    }
} else {
    reject('非法访问!');
}

die();
