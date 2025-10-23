<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h2 class="titleviewer">Módulo de configuración de productos sugeridos para cotizaciones</h2>
				<a href="<?php echo $this->Html->url(array('action'=>'add')) ?>" class="crearclientej float-rigth"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva configuración</span></a>

			</div>
		</div>
	</div>
	<div class="clientsLegals index blockwhite">
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class='myTable table-striped table-bordered'>
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('Principal.image','Imagen producto principal'); ?></th>
						<th><?php echo $this->Paginator->sort('Principal.part_number','Referencia producto principal'); ?></th>
						<th><?php echo $this->Paginator->sort('product_ppal','Producto o equipo principal'); ?></th>
						<th><?php echo $this->Paginator->sort('product_id','Total de productos sugeridos'); ?></th>
						<th><?php echo $this->Paginator->sort('modified','Fecha de modificación'); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($suggestedProducts as $suggestedProduct): ?>
					<tr>
						<td>
							<?php $ruta = $this->Utilities->validate_image_products($suggestedProduct['Principal']['img']); ?>
							<img class="minprods" minprod="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>"  width="50px" >
						</td>
						<td>
							<?php echo $suggestedProduct['Principal']['part_number']; ?>
						</td>
						<td><?php echo h($suggestedProduct['Principal']['name']); ?>&nbsp;</td>
						<td><?php echo h($suggestedProduct['0']['total']); ?>&nbsp;</td>
						<td><?php echo h($suggestedProduct['SuggestedProduct']['modified']); ?>&nbsp;</td>
						<td class="actions">
							<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($suggestedProduct['SuggestedProduct']['product_ppal']))) ?>" data-toggle="tooltip" title="Ver detalle"><i class="fa fa-fw fa-eye"></i>
					            </a>
					        <?php if (!is_null($suggestedProduct["SuggestedProduct"]["product_aditional"])): ?>
					        	<a href="<?php echo $this->Html->url(array('action' => 'add', $this->Utilities->encryptString($suggestedProduct['SuggestedProduct']['product_ppal']),$this->Utilities->encryptString($suggestedProduct['SuggestedProduct']['product_aditional']) )) ?>" data-toggle="tooltip" title="Editar configuración"><i class="fa fa-fw fa-pencil"></i>
					            </a>
					        <?php else: ?>
					        	<a href="<?php echo $this->Html->url(array('action' => 'add', $this->Utilities->encryptString($suggestedProduct['SuggestedProduct']['product_ppal']) )) ?>" data-toggle="tooltip" title="Editar configuración"><i class="fa fa-fw fa-pencil"></i>
					            </a>

					        <?php endif ?>
					        <a href="<?php echo $this->Html->url(array('action' => 'delete', $this->Utilities->encryptString($suggestedProduct['SuggestedProduct']['product_ppal']) )) ?>" class="changeState" data-toggle="tooltip" title="Eliminar configuración"><i class="fa fa-fw fa-trash"></i>
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

 ?>
