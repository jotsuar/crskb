<?php echo $this->Form->create('TechnicalService',array('id' => 'formInformeCliente','enctype'=>'multipart/form-data')); ?>
	<div class="row">
		<div class="col-md-12">
			<h3 class="text-center">
				Informar al cliente sobre una demora de servicio técnico
			</h3>
		</div>
		<?php foreach ($equipos_servicio as $value): ?>
					
			<div class="col-md-12">
				<h2 class="text-center text-info">Equipo ingresado</h2>
				<div id="<?php echo 'equipo_'.$value['ProductTechnical']['id'] ?>" >
					<div class="col-md-12 p-0">
						<div class="table-responsive">
							<table class="table table-hovered">
								<tbody>
									<tr>
										<td class="p-0">
											<p><b>Equipo:</b><?php echo $value['ProductTechnical']['equipment'] ?></p>
										</td>
										<td class="p-0">
											<p><b>Número de serie:</b><?php echo $value['ProductTechnical']['serial_number'] ?></p>
										</td>
									</tr>
									<tr>
										<td class="p-0">
											<p><b>Número de parte:</b><?php echo $value['ProductTechnical']['part_number'] ?></p>
										</td>
										<td class="p-0">
											<p><b>Marca:</b><?php echo $value['ProductTechnical']['brand'] ?></p>
										</td>
									</tr>
									<tr>
										<td class="p-0">
											<p><b>Motivo de Ingreso:</b><?php echo $value['ProductTechnical']['reason'] ?></p>
										</td>
										<td class="p-0">
											<p><b>Serial:</b><?php echo $this->Utilities->data_null($value['ProductTechnical']['serial_garantia']) ?></p>
										</td>
									</tr>
									<?php if (!is_null($technicalServices["TechnicalService"]["deadline"])): ?>
										<tr>
											<td class="p-0" colspan="2">
												<p><b>Fecha límite de entrega:</b> <?php echo $technicalServices['TechnicalService']['deadline'] ?></p>
											</td>											
										</tr>
									<?php endif ?>
									<tr>
										<td class="p-0" colspan="2">
												<p><b>Observaciones con que se recibió el equipo:</b></p>
										</td>
									</tr>
									<tr>
										<td class="p-0" colspan="2">
											<div class="p-0 px-1">	<span><?php echo $value['ProductTechnical']['observation'] ?></span></div>
										</td>
									</tr>
									<tr>
										<td class="p-0" colspan="2">
											<p><b>Posibles fallas indicadas por el cliente</b></p>
										</td>
									</tr>
									<tr>
										<td class="p-0" colspan="2">
											<div class="p-0 px-1">	<span><?php echo $value['ProductTechnical']['possible_failures'] ?></span></div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>												
					</div>
				</div>
			</div>
		<?php endforeach ?>
		
	</div>		
	<?php echo $this->Form->input("id",["value" => $id]) ?>
	<?php echo $this->Form->input("gest",["value" => 1, "type"=>"hidden"]) ?>
	<?php echo $this->Form->input("texto", array("label" => "Razón que le dará al cliente por la demora" ,"placeholder" => "Por favor detalla muy bien porque este producto sufrirá una demora" ,"required", "type" => "textarea" )) ?>
	<div class="form-group mb-4" style="margin-bottom: 15px !important;">
		<label for="">Fecha Probable de entrega</label>
		<?php echo $this->Form->text("deadline", array("label" => "Fecha probable de entrega" ,"placeholder" => "Por favor detalla muy bien porque este producto sufrirá una demora" ,"required","type" => "date","class" => "form-control", "value" => date("Y-m-d",strtotime("+7 day")) )) ?>
	</div>

	<input type="submit" class="btn btn-success" value="Enviar informe">
</form>