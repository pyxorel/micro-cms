<div id="container_objs_<?=$id_link?>">
<?php foreach ($instances as $i => $item):?>
    <label for="<?= "fields_{$id_link}_{$item->id}" ?>" class="checkbox">
        <?= form_checkbox("fields[$id_link][]", $item->id, FALSE, "id=\"fields_{$id_link}_{$item->id}\" id_input = \"$item->id\"") ?>
        <?= $item->fields['name']['value'] ?>
    </label>
<?php endforeach; ?>
</div>


