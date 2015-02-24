<script>
    $(function () {

        $('#id').focus(function () {
            $('#help').show();
        }).blur(function () {
            $('#help').hide();
        });

        $('#re_create_captcha').on('click', function () {
            var some = $(this);
            $.ajax(
                {
                    url: "<?= base_url('auth/re_create_captcha')?>",
                    method: "GET",
                    success: function (data) {
                        some.html(data);
                    }
                }
            );
            return false
        });

        $("#form").validate({

            rules: {

                email: {
                    required: true,
                    maxlength: 100,
                    email: true
                },
                pass: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                confirm_pass: {
                    required: true,
                    minlength: 6,
                    maxlength: 20,
                    equalTo: "#pass"
                },
                id: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                captcha: {
                    required: true,
                    minlength: 4,
                    maxlength: 4
                }
            },
            errorElement: "span",
            errorPlacement: function (error, element) {
                $('#' + element.attr('name') + '_error').append(error);
            }
        });
    });
</script>

<h1><?= lang('register_head') ?></h1>

<div class="form register">
    <form method="post" action="<?= base_url('auth/register') ?>" id="form">
        <?= form_error('user', '<div class="errors" id="err_sum">', '</div>'); ?>

        <label for="email"><?= lang('register_email') ?></label>

        <div class="input">
            <div class="shadow"></div>
            <?= form_input('email', set_value('email'), "id=\"email\"") ?>
        </div>
        <span class="err_span" id="email_error"></span>

        <div class="inline">
            <div class="left">
                <label for="pass"><?= lang('login_password') ?></label>

                <div class="input">
                    <div class="shadow"></div>
                    <?= form_password('pass', null, "id=\"pass\""); ?>
                </div>
                <span class="err_span" id="pass_error"></span>
            </div>

            <div class="right">
                <label for="confirm_pass"><?= lang('login_confirm_password') ?></label>

                <div class="input">
                    <div class="shadow"></div>
                    <?= form_password('confirm_pass', null, "id=\"confirm_pass\""); ?>
                </div>
                <span class="err_span" id="confirm_pass_error"></span>
            </div>
        </div>
        <label for="id"><?= lang('register_id_device') ?></label>

        <div class="input">
            <div class="shadow"></div>
            <div id="help"></div>
            <?= form_input('id', set_value('id'), "id=\"id\"") ?>
        </div>
        <span class="err_span" id="id_error"><?= form_error('id', '<span class="error">', "</span>"); ?></span>

        <div class="inline">
            <div class="small">
                <label for="captcha"><?= lang('register_captcha') ?></label>

                <div class="input">
                    <div class="shadow"></div>
                    <?= form_input('captcha', null, "id=\"captcha\"") ?>
                </div>
            </div>
            <div class="small">
                <label>&nbsp;</label>
                <a href="#" id="re_create_captcha"><?= !empty($captcha) ? $captcha['image'] : NULL ?></a>
            </div>
        </div>
        <span class="err_span"
              id="captcha_error"><?php echo form_error('captcha', '<span class="error">', "</span>"); ?></span>

        <div class="submit">
            <div class="button">
                <div class="shadow">
                    <div class="l"></div>
                    <div class="r"></div>
                </div>
                <input type="submit" value="<?= lang('register_btn_ok') ?>"/>
            </div>
        </div>
    </form>
</div>