{extends file="controller.tpl"}
{block name=right}
	<img src="{$base_url}/file_content/{$docs.account_second->img}" alt="" />
{/block}
{block name=second_title}
	<div class="title">
		<h1>{$docs.account_second->head|regex_replace:"/(RUBETEK EVO)/i":"<span>\$1</span>"}</h1>
		<div class="hr"></div>
	</div>
	<p class="desc">{$docs.account_second->s_content}</p>
	<div class="editor"></div>
{/block}
{block name=second_content}
	<div class="whitestripe"></div>
	<div class="content">
		<div class="editorlogo"></div>
		<div class="main2">
         	{$docs.account_second->content}
       	</div>
    </div>
{/block}