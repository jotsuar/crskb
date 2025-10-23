<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-purple big">
	     <i class="fa fa-1x flaticon-growth"></i>
	    <h2 class="m-0 text-white bannerbig" >Módulo de Gestión DESPACHOS CRM </h2>
	</div>
</div>

<?php $new = uniqid(); ?>

<div class=" blockwhite spacebtn20">
		<h1 class="nameview spacebtnm">FLUJOS PENDIENTES DE DESPACHO (<?php echo is_null($new) ? $this->Utilities->count_pending_dispatches() : ""; ?>)</h1>
			<ul class="subdespachos">
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'information_dispatches',$new)) ?>">Datos para despachar</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'pending_dispatches',$new)) ?>">Despachos por enviar <span></span></a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches',$new)) ?>"> Despachos por confirmar</a>
				</li>
				<li class="activesub">
					<a href="<?php echo $this->Html->url(array('controller'=>'shippings','action'=>'finals',$new)) ?>"> Despachos Finalizados</a>
				</li>
			</ul>

	</div>
<div class=" blockwhite spacebtn20">
	<div class="table-responsive">
		<table cellpadding="0" cellspacing="0" class="table table-hovered">
		<thead>
		<tr>
				<th><?php echo $this->Paginator->sort('document',"Ver guía"); ?></th>
				<th><?php echo $this->Paginator->sort('Order.prospective_user_id',"Flujo"); ?></th>
				<th><?php echo $this->Paginator->sort('type',"Tipo de envío"); ?></th>
				<th><?php echo $this->Paginator->sort('guide',"Número de gúia"); ?></th>
				<th><?php echo $this->Paginator->sort('conveyor_id',"Transportadora"); ?></th>
				<th><?php echo $this->Paginator->sort('note',"Nota adicional"); ?></th>
				<th><?php echo $this->Paginator->sort('state',"Estado"); ?></th>
				<th><?php echo $this->Paginator->sort('date_initial',"Fecha creado"); ?></th>
				<th><?php echo $this->Paginator->sort('date_preparation',"Fecha preparación"); ?></th>
				<th><?php echo $this->Paginator->sort('date_send',"Fecha enviado"); ?></th>
				<th><?php echo $this->Paginator->sort('date_end',"Fecha entregado"); ?></th>
				<th class="actions"><?php echo __('Acciones'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($shippings as $shipping): ?>
		<tr>
			<td>
				<?php if (!empty($shipping["Shipping"]["document"])): ?>					
					<a class="comprobanteguia imgbuy mt-0" href="<?php echo $this->Html->url('/img/shippings/'.$shipping['Shipping']['document']) ?>" target="_blank">
						VER GUIA &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
					</a>
				<?php endif ?>
			</td>
			<td>
				<a href="<?php echo $this->Html->url(array('controller'=> 'ProspectiveUsers', 'action' => 'Index' .'?q='.$value['FlowStage']['prospective_users_id'])) ?>" class="idflujotable"><?php echo $shipping['Order']['prospective_user_id'] ?></a> &nbsp;
			</td>
			<td><?php echo ($shipping['Shipping']['type']) == 1 ? "Envío a domicilio" : "Recoge en tienda" ; ?>&nbsp;</td>
			<td><?php echo h($shipping['Shipping']['guide']); ?>&nbsp;</td>
			<td>
				<?php echo $shipping['Conveyor']['name']; ?>
			</td>
			<td><?php echo h($shipping['Shipping']['note']); ?>&nbsp;</td>
			<td><?php 

					switch ($shipping['Shipping']['state']) {
						case '0':
							echo "Despacho creado";
							break;
						case '1':
							echo "Despacho en preparación";
							break;
						case '2':
							echo "Despacho enviado";
							break;
						case '3':
							echo "Despacho entregado";
							break;
					}

			 ?>&nbsp;</td>
			<td><?php echo h($shipping['Shipping']['date_initial']); ?>&nbsp;</td>
			<td><?php echo h($shipping['Shipping']['date_preparation']); ?>&nbsp;</td>
			<td><?php echo h($shipping['Shipping']['date_send']); ?>&nbsp;</td>
			<td><?php echo h($shipping['Shipping']['date_end']); ?>&nbsp;</td>
			<td class="actions">
				<?php if ($shipping["Shipping"]["state"] != 3): ?>					
					<a href="<?php echo $this->Html->url(["action" => "change", $this->Utilities->encryptString($shipping['Shipping']['id']) ]) ?>" class="btn btn-warning btnChangeState">
						Cambiar estado
					</a>
				<?php endif ?>
				<a href="<?php echo $this->Html->url(["action" => "view", $this->Utilities->encryptString($shipping['Shipping']['id']) ]) ?>" class="btn btn-info">
					Ver detalle
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
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

<!-- Modal -->
<div class="modal fade " id="cambioEstado" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Cambio de estado despacho</h5>
      </div>
      <div class="modal-body" id="cuerpoDespachoNuevo">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/shippings/admin.js?".rand(),				array('block' => 'AppScript'));
?>