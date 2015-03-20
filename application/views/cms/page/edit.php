<body>

<?php include_once 'application/views/cms/page/wysiwyg.php' ?>

<script>
    $(document).ready(function () {

        function parse_input_id(str) {
            return str.replace(/([\[|\]])/g, '\\$1');
        }

        $(".add_img").click(function () {
            var link = this;
            $('<div />').dialogelfinder({
                url: '<?= base_url('application/libs/elfinder/php/connector.php')?>',
                defaultView: elfinder_opt.defaultView,
                useBrowserHistory: elfinder_opt.useBrowserHistory,
                width: elfinder_opt.width,
                lang: elfinder_opt.lang,
                contextmenu: elfinder_opt.contextmenu,
                uiOptions: elfinder_opt.uiOptions,
                commandsOptions: {
                    getfile: {
                        oncomplete: 'close'
                    }
                },
                getFileCallback: function (file) {
                    file_path = file.url.replace('<?= base_url() ?>', '');
                    var input_id = $(link).attr('input_id');
                    input_id = input_id.replace(/([\[|\]])/g, '\\$1');
                    $('#' + input_id).val(file_path);
                }
            });
            return false;
        });

        $('.show_img').popover({
            trigger: 'manual', content: function () {
                var input_id = parse_input_id($(this).attr('input_id'));
                return "<ul class=\"thumbnails\"><li class=\"span2\"><img src=\"<?= base_url('file_content')?>/" + window.btoa($('#' + input_id).val()) + "\"></img></li></ul>"
            }, html: true
        }).click(function (e) {
            $(this).popover('toggle');
            return false;
        });

        $(".delete_img").click(function () {
            var input_id = parse_input_id($(this).attr('input_id'))
            $('#' + input_id).val('');
            return false;
        });

        $(".add_img_tmb").click(function () {
            var link = this;
            $('<div />').dialogelfinder({
                url: '<?= base_url('application/libs/elfinder/php/connector.php')?>',
                defaultView: elfinder_opt.defaultView,
                useBrowserHistory: elfinder_opt.useBrowserHistory,
                width: elfinder_opt.width,
                lang: elfinder_opt.lang,
                contextmenu: elfinder_opt.contextmenu,
                uiOptions: elfinder_opt.uiOptions,
                commandsOptions: {
                    getfile: {
                        oncomplete: 'close'
                    }
                },
                getFileCallback: function (file) {
                    file_path = file.url.replace('<?= base_url() ?>', '');
                    var input_id = $(link).attr('input_id');
                    input_id = input_id.replace(/([\[|\]])/g, '\\$1');
                    $('#' + input_id).val(file_path);
                }
            });
            return false;
        });

        $('.show_img_tmb').popover({
            trigger: 'manual', content: function () {
                var input_id = parse_input_id($(this).attr('input_id'));
                return "<ul class=\"thumbnails\"><li class=\"span2\"><img src=\"<?= base_url('file_content')?>/" + window.btoa($('#' + input_id).val()) + "\"></img></li></ul>"
            }, html: true
        }).click(function (e) {
            $(this).popover('toggle');
            return false;
        });

        $(".delete_img_tmb").click(function () {
            var input_id = parse_input_id($(this).attr('input_id'))
            $('#' + input_id).val('');
            return false;
        });

        $('#form').validate({ignore: ""});

    });
</script>

<?php include_once 'application/views/cms/menu.php' ?>

<div id="elFinder"></div>

<?php
echo form_open('cms/page/edit', "id=\"form\" class=\"form-horizontal well\"");
echo form_hidden('id', isset($page) ? $page[DEFAULT_LANG_CODE]->id : set_value('id'));
?>

<legend>Редактирование страницы</legend>

<?= validation_errors('<div class="control-group"><span class="error">', '</span></div>'); ?>

