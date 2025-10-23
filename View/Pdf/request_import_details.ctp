<?php $part = "of"; ?>
<?php if ($currency == "cop"): ?>
	<?php setlocale(LC_TIME, 'es_ES'); ?>	
	<?php $part = "de"; ?>
<?php endif ?>
<?php $iva = $import["Import"]["iva"] == 1 ? 1.19 : 1; ?>
<?php $ivaPesos = $import["Import"]["iva"] == 1 ? 1.19 : 0; ?>
<?php $arrayVarcharPermitido = array('','<br>'); ?>

	<div class="divContenedor2">
		<table cellpadding="0" cellspacing="0" class="tablageneral" >
			<tr>
				<td style="width: 300px">
					<img src="<?php echo WWW_ROOT.'/img/assets/logoproveedor.jpg' ?>">
					<h2 class="reseth2"><b> <?php echo $currency == "cop" ? "Linea Gratuita" : "Toll-Free" ?> <?php echo Configure::read("COMPANY.CALL_FREE_NUMBER") ?></b></h2>
					<h2 class="reseth2"><b> <?php echo $currency == "cop" ? "Correo Electrónico" : "e-mail" ?>: <?php echo AuthComponent::user("email") ?></b></h2>
				</td>	
				<td style="width: 400px">
					<br><br>
					<h2 class="reseth2"><b><?php echo Configure::read("COMPANY.NAME") ?></b></h2>
					<h2 class="reseth2"><b><?php echo Configure::read("COMPANY.NIT") ?></b></h2>
					<h2 class="reseth2"><b><?php echo Configure::read("COMPANY.ADDRESS") ?></b></h2>
					<h2 class="reseth2"><b><?php echo Configure::read("COMPANY.CITY") ?></b></h2>
					<br><br>
					<h2 class="reseth2">
						<?php echo $currency == "cop" ? "ORDEN DE COMPRA No." : "PURCHASE ORDER No." ?> <span style="color: red !important"><?php echo $import["Import"]["purchase_order"] ?></span>
					</h2>				
				</td>
			</tr>
		</table>
	</div>

	<div class="divContenedor2">
		<table cellpadding="5" cellspacing="0" class="tablageneral table-bordered" style="border-collapse: collapse;">
				<tr>
					<th style="background-color: #e1e2e4"><?php echo $currency == "usd" ? "PROVIDER" : "PROVEEDOR" ?></th>
					<td ><?php echo $brandInfo["Brand"]["social_reason"] ?></td>
					<th  ><?php echo $currency == "usd" ? "COMMENTS" : "OBSERVACIONES" ?></th>
				</tr>
				<tr>
					<th><?php echo $currency == "usd" ? "I.D #" : "NIT" ?></th>
					<td><?php echo $brandInfo["Brand"]["dni"] ?></td>
					<td>
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
					<th style="background-color: #e1e2e4"><?php echo $currency == "usd" ? "PHONE" : "TELÉFONO" ?></th>
					<th><?php echo $currency == "usd" ? "CITY" : "CIUDAD" ?></th>
					<th><?php echo $currency == "usd" ? "ADDRESS" : "DIRECCIÓN" ?></th>
				</tr>
				<tr>
					<td><?php echo $brandInfo["Brand"]["phone"] ?></td>
					<td><?php echo $brandInfo["Brand"]["city"] ?></td>
					<td><?php echo $brandInfo["Brand"]["address"] ?></td>
				</tr>
				<tr>
					<th style="background-color: #e1e2e4">CONCTACT NAME</th>
					<td><?php echo $brandInfo["Brand"]["contact_name"] ?></td>
					<th><?php echo $currency == "usd" ? "QUOTE #" : "COTIZACIÓN" ?> <?php echo $import["Import"]["quotation_num"] ?></th>
				</tr>
		</table>
	</div>


	<div class="divContenedor2">
		<table cellpadding="5" cellspacing="0" class="tablageneral table-bordered" style="border-collapse: collapse;">

				<tr>
					<th><?php echo $currency == "usd" ? "ORDER DATE" : "FECHA ELABORACIÓN" ?></th>
					<th><?php echo $currency == "usd" ? "MAX DELIVERY DATE" : "FECHA MÁXIMA DE ENTREGA" ?></th>
					<th><?php echo $currency == "usd" ? "ORDERED BY" : "ELABORADO POR" ?></th>
					<th><?php echo $currency == "usd" ? "PAYMENT METHOD" : "FORMA DE PAGO" ?></th>
				</tr>
				<tr>
					<td style="border-spacing: 0px !important; border-style:none;"><?php echo strtoupper(strftime("%A, %d $part %B $part %Y", strtotime(date("Y-m-d h:i:s")))) ?> </td>
					<td style="border-spacing: 0px !important; border-style:none;"><?php echo strtoupper(strftime("%A, %d $part %B $part %Y", strtotime(date("Y-m-d h:i:s")))) ?> </td>
					<td style="border-spacing: 0px !important; border-style:none;">
						<?php echo strtoupper(AuthComponent::user("name")) ?>
					</td>
					<td style="border-spacing: 0px !important; border-style:none;"><?php echo $import["Import"]["payment"] ?></td>
				</tr>
		</table>
	</div>

	<div class="divContenedor2">
		<table cellpadding="5" cellspacing="0" class="tablageneral table-bordered" style="border-collapse: collapse;">
				<tr>
					<th style="background-color: #e1e2e4"><?php echo $currency == "usd" ? "QUANTITY" : "CANTIDAD" ?></th>
					<th style="background-color: #e1e2e4"><?php echo $currency == "usd" ? "IMAGE" : "IMAGEN" ?></th>
					<th style="background-color: #e1e2e4"><?php echo $currency == "usd" ? "DESCRIPTION" : utf8_decode("DESCRIPCIÓN") ?></th>
					<th style="background-color: #e1e2e4"><?php echo $currency == "usd" ? "CODE" : utf8_decode("CÓDIGO") ?></th>
					<?php if ($currency == "cop"): ?>
						<th style="background-color: #e1e2e4">IVA</th>
					<?php endif ?>
					<th style="background-color: #e1e2e4"><?php echo $currency == "usd" ? "Unit Net Price" : "Costo Pesos Un." ?></th>
					<th style="background-color: #e1e2e4">TOTAL <?php echo $currency == "cop" ? "Pesos" : "USD"; ?></th>
				</tr>
				<?php $total = 0; ?>
				<?php foreach ($importaciones as $key => $value): ?>
					<?php if ($value["ImportProduct"]["quantity"] == 0): ?>
						<?php continue; ?>
					<?php endif ?>
					<tr>
						<td><?php echo $value["ImportProduct"]["quantity"] ?></td>
						<td>
							<?php $ruta = $this->Utilities->validate_image_products($value["Product"]['img']);?>
							<img style="margin-right: 5px !important" src="<?php echo WWW_ROOT.'/img/products/'.$ruta ?>" width="50px" height="40px">
						</td>
						<td>
							<span>
								<?php echo $this->Text->truncate(h(str_replace('"', '', $value["Product"]["name"])), 38,array('ellipsis' => '...','exact' => false)) ?>
							</span>
							<br><b><?php echo $currency == "usd" ? "Brand" : "Marca" ?>: </b><?php echo $value["brand"] ?>
						</td>
						<td><?php echo $value["Product"]["part_number"] ?></td>
						<?php if ($currency == "cop"): ?>
							<td>
								<?php echo $import["Import"]["iva"] == 1 ? "19%" : "N/A" ?>
							</td>
						<?php endif ?>
						<td style="text-align: right;"><?php echo number_format($value["ImportProduct"]["price"],2,",",".") ?></td>
						<td style="text-align: right;"><?php echo $currency == "usd" ? number_format( ($value["ImportProduct"]["price"] * $value["ImportProduct"]["quantity"]) ,2,",",".") : number_format( ($value["ImportProduct"]["price"] * $value["ImportProduct"]["quantity"])*$iva ,2,",",".") ?>
							<?php $total+=($value["ImportProduct"]["price"] * $value["ImportProduct"]["quantity"]);  ?> $</td>
					</tr>
					<?php if (!empty($value["Product"]["details"])): ?>
						<tr>
							<td colspan="<?php echo $currency == "cop" ? "7" : "6" ?>">
								Detalle de solicitudes por producto
							</td>
						</tr>
						<?php foreach ($value["Product"]["details"] as $keyDetails => $valueDetail): ?>
							<tr>
								<td colspan="<?php echo $currency == "cop" ? "7" : "6" ?>" style="text-align: left !important;">
									<span>
										<b>Tipo de solicitud: </b> 
										<?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$valueDetail["ImportRequestsDetail"]["type_request"]) ?> - 
										<b> 
											<?php echo utf8_decode("Razón: ") ?>  </b> 
											<?php echo $valueDetail["ImportRequestsDetail"]["description"] ?> 
									</span>
									<?php if (!empty($valueDetail["ImportRequestsDetail"]["prospective_user_id"])): ?>
										<p><b>Flujo</b>: <?php echo $valueDetail["ImportRequestsDetail"]["prospective_user_id"] ?> - <b>Cliente:</b>
											<?php if(!empty($valueDetail["ImportRequestsDetail"]["prospective_user_id"])): ?>
												
												<?php echo utf8_decode($this->Utilities->name_prospective($valueDetail["ImportRequestsDetail"]["prospective_user_id"],true) ) ?>

											<?php endif; ?>
										</p>
									<?php endif ?>
									
									<p><b>Cantidad:</b><?php echo $valueDetail["ImportProductsDetail"]["quantity"] ?> - 
									<b>Fecha solicitud: </b>
										<?php $fecha = $valueDetail["ImportRequestsDetail"]["created"]; ?>

										<?php echo $valueDetail["ImportProductsDetail"]["created"] ?>
									</p>
									<?php if (!empty($valueDetail["ImportProductsDetail"]["delivery"])): ?>
									<p><b>Tiempo de entrega: </b><?php echo $valueDetail["ImportProductsDetail"]["delivery"] ?> - 
										<b>Tiempo restante: </b>
																	
											<?php $fecha = $this->Utilities->calculateFechaFinalEntrega($fecha,Configure::read("variables.entregaProductValues.".$valueDetail["ImportProductsDetail"]["delivery"]));
											$dataDay = $this->Utilities->getClassDate($fecha); ?>
											<?php if ($dataDay == 0): ?>
												<span class="bg-danger text-white">
													Se debe entregar hoy el producto
												</span>
											<?php elseif($dataDay > 0): ?>
												<span class="bg-danger text-white">
													Se debío entregar hace <?php echo $dataDay ?> día(s) en la fecha: <?php echo date("Y-m-d",strtotime("-".$dataDay." day")) ?>
												</span>
											<?php elseif($dataDay <= -5): ?>
												<span class="bg-success text-white">El producto se debe entregar dentro de  <?php echo abs($dataDay) ?> día(s) en la fecha: <?php echo date("Y-m-d",strtotime("+".abs($dataDay)." day")) ?></span>
											<?php else: ?>
												<span class="bg-warning text-white">El producto se debe entregar dentro de  <?php echo abs($dataDay) ?> día(s) en la fecha: <?php echo date("Y-m-d",strtotime("+".abs($dataDay)." day")) ?></span>
											<?php endif ?>

									</p>
									<?php endif ?>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>
				<?php endforeach ?>
		</table> 
	</div>

	<div class="divContenedor2">
		<table cellpadding="5" cellspacing="0" class="tablageneral table-bordered" style="border-collapse: collapse;">
			<?php if ($currency == "cop"): ?>
				<tr>
					<td colspan="5">Valor en letras: <br> <b><?php echo strtoupper( utf8_decode($letras))  ?> PESOS</b></td>
				</tr>
			<?php endif ?>

			<tr>
				<td colspan="1" rowspan="<?php echo $currency == "usd" ? "3" : "3" ?>" style="vertical-align: top">
					<?php echo $currency == "cop" ? "Aprobado por:" : "Approved By"; ?><br>
						<img src="https://crmcdn.kebco.co/img/firmakenneth.png" style="width: 100px">
				</td>
				<td colspan="2" rowspan="<?php echo $currency == "usd" ? "3" : "3" ?>" style="vertical-align: top">
					<?php echo $currency == "cop" ? "Verificado por: " : "Verified By"; ?>
				</td>
				<th>SUBTOTAL <?php echo strtoupper($productForBrand["ImportProduct"]["currency"]) ?>
					<span style="color: white !important; font-size: 1px">.</span>
				</th>
				<th>$ <?php echo number_format( $total ,2,",",".") ?>
					<span style="color: white !important; font-size: 1px">.</span>
				</th>
			</tr>
			<tr>
				<th><?php echo $currency == "cop" ? "Descuento" : "DISCOUNT"; ?></th>
				<th>$ 0.00 </th>
			</tr>
			<?php if ($currency == "cop"): ?>
				<tr>
					<th>IVA PESOS</th>
					<th>$ <?php echo number_format( ($total * $ivaPesos) ,2,",",".") ?></th>
				</tr>
			<?php endif ?>
			<tr>
				<th colspan="<?php echo $currency == "cop" ? "3" : "1" ?>">TOTAL <?php echo $currency == "cop" ? "PESOS" : "USD" ?></th>
				<th>$ <?php echo $currency == "usd" ? number_format( $total ,2,",",".") : number_format( ($total * $iva) ,2,",",".")  ?></th>
			</tr>
		</table> 
	</div>

	<br>
	<br>
	<br>
	<br>
	<br>
	<img src="<?php echo WWW_ROOT.'/img/footerpdf.png' ?>" class="images-footer">