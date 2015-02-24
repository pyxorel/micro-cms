<div class="control">
	<?php
	foreach ($gallereis as $item):
	?>
	<label for="<?php echo "g$item->ID"?>" class="checkbox"> 
	<?php echo form_checkbox('gallery[]', $item->ID, FALSE ,"id=\"g$item->ID\"") ?>
		<?php echo $item->Name ?>
	</label>
</div>
<?php endforeach; ?>