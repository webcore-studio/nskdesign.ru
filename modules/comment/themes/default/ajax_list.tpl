
{strip}
<div id="mod_comment_new"></div>
<div id="mod_comment_list">
	{if $comment_list}
		<h2>Отзывы</h2>
        {foreach item=comment from=$comment_list}
			<div class="item"> 
				{if $comment.user_id>0}
					<b>{$comment.user_id_name}</b>
				{elseif $comment.user_displayname}
					<b>{$comment.user_displayname|truncate:80}</b>
				{else}
					<b> Гость</b>
				{/if}

				
				<div style="display:inline-block">
{if $comment.comm_fields.comment_rating_ball>0 && $comment.comm_fields.comment_rating_ball<1}
<img src="/modules/rating/images/star_half.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " alt="Оценка: {$comment.comm_fields.comment_rating_ball}">
{elseif $comment.comm_fields.comment_rating_ball==1}
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " alt="Оценка: {$comment.comm_fields.comment_rating_ball} ">
{elseif $comment.comm_fields.comment_rating_ball>1 && $comment.comm_fields.comment_rating_ball<2}
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " alt="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star_half.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " >
{elseif $comment.comm_fields.comment_rating_ball==2}
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " alt="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " >
{elseif $comment.comm_fields.comment_rating_ball>2 && $comment.comm_fields.comment_rating_ball<3}
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " alt="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star_half.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " >
{elseif $comment.comm_fields.comment_rating_ball==3}
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " alt="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " >
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
{elseif $comment.comm_fields.comment_rating_ball>3 && $comment.comm_fields.comment_rating_ball<4}
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " alt="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " >
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star_half.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
{elseif $comment.comm_fields.comment_rating_ball==4}
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " alt="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
{elseif $comment.comm_fields.comment_rating_ball>4 && $comment.comm_fields.comment_rating_ball<5}
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " alt="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star_half.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
{elseif $comment.comm_fields.comment_rating_ball==5}
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} " alt="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
<img src="/modules/rating/images/star.png" title="Оценка: {$comment.comm_fields.comment_rating_ball} ">
{/if}
</div>


				&nbsp;<div class="date">{$comment.comm_addtime|date_format:"%d.%m.%Y, %H:%M"}</div>
			  
				{if $curuser_sec_level>=50 || ($curuser_id>0 && $comment.user_id>0 && $comment.user_id==$curuser_id && $curuser_sec_level>=50)}
					{array name='url_vars'}
					{array_append name='url_vars' key='id' value=$comment.id}
					 <a href="" onclick="comment_modify({$comment.id});return false;"><i class="icon-edit"></i></a>&nbsp;
				{/if}

				{if $curuser_sec_level>=60 || ($curuser_id>0 && $comment.user_id>0 && $comment.user_id==$curuser_id && $curuser_sec_level>=60)}
					<a href="" onclick="comment_delete({$comment.id});return false;"><i class="icon-remove"></i></a>
				{/if}
				<div style="padding:7px 0;">{$comment.comm_content|animated|wordwrap}</div>
			</div>
        {/foreach}
	{/if}
</div>

{/strip}