<fieldset>

    <div class="control-group">
        <label class="control-label" for="name">Название (уникально)</label>
        <?= form_input('name', isset($page) ? $page[DEFAULT_LANG_CODE]->name : set_value('name'), "class=\"input-xxlarge\" id=\"name\""); ?>
    </div>

    <ul class="nav nav-tabs" id="myTab">
        <?php foreach ($langs as $lang): ?>
            <li><a href="#tab<?= $lang->id ?>" data-toggle="tab<?= $lang->id ?>"><?= $lang->text ?></a></li>
        <?php endforeach ?>
    </ul>

    <div class="tab-content">
        <?php foreach ($langs as $lang) : ?>
            <?php $f_content = sprintf('data[%s][content]', $lang->id) ?>
            <?php $f_s_content = sprintf('data[%s][scontent]', $lang->id) ?>
            <?php $f_description = sprintf('data[%s][description]', $lang->id) ?>
            <?php $f_head = sprintf('data[%s][head]', $lang->id) ?>
            <?php $f_keywords = sprintf('data[%s][keywords]', $lang->id) ?>
            <?php $f_img = sprintf('data[%s][img]', $lang->id) ?>
            <?php $f_img_tmb = sprintf('data[%s][img_tmb]', $lang->id) ?>

            <div class="tab-pane" id="tab<?= $lang->id ?>">
                <?php if (array_key_exists($lang->code, $page)) : ?>


                    <div class="control-group">
                        <label class="control-label" for="img">Изображение <?= anchor('#', '<i class="icon-eye-open"></i>', "name=\"show\" id=\"show_$f_img\" input_id=\"$f_img\" class=\"btn btn-mini show_img\""); ?>
                        </label>

                        <div class="input-append">
                            <input name="<?= $f_img ?>" type="text" value="<?= isset($page) ? base64_decode($page[$lang->code]->img) : set_value($f_img) ?>" id="<?= $f_img ?>" class="input-xxlarge"/>
                            <?= anchor('#', '<i class="icon-remove-sign"></i>', "name=\"delete\" id=\"delete_$f_img\" input_id=\"$f_img\" class=\"btn delete_img\""); ?>
                            <?= anchor('#', '<i class="icon-plus-sign"></i>', "name=\"add\" id=\"add_$f_img\" input_id=\"$f_img\" class=\"btn add_img\""); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="img">Миниатюра <?= anchor('#', '<i class="icon-eye-open"></i>', "name=\"show\" id=\"show_$f_img_tmb\" input_id=\"$f_img_tmb\" class=\"btn btn-mini show_img_tmb\""); ?>
                        </label>

                        <div class="input-append">
                            <input name="<?= $f_img_tmb ?>" type="text" value="<?= isset($page) ? base64_decode($page[$lang->code]->img_tmb) : set_value($f_img_tmb) ?>" id="<?= $f_img_tmb ?>" class="input-xxlarge"/>
                            <?= anchor('#', '<i class="icon-remove-sign"></i>', "name=\"delete\" id=\"delete_$f_img\" input_id=\"$f_img_tmb\" class=\"btn delete_img_tmb\""); ?>
                            <?= anchor('#', '<i class="icon-plus-sign"></i>', "name=\"add\" id=\"add_$f_img_tmb\" input_id=\"$f_img_tmb\" class=\"btn add_img_tmb\""); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="<?= $f_head ?>">Заголовок «Title»</label>
                        <input type="text" name="<?= $f_head ?>" value="<?= isset($page) ? $page[$lang->code]->head : set_value($f_head) ?>" class="input-xxlarge" data-rule-required="true" data-rule-maxlength="255" id="<?= $f_head ?>"/>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="<?= $f_s_content ?>">Краткое описание</label>
                        <?= form_textarea(array('cols' => 7, 'rows' => 2, 'id' => $f_s_content, 'name' => $f_s_content, 'value' => isset($page) ? $page[$lang->code]->s_content : set_value($f_s_content)), NULL, 'data-rule-maxlength="1024"'); ?>
                    </div>

                    <div class="control-group">
                        <div id="wisiwig">
                            <label class="control-label" for="<?= $f_content ?>" style="font-size: 15px;">Содержание</label>

                            <?= form_textarea(array('id' => $f_content, 'name' => $f_content, 'value' => isset($page) ? $page[$lang->code]->content : set_value($f_content)), NULL, 'data-rule-required="true" data-rule-maxlength="65535"'); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="<?= $f_description ?>">Мета-тег «description»</label>
                        <?= form_textarea(array('cols' => 5, 'rows' => 2, 'id' => $f_description, 'name' => $f_description, 'value' => isset($page) ? $page[$lang->code]->meta_description : set_value($f_description)), NULL, 'data-rule-maxlength="255"'); ?>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="<?= $f_keywords ?>">Мета-тег «keywords»</label>
                        <?= form_textarea(array('cols' => 5, 'rows' => 2, 'id' => $f_keywords, 'name' => $f_keywords, 'value' => isset($page) ? $page[$lang->code]->meta_keywords : set_value($f_keywords)), NULL, 'data-rule-maxlength="255"'); ?>
                    </div>
                <?php else: ?>

                <?php endif ?>
            </div>
        <?php endforeach ?>
    </div>

    <div class="control-group">
        <label class="control-label" for="script">Скрипты</label>
        <?= form_textarea(array('cols' => 10, 'rows' => 5, 'id' => 'script', 'name' => 'script', 'value' => isset($page) ? $page[DEFAULT_LANG_CODE]->script : set_value('script'))); ?>
    </div>

    <div class="controls">
        <label class="checkbox"> <?= form_checkbox('service', 1, (isset($page) && $page[DEFAULT_LANG_CODE]->is_service == 1) ? true : set_value('service')); ?>
            Служебная страница </label>

    </div>

    <div class="pull-right">
        <input type="submit" value="Применить" id="ok" name="ok" class="btn btn-primary"/>
        <input type="submit" value="Сохранить и выйти" id="save" name="save" class="btn btn-primary"/>
        <?= anchor('cms/page', 'Отмена', "class=\"btn\""); ?>
    </div>
</fieldset>

<?= form_close(); ?>

<?php include_once 'application/views/cms/footer.php' ?>
</body>





