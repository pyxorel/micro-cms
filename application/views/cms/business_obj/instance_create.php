<body>
<script
    src="<?= base_url('application/content/cms/javaScripts/elfinder/js/elfinder.min.js') ?>"
    type="text/javascript" charset="utf-8"></script>

<script
    src="<?= base_url('application/content/cms/javaScripts/elfinder/js/i18n/elfinder.ru.js') ?>"
    type="text/javascript" charset="utf-8"></script>

<?= link_tag(base_url('application/content/cms/jquery-ui-1.8.24.custom.css')); ?>
<?= link_tag(base_url('application/content/cms/javaScripts/elfinder/css/elfinder.min.css')); ?>

<script type="text/javascript" charset="utf-8">
    $(function () {
        <?php require_once 'application/views/cms/page/elfinder_init.php'?>

        $(".date_time").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true},
            $.datepicker.regional['ru']);

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

        $('#static').on('click', 'a.rm', function () {
            $(this).parent().remove();
            return false;
        });

        $("#add_obj_dialog").dialog({
            autoOpen: false,
            modal: true,
            resizable: false,
            height: 490,
            width: 540,
            position: ["center", "center"],
            buttons: {
                "Применить": function () {
                    var name = $("#add_obj_dialog").attr('class_name');
                    $('#container_'+name).append($('#add_obj_dialog').children().clone());
                    $(this).dialog("close");

                },
                "Закрыть": function () {
                    $(this).dialog("close");
                }
            }
        });

        $(".add_obj").click(function () {
            $("#add_obj_dialog").load("<?= base_url('cms/business_obj/get_part_instances')?>/"+$(this).attr('class_name') + '/' + $(this).attr('id'), function(){
                $("#add_obj_dialog").attr('class_name', $(".add_obj").attr('class_name'));
                $('#add_obj_dialog').dialog('option', 'title', 'Добавление объектов: ' + $(this).attr('class_name'));
                $("#add_obj_dialog").dialog("open");
            });

            return false;
        });

    });
</script>
<?php include_once 'application/views/cms/menu.php' ?>
<div id="add_obj_dialog"  class_name ="" title=""></div>
<?= form_open_multipart('cms/business_obj/create_instance', "id=\"form\" class=\"form-horizontal well\""); ?>
<?= form_hidden('id_class', $id_class); ?>
<?= validation_errors('<div class="control-group"><span class="error">', '</span></div>'); ?>

<legend>Создание объекта (<?= $class->name?>)</legend>
<fieldset id="static">
    <div class="control-group">Заполните необходимые поля:</div>
    <?php foreach ($class->links as $item):
        $item->__field->__load();
        $i = $item->__field;
        ?>
        <div class="control-group" style="margin-left: 10px;">
            <label class="control-label" for="name"><?= $i->loc_name ?></label>
            <?php switch ($i->type):
                case 'string': ?>
                    <?= form_input("fields[{$item->id}]", set_value("fields[{$item->id}]"), "id=\"ids\" class=\"input-xxlarge\""); ?>
                    <?php break; ?>
                <?php case
                'multi-string': ?>
                    <?= form_textarea(['name' => "fields[{$item->id}]", 'cols' => 3, 'rows' => 3], set_value("fields[{$item->id}]"), "id=\"ids\" class=\"input-xlarge\""); ?>
                    <?php break; ?>
                <?php case 'int': ?>
                    <?= form_input("fields[{$item->id}]", set_value("fields[{$item->id}]"), "id=\"ids\" class=\"input-small\""); ?>
                    <?php break; ?>
                <?php case
                'decimal': ?>
                    <?= form_input("fields[{$item->id}]", set_value("fields[{$item->id}]"), "id=\"ids\" class=\"input-small\""); ?>
                    <?php break; ?>
                <?php case 'file': ?>
                    <?= form_input(['name' => "files[{$item->id}]", 'type' => 'hidden'], set_value("fields[{$item->id}]"), "id=\"fields[{$item->id}]\" class=\"input-medium\""); ?>
                    <?= anchor('#', '<i class="icon-plus-sign"></i> Выбрать', "name=\"add\" id=\"add_fields_$item->id\" input_id=\"$item->id\" class=\"btn add_file\""); ?>
                    <div id="container_file_<?= $item->id ?>" input_id="<?= $item->id ?>"
                         style="display: inline;"></div>
                    <?php break; ?>
                <?php case 'img': ?>
                    <?= form_input(['name' => "files[{$item->id}]", 'type' => 'hidden'], set_value("fields[{$item->id}]"), "id=\"fields[{$item->id}]\" class=\"input-medium\""); ?>
                    <?= anchor('#', '<i class="icon-plus-sign"></i> Выбрать', "name=\"add\" id=\"add_fields_$item->id\" input_id=\"$item->id\" class=\"btn add_file\""); ?>
                    <div id="container_file_<?= $item->id ?>" input_id="<?= $item->id ?>"
                         style="display: inline;"></div>
                    <?php break; ?>
                <?php case 'bool': ?>
                    <?= form_checkbox(['name' => "check[{$item->id}]"], 1, FALSE, "id=\"check[{$item->id}]\" class=\"check\""); ?>
                    <?= form_input(['name' => "fields[{$item->id}]", 'type' => 'hidden'], set_value("fields[{$item->id}]"), "id=\"fields[{$item->id}]\""); ?>
                    <?php break; ?>
                <?php case 'date': ?>
                    <?= form_input(['name' => "fields[{$item->id}]"], set_value("fields[{$item->id}]"), "id=\"fields[{$item->id}]\" class=\"input-medium date_time\""); ?>
                    <?php break; ?>
                <?php case 'link': ?>
                    <?= anchor('#', "<i class=\"icon-plus-sign\"></i> Выбрать ($i->loc_name)", "class_name=\"$i->extra\" id=\"$item->id\" input_id=\"$item->id\"  class=\"btn add_obj\""); ?>
                    <?= form_checkbox(['name' => "fields[{$item->id}][]"], 'fake', TRUE, 'style=display:none;'); ?>
                    <div id="container_<?= $i->extra?>" style="display: none;">

                     </div>
                    <?php break; ?>
                <?php endswitch; ?>
        </div>
    <?php endforeach; ?>
    </div>

    <div class="pull-right">
        <input type="submit" value="Сохранить и выйти" id="save" name="save"
               class="btn btn-primary"/>
        <?= anchor('cms/business_obj/instances', 'Отмена', "class=\"btn\""); ?>
    </div>

</fieldset>
<?= form_close(); ?>

<?php include 'application/views/cms/footer.php' ?>
</body>
