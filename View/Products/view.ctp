<?php 
	$rolesPriceImport = array(
		Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'),
		Configure::read('variables.roles_usuarios.Administración'),Configure::read('variables.roles_usuarios.Gerente General'));
 ?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
             <i class="fa fa-1x flaticon-growth"></i>
            <h2 class="m-0 text-white bannerbig">Módulo de Gestión de CRM </h2>
		</div>
	<div class="row">
		<div class="col-lg-4 col-md-12 spacebtn20">
			<div class="products view blockwhite align-center">

				<?php $ruta = $this->Utilities->validate_image_products($product['Product']['img']); ?>
				<div style="background: url(<?php echo $this->Html->url('/img/products/'.$ruta) ?>)" class="img-detailproduct">
				</div>
				<!-- <p class="pricedetailp">Precio de venta: <b>$<?php echo number_format((int)h($product['Product']['list_price_usd']),0,",","."); ?>&nbsp;</b>					 -->
			    </p>

			    <?php if (in_array(AuthComponent::user("role") ,["Logística","Gerente General"] )): ?>

				    <?php 

						// $costoRep = round($product["Category"]["factor"] * ($product["Product"]["purchase_price_usd"] * $trmActual),2);
						// if (isset($costos[$product["Product"]["part_number"]]) && ($costos[$product["Product"]["part_number"]] * 1) >=  $costoRep ) {
							
						// }else{
						// 	$costoRealWo = $costoRep;
						// }
						if(isset($costos[$product["Product"]["part_number"]])){
						}
							$costoRealWo = $costoWo->Costo;
					 ?>

				    <?php if (in_array(AuthComponent::user("role"),["Gerente General","Logística"])): ?>
				    <p class="pricedetailp bg-info">Costo actual WO: <b>$<?php echo number_format($costoRealWo); ?>&nbsp;</b>					
				    	
				    </p>
				<?php endif;?>
				<!-- 
					<?php var_dump($costoWo) ?>
				 -->
			    <br>
			    <?php if ($editProducts): ?>									    	

				    <a href="<?php echo $this->Html->url(array('action' => 'edit', $product['Product']['id'])) ?>" class="btn btn-warning" data-toggle="tooltip">Editar producto <i class="fa fa-fw fa-pencil"></i>
				    </a>

				    <?php else: ?>
					  <a href="#" class="requestEditProduct" data-id="<?php echo $product["Product"]["id"] ?>" class="btn btn-warning" data-toggle="tooltip" title="x">Editar producto <i class="fa fa-fw fa-pencil"></i>
				    </a>
			    
				    <?php endif ?>
			    <?php endif ?>
			    <div class="row">
			    	<div class="col-md-6 p-0 pt-4">
			    		<h2><?php echo h($product['Product']['name']); ?></h2>
						<h2><b>Referencia: </b><?php echo $this->Utilities->data_null(h($product['Product']['part_number'])); ?>&nbsp;</h2>
			    	</div>
			    	<div class="col-md-6 p-0">

			    		

			    		<?php echo $this->element("products_block",["producto" => $product["Product"],"inventario_wo" => $partsData[$product["Product"]["part_number"]] ,"reserva" => isset($partsData["Reserva"][$product["Product"]["part_number"]]) ? $partsData["Reserva"][$product["Product"]["part_number"]] : null ]) ?>
			    	</div>
			    </div>

				

				

				<hr class="separate1">

				<?php if (isset($compositions) && !empty($compositions)): ?>
					<div class="border p-2 mb-2">						
						<p class="text-center uppercase mt-2 mb-2">
							<b>Productos que componen esta referencia</b>
						</p>
						<hr>	

						<?php foreach ($compositions as $key => $value): ?>
							<p>
								<b>
									<?php echo $value["Product"]["part_number"] ?> - 
								</b>
								<?php echo $value["Product"]["name"] ?>
							</p>
						<?php endforeach ?>
					</div>
				<?php endif ?>

				<p class="text-center"><b>Descripción: </b><?php echo $this->Utilities->data_null(($product['Product']['description'])); ?>&nbsp;</p>
				<p><b>Marca: </b><?php echo $this->Utilities->data_null(h($product['Product']['brand'])); ?>&nbsp;</p>
				<p><b>Categorías: </b> <br> 
						
						<?php 
							$categorias = $categoriesData[$product["Product"]["category_id"]]; 
						 	$grupos = explode("->",$categorias);
						 	$num = 1;
						 ?>
						<ul class="list-unstyled">
							<?php foreach ($grupos as $key => $value): ?>
								<li> Grupo <?php echo $num; $num++; ?>: <?php echo $value; ?> </li>
							<?php endforeach ?>
						</ul>
					
				</p>
				<p class="cuttext"><b>Enlace: </b><?php echo $this->Utilities->data_null(h($product['Product']['link'])); ?>&nbsp;</p>
				<p><b>Fecha de registro: </b><?php echo $this->Utilities->date_castellano(h($product['Product']['created'])); ?></p>
				<?php if (in_array(AuthComponent::user("role") ,["Logística","Gerente General"] )): ?>
					<div class="<?php echo !in_array(AuthComponent::user('role'), $rolesPriceImport) ? "d-none" : "" ?>">
						<p><b>Costo reposición USD: </b>$<?php echo $product['Product']['purchase_price_usd']; ?>&nbsp; + <?php echo $product['Product']['aditional_usd'] ?></p>
						<p><b>Costo WO: </b>$<?php echo number_format($costoRealWo) ?>



						&nbsp;</p>
						<p><b>Costo COP: </b>$<?php echo number_format((int)h($product['Product']['purchase_price_cop']),0,",","."); ?>&nbsp; + <?php echo number_format($product['Product']['aditional_cop'],2,",",".") ?></p>
					</div>
				<?php endif ?>				
			</div>
		</div>
		<div class="col-lg-8 col-md-12">
			<div class="products view blockwhite">
				<h2>ENCUÉNTRALO EN LAS SIGUIENTES COTIZACIONES</h2>
					<br>
					<div class="overflowxauto">
						<table cellpadding="0" cellspacing="0" class='tableCotizacionesEnviadas table-striped table-bordered'>
						<thead>
							<tr>
								<th>Cliente</th>
								<th>Precio de venta</th>
								<th>Nombre</th>
								<th>Estado</th>
								<th>Cotizó</th>
								<th>Fecha</th>
								<th>Ver</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($cotizaciones as $value): ?>
							<tr>
								<td class="uppercase">
									<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['Quotation']['prospective_users_id']), 30,array('ellipsis' => '...','exact' => false)); ?> 
								</td>

								<td>$<?php echo number_format((int)h($this->Utilities->find_precio_product_quotation($product['Product']['id'],$value['Quotation']['id'])),0,",","."); ?>&nbsp;</td>
								<td class="uppercase">

									<?php echo $this->Text->truncate($this->Utilities->find_name_document_quotation_send($value['Quotation']['prospective_users_id']), 50,array('ellipsis' => '...','exact' => false)); ?> 	
									</td>
								<td><?php echo $this->Utilities->find_stateFlow_quotation($value['Quotation']['prospective_users_id'],$value['Quotation']['id']) ?></td>
								<td><?php $data = $this->Utilities->find_name_adviser($value['Quotation']['user_id']); if(!is_array($data)) {echo $data;} ?></td>
								<td><?php echo $value['Quotation']['created'] ?></td>
								<td>
									<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($value['Quotation']['id']) )) ?>">
										<i class="fa fa-eye"></i>
									</a>
								</td>
							</tr>
						<?php endforeach ?>
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
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/product/edit_products.js?".rand(),    array('block' => 'AppScript')); 
	  echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); 
?>