<?php if (empty($cats)): ?>
	<option value="">El usuario no tiene categorías</option>
<?php else: ?>
	<?php foreach ($cats as $key => $value): ?>
		<option value="<?php echo $key ?>"><?php echo $value ?></option>
	<?php endforeach ?>
<?php endif ?>