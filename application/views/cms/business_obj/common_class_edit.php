<body>

<script>
    $(function () {
        $("#form").validate({
            errorElement: 'span',
            errorClass: 'error',
            rules: {
                name: {
                    required: true,
                    maxlength: 50,
                    regex: /^[0-9a-z-_]+$/
                }
            }
        });


        $('.up').click(function () {
            var parent = $(this).parent();
            parent.insertBefore(parent.prev());
        });

        $('.down').click(function () {
            var parent = $(this).parent();
            parent.insertAfter(parent.next());
        });
    });
</script>

<?php include_once 'application/views/cms/menu.php' ?>

<?php
echo form_open('cms/business_obj/edit_common_class', "id=\"form\" class=\"form-horizontal well\"");
echo form_hidden('id', isset($obj) ? $obj->id : set_value('id'));
echo form_input(['name'=>'order', 'id'=>'order', 'type'=>'hidden']);
?>

<legend>Редактирование класса</legend>

<?= validation_errors('<div class="control-group"><span class="error">', '</span></div>'); ?>

<fieldset>

    <div class="control-group">
        <label class="control-label" for="name">Служебное название (уникально)</label>
        <?= form_input('name', isset($obj) ? $obj->name : set_value('name'), "class=\"input-xxlarge\" id=\"name\""); ?>
    </div>
    <div class="control-group">
        <label class="control-label" for="loc_name">Название</label>
        <?= form_input('loc_name', isset($obj) ? $obj->loc_name : set_value('loc_name'), 'class="input-xxlarge" id="loc_name"'); ?>
    </div>

    <div class="control-group">Выберите необходимые поля:</div>
    <div class="control-group" style="margin-left: 10px;" id="container">
        <?php $has = [];
        foreach ($obj->links as $link):
            foreach ($fields as $item): ?>
                <?php if ($link->__field->id == $item->id): ?>
                    <div id_input="<?= $item->id ?>">
                        <a href="#" class="up" id_input="<?= $item->id ?>" title="Вверх"><span class="icon-arrow-up" style="display: inline-block;"></span></a>
                        <a href="#" class="down" id_input="<?= $item->id ?>" title="Вниз"><span style="display: inline-block;" class="icon-arrow-down"></span></a>
                        <label class="checkbox"  style="display: inline-block;"><?= form_checkbox("fields[]", $item->id, TRUE, "id=\"ids\""); ?><?= $item->name . " ($item->type)" ?></label>
                    </div>
                    <?php $has[$item->id] = $item->name; break; ?>
                <?php endif ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <?php foreach ($fields as $item):?>
                <?php if (array_key_exists($item->id, $has)===FALSE): ?>
            <div id_input="<?= $item->id ?>"><a href="#" class="up" id_input="<?= $item->id ?>"><span class="icon-arrow-up" style="display: inline-block;"></span></a>
                <a href="#" class="down" id_input="<?= $item->id ?>"><span style="display: inline-block;" class="icon-arrow-down"></span></a>
                <label class="checkbox"  style="display: inline-block;"><?= form_checkbox("fields[]", $item->id, FALSE, "id=\"ids\""); ?><?= $item->name  . " ($item->type)"; ?></label>
            </div>
                <?php endif ?>
        <?php endforeach;?>
    </div>

    <div class="pull-right">
        <input type="submit" value="Применить" id="ok" name="ok"
               class="btn btn-primary"/> <input type="submit"
                                                value="Сохранить и выйти" id="save" name="save"
                                                class="btn btn-primary"/>
        <?= anchor('cms/business_obj', 'Отмена', "class=\"btn\""); ?>
    </div>
</fieldset>

<?= form_close(); ?>
<?php include_once 'application/views/cms/footer.php' ?>
</body>






