
<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-verde big">
             <i class="fa fa-1x flaticon-money"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Tesorería</h2>
		</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview upper">
					Historial de pagos a crédito aprobados
				</h1> 
			</div>
		</div>
	</div>

	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 mb-3">
				<h2>TIPOS DE PAGOS</h2>
				<ul class="subpagos-box">
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment')) ?>">Verificar Pagos</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_tienda')) ?>">Verificar pagos en tienda</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_credito')) ?>">Verificar créditos</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_true')) ?>">Pagos verificados</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_false')) ?>">Pagos rechazados</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payments_payments')) ?>">Verificación total de abonos</a>
					</li>

				</ul>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<h2>INFORMES DE TESORERÍA</h2>
				<ul class="subpagos-box2">
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas')) ?>"><b>1-</b> Informe de ventas</a>
					</li>	
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas_tienda')) ?>"><b>2-</b> Informe de ventas en tienda</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones')) ?>"><b>3-</b> Informe de Comisiones</a>
					</li>					
				</ul>
			</div>
		</div>
	</div>

	

<div class="col-md-12 p-0">

	<div class="blockwhite">
				<h1 class="nameview spacebtnm">BUSCADOR POR FILTROS</h1>
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
						<div class="col-md-12">
							<div class="form-group">
								<?php if (isset($q)): ?>
									<a href="<?php echo $this->Html->url(["action"=>"history_credit"]) ?>" class="btn mt-4 btn-warning">
										Eliminar filtros
									</a>
								<?php endif ?>
								<button class="btn btn-info mt-4">
									Buscar <i class="fa fa-search vtc"></i>
								</button>
							</div>
						</div>
					</div>
				</form>	
			</div>

	<div class="blockwhite">		
		<h1 class="nameview spacebtnm">Historial de pagos a crédito aprobados</h1>
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class='table-striped table table-bordered'>
				<thead>
					<tr>
						<th>#</th>
						<th>Fecha</th>
						<th>Asesor</th>
						<th>Id del flujo</th>
						<th>Cliente</th>
						<th>Requerimiento</th>
						<th>Número de días</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($flujosVerifyDays as $value): ?>
						<tr>
							<td><?php echo $value['ProspectiveUser']['id'] ?></td>
							<td><?php echo $this->Utilities->date_castellano($value['FlowStage']['created']); ?></td>
							<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
							<td>
								<?php if ($value['ProspectiveUser']['type'] > 0){ ?>
									<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action' => 'flujos?q='.$value['ProspectiveUser']['id'])) ?>" class="idflujotable flujoModal" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $value['ProspectiveUser']['id'] ?>
									</a>
								<?php } else { ?>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value['ProspectiveUser']['id'])) ?>" class="idflujotable flujoModal" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $value['ProspectiveUser']['id'] ?>
									</a>
								<?php } ?>
							</td>
							<td>
								<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['FlowStage']['prospective_users_id']), 40,array('ellipsis' => '...','exact' => false)); ?> 							
							</td>
							<td>
								<?php echo $this->Text->truncate($this->Utilities->find_reason_prospective($value['ProspectiveUser']['id']), 40,array('ellipsis' => '...','exact' => false)); ?> 							
							</td>
							<td><?php echo $this->Utilities->number_day_payment_text($value['FlowStage']['payment_day']); ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
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
<div class="popup2">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
	<div class="contentpopup">
		<img src="" class="img-product" alt="">
	</div>
</div>
<div class="fondo"></div>


<div class="modal fade" id="modalClienteWo" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle de credito </h2>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-md-12">
      			<div class="row">
      				<div class="col-md-6">
      					<div class="form-group">
      						<label for="nitCliente">Nit a consultar</label>
      						<input type="text" id="nitCliente" class="form-control">
      					</div>
      				</div>
      				<div class="col-md-6">
      					<div class="form-group">
      						<a href="" class="btn btn-info btnSearchCustomer mt-4">Consultar en WO</a>
      					</div>
      				</div>
      			</div>
      		</div>
      		<div class="col-md-12" id="bodyClienteWo">
      			
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp')); 
	echo $this->Html->script("controller/prospectiveUsers/flujo_tienda.js?".rand(),	array('block' => 'AppScript'));
	echo $this->Html->script("controller/prospectiveUsers/verify_payment.js?".rand(),	array('block' => 'AppScript'));
?>

 <?php echo $this->element("flujoModal"); ?>

<?php echo $this->element("flujoModal"); ?>
