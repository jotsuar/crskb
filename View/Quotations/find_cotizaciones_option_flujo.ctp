<?php if (count($documentosList) > 0){ ?>
	<?php foreach ($documentosList as $value): ?>
		<option value="<?php echo $value['Quotation']['id'] ?>"><?php echo $value['Quotation']['codigo'].'  - '.$value['Quotation']['name'] ?></option>
	<?php endforeach ?>
<?php } else { ?>
	<?php return false; ?>
<?php } ?>