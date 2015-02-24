{extends file="master.tpl"}
{block name=content}

<div class="content">
				<div class="title">
					<h1>{$docs.scheme->head}</h1>
					<div class="hr"></div>
				</div>
				{$docs.scheme->content}
				<div class="title">
					<h1>{$docs.scheme->s_content}</h1>
					<div class="hr"></div>
				</div>
 				{$list = ['controller', 'devices', 'account', 'client_managment']}
 				{foreach $list as $item}
 					{if isset($docs.{$item})}
                  <div class="partblock">
                      <div class="label">
                          <div>
                              <img src="{$base_url}/file_content/{$docs.{$item}->img}" alt="" />
                              <p>{$docs.{$item}->s_content|nl2br}</p>
                          </div>
                      </div>
                      <div class="text">
                          {$docs.{$item}->content}
                      </div>
                  </div>
 					{/if}
 				{/foreach}
			</div>
{/block}