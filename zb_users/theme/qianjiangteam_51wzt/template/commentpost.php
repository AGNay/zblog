<div class="comment" id="comment">
    <h4 class="comment-title">发表评论</h4>
    <div class="comment-form">
        <form id="frmSumbit" target="_self" method="post" action="{$article.CommentPostUrl}">
            <p class="comment-form-head">电子邮件地址不会被公开。 必填项已用*标注</p>
            <input type="hidden" name="inpId" id="inpId" value="{$article.ID}" />
            <input type="hidden" name="inpRevID" id="inpRevID" value="0" />
            
            <div class="comment-form-body">
                <textarea name="txaArticle" id="txaArticle" cols="30" rows="10" resize="no"></textarea>
            </div>
            <div class="comment-form-footer">
            {if $user.ID>0}
                <div class="input-container">
                    <span><span class="red">*</span>名字：</span>
                    <input type="text" name="inpName" id="inpName" value="{$user.StaticName}" autocomplete="off">
                </div>
                <div class="input-container">
                    <span><span class="red">*</span>邮箱：</span>
                    <input type="text" name="inpEmail" id="inpEmail" value="{$user.Email}" autocomplete="off">
                </div>
                <input type="hidden" name="inpHomePage" id="inpHomePage" value="" />
            {else}
                <div class="input-container">
                    <span><span class="red">*</span>名字：</span>
                    <input type="text" name="inpName" id="inpName" value="{$user.StaticName}" tabindex="1" autocomplete="off">
                </div>
                <div class="input-container">
                    <span><span class="red">*</span>邮箱：</span>
                    <input type="text" name="inpEmail" id="inpEmail" value="{$user.Email}" tabindex="2" autocomplete="off">
                </div>
                <input type="hidden" name="inpHomePage" id="inpHomePage" value="" tabindex="3" />
                {if $option['ZC_COMMENT_VERIFY_ENABLE']}
                <div class="input-container" style="padding: 1px 10px;margin-top:5px">
                    <span><span class="red">*</span>验证码：</span>
                    <input type="text" name="inpVerify" id="inpVerify" value="" tabindex="4" autocomplete="off">
                    <img style="width:{$option['ZC_VERIFYCODE_WIDTH']}px;height:{$option['ZC_VERIFYCODE_HEIGHT']}px;cursor:pointer;" src="{$article.ValidCodeUrl}" alt="" title="" onclick="javascript:this.src='{$article.ValidCodeUrl}&amp;tm='+Math.random();"/>
                </div>
                {/if}

            {/if}
                
                <button type="submit" tabindex="6" class="btn btn-primary" onclick="return zbp.comment.post()">提交</button>
            </div>
        </form>
    </div>
</div>