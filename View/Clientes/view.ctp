<?php echo $this->element("nav_cliente", ["cliente" => $cliente, "action" => "index"]); ?>
<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
	     <i class="fa fa-1x flaticon-growth"></i>
	    <h2 class="m-0 text-white bannerbig" >Módulo clientes CRM </h2>
	</div>
</div>
<div class="container p-0 containerCRM">
	<div class="col-md-12 p-0" id="cotizacionview">
		<div class="blockwhite">
			<div class="row">
				<div class="col-md-12 my-4">
					<div class="text-left">
					    <a href="<?php echo $this->Html->url(["controller"=>"clientes","action" => "index",]) ?>" class="btn btn-info">
					    	Listar mis ordenes de compra
					    </a>
					    <a href="<?php echo $this->Html->url(["controller"=>"clientes","action" => "index",]) ?>" class="bg-blue btn text-white contactoNormal" data-flujo="<?php echo $order["Order"]["prospective_user_id"] ?>" data-order="<?php echo h($order['Order']['prefijo']); ?>-<?php echo h($order['Order']['code']); ?>" >
					    	Quiero volver a pedir sobre esta orden
					    </a>
					</div>
<!-- 					<div class="text-right mb-4">
					    <button id="imprimeData" class="btn btn-primary ">Imprimir</button>
					</div> -->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive" id="impresionDiv">
						<table class="table table-hovered">
							<tr>
								<th colspan="2">
									<h2>Orden #: <strong><?php echo h($order['Order']['prefijo']); ?>-<?php echo h($order['Order']['code']); ?></strong> </h2> 
								</th>
								<th class="text-right" colspan="2">
									<b>Fecha de generación:</b>
									<?php echo $this->Utilities->date_castellano($order['Order']['created']); ?>
								</th>
							</tr>
							<tr>
								<th class="text-center" colspan="4">
									<h2 class="py-1">Datos del cliente</h2>
								</th>
							</tr>
							<tr>
								<th class="col-md-3">
									Tipo de cliente
								</th>
								<td class="col-md-3">
									<?php if (empty($order["Order"]["clients_natural_id"])): ?>
										Empresa
									<?php else: ?>
										Persona Natural
									<?php endif ?>
								</td>
								<th class="col-md-3">
									Cliente
								</th>
								<td class="col-md-3">
									<?php echo isset($datosCliente["legal"]) ? $datosCliente["legal"] : $datosCliente["name"] ?>
								</td>
							</tr>
							<tr>
								<th>
									Contacto
								</th>
								<td>
									<?php echo isset($datosCliente["legal"]) ? $datosCliente["name"] : "N/A" ?>
								</td>
								<th>
									Nit o identificación
								</th>
								<td>
									<?php echo $datosCliente["identification"] ?>
								</td>
							</tr>
							<tr>
								<th>
									Teléfono(s)
								</th>
								<td>
									<?php echo empty($datosCliente["telephone"]) ? "N/A" : $datosCliente["telephone"] ?>  <?php echo !empty($datosCliente["cell_phone"]) ? "/". $datosCliente["cell_phone"] : "" ?>
								</td>
								<th>
									Correo electrónico
								</th>
								<td>
									<?php echo $datosCliente["email"] ?>
								</td>
							</tr>
							<tr>
								<th class="text-center" colspan="4">
									<h2 class="py-1">Información del negocio realizado</h2>
								</th>
							</tr>
							<tr>
								<th>
									Nacionalidad del pedido
								</th>
								<td>
									<?php echo $order["Order"]["nacional"] == 1 ? "Nacional" : "Internacional" ?>
								</td>
								<th>
									Tipo de pago
								</th>
								<td>
									<?php echo Configure::read("PAYMENT_TYPE.".$order["Order"]["payment_type"]) ?>
								</td>
							</tr>
							<tr>
								<th>
									Texto adicional al pago
								</th>
								<td>
									<?php echo empty($order["Order"]["payment_text"]) ? "N/A" : $order["Order"]["payment_text"] ?>
								</td>
								<th>
									Fechá limite del pago
								</th>
								<td>
									<?php echo $order["Order"]["deadline"] ?>
								</td>
							</tr>
							<tr>
								<th>
									Nota adicional de aprobación
								</th>
								<td>
									<?php echo empty($order["Order"]["note"]) ? "N/A" : $order["Order"]["note"] ?>
								</td>
								<th>
									Documento adjuntado
								</th>
								<td>
									<?php if (!empty($order["Order"]["document"])): ?>
										<span id="documentoSi">
											SI
										</span>										
										<a class="alingicon" id="alingicon" target="_blank" href="<?php echo $this->Html->url('/files/flujo/negociado/'.$order["Order"]["document"]) ?>">
											Ver documento &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
										</a>
									<?php else: ?>
											No
									<?php endif ?>
								</td>
							</tr>
							<tr>
								<th>
									Vendedor
								</th>
								<td colspan="3">
									<?php echo $order["User"]["name"] ?>
								</td>
							</tr>
							<tr>
								<th class="text-center pb-0" colspan="4">
									<h2 class="py-0">Información los productos vendidos</h2>
								</th>
							</tr>

						</table>
						<table class="table table-bordered">
							<thead>
								<tr class="text-dark">
									<th>
										Imagen producto
									</th>
									<th>
										Referencia
									</th>
									<th>
										Producto
									</th>
									<th>
										Mondea
									</th>
									<th class="text-center">
										Precio
									</th>
									<th>
										Cantidad
									</th>
									<th>
										IVA
									</th>
									<th class="text-center">
										Total
									</th>
								</tr>
							</thead>
									
							<tbody>
								<?php $totalCop = $totalCopIVa = $totalUsd = $totalUsdIva = 0 ?>
								<?php foreach ($order["Product"] as $key => $value): ?>
									<tr>
										<td>
											<?php $ruta = $this->Utilities->validate_image_products($value['img']); ?>
											<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="50px" height="45px" class="imgmin-product">
										</td>
										<td>
											<?php echo $value["part_number"] ?>
										</td>
										<td>
											<?php echo $value["name"] ?>
										</td>
										<td>
											<?php echo strtoupper($value["OrdersProduct"]["currency"]) ?>
										</td>
										<td class="text-right">
											$<?php echo number_format($value["OrdersProduct"]["price"], 2,",",".") ?>
										</td>
										<td>
											<?php echo number_format($value["OrdersProduct"]["quantity"]) ?>
										</td>
										<td>
											<?php echo ($value["OrdersProduct"]["iva"]) == 1 ? "19%" : "N/A" ?>
										</td>
										<td class="text-right">
											<?php 

												if ($value["OrdersProduct"]["currency"] == "cop") {
													$totalCop 	 +=  $value["OrdersProduct"]["price"]*$value["OrdersProduct"]["quantity"];
													if ($value["OrdersProduct"]["iva"] == 1) {
														$totalCopIVa +=  ($value["OrdersProduct"]["price"]*$value["OrdersProduct"]["quantity"]) * 0.19;
													}
												}else{
													$totalUsd +=  $value["OrdersProduct"]["price"]*$value["OrdersProduct"]["quantity"];
													if ($value["OrdersProduct"]["iva"] == 1) {
														$totalUsdIva +=  ($value["OrdersProduct"]["price"]*$value["OrdersProduct"]["quantity"]) * 0.19;
													}
												}

											?>
											$<?php echo number_format($value["OrdersProduct"]["price"]*$value["OrdersProduct"]["quantity"], 2,",",".") ?>
										</td>
									</tr>
									
								<?php endforeach ?>
								<?php if ($totalCop > 0): ?>
									<tr>
										<th colspan="5" class="text-right" style="padding-top: 3%;">
											INFORMACIÓN EN PESOS
										</th>
										<th class="text-right" colspan="3">
											<ul class="list-unstyled text-right">
												<li><b>SUBTOTAL:&nbsp;&nbsp;</b> <?php echo number_format($totalCop,2,",",".") ?></li>
												<li><b>IVA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><?php echo number_format($totalCopIVa,2,",",".") ?>	</li>
												<li><b>TOTAL:&nbsp;&nbsp;&nbsp;</b><?php echo number_format($totalCop+$totalCopIVa,2,",",".") ?></li>
											</ul>
										</th>
										
									</tr>
								<?php endif ?>
								<?php if ($totalUsd > 0): ?>
									<tr>
										<th colspan="5" class="text-right" style="padding-top: 3%;">
											INFORMACIÓN EN USD
										</th>
										<th class="text-right" colspan="3">
											<ul class="list-unstyled text-right">
												<li><b>SUBTOTAL:&nbsp;&nbsp;</b> <?php echo number_format($totalUsd,2,",",".") ?></li>
												<li><b>IVA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><?php echo number_format($totalUsdIva,2,",",".") ?>	</li>
												<li><b>TOTAL:&nbsp;&nbsp;&nbsp;</b><?php echo number_format($totalUsd+$totalUsdIva,2,",",".") ?></li>
											</ul>
										</th>
										
									</tr>
								<?php endif ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>

