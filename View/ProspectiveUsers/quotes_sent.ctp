<?php $clientes = array_merge($clientsLegals,$clientsNaturals); ?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-3">
				<h2 class="titleviewer">Todas las cotizacíones enviadas</h2>
			</div>	
			<div class="col-md-9">
				<?php echo $this->Form->create(false,["type" => "get"]); ?>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<?php echo $this->Form->input("txt_buscador_flujo", [ "label" => "Por flujo","id" => "txt_buscador_flujo", "placeholder" => "Ingresa el ID", "value" => isset($q) ? $q["txt_buscador_flujo"] : ""  ]) ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<?php echo $this->Form->input("txt_buscador_cliente", ["options" => $clientes, "empty" => "Seleccionar y buscar por cliente", "label" => "Por cliente","id" => "flujoTiendaCliente", "value" => isset($q) ? $q["txt_buscador_cliente"] : "" ]) ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="txt_buscador_fecha">Buscador por fecha</label>
								<?php echo $this->Form->text("txt_buscador_fecha", [ "label" => "Por fecha","id" => "txt_buscador_fecha", "placeholder" => "Buscador por fecha", "type" => "date", "max" => date("Y-m-d"), "class" => "form-control", "value" => isset($q) ? $q["txt_buscador_fecha"] : "" ]) ?>
							</div>
						</div>
						<div class="col-md-4">
							<?php echo $this->Form->input("txt_text", [ "label" => "Por nombre de la cotización, código de la cotización","id" => "txt_text", "placeholder" => "Ingresa el nombre de la cotización o código de la cotización", "value" => isset($q) ? $q["txt_text"] : ""  ]) ?>
						</div>
						<div class="col-md-4">
							<?php echo $this->Form->input("txt_asesor", [ "label" => "Por asesor","id" => "txt_asesor", "placeholder" => "Ingresa el ID", "value" => isset($q) ? $q["txt_asesor"] : "", "options" => $usuarios, "multiple" => true, "empty" => "Seleccionar usuario","default" => AuthComponent::user("id"), "style"=> "height: 160px;"  ]) ?>
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
						<th><?php echo $this->Paginator->sort('FlowStage.id', '#'); ?></th>
						<th>Nombre de la cotización</th>
						<th>Valor</th>
						<th><?php echo $this->Paginator->sort('FlowStage.codigoQuotation', 'Código'); ?></th>
						<th>Cliente</th>
						<th><?php echo $this->Paginator->sort('ProspectiveUser.user_id', 'Asesor'); ?></th>
						<th><?php echo $this->Paginator->sort('FlowStage.created', 'Fecha de envio'); ?></th>
						<th>VER</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($cotizaciones_enviadas)): ?>
						<tr>
							<td colspan="8" class="text-center">
								No existen cotizaciones 
								<?php if (isset($this->request->query['q'])): ?>								
									relacionadas con la búsqueda
								<?php endif ?>
							</td>
						</tr>
					<?php endif ?>
					<?php foreach ($cotizaciones_enviadas as $value): ?>
					<tr>
						<td>
							<?php echo $value['FlowStage']['id'] ?>
						</td>
						<td class="uppercase">
							<?php echo $this->Text->truncate($this->Utilities->find_name_file_quotation($value['FlowStage']['nameDocument'],$value['FlowStage']['document']), 40,array('ellipsis' => '...','exact' => false)); ?> 
						</td>
						<td>$<?php echo $this->Utilities->find_valor_quotation_flujo_id($value['FlowStage']['prospective_users_id']) ?></td>
						<td><?php echo $value['FlowStage']['codigoQuotation'] ?></td>
						<td class="uppercase">
							<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['FlowStage']['prospective_users_id']), 30,array('ellipsis' => '...','exact' => false)); ?> 
						</td>

						<td><?php echo $this->Utilities->find_name_lastname_adviser($value['ProspectiveUser']['user_id']) ?></td>
						<td><?php echo $this->Utilities->date_castellano(h($value['FlowStage']['created'])); ?></td>
						<td>
							<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$value['FlowStage']['document'])) { ?>
								<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$value['FlowStage']['document']) ?>">
									<i class="fa fa-file-text fa-x"></i>
								</a>
							<?php } else { ?>
								<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view', $this->Utilities->encryptString($value['FlowStage']['document']))) ?>">
									<i class="fa fa-file-text fa-x"></i>
								</a>
							<?php } ?>
						</td>
					</tr>
					<?php endforeach ?>
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



<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/flujo_tienda.js?".rand(),	array('block' => 'AppScript'));
	echo $this->Html->script("controller/prospectiveUsers/quotes_sent.js?".rand(),				array('block' => 'AppScript'));
?>


<style>	

		.btnSm{
			padding: 0.25rem 0.5rem !important;
		    font-size: 0.875rem !important;
		    line-height: 1.5 !important;
		    border-radius: 0.2rem !important;
		}

		.bgInfo {
		    background-color: #3f5dcb1f !important;
		}

</style>