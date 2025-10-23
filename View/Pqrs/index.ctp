<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de PQRS </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de PQRS</h2>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador código o correo electrónico">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador código o correo electrónico">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="myTable table-striped table-bordered">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('code',"Código PQRS"); ?></th>
						<th><?php echo $this->Paginator->sort('city',"Ciudad"); ?></th>
						<th><?php echo $this->Paginator->sort('email',"Correo electrónico"); ?></th>
						<th><?php echo $this->Paginator->sort('state',"Estado"); ?></th>
						<th><?php echo $this->Paginator->sort('subject',"Tipo de solicitud"); ?></th>
						<th><?php echo $this->Paginator->sort('created',"Fecha de creación"); ?></th>
						<th>
							Gestión
						</th>
					</tr>		
				</thead>
				<tbody>
					<?php foreach ($pqrs as $pqr): ?>
						<tr>
							<td><?php echo h($pqr['Pqr']['code']); ?>&nbsp;</td>
							<td><?php echo h($pqr['Pqr']['city']); ?>&nbsp;</td>
							<td><?php echo h($pqr['Pqr']['email']); ?>&nbsp;</td>
							<td><?php switch ($pqr['Pqr']['state']) {
								case '1':
									echo "Abierto";
									break;
								case '2':
									echo "Esperando respuesta del usuario";
									break;
								case '3':
									echo "Gestionado por el usuario";
									break;
								case '4':
									echo "Cerrado";
									break;
							} ?>&nbsp;</td>
							<td><?php echo h($pqr['Pqr']['subject']); ?>&nbsp;</td>
							<td><?php echo h($pqr['Pqr']['created']); ?>&nbsp;</td>
							<td class="actions">
								<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($pqr['Pqr']['id']))) ?>" data-toggle="tooltip" title="Gestionar"><i class="fa fa-fw fa-eye"></i>
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
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
	echo $this->Html->script("controller/pqrs/admin.js?".rand(),						array('block' => 'AppScript'));
?>