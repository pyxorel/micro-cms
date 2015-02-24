<body>
<?php include 'application/views/cms/menu.php' ?>

<script type="text/javascript">
    $('li.active').removeClass();
    $('#preview-menu-buss').addClass('active');
    $('#instnce').addClass('active');
</script>

<div id="wrap">
    <div class="container">
        <div class="row"></div>
        <div class="row" style="margin: 10px;">
        <div class="btn-group ">
            <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
                Добавить объект
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <?php foreach($classes as $item): ?>
                    <li><a href="<?= base_url('cms/business_obj/create_instance_view/'. $item->id) ?>"><span class="icon-plus"></span> <?=$item->name?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
            </div>

        <table class="table table-bordered table-striped table-hover">
            <tr class="top">
                <th width="25px">№</th>
                <th>Название</th>
                <th></th>
            </tr>

            <?php $x=0;
            foreach ($instances as $i=>$item):
                $linkName = anchor('cms/business_obj/edit_instance_view/' . $item->id, isset($item->fields['name']) && !empty($item->fields['name']) ? $item->fields['name']['value'] . " ($item->class_name)" : $item->id);
                ?>
                <tr>
                    <td><?= ++$x ?></td>
                    <td><i class="icon-edit"></i> <?= $linkName ?></td>
                    <td><i class="icon-remove"></i>
                        <?= anchor('cms/business_obj/delete_instance/' . $item->id, ' удалить', 'onclick="return confirm(\'Подтверждаете удаление?\') ? true : false;"') ?></td>
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
