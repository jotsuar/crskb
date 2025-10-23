<option value="">Seleccione una nota</option>
<?php $num = 1; ?>
<?php foreach ($notices as $key => $value): ?>
	<option value="<?php echo $key ?>"><?php echo $num; $num++; ?>. <?php echo $value ?></option>
<?php endforeach ?>