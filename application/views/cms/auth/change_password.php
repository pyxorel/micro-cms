<body>
<script>
    $(document).ready(function () {
        $("#form").validate({
            rules: {
                old: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },

                'new': {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },

                new_confirm: {
                    minlength: 6,
                    maxlength: 20,
                    equalTo: "#new"
                }
            },
            errorElement: "span"
        });
    });
</script>

<div class="container">
    <form class="form-signin" action="<?php echo base_url('cms/auth/change_password') ?>" method="post" id="form">
        <legend>Смена пароля</legend>
        <?= validation_errors('<div class="errors" id="err_sum">', '</div>'); ?>
        <fieldset>
            <?= form_input($user_id); ?>
            <div class="control-group">
                <label class="control-label" for="old">Старый пароль</label>

                <div class="controls">
                    <?= form_password($old_password, null, "class=\"input-block-level\" id=\"old\""); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="new">Новый пароль</label>

                <div class="controls">
                    <?= form_input($new_password, null, "class=\"input-block-level\" id=\"new\""); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="new_confirm">Новый пароль еще раз</label>

                <div class="controls">
                    <?= form_password($new_password_confirm, null, "class=\"input-block-level\" id=\"new_confirm\""); ?>
                </div>
            </div>
            <div class="control-group">
                <p></p>

                <div class="controls">
                    <button type="submit" class="btn btn-primary">Сменить</button>
                    <a class="btn" href="<?= base_url('cms/menu') ?>"> Отмена </a>
                </div>
            </div>
        </fieldset>
    </form>
</div>
</body>

