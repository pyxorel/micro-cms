<!DOCTYPE html>
<html lang="ru">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script
        src="<?= base_url('application/content/cms/javaScripts/jquery-1.8.3.min.js') ?>"
        type="text/javascript" charset="utf-8"></script>
    <script
        src="<?= base_url('application/content/cms/javaScripts/jquery.validate.min.js') ?>"
        type="text/javascript" charset="utf-8"></script>
    <script
        src="<?= base_url('application/content/cms/javaScripts/messages_ru.js') ?>"
        type="text/javascript" charset="utf-8"></script>
    <script
        src="<?= base_url('application/content/cms/javaScripts/bootstrap/js/bootstrap.min.js') ?>"
        type="text/javascript" charset="utf-8"></script>
    <script
        src="<?= base_url('application/content/cms/javaScripts/jquery.form.js') ?>"
        type="text/javascript" charset="utf-8"></script>

    <?= link_tag('application/content/cms/javaScripts/bootstrap/css/bootstrap.css') ?>
    <?= link_tag('application/content/cms/style.css') ?>
    <?= link_tag('application/content/cms/javaScripts/bootstrap/css/font-awesome.min.css') ?>
    <!--[if IE 7]>
    <?= link_tag('application/content/cms/javaScripts/bootstrap/css/font-awesome-ie7.min.css')  ?>
    <![endif]-->

    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({async: true});

            $.validator.addMethod("regex", function (value, element, regexpr) {
                return regexpr.test(value);
            }, "Допустимые символы: 0-9a-z-_");
        });

    </script>
    <title><?= $this->config->item('app_name') ?></title>
</head>
<?= $page_content ?>
</html>
