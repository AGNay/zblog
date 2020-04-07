<?php
#注册插件
RegisterPlugin("qianjiangteam_51wzt","ActivePlugin_qianjiangteam_51wzt");

function ActivePlugin_qianjiangteam_51wzt() {
    global $zbp;
    Add_Filter_Plugin('Filter_Plugin_Admin_TopMenu', 'qianjiangteam_51wzt_AddMenu');
    Add_Filter_Plugin('Filter_Plugin_Edit_Response5','qianjiangteam_51wzt_AticleThumbnail');
    Add_Filter_Plugin('Filter_Plugin_Edit_Response5','qianjiangteam_51wzt_AticleSeo');
    Add_Filter_Plugin('Filter_Plugin_ViewPost_Template','qianjiangteam_51wzt_imgAddClass');

}

function qianjiangteam_51wzt_AddMenu(&$m){
    global $zbp;
    $menu_name = MakeTopMenu("root",'主题配置',$zbp->host."zb_users/theme/qianjiangteam_51wzt/main.php","","topmenu_zblog5_lizi");
    array_unshift($m,$menu_name);
}

function qianjiangteam_51wzt_AticleThumbnail(){ 		 	 		     	  		       					          				     		 	    
    global $zbp,$article;    
    $uploadjs = "var container = document.createElement('script');$(container).attr('type','text/plain').attr('id','img_editor');$('body').append(container);_editor = UE.getEditor('img_editor');_editor.ready(function(){_editor.hide();$('.uploadimg strong').click(function(){object = $(this).parent().find('.uplod_img');_editor.getDialog('insertimage').open();_editor.addListener('beforeInsertImage', function (t, arg) {object.attr('value', arg[0].src);});});}); ";		   	     			  		    	 		 	          				
    echo "<script type='text/javascript'>{$uploadjs}</script>";
    echo '<p class="uploadimg"><span style="font-size: 1.1em;font-weight: bold;">自定义缩略图：</span><br>
    <input name="meta_thumbnail_img" placeholder="文章缩略图，为空默认使用文章第一张图，若无图则随机使用一张图" id="edtTitle" type="text" class="uplod_img" style="width:80%;" value="'.$article->Metas->thumbnail_img.'" />
    <strong style="color: #ffffff; font-size: 14px;padding: 6px 18px 6px 18px; background: #3a6ea5;border: 1px solid #3399cc; cursor: pointer;">上传缩略图</strong>
    </p>';
}
function qianjiangteam_51wzt_AticleSeo(){ 		 	 		     	  		       					          				     		 	    
    global $zbp,$article;        	  				    		     	    	 		 	 	         			
	echo '<div class="editmod"><label for="meta_keywords" class="editinputname">自定义文章关键词(主题功能)：</label><input type="text" name="meta_keywords" style="margin: 5px 3px 0 3px; padding: 3px; line-height: 1.8em; height: 1.8em; font-size: 1.2em; width: 99%; color: #333;" placeholder="默认调用文章标签" value="'.$article->Metas->keywords.'"/></div>';    		 		  	    		 	 			    	 	 				        		  
	echo '<div class="editmod"><label for="meta_description" class="editinputname">自定义文章描述(主题功能)：</label><textarea name="meta_description" style="margin: 5px 3px 0 3px; padding: 3px; line-height: 1.8em; height: 5em; font-size: 1.2em; width: 99%; color: #333;" placeholder="默认调用文章摘要">'.$article->Metas->description.'</textarea></div>';
}

//front start  
function qianjiangteam_51wzt_GetImage($type=1,$param=array()) {
    global $zbp;    
    
    if(1==$type) {//favicon.ico
        $path = $zbp->host .'zb_users/theme/qianjiangteam_51wzt/needfile/images/favicon.ico';
        echo $path;
    }elseif(2==$type){
        $path = $zbp->host .'zb_users/theme/qianjiangteam_51wzt/needfile/images/front-logo.png';
        echo $path;
    }
    
    echo '';
}

function qianjiangteam_51wzt_GetCateList($array=array()) {
    global $zbp;

    $select = '*';
    $where  = array(array('=','cate_ParentID',0));
    $order  =  array('cate_Order'=>'DESC');
    $lists  = $zbp->GetCategoryList($select, $where,$order, null, null);
    $cateListArr = array();
    foreach($lists as $key=>$list) {
        $cateSubListArr = array();
        $where  = array(array('=','cate_ParentID',$list->ID));
        $subLists = $zbp->GetCategoryList($select, $where,$order, null, null);
        if($subLists) {
            foreach($subLists as $k=>$subone) {
                $cateSubListArr[$k] = array(
                    'id'=>$subone->ID,
                    'cate_name'=>$subone->Name,
                    'cate_url'=>$subone->Url,
                );
            }
        };
        $cateListArr[$key] = array(
            'id'=>$list->ID,
            'cate_name'=>$list->Name,
            'cate_url'=>$list->Url,
            'sub_cate'=>$cateSubListArr,
        );
    }
    return  $cateListArr;
}


