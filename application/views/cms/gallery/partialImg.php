<ul class="thumbnails">
    <?php
    if (!empty($imgs))
        foreach ($imgs[DEFAULT_LANG_CODE] as $item):
            ?>
            <li class="span2">
                <div class="thumbnail">
                    <img alt="<?= $item->description ?>" src="<?= base_url('file_content/' . $item->img) ?>"/>
                    <a href="#<?= $item->id ?>" class="btn-add-img btn btn-mini btn-primary edit" id="addItem" title="редактировать"><i class="icon-edit"></i></a>
                    <a href="<?= base_url("cms/gallery/deleteItem/$gallery->ID/{$item->id}") ?>" class="btn-add-img btn btn-mini btn-primary" id="addItem" title="удалить"><i class="icon-remove"></i></a>
                </div>
            </li>
        <?php endforeach; ?>
</ul>
