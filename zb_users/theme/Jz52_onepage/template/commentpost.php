{* Template Name:评论提交模块 *}
<section id="divCommentPost" class="cpost">
  <h2 class="mt2">评论{if $user.ID>0} ({$user.StaticName}){/if}：</h2>
  <form id="frmSumbit" target="_self" method="post" action="{$article.CommentPostUrl}" >
    <input type="hidden" name="inpId" id="inpId" value="{$article.ID}">
    <input type="hidden" name="inpRevID" id="inpRevID" value="0">
    <div class="row gtr-uniform"> {if $user.ID>0}
      <input type="hidden" name="inpName" id="inpName" value="{$user.Name}">
      <input type="hidden" name="inpEmail" id="inpEmail" value="{$user.Email}">
      <input type="hidden" name="inpHomePage" id="inpHomePage" value="{$user.HomePage}">
      {else}
      <div class="col-6 col-12-xsmall">
        <input type="text" name="inpName" id="inpName" value="" placeholder="称呼" tabindex="1">
      </div>
      <div class="col-6 col-12-xsmall">
        <input type="email" name="inpEmail" id="inpEmail" value="" placeholder="邮件" tabindex="2">
      </div>
      <div class="col-6 col-12-xsmall">
        <input type="text" name="inpHomePage" id="inpHomePage" value="" placeholder="网址" tabindex="3">
      </div>
      {if $option['ZC_COMMENT_VERIFY_ENABLE']}
      <div class="comment-form col-6 col-12-xsmall">
        <input type="text" name="inpVerify" id="inpVerify" value="" placeholder="验证码" tabindex="4">
        <img class="Coduimg" style="width:{$option['ZC_VERIFYCODE_WIDTH']}px;height:{$option['ZC_VERIFYCODE_HEIGHT']}px;cursor:pointer;" src="{$article.ValidCodeUrl}" alt="" title="" onclick="javascript:this.src='{$article.ValidCodeUrl}&amp;tm='+Math.random();"> </div>
      {/if}
      {/if}
      <div class="col-12">
        <div class="textarea-wrapper">
          <div class="textarea-wrapper">
            <textarea name="txaArticle" id="txaArticle" placeholder="想说点啥？" rows="1" tabindex="5" style="overflow: hidden; resize: none; height: 79px;"></textarea>
          </div>
        </div>
      </div>
      <div class="col-12 d-flex">
        <div class="flex-fill"></div>
        <ul class="actions">
          <li>
            <input name="sumbit" type="submit" tabindex="6" value="提交内容" onclick="return zbp.comment.post()" class="primary">
          </li>
		  <li> <a rel="nofollow" id="cancel-reply" href="#divCommentPost"  class="button" style="display:none;">取消回复</a> </li>
        </ul>
      </div>
    </div>
  </form>
</section>