<?php
#注册插件
RegisterPlugin("ThemePreview", "ActivePlugin_ThemePreview");

function ActivePlugin_ThemePreview()
{
  Add_Filter_Plugin('Filter_Plugin_ViewAuto_Begin', 'ThemePreview_Rewrite');
  Add_Filter_Plugin('Filter_Plugin_SmartQQ2_message', 'ThemePreview_QQ');
}
function ThemePreview_QQ($robot, $msg)
{
  global $zbp;
  $type = $msg["message_type"];
  if ($type !== "private" && $type !== "group") {
    return;
  }
  $line = $msg["message"];
  $acptPrfx = "主题预览";
  // $robot->WriteDB("log", $line, __LINE__);
  if (0 === strpos($line, $acptPrfx)) {
    $cont = trim(substr($line, strlen($acptPrfx)));
  } else {
    return;
  }
  $reply = "";
  if (!empty($cont)) {
    $app = new App();
    if ($app->LoadInfoByXml('theme', $cont) == true) {
      SetTheme($cont, $cont);
      $zbp->BuildTemplate();
      $reply .= "切换成功\n";
      $reply .= $zbp->host;
    }
  } else {
    // $robot->WriteDB("log", $line, __LINE__);
    $reply .= "主题名丨切换指令\n";
    $array = array();
    ThemePreview_GenTable($array);
    foreach ($array as $id => $name) {
      $reply .= "{$name}丨主题预览 {$id}\n";
    }
    $reply .= "发送竖线后的部分切换至指定主题";
  }
  // $robot->WriteDB("log", $reply, __LINE__);

  // 原路径回复的话封装进类里？？？
  $uin = array();
  $uin["user_id"] = $msg["user_id"];
  if (isset($msg["group_id"])) {
    $uin["group_id"] = $msg["group_id"];
  }
  // $reply = "收到的信息是：{$msg["message"]}";
  $robot->SendMsg($type, $uin, $reply);
}
function ThemePreview_Rewrite($inpurl, $url)
{
  global $zbp;
  $m = array();
  if (preg_match("/tPre\/(?<id>.+)\/(?<style>.+)/", $url, $m) == 1) {
    $GLOBALS['hooks']['Filter_Plugin_ViewAuto_Begin']['ThemePreview_Rewrite'] = PLUGIN_EXITSIGNAL_RETURN;
    $last = floor(time() / 600);
    if ($last === $zbp->Config('ThemePreview')->last) {
      RedirectByScript($zbp->host . "#10分钟内只能切换一次");
      return;
    }
    $zbp->Config('ThemePreview')->last = $last;
    $zbp->SaveConfig('ThemePreview');
    SetTheme($m["id"], str_replace(".css", "", $m["style"]));
    $zbp->BuildTemplate();
    RedirectByScript($zbp->host);
    return null;
  }
}
function ThemePreview_Url($id, $style)
{
  global $zbp;
  return "{$zbp->host}tPre/{$id}/{$style}";
}
function ThemePreview_GenTable(&$array = array())
{
  global $zbp;
  $allthemes = $zbp->LoadThemes();
  foreach ($allthemes as $theme) {
    $array[$theme->id] = $theme->name;
    echo "<p>{$theme->name}</p>";
    foreach ($theme->GetCssFiles() as $key => $value) {
      $url = ThemePreview_Url($theme->id, $key);
      echo "<p><a href=\"{$url}\" target=\"_blank\" title=\"{$theme->note}\">{$url}</a></p>";
    }
    echo "<p>----</p>";
  }
}
// function ThemePreview_Path($file, $t = 'path')
// {
//   global $zbp;
//   $result = $zbp->$t . 'zb_users/plugin/ThemePreview/';
//   switch ($file) {
//     case 'file':
//       return $result . 'file';
//       break;
//     case 'usr':
//       return $result . 'usr/';
//       break;
//     case 'var':
//       return $result . 'var/';
//       break;
//     case 'main':
//       return $result . 'main.php';
//       break;
//     default:
//       return $result . $file;
//   }
// }
function InstallPlugin_ThemePreview()
{
}
function UninstallPlugin_ThemePreview()
{
}
