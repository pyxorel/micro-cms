<script src="<?= base_url('application/content/cms/javaScripts/jquery-ui.min.js') ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('application/content/cms/javaScripts/jquery.ui.datepicker-ru.js') ?>" type="text/javascript" charset="utf-8"></script>
<?= link_tag(base_url('application/content/cms/jquery-ui.css')); ?>
<script type="text/javascript">
    $(function () {

        $.ajaxSetup({
            cache: false
        });

        $('#logo').popover({content: "<?=$this->config->item('app_name')?>", html: true}).toggle(function () {
            $(this).popover('show');
            return false;
        }, function () {
            $(this).popover('hide');
            return false;
        });
    });
</script>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
            <a class="brand" href="#" id="logo">MicroCMS</a>

            <div class="nav-collapse collapse">
                <ul class="nav acc">
                    <li id="nav"><?= anchor('cms/menu', '<i class="icon-sitemap" style="width:auto"> Навигация</i>') ?>
                    </li>
                    <li id="page"><?= anchor('cms/page', '<i class="icon-file-alt" style="width:auto"> Страницы</i>') ?>
                    </li>
                    <li id="gall"><?= anchor('cms/gallery', '<i class="icon-picture" style="width:auto"> Галереи</i>') ?>
                    </li>
                    <li id="res"><?= anchor('cms/resource', '<i class="icon-list-alt" style="width:auto"> Ресурсы</i>') ?>
                    </li>
                    <li id="template"><?= anchor('cms/template', '<i class="icon-file-alt" style="width:auto"> Шаблоны страниц</i>') ?>
                    </li>
                    <li class="dropdown" id="preview-menu-buss">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-suitcase" style="width: auto"> Бизнес сущности</i><b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li id="class"><?= anchor('cms/Business_obj', '<i class="icon" style="width:auto"> Классы</i>') ?>
                            </li>
                            <li id="field"><?= anchor('cms/Business_obj/fields', '<i class="icon" style="width:auto"> Поля</i>') ?>
                            </li>
                            <li id="inst"><?= anchor('cms/Business_obj/instances', '<i class="icon" style="width:auto"> Объекты</i>') ?>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown" id="preview-menu-seo">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-bar-chart" style="width: auto"> Разное</i><b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li id="config"><?= anchor('cms/config', '<i class="icon-dashboard" style="width:auto"> Конфигурация</i>') ?>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <ul class="nav pull-right">
                <li>
                    <a href="<?= base_url('cms/auth/change_password') ?>" id="changePwd" class="icon-key" title="Сменить логин/пароль"></a>
                </li>
                <li><a href="<?= base_url('cms/auth/logout') ?>" class="icon-off" title="Выход"></a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div id="dialog" title="Смена логина/пароля"></div>

