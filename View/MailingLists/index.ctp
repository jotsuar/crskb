<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de listas de distribución</h2>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('action'=>'add')) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva lista</span></a>
			</div>
		</div>
	</div>

	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('name',"Nombre"); ?></th>
						<th><?php echo $this->Paginator->sort('type',"Tipo"); ?></th>
						<th><?php echo $this->Paginator->sort('created',"Fecha de creación"); ?></th>
						<th><?php echo __('Acciones'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($mailingLists)): ?>
						<tr>
							<td colspan="4" class="text-center">
								No hay listas creadas
							</td>
						</tr>
					<?php else: ?>
						<?php foreach ($mailingLists as $mailingList): ?>
							<tr>
								<td><?php echo h($mailingList['MailingList']['name']); ?>&nbsp;</td>
								<td><?php echo $mailingList['MailingList']['type'] == "1" ? "Whatsapp" : "Correos" ?>&nbsp;</td>
								<td><?php echo h($mailingList['MailingList']['created']); ?>&nbsp;</td>
								<td>
									<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($mailingList['MailingList']['id']))) ?>" data-toggle="tooltip" title="Ver detalle">
										<i class="fa fa-fw fa-eye"></i>
								    </a>
							        <a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($mailingList['MailingList']['id']) )) ?>" data-toggle="tooltip" title="Editar lista">
							        	<i class="fa fa-fw fa-pencil"></i>
							        </a>										
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>
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
	 echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript'));
?>