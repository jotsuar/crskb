<?php $product = end($suggestedProducts); ?>
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
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h2 class="titleviewer">Módulo de configuración de productos sugeridos para cotizaciones</h2>
				<a href="<?php echo $this->Html->url(array('action'=>'index')) ?>" class="crearclientej float-rigth"><i class="fa fa-1x fa-list"></i> <span>Listar configuraciones</span></a>
				<a href="<?php echo $this->Html->url(array('action'=>'add')) ?>" class="crearclientej float-rigth"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva configuración</span></a>

			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-12 spacebtn20">
			<div class="products view blockwhite align-center">
				<div class="col-md-12">
					<h2 class="text-center">
						Equipo principal
					</h2>
				</div>

				 <?php 

					$costoRep = round($product["Principal"]["Category"]["factor"] * ($product["Principal"]["purchase_price_usd"] * $trmActual),2);
					if (isset($costos[$product["Principal"]["part_number"]]) && ($costos[$product["Principal"]["part_number"]] * 1) >=  $costoRep ) {
						$costoRealWo = $costos[$product["Principal"]["part_number"]]*1;
					}else{
						$costoRealWo = $costoRep;
					}

				 ?>


				<?php $ruta = $this->Utilities->validate_image_products($product['Principal']['img']); ?>
				<div style="background: url(<?php echo $this->Html->url('/img/products/'.$ruta) ?>)" class="img-detailproduct">
				</div>
			    <div class="row">
			    	<div class="col-md-6 p-0 pt-1">
			    		<h2><?php echo h($product['Principal']['name']); ?></h2>
						<h2><b>Referencia: </b><?php echo $this->Utilities->data_null(h($product['Principal']['part_number'])); ?>&nbsp;</h2>
			    	</div>
			    	<div class="col-md-6 p-0 pt-1">
			    		<?php echo $this->element("products_block",["producto" => $product["Principal"],"inventario_wo" => $partsData[$product["Principal"]["part_number"]] ,"reserva" => isset($partsData["Reserva"][$product["Principal"]["part_number"]]) ? $partsData["Reserva"][$product["Principal"]["part_number"]] : null ]) ?>
			    	</div>
			    </div>

				<hr class="separate1">

				<p class="text-center"><b>Descripción: </b><?php echo $this->Utilities->data_null(strip_tags($product['Principal']['description'])); ?>&nbsp;</p>
				<div class="row">
					<div class="col-md-6">
						<p><b>Marca: </b><?php echo $this->Utilities->data_null(h($product['Principal']['brand'])); ?>&nbsp;</p>
						<p class="cuttext"><b>Enlace: </b><?php echo $this->Utilities->data_null(h($product['Principal']['link'])); ?>&nbsp;</p>
					</div>
					<div class="col-md-6">
						<p><b>Categorías: </b> <br> 
						
							<?php 
								$categorias = $categoriesData[$product["Principal"]["category_id"]]; 
							 	$grupos = explode("->",$categorias);
							 	$num = 1;
							 ?>
							<ul class="list-unstyled">
								<?php foreach ($grupos as $key => $value): ?>
									<li> Grupo <?php echo $num; $num++; ?>: <?php echo $value; ?> </li>
								<?php endforeach ?>
							</ul>

						</p>
	
					</div>
				</div>
						
				<hr class="separate1">
				<div class="<?php echo !in_array(AuthComponent::user('role'), $rolesPriceImport) ? "d-none" : "" ?>">
					<p><b>Costo reposición USD: </b>$<?php echo $product['Principal']['purchase_price_usd']; ?>&nbsp; + <?php echo $product['Principal']['aditional_usd'] ?></p>
					<p><b>Costo WO: </b>$<?php echo number_format($costoRealWo) ?>



					&nbsp;</p>
					<p><b>Costo COP: </b>$<?php echo number_format((int)h($product['Principal']['purchase_price_cop']),0,",","."); ?>&nbsp; + <?php echo number_format($product['Principal']['aditional_cop'],2,",",".") ?></p>
				</div>
								
			</div>
		</div>
		<div class="col-lg-8 col-md-12">
			<div class="products view blockwhite">
				<h2 class="text-center">Productos sugeridos</h2>
					<br>
					<div class="overflowxauto">
						<table cellpadding="0" cellspacing="0" class='tableCotizacionesEnviadas table-striped table-bordered'>
						<thead>
							<tr >
								<th class="text-center bg-blue">Imagen</th>
								<th class="bg-blue">Referencia</th>
								<th class="bg-blue">Producto</th>
								<th class="bg-blue">Marca</th>
								
								<th class="bg-blue">
									Tiempo de entrega
								</th>
								<th class="bg-blue">
									Cantidad
								</th>
								<th class="bg-blue">
									Margen min.
								</th>
								<th class="bg-blue">
									Costo actual
								</th>
								<th class="bg-blue">
									Precio de venta
								</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($suggestedProducts)): ?>
								<?php foreach ($suggestedProducts as $key => $product): ?>
									<tr id="trID_<?php echo $product["Product"]["id"] ?> " class="tr_remarket" data-id="<?php echo $product["Product"]["id"] ?>">
										<td class="resetpd text-center">
											<?php $ruta = $this->Utilities->validate_image_products($product['Product']['img']); ?>
											<img class="minprods" minprod="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>"  width="50px" >
										</td>
										<td>
											<?php echo $product["Product"]["part_number"] ?>
										</td>
										<td>
											<?php echo $product["Product"]["name"] ?>
										</td>
										<td>
											<?php echo $product["Product"]["brand"] ?>
										</td>	
										
										<td>
											<?php echo $product["SuggestedProduct"]["delivery"];?>
										</td>
										<td>
											<?php echo $product["SuggestedProduct"]["quantity"];?>
										</td>
										<td>
											<?php echo $product["Product"]["categoria"] ?>
										</td>
										<td>
											<?php echo round($product["Product"]["purchase_price_usd"],2) ?> USD
										</td>
										<td>
											<?php $precioVenta = $product["SuggestedProduct"]["price_usd"] ?>
											<?php echo $precioVenta;?>
										</td>
									</tr>
								<?php endforeach ?>
							<?php endif ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));

 ?>