{$doc = $docs|@current}
{extends file="master.tpl"}
{block name=content}
<div class="content">
				<div class="cols">
					<div class="sidemenu">
						<ul>
                         {$cur = $current_menu}
                     	 {while $cur != null && $cur->s_name != 'features'}
                     		{$cur = $cur->child}
                     	 {/while}
                         {if $cur != null}
                     		{$cur = $cur->child}
                     	 {/if}
                         {menu name='features' out='side_menu'}
                         {foreach $side_menu->menu as $item}
                         	{if $cur != null && $cur->id == $item->id}
                         		{$iscur = true}
                         	{else}
                         		{$iscur = false}
                            {/if}
							<li>
                            	<a href="{$base_url}{$item->route}" {if $iscur}class="cur"{/if}>{$item->name}</a>
                             	{if $iscur && isset($item->menu)}
                             		<ul>
                                     	{foreach $item->menu as $sub_item}
                                     		<li><a href="{$base_url}{$sub_item->route}" {if $cur->child != null && $cur->child->id == $sub_item->id}class="cur"{/if}>{$sub_item->name}</a></li>
                                     	{/foreach}
                                    </ul>
                             	{/if}
                            </li>
                         {/foreach}
						</ul>
					</div>
					<div class="main">
						<h1 class="title">{$doc->head}</h1>
                     	{block name=features_content}
                          <div class="text">
                              {$doc->content}
                          </div>
                     	{/block}
					</div>
				</div>
			</div>
{/block}