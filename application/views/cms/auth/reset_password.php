<body>
<script type="text/javascript">
    $("#form").validate({
        rules: {
            'new': {
                required: true,
                minlength: 6,
                maxlength: 16
            },
            new_confirm: {
                equalTo: "#new",
                required: true,
                minlength: 6,
                maxlength: 16
            }
        }
    });

</script>

<div id="wrap">
    <div class="container">
        <?= form_open('cms/auth/reset_password/'. $code, "id=\"form\" class=\"form-signin\"") ?>
        <fieldset>
            <legend>Вход</legend>
            <?= validation_errors('<div class="errors" id="err_sum">', '</div>'); ?>
            <?= form_error('user_not_activate', '<div class="errors" id="err_sum"><p>', '</p></div>'); ?>
                <?= form_input($user_id); ?>
                <?= form_hidden($csrf); ?>
                <?= form_error('user', '<div class="errors" id="err_sum">', '</div>'); ?>

                <div class="control-group">
                    <label class="control-label" for="pwd">Пароль</label>
                    <?= form_password('new', null, "id=\"new\" class=\"input-xlarge\""); ?>
                </div>

                <div class="control-group">
                    <label class="control-label" for="repeatPwd">Пароль еще раз</label>
                    <?= form_password('new_confirm', null, "id=\"new_confirm\" class=\"input-xlarge\""); ?>
                </div>
        </fieldset>
        <?= form_close(); ?>
    </div>
</div>
<?php include_once 'application/views/cms/footer.php' ?>
</body>



