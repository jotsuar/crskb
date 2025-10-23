<div class="col-md-12">
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h2 class="titleviewer">Contactos registrados de todas las empresas</h2>
			</div>
			<div class="col-md-6 text-right">
			<div class="input-group stylish-input-group">
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
		</div>
	</div>
	<div class="users index blockwhite">
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class='table-striped table-bordered' id="contactsall">
				<thead>
					<tr>
						<th class="nameuppercase"><?php echo $this->Paginator->sort('ContacsUser.name', 'Nombre'); ?></th>
						<th>Ciudad</th>
						<th><?php echo $this->Paginator->sort('ContacsUser.email', 'Correo electrónico'); ?></th>
						<th class="nameuppercase">Empresa</th>
						<th><?php echo $this->Paginator->sort('ContacsUser.created', 'Fecha de registro'); ?></th>
						<th>Usuario que realizó el registro</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($users as $user): ?>
					<tr>
						<td><?php echo h($user['ContacsUser']['name']); ?>&nbsp;</td>
						<td><?php echo h($user['ContacsUser']['city']); ?>&nbsp;</td>
						<td><?php echo h($user['ContacsUser']['email']); ?>&nbsp;</td>
						<td>
							<?php echo $this->Utilities->name_bussines($user['ContacsUser']['clients_legals_id']); ?>&nbsp;
						</td>
						<td><?php echo $this->Utilities->date_castellano(h($user['ContacsUser']['created'])); ?>&nbsp;</td>
						<td>
							<?php if ($user['ContacsUser']['user_receptor'] != 0) { ?>
								<?php echo $this->Utilities->find_name_lastname_adviser(h($user['ContacsUser']['user_receptor'])); ?>
							<?php } else { ?>
								Información no encontrada
							<?php } ?>
						</td>
						<td>
							<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($user['ContacsUser']['id']))) ?>" data-toggle="tooltip" title="Ver contacto"><i class="fa fa-fw fa-eye"></i>
				            </a>
				            <a href="javascript:void(0)" data-toggle="tooltip" title="Editar" class="btn_editar_contacto" data-uid="<?php echo $user['ContacsUser']['id'] ?>" data-flujo="0">
				            	<i class="fa fa-fw fa-pencil"></i>
				            </a>
							<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Agregar requerimiento" data-uid='<?php echo $user['ContacsUser']['id'] ?>' id='btn_agregar'>
								<i class="fa fa-plus-circle"></i>
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

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/clientsLegal/index.js?".rand(),				array('block' => 'AppScript'));
?>