
<?php 
	$importacion = false;
	$imports = array();

   	if(!empty($datosFlujo["Import"]["id"]) && empty($datosFlujo["Imports"]) ){
   		$imports[] 		= $datosFlujo["Import"];
   		$importacion 	= true;
   	}else if(!empty($datosFlujo["Imports"])){
   		$importacion 	= true;
   		$imports 		= $datosFlujo["Imports"];
   	}
 ?>

 <div class="container p-0 containerCRM">

<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
	     <i class="fa fa-1x flaticon-growth"></i>
	    <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
</div>

<div class=" blockwhite spacebtn20">
	<div class="row ">
		<div class="col-md-12">
			<h2 class="titleviewer">Agregar despacho para la orden #: <?php echo $orderData["Order"]["prefijo"] ?>-<?php echo $orderData["Order"]["code"] ?> asociada al flujo 
				<div class="dropdown d-inline">
				  	<a class="bg-blue btn btn-sm btn-success dropdown-toggle p-1 rounded text-white" href="#" role="button" id="dropdownMenuLink_<?php echo md5($orderData["Order"]["prospective_user_id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				   	<?php echo $orderData["Order"]["prospective_user_id"] ?>
				  	</a>

					<div class="dropdown-menu styledrop" aria-labelledby="dropdownMenuLink_<?php echo md5($orderData["Order"]["prospective_user_id"]) ?>">
					    <a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $orderData["Order"]["prospective_user_id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($orderData["Order"]["prospective_user_id"]); ?>">Ver flujo</a>
					    <a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($orderData["Order"]["prospective_user_id"]) ?>" href="#">Ver cotización</a>
					    <a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $orderData["Order"]["prospective_user_id"] ?>">Ver órden de compra</a>
					</div>
				</div> 
			</h2>

			
		</div>
	</div>
</div>
<div class="blockwhite spacebtn20">



<?php echo $this->Form->create('Shipping',["type" => "file",'data-parsley-validate'=>true]); ?>
	<div class="row">
		<?php if ($max_hour_shipping >= date("H:i:s")): ?>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<div class="card-header">
						    Direcciones de envío para el cliente: <br> <b><?php echo $cliente["name"]; ?></b>
						    <a href="javascript:void(0)" class="btnoz btn-group-vertical btnAddressClient" data-toggle="tooltip" data-placement="right" title="Agregar dirección" data-id="0" data-client='<?php echo is_null($orderData["ProspectiveUser"]["clients_natural_id"]) ? $cliente["id"] : $orderData["ProspectiveUser"]["clients_natural_id"] ?>' data-type="<?php echo is_null($orderData["ProspectiveUser"]["clients_natural_id"]) ? "legal" : "natural" ?>" id='btnAddressClient'>
								<span>Añadir nueva dirección <i class="fa fa-plus-circle"></i> </span>
							</a>

						  </div>
					</div>
				</div>
				<div class="card-body">
			  	<?php if (!empty($direcciones)): ?>
			  		
				    <h3 class="card-title">Seleccionar dirección de envío </h3>
				    <div class="card-text">
				    	
				    		<div class="row">
				    			<?php foreach ($direcciones as $key => $value): ?>
							  		<div class="col-md-12">
							  			<input type="radio" name="direccion" class="direccionesCliente" required="" value="<?php echo $value["Adress"]["id"] ?>"> 
							  			<b>Contacto: </b><?php echo $value['Adress']['name']; ?> |
										<b>Ciudad: </b><?php echo $value['Adress']['city']; ?> |
										<b>Teléfono: </b><?php echo $value['Adress']['phone']; echo $value['Adress']['phone_two'] != null ? " - ".$value['Adress']['phone_two'] : "" ?> |
										<b>Dirección de entrega: </b><?php echo $value['Adress']['address']; ?> (<?php echo $value['Adress']['address_detail']; ?>) 
							  		</div>
							  	<?php endforeach ?>
				    		</div>
				    </div>
				<?php else: ?>
					<p class="card-text text-danger">
						No existen direcciones de envío creadas, por favor agregue una nueva
					</p>
			  	<?php endif ?>
			  </div>
			</div>
		<?php endif ?>
		<div class="col-md-12">
			<div class="form-group">
				<?php echo $this->Form->input('id'); ?>	
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<?php $optionsEntrega = $max_hour_shipping > date("H:i:s") ? ["0"=>"Ninguno","1" => "Envio a domicilio", "2" => "Entrega o recoge en tienda","3"=>"Contraentrega", "4" => "Crédito"] : ["0"=>"Ninguno"];


				 ?>
				<?php echo $this->Form->input('type',["label" => "Tipo de envio", "options" => $optionsEntrega, "required" => true ]); ?>	
			</div> 
		</div>
		
		<div class="col-md-4">
			<div class="form-group">
				<?php echo $this->Form->input('envio',["label" => "Enviar correo al cliente","options" => [ "1" => "Si", "0" => "No"], "default" => 0 ]); ?>
			</div>
		</div>
		<div class="col-md-4">
			<?php echo $this->Form->input('copias_email',array("type" => "text", "value" => $emails, "label" => "Notificar del envío a estos correos: ")); ?>
		</div>
		<div class="col-md-6" style=" <?php echo $max_hour_shipping >= date("H:i:s") ? "" : "display:none !important;" ?> ">
			<div class="form-group">
				<?php echo $this->Form->input('note',["label" => "Nota"]); ?>
			</div>
		</div>
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">
						Productos a despachar
					</h3>
				</div>
				<div class="col-md-12">
					<table class="table-hovered table">
						<thead>
							<tr>
								<th>Imagen</th>
								<th>Referencia</th>
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Despacho</th>
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
									<td>SI</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
			    	
		    		<input type="submit" class="btn btn-success float-right mt-4" value="Solicitar despacho" >
				</div>
			</div>
		</div>	
	</div>


<?php echo $this->Form->end(); ?>
</div>
</div>

<?php echo $this->element("address"); ?>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
	echo $this->Html->script("controller/shippings/admin.js?".rand(),				array('block' => 'AppScript'));
?>

<?php echo $this->element("flujoModal"); ?>