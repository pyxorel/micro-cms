<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-01-30 15:10:20
         compiled from "D:\workspace\web\test_cms\application\templates\scheme.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2081854cb90ccd8f814-07560076%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'efce66dca8f4fde3bffc444c9b5766e6d21b5c8a' => 
    array (
      0 => 'D:\\workspace\\web\\test_cms\\application\\templates\\scheme.tpl',
      1 => 1422620920,
      2 => 'file',
    ),
    '90ad40f642796ba3adad017c09016a8b37f4fa66' => 
    array (
      0 => 'D:\\workspace\\web\\test_cms\\application\\templates\\master.tpl',
      1 => 1422616098,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2081854cb90ccd8f814-07560076',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'doc' => 0,
    'docs' => 0,
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
  'unifunc' => 'content_54cb90cd633b66_05021170',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54cb90cd633b66_05021170')) {function content_54cb90cd633b66_05021170($_smarty_tpl) {?><?php if (!isset($_smarty_tpl->tpl_vars['doc']->value)) {
$_smarty_tpl->tpl_vars['doc'] = new Smarty_variable(current($_smarty_tpl->tpl_vars['docs']->value), null, 0);
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,300italic&subset=latin,cyrillic' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/application/content/style.css" />
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/application/content/jquery-1.10.1.min.js"><?php echo '</script'; ?>
>
 	
	<?php echo '<script'; ?>
 type="text/javascript">
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
	<?php echo '</script'; ?>
>
 	
 	<?php $_smarty_tpl->tpl_vars['l'] = new Smarty_variable($_smarty_tpl->tpl_vars['current_menu']->value, null, 0);?>
 	<?php while ($_smarty_tpl->tpl_vars['l']->value!=null) {?>
 		<?php $_smarty_tpl->tpl_vars['last'] = new Smarty_variable($_smarty_tpl->tpl_vars['l']->value, null, 0);?>
 		<?php $_smarty_tpl->tpl_vars['l'] = new Smarty_variable($_smarty_tpl->tpl_vars['l']->value->child, null, 0);?>
 	<?php }?>
	<title>Rubetek Evo <?php if (isset($_smarty_tpl->tpl_vars['last']->value)&&$_smarty_tpl->tpl_vars['last']->value->route!='/') {?>| <?php echo $_smarty_tpl->tpl_vars['last']->value->name;
}?></title>
</head>
<body <?php if ($_smarty_tpl->tpl_vars['doc']->value->name=='home') {?>class="mainpage"<?php }?>>
	<div class="wrapper">
		<div class="page">
			<div class="header">
				<div class="menu">
					<div class="content">
						<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/" class="logo"></a>
						<a href="https://pm.rubetek.com" class="acc"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['resource'][0][0]->function_resource(array('name'=>'account_link'),$_smarty_tpl);?>
</a>
                     	<?php $_smarty_tpl->tpl_vars['cur'] = new Smarty_variable($_smarty_tpl->tpl_vars['current_menu']->value, null, 0);?>
                     	<?php while ($_smarty_tpl->tpl_vars['cur']->value!=null&&$_smarty_tpl->tpl_vars['cur']->value->s_name!='top_menu') {?>
                     		<?php $_smarty_tpl->tpl_vars['cur'] = new Smarty_variable($_smarty_tpl->tpl_vars['cur']->value->child, null, 0);?>
                     	<?php }?>
                     	<?php if ($_smarty_tpl->tpl_vars['cur']->value!=null) {?>
                     		<?php $_smarty_tpl->tpl_vars['cur'] = new Smarty_variable($_smarty_tpl->tpl_vars['cur']->value->child, null, 0);?>
                     	<?php }?>
                     	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['menu'][0][0]->function_menu(array('name'=>'top_menu','out'=>'top_menu'),$_smarty_tpl);?>

						<ul>
                         <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['top_menu']->value->menu; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
							<li <?php if (($_smarty_tpl->tpl_vars['current_menu']->value->child==null&&$_smarty_tpl->tpl_vars['item']->value->route=='/')||($_smarty_tpl->tpl_vars['cur']->value!=null&&$_smarty_tpl->tpl_vars['cur']->value->id==$_smarty_tpl->tpl_vars['item']->value->id)) {?>class="cur"<?php }?>>
								<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;
echo $_smarty_tpl->tpl_vars['item']->value->route;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
</a>
                             <?php if (isset($_smarty_tpl->tpl_vars['item']->value->menu)) {?>
                             	<?php if ($_smarty_tpl->tpl_vars['cur']->value!=null) {?>
                             		<?php $_smarty_tpl->tpl_vars['sub'] = new Smarty_variable($_smarty_tpl->tpl_vars['cur']->value->child, null, 0);?>
                             	<?php }?>
                             	<ul class="inner">
                                 <?php  $_smarty_tpl->tpl_vars['sub_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sub_item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['item']->value->menu; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sub_item']->key => $_smarty_tpl->tpl_vars['sub_item']->value) {
$_smarty_tpl->tpl_vars['sub_item']->_loop = true;
?>
                                  <li <?php if (isset($_smarty_tpl->tpl_vars['sub']->value)&&$_smarty_tpl->tpl_vars['sub']->value!=null&&$_smarty_tpl->tpl_vars['sub']->value->id==$_smarty_tpl->tpl_vars['sub_item']->value->id) {?>class="cur"<?php }?>>
                                         <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;
echo $_smarty_tpl->tpl_vars['sub_item']->value->route;?>
"><?php echo $_smarty_tpl->tpl_vars['sub_item']->value->name;?>
</a>
                                  </li>
                                 <?php } ?>
                                </ul>
                             <?php }?>
							</li>
                         <?php } ?>
						</ul>
					</div>
				</div>
			</div>
			