function qianjiangteam_51wzt_firstImg($article,$option=array('urltext'=>false)) {      		  		       			      	     		
    global $zbp;    	 	  		      				 	      		 	  	
    $num = mt_rand(1,10);    		 		 	      				  	       	  	 
    $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";                  		    	     		   	 	    						 	    	  		   
    $content = $article->Content;                			 	 		    	  			 	    	    			         	 	
    preg_match_all($pattern,$content,$matchContent);                    	  	     	 				     			  	      	 	 	 	 
    if(isset($matchContent[1][0])) {                   	  		 	     		 	 		      		 			            
        $imgurl =$matchContent[1][0];                    			 	 	     	   	  	    	    			    	  	    
    }else{                    	 	 	  	    			   		      	 	  	    			 		  
        $imgurl=$zbp->host."zb_users/theme/qianjiangteam_51wzt/needfile/images/random_". $num .".png";    	 		 	           	        			   
    }
    if($option['urltext']==true) {
        return $imgurl;
    }else{
         echo $imgurl; 
    }        	      	     	     	  		 		     	 	 	            	     		    		  		        					      			 		 
}

function qianjiangteam_51wzt_relatedPost($article) {
    global $zbp,$str;
    $str = ','.$article->Category->ID;
    qianjiangteam_51wzt_getParentCateID($article->Category);
    $catestr = trim($str,',');

    $select = array('*');
    $where  = array(array('in','log_CateID',$catestr));
    $order  = array('log_ViewNums'=>'DESC');
    $lists = $zbp->GetArticleList($select,$where,$order,array(8),'');   
    $return = array();
    foreach($lists as $key=>$list) {
        if($list->ID != $article->ID){
            $return[$key] = array(
                'img_url'   => qianjiangteam_51wzt_firstImg($list,array('urltext'=>true)),
                'title'     => $list->Title,
                'url'       => $list->Url
            );
        }
        
    }  	  	  	     
    //echo '<pre>';print_r($return);die;		  	 	
    return $return;

    
}

function qianjiangteam_51wzt_getParentCateID($category) {
    global $str;
    if($category->Parent) {
        $str = $str .','.$category->Parent->ID;
        qianjiangteam_51wzt_getParentCateID($category->Parent);
    }
}

function qianjiangteam_51wzt_imgAddClass(&$template){
    global $zbp;
    $article = $template->GetTags('article');
    $pattern = "/<img(.*?)src=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>/i";
    $replacement = '<img class="img-responsive" $1 src=$2$3.$4$5 />';
    $content = preg_replace($pattern, $replacement, $article->Content);
    $article->Content = $content;
    $template->SetTags('article', $article);

}



function InstallPlugin_qianjiangteam_51wzt() {
    global $zbp;

    if(!$zbp->Config('qianjiangteam_51wzt')->HasKey('Version'))
    {
        $zbp->Config('qianjiangteam_51wzt')->Version = '1.0';
        $zbp->Config('qianjiangteam_51wzt')->qjt_base_qq_switch = 1;
        $zbp->Config('qianjiangteam_51wzt')->qjt_base_weixin_switch = 1;
        $zbp->Config('qianjiangteam_51wzt')->qjt_base_weibo_switch = 1;
        $zbp->Config('qianjiangteam_51wzt')->qjt_base_qq = '727677752';
        $zbp->Config('qianjiangteam_51wzt')->qjt_base_weibo = 'https://weibo.com/u/7409307829';
        $zbp->Config('qianjiangteam_51wzt')->qjt_base_weibo = 'https://weibo.com/u/7409307829';
        $zbp->Config('qianjiangteam_51wzt')->qjt_carousel_img = '轮播信息1111|轮播信息2222|轮播信息3333';
        $zbp->Config('qianjiangteam_51wzt')->qjt_footer_left = '<a href="https://www.zblogcn.com/">Zblog主题</a><a href="#">网站分类</a><a href="#">热门文章</a>';
        $zbp->Config('qianjiangteam_51wzt')->qjt_footer_right = '热搜：<a href="https://www.zblogcn.com/">Zblog主题</a><a href="#">网络营销</a><a href="#">热门文章</a>';
        $zbp->Config('qianjiangteam_51wzt')->qjt_footer_copyright = '@Zblog主题-- 专业博客程序，十年磨一剑！';
        $zbp->Config('qianjiangteam_51wzt')->qjt_seo_switch = 1;
        $zbp->Config('qianjiangteam_51wzt')->qjt_seo_site_title = '三疯工作室';
        $zbp->Config('qianjiangteam_51wzt')->qjt_seo_site_keywords = '主题制作、分享';
        $zbp->Config('qianjiangteam_51wzt')->qjt_adv_switch = 0;
        $zbp->Config('qianjiangteam_51wzt')->qjt_adv_pc_head = '';
        $zbp->Config('qianjiangteam_51wzt')->qjt_adv_mobile_head = '';

        $zbp->SaveConfig('qianjiangteam_51wzt');
    }
    $zbp->SaveConfig('qianjiangteam_51wzt');
}
function UninstallPlugin_qianjiangteam_51wzt() {}