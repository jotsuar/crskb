
<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
	     <i class="fa fa-1x flaticon-growth"></i>
	    <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
</div>
<div class="container p-0 containerCRM">
	<div class="col-md-10 p-0 mx-auto d-block" id="cotizacionview">
		<div class="blockwhite">
			<div class="row">
				<div class="col-md-12">
					<?php if (AuthComponent::user("id")): ?>
						
						<div class="text-left d-inline-block">
						    <a href="<?php echo $this->Html->url(["controller"=>"prospective_users","action" => "index", "?"=>["q"=>$order["Order"]["prospective_user_id"]]]) ?>" class="btn btn-info" id="irAFlujo">
						    	Ir al flujo
						    </a>
						</div>

						<?php if ($order["Order"]["state"] == 0): ?>
							<a href="" data-id="<?php echo $this->Utilities->encryptString($order["Order"]["id"]) ?>" class="btn btn-success envioCliente">
								Enviar al cliente <i class="fa fa-send vtc"></i>
							</a>
						<?php endif ?>
					<?php else: ?>
						<?php if ($aproveeCustomer && $order["Order"]["state"] == 2 ): ?>
							<a href="" data-id="<?php echo $this->Utilities->encryptString($order["Order"]["id"]) ?>" class="btn btn-success approve">
								Aprobar <i class="fa fa-check vtc"></i>
							</a>
							<a href="" data-id="<?php echo $this->Utilities->encryptString($order["Order"]["id"]) ?>" class="btn btn-danger reject">
								Rechazar <i class="fa fa-times vtc"></i>
							</a>
						<?php endif ?>
					<?php endif ?>
					<div class="text-right <?php echo $aproveeCustomer ? '' : 'd-inline-block' ?>">
					    <button id="imprimeData" class="btn btn-primary ">Imprimir</button>
					</div>
					<h2 class="my-4">
						Estado de la orden: 
						<?php switch ($order["Order"]["state"]) {
							case '0':
								echo "Borrador";
								break;
							case '1':
								echo "Aprobada por el cliente";
								break;
							case '2':
								echo "Enviada";
								break;
							case '3':
								echo "Rechazada";
								break;
						} ?>

					</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<table class="table table">
						<tr>
							<td class="pl-4">
								<img src="<?php echo $this->Html->url("/files/logoProveedor.png") ?>" id="imgLogoImprime" alt="Logo Proveedor" class="img-fluid mb-2 w-75">

								<h2 class="text-mobile"><b> <?php echo "Linea Gratuita"  ?>  <?php echo Configure::read("COMPANY.CALL_FREE_NUMBER") ?></b></h2>
							</td>
							<td>
								<h3 class="strongtittle spacetop text-mobile"><?php echo Configure::read("COMPANY.NAME") ?></h3>
								<h3 class="strongtittle text-mobile"><?php echo Configure::read("COMPANY.NIT") ?></h3>
								<h3 class="text-mobile"><b><?php echo Configure::read("COMPANY.ADDRESS") ?></b></h3>
								<h3 class="text-mobile"><b><?php echo Configure::read("COMPANY.TELCOMPANY") ?></b></h3>
							</td>
						</tr>
					</table>
				</div>
				<div class="col-md-12">
					<div class="table-responsive" id="impresionDiv">
						<h2 id="remisionTitle" style="display:none"> Remisión de pedido </h2>
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
									Fechá limite del pedido
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

										<?php $isPdfFile = strpos(strtolower($order['Order']['document']), ".pdf" ); ?>
											<span id="documentoSi">
												SI
											</span>	
										<?php if ($isPdfFile === false): ?>																				
											<a class="alingicon" id="alingicon" target="_blank" href="<?php echo $this->Html->url('/img/flujo/negociado/'.$order["Order"]["document"]) ?>">
												Ver documento &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
											</a>
										<?php else: ?>
											<a class="alingicon" id="alingicon" target="_blank" href="<?php echo $this->Html->url('/files/flujo/negociado/'.$order["Order"]["document"]) ?>">
												Ver documento &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
											</a>
										<?php endif ?>
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
									<th width="70">
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
									<tr style="display: <?php echo (in_array($value["part_number"],['S-003']) && $order["Quotation"]["show_ship"] == 0 && $order["Quotation"]["header_id"] != 3) ? 'none' : 'table-row' ?> ">
										<td>
											<?php $ruta = $this->Utilities->validate_image_products($value['img']); ?>
											<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="50px" height="45px" class="imgmin-product">
										</td>
										<td>
											<?php echo $value["part_number"] ?>
											<?php if (AuthComponent::user("email") == "jotsuar@gmail.com"): ?>
												<br>
												<a data-href="<?php echo $this->Html->url(["action"=>"change_ref_order",$value["OrdersProduct"]["id"]]) ?>" class="btn btn-warning btn-sm text-white btnChangeRef" data-id="<?php echo $value["OrdersProduct"]["id"] ?>">
													<i class="fa fa-pencil vtc"></i>
													Editar referencia
												</a>
											<?php endif ?>
										</td>
										<td>
											<?php echo $value["name"] ?>
										</td>
										<td>
											<?php echo strtoupper($value["OrdersProduct"]["currency"]) ?>
										</td>
										<td class="text-right pr-1">
											$<?php echo number_format($value["OrdersProduct"]["price"], 2,",",".") ?>
										</td>
										<td>
											<?php echo number_format($value["OrdersProduct"]["quantity"]) ?>
										</td>
										<td>
											<?php echo ($value["OrdersProduct"]["iva"]) == 1 ? "19%" : "N/A" ?>
										</td>
										<td class="text-right pr-1">
											<?php 

												if ($order["Quotation"]["header_id"] == 3 || $order["Quotation"]["show_ship"] == 1  || (!in_array($value["part_number"],['S-003']) && $order["Quotation"]["show_ship"] == 0) ){

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
												}
												

											?>
											$<?php echo number_format($value["OrdersProduct"]["price"]*$value["OrdersProduct"]["quantity"], 2,",",".") ?>
										</td>
									</tr>
									
								<?php endforeach ?>
								<?php if ($totalCop > 0): ?>
									<tr>
										<th colspan="5" class="text-right pr-1" style="padding-top: 3%;">
											INFORMACIÓN EN PESOS
										</th>
										<th class="text-right" colspan="3">
											<ul class="list-unstyled text-right pr-1">
												<li><b>SUBTOTAL:&nbsp;&nbsp;</b> <?php echo number_format($totalCop,2,",",".") ?></li>
												<li><b>IVA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><?php echo number_format($totalCopIVa,2,",",".") ?>	</li>
												<li><b>TOTAL:&nbsp;&nbsp;&nbsp;</b><?php echo number_format($totalCop+$totalCopIVa,2,",",".") ?></li>
											</ul>
										</th>
										
									</tr>
								<?php endif ?>
								<?php if ($totalUsd > 0): ?>
									<tr>
										<th colspan="5" class="text-right pr-1" style="padding-top: 3%;">
											INFORMACIÓN EN USD
										</th>
										<th class="text-right" colspan="3">
											<ul class="list-unstyled text-right pr-1">
												<li><b>SUBTOTAL:&nbsp;&nbsp;</b> <?php echo number_format($totalUsd,2,",",".") ?></li>
												<li><b>IVA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><?php echo number_format($totalUsdIva,2,",",".") ?>	</li>
												<li><b>TOTAL:&nbsp;&nbsp;&nbsp;</b><?php echo number_format($totalUsd+$totalUsdIva,2,",",".") ?></li>
											</ul>
										</th>
										
									</tr>
								<?php endif ?>
							</tbody>
						</table>
						<div class="text-center">
							<small>KEBCO SAS - Equipos industruales <?php echo date("Y") ?>  </small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/orders/admin.js?".rand(),				array('block' => 'AppScript'));
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
        window.print();
    });
