<!DOCTYPE html>
<html xml:lang="<?php  echo $lang['lang_bcp47'];  ?>" lang="<?php  echo $lang['lang_bcp47'];  ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" content="<?php  echo $language;  ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<?php 
$SEOON = $zbp->Config('tpure')->SEOON;
$SEOTITLE = $zbp->Config('tpure')->SEOTITLE;
$SEOKEYWORDS = $zbp->Config('tpure')->SEOKEYWORDS;
$SEODESCRIPTION = $zbp->Config('tpure')->SEODESCRIPTION;
if(isset($SEOON) && $SEOON == '1'){
    if($type == 'index'){
        if($page == '1'){
            if(isset($SEOTITLE) && !empty($SEOTITLE)){
                $ThisTitle = $SEOTITLE;
            }else{
                $ThisTitle = $zbp->name.' - '.$zbp->subname;
            }
        }else{
            if(isset($SEOTitle) && !empty($SEOTitle)){
                $ThisTitle = $SEOTitle.' - '.'第'.$page.'页';
            }else{
                $ThisTitle = $zbp->name.' - '.'第'.$page.'页'.' - '.$zbp->subname;
            }
        }
        if(isset($SEOKEYWORDS)){
            $keywords = $SEOKEYWORDS;
        }else{
            $keywords = '';
        }
        if(isset($SEODESCRIPTION)){
            $description = $SEODESCRIPTION;
        }else{
            $description = '';
        }
    }elseif($type == 'category'){
        if($category->Metas->catetitle){
            if ($page=='1') {
                $ThisTitle = $category->Metas->catetitle;
            }else{
                $ThisTitle = $zbp->title.' - '.'第'.$page.'页'.' - '.$category->Metas->catetitle;
            }
        }else{
            if ($page=='1') {
                $ThisTitle = $zbp->title.' - '.$zbp->name;
            }else{
                $ThisTitle = $zbp->title.' - '.'第'.$page.'页'.' - '.$zbp->name;
            }
        }
        if($category->Metas->catekeywords){
            $keywords = $category->Metas->catekeywords;
        }else{
            $keywords = $category->Name;
        }
        if($category->Metas->catedescription){
            $description = $category->Metas->catedescription;
        }else{
            $description = $category->Intro;
        }
    }elseif($type == 'tag'){
        if($tag->Metas->tagtitle){
            if ($page=='1') {
                $ThisTitle = $tag->Metas->tagtitle;
            }else{
                $ThisTitle = $zbp->title.' - '.'第'.$page.'页'.' - '.$tag->Metas->tagtitle;
            }
        }else{
            if ($page=='1') {
                $ThisTitle = $zbp->title.' - '.$zbp->name;
            }else{
                $ThisTitle = $zbp->title.' - '.'第'.$page.'页'.' - '.$zbp->name;
            }
        }
        if($tag->Metas->tagkeywords){
            $keywords = $tag->Metas->tagkeywords;
        }else{
            $keywords = $tag->Name;
        }
        if($tag->Metas->tagdescription){
            $description = $tag->Metas->tagdescription;
        }else{
            $description = $tag->Intro;
        }
    }elseif($type == 'article'){
        if($article->Metas->singletitle){
            $ThisTitle = $article->Metas->singletitle;
        }else{          
            $ThisTitle = $article->Title.' - '.$article->Category->Name.' - '.$zbp->name;
        }   
            if($article->Metas->singlekeywords){
            $keywords = $article->Metas->singlekeywords;
        }else{
            $aryTags = array();
            foreach($article->Tags as $key){
                $aryTags[] = $key->Name;
            }
            if(count($aryTags)>0){
                $keywords = implode(',',$aryTags);
            }else{
                $keywords = '';
            }
        }
        if($article->Metas->singledescription){
            $description = $article->Metas->singledescription;
        }else{
            $description = preg_replace('/[\r\n\s]+/', '', trim(SubStrUTF8(TransferHTML($article->Content,'[nohtml]'),100)).'...');
        }
    }elseif($type == 'page'){
        if($article->Metas->singletitle){
            $ThisTitle = $article->Metas->singletitle;
        }else{
            $ThisTitle = $article->Title.' - '.$zbp->name;
        }
        if($article->Metas->singlekeywords){
            $keywords = $article->Metas->singlekeywords;
        }else{
            $keywords = '';
        }
        if($article->Metas->singledescription){
            $description = $article->Metas->singledescription;
        }else{
            $description = preg_replace('/[\r\n\s]+/', '', trim(SubStrUTF8(TransferHTML($article->Content,'[nohtml]'),100)).'...');
        }
    }else {
        if($page>'1'){
            $ThisTitle = $zbp->title.' - '.'第'.$page.'页'.' - '.$zbp->name;
        }else{
            $ThisTitle = $zbp->title.' - '.$zbp->name;
        }
        if(isset($SEOKEYWORDS)){
            $keywords = $SEOKEYWORDS;
        }else{
            $keywords = '';
        }
        if(isset($SEODESCRIPTION)){
            $description = $SEODESCRIPTION;
        }else{
            $description = '';
        }
    }
}
 ?>
    <title><?php if (isset($SEOON) && $SEOON == '1') { ?><?php  echo $ThisTitle;  ?><?php }else{  ?><?php if ($type == 'index' && $page == '1') { ?><?php  echo $name;  ?> - <?php  echo $title;  ?><?php }else{  ?><?php  echo $title;  ?> - <?php  echo $name;  ?><?php } ?><?php } ?></title>
