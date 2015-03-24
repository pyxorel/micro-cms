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

        $("#sortable_to").sortable({
            scroll: true,
            revert: false,
            delay: 0,
            placeholder: "ui-state-highlight-empty",
            stop: function (event, ui) {
                var $input = $(ui.item).find('input');
                $($input).attr('checked', "checked");
                $(ui.item).width('auto');
                $(ui.item).find('a').remove();
                $(ui.item).append('<a href="#" class="icon-remove" style="display: inline;width:auto; float: right; margin-top: 2px;"><span></span></a>');
            }
        }).disableSelection();

        $("li.ui-state-highlight").draggable({
            revert: "invalid",
            cursor: "move",
            connectToSortable: "#sortable_to"
        });

        $('#sortable_to').on('click', 'a.icon-remove', function () {
            var $p = $(this).parent();
            $p.find('input').removeAttr('checked');
            $(this).remove();
            $p.appendTo($('#sortable_from')).css('position', 'relative').draggable({
                revert: false,
                cursor: "move",
                connectToSortable: "#sortable_to"
            });
            return false;
        });



    });
</script>

<?php include_once 'application/views/cms/menu.php' ?>

<?php
echo form_open('cms/business_obj/edit_common_class', "id=\"form\" class=\"form-horizontal well\"");
echo form_hidden('id', isset($obj) ? $obj->id : set_value('id'));
echo form_input(['name' => 'order', 'id' => 'order', 'type' => 'hidden']);
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

    <div class="control-group">
        <label class="control-label" for="loc_name">Поля</label>
        <div class="span6" style="margin: 0">
            <div class="head">
                <div class="head">Используемые поля</div>
            </div>
            <div class="content">
                <ul id="sortable_to">
                    <?php $has = [];
                    foreach ($obj->links as $link):
                        foreach ($fields as $item): ?>
                            <?php if ($link->__field->id == $item->id): ?>
                                <li class="ui-state-highlight"><?= $item->loc_name . " ($item->type)" ?>
                                    <?= form_checkbox('fields[]', $item->id, TRUE, "id=\"ids\" style=\"display:none\""); ?>
                                    <a href="#" class="icon-remove" style="display: inline;width:auto; float: right; margin-top: 2px;"><span></span></a>
                                </li>
                                <?php $has[$item->id] = $item->name;
                                break; ?>
                            <?php endif ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="span6">
            <div class="head">Возможные поля</div>
            <div class="content">
                <ul id="sortable_from">
                    <?php foreach ($fields as $item): ?>
                    <?php if (array_key_exists($item->id, $has) === FALSE): ?>
                            <li class="ui-state-highlight"><?= $item->loc_name . " ($item->type)" ?>
                                <?= form_checkbox('fields[]', $item->id, FALSE, "id=\"ids\" style=\"display:none\""); ?>
                            </li>
                        <?php endif ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="control-group">
        <span class="label label-info">Перетащите нужные поля в левую область</span>
    </div>
    <div class="control-group">
    <div class="pull-right">
        <input type="submit" value="Применить" id="ok" name="ok" class="btn btn-primary"/>
        <input type="submit" value="Сохранить и выйти" id="save" name="save" class="btn btn-primary"/>
        <?= anchor('cms/business_obj', 'Отмена', "class=\"btn\""); ?>
    </div>
    </div>
</fieldset>

<?= form_close(); ?>
<?php include_once 'application/views/cms/footer.php' ?>
</body>






