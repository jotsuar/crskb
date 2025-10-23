<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Clientes Naturales</h2>
			</div>
			<div class="col-md-6 text-right">				
				<a href="<?php echo $this->Html->url(array('controller'=>'ClientsLegals','action'=>'index')) ?>" class="btn btn-warning mr-1"> <span>Clientes Jurídicos</span></a>
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
	<div class="clientsNaturals index blockwhite">
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class='table-striped table-bordered tableproducts'>
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('ClientsNatural.name', 'Nombre'); ?></th>
						<th>Ciudad</th>
						<th><?php echo $this->Paginator->sort('ClientsNatural.email', 'Correo electrónico'); ?></th>
						<th><?php echo $this->Paginator->sort('ClientsNatural.created', 'Fecha'); ?></th>
						<th>Creó</th>
						<th class="actions">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($clientsNaturals as $value): ?>
						<tr>
							<td class="nameuppercase">
								<?php echo h($value['ClientsNatural']['name']); ?>&nbsp;
							</td>
							<td>
								<?php echo h($value['ClientsNatural']['city']); ?>&nbsp;
							</td>
							<td>
								<?php echo h($value['ClientsNatural']['email']); ?>&nbsp;
							</td>
							<td class="">
								<?php echo $this->Utilities->date_castellano(h($value['ClientsNatural']['created'])); ?> &nbsp;
							</td>
							<td>
								<?php if ($value['ClientsNatural']['user_receptor'] != 0) { ?>
									<?php echo $this->Utilities->find_name_lastname_adviser(h($value['ClientsNatural']['user_receptor'])); ?>
								<?php } else { ?>
									Información no encontrada
								<?php } ?>
							</td>
							<td class="size2 resetth">
								<a class="btn btn-outline-primary" href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($value['ClientsNatural']['id']))) ?>" data-toggle="tooltip" title="Ver cliente">
									<i class="fa fa-fw fa-eye vtc"></i>
								</a>
								<?php echo $this->Utilities->paint_edit_model_modal($value['ClientsNatural']['id'],'cliente') ?>
								<a class="btn btn-outline-primary" href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Agregar requerimiento" data-uid='<?php echo $value['ClientsNatural']['id'] ?>' id='btn_agregar'>
									<i class="fa fa-plus-circle vtc"></i>
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
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/clientsNatural/index.js?".rand(),				array('block' => 'AppScript'));
?>

<?php echo $this->element("customer"); ?>