<?php include_once 'application/views/cms/menu.php'; ?>

<script>
    $(document).ready(function () {

        $("#form").validate({

            rules: {
                yandex: {
                    maxlength: 4096
                },
                google: {
                    maxlength: 4096
                }
            }
        });
    });
</script>

<body>

<?php
echo validation_errors('<span class="error">', '</span>');
echo form_open('cms/analytic/create', "id=\"form\" class=\"form-horizontal well\"");
?>

<legend>Счетчики</legend>
<fieldset>
    <div class="control-group">
        <label class="control-label" for="google">Google Analytics</label>
        <?php echo form_textarea(array('cols' => 5, 'rows' => 12, 'id' => 'google', 'name' => 'google', 'value' => isset($google) ? $google : set_value('google'))); ?>
    </div>

    <div class="control-group">
        <label class="control-label" for="yandex">Yandex метрика</label>
        <?php echo form_textarea(array('cols' => 5, 'rows' => 12, 'id' => 'yandex', 'name' => 'yandex', 'value' => isset($yandex) ? $yandex : set_value('yandex'))); ?>
    </div>

    <div class="pull-right">
        <input type="submit" value="Применить" id="ok" name="ok" class="btn btn-primary"/>
    </div>
</fieldset>

<?= form_close(); ?>

<?= include 'application/views/cms/footer.php' ?>
</body>





