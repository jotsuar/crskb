<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-5">
				<?php $ruta = $this->Utilities->validate_image_products($datos["Product"]['img']); ?>
				<div class="imginv mb-3" style="background-image: url(<?php echo $this->Html->url('/img/products/'.$ruta) ?>)"></div>
			</div>
			<div class="col-md-7">
				<div class="col-md-12">
					<?php echo $datos["Product"]["name"] ?>
				</div>
				<div class="col-md-12">
					<b>Referencia: </b><?php echo $datos["Product"]["part_number"] ?>
				</div>
				<div class="col-md-12">
					<h3>
						Inventario actual:
					</h3>
					<?php if (isset($inventario_wo) ): ?>
						<div class="border p-1">							
							<?php foreach ($inventario_wo as $key => $value): ?>
								<small class="mb-0" style="font-size: 16px;"><b><?php echo $value["bodega"] ?>:</b> <?php echo $value["total"] ?></small>
							<?php endforeach ?>
						</div>
					<?php endif ?>
					<?php if ( isset($reserva) && !is_null($reserva) ): ?>
						<small class="text-success text-center">Reservas actuales</small>
						<?php foreach ($reserva["reservas"] as $key => $value): ?>
							<small class="mb-0" style="font-size: 12px;"><b>Bodega:</b> <?php echo $value["Bodega"] ?><br>
							<b>Cliente:</b> <?php echo $value["Cliente"] ?><br>
							<b>Cantidad:</b> <?php echo $value["Cantidad"] ?><br>
							<b>Empleado:</b> <?php echo $value["Empleado"] ?></small>
							<hr>
						<?php endforeach ?>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>	
	<div class="col-md-12">
		<?php echo $this->Form->create('ImportRequestsDetailsProduct',array('enctype'=>"multipart/form-data",'data-parsley-validate','id' => 'formDatos')); ?>
			<?php echo $this->Form->input('id',array('value' => $datos['ImportRequestsDetailsProduct']['id']));?>
			<div class="form-group">
				<?php echo $this->Form->input('quantity',array('type' => 'number','label' => 'Cantidad Solicitada',"min" => 1, "required" => true, "value" => $datos["ImportRequestsDetailsProduct"]["quantity"])); ?>
			</div>
			<div class="form-group">
				<input type="submit" value="Cambiar cantidad" class="btn btn-success btn-block">
			</div>
	</div>	
</div>