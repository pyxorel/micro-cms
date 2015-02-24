
<body>
<?= link_tag('application/content/cms/codemirror/codemirror.css') ?>
<?= link_tag('application/content/cms/codemirror/addon/scroll/simplescrollbars.css') ?>
<script src="<?= base_url('application/content/cms/codemirror/codemirror.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/codemirror/mode/smarty/smarty.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/codemirror/mode/smartymixed/smartymixed.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/codemirror/mode/xml/xml.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/codemirror/mode/htmlmixed/htmlmixed.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/codemirror/mode/css/css.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/codemirror/mode/javascript/javascript.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/codemirror/addon/selection/active-line.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/codemirror/addon/scroll/simplescrollbars.js') ?>" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">

    $(function () {

            $(window).keypress(function(event) {
                if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)) return true;
                $('#ok').click();
                event.preventDefault();
                return false;
            });

            var editor = CodeMirror.fromTextArea(document.getElementById("text"), {
                mode: "smartymixed",
                scrollbarStyle: "simple",
                lineNumbers: true,
                styleActiveLine: true,
                matchBrackets: false,
                enterMode: 'keep',
                indentWithTabs: false,
                indentUnit: 1,
                tabMode: 'classic',
                addModeClass: true
            });

			$.validator.addMethod("regex", function(value, element, regexpr) {          
				return regexpr.test(value);
			}, "Допустимые символы: 0-9a-z-_"); 
			
            $("#form").validate({
                errorElement: 'span',
                errorClass: 'error',
                rules: {
                    name: {
                        required: true,
                        maxlength: 50,
						regex: /^[0-9a-z-_]+$/
                    },
                    text: {
                        required: true
                    }
                }
            });
        });
</script>

<?php include_once 'application/views/cms/menu.php' ?>

<?= form_open('cms/template/edit', "id=\"form\" class=\"form-horizontal well\""); ?>
<?php $key = isset($template) ? array_keys($template)[0] : set_value('name');?>
<?= form_hidden('old_name', str_replace('.tpl', '', $key)); ?>
<legend>Редактирование шаблона</legend>

<?= validation_errors('<div class="control-group"><span class="error">', '</span></div>'); ?>

<fieldset>

    <div class="control-group">
        <label class="control-label" for="name">Название</label>
        <?= form_input('name', str_replace('.tpl', '', $key), 'class="input-xxlarge" id="name"'); ?>
    </div>

    <div class="control-group">
        <label class="control-label" for="text">Текст шаблона</label>
        <textarea name="text" cols="15" rows="20" id="text" class="" ><?=isset($template) ? $template[$key] : set_value('text')?></textarea>
    </div>

    <div class="pull-right">
        <input type="submit" value="Применить (Ctrl+S)" id="ok" name="ok"
               class="btn btn-primary"/> <input type="submit"
                                                value="Сохранить и выйти" id="save" name="save"
                                                class="btn btn-primary"/>
        <?= anchor('cms/template', 'Отмена', "class=\"btn\""); ?>
    </div>

</fieldset>
<?= form_close(); ?>

<?php include 'application/views/cms/footer.php' ?>
</body>
