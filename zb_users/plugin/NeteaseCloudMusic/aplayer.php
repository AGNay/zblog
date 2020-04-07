<?php
/**
 * 网易云音乐插件单页 APlayer 播放器
 * 用于支持内联框架 iframe 的调用
 */
require_once __DIR__ . '/../../../zb_system/function/c_system_base.php';
require_once __DIR__ . '/php/NeteaseCloudMusicSDK/autoload.php';
use NeteaseCloudMusicSDK\Song;
use NeteaseCloudMusicSDK\Music;
include_once __DIR__ . '/php/cache.class.php';

// 检查 ZBP 版本
if ((int) $GLOBALS['blogversion'] >= 162090) {
    ZBlogException::$disabled = true;
} else {
    ZBlogException::$isdisable = true;
}

$zbp->Load();
$plugin_url = $zbp->host . 'zb_users/plugin/NeteaseCloudMusic';
$song       = new Song;
$music      = new Music;

// 默认参数
$cover  = $plugin_url . '/static/images/nocover.jpg';
$name   = 'No Name';
$artist = 'No Artist';
$url    = '';

// 从 url 获取播放器参数
(int)$auto          = isset($_GET['a']) ? $_GET['a'] : 0; // 自动播放，0|1
(int)$preload       = isset($_GET['p']) ? $_GET['p'] : 0; // 预加载，0:'none'，1:'metadata'，2:'auto'
(int)$theme         = isset($_GET['t']) ? $_GET['t'] : '#DF2D2D'; // 主题色，Hex
(int)$loop          = isset($_GET['l']) ? $_GET['l'] : 0; // 循环模式，0:'none'，1:'one'，2:'all'
(int)$order         = isset($_GET['o']) ? $_GET['o'] : 0; // 播放顺序，0:'list'，1:'random'
(int)$mini          = isset($_GET['m']) ? $_GET['m'] : 0; // 最小化，0|1
(int)$listFolded    = isset($_GET['lf']) ? $_GET['lf'] : 0; // 是否默认收起音乐列表，0|1
(int)$listMaxHeight = isset($_GET['lm']) ? $_GET['lm'] : 500; // 列表最大高度
(float)$volume      = isset($_GET['v']) ? $_GET['v'] : 0.7; // 默认音量
$music_ids          = isset($_GET['i']) ? $_GET['i'] : ''; // 资源 id 列表

// 处理播放器参数
$auto       = $auto ? 'true' : 'false';
$mini       = $mini ? 'true' : 'false';
$listFolded = $listFolded ? 'true' : 'false';

if (1 == $preload) {
    $preload = 'metadata';
} elseif (2 == $preload) {
    $preload =  'auto';
} else {
    $preload =  'none';
}

if (1 == $loop) {
    $loop = 'one';
} elseif (2 == $loop) {
    $loop =  'all';
} else {
    $loop =  'none';
}

if (1 == $order) {
    $order = 'random';
} else {
    $order =  'list';
}

if (!ctype_xdigit(substr($theme, 1))) {
    $theme = '#DF2D2D';
}

if (strlen($music_ids) > 0) {
    $music_ids = explode('_', $music_ids);
} else {
    die(0);
}

// 缓存
$cache = new Cache([
    'name'      => 'default',
    'path'      => __DIR__ . '/cache/',
    'extension' => '.cache'
]);

