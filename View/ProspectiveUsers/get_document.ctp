<?php if (!empty($datos)): ?>
	<?php //$factValue = (array) json_decode($valueFactura["Salesinvoice"]["bill_text"]); ?>
	<?php echo $this->element("vistaFacturaWo", ["factValue" => $datos]); ?>

	<?php if (!isset($this->request->data["nuevo"])): ?>
		<a href="" class="btn btn-success validateFinal pull-right px-5">
			Guardar información
		</a>
	<?php else: ?>
		<span id="existeInfo"></span>
	<?php endif ?>
<?php else: ?>
	<h2 class="text-center text-danger">
		No se encuentran datos en WO
	</h2>
<?php endif ?>