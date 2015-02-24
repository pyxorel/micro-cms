<?php /*%%SmartyHeaderCode:1682054cb907b9a5012-39128229%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'acb7757c7322ba18aba75b9ded06710f87474688' => 
    array (
      0 => 'D:\\workspace\\web\\test_cms\\application\\templates\\features_base.tpl',
      1 => 1422617012,
      2 => 'file',
    ),
    '90ad40f642796ba3adad017c09016a8b37f4fa66' => 
    array (
      0 => 'D:\\workspace\\web\\test_cms\\application\\templates\\master.tpl',
      1 => 1422616098,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1682054cb907b9a5012-39128229',
  'variables' => 
  array (
    'docs' => 0,
    'doc' => 0,
    'base_url' => 0,
    'current_menu' => 0,
    'l' => 0,
    'last' => 0,
    'cur' => 0,
    'top_menu' => 0,
    'item' => 0,
    'sub' => 0,
    'sub_item' => 0,
    'bottom_menu' => 0,
    'langs' => 0,
    'lang' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_54cb907c57ead3_58831039',
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54cb907c57ead3_58831039')) {function content_54cb907c57ead3_58831039($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,300italic&subset=latin,cyrillic' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="http://localhost/test_cms/application/content/style.css" />
	<script type="text/javascript" src="http://localhost/test_cms/application/content/jquery-1.10.1.min.js"></script>
 	
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
 	
 	 	 		 		 	 		 		 	 		 		 	 		 		 		<title>Rubetek Evo | Возможности</title>
</head>
<body >
	<div class="wrapper">
		<div class="page">
			<div class="header">
				<div class="menu">
					<div class="content">
						<a href="http://localhost/test_cms/" class="logo"></a>
						<a href="https://pm.rubetek.com" class="acc">Личный кабинет</a>
                     	                     	                     		                     	                     	                     		                     	                     	
						<ul>
                         							<li >
								<a href="http://localhost/test_cms/">Главная</a>
                             							</li>
                         							<li class="cur">
								<a href="http://localhost/test_cms/about">О системе</a>
                                                          	                             		                             	                             	<ul class="inner">
                                                                   <li class="cur">
                                         <a href="http://localhost/test_cms/about/features">Возможности</a>
                                  </li>
                                                                   <li >
                                         <a href="http://localhost/test_cms/about/work">Как работает</a>
                                  </li>
                                                                   <li >
                                         <a href="http://localhost/test_cms/about/advantages">Преимущества системы</a>
                                  </li>
                                                                 </ul>
                             							</li>
                         							<li >
								<a href="http://localhost/test_cms/support">Поддержка </a>
                             							</li>
                         							<li >
								<a href="http://localhost/test_cms/buy">Где купить</a>
                             							</li>
                         						</ul>
					</div>
				</div>
			</div>
			
<div class="content">
				<div class="cols">
					<div class="sidemenu">
						<ul>
                                              	                      		                     	                      		                     	                      		                     	                                               		                     	                          
                                                  	                         		                            							<li>
                            	<a href="http://localhost/test_cms/about/features/light" >Освещение</a>
                             	                            </li>
                                                  	                         		                            							<li>
                            	<a href="http://localhost/test_cms/about/features/power" >Электропитание</a>
                             	                            </li>
                                                  	                         		                            							<li>
                            	<a href="http://localhost/test_cms/about/features/control" >Контроль</a>
                             	                            </li>
                                                  	                         		                            							<li>
                            	<a href="http://localhost/test_cms/about/features/security" >Безопасность</a>
                             	                            </li>
                                                  	                         		                            							<li>
                            	<a href="http://localhost/test_cms/about/features/video" >Видеонаблюдение</a>
                             	                            </li>
                                                  	                         		                            							<li>
                            	<a href="http://localhost/test_cms/about/features/multimedia" >Мультимедиа</a>
                             	                            </li>
                                                  	                         		                            							<li>
                            	<a href="http://localhost/test_cms/about/features/climate" >Климат</a>
                             	                            </li>
                                                  	                         		                            							<li>
                            	<a href="http://localhost/test_cms/about/features/scripts" >Сценарии</a>
                             	                            </li>
                                                  	                         		                            							<li>
                            	<a href="http://localhost/test_cms/about/features/statistics" >Отчеты</a>
                             	                            </li>
                         						</ul>
					</div>
					<div class="main">
						<h1 class="title">Управляй с любого устройства</h1>
                     	
                          <div class="text">
                              <img src="http://localhost/test_cms/file_content/MTIzXGltZzAxLnBuZw" />
                          </div>
                     	
					</div>
				</div>
			</div>

		</div>
		<div class="footer">
			<div class="content">
				<div class="phone">+7 914 091 5500</div>
				<div class="copyright">© 2015 Rubetek, Все права защищены</div>
            		 
				<ul class="menu">
                 					<li><a href="http://localhost/test_cms/partners">Партнерам</a></li>
                 					<li><a href="http://localhost/test_cms/contacts">Контакты</a></li>
                 					<li><a href="http://localhost/test_cms/vacancies">Вакансии</a></li>
                 					<li><a href="http://localhost/test_cms/company">О компании</a></li>
                 				</ul>
				<div class="lang">
					<span class="cur ru"></span>
					<div class="popup">
						<div class="links">
                         	
                         									<p><a href="http://localhost/test_cms/lang/ru/L3Rlc3RfY21zL2Fib3V0L2ZlYXR1cmVz">Русский</a></p>
                         									<p><a href="http://localhost/test_cms/lang/en/L3Rlc3RfY21zL2Fib3V0L2ZlYXR1cmVz">English</a></p>
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
</html><?php }} ?>
