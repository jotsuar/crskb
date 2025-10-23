<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-8">
				<h2 class="titleviewer">Detalle de la campaña: <?php echo $campaign['Campaign']['name'] ?></h2>
			</div>
			<div class="col-md-4 text-right">
				<a href="<?php echo $this->Html->url(array("controller" => "campaigns", "action" => "index")) ?>" class="btn btn-warning mr-1"><i class="fa fa-arrow-left vtc"></i> <span>Regresar</span></a>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="table-responsive">
			<table class="table table-bordered table-hovered">
				<tr>
					<th>Asunto: </th>
					<td><?php echo h($campaign['Campaign']['subject']); ?></td>
				</tr>
				<tr>
					<th>Fecha de envío: </th>
					<td><?php echo h($campaign['Campaign']['created']); ?></td>
				</tr>
				<tr>
					<th>Tipo de envio: </th>
					<td><?php echo $campaign['Campaign']['type'] == 1 ? "Manual" : "Automático en base a ventas" ?></td>
				</tr>
				<?php if ($campaign["Campaign"]["type"] == 2 ): ?>
					<tr>
						<th>Productos agregados:</th>
						<td>
							<table class="table table-hovered table-bordered">
								<thead>
									<th>Imagen</th>
									<th><?php echo 'Referencia'; ?></th>
									<th><?php echo 'Nombre del producto'; ?></th>
									<th><?php echo 'Categoría'; ?></th>
									<th><?php echo 'Marca'; ?></th>
								</thead>
								<tbody>
									<?php foreach ($productos as $key => $product): ?>
										<tr>
											<td>
												<?php $ruta = $this->Utilities->validate_image_products($product['Product']['img']); ?>
												<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($product['Product']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="50px" height="45px" class="imgmin-product">
											</td>
											<td><?php echo h($product['Product']['part_number']); ?>&nbsp;</td>
											<td>
												<?php echo $this->Text->truncate(h($product['Product']['name']), 45,array('ellipsis' => '...','exact' => false)); ?>&nbsp;
											</td>
											<td class="sizeprod ">
												<?php 
													$categorias = $categoriesData[$product["Product"]["category_id"]]; 
												 	$grupos = explode("-->",$categorias);
												 	$num = 1;
												 ?>
												 
						    					<div class="dropdown d-inline styledrop ">
													  <a class="btn btn-outline-secondary dropdown-toggle p-1 rounded" href="#" role="button" id="gruposproducts<?php echo md5($product['Product']['id']) ?>" data-toggle="dropdown" aria-expanded="false">	Ver agrupación 								  
													  </a>

													  <div class="dropdown-menu" aria-labelledby="gruposproducts<?php echo md5($product['Product']['id']) ?>">
															<ul class="list-unstyled groupsp dropdown-item">
																<?php foreach ($grupos as $key => $value): ?>
																	<li> <b>Grupo <?php echo $num; $num++; ?>:</b> <?php echo $value; ?> </li>
																<?php endforeach ?>
															</ul>
													  </div>
												</div>



											</td>
											<td>
												<?php echo h($product['Product']['brand']); ?>&nbsp;
											</td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<th>Días deespues de compra</th>
						<td><?php echo $campaign["Campaign"]["deadline"] ?></td>
					</tr>
				<?php endif ?>
				<tr>
					<th>Datos generales: </th>
					<td class="uppercase">
						<ul class="list-unstyled">
							<li class="text-primary"> <?php echo count($campaign["EmailTracking"]) ?> correos enviados </li>
							<li class="text-info"> <?php echo $totalDelivered ?> correos leidos </li>
							<li class="text-danger"> <?php echo $totalRead ?> correos abiertos </li>
							<li class="text-success"> <?php echo $totalClick ?> correos con click </li>
						</ul>
					</td>
				</tr>
				<tr>
					<th>Contenido correo electrónico: </th>
					<td>
						<div class="table-responsive">
							<?php echo $campaign['Campaign']['content'] ?>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<h3 class="text-center mt-2 mb-2">Detalle de correos eléctronicos</h3>
		<div class="table-responsive">
			<table class="table-bordered table table-hovered">
				<thead>
					<tr>
						<th>Correo electrónico</th>
						<th>Fecha de envío</th>
						<th>Fecha de recibido</th>
						<th>Fecha de abierto</th>
						<th>Fecha de clic</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($campaign['EmailTracking'] as $emailTracking): ?>
						<tr>
							<td><?php echo $emailTracking['email']; ?></td>
							<td><?php echo $emailTracking['send']; ?></td>
							<td><?php echo $emailTracking['delivered']; ?></td>
							<td><?php echo $emailTracking['read']; ?></td>
							<td><?php echo $emailTracking['clicked']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp')); ?>
