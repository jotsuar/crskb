<?php if (!$country): ?>
	
	<div>
		<div class="divContenedor2">
			<table cellpadding="0" cellspacing="0" class="tableproductsst" >
					<tr>
						<td style="padding: 0px;">
							<div class="center">
								<?php echo $this->Html->image('/img/assets/logoproveedor.jpg',array('class' => 'imgFull')) ?>
							</div>
							<p style="font-size: 10px !important; margin-left: 30px;"><b>ACT. ECONÓMICA 4659. RESPONSABLE DE IVA</b> <br>NO AUTORRETENEDOR. NO GRAN CONTRIBUYENTE</p></p>
							<p>
						</td>	
						<td style="width: 10px">
							
						</td>	
						<td style="width: 300px;">
							<p style="display: block; text-align: center; font-size: smaller; color: #000;">
								<b><?php echo Configure::read("COMPANY.NAME") ?></b> <br>
								Nit: <?php echo Configure::read("COMPANY.NIT") ?>
								<br>Calle 10 No. 52A - 18, Bodega 104 ::: Teléfono: (4) 448 5566
							</p>
							<table cellpadding="0" cellspacing="0" class="tablageneral tablaTr"  border="1">
								<tr>
									<td colspan="2" style="text-align: center; background-color: #ddd;" >
										FACTURA PROFORMA
									</td>
								</tr>
								<tr>
									<td colspan="2" style="text-align: center;">
										<b>
											<?php echo $currency == "cop" ? $quotation["Quotation"]["proforma"] : $quotation["Quotation"]["proforma_usd"] ?>
										</b>
									</td>
								</tr>
								<tr>
									<td style="text-align: center;">
										<b>EMISIÓN</b>
									</td>
									<td style="text-align: center;">
										<b>VENCIMIENTO</b>
									</td>
								</tr>
								<tr>
									<td style="text-align: center;">
										<?php echo date("Y-m-d") ?>
									</td>
									<td style="text-align: center;">
										<?php echo $this->request->query["fecha_limite"] ?>
									</td>
								</tr>
								<tr>
									<td style="text-align: center;">
										<b>FORMA DE PAGO</b>
									</td>
									<td style="text-align: center;">
										<?php echo $this->request->query["payment"] ?>
									</td>
								</tr>
							</table>
			
						</td>
					</tr>
			</table>
			<br><br>
			<br><br>

			
		</div>
		
	</div>
	<div>
		<table cellpadding="0" cellspacing="0" class="tablageneral tablaTr" style="border: 1px #000 solid;"  border="1" >
				<tr>
					<th style="text-align: left; width: 100px">
						CLIENTE
					</th>
					<td colspan="4" style="color: #000;">
						<span><?php echo $this->request->query["name"] ?></span>
					</td>
				</tr>
				<tr>
					<th style="text-align: left; width: 100px; color:#000;">
						SUCURSAL
					</th>
					<td>
						
					</td>
					<th style="text-align: left; color:#000;">
						NIT
					</th>
					<td style="color: #000">
						<span><?php echo $this->request->query["identification"] ?></span>
					</td>
					<th style="text-align: center;">
						ORDEN DE COMPRA CLIENTE
					</th>
				</tr>
				<tr>
					<th style="text-align: left; width: 100px;color: #000">
						DIRECCIÓN
					</th>
					<td colspan="3" style="color: #000">
						<span><?php echo $this->request->query["address"] ?></span>
					</td>
					<td style="color: #000; text-align: center;">
						<span><?php echo empty($this->request->query["nro_orden"]) ? "" : $this->request->query["nro_orden"] ?></span>						
					</td>
				</tr>
				<tr>
					<th style="text-align: left; width: 100px;color: #000">
						CIUDAD
					</th>
					<td style="color: #000">
						<span><?php echo $this->request->query["city"] ?></span>
					</td>
					<th style="text-align: left;">
						TELÉFONO
					</th>
					<td style="color: #000">
						<span><?php echo $this->request->query["telephone"] ?></span>
					</td>
					<th style="text-align: center;">
						VENDEDOR
					</th>
				</tr>
				<tr>
					<th style="text-align: left; width: 100px; color: #000">
						CONTACTO
					</th>
					<td colspan="3" style="color: #000; ">
						<span><?php echo empty($this->request->query["contacto"]) ? "" : $this->request->query["contacto"] ?></span>
					</td>
					<td style="text-align: center;color: #000">
						<?php echo $quotation["User"]["name"] ?>
					</td>
				</tr>
		</table>
	</div>
	<br>
	<br>
	<br>
	<div style="min-height: 500px; height: 500px;">
		<table cellpadding="0" cellspacing="0"  class="tablageneral tablaTr" >
			<tr style="border: 1px solid;">
				<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
					Cant.
				</th>
				<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
					Descripción.
				</th>
				<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
					Referencia
				</th>
				<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
					Unid.
				</th>
				<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
					Valor unitario
				</th>
				<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
					IVA
				</th>
				<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
					Total
				</th>
			</tr>
			<?php $totalIVa 	= 0; ?>
			<?php foreach ($products as $key => $value): ?>
				<tr style="border: none">
					<td style="border: none !important;text-align: center; color: #000"><?php echo $value["QuotationsProduct"]["quantity"] ?></td>
					<td style="border: none !important;text-align: center; color: #000"><?php echo strtoupper($value["Product"]["name"]) ?></td>
					<td style="border: none !important;text-align: center; color: #000"><?php echo strtoupper($value["Product"]["part_number"]) ?></td>
					<td style="border: none !important;text-align: center; color: #000">Und. </td>
					<td style="border: none !important;text-align: center; color: #000">$ <?php echo number_format($value["QuotationsProduct"]["price"],2,",",".") ?></td>
					<td style="border: none !important;text-align: center; color: #000"> <?php echo $value["QuotationsProduct"]["iva"] == 1 ? "19%" : "0%"  ?> </td>
					<td style="border: none !important;text-align: center; color: #000">
						$ <?php echo number_format($value["QuotationsProduct"]["price"]*$value["QuotationsProduct"]["quantity"],2,",",".") ?>
						<?php 
							
							if ($value["QuotationsProduct"]["iva"] == 1) {
								$totalIVa += ( $value['QuotationsProduct']['quantity'] * $value['QuotationsProduct']['price'] );
							}

						?>
					</td>
				</tr>
			<?php endforeach ?>
		</table>
	</div>	


	<div>
		<table cellpadding="0" cellspacing="0" class="tablageneral tablaTr" style="border: 1px #000 solid;"  border="1" >
			<tr>
				<th rowspan="2" style="text-align: left; width: 380px">
					OBSERVACIONES: <?php echo empty($this->request->query["observaciones"]) ? "***" : $this->request->query["observaciones"] ?>
				</th>
				<th>
					SUBTOTAL
				</th>
				<th>
					$<?php echo number_format($total,2,",",".") ?>
				</th>
			</tr>
			<tr>
				<th>
					DESCUENTO
				</th>
				<th>
					$ <?php echo number_format($descuento,2,",",".") ?>
				</th>
			</tr>
			<tr>
				<th rowspan="2" style="text-align: left; width: 380px">
					SON: <?php echo strtoupper($letras) ?> <?php echo $currency == "cop" ? "PESOS" : "" ?>
				</th>
				<th>
					IVA
				</th>
				<th>
					$ <?php echo number_format(($totalIVa-$descuento)*0.19,2,",",".") ?>
				</th>
			</tr>
			<tr>
				<th>
					TOTAL DOCUMENTO
				</th>
				<th>
					$ <?php echo number_format(($total-$descuento)+(($totalIVa-$descuento)*0.19),2,",",".") ?>
				</th>
			</tr>
			<tr>
				<th colspan="3" style="padding: 5px;">
					Titular de la Cuenta: KEBCO S.A.S. Cuenta Corriente BANCOLOMBIA 029-719631-94, Convenio 44103
					Enviar soporte de pago al correo contabilidad@almacendelpintor.com

				</th>
			</tr>
			<tr>
				<td colspan="3">
					<table cellpadding="0" cellspacing="0" class="tablageneral tablaTr" style="border: 0px #000 solid;"  border="1">
						<tr>
							<th style="padding-bottom: 30px;">
								APROVADO
							</th>
							<th style="padding-bottom: 30px;">
								REVISADO
							</th>
						</tr>
					</table>
				</td>
			</tr>	
		</table>

	</div>


	<style>
		table > tr > th {
			color: #000 !important;
		}
	</style>
