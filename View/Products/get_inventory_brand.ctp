<h3 class="text-center text-info">
	Seleccionar productos
</h3>

<?php if (isset($finalProducts)): ?>

	<ul class="nav nav-tabs" id="myTab" role="tablist">
	 
	<?php $num = 0; ?>
	<?php foreach ($categories as $key => $value): ?>
		<li class="border-right border-white nav-item" role="presentation">
	    	<a class="nav-link brandsLink <?php echo $num == 0 ? "active show" : ''; $num++; ?>" id="CAT_<?php echo md5($key) ?>-tab" data-toggle="tab" data-target="#CAT_<?php echo md5($key) ?>" type="button" role="tab" aria-controls="CAT_<?php echo md5($key) ?>" aria-selected="true">
	    		<?php echo $value ?>
	    	</a>
	  	</li>
	<?php endforeach ?>
	</ul>
	<?php echo $this->Form->create('Product',array('enctype'=>"multipart/form-data",'data-parsley-validate','id' => 'form_productModal')); ?>
		<?php if (isset($this->request->query["currency"])): ?>
			<?php echo $this->Form->hidden('currency',array("value" => $this->request->query["currency"])); ?>			
			<?php echo $this->Form->hidden('brandId',array("value" => $this->request->query["brandId"])); ?>			
			<?php echo $this->Form->hidden('import_id',array("value" => $this->request->query["importId"])); ?>			
			<?php echo $this->Form->hidden('request',array("value" => $this->request->query["requestId"])); ?>			
		<?php endif ?>
		<div class="tab-content" id="myTabContent">
			<?php $num = 0; ?>
			<?php foreach ($categories as $key => $category): ?>
				<?php $productos = $finalProducts[$category]; ?>
				<div class="tab-pane fade <?php echo $num == 0 ? "show active" : ''; $num++; ?>" id="CAT_<?php echo md5($key) ?>" role="tabpanel" aria-labelledby="CAT_<?php echo md5($key) ?>-tab">
					<div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
				  		<table class="table-bordered w-100 table <?php echo empty($productos) ? "" : "tblProcesoSolicitud2" ?>" id="inventoryFinalBrand" >
							<thead id="encabezado">
								<th class="esconder">Imagen</th>
								<th><?php echo 'Referencia'; ?></th>
								<th><?php echo 'Producto'; ?></th>
								<?php if ($brand_id == 0): ?>
									<th>
										Marca
									</th>
								<?php endif ?>
								<th><?php echo 'Stock Min'; ?></th>
								<th><?php echo 'Stock Max'; ?></th>
								<th><?php echo 'Pto. Reorder'; ?></th>
								<th class="esconder"><?php echo __("Inventario actual") ?></th>
								<th class="text-center esconder">
									<?php echo 'Costo reposición'; ?><br>
									<?php echo 'Costo WO'; ?>
								</th>
								<th class="sizeprecio esconder">Precio</th>
								
								<th class="esconder">Seleccionar</th>
								<th>
									Cantidad a solicitar
								</th>
							</thead>
							<tbody>
								<?php if (!empty($productos )): ?>								
									<?php foreach ($productos as $key => $product): ?>
										<tr id="trInv_<?php echo $product['Product']['id'] ?>">
											<td>
												<?php $ruta = $this->Utilities->validate_image_products($product['Product']['img']); ?>
												<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($product['Product']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="50px" height="45px" class="imgmin-product">
											</td>
											<td><?php echo h($product['Product']['part_number']); ?>&nbsp;</td>
											<td class="sizeprod nameuppercase <?php echo !is_null($product["Product"]["notes"]) ? "nota" : "" ?>">
												<?php echo $this->Text->truncate(h($product['Product']['name']), 255,array('ellipsis' => '...','exact' => false)); ?>&nbsp;
											</td>
											<?php if ($brand_id == 0): ?>
												<th>
													<?php echo $product["Product"]["brand"] ?>
												</th>
											<?php endif ?>
											<td class="text-center">
												<?php echo $product["Product"]["min_stock"] ?>
											</td>
											<td class="text-center">
												<?php echo $product["Product"]["max_cost"] ?>
											</td>

											<td class="text-center">
												<?php echo $product["Product"]["reorder"] ?>
											</td>

											<td class="size11">
												<?php echo $this->element("products_block",["producto" => $product["Product"],"inventario_wo" => $partsData[$product["Product"]["part_number"]] ,"reserva" => isset($partsData["Reserva"][$product["Product"]["part_number"]]) ? $partsData["Reserva"][$product["Product"]["part_number"]] : null ]) ?>
											</td>

											<td class="text-center resetpd">

												<div class="price-purchase_price_usd">
													$<?php echo number_format($product['Product']['purchase_price_usd'],2); ?> USD
												</div>
												 
												<div class="price-purchase_price_wo">
													$<?php echo number_format($product['Product']['purchase_price_wo']); ?> COP
												</div>

											</td>				
											<td>$<?php echo number_format((int)h($product['Product']['list_price_usd']),0,",","."); ?>&nbsp;</td>
											<td>

												<a href="" class="btn btn-danger <?php echo isset($this->request->query["import_active"]) ? "selectOtherProductImport" : "selectOtherProduct" ?> " data-id="<?php echo $product['Product']['id'] ?>">
													<i class="fa fa-times vtc"></i>
												</a>
											</td>
											<td>
												<?php 

										    		if (isset($partsData[$product["Product"]["part_number"]])) {
										    			if (empty($partsData[$product["Product"]["part_number"]])) {
										    				$total = $product["Product"]["max_cost"];
										    			}else{
										    				$actual = 0;
										    				foreach ($partsData[$product["Product"]["part_number"]] as $key => $value) {
										    					$actual+=$value["total"];
										    				}
										    				if($actual >= $product["Product"]["max_cost"]){
										    					$total = 0;
										    				}else{
										    					$total = $product["Product"]["max_cost"] - $actual;
										    				}
										    			}
										    		}else{
										    			$total = $product["Product"]["max_cost"];
										    		}


										    	?>
												<?php echo $this->Form->input('quantity.'.$product['Product']['id'].".",array("disabled"=>true,'type' => 'number','label' => false,"div"=>"form-group","min" => $total > 0 ? 1 : 0 , "class" => "form-control productsImportModal","value" => $total)); ?>
											</td>
											
										</tr>
									<?php endforeach ?>
								<?php else: ?>
									<tr id="noProductsInformation">
										<td colspan="8" class="text-center">
											No se encuentran más productos para agregar de esta marca
										</td>
									</tr>
								<?php endif ?>
							</tbody>
						</table>


				  	</div>

				</div>
			<?php endforeach ?>
		</div>
		<div class="row">
			<div class="col-md-12 py-3">
				<input type="submit" class="btn btn-success pull-right" name="brand" id="solicitaBtnModal" value="Solicitar productos" style="display:none">
			</div>
		</div>
	</form>
<?php else: ?>



<div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
	<?php echo $this->Form->create('Product',array('enctype'=>"multipart/form-data",'data-parsley-validate','id' => 'form_productModal')); ?>
		<?php if (isset($this->request->query["currency"])): ?>
			<?php echo $this->Form->hidden('currency',array("value" => $this->request->query["currency"])); ?>			
			<?php echo $this->Form->hidden('brandId',array("value" => $this->request->query["brandId"])); ?>			
			<?php echo $this->Form->hidden('import_id',array("value" => $this->request->query["importId"])); ?>			
			<?php echo $this->Form->hidden('request',array("value" => $this->request->query["requestId"])); ?>			
		<?php endif ?>
		<table class="table-bordered w-100 table <?php echo empty($productos) ? "" : "tblProcesoSolicitud" ?>" id="inventoryFinalBrand" >
			<thead id="encabezado">
				<th class="esconder">Imagen</th>
				<th><?php echo 'Referencia'; ?></th>
				<th><?php echo 'Producto'; ?></th>
				<?php if ($brand_id == 0): ?>
					<th>
						Marca
					</th>
				<?php endif ?>
				<th><?php echo 'Stock Min'; ?></th>
				<th><?php echo 'Stock Max'; ?></th>
				<th><?php echo 'Pto. Reorder'; ?></th>
				<th class="esconder"><?php echo __("Inventario actual") ?></th>
				<th class="text-center esconder">
					<?php echo 'Costo reposición'; ?><br>
					<?php echo 'Costo WO'; ?>
				</th>
				<th class="sizeprecio esconder">Precio</th>
				
				<th class="esconder">Seleccionar</th>
				<th>
					Cantidad a solicitar
				</th>
			</thead>
			<tbody>
				<?php if (!empty($productos )): ?>								
					<?php foreach ($productos as $key => $product): ?>
						<tr id="trInv_<?php echo $product['Product']['id'] ?>">
							<td>
								<?php $ruta = $this->Utilities->validate_image_products($product['Product']['img']); ?>
								<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($product['Product']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="50px" height="45px" class="imgmin-product">
							</td>
							<td><?php echo h($product['Product']['part_number']); ?>&nbsp;</td>
							<td class="sizeprod nameuppercase <?php echo !is_null($product["Product"]["notes"]) ? "nota" : "" ?>">
								<?php echo $this->Text->truncate(h($product['Product']['name']), 255,array('ellipsis' => '...','exact' => false)); ?>&nbsp;
							</td>
							<?php if ($brand_id == 0): ?>
								<th>
									<?php echo $product["Product"]["brand"] ?>
								</th>
							<?php endif ?>
							<td class="text-center">
								<?php echo $product["Product"]["min_stock"] ?>
							</td>
							<td class="text-center">
								<?php echo $product["Product"]["max_cost"] ?>
							</td>

							<td class="text-center">
								<?php echo $product["Product"]["reorder"] ?>
							</td>

							<td class="size11">
								<?php echo $this->element("products_block",["producto" => $product["Product"],"inventario_wo" => $partsData[$product["Product"]["part_number"]] ,"reserva" => isset($partsData["Reserva"][$product["Product"]["part_number"]]) ? $partsData["Reserva"][$product["Product"]["part_number"]] : null ]) ?>
							</td>

							<td class="text-center resetpd">

								<div class="price-purchase_price_usd">
									$<?php echo number_format($product['Product']['purchase_price_usd'],2); ?> USD
								</div>
								 
								<div class="price-purchase_price_wo">
									$<?php echo number_format($product['Product']['purchase_price_wo']); ?> COP
								</div>

							</td>				
							<td>$<?php echo number_format((int)h($product['Product']['list_price_usd']),0,",","."); ?>&nbsp;</td>
							<td>

								<a href="" class="btn btn-danger <?php echo isset($this->request->query["import_active"]) ? "selectOtherProductImport" : "selectOtherProduct" ?> " data-id="<?php echo $product['Product']['id'] ?>">
									<i class="fa fa-times vtc"></i>
								</a>
							</td>
							<td>
								<?php 

						    		if (isset($partsData[$product["Product"]["part_number"]])) {
						    			if (empty($partsData[$product["Product"]["part_number"]])) {
						    				$total = $product["Product"]["max_cost"];
						    			}else{
						    				$actual = 0;
						    				foreach ($partsData[$product["Product"]["part_number"]] as $key => $value) {
						    					$actual+=$value["total"];
						    				}
						    				if($actual >= $product["Product"]["max_cost"]){
						    					$total = 0;
						    				}else{
						    					$total = $product["Product"]["max_cost"] - $actual;
						    				}
						    			}
						    		}else{
						    			$total = $product["Product"]["max_cost"];
						    		}


						    	?>
								<?php echo $this->Form->input('quantity.'.$product['Product']['id'].".",array("disabled"=>true,'type' => 'number','label' => false,"div"=>"form-group","min" => $total > 0 ? 1 : 0 , "class" => "form-control productsImportModal","value" => $total)); ?>
							</td>
							
						</tr>
					<?php endforeach ?>
				<?php else: ?>
					<tr id="noProductsInformation">
						<td colspan="8" class="text-center">
							No se encuentran más productos para agregar de esta marca
						</td>
					</tr>
				<?php endif ?>
			</tbody>
		</table>
		<div class="row">
			<div class="col-md-12 py-3">
				<input type="submit" class="btn btn-success pull-right" name="brand" id="solicitaBtnModal" value="Solicitar productos" style="display:none">
			</div>
		</div>
	</form>
</div>

<?php endif ?>