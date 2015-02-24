
<body>
<?php include_once 'application/views/cms/page/wysiwyg.php' ?>
<script type="text/javascript" charset="utf-8">
    $(function () {

        $(".add_img").click(function () {
            var link = this;
            $('<div />').dialogelfinder({
                url: '<?= base_url('application/libs/elfinder/php/connector.php')?>',
                defaultView: elfinder_opt.defaultView,
				useBrowserHistory: elfinder_opt.useBrowserHistory,
				width: elfinder_opt.width,
				lang : elfinder_opt.lang,
				contextmenu : elfinder_opt.contextmenu,
                uiOptions : elfinder_opt.uiOptions,
                commandsOptions: {
                    getfile: {
                        oncomplete: 'close'
                    }
                },
                getFileCallback: function (file) {
					file_path =  file.url.replace('<?= base_url() ?>', '');
                    var input_id = $(link).attr('input_id');
                    input_id = input_id.replace(/([\[|\]])/g, '\\$1');
                    $('#'+input_id).val(file_path);
                }
            });
            return false;
        });
		
        $(".add_img_tmb").click(function () {
            var link = this;
            $('<div />').dialogelfinder({
                url: '<?= base_url('application/libs/elfinder/php/connector.php')?>',
                defaultView: elfinder_opt.defaultView,
				useBrowserHistory: elfinder_opt.useBrowserHistory,
				width: elfinder_opt.width,
				lang : elfinder_opt.lang,
				contextmenu : elfinder_opt.contextmenu,
                uiOptions : elfinder_opt.uiOptions,
                commandsOptions: {
                    getfile: {
                        oncomplete: 'close'
                    }
                },
                getFileCallback: function (file) {
                    file_path =  file.url.replace('<?= base_url() ?>', '');
                    var input_id = $(link).attr('input_id');
                    input_id = input_id.replace(/([\[|\]])/g, '\\$1');
                    $('#'+input_id).val(file_path);
                }
            });
            return false;
        });

       $('#form').validate();

    });

</script>

<?php include_once 'application/views/cms/menu.php' ?>

<div id="elFinder"></div>

<?= form_open('cms/page/create', "id=\"form\" class=\"form-horizontal well\""); ?>
<legend>Создание страницы</legend>

<?= validation_errors('<div class="control-group"><span class="error">', '</span></div>'); ?>

<fieldset>

    <div class="control-group">
        <label class="control-label" for="name">Название</label>
        <?= form_input('name', set_value('name'), 'class="input-xxlarge" id="name" data-rule-required="true" data-rule-maxlength="255"'); ?>
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

                <div class="control-group">
                    <label class="control-label" for="<?=$f_img?>">Изображение</label>

                    <div class="input-append">
                        <?= form_input($f_img, set_value($f_img), "id=\"$f_img\" class=\"input-xxlarge\" data-rule-maxlength=\"255\""); ?>
                        <?= anchor('#', '<i class="icon-plus-sign"></i> Выбрать', "name=\"add\" id=\"add_$f_img\" input_id=\"$f_img\" class=\"btn add_img\"");?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="<?=$f_img_tmb?>">Миниатюра</label>

                    <div class="input-append">
                        <?= form_input($f_img_tmb, set_value($f_img_tmb), "id=\"$f_img_tmb\" class=\"input-xxlarge\" data-rule-maxlength=\"255\""); ?>
                        <?= anchor('#', '<i class="icon-plus-sign"></i> Выбрать', "name=\"add\" id=\"add_$f_img_tmb\" input_id=\"$f_img_tmb\" class=\"btn add_img_tmb\"");?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="<?=$f_head?>">Заголовок «Title»</label>
                    <input type="text" name="<?=$f_head?>" value="<?=set_value($f_head)?>" class="input-xxlarge" data-rule-required="true" data-rule-maxlength="255" id="<?=$f_head?>"/>
                </div>

                <div class="control-group">
                    <label class="control-label" for="<?=$f_s_content?>">Краткое описание</label>
                    <?= form_textarea(array('cols' => 7, 'rows' => 2, 'id' => $f_s_content, 'name' => $f_s_content, 'class' => 'input-xxlarg', 'value' => set_value($f_s_content)), NULL, 'data-rule-maxlength="1024"'); ?>
                </div>

                <div class="control-group">
                    <div id="wisiwig">
                        <label class="control-label" for="<?=$f_content?>" style="font-size: 15px;">Содержание</label>
                        <?= form_textarea(array('cols' => 20, 'rows' => 5, 'id' => $f_content, 'name' => $f_content, 'value' => set_value($f_content)), NULL, 'data-rule-required="true" data-rule-maxlength="65535"'); ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="<?=$f_description?>">Мета-тег «description»</label>
                    <?= form_textarea(array('cols' => 5, 'rows' => 2, 'id' => $f_description, 'name' => $f_description, 'value' => set_value($f_description)), NULL, 'data-rule-maxlength="255"'); ?>
                </div>

                <div class="control-group">
                    <label class="control-label" for="<?=$f_keywords?>">Мета-тег «keywords»</label>
                    <?= form_textarea(array('cols' => 10, 'rows' => 2, 'id' => $f_keywords, 'name' => $f_keywords, 'value' => set_value($f_keywords)), NULL, 'data-rule-maxlength="255"'); ?>
                </div>

            </div>
        <?php endforeach ?>
    </div>

    <div class="control-group">
        <label class="control-label" for="script">Скрипты</label>
        <?= form_textarea(array('cols' => 5, 'rows' => 5, 'id' => 'script', 'name' => 'script', 'value' => set_value('script')), NULL, 'data-rule-maxlength="4096"'); ?>
    </div>


    <div class="controls">
        <label
            class="checkbox"> <?= form_checkbox('service', set_value('service') != null ? set_value('service') : 1, set_value('service')); ?>
            Служебная страница
        </label>
    </div>

    <div class="pull-right">
        <input type="submit" value="Сохранить и выйти" id="save" name="save"
               class="btn btn-primary"/>
        <?= anchor('cms/page', 'Отмена', "class=\"btn\""); ?>
    </div>

</fieldset>
<?= form_close(); ?>

<?php include 'application/views/cms/footer.php' ?>
</body>
