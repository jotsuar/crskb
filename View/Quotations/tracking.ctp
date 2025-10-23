<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-4">
				<h2 class="titleviewer">Cotizaciones si leer y/o sin hacer clic</h2>
			</div>	
			<div class="col-md-8">
				<?php echo $this->Form->create(false,["type" => "get"]); ?>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<?php echo $this->Form->input("txt_cotizacion", [ "label" => "Código o nombre de cotización","id" => "txt_cotizacion", "placeholder" => "Ingresa el Código o nombre de la cotización", "value" => isset($q) ? $q["txt_cotizacion"] : ""  ]) ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<?php echo $this->Form->input("txt_flujo", [ "label" => "Por flujo","id" => "txt_flujo", "placeholder" => "Ingresa el ID", "value" => isset($q) ? $q["txt_flujo"] : ""  ]) ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="txt_buscador_fecha">Buscador por fecha de envio</label>
								<?php echo $this->Form->text("txt_buscador_fecha", [ "label" => "Por fecha","id" => "txt_buscador_fecha", "placeholder" => "Buscador por fecha", "type" => "date", "max" => date("Y-m-d"), "class" => "form-control", "value" => isset($q) ? $q["txt_buscador_fecha"] : "" ]) ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="txt_estado">Buscador por estado</label>
								<?php echo $this->Form->input("txt_estado", [ "label" => false,"id" => "txt_estado", "options" => ["read"=>"Sin Leer", "clicked" => "Sin hacer clic"],"empty"=>"Sin leer y sin hacer clic", "class" => "form-control", "value" => isset($q) ? $q["txt_estado"] : "" ]) ?>
							</div>
						</div>
						<div class="col-md-4">
							<?php echo $this->Form->input("txt_asesor", [ "label" => "Por asesor","id" => "txt_asesor", "placeholder" => "Ingresa el ID", "value" => isset($q) ? $q["txt_asesor"] : "", "options" => $usuarios, "empty" => "Seleccionar usuario"  ]) ?>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<button type="submit" class="btn btn-info btn-block mt-4">
									Buscar <i class="fa fa-search vtc"></i>
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="blockwhite">
		<div class="contenttableresponsive">
			<table class="table-striped tableCotizacionesEnviadas table-bordered">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('EmailTracking.quotation_id', '#'); ?></th>
						<th><?php echo $this->Paginator->sort('Quotation.codigo', 'Código cotización'); ?></th>
						<th><?php echo $this->Paginator->sort('Quotation.name', 'Nombre cotización'); ?></th>
						<th>Cliente</th>
						<th><?php echo $this->Paginator->sort('Quotation.prospective_users_id', 'Flujo'); ?></th>
						<th>Asesor</th>
						<th><?php echo $this->Paginator->sort('Quotation.send', 'Fecha de envio'); ?></th>
						<th><?php echo $this->Paginator->sort('Quotation.read', 'Fecha de lectura'); ?></th>
						<th><?php echo $this->Paginator->sort('Quotation.clicked', 'Fecha de clic'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($tracks)): ?>
						<tr>
							<td colspan="9" class="text-center">
								No hay registros
							</td>
						</tr>
					<?php else: ?>
						<?php foreach ($tracks as $key => $value): ?>
							<tr>
								<td>
									<?php echo $value["EmailTracking"]["quotation_id"] ?>
								</td>
								<td>
									<a class="btn-info btn btnSm getQuotationId modalFlujoClick mr-1 mb-2" data-toggle="tooltip" data-placement="bottom" title="Ver cotización" data-quotation="<?php echo $value["Quotation"]["id"] ?>" href="#">
										<?php echo $value["Quotation"]["codigo"] ?>
									 	<i class="fa fa-eye vtc"></i>
									 </a>
									
								</td>
								<td>
									<?php echo $value["Quotation"]["name"] ?>
								</td>
								<td>
									<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['Quotation']['prospective_users_id']), 30,array('ellipsis' => '...','exact' => false)); ?> 
								</td>
								<td>
									<a class="btn btn-success idflujotable flujoModal" href="#" data-uid="<?php echo $value['Quotation']['prospective_users_id'] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value['Quotation']['prospective_users_id']); ?>"><?php echo $value['Quotation']['prospective_users_id'] ?></a>
									
								</td>
								<td><?php echo $this->Utilities->get_user_flujo($value['Quotation']['prospective_users_id']) ?></td>
								<td>
									<?php echo $value["EmailTracking"]["send"] ?>
								</td>
								<td>
									<?php echo empty($value["EmailTracking"]["read"]) ? $this->Utilities->calculateDays(date("Y-m-d",strtotime($value["EmailTracking"]["send"])),date("Y-m-d"))." días sin leer" : $value["EmailTracking"]["read"] ?>
								</td>
								<td>
									<?php if (empty($value["EmailTracking"]["clicked"])): ?>
										<?php if (empty($value["EmailTracking"]["read"])): ?>
											<?php echo $this->Utilities->calculateDays(date("Y-m-d",strtotime($value["EmailTracking"]["send"])),date("Y-m-d"))." días sin hacer clic"; ?>
										<?php else: ?>
											<?php echo $this->Utilities->calculateDays(date("Y-m-d",strtotime($value["EmailTracking"]["read"])),date("Y-m-d"))." días sin hacer clic"; ?>
										<?php endif ?>
									<?php else: ?>
										<?php echo $value["EmailTracking"]["clicked"] ?>
									<?php endif ?>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
			<div class="row numberpages">
				<?php
					echo $this->Paginator->first('<< ', array('class' => 'prev'), null);
					echo $this->Paginator->prev('< ', array(), null, array('class' => 'prev disabled'));
					echo $this->Paginator->counter(array('format' => '{:page} de {:pages}'));
					echo $this->Paginator->next(' >', array(), null, array('class' => 'next disabled'));
					echo $this->Paginator->last(' >>', array('class' => 'next'), null);
				?>
				<b> <?php echo $this->Paginator->counter(array('format' => '{:count} en total')); ?></b>
			</div>
		</div>
	</div>
</div>

<script>
	var roleGerente = "<?php echo AuthComponent::user("role") == "Gerente General" ? 1 : 0 ?>";
</script>
<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),							array('block' => 'jqueryApp'))
?>
<?php echo $this->element("flujoModal"); ?>