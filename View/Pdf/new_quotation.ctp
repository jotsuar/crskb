<?php 
	$arrayVarcharPermitido = array('','<br>');
?>
<img src="<?php echo WWW_ROOT.'/img/header/header/'.$datosHeaders['Header']['img_big'] ?>" class="images-header">
<br>

<div class="contentmargin">
	<div class="datosPedido">
		<br>
		<h3 class="colorrojo"><?php echo $codigoQuotation ?>
			<?php if ($datos['ProspectiveUser']['type'] > 0): ?>
				/ <span class="numberorderst">
					<?php echo $this->Utilities->consult_cod_service($datos['ProspectiveUser']['type']) ?>
				</span>
			<?php endif ?>
		</h3>
		<br>
		<br>
		<h3 style="color: #3c3c3c">
			<?php echo $this->Utilities->explode_city($datosUsuario['User']['city'],$datos["ProspectiveUser"]["country"]) .', '.$this->Utilities->date_castellano($datosQuation['Quotation']['created']); ?>
		</h3>
		<br><br>
		<p>Sr(a):</p>
		<br><br>
		<h3 style="color: #3c3c3c">
			<?php echo mb_strtoupper($this->Utilities->name_prospective($datos['ProspectiveUser']['id'])); ?> <?php echo $datos['ProspectiveUser']['contacs_users_id'] > 0 ? " - ".$this->Utilities->name_bussines($datosC['ContacsUser']['clients_legals_id']) : "" ?>
		</h3>
		<?php if ($datos['ProspectiveUser']['contacs_users_id'] > 0){ ?>
			<?php if ($datosC['ContacsUser']['telephone'] != ''): ?>
				<h3 style="color: #3c3c3c"><?php echo $datosC['ContacsUser']['telephone'] ?></h3>
			<?php endif ?>
			<?php if ($datosC['ContacsUser']['cell_phone'] != ''): ?>
				<h3 style="color: #3c3c3c"><?php echo $datosC['ContacsUser']['cell_phone'] ?></h3>
			<?php endif ?>
			<h3 style="color: #3c3c3c"><?php echo $datosC['ContacsUser']['email'] ?></h3>
			<h3 style="color: #3c3c3c"><?php echo $datosC['ContacsUser']['city'] ?></h3>
		<?php } else { ?>
			<?php if ($datosC['ClientsNatural']['telephone'] != ''): ?>
				<h3 style="color: #3c3c3c"><?php echo $datosC['ClientsNatural']['telephone'] ?></h3>
			<?php endif ?>
			<?php if ($datosC['ClientsNatural']['cell_phone'] != ''): ?>
				<h3 style="color: #3c3c3c"><?php echo $datosC['ClientsNatural']['cell_phone'] ?></h3>
			<?php endif ?>
			<h3 style="color: #3c3c3c"><?php echo $datosC['ClientsNatural']['email'] ?></h3>
			<h3 style="color: #3c3c3c"><?php echo $datosC['ClientsNatural']['city'] ?></h3>
		<?php } ?>
		<br><br><br>
		<h3 class="colorazul"><b>Asunto: </b><?php echo $datosQuation['Quotation']['name'] ?></h3>
		<br><br>
		<br><br>
		<hr>

		<?php if ($datos['ProspectiveUser']['type'] > 0): ?>
			<table cellpadding="0" cellspacing="0" class="tableproductsst">
				<tr class="text-center">
					<th colspan="6">PRODUCTO DEL CLIENTE - ORDEN DE SERVICIO <?php echo $this->Utilities->consult_cod_service($datos['ProspectiveUser']['type']) ?></b></th>
				</tr>
				<tr class="titles-tablest">
					<td>Equipo</td>
					<td>Número de parte</td>
					<td>Serie</td>
					<td>Serial</td>
					<td>Marca</td>
				</tr>
				<?php foreach ($productClient as $valueP): ?>
					<tr>
						<td><?php echo $valueP['ProductTechnical']['equipment'] ?></td>
						<td><?php echo $valueP['ProductTechnical']['part_number'] ?></td>
						<td><?php echo $valueP['ProductTechnical']['serial_number'] ?></td>
						<td><?php echo $this->Utilities->data_null($valueP['ProductTechnical']['serial_garantia']) ?></td>
						<td><?php echo $valueP['ProductTechnical']['brand'] ?></td>
					</tr>
				<?php endforeach ?>
			</table>
			<img src="<?php echo WWW_ROOT.'/img/logos-clientes-tecnico.jpg' ?>" class="images-cliente">
		<?php endif ?>
		<?php if ($datos['ProspectiveUser']['type'] < 1): ?>
			<?php if (!in_array($datosQuation['Quotation']['notes'],$arrayVarcharPermitido)) { ?>
				<div class="contentgrafico">
					<p><?php echo $datosQuation['Quotation']['notes'] ?></p>
				</div>
			<?php } else { ?>
				<img src="<?php echo WWW_ROOT.'/img/logos-clientes.jpg' ?>" class="images-cliente">
			<?php } ?>
		<?php endif; ?>
		<hr>
	</div>
	<br>
	<br>
	<br>

	<h2 class="text-center colorazul">PRODUCTOS COTIZADOS</h2>
	<hr>
	<?php $totalNoIva = 0; ?>
	<?php $totalNoIvaUSD = 0; ?>
	<?php $usdData = array(); $copData=array(); ?>
	<?php $i = 0; foreach ($datosProductos as $value): $i++; ?>
		<?php 
			if($value["QuotationsProduct"]["currency"] == "usd"){
				$usdData[] = $value["QuotationsProduct"]["id"];
			}else{
				$copData[] = $value["QuotationsProduct"]["id"];								
			}
		 ?>
		<div class="divContenedor">
			<br>
			<?php $ruta = $this->Utilities->validate_image_products($value['Product']['img']);?>
			<div class="imgProduct">
				<img src="<?php echo WWW_ROOT.'/img/products/'.$ruta ?>" class="images-product">
			</div>
			<div class="atributos">
				<p class="txtPLineaNo text-left">
					<strong class="colorVerde">
						Referencia: <?php echo $value['Product']['part_number'] ?> / Marca: <?php echo $value['Product']['brand'] ?>
					</strong>
				</p>
				<p class="txtPLineaNo text-right"><?php echo 'Producto '.$i; ?></p>
				<h3 class="colorazul" style="text-transform: uppercase;"><?php echo $this->Text->truncate(strip_tags($value['Product']['name']), 60,array('ellipsis' => '...','exact' => false)); ?></h3>
				<p class="descriptionview">
					<?php echo $this->Text->truncate(strip_tags($value['Product']['description']), 250,array('ellipsis' => '...','exact' => false)); ?>
				</p>

				<div class="content-price">
					<div class="cantidad ivaPrice">Cant. <?php echo $value['QuotationsProduct']['quantity'] ?></div>
					<div class="entrega ivaPrice"><?php echo $value['QuotationsProduct']['delivery'] ?></div>


					<div class="precio ivaPrice" style="<?php echo $datosQuation['Quotation']['currency'] == "usd" ? "" : "" ?>">
						<?php $precioCotizacionUnidad = explode(",", number_format((float)$value['QuotationsProduct']['price'], "2",",",".")) ?>
						$<?php echo $precioCotizacionUnidad[0] ?><span class="decimales simpledecimal">,<?php echo $precioCotizacionUnidad[1] ?></span>
						<?php echo $value['QuotationsProduct']['currency'] == "usd" ? " USD" : "" ?>
					</div>

					<div class="subtotal ivaPrice" style="<?php echo $datosQuation['Quotation']['currency'] == "usd" ? "" : "" ?>">
						<?php $precioCotizacionSubtotal = explode(",", $this->Utilities->total_item_products_quotations2($value['QuotationsProduct']['quantity'],$value['QuotationsProduct']['price'])) ?>
						$<?php echo $precioCotizacionSubtotal[0] ?><span class="decimales simpledecimal">,<?php echo $precioCotizacionSubtotal[1] ?></span>
						<?php echo $value['QuotationsProduct']['currency'] == "usd" ? " USD" : "" ?> 
						<?php echo $datos["ProspectiveUser"]["country"] == "Colombia"? "+IVA" : "" ?>	
					</div>


					<?php
						if ($value["QuotationsProduct"]["currency"] == "cop") {
							$totalNoIva += ( $value['QuotationsProduct']['quantity'] *$value['QuotationsProduct']['price'] );
						}else{
							$totalNoIvaUSD += ( $value['QuotationsProduct']['quantity'] *$value['QuotationsProduct']['price'] );
						}
					?>
				</div>
			</div>
		</div>
		<?php if (!empty($value["QuotationsProduct"]["note"])): ?>
			<div>
				<hr>
				<?php echo $value["QuotationsProduct"]["note"] ?>
			</div>
		<?php endif ?>

		<?php if ($i % 4 == 0): ?>
			<?php if (isset($datosProductos[($i + 1)])): ?>
				<br>
				<br>
				<br>
				<br>
				<p></p>
			<?php endif ?>
		<?php endif ?>
	<?php endforeach ?>
	<div class="notasImagenes">
		<?php $total = number_format((float)$datosQuation['Quotation']['total'], 2); ?>
		<small class="notaimg">
			Las imágenes de producto son de referencia para ambientación fotográfica y pueden variar con respecto a las versiones disponibles
		</small>
		<br>
		<small class="notaimg">
			Las unidades disponibles se encuentran sujetas a la venta previa.
		</small>
		<br>
		<br>
		<small class="notaimg">
			En el caso de que la cotización esté realizada en Dólares americanos el valor en Pesos se liquida en el momento de realizar la factura con la TRM del dia de facturación
		</small>	
		<br>	
		<h4 class="totalcotiza">
			<?php if ($totalNoIva > 0): ?>
				<?php if ($datosQuation['Quotation']['total_visible'] == 1 && $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1): ?>

					<?php $totalNoIvaParts = explode(",", number_format($totalNoIva, "2",",",".")) ?>
					<?php $ivaParts = explode(",", number_format(($totalNoIva * 0.19), "2",",",".")) ?>
					<?php $totalIvaParts = explode(",", number_format(($totalNoIva * 1.19), "2",",",".")) ?>

					Subtotal COP: <b>$<?php echo $totalNoIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalNoIvaParts[1] ?></span> </b>  <br>

					IVA 19%:  $<?php echo $ivaParts[0] ?><span class="decimales simpledecimal">,<?php echo $ivaParts[1] ?></span> <br>

					Total IVA incluido:  $<?php echo $totalIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalIvaParts[1] ?></span> 

				<?php else: ?>
					<?php echo $this->Utilities->total_visible_quotations(number_format((float)$totalNoIva, 2, ",","."),$datosQuation['Quotation']['total_visible']); ?> <?php echo $datosQuation['Quotation']['total_visible'] == "1" ? " COP" : "" ?>
				<?php endif ?>
			<?php endif ?>
			<?php if ($totalNoIvaUSD > 0): ?>
				<?php if ($datosQuation['Quotation']['total_visible'] == 1 && $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1): ?>

					<?php $totalNoIvaParts = explode(",", number_format($totalNoIvaUSD, "2",",",".")) ?>
					<?php $ivaParts = explode(",", number_format(($totalNoIvaUSD * 0.19), "2",",",".")) ?>
					<?php $totalIvaParts = explode(",", number_format(($totalNoIvaUSD * 1.19), "2",",",".")) ?>

					Subtotal USD: <b>$<?php echo $totalNoIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalNoIvaParts[1] ?></span> </b>  USD <br>

					IVA 19%:  $<?php echo $ivaParts[0] ?><span class="decimales simpledecimal">,<?php echo $ivaParts[1] ?></span>  USD <br>

					Total IVA incluido:  $<?php echo $totalIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalIvaParts[1] ?></span>  USD


				<?php else: ?>
					<br>
					<?php echo $this->Utilities->total_visible_quotations(number_format((float)$totalNoIvaUSD, 2, ",","."),$datosQuation['Quotation']['total_visible']); ?> <?php echo $datosQuation['Quotation']['total_visible'] == "1" ? " USD" : "" ?>
				<?php endif ?>
			<?php endif ?>
		</h4>
	</div>
	<hr>
	
	<?php if (!in_array($datosQuation['Quotation']['notes_description'],$arrayVarcharPermitido)) : ?>
		<p><?php echo $datosQuation['Quotation']['notes_description'] ?></p>
		<hr>
	<?php endif ?>
	<?php if (!in_array($datosQuation['Quotation']['conditions'],$arrayVarcharPermitido)) : ?>
		<br>
		<div class="conditionsview condiciones_negociacion">
			<h2>CONDICIONES DE LA NEGOCIACIÓN:</h2>
			<p><?php echo $datosQuation['Quotation']['conditions'] ?></p>
		</div>
		<br>
		<br>
	<?php endif ?>
	<b>Cordial saludo,</b>
	<div class="datasesorview">
		<?php if (!is_null($datosUsuario["User"]["img_signature"])): ?>
			<?php echo $this->Html->image('/img/users/signatures/'.$datosUsuario["User"]["img_signature"],array('class' => 'images-footer')) ?>
		<?php else: ?>
			<b class="firmaUsuario"><?php echo mb_strtoupper($datosUsuario['User']['name']) ?></b><br>
			<b class="firmaUsuario"><?php echo $datosUsuario['User']['role'] ?></b><br>
			<?php echo 'CEL: '.$datosUsuario['User']['cell_phone'] ?><br>
			<?php echo 'TEL: '.$datosUsuario['User']['telephone'] ?><br>
			<?php echo $datosUsuario['User']['email'] ?></p>
		<?php endif; ?>
	</div> 
	<br>
	<?php echo $this->Html->image('/img/header/miniatura/'.$datosHeaders['Header']['img_small'],array('class' => 'images-footer')) ?>
</div>