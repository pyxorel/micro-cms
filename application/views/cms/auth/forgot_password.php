<script>
    $(function () {

        $("#form").validate({
            rules: {
                email: {
                    required: true,
                    maxlength: 100,
                    email: true
                }
            },
            errorElement: "span",

            errorPlacement: function (error, element) {

                error.insertAfter(element.parent());
            }
        });
    });
</script>


<h1><?= lang('reset_pwd_heading') ?></h1>

<div class="form login">
    <form method="post" action="<?= base_url('auth/forgot_password') ?>" id="form">
        <?= validation_errors('<div class="errors" id="err_sum">', '</div>'); ?>
        <label for="email"><?= lang('register_email') ?>*</label>

        <div class="input">
            <div class="shadow"></div>
            <?= form_input('email', set_value('email'), "id=\"email\"") ?>
        </div>
        <div class="submit">
            <div class="button">
                <div class="shadow">
                    <div class="l"></div>
                    <div class="r"></div>
                </div>
                <input type="submit" value="<?= lang('reset_pwd_reestablish') ?>"/>
            </div>
            <br/>
        </div>
    </form>
</div>

