

<ul  class="cmt{$comment.ID}">
    <li>
        <div class="user">
            <span class="avatar" style="background-image: url({$comment.Author.Avatar});"></span>
            <p class="name">{$comment.Author.StaticName}</p>
            <p class="time">
                {$comment.Time()} 
                <a class="reply" href="#comment" onclick="zbp.comment.reply('{$comment.ID}')">回复</a>
            </p>
        </div>
        <div class="content">{$comment.Content}</div>

        <div class="comments-reply">
            {foreach $comment.Comments as $comment}
                {template:comment}
            {/foreach}
        </div>
    </li>
</ul>