<div class="content">
				<div class="title">
					<h1><?php echo $_smarty_tpl->tpl_vars['docs']->value['scheme']->head;?>
</h1>
					<div class="hr"></div>
				</div>
				<?php echo $_smarty_tpl->tpl_vars['docs']->value['scheme']->content;?>

				<div class="title">
					<h1><?php echo $_smarty_tpl->tpl_vars['docs']->value['scheme']->s_content;?>
</h1>
					<div class="hr"></div>
				</div>
 				<?php $_smarty_tpl->tpl_vars['list'] = new Smarty_variable(array('controller','devices','account','client_managment'), null, 0);?>
 				<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
 					<?php if (isset($_smarty_tpl->tpl_vars['docs']->value[$_smarty_tpl->tpl_vars['item']->value])) {?>
                  <div class="partblock">
                      <div class="label">
                          <div>
                              <img src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/file_content/<?php echo $_smarty_tpl->tpl_vars['docs']->value[$_smarty_tpl->tpl_vars['item']->value]->img;?>
" alt="" />
                              <p><?php echo nl2br($_smarty_tpl->tpl_vars['docs']->value[$_smarty_tpl->tpl_vars['item']->value]->s_content);?>
</p>
                          </div>
                      </div>
                      <div class="text">
                          <?php echo $_smarty_tpl->tpl_vars['docs']->value[$_smarty_tpl->tpl_vars['item']->value]->content;?>

                      </div>
                  </div>
 					<?php }?>
 				<?php } ?>
			</div>

		</div>
		<div class="footer">
			<div class="content">
				<div class="phone"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['resource'][0][0]->function_resource(array('name'=>'phone'),$_smarty_tpl);?>
</div>
				<div class="copyright"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['resource'][0][0]->function_resource(array('name'=>'copyright'),$_smarty_tpl);?>
</div>
            		 <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['menu'][0][0]->function_menu(array('name'=>'bottom_menu','out'=>'bottom_menu'),$_smarty_tpl);?>

				<ul class="menu">
                 <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['bottom_menu']->value->menu; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
					<li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;
echo $_smarty_tpl->tpl_vars['item']->value->route;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
</a></li>
                 <?php } ?>
				</ul>
				<div class="lang">
					<span class="cur <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang_current_code'][0][0]->function_lang_current_code(array(),$_smarty_tpl);?>
"></span>
					<div class="popup">
						<div class="links">
                         	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang_list'][0][0]->function_lang_list(array('out'=>'langs'),$_smarty_tpl);?>

                         	<?php  $_smarty_tpl->tpl_vars['lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lang']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->key => $_smarty_tpl->tpl_vars['lang']->value) {
$_smarty_tpl->tpl_vars['lang']->_loop = true;
?>
								<p><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/lang/<?php echo $_smarty_tpl->tpl_vars['lang']->value->code;?>
/<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['base64'][0][0]->modifier_base64($_SERVER['REQUEST_URI']);?>
"><?php echo $_smarty_tpl->tpl_vars['lang']->value->text;?>
</a></p>
                         	<?php } ?>
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
