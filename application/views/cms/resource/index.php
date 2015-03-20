<body>
<?php include 'application/views/cms/menu.php' ?>

<script type="text/javascript">
    $('li.active').removeClass();
    $('#res').addClass('active');
</script>
<div id="wrap">
    <!-- Begin page content -->
    <div class="container">

        <div class="container buttons">
            <?php
            echo anchor('cms/resource/createView', '<i class="icon-plus-sign"></i> Добавить ресурс', "class=\"item btn btn-primary\"");
            ?>
        </div>

        <table class="table table-bordered table-striped table-hover">
            <tr class="top">
                <th width="25px">№</th>
                <th>Название</th>
                <th>Примечание</th>
                <th></th>
            </tr>

            <?php
            foreach ($resources as $i => $item):
                $linkName = anchor('cms/resource/editView/' . $item->id, $item->name);
                ?>
                <tr>
                    <td><?= ++$i ?></td>
                    <td><?= $linkName ?></td>
                    <td><?= $item->description ?></td>
                    <td>
                        <i class="icon-remove"></i><?= anchor('cms/resource/delete/' . $item->id, ' удалить', 'onclick="return confirm(\'Подтверждаете удаление?\') ? true : false;"') ?>
                    </td
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div id="push" style="margin: 21px 0px"></div>
</div>
<?php include 'application/views/cms/footer.php' ?>
</body>
