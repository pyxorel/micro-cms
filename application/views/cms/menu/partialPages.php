<div class="control">
    <?php foreach ($pages as $item): ?>
    <label for="<?= "p$item->id" ?>" class="checkbox">
        <?= form_checkbox('page[]', $item->id, FALSE, "id=\"p$item->id\"") ?>
        <?php $head = strlen($item->head) > 50 ? mb_substr($item->head, 0, 50) . ' ...' : $item->head ?>
        <?= "$item->name ($head)" ?>
    </label>
</div>
<?php endforeach; ?>