<body>
<?php include 'application/views/cms/menu.php' ?>

<script type="text/javascript">
    $('li.active').removeClass();
    $('#config').addClass('active');
</script>
<div id="wrap">
    <div class="container">
        <form method="post" action="<?= base_url('cms/config/save')?>">
            <?php foreach ($config as $i => $item): ?>
                <div class="container buttons">
                    <h2 style="margin-left: 10px;"><?= $i ?></h2>
                </div>

                <table class="table table-bordered table-striped table-hover">
                    <tr class="top">
                        <th>Ключ</th>
                        <th>Значение</th>
                    </tr>

                    <?php foreach ($item as $_i => $val): ?>
                        <tr>
                            <td style="vertical-align: middle"><?= $_i ?></td>
                            <td><input  name="<?=$_i?>" type="text" value="<?= $val ?>" style="margin-bottom: 0px;"/></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
    </div>
        <input type="submit" value="Сохранить" class="btn btn-primary" style="margin-left: 18px;"/>
    </form>
    <div id="push" style="margin: 21px 0px"></div>
</div>
<?php include 'application/views/cms/footer.php' ?>
</body>
