{$doc = $docs|@current}
{extends file="master.tpl"}
{block name=content}
<div class="content">
				<div class="title">
					<h1>{$doc->head}</h1>
					<div class="hr"></div>
				</div>
				<div class="contacts">
					<p>{$doc->s_content}</p>
					<div class="cols">
						<div id="map"></div>
						<div class="info">
							<div class="mail">
								<span></span>
								<p class="label">{resource name='contact_label_email'}</p>
								<p>info@rubetek.com</p>
							</div>
							<div class="skype">
								<span></span>
								<p class="label">Skype</p>
								<p>rubetek</p>
							</div>
							<div class="phone">
								<span></span>
								<p class="label">{resource name='contact_label_phone'}</p>
								<p>+7-909-225-6796</p>
							</div>
							<div class="addr">
								<span></span>
								<p class="label">{resource name='contact_label_address'}</p>
								<p>{resource name='contact_text_address'}</p>
							</div>
						</div>
					</div>
                 		{$doc->content}
				</div>
 				{literal}
				<script type="text/javascript" src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU"></script>
				<script type="text/javascript">
					$(function(){
						var coords = [55.757369, 37.557807];
						ymaps.ready(function(){
							var map = new ymaps.Map('map', {
								center: coords,
								zoom: 15
							});
							var placemark = new ymaps.Placemark(coords);
							map.geoObjects.add(placemark);
							map.controls
							.add('zoomControl')
							.add('typeSelector')
							.add('smallZoomControl', {top: 5})
							.add(new ymaps.control.ScaleLine());
						});
					});
				</script>
 				{/literal}
			</div>
{/block}