<?php else: ?>
	<header>
		<div class="divContenedor2">
		<table cellpadding="0" cellspacing="0" class="tableproductsst" >
				<tr>
					<td style="padding: 0px;">
						<div class="center">
							<?php echo $this->Html->image('/img/assets/logoproveedor.jpg',array('class' => 'imgFull')) ?>
						</div>
					</td>	
					<td style="width: 10px">
						
					</td>	
					<td style="width: 300px;">
						<p style="display: block; text-align: right; font-size: 14px; color: #000;">
							<b>FACTURA PROFORMA <?php echo $currency == "cop" ? $quotation["Quotation"]["proforma"] : $quotation["Quotation"]["proforma_usd"] ?></b> 
							<p style="display: block; text-align: right; font-size: smaller; color: #000;">
								<b>Fecha de la factura: </b> <?php echo date("Y-m-d H:i:s") ?> <br>
								<b>Fecha de vencimiento: </b> <?php echo date("Y-m-d H:i:s",strtotime($this->request->query["fecha_limite"]." ".date("H:i:s"))) ?> <br>
							</p>
						</p>
					</td>
				</tr>
		</table>
		<br><br>
		<br><br>

		
	</div>
		
	</header>
	<section>
		<table cellpadding="0" cellspacing="0" class="tablageneral tablaTrs" style=""  >
				<tr>
					<td style="width: 250px;">
						<p style="display: block; text-align: left; font-size: smaller; color: #000; ">
							<b>De:</b>
						</p>
						<table cellpadding="0" cellspacing="0" class="tablageneral tablaTr" >
							<tr>
								<td colspan="2" style="text-align: left; background-color: #ddd; padding-left: 5pt; height: 160px" >
									<p style="margin-left:10px">
										<b>Kebco LLC</b><br>
										<span>TAX-ID: 84-3642427</span><br>
										<span>7085 NW 50 Street Miami, Fl 33166</span><br>
										<span>, Florida</span><br>
										<span>Estados Unidos</span><br>
										<span>Teléfono: +1 305 509 9893</span><br>
										<span>Correo: ventas@kebco.co</span><br>
										<span>Web: https://kebco.co/</span>
									</p>
								</td>
							</tr>
						</table>
		
					</td>

					<td style="width: 80px">
						
					</td>

					<td style="width: 250px;">
						<p style="display: block; text-align: left; font-size: smaller; color: #000;">
							<b>A:</b>
						</p>
						<table cellpadding="0" cellspacing="0" class="tablageneral tablaTr" border="1">
							<tr>
								<td colspan="2" style="text-align: left; background-color: #fff; height: 160px;" >
									<p style="margin-left:10px">
										<span style="text-transform: uppercase">
											<b><?php echo $this->request->query["name"] ?></b>
										</span><br>
										<span><?php echo $this->request->query["address"] ?></span><br>
										<span><?php echo $this->request->query["city"] ?></span><br>
										<span><?php echo $this->request->query["telephone"] ?></span><br>
										<span><?php echo $quotation["ProspectiveUser"]["country"] ?></span><br>
										<span>DNI:<?php echo $this->request->query["identification"] ?></span><br>
									</p>
								</td>
							</tr>
						</table>		
					</td>
				</tr>
		</table>
	</section>
	<br>
	<div style="min-height: 600px; height: 600px;">
		<table cellpadding="0" cellspacing="0"  class="tablageneral tablaTr" >
				<tr>
					<td colspan="5">
						<p style="display: block; text-align: right; font-size: smaller; color: #000;">
							Importes visualizados en Dólares USA
						</p>
					</td>
				</tr>
				<tr style="border: 1px solid;">
					
					<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
						Descripción.
					</th>
					<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
						IVA
					</th>
					
					<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
						P.U.
					</th>
					<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
						Cant.
					</th>
					
					<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000">
						Total
					</th>
				</tr>
				<?php foreach ($products as $key => $value): ?>
					<tr style="border: 1px solid">
						
						<td style="border: 1px solid !important;text-align: left; color: #000; width: 400px;">
							<p style="margin-top: 5px; margin-left: 10px;">								
								<?php echo strtoupper($value["Product"]["part_number"]) ?><br>
								<?php echo strtoupper($value["Product"]["name"]) ?>
							</p>
						</td>
						<td style="border: 1px solid !important;text-align: right; color: #000; ">
							<?php echo empty($this->request->query["iva"]) ? "0" : str_replace("1.0", "", $this->request->query["iva"]) ?>%
						</td>
						<td style="border: 1px solid !important;text-align: right; color: #000; ">$ 
							<?php echo number_format($value["QuotationsProduct"]["price"],2,",",".") ?>
						</td>
						<td style="border: 1px solid !important;text-align: right; color: #000; width: 30px;"><?php echo $value["QuotationsProduct"]["quantity"] ?></td>
						<td style="border: 1px solid !important;text-align: right; color: #000; width: 80px; ">$ 
							<?php echo number_format($value["QuotationsProduct"]["price"],2,",",".") ?>
						</td>
					</tr>
				<?php endforeach ?>
				<tr>
					<td colspan="4">
						<p style="display: block; text-align: left; font-size: 12px; color: #000;">
							<span style="display: block; text-align: right; font-size: 12px; color: #000;">
									Total (Base imp).
							</span> 
						
						<?php if (!empty($this->request->query["iva"])): ?>
							<br>
							<span style="display: block; text-align: right; font-size: 12px; color: #000;">
								IVA.
							</span>
						<?php endif ?>
						<br>
						<span style="display: block; background-color: #ddd; text-align: right ;">Total</span>

						</p>
					</td>
					<td colspan="1">
						<p style="display: block; text-align: right; font-size: 12px; color: #000; width: 100%;">
							<span><?php echo number_format($total,2,".",",") ?></span>
							<?php if (!empty($this->request->query["iva"])): ?>
								<br>
								<span style="display: block; text-align: right; font-size: 12px; color: #000;">
									<?php echo number_format($total* ($this->request->query["iva"] - 1) ,2,".",",") ?>
								</span>
							<?php endif ?>
							<br>
							<span style="background-color: #ddd; text-align: right;">
								<?php if (!empty($this->request->query["iva"])): ?>
									<?php echo number_format($total* ($this->request->query["iva"]) ,2,".",",") ?>
								<?php else: ?>
									<?php echo number_format($total ,2,".",",") ?>
								<?php endif ?>
							</span>
						</p>
					</td>
				</tr>
		</table>
		<br>
		<table cellpadding="0" cellspacing="0"  class="tablageneral tablaTr" >
				<tr>
					<td style="width: 150px">
						<p style="display: block; text-align: left; font-size: smaller; color: #000;">
							<b>Condiciones de pago:</b>
						</p>
						<br>
						<p style="display: block; text-align: left; font-size: smaller; color: #000;">
							<b>Freight Terms: </b>
						</p>
					</td>
					<td>
						<p style="display: block; text-align: left; font-size: smaller; color: #000;">
							<?php echo $this->request->query["payment"] ?>
						</p>
						<br>
						<p style="display: block; text-align: left; font-size: smaller; color: #000;">
							<?php echo $this->request->query["shipping"] ?>
						</p>
					</td>
					<td>
						
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>
						
					</td>
				</tr>
		</table>
		<br>
		<table cellpadding="0" cellspacing="0"  class="tablageneral tablaTr" >
				<tr>
					<td colspan="5">
						<p style="display: block; text-align: left; font-size: smaller; color: #000;">
							<span>
								<b>Pago mediante transferencia a la cuenta bancaria siguiente:</b>
							</span> <br>
							<span>
								Banco: Bank of America, N.A.
							</span>
						</p>
						<p style="display: block; text-align: left; font-size: smaller; color: #000; width: 300px;">
							<span style="border-left: 1px solid; border-right: 1px solid; padding: 10px;">
								Código  Bancario   
							</span>

							<span style=" padding: 2px; ">
								<b>Número de cuenta: </b>  8981 0747 0088 
							</span>
						</p>
						<p style="display: block; text-align: left; font-size: smaller; color: #000;">
							<span>
								Dirección: P.O. Box 25118
							</span><br>
							<span>
								Tampa, FL 33622-5118
							</span><br>
							<span>
								Nombre del titular de la cuenta: KEBCO LLC
							</span><br>
							<span>
								<b>Número de cuenta IBAN: 0260 0959 3</b>
							</span><br>
							<span>
								<b>Código BIC/SWIFT: BOFAUS3N</b>
							</span>
						</p>
					</td>
				</tr>
				<tr>
					<td>
						
					</td>
				</tr>
		</table>
		
	</div>
	<footer style="position: absolute; bottom: 0;">
			<p style="text-align: center">
				FEIN: 84-3642427
			</p>
		</footer>
<?php endif ?>