</script>

<style media="print">
	#imgLogoImprime{
		max-width: 150px !important;
	}
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
	.dataFull,.menu_fixed,#irAFlujo,#imprimeData,.top_nav,.bg-azulclaro{
		display: none !important;
	}
	.data-textData,#remisionTitle{
		display: block !important;
	}
	#remisionTitle{
		text-align: center;
		margin-bottom: 10px;
	}
	.dataclientview {
		margin-top: -20px;
	}
	#impresionDiv{
		padding: 15px;
	}
	footer,.btnChangeRef{
		display: none !important;
	}
	.content-all,.right_col {

		padding-bottom: 0px !important;
	}

	/*body { color: #000 !important|; font: 100%/150% Georgia, "Times New Roman", Times, serif; }*/

</style>    

<div class="modal fade " id="modalAprove" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Aprobar orden de pedido</h5>
      </div>
      <div class="modal-body" id="cuerpoAprueba">
      	<form action="#" method="post" id="formAprovee" enctype="multipart/form-data" data-parsley-validate="data-parsley-validate">      		
			<div class="form-group">
				<label for="correoPrincipal">Por favor escriba su correo electrónico:</label>
				<input type="email" id="correoPrincipal" name="correoPrincipal" value="<?php echo $datosCliente["email"] ?>" required="" class="form-control">
				<input type="hidden" id="order" name="order" required="" value="<?php echo $order['Order']['id'] ?>" class="form-control">
				<input type="hidden" id="order" name="type" required="" value="approve" class="form-control">
			</div>
			<div class="form-group">
				<label for="comentarioCotizacion">Por favor escriba un comentario para la aprobación de la orden</label>
				<textarea name="comentarioCotizacion" id="comentarioCotizacionAprobar" cols="30" rows="30" class="form-control" required=""></textarea>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-success float-right mt-3" value="Aprobar orden" >
			</div>
      	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade " id="modalChangeRef" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Cambiar referencia de producto</h5>
      </div>
      <div class="modal-body" id="cuerpoModalChangeRef">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade " id="modalReject" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Rechazar orden de pedido</h5>
      </div>
      <div class="modal-body" id="cuerpoReject">
      	<form action="#" method="post" id="formReject" enctype="multipart/form-data" data-parsley-validate="data-parsley-validate">      		
			<div class="form-group">
				<label for="correoPrincipal">Por favor escriba su correo electrónico:</label>
				<input type="email" id="correoPrincipal" name="correoPrincipal" value="<?php echo $datosCliente["email"] ?>" required="" class="form-control">
				<input type="hidden" id="order" name="order" required="" value="<?php echo $order['Order']['id'] ?>" class="form-control">
				<input type="hidden" id="order" name="type" required="" value="reject" class="form-control">
			</div>
			<div class="form-group">
				<label for="comentarioCotizacion">Por favor escriba un comentario informando porque rechaza la orden</label>
				<textarea name="comentarioCotizacion" id="comentarioCotizacionReject" cols="30" rows="30" class="form-control" required=""></textarea>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-danger float-right mt-3" value="Rechazar orden" >
			</div>
      	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>