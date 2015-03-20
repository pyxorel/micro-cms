<body>
<?php include 'application/views/cms/menu.php' ?>

<script type="text/javascript">
    $('li.active').removeClass();
    $('#preview-menu-buss').addClass('active');
    $('#inst').addClass('active');
</script>

<div id="wrap">
    <div class="container">
        <div class="container buttons">
            <div class="btn-group" style="margin: 10px;">
                <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">Добавить объект
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php foreach ($classes as $item): ?>
                        <li>
                            <a href="<?= base_url('cms/business_obj/create_instance_view/' . $item->id . (isset($s_class) ? '/' . $s_class : NULL)) ?>"><span class="icon-plus"></span> <?= $item->name ?>
                            </a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="pull-right">
                <form class="form-search" method="get" action="<?= base_url('cms/business_obj/instances') ?>">
                    <?= form_dropdown("s_class", $class_assoc, $s_class = isset($s_class) ? [$s_class] : NULL, "style=\"margin-top:9px;\" class=\"span2\"") ?>
                    <div class="input-append">
                        <input placeholder="name:название" value="<?= $s_text = isset($s_text) ? $s_text : NULL; ?>" name="s_text" type="text" class="span3 search-query" style="margin:10px 0 0 10px;">
                        <button type="submit" class="btn" style="margin:10px 0 0 0px;">Поиск</button>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover">
            <tr class="top">
                <th width="25px">№</th>
                <th>Название</th>
                <th>Класс</th>
                <th></th>
            </tr>

            <?php $x = 0;
            foreach ($instances as $i => $item):
                $linkName = anchor('cms/business_obj/edit_instance_view/' . $item->id . (isset($s_class) ? '/' . $s_class[0] : null), (isset($item->fields['name']) && !empty($item->fields['name']) ? $item->fields['name']['value'] : $item->id));
                ?>
                <tr>
                    <td><?= $this->pagination->cur_page + ++$x ?></td>
                    <td><i class="icon-edit"></i> <?= $linkName ?></td>
                    <td><?= $item->class_name ?></td>
                    <td><i class="icon-remove"></i>
                        <?= anchor('cms/business_obj/delete_instance/' . $item->id . (isset($s_class) ? '/' . $s_class[0] : null), ' удалить', 'onclick="return confirm(\'Подтверждаете удаление?\') ? true : false;"') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="pagination pagination-centered">
            <ul>
                <?= $this->pagination->create_links(); ?>
            </ul>
        </div>
    </div>
    <div id="push"></div>
</div>
<?php include 'application/views/cms/footer.php' ?>
</body>