<?php echo $this->Html->script("printArea.js?".rand(),           array('block' => 'jqueryApp')); ?>
<style>
	#documentoSi{
		display: none;
	}
		.table td, .table th {
	    padding: .50rem;
	}
</style>

<script>
    $("#imprimeData").click(function(event) {
        var mode = 'iframe';
        var close = mode == "popup";
        var options = { mode : mode, popClose : close};
        $("div#impresionDiv").printArea( options );
    });
</script>

<style media="print">
	#documentoSi{
		display: block;
	}
	th,h2,td{
		padding: 0px !important;
	}

	.py-3{
		padding-bottom: 0px !important;
		padding-top: 0px !important;
	}

     #alingicon {
        display:none;
     }          
     
     .right_col,.content-all{
     	padding: 1px !important;
     	margin: 0 !important;
     }
     .col-md-12{
     	padding: 5px !important;
     }
     body{
	  float: none !important;
	  width: auto !important;
	  margin:  0 !important;
	  padding: 0 !important;
	  font-weight: bold !important;
	  background-color: transparent !important;
	  padding: 0px !important;
	  background: #fff !important;
	}
	.osspecialrsp{
		width: 100% !important;
	}
	.container{
		margin: unset !important;
	}
	.body{
		background: #fff !important;
	}

	.centerimg{
		width: 100px !important;
		display: inline-block;
	}
	.centerimg > img{
		width: 100px !important;
	}
	.col-md-3{
		width: 150px;
	}
	h2.titulost{
		font-size: 20px !important;
		padding: 0px !important; 
	}
	.dataFull{
		display: none !important;
	}
	.data-textData{
		display: block !important;
	}
	.dataclientview {
		margin-top: -20px;
	}
	/*body { color: #000 !important|; font: 100%/150% Georgia, "Times New Roman", Times, serif; }*/

</style>    