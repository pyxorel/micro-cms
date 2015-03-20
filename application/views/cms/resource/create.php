<body>
<script type="text/javascript" charset="utf-8">
    $(function () {

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

<?php include_once 'application/views/cms/menu.php'; ?>

<?= form_open('cms/resource/create', "id=\"form\" class=\"form-horizontal well\""); ?>

<?= validation_errors('<span class="error">', '</span>') ?>
<legend>Создание нового ресурса</legend>
<fieldset>

    <div class="control-group">
        <label class="control-label" for="name">Название (уникально)</label>
        <?= form_input('name', set_value('name'), 'class="input-xlarge" data-rule-required="true" data-rule-maxlength="255"'); ?>
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

                <div class="control-group">
                    <label class="control-label" for="<?= $f_content ?>">Значение</label>
                    <?php echo form_textarea(array('cols' => 5, 'rows' => 2, 'id' => $f_content, 'name' => $f_content, 'value' => set_value($f_content)), NULL, 'data-rule-required="true" data-rule-maxlength="1024"'); ?>
                </div>

                <div class="control-group">
                    <label class="control-label" for="<?= $f_description ?>">Примечание</label>
                    <?php echo form_textarea(array('cols' => 5, 'rows' => 2, 'id' => $f_description, 'name' => $f_description, 'value' => set_value($f_description)), NULL, 'data-rule-maxlength="255"'); ?>
                </div>

            </div>
        <?php endforeach ?>
    </div>

    <div class="pull-right">
        <input type="submit" value="Сохранить и выйти" id="save" name="save" class="btn btn-primary"/>
        <?php echo anchor('cms/resource', 'Отмена', "class=\"btn\""); ?>
    </div>

</fieldset>
<?php echo form_close(); ?>

<?php include 'application/views/cms/footer.php' ?>
</body>
