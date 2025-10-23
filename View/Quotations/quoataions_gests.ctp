<div class="row" id="notifyGestData">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered font18">
				<tbody>
					<tr>
						<th>Flujo</th>
						<td>
							<?php echo $flow ?>	- 								
							<a href="<?php echo $this->Html->url(["controller"=>"quotations","action"=>"view",$this->Utilities->getQuotationId($flow),"?"=>["from_gest"=>1]]) ?>" data-uid="<?php echo $flow ?>" target="_blank" data-type="903" class="idflujotable flujoModal">
								Ver cotización
							</a>
							
						</td>
					</tr>
					<tr>
						<th>Fecha cotizado</th>
						<td><?php echo $fecha_cotizado ?></td>
					</tr>
					<tr>
						<th>Cliente</th>
						<td><ul>
							<li>ID cliente: <?php echo $cliente["identification"] ?></li>
							<li>Nombre: <?php echo $cliente["name"] ?> <?php echo isset($cliente["legal"]) ? $cliente["legal"] : "" ?> </li>
							<li>Celular: <?php echo $cliente["cell_phone"] ?></li>
							<li>Correo: <?php echo $cliente["email"] ?></li>
						</ul></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-12">
		<p class="text-info font22">
			Por favor realiza una gestión respectiva a este flujo para poder continuar y que no pase al área de remarketing.
		</p>
	</div>
	<div class="col-md-12 font16">
		<?php echo $this->Form->create('ProgresNote',["type"=>"file","url" => ["action" => "update_flow_gest","controller" => "prospective_users"]] ); ?>
			<?php echo $this->Form->hidden('flujo_id',array('value' => $flow)); ?>
			<?php echo $this->Form->hidden('type',array('value' => $type)); ?>
			<?php echo $this->Form->hidden('etapa',array('value' => $this->Utilities->name_state_flujo($state_flow))); ?>
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->Form->input('image',array('label' => "Selecciona o arrastra la imagen de prueba del contacto (requerido)", 'type' => "file","class" => "form-control dropify", "data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M", "required" => true)); ?>
					
				</div>
				<div class="col-md-6">
					<?php echo $this->Form->input('description',array('label' => "Detalle de gestión realizada",'type' => 'textarea','rows'=>'3','placeholder' => 'Cuéntanos la gestión que realizaste con el cliente','required' => true,  )); ?>
	
				</div>
				<?php if ($type == "prorroga"): ?>
					<div class="col-md-6">
						<label for="">
							Fecha de proxima gestión
						</label>
						<?php echo $this->Form->text('date_prorroga_final',array('label' => "Fecha de última prorroga", 'type' => "date","class" => "form-control", 'value' => date("Y-m-d",strtotime("+".Configure::read("DIAS_PRORROGA_TWO"). " day")), "max" => date("Y-m-d",strtotime('+35 day')), "min" => date("Y-m-d"), "required" => true)); ?>
					</div>
					<div class="col-md-6">
						<?php echo $this->Form->input('reason',array('label' => "Detalla el motivo por el cual solicitas la prorroga (opcional)",'type' => 'textarea','rows'=>'3','placeholder' => 'Detalla el motivo por el cual solicitas la prorroga','required' => false)); ?>
					</div>
				<?php endif ?>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-success mt-4 pull-right" value="Guardar gestión">
			</div>
		</form>
	</div>
</div>
