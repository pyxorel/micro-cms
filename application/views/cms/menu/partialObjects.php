<div class="control">
    <?php foreach ($objs as $item): ?>
    <label for="<?= "p$item->id" ?>" class="checkbox">
        <?= form_checkbox('objs[]', $item->id, FALSE, "id=\"p$item->id\"") ?>
        <?= $item->fields['name']['value'] ?>
    </label>
</div>
<?php endforeach; ?>