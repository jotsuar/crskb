<div class="col-md-12">
			<div class=" widget-panel widget-style-2 bg-azulclaro big">
             <i class="fa fa-1x flaticon-growth"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
		</div>	
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Notas de las Cotizaciones</h2>
			</div>
			<div class="col-md-6 text-right">
				<div class="input-group stylish-input-group">
				<a href="<?php echo $this->Html->url(array('controller'=>'Notes','action'=>'add')) ?>" class="crearclientej">
					<i class="fa fa-1x fa-plus-square"></i> <span>Crear Nota</span>
				</a>
					<?php if (isset($this->request->query['q'])){ ?>
						<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador nombre o descripción">
						<span class="input-group-addon btn_buscar">
			                <i class="fa fa-search"></i>
			            </span>
					<?php } else { ?>
						<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador nombre o descripción">
						<span class="input-group-addon btn_buscar">
			                <i class="fa fa-search"></i>
			            </span>
					<?php } ?>
				</div>
			</div>

		</div>
	</div>
	<div class="notes index blockwhite">
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class="tabletemplates table-striped table-bordered responsive">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('Note.name', 'Nombre'); ?></th>
						<th><?php echo $this->Paginator->sort('Note.description', 'Descripción'); ?></th>
						<th><?php echo $this->Paginator->sort('Note.type', 'Tipo'); ?></th>
						<th class="actions">Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($notes as $note): ?>
						<?php if ($note["Note"]["type"] == 4 && AuthComponent::user("role") != "Gerente General"  ): ?>
							<?php continue; ?>
						<?php endif ?>
						<tr>
							<td><?php echo h($note['Note']['name']); ?>&nbsp;</td>
							<td><?php echo $this->Text->truncate(strip_tags($note['Note']['description']), 70,array('ellipsis' => '...','exact' => false)); ?>&nbsp;</td>
							<td><?php echo $this->Utilities->type_note($note['Note']['type']); ?>&nbsp;</td>
							<td class="actions">
								<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($note['Note']['id']))) ?>" data-toggle="tooltip" data-placement="right" title="Ver nota"><i class="fa fa-fw fa-eye"></i>
					            </a>
								<a href="<?php echo $this->Html->url(array('action' => 'edit', $note['Note']['id'])) ?>" data-toggle="tooltip" data-placement="right" title="Editar nota"><i class="fa fa-fw fa-pencil"></i>
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
	echo $this->Html->script("controller/notes/index.js?".rand(),						array('block' => 'AppScript'));
?>