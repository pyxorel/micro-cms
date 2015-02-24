<div class="control">
    <?php foreach ($pages as $item):?>
    <label for="<?= "p$item->id" ?>" class="checkbox">
        <?= form_checkbox('page[]', $item->id, FALSE, "id=\"p$item->id\"") ?>
        <?= "$item->name ($item->head)" ?>
    </label>
</div>
<?php endforeach; ?>