{*
	для теста передать следующие переменные:
	$topmenu
	$sidemenu
	$bottommenu
*}


{extends file="master.tpl"}
{block name=content}
<div class="content">
	<div class="cols">
		<div class="sidemenu">
			<ul>{menu name='top_menu' out='side_menu'}
				{foreach $side_menu->menus as $item}
					<li><a href="path/{$item->route}">{$item->name}</a></li>
				{/foreach}
			</ul>
		</div>
     	{$doc = $docs|@current}
		<div class="main">
			<h1 class="title">{$doc->head}</h1>
			<div class="img default"></div>
			<div class="text withimg">
				{$doc->content}
			</div>
		</div>
	</div>
</div>
{/block}