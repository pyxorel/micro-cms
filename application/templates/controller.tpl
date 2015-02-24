{$cnt = $docs|@count}
{if $cnt > 1}
	{$doc = $docs.account_details}
{else}
	{$doc = $docs|@current}
{/if}
{extends file="master.tpl"}
{block name=content}
<div class="content">
				<div class="title">
					<h1>{$doc->head|regex_replace:"/(RUBETEK EVO)/i":"<span>\$1</span>"}</h1>
					<div class="hr"></div>
				</div>
				<div class="preamble">
					<div class="left">
						<img src="{$base_url}/file_content/{$doc->img}" alt="" />
                     {$l = $current_menu}
                     {while $l != null}
                         {$last = $l}
                         {$l = $l->child}
                     {/while}
						<p>
                        {if isset($last)}
                        	{$last->name|wordwrap:20:"<br/>"}
                     	{/if}
                    	</p>
					</div>
					<div class="right">
                     	{block name=right}
                     		<p>{$doc->s_content}</p>
                     	{/block}
					</div>
				</div>
				<div class="main2">
					{$doc->content}
				</div>
 				{block name=second_title}{/block}
			</div>
			{block name=second_content}{/block}
{/block}