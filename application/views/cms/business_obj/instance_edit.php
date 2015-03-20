<body>
<script src="<?= base_url('application/content/cms/javaScripts/elfinder/js/elfinder.min.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/javaScripts/elfinder/js/i18n/elfinder.ru.js') ?>" type="text/javascript" charset="utf-8"></script>

<?= link_tag(base_url('application/content/cms/jquery-ui.css')); ?>
<?= link_tag(base_url('application/content/cms/javaScripts/elfinder/css/elfinder.min.css')); ?>

<script>
    $(function () {

        $(".date_time").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true},
            $.datepicker.regional['ru']);

        $('#static').on('click', 'a.rm', function () {
            $(this).parent().remove();
            return false;
        });

        <?php require_once 'application/views/cms/page/elfinder_init.php'?>

        $(".add_file").click(function () {
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
                    $('#container_file_' + input_id).append('<div style="display: inline;"><a href="#" class="rm"><span class="icon-remove" style="margin-left: 10px;"></span></a> ' + file_path + '</div>');
                }
            });
            return false;
        });

        $("#form").validate({
            submitHandler: function (form) {
                var c = $('div').filter(function () {
                    return this.id.match(/container_file_.+/);
                });
                $.each(c, function (key, value) {
                    var input_id = $(value).attr('input_id');
                    if (input_id != 'undefined') {
                        $(value).children().each(function () {
                            var v = $('#fields\\[' + input_id + '\\]').val();
                            $('#fields\\[' + input_id + '\\]').val(v + $(this).text().trim() + ',');
                        });
                    }
                });
                form.submit();
            }
        });

        $('.check').change(function () {
            if ($(this).is(':checked')) $(this).next().val('1');
            else $(this).next().val('0');
        });

        $("#add_obj_dialog").dialog({
            autoOpen: false,
            modal: true,
            resizable: false,
            height: 490,
            width: 540,
            buttons: {
                "Применить": function () {
                    var id = $("#add_obj_dialog").attr('id_input');
                    $('#container_' + id).children().remove();
                    $('#container_' + id).append($('#add_obj_dialog').children().clone());
                    $(this).dialog("close");
                },
                "Закрыть": function () {
                    $(this).dialog("close");
                }
            }
        });

        var arr_objs = [];

        <?php foreach ($obj->fields as $k => $item): ?>
        <?php if($item['type']=='link'):?>
        arr_objs[<?=$item['id']?>] = $.parseJSON('<?=$item['value']?>');
        <?php endif;?>

        <?php endforeach; ?>

        $(".add_obj").click(function () {
            var id = $(this).attr('id');
            $("#add_obj_dialog").load("<?= base_url('cms/business_obj/get_part_instances')?>/" + $(this).attr('class_name') + '/' + $(this).attr('id'), function () {
                $("#add_obj_dialog").attr('id_input', $(".add_obj").attr('id'));
                $('#add_obj_dialog').dialog('option', 'title', 'Добавление объектов: ' + $(this).attr('class_name'));

                $('#add_obj_dialog #container_objs_' + id + ' :input').each(function () {
                    if ($.inArray($(this).attr('id_input'), arr_objs[id]) > -1) {
                        $(this).prop('checked', true);
                    }
                });


                $("#add_obj_dialog").dialog("open");
            });
            return false;
        });

    });
</script>

<?php include_once 'application/views/cms/menu.php' ?>
<div id="add_obj_dialog" id_input="" title=""></div>
<?php
echo form_open_multipart('cms/business_obj/edit_instance', "id=\"form\" class=\"form-horizontal well\"");
echo form_hidden('id', isset($obj) ? $obj->id : set_value('id'));
echo form_hidden('s_class', isset($s_class) ? $s_class : set_value('s_class'));
?>

