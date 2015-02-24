<body>
<script>
    $(document).ready(function () {

        $("#form").validate({

            rules: {

                identity: {
                    required: true,
                    maxlength: 50,
                    email: true
                },
                password: {
                    required: true,
                    maxlength: 20
                }
            },
            errorElement: "span"
        });
    });
</script>


<div id="wrap">
    <div class="container">
        <?= form_open('cms/auth/login', "id=\"form\" class=\"form-signin\"") ?>
        <legend style="width: 100%">Вход</legend>
        <?= validation_errors('<div class="errors" id="err_sum">', '</div>'); ?>
        <?= form_error('user_not_activate', '<div class="errors" id="err_sum"><p>', '</p></div>'); ?>
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="identity">Логин</label>
                <?= form_input($identity, set_value('identity'), "class=\"input-block-level\" id=\"identity\"") ?>
            </div>
            <div class="control-group">
                <label class="control-label" for="pwd">Пароль</label>
                <?= form_password($password, null, "class=\"input-block-level\" id=\"pwd\""); ?>
            </div>
            <label class="checkbox">
                <?= form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
                <?= lang('login_remember') ?>
            </label>
            <?php
            echo form_submit('submit', 'Вход', "class=\"btn btn-medium btn-primary\"");
            ?>
        </fieldset>
        <?= form_close(); ?>
    </div>
</div>
<?php include_once 'application/views/cms/footer.php' ?>
</body>
