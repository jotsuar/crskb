<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Solicitudes de creación de clientes</h2>
			</div>

		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscar por flujo">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscar por flujo">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table">
				<thead>
				<tr>
						<th><?php echo $this->Paginator->sort('name','Nombre'); ?></th>
						<th><?php echo $this->Paginator->sort('company','Nombre Empresa'); ?></th>
						<th><?php echo $this->Paginator->sort('prospective_user_id','Flujo'); ?></th>
						<th><?php echo $this->Paginator->sort('type','Tipo de identificación'); ?></th>
						<th><?php echo $this->Paginator->sort('identification','ID'); ?></th>
						<th><?php echo $this->Paginator->sort('email','Correo Facturación Electrónica'); ?></th>
						<th><?php echo $this->Paginator->sort('address','Dirección'); ?></th>
						<th><?php echo $this->Paginator->sort('phone','Teléfono'); ?></th>
						<th><?php echo $this->Paginator->sort('city','Ciudad'); ?></th>
						<th><?php echo $this->Paginator->sort('rut'); ?></th>
						<th><?php echo $this->Paginator->sort('state','Estado'); ?></th>
						<th><?php echo $this->Paginator->sort('user_id','Asesor'); ?></th>
						<th><?php echo $this->Paginator->sort('user_create','Quien crea / Fecha de creación'); ?></th>
						<th><?php echo $this->Paginator->sort('Solicitado'); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($customerRequests as $customerRequest): ?>
						<tr>
							<td><?php echo h($customerRequest['CustomerRequest']['name']); ?>&nbsp;</td>
							<td><?php echo h($customerRequest['CustomerRequest']['company']); ?>&nbsp;</td>
							<td><?php echo h($customerRequest['CustomerRequest']['prospective_user_id']); ?>&nbsp;</td>
							<td><?php echo h($customerRequest['CustomerRequest']['type']); ?>&nbsp;</td>
							<td><?php echo h($customerRequest['CustomerRequest']['identification']); ?>&nbsp;</td>
							<td><?php echo h($customerRequest['CustomerRequest']['email']); ?>&nbsp;</td>
							<td><?php echo h($customerRequest['CustomerRequest']['address']); ?>&nbsp;</td>
							<td><?php echo h($customerRequest['CustomerRequest']['phone']); ?>&nbsp;</td>
							<td><?php echo h($customerRequest['CustomerRequest']['city']); ?>&nbsp;</td>
							<td style="width: 100px">
								<?php if (!empty($customerRequest['CustomerRequest']['rut'])): ?>
									<a href="<?php echo $this->Html->url("/files/requests/".$customerRequest['CustomerRequest']['rut']) ?>" target="_blank" class="btn btn-info btn-sm">
										Ver rut <i class="fa fa-eye vtc"></i>
									</a>
								<?php endif ?>
							&nbsp;</td>
							<td><?php echo ($customerRequest['CustomerRequest']['state']) == 0 ? 'Sin crear' : 'Creado'; ?>&nbsp;</td>
							<td>
								<?php echo $customerRequest['User']['name']; ?>
							</td>
							<td><?php echo h($customerRequest['UserCreate']['name']); ?>&nbsp; / <?php echo h($customerRequest['CustomerRequest']['date_created']); ?> </td>
							<td><?php echo h($customerRequest['CustomerRequest']['created']); ?>&nbsp;</td>
							<td class="actions">
								<?php if (empty($customerRequest['CustomerRequest']['date_created'])): ?>
									<a class="btn btn-outline-success changeState" data-id="<?php echo $this->Utilities->encryptString($customerRequest['CustomerRequest']['id']) ?>" data-controller="CustomerRequests" data-action="change_payed" href="<?php echo $this->Html->url(array('controller' => 'CustomerRequests','action' => 'change_payed', $this->Utilities->encryptString($customerRequest['CustomerRequest']['id']))) ?>" data-toggle="tooltip" title="Marcar como pagado">
										<i class="fa vtc fa-check"></i> Marcar como creado
									</a>		 
								<?php endif ?>
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
</div>


<script>
	var isRole = 1;
</script>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js'),						array('block' => 'jqueryApp'));
	 echo $this->Html->script("controller/inventories/detail.js",    array('block' => 'AppScript'));
	 	echo $this->Html->script("controller/clientsLegal/index.js",				array('block' => 'AppScript'));
?>