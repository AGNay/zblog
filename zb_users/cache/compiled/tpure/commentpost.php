<div class="cmt block" id="divCommentPost">
    <div class="posttitle">
        <h4><?php  echo $lang['tpure']['writecmt'];  ?><button id="cancel-reply" class="cmtbtn"><?php  echo $lang['tpure']['cancelreply'];  ?></button></h4>
    </div>
    <div class="comment">
        <div class="cmtimg"><img src="<?php  echo $user->Avatar;  ?>"></div>
        <div class="cmtarea">
            <form id="frmSumbit" target="_self" method="post" action="<?php  echo $article->CommentPostUrl;  ?>" >
			<input type="hidden" name="inpId" id="inpId" value="<?php  echo $article->ID;  ?>" />
			<input type="hidden" name="inpRevID" id="inpRevID" value="0" />
			<textarea name="txaArticle" id="txaArticle" rows="5" tabindex="1"></textarea>
<?php if ($user->ID>0) { ?>
			<input type="hidden" name="inpName" id="inpName" value="<?php  echo $user->Name;  ?>" />
			<input type="hidden" name="inpEmail" id="inpEmail" value="<?php  echo $user->Email;  ?>" />
			<input type="hidden" name="inpHomePage" id="inpHomePage" value="<?php  echo $user->HomePage;  ?>" />	
<?php }else{  ?>
            <div class="cmtform">
                <p><input type="text" name="inpName" id="inpName" class="text" size="28" tabindex="2"><label for="inpName"><?php  echo $lang['tpure']['inp_name'];  ?></label></p>
                <p><input type="text" name="inpEmail" id="inpEmail" class="text" size="28" tabindex="3"><label for="inpEmail"><?php  echo $lang['tpure']['inp_email'];  ?></label></p>
                <p><input type="text" name="inpHomePage" id="inpHomePage" class="text" size="28" tabindex="4"><label for="inpHomePage"><?php  echo $lang['tpure']['inp_homepage'];  ?></label></p>
                <?php if ($option['ZC_COMMENT_VERIFY_ENABLE']) { ?>
				<p><input type="text" name="inpVerify" id="inpVerify" class="textcode" value="" size="28" tabindex="5" /><img src="<?php  echo $article->ValidCodeUrl;  ?>" title="<?php  echo $lang['tpure']['refresh_code'];  ?>" onclick="javascript:this.src='<?php  echo $article->ValidCodeUrl;  ?>&amp;tm='+Math.random();" class="imgcode" /><label for="inpVerify"><?php  echo $lang['tpure']['inp_verify'];  ?></label></p>
				<?php } ?>
            </div>
<?php } ?>
            <div class="cmtsubmit">
                <button type="button" name="btnSumbit" onclick="return zbp.comment.post()" class="cmtbtn" tabindex="6"><?php  echo $lang['tpure']['cmt'];  ?></button>
                <span><?php  echo $lang['tpure']['cmttip'];  ?></span>
            </div>
            </form>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript">
var txaArticle = document.getElementById('txaArticle');
txaArticle.onkeydown = function quickSubmit(e) {
if (!e) var e = window.event;
if (e.ctrlKey && e.keyCode == 13){
return zbp.comment.post();
}
}
</script>