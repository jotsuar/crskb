<?php 
	$arrayVarcharPermitido = array('','0000-00-00');
?>
<div class="resultadoDatos">
	<div class="col-md-12">
		<?php foreach ($datos as $value): ?>
			<div class="row">
				<div class="col-md-2 text-center">
					<b>Fecha: </b><br><?php echo $this->Utilities->date_castellano($this->Utilities->data_null_date_importacion(h($value['created']))) ?>
				</div>

				<div class="col-md-2 text-center">
				<?php if (!in_array($value['fecha_orden'],$arrayVarcharPermitido)): ?>
					<b>Proveedor: </b><?php echo $this->Utilities->data_null(h($value['proveedor'])) ?><br>
					<b>Número de orden: </b><?php echo $this->Utilities->data_null(h($value['numero_orden'])) ?><br>
					<b>Fecha: </b><?php echo $this->Utilities->date_castellano($this->Utilities->data_null_date_importacion(h($value['fecha_orden']))) ?>
				<?php endif ?>
				</div>

				<div class="col-md-2 text-center">
				<?php if (!in_array($value['link'],$arrayVarcharPermitido)): ?>
					<b>Guía: </b><a class="btn btn-primary btn-sm" href="<?php echo $this->Utilities->data_null(h($value['link'])) ?>">VER <i class="fa fa-eye vtc"></i></a><br>
					Se estima que llegue el <?php echo $this->Utilities->date_castellano($this->Utilities->data_null_date_importacion(h($value['fecha_estimada']))) ?>
				<?php endif ?>
				</div>

				<div class="col-md-2 text-center">
				<?php if (!in_array($value['fecha_miami'],$arrayVarcharPermitido)): ?>
					<b>Fecha </b><br><?php echo $this->Utilities->date_castellano($this->Utilities->data_null_date_importacion(h($value['fecha_miami']))) ?>
				<?php endif ?>
				</div>

				<div class="col-md-2 text-center">
				<?php if (!in_array($value['numero_guia'],$arrayVarcharPermitido)): ?>
					<b>Número guía: </b><?php echo $this->Utilities->data_null(h($value['numero_guia'])) ?><br>
					<b>Transportadora: </b><?php echo $this->Utilities->data_null(h($value['transportadora'])) ?>
				<?php endif ?>
				</div>

				<div class="col-md-2 text-center">
				<?php if (!in_array($value['fecha_nacionalizacion'],$arrayVarcharPermitido)): ?>
					<b>Fecha: </b><?php echo $this->Utilities->data_null_date_importacion(h($value['fecha_nacionalizacion'])) ?>
				<?php endif ?>
				</div>
				<?php if (!in_array($value['fecha_producto_empresa'],$arrayVarcharPermitido)): ?>
					<b>Fecha: </b><?php echo $this->Utilities->data_null_date_importacion(h($value['fecha_producto_empresa'])) ?>
				<?php endif ?>
			</div>
		<?php endforeach ?>
	</div>
</div>