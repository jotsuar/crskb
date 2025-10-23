<?php echo $this->element("nav_cliente", ["cliente" => $cliente, "action" => "shippings"]); ?>

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
				<div class="col-md-12">
					<div class="row">
						
						<div class="col-md-6 text-left">
						    <a href="<?php echo $this->Html->url(["controller"=>"clientes","action" => "view", $this->Utilities->encryptString($shipping["Shipping"]["order_id"]) ]) ?>" class="btn btn-info ml-4"> <i class="fa fa-eye vtc"></i> Ver orden de pedido </a>
						</div>
						<div class="col-md-6 text-right">
						    <button id="imprimeData" class="btn btn-primary ">Imprimir</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12" id="impresionDiv">
			<div class="blockwhite mt-2">
				<h2 class="titleviewer">Detalle de despacho</h2>
			</div>
			<div class="blockwhite">
				<div class="table-responsive">
					<table class="table table-hovered">
						<tr>
							<th>
								Orden de pedido:
							</th>
							<td>
								<?php echo $shipping["Order"]["prefijo"] ?>-<?php echo $shipping["Order"]["code"] ?>
							</td>
						</tr>
						<tr>
							<th>
								Estado actual: 
							</th>
							<td>
								<?php switch ($shipping['Shipping']['state']) {
									case '0':
										echo "Despacho creado";
										break;
									case '1':
										echo "Despacho en preparación";
										break;
									case '2':
										echo "Despacho enviado";
										break;
									case '3':
										echo "Despacho entregado";
										break;
								} ?>
							</td>
						</tr>
						<tr>
							<th>
								Tipo de despacho
							</th>
							<td>
								<?php echo $shipping["Shipping"]["type"] == 2 ? "Recoge en oficina" : "Envio a domicilio" ?>
							</td>
						</tr>
						<tr>
							<th>
								Nota adicional
							</th>
							<td>
								<?php echo $shipping["Shipping"]["note"] ?>
							</td>
						</tr>
						<tr>
							<th>
								Dirección del cliente:
							</th>
							<td>
								<?php echo $shipping["Adress"]["city"] ?> | <?php echo $shipping["Adress"]["address"] ?> | <?php echo $shipping["Adress"]["phone"] ?>
							</td>
						</tr>
						<?php if (!empty($shipping["Shipping"]["conveyor_id"])): ?>
							<tr>			
								<th>
									Transportadora: 
								</th>
								<td>
									<?php echo $shipping['Conveyor']['name'] ?>
								</td>
							</tr>	
						<?php endif ?>
						<tr>
							<th>
								Fecha de creación inicial
							</th>
							<td>
								<?php echo $shipping["Shipping"]["date_initial"] ?>
							</td>
						</tr>
						<tr>
							<th>
								Fecha de preparación
							</th>
							<td>
								<?php echo $shipping["Shipping"]["date_preparation"] ?>
							</td>
						</tr>
						<tr>
							<th>
								Fecha de envío
							</th>
							<td>
								<?php echo $shipping["Shipping"]["date_send"] ?>
							</td>
						</tr>
						<?php if (!empty($shipping["Shipping"]["guida"])): ?>
							<tr>
								<th>
									# de guía
								</th>
								<td>
									<?php echo $shipping["Shipping"]["guide"] ?>
								</td>
							</tr>
						<?php endif ?>
						<?php if (!empty($shipping["Shipping"]["document"])): ?>
							<tr>
								<th>
									Comprobante
								</th>
								<td>
									<a class="comprobanteguia imgbuy mt-0" href="<?php echo $this->Html->url('/img/shippings/'.$shipping['Shipping']['document']) ?>" target="_blank">
										VER GUIA &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</a>
								</td>
							</tr>
						<?php endif ?>
						<tr>
							<th>
								Fecha probable de entrega
							</th>
							<td>
								<?php echo $shipping["Shipping"]["date_deadline"] ?>
							</td>
						</tr>
						<tr>
							<th>
								Fecha de entrega
							</th>
							<td>
								<?php echo $shipping["Shipping"]["date_end"] ?>
							</td>
						</tr>
					</table>

					<h3 class="text-center">
						Productos agregados
					</h3>

					<table class="table-hovered table">
						<thead>
							<tr>
								<th>Imagen</th>
								<th>Referencia</th>
								<th>Producto</th>
								<th>Cantidad</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($shipping['Product'] as $product): ?>
								<tr>
									<td>
										
										<?php $ruta = $this->Utilities->validate_image_products($product['img']); ?>
										<img class="img-fluid" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" style="width: 100px">

									</td>
									<td><?php echo $product['part_number']; ?></td>
									<td><?php echo $product['name']; ?></td>
									<td><?php echo $product['ShippingsProduct']["quantity"]; ?></td>
								</tr>
							<?php endforeach ?>
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

<?php echo $this->Html->script("printArea.js?".rand(),           array('block' => 'jqueryApp')); ?>

<script>
    $("#imprimeData").click(function(event) {
        var mode = 'iframe';
        var close = mode == "popup";
        var options = { mode : mode, popClose : close};
        $("div#impresionDiv").printArea( options );
    });
</script>