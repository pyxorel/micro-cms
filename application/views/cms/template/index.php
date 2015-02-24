<body>
<?php include_once 'application/views/cms/menu.php' ?>

<script type="text/javascript">
    $('li.active').removeClass();
    $('#template').addClass('active');
</script>

<div id="wrap">
    <div class="container">
        <div class="container buttons">
            <?= anchor('cms/template/createView', '<i class="icon-plus-sign"></i> Добавить шаблон', "class=\"item btn btn-primary\""); ?>
        </div>

        <table class="table table-bordered table-striped table-hover">
            <tr class="top">
                <th width="25px">№</th>
                <th>Название</th>
                <th></th>
            </tr>

            <?php $i=0; foreach ($templates as $item): ?>
                <tr>
                    <td><?= ++$i?></td>
                    <td><i class="icon-edit"></i> <?= anchor('cms/template/editView/'.$item, $item)?></td>
                    <td><i class="icon-remove"></i>
                        <?= anchor('cms/template/delete/' . $item, ' удалить', 'onclick="return confirm(\'Подтверждаете удаление?\') ? true : false;"') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="pagination pagination-centered">
            <ul>
            </ul>
        </div>
    </div>
    <div id="push" style="margin: 21px 0px"></div>
</div>
<?php include 'application/views/cms/footer.php' ?>
</body>