<?php if (isset($SEOON) && $SEOON == '1') { ?>
<?php if ($keywords) { ?>
    <meta name="keywords" content="<?php  echo $keywords;  ?>" />
<?php } ?>
<?php if ($description) { ?>
    <meta name="description" content="<?php  echo $description;  ?>"/>
<?php } ?>
<?php } ?>
    <?php if ($zbp->Config('tpure')->PostFAVICONON) { ?><link rel="shortcut icon" href="<?php  echo $zbp->Config('tpure')->PostFAVICON;  ?>" type="image/x-icon" /><?php } ?>
    <meta name="generator" content="<?php  echo $zblogphp;  ?>" />
<?php if ($type=='article') { ?>
    <link rel="canonical" href="<?php  echo $article->Url;  ?>"/>
<?php } ?>
    <link rel="stylesheet" rev="stylesheet" href="<?php  echo $host;  ?>zb_users/theme/<?php  echo $theme;  ?>/style/<?php  echo $style;  ?>.css" type="text/css" media="all"/>
<?php if ($zbp->Config('tpure')->PostCOLORON == '1') { ?>
    <link rel="stylesheet" rev="stylesheet" href="<?php  echo $host;  ?>zb_users/theme/<?php  echo $theme;  ?>/include/skin.css" type="text/css" media="all"/>
<?php } ?>
    <script src="<?php  echo $host;  ?>zb_system/script/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script src="<?php  echo $host;  ?>zb_system/script/zblogphp.js" type="text/javascript"></script>
    <script src="<?php  echo $host;  ?>zb_system/script/c_html_js_add.php" type="text/javascript"></script>
    <script type="text/javascript" src="<?php  echo $host;  ?>zb_users/theme/<?php  echo $theme;  ?>/script/common.js"></script>
    <script type="text/javascript">window.tpure={<?php if ($zbp->Config('tpure')->PostBANNERDISPLAYON=='1') { ?>bannerdisplay:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostVIEWALLON=='1') { ?>viewall:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostVIEWALLSTYLE) { ?>viewallstyle:'1',<?php }else{  ?>viewallstyle:'0',<?php } ?><?php if ($zbp->Config('tpure')->PostVIEWALLHEIGHT) { ?>viewallheight:'<?php  echo $zbp->Config('tpure')->PostVIEWALLHEIGHT;  ?>',<?php } ?><?php if ($zbp->Config('tpure')->PostAJAXON=='1') { ?>ajaxpager:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostLOADPAGENUM) { ?>loadpagenum:'<?php  echo $zbp->Config('tpure')->PostLOADPAGENUM;  ?>',<?php } ?><?php if ($zbp->Config('tpure')->PostLAZYLOADON=='1') { ?>lazyload:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostSELECTON=='1') { ?>selectstart:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostSINGLEKEY=='1') { ?>singlekey:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostPAGEKEY=='1') { ?>pagekey:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostREMOVEPON=='1') { ?>removep:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostBACKTOTOPON=='1') { ?>backtotop:'on'<?php } ?>}</script>
<?php if ($zbp->Config('tpure')->PostBLANKON=='1') { ?>
    <base target="_blank" />
<?php } ?>
<?php if ($zbp->Config('tpure')->PostGREYON=='1') { ?>
    <?php if ($zbp->Config('tpure')->PostGREYSTATE=='0') { ?>
    <?php if ($type == 'index') { ?><style>html { filter:grayscale(100%); } * { filter:gray; }</style><?php } ?>
    <?php }else{  ?>
    <style>html { filter:grayscale(100%); } * { filter:gray; }</style>
    <?php } ?>
<?php } ?>
<?php if ($type=='article') { ?>
    <link rel="canonical" href="<?php  echo $article->Url;  ?>"/>
<?php } ?>
<?php  echo $header;  ?><link rel="stylesheet" href="http://zblog.cn/zb_users/plugin/live2d2/var/css/live2d.css?v=2020-03-18" />
<?php if ($type=='index'&&$page=='1') { ?>
    <link rel="alternate" type="application/rss+xml" href="<?php  echo $feedurl;  ?>" title="<?php  echo $name;  ?>" />
    <link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php  echo $host;  ?>zb_system/xml-rpc/?rsd" />
    <link rel="wlwmanifest" type="application/wlwmanifest+xml" href="<?php  echo $host;  ?>zb_system/xml-rpc/wlwmanifest.xml" />
<?php } ?>
</head>