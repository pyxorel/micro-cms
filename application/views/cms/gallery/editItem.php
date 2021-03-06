<script type="text/javascript">
    $(function () {
        <?php require_once 'application/views/cms/page/elfinder_init.php'?>

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

        $("#formEditItem").validate({
            errorElement: 'span',
            errorClass: 'error-block',
            submitHandler: function (form) {
                $(form).ajaxSubmit(
                    {
                        success: function (data, textStatus) {
                            $('#dialogEditItem').dialog("close");
                            window.location.href = "<?= base_url('cms/gallery/editView')?>/" +<?= $gallery ?>;
                        }
                    });
            },
            rules: {}
        });


        $("#myTab a").click(function (e) {
            e.preventDefault();
            $(this).tab("show");
        });

        $("#myTab a:first").tab("show");
    });

</script>

<?php
echo validation_errors('<span class="error">', '</span>');
echo form_open('cms/gallery/editItem', "id=\"formEditItem\" class=\"form-horizontal\"");
echo form_hidden('gallery', $gallery);
echo form_hidden('id', $item[DEFAULT_LANG_CODE]->id);
?>

<ul class="nav nav-tabs" id="myTab">
    <?php foreach ($langs as $lang): ?>
        <li><a href="#tab<?= $lang->id ?>" data-toggle="tab<?= $lang->id ?>"><?= $lang->text ?></a></li>
    <?php endforeach ?>
</ul>

<div class="tab-content">
    <?php foreach ($langs as $lang) : ?>
        <?php $f_img = sprintf('data[%s][img]', $lang->id) ?>
        <?php $f_desc = sprintf('data[%s][desc]', $lang->id) ?>
        <div class="tab-pane" id="tab<?= $lang->id ?>">
            <div class="control-group">
                <label for="<?= $f_img ?>" class="control-label">Изображение</label>
                <?= form_input($f_img, isset($item) ? base64_decode($item[$lang->code]->img) : set_value($f_img), "data-rule-required=\"true\" data-rule-maxlength=\"255\" class=\"input-xlarge\" id=\"$f_img\""); ?>
                <?= anchor('#', '<i class="icon-plus-sign"></i> Выбрать', "name=\"add\" id=\"add_$f_img\" input_id=\"$f_img\" class=\"btn add_img\""); ?>
            </div>
            <div class="control-group">
                <label class="control-label" for="<?= $f_desc ?>">Описание</label>
                <?= form_textarea(array('cols' => 5, 'rows' => 2, 'id' => $f_desc, 'name' => $f_desc), isset($item) ? $item[$lang->code]->description : set_value($f_desc)); ?>
            </div>
        </div>
    <?php endforeach ?>
</div>

<?= form_close(); ?>


