<body>

<script>
    $(document).ready(function () {

        $("#myTab a").click(function (e) {
            e.preventDefault();
            $(this).tab("show");
        });

        $(function () {
            $("#myTab a:first").tab("show");
        });

        $('#form').validate({ignore: ""});

    });
</script>

<?php require 'application/views/cms/menu.php'; ?>


<?php

echo form_open('cms/resource/edit', "id=\"form\" class=\"form-horizontal well\"");
echo form_hidden('id', isset($resource) ? $resource[DEFAULT_LANG_CODE]->id : set_value('id'));
?>
<?= validation_errors('<span class="error">', '</span>'); ?>
<legend>Редактирование ресурса</legend>
<fieldset>

    <div class="control-group">
        <label for="name" class="control-label">Название (уникально)</label>
        <?= form_input('name', isset($resource) ? $resource[DEFAULT_LANG_CODE]->name : set_value('name'), 'data-rule-required="true" data-rule-maxlength="255"'); ?>
        <span class="label label-important">Не следует редактировать это поле без необходимости!</span>
    </div>

    <ul class="nav nav-tabs" id="myTab">
        <?php foreach ($langs as $lang): ?>
            <li><a href="#tab<?= $lang->id ?>" data-toggle="tab<?= $lang->id ?>"><?= $lang->text ?></a></li>
        <?php endforeach ?>
    </ul>

    <div class="tab-content">
        <?php foreach ($langs as $lang) : ?>
            <?php $f_content = sprintf('data[%s][content]', $lang->id) ?>
            <?php $f_description = sprintf('data[%s][description]', $lang->id) ?>

            <div class="tab-pane" id="tab<?= $lang->id ?>">
                <?php if (array_key_exists($lang->code, $resource)) : ?>

                    <div class="control-group">
                        <label for="<?= $f_content ?>" class="control-label">Значение</label>
                        <?= form_textarea(array('cols' => 5, 'rows' => 2, 'id' => $f_content, 'name' => $f_content, 'value' => isset($resource) ? $resource[$lang->code]->content : set_value($f_content)), NULL, 'data-rule-required="true" data-rule-maxlength="1024"'); ?>
                    </div>

                    <div class="control-group">
                        <label for="<?= $f_description ?>" class="control-label">Примечание</label>
                        <?= form_textarea(array('cols' => 5, 'rows' => 2, 'id' => $f_description, 'name' => $f_description, 'value' => isset($resource) ? $resource[$lang->code]->description : set_value($f_description)), NULL, 'data-rule-maxlength="255"'); ?>
                    </div>

                <?php else: ?>
                    <div class="control-group">
                        <label for="<?= $f_content ?>" class="control-label">Значение</label>
                        <?= form_textarea(array('cols' => 5, 'rows' => 2, 'id' => $f_content, 'name' => $f_content, 'value' => set_value($f_content)), NULL, 'data-rule-required="true" data-rule-maxlength="1024"'); ?>
                    </div>

                    <div class="control-group">
                        <label for="<?= $f_description ?>" class="control-label">Примечание</label>
                        <?= form_textarea(array('cols' => 5, 'rows' => 2, 'id' => $f_description, 'name' => $f_description, 'value' => set_value($f_description)), NULL, 'data-rule-maxlength="255"'); ?>
                    </div>
                <?php endif ?>
            </div>

        <?php endforeach ?>
    </div>
    <div class="pull-right">
        <input type="submit" value="Применить" id="ok" name="ok" class="btn btn-primary"/>
        <input type="submit" value="Сохранить и выйти" id="save" name="save" class="btn btn-primary"/>

        <?= anchor('cms/resource', 'Отмена', "class=\"btn\""); ?>
    </div>
</fieldset>

<?= form_close(); ?>

<?php include 'application/views/cms/footer.php' ?>
</body>

