<?php $part = "of"; ?>
<?php if ($currency == "cop"): ?>
	<?php setlocale(LC_TIME, 'es_ES'); ?>	
	<?php $part = "de"; ?>
<?php endif ?>
<?php $iva = $import["Import"]["iva"] == 1 ? 1.19 : 1; ?>

<meta charset='utf-8'>
<div class="col-md-12 col-lg-12">
	<div class="container-fluid ">
		<div class="row">
			<div class="col-md-6">
				<img src="<?php echo $this->Html->url("/files/logoProveedor.png") ?>" alt="Logo Proveedor" class="img-fluid mb-2">

				<h2 class="text-mobile"><b> <?php echo $currency == "cop" ? "Linea Gratuita" : "Toll-Free" ?>  <?php echo Configure::read("COMPANY.CALL_FREE_NUMBER") ?></b></h2>
				<h2 class="v"><b> <?php echo $currency == "cop" ? "Correo Electrónico" : "e-mail" ?>: <?php echo AuthComponent::user("email") ?></b></h2>
			</div>
			<div class="col-md-6">
				<h2 class="strongtittle spacetop text-mobile"><?php echo Configure::read("COMPANY.NAME") ?></h2>
				<h2 class="strongtittle text-mobile"><?php echo Configure::read("COMPANY.NIT") ?></h2>
				<h2 class="text-mobile"><b><?php echo Configure::read("COMPANY.ADDRESS") ?></b></h2>
				<h2 class="text-mobile"><b><?php echo Configure::read("COMPANY.TELCOMPANY") ?></b></h2>
				<br>
				<h2 class="text-mobile">
					<?php echo $currency == "cop" ? "ORDEN DE COMPRA No." : "PURCHASE ORDER No." ?> <?php echo $import["Import"]["purchase_order"] ?>
				</h2>
			</div>
			<div class="col-md-12 mt-4 table-responsive">
				<table class="table table-bordered" id="orderproveedor">
					<tbody>
						<tr>
							<th style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "PROVIDER" : "PROVEEDOR" ?>
							</th>
							<td colspan="2" style="border: 1px solid #cfcfcf !important;">
								<?php echo $brandInfo["Brand"]["social_reason"] ?>
							</td>
							<th colspan="<?php echo $currency == "cop" ? "3" : "2" ?>" style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "COMMENTS" : "OBSERVACIONES" ?>
							</th>
						</tr>
						<tr>
							<th style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "I.D #" : "NIT" ?>
							</th>
							<td colspan="2" style="border: 1px solid #cfcfcf !important;">
								<?php echo $brandInfo["Brand"]["dni"] ?>							
							</td>
							<td colspan="<?php echo $currency == "cop" ? "3" : "2" ?>" rowspan="3" style="border: 1px solid #cfcfcf !important;">
								<?php echo $import["Import"]["commetns"] ?> 
								<?php if(!empty($import["Import"]["address"])){
									if($currency == "usd"){
										echo "Delivery address: ".$import["Import"]["address"];
									}else{
										echo "Dirección de entrega: ".$import["Import"]["address"];
									}
								}?>
							</td>
						</tr>
						<tr>
							<th style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "ADDRESS" : "DIRECCIÓN" ?>
							</th>
							<th style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "CITY" : "CIUDAD" ?>
							</th>
							<th style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "PHONE" : "TELÉFONO" ?>
							</th>
						</tr>
						<tr>
							<td style="border: 1px solid #cfcfcf !important;">
								<?php echo $brandInfo["Brand"]["address"] ?>
							</td>
							<td style="border: 1px solid #cfcfcf !important;">
								<?php echo $brandInfo["Brand"]["city"] ?>
							</td>
							<td style="border: 1px solid #cfcfcf !important;">
								<?php echo $brandInfo["Brand"]["phone"] ?>
							</td>
						</tr>
						<tr>
							<th style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "CONCTACT NAME" : "CONTACTO" ?>
							</th>
							<td colspan="2" style="border: 1px solid #cfcfcf !important;">
								<?php echo $brandInfo["Brand"]["contact_name"] ?>
							</td>
							<th style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "QUOTE #" : "COTIZACIÓN" ?>
							</th>
							<th style="border: 1px solid #cfcfcf !important;" colspan="<?php echo $currency == "cop" ? "3" : "2" ?>">
								<?php echo $import["Import"]["quotation_num"] ?>
							</th>
						</tr>
						<tr>
							<td colspan="<?php echo $currency == "cop" ? "6" : "5" ?>" style="border-bottom: 1px solid #cfcfcf !important;"></td>
						</tr>
						<tr>
							<th style="border: 1px solid #cfcfcf !important;"><?php echo $currency == "usd" ? "ORDER DATE" : "FECHA ELABORACIÓN" ?></th>
							<th colspan="2" style="border: 1px solid #cfcfcf !important;"><?php echo $currency == "usd" ? "MAX DELIVERY DATE" : "FECHA MÁXIMA DE ENTREGA" ?></th>
							<th style="border: 1px solid #cfcfcf !important;"><?php echo $currency == "usd" ? "ORDERED BY" : "ELABORADO POR" ?></th>
							<th style="border: 1px solid #cfcfcf !important;" colspan="<?php echo $currency == "cop" ? "2" : "1" ?>"><?php echo $currency == "usd" ? "PAYMENT METHOD" : "FORMA DE PAGO" ?></th>
						</tr>
						<tr>
							<td style="border: 1px solid #cfcfcf !important;" style="border: 1px solid #cfcfcf !important;"><?php echo strtoupper(strftime("%A, %d $part %B $part %Y", strtotime(date("Y-m-d h:i:s")))) ?> </td>
							<td colspan="2" style="border: 1px solid #cfcfcf !important;" style="border: 1px solid #cfcfcf !important;"><?php echo strtoupper(strftime("%A, %d $part %B $part %Y", strtotime(date("Y-m-d h:i:s")))) ?> </td>
							<td style="border: 1px solid #cfcfcf !important;" style="border: 1px solid #cfcfcf !important;">
								<?php echo strtoupper(AuthComponent::user("name")) ?>
							</td>
							<td colspan="<?php echo $currency == "cop" ? "2" : "1" ?>" style="border: 1px solid #cfcfcf !important;" style="border: 1px solid #cfcfcf !important;">
								<?php echo $import["Import"]["payment"] ?>
							</td>
						</tr>
						<tr>
							<td colspan="5" style="border-bottom: 1px solid #cfcfcf !important;": 1px solid #cfcfcf !important;"></td>
						</tr>
						<tr class="text-center">
							<th class="text-center" style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "Quantity" : "Cantidad" ?>
							</th>
							<th class="text-center" style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "Description" : "Descripción" ?>
							</th>
							<th style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "Code" : "Código" ?>
							</th>
							<?php if ($currency == "cop"): ?>
								<th>
									IVA
								</th>
							<?php endif ?>
							<th style="border: 1px solid #cfcfcf !important;">
								<?php echo $currency == "usd" ? "Unit Net Price" : "Costo Pesos Un." ?>
							</th>
							<th style="border: 1px solid #cfcfcf !important;">
								TOTAL <?php echo $currency == "cop" ? "Pesos" : "USD"; ?>
							</th>
						</tr>
						<?php $total = 0; ?>
						<?php foreach ($import["Product"] as $key => $value): ?>
							<?php if ($value["ImportProduct"]["quantity"] == 0): ?>
								<?php continue; ?>
							<?php endif ?>
							<tr class="text-center">
								<td  style="border: 1px solid #cfcfcf !important; <?php echo $key === (count($import["Product"]) - 1) ? 'border-bottom: 1px solid #cfcfcf !important;' : "e"; ?>">
									<?php echo $value["ImportProduct"]["quantity"] ?>
								</td>



								<td style="border: 1px solid #cfcfcf !important; <?php echo $key === (count($import["Product"]) - 1) ? 'border-bottom: 1px solid #cfcfcf !important;' : "e"; ?>">
									<?php $ruta = $this->Utilities->validate_image_products($value['img']); ?>
									<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="50px" height="50px" class="imgmin-product imgtab">
									<p class="nameestyle"><?php echo $value["name"] ?> 
										<br>
										<b><?php echo $currency == "usd" ? "Brand" : "Marca" ?>: </b><?php echo $value["brand"] ?>
									</p>

								</td>


								<td style="border: 1px solid #cfcfcf !important; <?php echo $key === (count($import["Product"]) - 1) ? 'border-bottom: 1px solid #cfcfcf !important;' : "e"; ?>">
									<?php echo $value["part_number"] ?>
								</td>
								<?php if ($currency == "cop"): ?>
									<td class="text-center">
										<?php echo $import["Import"]["iva"] == 1 ? "19%" : "N/A" ?>
									</td>
								<?php endif ?>
								<td style="border: 1px solid #cfcfcf !important; <?php echo $key === (count($import["Product"]) - 1) ? 'border-bottom: 1px solid #cfcfcf !important;' : "e"; ?>">
									$ <?php echo number_format($value["ImportProduct"]["price"],2,",",".") ?>
								</td>
								<td style="border: 1px solid #cfcfcf !important; <?php echo $key === (count($import["Product"]) - 1) ? 'border-bottom: 1px solid #cfcfcf !important;' : "e"; ?>">
									$ <?php echo $currency == "usd" ? number_format( ($value["ImportProduct"]["price"] * $value["ImportProduct"]["quantity"]) ,2,",",".") : number_format( ($value["ImportProduct"]["price"] * $value["ImportProduct"]["quantity"]) * $iva ,2,",",".") ?>
									<?php $total+=($value["ImportProduct"]["price"] * $value["ImportProduct"]["quantity"]);  ?>
								</td>
							</tr>
						<?php endforeach ?>
						<tr>
							<td colspan="<?php echo $currency == "cop" ? "6" : "5" ?>" style="border-bottom: 1px solid #cfcfcf !important; border-top: 1px solid #cfcfcf !important;"> <b><?php echo $currency == "cop" ? "Valor en letras" : "" ?></b></td>
						</tr>
						<?php if ($currency == "cop"): ?>
							<td colspan="4" rowspan="1">
								<?php echo strtoupper($letras) ?> PESOS
							</td>
							<th style="border: 1px solid #cfcfcf !important;">SUBTOTAL <?php echo $currency == "cop" ? "Pesos" : "USD" ?></th>
								<th style="border: 1px solid #cfcfcf !important; text-align: right;">$ <?php echo number_format( $total ,2,",",".") ?></th>
						<?php endif ?>
						<tr>
							<td colspan="<?php echo $currency == "cop" ? "2" : "1" ?>" rowspan="<?php echo $currency == "usd" ? "3" : "4" ?>" style="border: 1px solid #cfcfcf !important;">
								<b><?php echo $currency == "cop" ? "Aprobado por:" : "Approved By"; ?></b><br>
								<img src="https://crm.kebco.co/img/firmakenneth.png" class="firmak">
							</td>
							<td colspan="2" rowspan="<?php echo $currency == "usd" ? "3" : "4" ?>" style="border: 1px solid #cfcfcf !important;">
								<b><?php echo $currency == "cop" ? "Verificado por: " : "Verified By"; ?></b>
							</td>
							<?php if ($currency == "usd"): ?>								
								<th style="border: 1px solid #cfcfcf !important;">SUBTOTAL <?php echo $currency == "cop" ? "Pesos" : "USD" ?></th>
								<th style="border: 1px solid #cfcfcf !important; text-align: right;">$ <?php echo number_format( $total ,2,",",".") ?></th>
							<?php endif ?>
						</tr>
						<tr>
							<th style="border: 1px solid #cfcfcf !important;"><?php echo $currency == "cop" ? "Descuento" : "DISCOUNT"; ?></th>
							<th style="border: 1px solid #cfcfcf !important; text-align: right;">$ 0.00</th>
						</tr>
						<?php if ($currency == "cop"): ?>
							<tr>
								<th style="border: 1px solid #cfcfcf !important;">IVA PESOS</th>
								<?php if ($import["Import"]["iva"] == 1): ?>
									<th style="border: 1px solid #cfcfcf !important; text-align: right;">$ <?php echo number_format( ($total * 0.19) ,2,",",".") ?></th>
								<?php else: ?>									
									<th style="border: 1px solid #cfcfcf !important; text-align: right;">$ <?php echo number_format(0) ?></th>
								<?php endif ?>
							</tr>
						<?php endif ?>
						<tr>
							<th style="border: 1px solid #cfcfcf !important;">TOTAL <?php echo $currency == "cop" ? "Pesos" : "USD" ?></th>
							<th style="border: 1px solid #cfcfcf !important; text-align: right;">$ <?php echo $currency == "usd" ? number_format( $total ,2,",",".") : number_format( ($total*$iva) ,2,",",".") ?></th>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()));
 ?>
