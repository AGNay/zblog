<?php
/**
* 批量优化视频
*
* @author 心扬 <chrishyze@163.com>
*/

//引入预处理与公共函数
require_once __DIR__.'/common.php';

//检测管理员权限
if (!$zbp->CheckRights('root')) {
    reject('没有访问权限!');
}

include_once __DIR__.'/simple_html_dom.php';

$csp    = json_decode($zbp->Config('IframeVO')->csp);
$format = '0' === GetVars('format', 'POST') ? false : true;
$ratio  = GetVars('ratio', 'POST');
if ('56.25'  !==  $ratio && '62.5'  !==  $ratio && '75'  !==  $ratio) {
    $ratio = '75';
}

// 遍历所有文章页面
$postCount  = 0; // 文章总数
$videoCount = 0; // 嵌入视频总数
$voCount    = 0; // 本次优化的视频数
$postArr    = [
    $zbp->GetArticleList('', '1=1', null, null, null, false),
    $zbp->GetPageList('', '1=1'),
];
foreach ($postArr as $posts) {
    foreach ($posts as $key => $value) {
        $hasVideo = false;
        $html      = str_get_html($value->Content);
        if (!$html) {
            continue;
        }
        foreach ($html->find('iframe') as $element) {
            $isInCsp = false;
            // 判断是否在 csp 规则中
            foreach ($csp as $domain) {
                if (false !== stripos($element->src, $domain)) {
                    $isInCsp = true;
                    break;
                }
            }
            if ($isInCsp) {
                // 判断是否已经优化
                if (false === stripos(str_ireplace(' ', '', $element->parent()->style), 'position:relative;width:100%;height:0')) {
                    // 哔哩哔哩添加外阴影
                    $style = 'position:absolute;width:100%;height:100%;left:0;top:0;border:none;';
                    if (false !== stristr($element->src, 'player.bilibili.com')) {
                        $style .= 'box-shadow:0 0 8px #e5e9ef;';
                    }

                    // HTML5 格式化
                    if ($format) {
                        // 地址优化
                        if (0 === stristr($element->src, '//')) {
                            $element->src = str_ireplace('//', 'https://', $element->src);
                        } elseif (0 === stristr($element->src, 'http:')) {
                            $element->src = str_ireplace('http:', 'https:', $element->src);
                        }

                        // 允许的属性
                        $allow = '';
                        $allowfullscreen = '';
                        $scrolling = '';
                        if (null !== $element->allow) {
                            if (null !== $element->allowfullscreen && 0 === stristr(strtolower($element->allow), "fullscreen")) {
                                $allow = ' allow="'.$element->allow.' fullscreen"';
                            }
                        } else {
                            if (null !== $element->allowfullscreen) {
                                $allow = ' allow="fullscreen"';
                            }
                        }
                        if (null !== $element->allowfullscreen) {
                            $allowfullscreen = ' allowfullscreen';
                        }
                        // 搜狐视频屏蔽滚动条
                        if (false !== stristr($element->src, 'tv.sohu.com')) {
                            $scrolling = ' scrolling="no"';
                        }

                        // 直接用新元素替换旧元素
                        $element->outertext = '<div style="position:relative;width:100%;height:0;padding-bottom:'
                            .$ratio.
                            '%;"><iframe src="'
                            .$element->src.'"'
                            .$allow
                            .$allowfullscreen
                            .$scrolling.
                            ' style="'
                            .$style.
                            '"></iframe></div>';
                    } else {
                        // 框架样式
                        $element->style = $style;

                        // 添加外部容器
                        $element->outertext = '<div style="position:relative;width:100%;height:0;padding-bottom:'
                        .$ratio.
                        '%;">'.$element->outertext.'</div>';
                    }
                    $voCount++;
                }
                $videoCount++;
                $hasVideo = true;
            }
        }
        if ($hasVideo) {
            $post          = GetPost((int) $value->ID);
            $post->Content = $html;
            $post->Save();
            $postCount++;
        }
    }
}
echo jsonResponse([true, '操作完毕<br>总计 '.$postCount.' 篇文章中，包含 '
    .$videoCount.' 个嵌入视频。<br>其中已优化的视频有 '
    .($videoCount - $voCount).'个，<br><strong>本次优化视频 '
.$voCount.'个。</strong>', ]);

die();