//专辑歌单转单曲
$i = 0;  // 当前元素在数组中的动态位置
foreach ($music_ids as $mid) {
    if (false !== stripos($mid, 'a')) {
        // 专辑
        $song_ids = [];
        $aid = substr($mid, 1);
        $cache->setCache('album_' . strlen($aid));  // 类型_ID长度
        $cache->eraseExpired();
        if ($cache->isCached($aid)) {
            $song_ids = $cache->retrieve($aid);
        } else {
            $album = new NeteaseCloudMusicSDK\Album;
            $info  = $album->album($aid);
            if (200 == $info['code'] && !empty($info['songs'])) {
                foreach ($info['songs'] as $s) {
                    array_push($song_ids, $s['id']);
                }
                $cache->store($aid, $song_ids, 1500);  // 最长25分钟过期
            }
        }
        array_splice($music_ids, $i, 1, $song_ids);
        $i = $i + count($song_ids) - 1;
    } elseif (false !== stripos($mid, 'p')) {
        // 歌单
        $song_ids = [];
        $pid = substr($mid, 1);
        $cache->setCache('playlist_' . strlen($pid));
        $cache->eraseExpired();
        if ($cache->isCached($pid)) {
            $song_ids = $cache->retrieve($pid);
        } else {
            $playlist = new NeteaseCloudMusicSDK\PlayList;
            $info     = $playlist->detail(substr($mid, 1));
            if (200 == $info['code'] && !empty($info['privileges'])) {
                foreach ($info['privileges'] as $s) {
                    array_push($song_ids, $s['id']);
                }
                $cache->store($pid, $song_ids, 1500);
            }
        }
        array_splice($music_ids, $i, 1, $song_ids);
        $i = $i + count($song_ids) - 1;
    } elseif (false !== stripos($mid, 's')) {
        array_splice($music_ids, $i, 1, [substr($mid, 1)]);
    }
    $i++;
}

//获取歌曲详情
$music_list = array();
foreach ($music_ids as $mid) {
    $cache->setCache('song_' . strlen($mid));
    $cache->eraseExpired();
    if ($cache->isCached($mid)) {
        array_push($music_list, $cache->retrieve($mid));
    } else {
        $info = $song->detail($mid);
        if (200 == $info['code'] && !empty($info['songs'])) {
            $music_info = array(
                'name'   => $info['songs'][0]['name'],
                'artist' => $info['songs'][0]['ar'][0]['name'],
                'cover'  => $info['songs'][0]['al']['picUrl']
            );
            $url = $music->musicUrl($mid);
            if (200 == $url['code'] && !empty($url['data'])) {
                $music_info['url'] = $url['data'][0]['url'];
            }
            array_push($music_list, $music_info);
            $cache->store($mid, $music_info, 1500);
        }
    }
}
$music_list = json_encode($music_list);

// 输出页面
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Cache-control" content="no-cache">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $name; ?> - NeteaseCloudMusic for Z-BlogPHP</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo $plugin_url; ?>/static/thirdparty/normalize/normalize.min.css">
        <link rel="stylesheet" href="<?php echo $plugin_url; ?>/static/thirdparty/aplayer/APlayer.min.css">
        <style>
            html,
            body {
                overflow: hidden;
            }
        </style>
    </head>
    <body>
        <div id="aplayer"></div>

        <script src="<?php echo $plugin_url; ?>/static/thirdparty/aplayer/APlayer.min.js"></script>
        <script>
        const ap = new APlayer({
            container: document.getElementById("aplayer"),
            mini: <?php echo $mini; ?>,
            autoplay: <?php echo $auto; ?>,
            theme: "<?php echo $theme; ?>",
            loop: "<?php echo $loop; ?>",
            order: "<?php echo $order; ?>",
            preload: "<?php echo $preload; ?>",
            volume: <?php echo $volume; ?>,
            mutex: 1,
            lrcType: 0,
            listFolded: <?php echo $listFolded; ?>,
            listMaxHeight: <?php echo $listMaxHeight; ?>,
            audio: <?php echo $music_list; ?>
        });
        ap.on("listshow", () => {
            if (ap.list.audios.length > 1) {
                window.frameElement.style.height = 76 + ap.list.audios.length * 32 + (ap.list.audios.length - 1) + "px";
            }
        });
        ap.on("listhide", () => {
            if (ap.list.audios.length > 1) {
                window.frameElement.style.height = "76px";
            }
        });
        </script>
    </body>
</html>
