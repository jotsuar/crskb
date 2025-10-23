
<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
	     <i class="fa fa-1x flaticon-growth"></i>
	    <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
</div>

<div class="container p-0 containerCRM">
	<div class="col-md-12 p-0" id="cotizacionview">

		<div class="blockwhite">
			<div class="row">
				<div class="col-md-12">
					<div class="row">

						<?php if (AuthComponent::user("id") && ($shipping["Shipping"]["state"] == 0 &&  (in_array(AuthComponent::user("email"), Configure::read("email_shippings") ) || AuthComponent::user("role") == "Gerente General" )) ): ?>
							
							<div class="col-md-12">
								
							</div>

						<?php endif ?>
						
						<div class="col-md-6 text-left">
								<?php if (AuthComponent::user("id")): ?>
									<a href="<?php echo $this->Html->url(["controller"=>"shippings","action"=>"index"]) ?>" class="btn btn-primary">
										Volver al listado <i class="fa fa-list vtc"></i>
									</a>
								<?php endif ?>

								<?php if (empty($flujos_without_shipping) || ( !empty($flujos_without_shipping) && in_array($shipping["Shipping"]["id"], $flujos_without_shipping) ) ): ?>

							    <a href="<?php echo $this->Html->url(["controller"=>"orders","action" => "view", $this->Utilities->encryptString($shipping["Shipping"]["order_id"]) ]) ?>" class="btn btn-info ml-4"> <i class="fa fa-eye vtc"></i> Ver orden de pedido </a>
							    <?php if (
							    	$shipping["Shipping"]["state"] != -1 && (  $shipping["Shipping"]["state"] != 3 || ($shipping["Shipping"]["state"] == 3 && $shipping["Shipping"]["request_envoice"] == 1) ) &&  (in_array(AuthComponent::user("email"), Configure::read("email_shippings") ) || AuthComponent::user("role") == "Gerente General" )  ): ?>					
										<a href="<?php echo $this->Html->url(["action" => "change", $this->Utilities->encryptString($shipping['Shipping']['id']) ]) ?>" class="btn btn-warning btnChangeState">
											Cambiar estado <i class="fa fa-pencil vtc"></i>
										</a>
									<?php endif ?>
									<?php if ( 

											(

												(in_array(AuthComponent::user("email"), Configure::read("email_shippings") ) || AuthComponent::user("role") == "Gerente General" ) || $shipping["Shipping"]["user_id"] == AuthComponent::user("id")

											) && ($shipping["Shipping"]["request_type"] == 0 || $shipping["Shipping"]["request_type"] == 3)&& $shipping['Shipping']['state'] == 3 && $shipping["Shipping"]["request_envoice"] == 0

										): ?>
											<a href="<?php echo $this->Html->url(["action" => "request_envoice", $this->Utilities->encryptString($shipping['Shipping']['id']) ]) ?>" class="btn btn-success btn-sm request_envoice">
												Solicitar facturación  <i class="fa fa-eye vtc"></i>
											</a>
										<?php endif ?>


										<?php if ( 

											(

												(in_array(AuthComponent::user("email"), Configure::read("email_shippings") ) || AuthComponent::user("role") == "Gerente General" ) || $shipping["Shipping"]["user_id"] == AuthComponent::user("id")

											) && $shipping["Shipping"]["request_type"] == 1 && $shipping['Shipping']['state'] == 3 && $shipping["Shipping"]["request_shipping"] == 0

										): ?>
											<a href="<?php echo $this->Html->url(["action" => "request_shipping", $this->Utilities->encryptString($shipping['Shipping']['id']) ]) ?>" class="btn btn-danger btn-sm request_shipping" target="_blank">
												Solicitar despacho  <i class="fa fa-eye vtc"></i>
											</a>
										<?php endif ?>
										
									<?php endif ?>
						</div>

						<div class="col-md-6 text-right">
						    <button id="imprimeData" class="btn btn-primary ">Imprimir</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 " id="impresionDiv">
			<div class="blockwhite mt-2 mb-2">
				<h2 class="titleviewer">Detalle de solicitud de despacho y/o facturación</h2>
			</div>
			<div class="blockwhite">
				<div class="table-responsive">
					<table class="table table-hovered">
						<tr>
							<th>
								Flujo:
							</th>
							<td>
								<div class="dropdown d-inline">
							  	<a class="bg-blue btn btn-sm btn-success dropdown-toggle p-1 rounded text-white" href="#" role="button" id="dropdownMenuLink_<?php echo md5($shipping["Order"]["prospective_user_id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							   	<?php echo $shipping["Order"]["prospective_user_id"] ?>
							  	</a>

								<div class="dropdown-menu styledrop" aria-labelledby="dropdownMenuLink_<?php echo md5($shipping["Order"]["prospective_user_id"]) ?>">
								    <a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $shipping["Order"]["prospective_user_id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($shipping["Order"]["prospective_user_id"]); ?>">Ver flujo</a>
								    <a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($shipping["Order"]["prospective_user_id"]) ?>" href="#">Ver cotización</a>
								    <a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $shipping["Order"]["prospective_user_id"] ?>">Ver órden de compra</a>
								</div>
							</div> 
							</td>
						</tr>
						<tr>
							<th>
								# Solicitud
							</th>
							<td>
								#<?php echo $shipping["Shipping"]["id"] ?>
							</td>
						</tr>
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
								Solicita:
							</th>
							<td>
								<?php echo $shipping["User"]["name"] ?>
							</td>
						</tr>
						<tr>
							<th>
								Estado actual: 
							</th>
							<td>
								<?php 

									switch ($shipping['Shipping']['state']) {
										case '-1':
											echo "Solicitud cancelada rechazada";
											break;
										case '0':
											echo "Solicitud creada";
											break;
										case '1':
											echo "Solicitud en preparación";
											break;
										case '2':
											echo "Solicitud enviada y/o facturada";
											break;
										case '3':

											if ($shipping["Shipping"]["request_envoice"] == 1) {
												echo "Despacho enviado y factura solicitada";
											}elseif($shipping["Shipping"]["request_envoice"] == 2){
												echo "Despacho enviado y factura cargada";
											}elseif ($shipping["Shipping"]["request_shipping"] == 1) {
												echo "Despacho solicitado y factura cargada";
											}elseif($shipping["Shipping"]["request_shipping"] == 2){
												echo "Despacho enviado y factura cargada";
											}else{
												echo "Solicitud entregada";
											}

											
											break;
									}

								 ?>
							</td>
						</tr>
						<tr>
							<th>
								Tipo de solicitud
							</th>
							<td>
								<?php 

									switch ($shipping["Shipping"]["request_type"]) {
										case '0':
											echo "Despacho y/o remisión";
											break;
										case '1':
											echo "Facturación";
											break;
										case '2':
											echo "Despacho y Facturación";
											break;
										case '3':
											echo "Remisión";
											break;
										
										default:
											// code...
											break;
									}

								 ?>
							</td>
						</tr>
						<tr>
							<th>
								Tipo de despacho
							</th>
							<td>
								<?php $states = ["0"=>"Ninguno","1" => "Envio a domicilio", "2" => "Entrega o recoge en tienda","3"=>"Contraentrega", "4" => "Crédito"]; ?>
								<?php echo $states[$shipping["Shipping"]["type"]]; ?>
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
						<tr class="border border-danger">
								<th class="border border-danger border-right-0">
									Nota de logística
								</th>
								<td class="border border-danger border-left-0">
									<span class="font-italic font-weight-bold font20">
										<?php echo $shipping["Shipping"]["note_logistic"] ?>
									</span>
									<br>
									<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Logística"] ) && $shipping["Shipping"]["state"] != -1): ?>
										<a href="<?php echo $this->Html->url(["action"=>"note_logistic", $this->Utilities->encryptString($shipping["Shipping"]["id"])]) ?>" class="btn btn-info editNote btn-sm">
											Editar nota <i class="fa fa-pencil vtc"></i>
										</a>
									<?php endif ?>
								</td>
							</tr>
						<tr>
							<th>
								Dirección del cliente:
							</th>
							<td>
								<b>Contacto: </b><?php echo $shipping['Adress']['name']; ?> |
									<b>Ciudad: </b><?php echo $shipping['Adress']['city']; ?> |
									<b>Teléfono: </b><?php echo $shipping['Adress']['phone']; echo $shipping['Adress']['phone_two'] != null ? " - ".$shipping['Adress']['phone_two'] : "" ?> |
									<b>Dirección de entrega: </b><?php echo $shipping['Adress']['address']; ?> (<?php echo $shipping['Adress']['address_detail']; ?>) 
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

						<?php if (!empty($shipping["Shipping"]["rut"])): ?>
							<tr>			
								<th>
									RUT Cliente
								</th>
								<td>
									<a class="btn btn-primary btn-sm" href="<?php echo $this->Html->url('/files/flujo/ruts/'.$shipping['Shipping']['rut']) ?>" target="_blank" data-toggle="tooltip" title="Ver rut">
										<i class="fa-1x fa fa-eye vtc"></i> Ver RUT
									</a>
								</td>
							</tr>	

						<?php endif ?>
						<?php if (!empty($shipping["Shipping"]["email_envoice"])): ?>
							<tr>
								<th>
									# de guía
								</th>
								<td>
									<?php echo $shipping["Shipping"]["email_envoice"] ?>
								</td>
							</tr>
						<?php endif ?>
						<?php if (!empty($shipping["Shipping"]["orden"])): ?>
							<tr>			
								<th>
									OC Cliente
								</th>
								<td>
									<?php $isPdfFile = strpos(strtolower($shipping['Shipping']['orden']), ".pdf" ); ?>
									<?php if ($isPdfFile === false): ?>		
										<a class="btn btn-primary btn-sm" href="<?php echo $this->Html->url('/img/flujo/ordenes/'.$shipping['Shipping']['orden']) ?>" target="_blank" data-toggle="tooltip" title="Ver orden de compra">
											<i class="fa-1x fa fa-eye vtc"></i> Ver Orden de compra
										</a>
									<?php else: ?>
										<a class="btn btn-primary btn-sm" href="<?php echo $this->Html->url('/files/flujo/ordenes/'.$shipping['Shipping']['orden']) ?>" target="_blank" data-toggle="tooltip" title="Ver orden de compra">
											<i class="fa-1x fa fa-eye vtc"></i> Ver Orden de compra
										</a>
									<?php endif ?>
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
						<?php if (!empty($shipping["Shipping"]["guide"])): ?>
							<tr>
								<th>
									# de guía
								</th>
								<td>
									<?php echo $shipping["Shipping"]["guide"] ?>
								</td>
							</tr>
						<?php endif ?>
						<?php if (!empty($shipping["Shipping"]["bodega"])): ?>
							<tr>
								<th>
									Bodega
								</th>
								<td>
									<?php echo $shipping["Shipping"]["bodega"] ?>
								</td>
							</tr>
						<?php endif ?>
							
						<?php if (!empty($shipping["Shipping"]["document"])): ?>
							<tr>
								<th>
									Comprobante
								</th>
								<td>
									<a class="comprobanteguia imgbuy mt-0" href="<?php echo $this->Html->url('/files/flujo/despachado/'.$shipping['Shipping']['document']) ?>" target="_blank">
										VER GUIA &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</a>
								</td>
							</tr>
						<?php endif ?>

						<?php if (!empty($shipping["Shipping"]["document_add"])): ?>
							<tr>
								<th>
									Documento adicional subido por el asesor
								</th>
								<td>
									<a class="comprobanteguia imgbuy mt-0" href="<?php echo $this->Html->url('/files/flujo/despachado/'.$shipping['Shipping']['document_add']) ?>" target="_blank">
										VER DOCUMENTO &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</a>
								</td>
							</tr>
						<?php endif ?>
						<?php if (!empty($shipping["Shipping"]["bill_code"]) && trim($shipping["Shipping"]["bill_code"]) != 'KE' && trim($shipping["Shipping"]["bill_code"]) != 'KEB' ): ?>
							<tr>
								<th>
									Código de factura
								</th>
								<td>
									<?php echo $shipping["Shipping"]["bill_code"] ?>
								</td>
							</tr>
						<?php endif ?>
						<?php if (!empty($shipping["Shipping"]["bill_file"])): ?>
							<tr>
								<th>
									Factura de venta
								</th>
								<td>
									<a class="comprobanteguia imgbuy mt-0" href="<?php echo $this->Html->url('/files/flujo/facturas/'.$shipping['Shipping']['bill_file']) ?>" target="_blank">
										VER FACTURA &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</a>
								</td>
							</tr>
						<?php endif ?>
						<?php if (!empty($shipping["Shipping"]["remision"])): ?>
							<tr>
								<th>
									Remisión generada en WO
								</th>
								<td>
									<a class="comprobanteguia imgbuy mt-0" href="<?php echo $this->Html->url('/files/flujo/remisiones/'.$shipping['Shipping']['remision']) ?>" target="_blank">
										VER REMISIÓN &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</a>
								</td>
							</tr>
						<?php endif ?>
						<?php if (!empty($shipping["Shipping"]["note_bill"])): ?>
							<tr>
								<th>
									Nota adicional <?php echo $shipping["Shipping"]["state"] == -1 ? "al despacho" : "a la factura"; ?>
								</th>
								<td>
									<?php echo $shipping["Shipping"]["note_bill"] ?>
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
						Productos gestionados
					</h3>

					<table class="table-hovered table">
						<thead>
							<tr>
								<th>Imagen</th>
								<th>Referencia</th>
								<th>Producto</th>
								<th>Cantidad</th>
								<th> <?php echo $shipping["Shipping"]["request_type"] == "3" ? "Remisión" : "Despacho" ?> </th>
								<th>Factura</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($shipping['Product'] as $product): ?>
								<tr>
									<td>
										
										<?php $ruta = $this->Utilities->validate_image_products($product['img']); ?>
										<img class="img-fluid" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" style="width: 100px">

									</td>
									<td>
										<?php echo $product['part_number']; ?> 
										
										<?php if ($product['ShippingsProduct']["envoice"] == 1 && !empty($product['ShippingsProduct']["serial_number"] )): ?>
											<br> 
											<p>
												<b>Número de serie: </b> <?php echo $product['ShippingsProduct']["serial_number"] ?>
											</p>
										<?php endif ?>
									 </td>
									<td><?php echo $product['name']; ?></td>
									<td><?php echo $product['ShippingsProduct']["quantity"]; ?></td>
									<td><?php echo $product['ShippingsProduct']["shipping"] == 1 ? "Si" : "No"; ?></td>
									<td><?php echo $product['ShippingsProduct']["envoice"] == 1 ? "Si" : "No"; ?></td>
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
		echo $this->Html->script("controller/shippings/admin.js?".rand(),				array('block' => 'AppScript'));
?>

<?php echo $this->Html->script("printArea.js?".rand(),           array('block' => 'jqueryApp')); ?>


<?php echo $this->element("flujoModal"); ?>

<div class="modal fade" id="modalNotices" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Gestionar nota de logística</h5>
      </div>
      <div class="modal-body" id="bodyNotices">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalFacturacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Solicitar facturación para orden de despacho</h5>
      </div>
      <div class="modal-body" id="bodyFacturacion">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="cambioEstado" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Cambio de estado despacho</h5>
      </div>
      <div class="modal-body" id="cuerpoDespachoNuevo">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<script>
    $("#imprimeData").click(function(event) {
        var mode = 'iframe';
        var close = mode == "popup";
        var options = { mode : mode, popClose : close};
        $("div#impresionDiv").printArea( options );
    });
</script>