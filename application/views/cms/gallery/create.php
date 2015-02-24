<script>
    $(document).ready(function () {

        $("#form").validate({

            rules: {

                name: {
                    required: true,
                    maxlength: 255
                },

                description: {
                    maxlength: 255
                }
            }
        });
    });
</script>


<body>
<?php include 'application/views/cms/menu.php'; ?>

<?php
echo validation_errors('<span class="error">', '</span>');
echo form_open('cms/gallery/create', "id=\"form\" class=\"form-horizontal well\"");
?>

<legend>Создание галлереи</legend>
<fieldset>

    <div class="control-group">
        <label class="control-label" for="name">Название (уникально)</label>
        <?php echo form_input('name', set_value('name') != null ? set_value('name') : null, "class=\"input-xlarge\""); ?>
    </div>

    <div class="control-group">
        <label class="control-label" for="description">Примечание</label>
        <?php echo form_input('description', set_value('description') != null ? set_value('description') : null, "class=\"input-xlarge\""); ?>
    </div>

    <div class="control">
			<span class="label label-info">Добавлять изобрежения в галлерею можно
				будет после её создания.</span>
    </div>

    <div class="pull-right">
        <input type="submit" value="Сохранить и выйти" id="save" name="save"
               class="btn btn-primary"/>
        <?php echo anchor('cms/gallery', 'Отмена', "class=\"btn\""); ?>
    </div>

</fieldset>

<?php echo form_close(); ?>

<?php include 'application/views/cms/footer.php' ?>
</body>
