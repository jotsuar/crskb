<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-4">
				<h2 class="titleviewer">Clientes Jurídicos</h2>
			</div>
			<div class="col-md-8 text-right">
				<a href="<?php echo $this->Html->url(array('controller'=>'ClientsNaturals','action'=>'index')) ?>" class="btn btn-warning mr-1"><i class="fa fa-arrow-circle-right vtc" aria-hidden="true"></i> <span>Clientes Naturales</span></a>
				<a href="<?php echo $this->Html->url(array('controller'=>'concessions','action'=>'index')) ?>" class="btn btn-warning mr-1"><i class="fa fa-archive vtc" aria-hidden="true"></i> <span>Concesiones</span></a>
				<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("email") == "servicioalcliente@kebco.co" ): ?>
					<a id="btn_registrar" class="btn btn-warning mr-1"><i class="fa fa-plus-square vtc"></i> <span>Crear cliente</span></a>
				<?php endif ?>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por nombre o correo electrónico">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por nombre o correo electrónico">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>	
	<div class="clientsLegals index blockwhite">
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class='myTable table-striped table-bordered'>
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('ClientsLegal.name', 'Nombre'); ?></th>
						<th><?php echo $this->Paginator->sort('ClientsLegal.nit', 'Nit'); ?></th>
						<th><?php echo $this->Paginator->sort('ClientsLegal.parent_id', 'Grupo o empresa principal'); ?></th>
						<th><?php echo $this->Paginator->sort('ClientsLegal.created', 'Fecha de creación'); ?></th>
						<th>Creó</th>
						<th class="actions">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($clientsLegals as $clientsLegal): ?>
						<tr>
							<td><?php echo h($clientsLegal['ClientsLegal']['name']); ?>&nbsp;</td>
							<td><?php echo h($clientsLegal['ClientsLegal']['nit']); ?>&nbsp;</td>
							<td>
								<?php if (!empty($clientsLegal["ClientsLegal"]["parent_id"])): ?>
									<?php echo $this->Utilities->data_null(h($clientsLegal['Parent']['nit'])); ?> | <?php echo $this->Utilities->data_null(h($clientsLegal['Parent']['name'])); ?>
									
								<?php endif ?>

								&nbsp;
							</td>
							<td><?php echo $this->Utilities->date_castellano(h($clientsLegal['ClientsLegal']['created'])); ?>&nbsp;</td>
							<td>
								<?php if ($clientsLegal['ClientsLegal']['user_receptor'] != 0) { ?>
									<?php echo $this->Utilities->find_name_lastname_adviser(h($clientsLegal['ClientsLegal']['user_receptor'])); ?>
								<?php } else { ?>
									Información no encontrada
								<?php } ?>
							</td>
							<td class="size2 resetth">
								<a class="btn btn-outline-primary" href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($clientsLegal['ClientsLegal']['id']))) ?>" data-placement="top" data-toggle="tooltip" title="Ver cliente">
									<i class="fa fa-fw fa-eye vtc"></i>
								</a>
								<?php echo $this->Utilities->paint_edit_model_modal($clientsLegal['ClientsLegal']['id'],'cliente') ?>
								<a href="javascript:void(0)" class="agregar_contacto btn btn-outline-primary" data-uid="<?php echo $clientsLegal['ClientsLegal']['id'] ?>" data-placement="top" data-toggle="tooltip" title="Añadir contacto">
									<i class="fa fa-user-plus vtc"></i>
								</a>
							</td>

						</tr>
					<?php endforeach; ?>
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
	var isRole = <?php echo in_array(AuthComponent::user('role'), $rolesPayments) ? 1 : 0 ?>;
</script>

<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js'),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/clientsLegal/index.js",				array('block' => 'AppScript'));
?>

<?php echo $this->element("customer") ?>