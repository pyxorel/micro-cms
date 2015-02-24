<script
    src="<?= base_url('application/content/cms/javaScripts/elrte/js/elrte.full.js') ?>"
    type="text/javascript" charset="utf-8"></script>

<script
    src="<?= base_url('application/content/cms/javaScripts/elrte/js/i18n/elrte.ru.js') ?>"
    type="text/javascript" charset="utf-8"></script>

<script
    src="<?= base_url('application/content/cms/javaScripts/elfinder/js/elfinder.min.js') ?>"
    type="text/javascript" charset="utf-8"></script>

<script
    src="<?= base_url('application/content/cms/javaScripts/elfinder/js/i18n/elfinder.ru.js') ?>"
    type="text/javascript" charset="utf-8"></script>


<?php
echo link_tag(base_url('application/content/cms/jquery-ui-1.8.24.custom.css'));
echo link_tag(base_url('application/content/cms/javaScripts/elrte/css/elrte.full.css'));
echo link_tag(base_url('application/content/cms/javaScripts/elfinder/css/elfinder.min.css'));
?>

<script type="text/javascript" charset="utf-8">
	<?php require_once 'application/views/cms/page/elfinder_init.php'?>

    $(function () {

        $('.tab-pane').show();

        $('#elFinder a').hover(
            function () {
                $('#elFinder a').animate({
                    'background-position': '0 -45px'
                }, 300);
            },
            function () {
                $('#elFinder a').delay(400).animate({
                    'background-position': '0 0'
                }, 300);
            }
        );

		elRTE.prototype.options.panels.custom_panel = [
			'copy', 'cut', 'pasteformattext', 'removeformat', 'docstructure',
			'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript',
			'forecolor', 'hilitecolor',
			'outdent', 'indent',
			'justifyleft', 'justifycenter', 'justifyright', 'justifyfull',
			'formatblock', 'fontsize', 'fontname',
			'insertorderedlist', 'insertunorderedlist',
			'horizontalrule', 'blockquote', 'div', 'stopfloat', 'nbsp',
			'link', 'unlink',
			'image',
			'about'
			
		];
		elRTE.prototype.options.toolbars.custom_panel = ['custom_panel'];
		
        $('#elFinder a').delay(300).animate({'background-position': '0 0'}, 300);
        var dialog;
        var opts = {
			toolbar: 'custom_panel',
            absoluteURLs: false,
            doctype: '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">',
            cssClass: 'el-rte',
            lang: 'ru',
            height: 300,
            cssfiles: ['<?= base_url('application/content/cms/javaScripts/elrte/css/elrte.min.css')?>'],
            linklistroute: '<?= base_url('cms/menu/list_route')?>',
            fmAllow: true,
            fmOpen: function (callback) {
                if (!dialog) {
                    dialog = $('<div />').dialogelfinder({
                        url: '<?= base_url('application/libs/elfinder/php/connector.php')?>',
                        commandsOptions: {
                            getfile: {
                                oncomplete: 'close'
                            }
						},
						defaultView: elfinder_opt.defaultView,
						useBrowserHistory: elfinder_opt.useBrowserHistory,
						width: elfinder_opt.width,
						lang : elfinder_opt.lang,
						contextmenu : elfinder_opt.contextmenu,
                        uiOptions : elfinder_opt.uiOptions,
                        getFileCallback: function(file) {
                            file_path =  file.hash.replace(/^[a-z0-9]+_/, '');
                            callback('<?= base_url()?>' + 'file_content'+ '/'+ file_path);
                            dialog=null;
                        }
                    });
                } else {
                    dialog.dialogelfinder('open');
                }
            }
        };

        <?php foreach($this->data['langs'] as $lang): ?>
            $('<?="#data\\\\[$lang->id\\\\]\\\\[content\\\\]"?>').elrte(opts);
        <?php endforeach?>
        $('.tab-pane').removeAttr('style');

        $("#myTab a").click(function (e) {
            e.preventDefault();
            $(this).tab("show");
        });

        $("#myTab a:first").tab("show");
    });
</script>
