<body>
<?php include 'application/views/cms/menu.php' ?>

<script src="<?= base_url('application/content/cms/javaScripts/highlight.js') ?>" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.navbar li.active').removeClass();
        $('#page').addClass('active');
        $('#search-highlight').highlight('<?= $s_text = isset($s_text) ? $s_text : NULL; ?>');
    });
</script>

<div id="wrap">
    <div class="container">
        <div class="container buttons">
            <?= anchor('cms/page/createView', '<i class="icon-plus-sign"></i> Добавить страницу', "class=\"item btn btn-primary\""); ?>
            <div class="pull-right">
                <form class="form-search" method="get" action="<?= base_url('cms/page') ?>">
                    <div class="input-append">
                        <input placeholder="Заголовок или название" value="<?= $s_text = isset($s_text) ? $s_text : NULL; ?>" name="s_text" type="text" class="span2 search-query" style="margin:10px 0 0 10px; width: 180px">
                        <button type="submit" class="btn" style="margin:10px 0 0 0px;">Поиск</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
        $to = $to == 'asc' ? 'desc' : 'asc';
        $class = $to == 'asc' ? 'icon-chevron-up' : 'icon-chevron-down';
        ?>
        <table class="table table-bordered table-striped table-hover" id="search-highlight">
            <tr class="top">
                <th width="25px">№</th>
                <th width="230px"><a href="<?= base_url("cms/page/index/0/name/$to/$s_text") ?>">Название
                        <span class="<?= $field == 'name' ? $class : NULL ?>"></span></a></th>
                <th width="600px">Заголовок (Title)</th>
                <th>Примечание ("meta-description")</th>
                <th width="120px"><a href="<?= base_url("cms/page/index/0/c_time/$to/$s_text") ?>">Дата создания
                        <span class="<?= $field == 'c_time' ? $class : NULL ?>"></span></a></th>
                <th width="60px"></th>
            </tr>
            <?php
            foreach ($pages as $i => $item):
                $linkName = anchor('cms/page/editView/' . $item->id, $item->name);
                ?>
                <tr>
                    <td><?= $this->pagination->cur_page + ++$i ?></td>
                    <td><i class="icon-edit"></i>
                        <?= $linkName ?></td>
                    <td><?= $item->head ?></td>
                    <td><?= $item->meta_description ?></td>
                    <td><?= $item->c_time ?></td>
                    <td><i class="icon-remove"></i>
                        <?php echo anchor('cms/page/delete/' . $item->id, ' удалить', 'onclick="return confirm(\'Подтверждаете удаление?\') ? true : false;"') ?>
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
