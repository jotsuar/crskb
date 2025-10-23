<div class="col-md-12">
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview">PANEL PRINCIPAL DE COMPRAS - RECHAZO DE PRODUCTOS</h1>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<?php if ($movileAccess): ?>
			<?php echo $this->element("order_responsive"); ?>
		<?php endif ?>
			
	</div>

	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="myTable table-striped table-bordered">
				<thead>
				<tr>
						<th>Imagen</th>
						<th><?php echo $this->Paginator->sort('Product.part_number',"Número de parte"); ?></th>
						<th><?php echo $this->Paginator->sort('product_id',"Producto"); ?></th>
						<th><?php echo $this->Paginator->sort('Import.code_import',"Código de importación"); ?></th>
						<th><?php echo __("Inventario actual"); ?></th>
						<th><?php echo $this->Paginator->sort('quantity',"Cantidad rechazada"); ?></th>
						<th><?php echo $this->Paginator->sort('reason',"Razón del rechazo"); ?></th>
						<th><?php echo $this->Paginator->sort('user_id',"Usuario que realizó el rechazo"); ?></th>
						<th><?php echo $this->Paginator->sort('created',"Fecha del rechazo"); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($rejects as $reject): ?>
					<tr>
						<td>
							<?php $ruta = $this->Utilities->validate_image_products($reject['Product']['img']); ?>
									<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($reject['Product']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="50px" height="45px" class="imgmin-product">
						</td>
						<td>
							<?php echo $reject['Product']['part_number']; ?>
						</td>
						<td>
							<?php echo $reject['Product']['name']; ?>
						</td>						
						<td>
							<?php echo $reject['Import']['code_import']; ?>
						</td>
						<td class="size11">
							<?php echo $this->element("products_block",["producto" => $reject["Product"]]) ?>
						</td>
						<td><?php echo h($reject['Reject']['quantity']); ?>&nbsp;</td>
						<td><?php echo h($reject['Reject']['reason']); ?>&nbsp;</td>
						<td>
							<?php echo $reject['User']['name']; ?>
						</td>
						<td><?php echo h($reject['Reject']['created']); ?>&nbsp;</td>
						<td class="actions">
							<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($reject['Reject']['id']))) ?>" data-toggle="tooltip" class="verDetalleRechazo" title="Ver detalle"><i class="fa fa-fw fa-eye"></i>
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


<div class="modal fade" id="modalDetalleReject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg3" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
					Detalle de solicitud rechazada
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="bodyRechazo">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>


<?php echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp'));

echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript'));
 ?>