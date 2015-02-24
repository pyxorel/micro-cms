{$doc = $docs|@current}
{extends file="master.tpl"}
{block name=content}
<div class="banner">
				<div class="content">
					<h1>{$doc->head}</h1>
				</div>
			</div>
			<div class="line left">
				<div class="h"></div>
				<div class="v"></div>
			</div>
			<div class="content">
				<div class="cols">
					<div class="col col2">
						<div class="slogons">
							<div class="s1">
								<span>{resource name='home_phrase1'}</span>
								<span class="sh">{resource name='home_phrase1'}</span>
							</div>
							<div class="s2">
								<span>{resource name='home_phrase2'}</span>
								<span class="sh">{resource name='home_phrase2'}</span>
							</div>
							<div class="s3">
								<span>{resource name='home_phrase3'}</span>
								<span class="sh">{resource name='home_phrase3'}</span>
							</div>
							<div class="s4">
								<span>{resource name='home_phrase4'}</span>
								<span class="sh">{resource name='home_phrase4'}</span>
							</div>
						</div>
					</div>
					<div class="col col2">
						<div class="video">
							<a href="#" class="hover">
								<span></span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="line right">
				<div class="h"></div>
				<div class="v"></div>
			</div>
			<div class="content">
				{$doc->content}
				<div class="stores">
					<a href="{resource name='get_link_android'}" class="android"></a>
					<a href="{resource name='get_link_ios'}" class="ios"></a>
					<a href="#" class="windows"></a>
				</div>
			</div>
{/block}