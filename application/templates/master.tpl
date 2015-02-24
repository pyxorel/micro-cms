{if !isset($doc)}{$doc=$docs|@current}{/if}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,300italic&subset=latin,cyrillic' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="{$base_url}/application/content/style.css" />
	<script type="text/javascript" src="{$base_url}/application/content/jquery-1.10.1.min.js"></script>
 	{literal}
	<script type="text/javascript">
		$(function(){
			$('.header ul:not(.inner) a').click(function(){
				var $this = $(this), $parent = $this.parent();
				$parent.siblings().removeClass('active');
				if($this.siblings('.inner').size() > 0){
					$parent.addClass('active');
					$('.lang .popup').hide();
					return false;
				}
				return true;
			});
			$('.lang .cur').click(function(){
				$(this).siblings('.popup').show();
				$('.header .active').removeClass('active');
				return false;
			});
			$(document).click(function(e){
				var $lang = $('.lang');
				if($lang.has(e.target).length === 0){
					$('.popup', $lang).hide();
				}
				var $inner = $(e.traget).parents('.inner').parent();
				if($inner.size() <= 0){
					$('.header .active').removeClass('active');
				}
			});
		});
	</script>
 	{/literal}
 	{$l = $current_menu}
 	{while $l != null}
 		{$last = $l}
 		{$l = $l->child}
 	{/while}
	<title>Rubetek Evo {if isset($last) && $last->route != '/'}| {$last->name}{/if}</title>
</head>
<body {if $doc->name == 'home'}class="mainpage"{/if}>
	<div class="wrapper">
		<div class="page">
			<div class="header">
				<div class="menu">
					<div class="content">
						<a href="{$base_url}/" class="logo"></a>
						<a href="https://pm.rubetek.com" class="acc">{resource name='account_link'}</a>
                     	{$cur = $current_menu}
                     	{while $cur != null && $cur->s_name != 'top_menu'}
                     		{$cur = $cur->child}
                     	{/while}
                     	{if $cur != null}
                     		{$cur = $cur->child}
                     	{/if}
                     	{menu name='top_menu' out='top_menu'}
						<ul>
                         {foreach $top_menu->menu as $item}
							<li {if ($current_menu->child == null && $item->route == '/') || ($cur != null && $cur->id == $item->id)}class="cur"{/if}>
								<a href="{$base_url}{$item->route}">{$item->name}</a>
                             {if isset($item->menu)}
                             	{if $cur != null}
                             		{$sub = $cur->child}
                             	{/if}
                             	<ul class="inner">
                                 {foreach $item->menu as $sub_item}
                                  <li {if isset($sub) && $sub != null && $sub->id == $sub_item->id}class="cur"{/if}>
                                         <a href="{$base_url}{$sub_item->route}">{$sub_item->name}</a>
                                  </li>
                                 {/foreach}
                                </ul>
                             {/if}
							</li>
                         {/foreach}
						</ul>
					</div>
				</div>
			</div>
			{block name=content}{/block}
		</div>
		<div class="footer">
			<div class="content">
				<div class="phone">{resource name='phone'}</div>
				<div class="copyright">{resource name='copyright'}</div>
            		 {menu name='bottom_menu' out='bottom_menu'}
				<ul class="menu">
                 {foreach $bottom_menu->menu as $item}
					<li><a href="{$base_url}{$item->route}">{$item->name}</a></li>
                 {/foreach}
				</ul>
				<div class="lang">
					<span class="cur {lang_current_code}"></span>
					<div class="popup">
						<div class="links">
                         	{lang_list out='langs'}
                         	{foreach $langs as $lang}
								<p><a href="{$base_url}/lang/{$lang->code}/{$smarty.server.REQUEST_URI|base64}">{$lang->text}</a></p>
                         	{/foreach}
							<div class="arr">
								<div></div>
								<span></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>