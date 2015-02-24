<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-02-02 17:07:30
         compiled from "D:\workspace\web\test_cms\application\templates\features.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1592554cf7692414213-85736427%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ba15384f3dff412062ef10af80081a08e6f1cc47' => 
    array (
      0 => 'D:\\workspace\\web\\test_cms\\application\\templates\\features.tpl',
      1 => 1422547121,
      2 => 'file',
    ),
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
  'nocache_hash' => '1592554cf7692414213-85736427',
  'function' => 
  array (
  ),
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
  'unifunc' => 'content_54cf7692eaff40_79603737',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54cf7692eaff40_79603737')) {function content_54cf7692eaff40_79603737($_smarty_tpl) {?><?php if (!isset($_smarty_tpl->tpl_vars['doc']->value)) {
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
				<div class="cols">
					<div class="sidemenu">
						<ul>
                         <?php $_smarty_tpl->tpl_vars['cur'] = new Smarty_variable($_smarty_tpl->tpl_vars['current_menu']->value, null, 0);?>
                     	 <?php while ($_smarty_tpl->tpl_vars['cur']->value!=null&&$_smarty_tpl->tpl_vars['cur']->value->s_name!='features') {?>
                     		<?php $_smarty_tpl->tpl_vars['cur'] = new Smarty_variable($_smarty_tpl->tpl_vars['cur']->value->child, null, 0);?>
                     	 <?php }?>
                         <?php if ($_smarty_tpl->tpl_vars['cur']->value!=null) {?>
                     		<?php $_smarty_tpl->tpl_vars['cur'] = new Smarty_variable($_smarty_tpl->tpl_vars['cur']->value->child, null, 0);?>
                     	 <?php }?>
                         <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['menu'][0][0]->function_menu(array('name'=>'features','out'=>'side_menu'),$_smarty_tpl);?>

                         <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['side_menu']->value->menu; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                         	<?php if ($_smarty_tpl->tpl_vars['cur']->value!=null&&$_smarty_tpl->tpl_vars['cur']->value->id==$_smarty_tpl->tpl_vars['item']->value->id) {?>
                         		<?php $_smarty_tpl->tpl_vars['iscur'] = new Smarty_variable(true, null, 0);?>
                         	<?php } else { ?>
                         		<?php $_smarty_tpl->tpl_vars['iscur'] = new Smarty_variable(false, null, 0);?>
                            <?php }?>
							<li>
                            	<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;
echo $_smarty_tpl->tpl_vars['item']->value->route;?>
" <?php if ($_smarty_tpl->tpl_vars['iscur']->value) {?>class="cur"<?php }?>><?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
</a>
                             	<?php if ($_smarty_tpl->tpl_vars['iscur']->value&&isset($_smarty_tpl->tpl_vars['item']->value->menu)) {?>
                             		<ul>
                                     	<?php  $_smarty_tpl->tpl_vars['sub_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sub_item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['item']->value->menu; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sub_item']->key => $_smarty_tpl->tpl_vars['sub_item']->value) {
$_smarty_tpl->tpl_vars['sub_item']->_loop = true;
?>
                                     		<li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;
echo $_smarty_tpl->tpl_vars['sub_item']->value->route;?>
" <?php if ($_smarty_tpl->tpl_vars['cur']->value->child!=null&&$_smarty_tpl->tpl_vars['cur']->value->child->id==$_smarty_tpl->tpl_vars['sub_item']->value->id) {?>class="cur"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sub_item']->value->name;?>
</a></li>
                                     	<?php } ?>
                                    </ul>
                             	<?php }?>
                            </li>
                         <?php } ?>
						</ul>
					</div>
					<div class="main">
						<h1 class="title"><?php echo $_smarty_tpl->tpl_vars['doc']->value->head;?>
</h1>
                     	
	<div class="img default"></div>
	<div class="text withimg">
    	<?php echo $_smarty_tpl->tpl_vars['doc']->value->content;?>

	</div>

					</div>
				</div>
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