<legend>Редактирование объекта <?= "($obj->class_name)" ?></legend>
<?= validation_errors('<div class="control-group"><span class="error">', '</span></div>'); ?>
<fieldset id="static">
    <div class="control-group">Заполните необходимые поля:</div>
    <?php foreach ($obj->fields as $k => $item):
        $select = explode(',', $item['extra']);

        $assoc_select = [];
        foreach ($select as $s) {
            $assoc_select[$s] = $s;
        }

        $id = "fields[{$item['id']}]";
        ?>
        <div class="control-group" style="margin-left: 10px;">
            <label style="width: 250px;" class="control-label" for="<?= $id ?>"><?= $item['field_loc_name'] ?></label>
            <?php switch ($item['type']):
                case 'select': ?>
                    <?= form_dropdown("fields[{$item['id']}]", $assoc_select, $item['value'], "id=\"$id\" class=\"input-large\""); ?>
                    <?php break; ?>
                <?php case 'string': ?>
                    <?= form_input("fields[{$item['id']}]", $item['value'], "id=\"$id\" class=\"input-xxlarge\""); ?>
                    <?php break; ?>
                <?php case
                'multi-string': ?>
                    <?= form_textarea(['name' => "fields[{$item['id']}]", 'cols' => 3, 'rows' => 3], $item['value'], "id=\"$id\" class=\"input-xxlarge\""); ?>
                    <?php break; ?>
                <?php case 'int': ?>
                    <?= form_input("fields[{$item['id']}]", $item['value'], "id=\"$id\" class=\"input-small\""); ?>
                    <?php break; ?>
                <?php case
                'decimal': ?>
                    <?= form_input("fields[{$item['id']}]", $item['value'], "id=\"$id\" class=\"input-small\""); ?>
                    <?php break; ?>
                <?php case 'file': ?>
                    <?php $files = json_decode($item['value']); ?>
                    <?= form_input(['name' => "files[{$item['id']}]", 'type' => 'hidden'], set_value("fields[{$item['id']}]"), "id=\"fields[{$item['id']}]\" class=\"input-medium\""); ?>
                    <?= anchor('#', '<i class="icon-plus-sign"></i> Выбрать', "name=\"add\" id=\"add_fields_{$item['id']}\" input_id=\"{$item['id']}\" class=\"btn add_file\""); ?>
                    <div id="container_file_<?= $item['id'] ?>" input_id="<?= $item['id'] ?>" style="display: inline;">
                        <?php if (!empty($files)) foreach ($files as $f): ?>
                            <?php if (!empty($f)): ?>
                                <div style="display: inline;">
                                    <a href="#" class="rm" title="удалить"><span class="icon-remove" style="margin-left: 10px;"></span></a> <?= base64_decode($f) ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php break; ?>
                <?php case 'img': ?>
                    <?php $files = json_decode($item['value']); ?>
                    <?= form_input(['name' => "files[{$item['id']}]", 'type' => 'hidden'], set_value("fields[{$item['id']}]"), "id=\"fields[{$item['id']}]\" class=\"input-medium\""); ?>
                    <?= anchor('#', '<i class="icon-plus-sign"></i> Выбрать', "name=\"add\" id=\"add_fields_{$item['id']}\" input_id=\"{$item['id']}\" class=\"btn add_file\""); ?>
                    <div id="container_file_<?= $item['id'] ?>" input_id="<?= $item['id'] ?>" style="display: inline;">
                        <?php if (!empty($files)) foreach ($files as $f): ?>
                            <?php if (!empty($f)): ?>
                                <div style="display: inline;">
                                    <a href="#" class="rm" title="удалить"><span class="icon-remove" style="margin-left: 10px;"></span></a> <?= base64_decode($f) ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php break; ?>
                <?php case 'bool': ?>
                    <?= form_checkbox(['name' => "check[{$item['id']}]"], 1, $item['value'], "id=\"check[{$item['id']}]\" class=\"check\""); ?>
                    <?= form_input(['name' => "fields[{$item['id']}]", 'type' => 'hidden'], set_value("fields[{$item['id']}]"), "id=\"fields[{$item['id']}]\""); ?>
                    <?php break; ?>
                <?php case 'date': ?>
                    <?= form_input(['name' => "fields[{$item['id']}]"], $item['value'], "id=\"fields[{$item['id']}]\" class=\"input-medium date_time\""); ?>
                    <?php break; ?>
                <?php case 'link': ?>
                    <?= form_checkbox(['name' => "fields[{$item['id']}][]"], 'fake', TRUE, 'style=display:none;'); ?>
                    <?= anchor('#', "<i class=\"icon-plus-sign\"></i> Выбрать ({$item['field_loc_name']})", "class_name=\"{$item['extra']}\" id=\"{$item['id']}\" class=\"btn add_obj\""); ?>
                    <div id="container_<?= $item['id'] ?>" style="display: none;">
                        <?php foreach (json_decode($item['value']) as $i): ?>
                            <?= form_checkbox("fields[{$item['id']}][]", $i, TRUE, NULL) ?>
                        <?php endforeach; ?>
                    </div>
                    <?php break; ?>
                <?php endswitch; ?>
        </div>
    <?php endforeach; ?>
    <div class="pull-right">
        <input type="submit" value="Сохранить и выйти" id="save" name="save" class="btn btn-primary"/>
        <?= anchor('cms/business_obj/instances' . (isset($s_class) ? '/?s_class=' . $s_class : null), 'Отмена', "class=\"btn\""); ?>
    </div>
</fieldset>
<?= form_close(); ?>
<?php include_once 'application/views/cms/footer.php' ?>


</body>





