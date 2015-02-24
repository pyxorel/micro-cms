{$doc = $docs|@current}
{extends file="features_base.tpl"}
{block name=features_content}
	<div class="img default"></div>
	<div class="text withimg">
    	{$doc->content}
	</div>
{/block}