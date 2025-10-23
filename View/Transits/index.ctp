<div class="col-md-12">
	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1 class="nameview">PANEL PRINCIPAL DE COMPRAS</h1>
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12">
				<h2 class="titleviewer">Gestión productos solicitados en tránsito para ventas de flujos</h2>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por ID de flujo o referencia de producto">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por ID de flujo o referencia de producto">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>
	<div class="blockwhite-import spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-bordered"> 
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('product_id','Imagen'); ?></th>
						<th><?php echo $this->Paginator->sort('product_id','Producto'); ?></th>
						<th><?php echo $this->Paginator->sort('quantity','Cantidad'); ?></th>
						<!-- <th><?php //echo $this->Paginator->sort('import_id'); ?></th> -->
						<th><?php echo $this->Paginator->sort('prospective_user_id','Flujo'); ?></th>
						<th><?php echo $this->Paginator->sort('user_id','Asesor'); ?></th>
						<th><?php echo $this->Paginator->sort('note','Nota'); ?></th>
						<th><?php echo $this->Paginator->sort('state','Estado'); ?></th>
						<th><?php echo $this->Paginator->sort('created','Fecha de solicitud'); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($transits as $transit): ?>
						<tr>
							<td>
								<?php $ruta = $this->Utilities->validate_image_products($transit['Product']['img']); ?>
								<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($transit['Product']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="100px" height="100px" class="imgmin-product">

							</td>
							<td>
								<?php echo $transit['Product']['part_number']; ?> | <?php echo $transit['Product']['name']; ?>
							</td>
							<td>
								<?php echo $transit['Transit']['quantity']; ?>
							</td>
							<!-- <td>
								<?php //echo $transit['Import']['code_import']; ?>
							</td> -->
							<td>
								<div class="dropdown d-inline">
								  	<a class="bg-blue btn btn-sm btn-success dropdown-toggle p-1 rounded text-white" href="#" role="button" id="dropdownMenuLink_<?php echo md5($transit['ProspectiveUser']['id']) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   	<?php echo $transit['ProspectiveUser']['id'] ?>
								  	</a>

									<div class="dropdown-menu styledrop" aria-labelledby="dropdownMenuLink_<?php echo md5($transit['ProspectiveUser']['id']) ?>">
									    <a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $transit['ProspectiveUser']['id'] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($transit['ProspectiveUser']['id']); ?>">Ver flujo</a>
									    <a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($transit['ProspectiveUser']['id']) ?>" href="#">Ver cotización</a>
									    <a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $transit['ProspectiveUser']['id'] ?>">Ver órden de compra</a>
									</div>
								</div> 
							</td>
							<td>
								<?php echo $transit['User']['name']; ?>
							</td>
							<td>
								<?php echo h($transit['Transit']['note']); ?>&nbsp;
							</td>
							<td>
								<?php 

									switch ($transit['Transit']['state']) {
										case '0':
											echo "Solicitado";
											break;

										case '1':
											echo "Enviado y/o entregado al asesor";
											break;
									}

								 ?>&nbsp;
							</td>
							<td>
								<?php echo h($transit['Transit']['created']); ?>&nbsp;
							</td>	
							<td class="actions">
								<?php if ($transit['Transit']['state'] == 0): ?>
									<a href="javascript:void(0)" class="btn btn-warning btn-sm btn-xs generateTransit" data-id="<?php echo $transit["Transit"]["id"] ?>">
										<i class="fa fa-check vtc"></i> Enviado/Entregado
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
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/transits/index.js?".time(),				array('block' => 'AppScript'));
?>


<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>



<?php echo $this->element("flujoModal"); ?>