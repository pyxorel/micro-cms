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

        $('a.show_rules').click(function () {
            var id = $(this).attr('id_field');
            if (!$('#container_rule_' + id).is(':visible'))
                $('#container_rule_' + id).show();
            else
                $('#container_rule_' + id).hide();
            return false;
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
            <label class="checkbox"> <?= form_checkbox('fields[]', $item->id, FALSE, "id=\"ids\""); ?>
                <?= $item->loc_name . " ($item->type)" ?>
                <?= anchor('#', 'Правила валидации', "class=\"show_rules\" id_field = \"$item->id\""); ?>
            </label>
            <div class="control-group well" style="margin-left: 20px; margin-bottom: 5px;display: none; padding: 10px;" id="container_rule_<?= $item->id ?>">
                <?php switch ($item->type):
                    case 'string': ?>
                        <label class="checkbox">
                            <?= form_checkbox("rules[{$item->id}][required]", 1, FALSE, "id=\"ids\""); ?>Обязательно для заполнения?</label>
                        <div class="controls form-inline" style="margin: 0px">
                            <label class="control-label" style="width: 180px;" for="<?= "rules[{$item->id}][min]" ?>">Минимальная длина</label>
                            <?= form_input("rules[{$item->id}][min]", NULL, "class=\"input-small\" id=\"rules[{$item->id}][min]\" style=\"float:left;\""); ?>
                            <label class="control-label" style="width: 120px; margin-left: 10px;" for="<?= "rules[{$item->id}][max]" ?>">Максимальная</label>
                            <?= form_input("rules[{$item->id}][max]", NULL, "class=\"input-small\" id=\"rules[{$item->id}][max]\""); ?>
                        </div>
                        <?php break; ?>
                    <?php case 'int': ?>
                        <label class="checkbox">
                            <?= form_checkbox("rules[{$item->id}][required]", 1, FALSE, "id=\"ids\""); ?>Обязательно для заполнения?</label>
                        <div class="controls form-inline" style="margin: 0px">
                            <label class="control-label" style="width: 100px;" for="<?= "rules[{$item->id}][min]" ?>">Минимум</label>
                            <?= form_input("rules[{$item->id}][min]", NULL, "class=\"input-small\" id=\"rules[{$item->id}][min]\" style=\"float:left;\""); ?>
                            <label class="control-label" style="width: 100px; margin-left: 10px;" for="<?= "rules[{$item->id}][max]" ?>">Максимум</label>
                            <?= form_input("rules[{$item->id}][max]", NULL, "class=\"input-small\" id=\"rules[{$item->id}][max]\""); ?>
                        </div>
                        <?php break; ?>
                    <?php case 'decimal': ?>
                        <label class="checkbox">
                            <?= form_checkbox("rules[{$item->id}][required]", 1, FALSE, "id=\"ids\""); ?>Обязательно для заполнения?</label>
                        <div class="controls form-inline" style="margin: 0px">
                            <label class="control-label" style="width: 100px;" for="<?= "rules[{$item->id}][min]" ?>">Минимум</label>
                            <?= form_input("rules[{$item->id}][min]", NULL, "class=\"input-small\" id=\"rules[{$item->id}][min]\" style=\"float:left;\""); ?>
                            <label class="control-label" style="width: 100px; margin-left: 10px;" for="<?= "rules[{$item->id}][max]" ?>">Максимум</label>
                            <?= form_input("rules[{$item->id}][max]", NULL, "class=\"input-small\" id=\"rules[{$item->id}][max]\""); ?>
                        </div>
                        <?php break; ?>
                    <?php case 'multi-string': ?>
                        <label class="checkbox">
                            <?= form_checkbox("rules[{$item->id}][required]", 1, FALSE, "id=\"ids\""); ?>Обязательно для заполнения?</label>
                        <div class="controls form-inline" style="margin: 0px">
                            <label class="control-label" style="width: 180px;" for="<?= "rules[{$item->id}][min]" ?>">Минимальная длина</label>
                            <?= form_input("rules[{$item->id}][min]", NULL, "class=\"input-small\" id=\"rules[{$item->id}][min]\" style=\"float:left;\""); ?>
                            <label class="control-label" style="width: 120px; margin-left: 10px;" for="<?= "rules[{$item->id}][max]" ?>">Максимумальная </label>
                            <?= form_input("rules[{$item->id}][max]", NULL, "class=\"input-small\" id=\"rules[{$item->id}][max]\""); ?>
                        </div>
                        <?php break; ?>
                    <?php case 'date': ?>
                        <label class="checkbox"><?= form_checkbox("rules[{$item->id}][required]", 1, FALSE, "id=\"ids\""); ?>
                            Обязательно для заполнения?</label>
                        <?php break; ?>
                    <?php case 'bool': ?>
                        <label class="checkbox"><?= form_checkbox("rules[{$item->id}][required]", 1, FALSE, "id=\"ids\""); ?>
                            Обязательно для заполнения?</label>
                        <?php break; ?>
                    <?php endswitch; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="pull-right">
        <input type="submit" value="Сохранить и выйти" id="save" name="save" class="btn btn-primary"/>
        <?= anchor('cms/business_obj', 'Отмена', "class=\"btn\""); ?>
    </div>

</fieldset>
<?= form_close(); ?>

<?php include 'application/views/cms/footer.php' ?>
</body>
