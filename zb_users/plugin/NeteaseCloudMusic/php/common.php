<?php
/**
* 预处理和公共函数
*
* @author 心扬 <chrishyze@163.com>
*/

//系统初始化
require_once __DIR__ . '/../../../../zb_system/function/c_system_base.php';

$zbp->Load(); //加载系统
//声明HTTP资源类型为JSON
if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
}

//验证CSRF Token
if (GetVars('csrfToken')) {
    if (!$zbp->VerifyCSRFToken(GetVars('csrfToken'), 'NeteaseCloudMusic')) {
        reject('非法访问！');
    }
} else {
    reject('非法访问！');
}

//检测主题/插件启用状态
if (!$zbp->CheckPlugin('NeteaseCloudMusic')) {
    reject('插件未启用!');
}

/**
 * JSON消息响应
 * 转义输出，避免中文转码 Unicode
 *
 * @param array $arr
 *
 * @return string
 */
function jsonResponse($arr)
{
    $arr = resurHtmlspecialchars($arr);

    return json_encode(
        $arr,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}

/**
 * 递归转换 HTML 实体.
 *
 * @param array $arr 内容数组，其元素只能为数组或字符串
 *
 * @return array
 */
function resurHtmlspecialchars(Array &$arr)
{
    if (is_array($arr)) {
        foreach ($arr as &$value) {
            if (is_array($value)) {
                resurHtmlspecialchars($value);
            } else {
                $value = htmlspecialchars($value);
            }
        }
    }

    return $arr;
}

/**
 * 拒绝访问
 *
 * @param string $message
 */
function reject($message)
{
    echo jsonResponse(array(false, $message));
    die();
}
