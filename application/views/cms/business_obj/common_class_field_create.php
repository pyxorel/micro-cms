<body>
<script type="text/javascript" charset="utf-8">
    $(function () {
        $("#form").validate({
            errorElement: 'span',
            errorClass: 'error',
            rules: {
                name: {
                    required: true,
                    maxlength: 50,
                    regex: /^[0-9a-z-_]+$/
                },
                loc_name: {
                    required: true,
                    maxlength: 50
                },
                type: {
                    required: true
                },
                extra: {
                    maxlength: 255
                },
                unit: {
                    maxlength: 25
                }
            }
        });

        $('#type').change(function () {
            if ($(this).val() == 'link') {
                $('#class_container').show();
                $('#extra').val($('#_class option:first-child').val());
            } else if ($(this).val() == 'select') {
                $('#extra_container').show();
                $('#extra').attr('type', 'text');
            }
            else {
                $('#extra_container').hide();
                $('#class_container').hide();
                $('#extra').val('');
                $('#_class option:first-child').attr("selected", "selected");
            }

        });

        $('#_class').change(function () {
            $('#extra').val($(this).val());
        });

    });
</script>

<?php include_once 'application/views/cms/menu.php' ?>

<?= form_open('cms/business_obj/create_common_class_field', "id=\"form\" class=\"form-horizontal well\""); ?>
<legend>Создание поля</legend>

<?= validation_errors('<div class="control-group"><span class="error">', '</span></div>'); ?>

<fieldset>
    <div class="control-group">
        <label class="control-label" for="name">Служебное название (уникально)</label>
        <?= form_input('name', set_value('name'), 'class="input-xlarge" id="name"'); ?>
    </div>
    <div class="control-group">
        <label class="control-label" for="loc_name">Название</label>
        <?= form_input('loc_name', set_value('loc_name'), 'class="input-xlarge" id="loc_name"'); ?>
    </div>

    <div class="control-group">
        <label class="control-label" for="type">Тип</label>
        <?= form_dropdown('type', \Entities\Common_class_field::$TYPES, NULL, 'class="input-medium" id="type"'); ?>
    </div>

    <div class="control-group" style="display: none;" id="class_container">
        <label class="control-label" for="_class">Класс</label>
        <?= form_dropdown('class', $classes, NULL, 'class="input-medium" id="_class"'); ?>
    </div>

    <div class="control-group" id="extra_container" style="display: none;">
        <label class="control-label" for="unit">Значения ( , - разделитель )</label>
        <?= form_input(['name' => 'extra', 'type' => 'text'], set_value('extra'), 'class="" id="extra"'); ?>
    </div>

    <div class="control-group">
        <label class="control-label" for="unit">Единица</label>
        <?= form_input('unit', set_value('unit'), 'class="input-medium" id="unit"'); ?>
    </div>

    <div class="pull-right">
        <input type="submit" value="Сохранить и выйти" id="save" name="save" class="btn btn-primary"/>
        <?= anchor('cms/business_obj/fields', 'Отмена', "class=\"btn\""); ?>
    </div>

</fieldset>
<?= form_close(); ?>

<?php include 'application/views/cms/footer.php' ?>
</body>
