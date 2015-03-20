<body>
<?php include 'application/views/cms/menu.php' ?>

<script type="text/javascript">
    $('li.active').removeClass();
    $('#preview-menu-buss').addClass('active');
    $('#field').addClass('active');
</script>

<div id="wrap">
    <div class="container">
        <div class="container buttons">
            <?= anchor('cms/business_obj/create_common_class_field_view', '<i class="icon-plus-sign"></i> Добавить поле', "class=\"item btn btn-primary\""); ?>
        </div>
        <table class="table table-bordered table-striped table-hover">
            <tr class="top">
                <th width="25px">№</th>
                <th>Название</th>
                <th>Тип</th>
                <th></th>
            </tr>

            <?php
            foreach ($fields as $i => $item):
                $linkName = anchor('cms/business_obj/edit_common_class_field_view/' . $item->id, $item->loc_name);
                ?>
                <tr>
                    <td><?= ++$i ?></td>
                    <td><i class="icon-edit"></i> <?= $linkName ?></td>
                    <td><?= $item->type ?></td>
                    <td><i class="icon-remove"></i>
                        <?= anchor('cms/business_obj/delete_common_class_field/' . $item->id, ' удалить', 'onclick="return confirm(\'Подтверждаете удаление?\') ? true : false;"') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="pagination pagination-centered">
            <ul>

            </ul>
        </div>
    </div>
    <div id="push"></div>
</div>
<?php include 'application/views/cms/footer.php' ?>
</body>
