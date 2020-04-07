<?php
/**
* SDK API 接口
*
* @author 心扬 <chrishyze@163.com>
*/

//引入预处理与公共函数
require_once __DIR__ . '/common.php';
//autoloader
require_once __DIR__ . '/NeteaseCloudMusicSDK/autoload.php';
include_once __DIR__ . '/cache.class.php';

// 缓存
$cache = new Cache([
    'name'      => 'default',
    'path'      => __DIR__ . '/../cache/',
    'extension' => '.cache'
]);

$action  = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
$request = isset($_REQUEST['request']) ? $_REQUEST['request'] : null;
if ('song' == $action) {
    // 单曲
    if (isset($_REQUEST['id'])) {
        $sid = $_REQUEST['id'];
    } else {
        reject('缺少id参数');
    }
    if ('detail' == $request) {
        // 详情
        $cache->setCache('song_detail_' . strlen($sid));
        $cache->eraseExpired();
        if ($cache->isCached($sid)) {
            $info = $cache->retrieve($sid);
        } else {
            $song = new NeteaseCloudMusicSDK\Song;
            $info = $song->detail($sid);
            $cache->store($sid, $info, 1500);
        }
        echo jsonResponse($info);
    } elseif ('url' == $request) {
        // 音乐详情和 url
        $cache->setCache('song_url_' . strlen($sid));
        $cache->eraseExpired();
        if ($cache->isCached($sid)) {
            $info = $cache->retrieve($sid);
        } else {
            $song  = new NeteaseCloudMusicSDK\Song;
            $music = new NeteaseCloudMusicSDK\Music;
            $info  = $song->detail($sid);
            $url   = $music->musicUrl($sid);
            if (200 == $url['code'] && !empty($url['data'])) {
                $info['songs'][0]['url'] = $url['data'][0]['url'];
            } else {
                $info['songs'][0]['url'] = '';
            }
            $cache->store($sid, $info, 1500);
        }
        echo jsonResponse($info);
    }
} elseif ('playlist' == $action) {
    // 歌单
    if (isset($_REQUEST['id'])) {
        $pid = $_REQUEST['id'];
    } else {
        reject('缺少id参数');
    }
    if ('detail' == $request) {
        $cache->setCache('playlist_detail_' . strlen($pid));
        $cache->eraseExpired();
        if ($cache->isCached($pid)) {
            $info = $cache->retrieve($pid);
        } else {
            $playlist = new NeteaseCloudMusicSDK\PlayList;
            $info     = $playlist->detail($pid);
            $cache->store($pid, $info, 1500);
        }
        echo jsonResponse($info);
    } elseif ('url' == $request) {
        // 歌单详情和 url
        $cache->setCache('playlist_url_' . strlen($pid));
        $cache->eraseExpired();
        if ($cache->isCached($pid)) {
            $info = $cache->retrieve($pid);
        } else {
            $playlist = new NeteaseCloudMusicSDK\PlayList;
            $music    = new NeteaseCloudMusicSDK\Music;
            $info     = $playlist->detail($pid);
            if (200 == $info['code'] && !empty($info['privileges'])) {
                $i = 0;
                foreach ($info['privileges'] as $s) {
                    $url = $music->musicUrl($s['id']);
                    if (200 == $url['code'] && !empty($url['data'])) {
                        $info['playlist']['tracks'][$i]['url'] = $url['data'][0];
                    }
                    $i++;
                }
            }
            $cache->store($pid, $info, 1500);
        }
        echo jsonResponse($info);
    }
} elseif ('album' == $action) {
    // 专辑
    if (isset($_REQUEST['id'])) {
        $aid = $_REQUEST['id'];
    } else {
        reject('缺少id参数');
    }
    if ('detail' == $request) {
        $cache->setCache('album_detail_' . strlen($aid));
        $cache->eraseExpired();
        if ($cache->isCached($aid)) {
            $info = $cache->retrieve($aid);
        } else {
            $album = new NeteaseCloudMusicSDK\Album;
            $info  = $album->album($aid);
            $cache->store($aid, $info, 1500);
        }
        echo jsonResponse($info);
    } elseif ('url' == $request) {
        // 专辑详情和 url
        $cache->setCache('album_url_' . strlen($aid));
        $cache->eraseExpired();
        if ($cache->isCached($aid)) {
            $info = $cache->retrieve($aid);
        } else {
            $album = new NeteaseCloudMusicSDK\Album;
            $music = new NeteaseCloudMusicSDK\Music;
            $info  = $album->album($aid);
            if (200 == $info['code'] && !empty($info['songs'])) {
                $i = 0;
                foreach ($info['songs'] as $s) {
                    $url = $music->musicUrl($s['id']);
                    if (200 == $url['code'] && !empty($url['data'])) {
                        $info['songs'][$i]['url'] = $url['data'][0];
                    }
                    $i++;
                }
            }
            $cache->store($aid, $info, 1500);
        }
        echo jsonResponse($info);
    }
} elseif ('search' == $action) {
    // 搜索
    if ('result' == $request) {
        // 搜索结果
        $search = new NeteaseCloudMusicSDK\Search;
        $result = $search->search(
            isset($_REQUEST['keywords']) ? $_REQUEST['keywords'] : '',
            isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 30,
            isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0,
            isset($_REQUEST['type']) ? $_REQUEST['type'] : 1
        );
        echo jsonResponse($result);
    } elseif ('multimatch' == $request) {
        // 搜索多重匹配
        $search = new NeteaseCloudMusicSDK\Search;
        $result = $search->multimatch(isset($_REQUEST['keywords']) ? $_REQUEST['keywords'] : '');
        echo jsonResponse($result);
    }
} elseif ('artist' == $action) {
    // 歌手
    if ('detail' == $request) {
        // 歌手详情，附加热门歌曲
        $artists = new NeteaseCloudMusicSDK\Artists;
        $detail  = $artists->artists(
            isset($_REQUEST['id']) ? $_REQUEST['id'] : 0,
            isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0,
            isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 50
        );
        echo jsonResponse($detail);
    } elseif ('album' == $request) {
        // 歌手专辑
        $artists = new NeteaseCloudMusicSDK\Artists;
        $album   = $artists->album(
            isset($_REQUEST['id']) ? $_REQUEST['id'] : 0,
            true,
            isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0,
            isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 50
        );
        echo jsonResponse($album);
    }
}

die();
