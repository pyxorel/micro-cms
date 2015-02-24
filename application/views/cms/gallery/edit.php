<?php //include 'application/views/jq_validation.php'  ?>

<script
    src="<?php echo base_url('application/content/cms/javaScripts/jquery-ui-1.8.24.custom.min.js') ?>"
    type="text/javascript" charset="utf-8"></script>

<script
    src="<?php echo base_url('application/content/cms/javaScripts/elfinder/js/elfinder.min.js') ?>"
    type="text/javascript" charset="utf-8"></script>

<script
    src="<?php echo base_url('application/content/cms/javaScripts/elfinder/js/i18n/elfinder.ru.js') ?>"
    type="text/javascript" charset="utf-8"></script>

<?php
echo link_tag(base_url('application/content/cms/javaScripts/elfinder/css/elfinder.min.css'))
?>
<?= link_tag(base_url('application/content/cms/jquery-ui-1.8.24.custom.css')); ?>
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
<script type="text/javascript">
    $(function () {

        $.ajaxSetup({
            cache: false
        });

        $("#addItem").click(function () {
            $('#dialogAddItem').load("<?php echo base_url("cms/gallery/addItemView/" . $gallery->ID) ?>");
            $("#dialogAddItem").dialog("open");
            return false;
        });

        $("#dialogAddItem").dialog({
            autoOpen: false,
            modal: true,
            height: 450,
            width: 950,
            position: ["center", "center"],
            buttons: {
                "Добавить": function () {
                    $('#formAddItem').submit();

                },
                "Закрыть": function () {
                    $(this).dialog("close");
                }
            }
        });

        $(".edit").click(function () {
            var id = $(this).attr('href');
            $('#dialogEditItem').load("<?php echo base_url("cms/gallery/editItemView/". $gallery->ID)?>" + '/' + id.substring(1, id.length));
            $("#dialogEditItem").dialog("open");
        });

        $("#dialogEditItem").dialog({
            autoOpen: false,
            modal: true,
            height: 500,
            width: 940,
            position: ["center", "center"],
            buttons: {
                "Сохнанить": function () {
                    $('#formEditItem').submit();

                },
                "Закрыть": function () {
                    $(this).dialog("close");
                }
            }
        });


    });
</script>

<div id="elFinder"></div>

<div id="dialogAddItem" title="Добавление изображения в галлерею"></div>
<div id="dialogEditItem" title="Изменить изобрежение в галлереи"></div>
<?php
echo validation_errors('<span class="error">', '</span>');

echo form_open('cms/gallery/edit', "id=\"form\" class=\"form-horizontal well\"");
echo form_hidden('id', isset($gallery) ? $gallery->ID : set_value('id'));
?>
<fieldset>
    <legend>Редактирование галлереи</legend>
    <div class="control-group">
        <label class="control-label">Название (уникально)</label>
        <?= form_input(array('name' => 'name', 'id' => 'name', 'class' => "input-xlarge"), isset($gallery) ? $gallery->Name : set_value('name')); ?>
    </div>

    <div class="control-group">
        <label class="control-label">Примечание</label>
        <?= form_input('description', isset($gallery) ? $gallery->Description : set_value('description'), "class = \"input-xlarge\""); ?>
    </div>

    <a href="#" class="btn-add-img btn btn-small btn-primary" id="addItem"><i class="icon-plus-sign"></i> Добавить
        изображение</a>

    <div id="gallery">
        <?= $imgs ?>
    </div>

    <div class="pull-right">
        <input type="submit" value="Применить" id="ok" name="ok"
               class="btn btn-primary"/> <input type="submit"
                                                value="Сохранить и выйти" id="save" name="save"
                                                class="btn btn-primary"/>
        <?= anchor('cms/gallery', 'Отмена', "class=\"btn\""); ?>
    </div>
</fieldset>
<?= form_close(); ?>
<?php include 'application/views/cms/footer.php' ?>
</body>
