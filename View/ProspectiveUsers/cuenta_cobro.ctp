<div class="row">
	<div class="col-md-6 pl-5">
		<img src="/CRM/files/logoProveedor.png" alt="Logo Proveedor" class="img-fluid mb-2 w-75">

		<h2 class="text-mobile"><b> Linea Gratuita  018000 425700</b></h2>
	</div>
	<div class="col-md-6">
		<h3 class="strongtittle spacetop text-mobile">KEBCO S.A.S.</h3>
		<h3 class="strongtittle text-mobile">900412283 - 0</h3>
		<h3 class="text-mobile"><b>CALLE 10 # 52A - 18 INT 104</b></h3>
		<h3 class="text-mobile"><b>PBX (4) 448 5566</b></h3>
	</div>
	<div class="col-md-12 mt-4 table-responsive">
		<table class="table table-bordered" id="orderproveedor">
			<tbody>
				<tr class="text-center">
					<th>Fecha: </th>
					<td>
						<?php echo date("Y-m-d") ?>
					</td>
				</tr>
				<tr class="text-center">
					<th>Código cuenta de cobro: </th>
					<td>CBKEB <span class="text-danger">#BORRADOR</span></td>
				</tr>
				<tr class="text-center">
					<th>Banco </th>
					<td> <?php echo empty(AuthComponent::user("bank")) ? "NO SE ESPECIFICA BANCO (Configurar en el perfil para que séa valida la cuenta)" : AuthComponent::user("bank") ?> </td>
				</tr>
				<tr class="text-center">
					<th>Número de cuenta</th>
					<td><?php echo empty(AuthComponent::user("account_number")) ? "NO SE ESPECIFICA NÚMERO DE CUENTA (Configurar en el perfil para que séa valida la cuenta)" : AuthComponent::user("account_number") ?></td>
				</tr>
				<tr class="text-center">
					<th>Tipo de cuenta</th>
					<td><?php echo empty(AuthComponent::user("account_type")) ? "NO SE ESPECIFICA TIPO DE CUENTA (Configurar en el perfil para que séa valida la cuenta)" : AuthComponent::user("account_type") ?></td>
				</tr>
			</tbody>
		</table>
		<h2 class="text-center my-2"> Detalle de comisiones </h2>
		<table class="table table-bordered <?php echo empty($sales) ? "" : "datosPendientesDespacho" ?>  table-hovered">
				<tbody>						
					<?php if (empty($sales)): ?>
						<tr>
							<td class="text-center">
								<p class="text-danger mb-0">No existen registros de facturación</p>
							</td>
						</tr>
					<?php else: ?>
						<?php $totalPagar = 0; foreach ($sales as $key => $value): ?>
							<tr>
								<th class="pt-5">
									<?php echo $key+1; ?>
								</th>
								<td class="p-1">
									<div class="row">
										<div class="col-md-4">
											<ul class="list-unstyled">
												<li><b>Flujo:</b>  <?php echo $value["ProspectiveUser"]["id"] ?> </li>
												<li><b>Cliente:</b> <?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?></li>
												<li><b>Factura:</b> <?php echo $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_code") ?> <?php echo $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date") ?> </li>
												<li><b>Valor factura: </b>$ <?php echo number_format($this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_value"),0,".",",") ?></li>
												
											</ul>
										</div>
										<div class="col-md-4">
											<ul class="list-unstyled">
												
												<li><b>Recibo: </b><?php echo $value["Receipt"]["code"] ?></li>
												<li><b>Fecha pago: </b><?php echo $this->Utilities->date_castellano($value["Receipt"]["date_receipt"]) ?></li>
												<li><b>Valor pago: </b>$ <?php echo number_format($value["Receipt"]["total"],0,".",",") ?> </li>
												<li><b>Base comisión: </b>$ <?php echo number_format($value["Receipt"]["total_iva"],0,".",",") ?></li>
											</ul>
										</div>
										<div class="col-md-4">
											<ul class="list-unstyled">
												<li><b>Días de pago: </b>
													<?php $dias = $this->Utilities->calculateDays($this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date"),$value["Receipt"]["date_receipt"]); echo $dias; ?>
												</li>
												<li><b>Porcentaje obtenido: </b> <?php $percentaje =  $this->Utilities->getComissionPercentaje($dias,$comision); echo $percentaje; ?>%</li>
												<li><b>Valor a pagar:</b>
													$ <?php
													if ($value["Valores"]["totalProductos"] == 1 && $value["Valores"]["totalSt"] > 0) {
														$value["Receipt"]["total_iva"] = $value["Valores"]["valorSt"];
													}

													$pagar 		= ($percentaje / 100) * floatval($value["Receipt"]["total_iva"] - $value["Valores"]["valorSt"]) + ($value["Valores"]["valorBySt"]) ; 
													$totalPagar += $pagar; echo number_format($pagar,0,".",",") ?>
												</li>
											</ul>
										</div>
									</div>
									
								</td>
							</tr>
						<?php endforeach ?>
						<tr>
							<td colspan="2" class="text-right pr-1"><h2>Total a pagar $<?php echo number_format($totalPagar,0,".",",") ?></h2> <br>
								
							</td>
						</tr>
						<tr>
							<td colspan="2" class="text-center">
								<?php if (empty(AuthComponent::user("bank")) || empty(AuthComponent::user("account_type")) || empty(AuthComponent::user("account_number"))): ?>
									<span class="text-center text-danger">Debes configurar los datos bancarios</span>
								<?php else: ?>
									<a href="<?php echo $this->Html->url(["action"=>"cuenta_cobro",$this->Utilities->encryptString(AuthComponent::user("id"))]) ?>" class="btn btn-success btn-block solicitarPago">
										Solicitar Pago
									</a>
								<?php endif ?>
							</td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
	</div>
</div>