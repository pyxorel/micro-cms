<body>
<?php include 'application/views/cms/menu.php'; ?>

<script type="text/javascript">
    $('li.active').removeClass();
    $('#gall').addClass('active');
</script>

<div id="wrap">
    <div class="container">

        <div class="container buttons">
            <?php
            echo anchor('cms/gallery/createView', '<i class="icon-plus-sign"></i> Добавить галлерею', "class=\"item btn btn-primary\"");
            ?>
        </div>

        <table class="table table-bordered table-striped table-hover">
            <tr class="top">
                <th>Название</th>
                <th>Примечание</th>
                <th></th>
            </tr>
            <?php
            if (!empty($galleries))
                foreach ($galleries as $item):
                    $linkName = anchor('cms/gallery/editView/' . $item->ID, $item->Name);
                    ?>

                    <tr>
                        <td><?php echo $linkName ?>
                        </td>
                        <td><?php echo $item->Description ?>
                        </td>
                        <td>
                            <i class="icon-remove"></i><?php echo anchor('cms/gallery/delete/' . $item->ID, ' удалить', 'onclick="return confirm(\'Подтверждаете удаление?\') ? true : false;"') ?>
                        </td>
                    </tr>
                <?php endforeach ?>
        </table>

        <div class="pagination pagination-centered">
            <ul>
                <?php
                echo $this->pagination->create_links();
                ?>
            </ul>
        </div>
    </div>

    <div id="push"></div>
</div>

<?php include_once 'application/views/cms/footer.php' ?>
</body>

