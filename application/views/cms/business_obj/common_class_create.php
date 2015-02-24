
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
                }
            }
        });
    });
</script>

<?php include_once 'application/views/cms/menu.php' ?>

<?= form_open('cms/business_obj/create_common_class', "id=\"form\" class=\"form-horizontal well\""); ?>
<legend>Создание класса</legend>

<?= validation_errors('<div class="control-group"><span class="error">', '</span></div>'); ?>

<fieldset>
    <div class="control-group">
        <label class="control-label" for="name">Служебное название (уникально)</label>
        <?= form_input('name', set_value('name'), 'class="input-xxlarge" id="name"'); ?>
    </div>
    <div class="control-group">
        <label class="control-label" for="loc_name">Название</label>
        <?= form_input('loc_name', set_value('loc_name'), 'class="input-xxlarge" id="loc_name"'); ?>
    </div>

    <div class="control-group">Выберите необходимые поля:</div>
    <div class="control-group" style="margin-left: 10px;">
        <?php foreach ($fields as $item): ?>
            <label
                class="checkbox"> <?= form_checkbox('fields[]', $item->id, FALSE, "id=\"ids\""); ?>
                <?= $item->loc_name . " ($item->type)" ?>
            </label>
        <?php endforeach; ?>
    </div>

    <div class="pull-right">
        <input type="submit" value="Сохранить и выйти" id="save" name="save"
               class="btn btn-primary"/>
        <?= anchor('cms/business_obj', 'Отмена', "class=\"btn\""); ?>
    </div>

</fieldset>
<?= form_close(); ?>

<?php include 'application/views/cms/footer.php' ?>
</body>
