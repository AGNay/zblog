<?php  /*Template Name:搜索页模板*/  ?>
<?php  include $this->GetTemplate('header');  ?>
<body class="<?php  echo $type;  ?>">
<div class="wrapper">
    <?php  include $this->GetTemplate('navbar');  ?>
    <div class="main<?php if ($zbp->Config('tpure')->PostFIXMENUON=='1') { ?> fixed<?php } ?>">
        <div class="mask"></div>
        <div class="wrap">
            <?php if ($zbp->Config('tpure')->PostSITEMAPON=='1') { ?>
            <div class="sitemap"><?php  echo $lang['tpure']['sitemap'];  ?><a href="<?php  echo $host;  ?>"><?php  echo $lang['tpure']['index'];  ?></a> > <?php  echo $title;  ?>
            </div>
            <?php } ?>
            <div class="content">
                <div class="block">
                    <?php  foreach ( $articles as $article) { ?>
                    <?php  include $this->GetTemplate('post-multi');  ?>
                    <?php }   ?>
                </div>
                <div class="pagebar">
                    <?php  include $this->GetTemplate('pagebar');  ?>
                </div>
            </div>
            <div class="sidebar">
                <?php  include $this->GetTemplate('sidebar5');  ?>
            </div>
        </div>
    </div>
    <?php  include $this->GetTemplate('footer');  ?>