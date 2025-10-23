<?php echo $this->Form->create('Quotation',array('id' => 'facturaForm')); ?>
<div class="row">
	<div class="col-md-4 col-sm-4">
		<div class="card">
		  <div class="card-header">
		    Direcciones de envío para el cliente: <br> <b><?php echo $cliente["name"]; ?></b> <br>
		    <a href="javascript:void(0)" class="btnoz btn-group-vertical btnAddressClient" data-toggle="tooltip" data-placement="right" title="Agregar dirección" data-id="0" data-client='<?php echo $this->request->data["client"] ?>' data-type="<?php echo $this->request->data["type"] ?>" id='btnAddressClient'>
				<span>Añadir nueva dirección <i class="fa fa-plus-circle"></i> </span>
			</a>
		  </div>
		  <div class="card-body">
		  	<?php if (!empty($direcciones)): ?>
		  		
			    <h3 class="card-title">Seleccionar dirección de envío </h3>
			    <div class="card-text">
			    	<div class="card">
					  <ul class="list-group list-group-flush">
					  	<?php foreach ($direcciones as $key => $value): ?>
					  		<li class="list-group-item radiodiv">
					  			<input type="radio" name="direccion" class="direccionesCliente" required="" value="<?php echo $value["Adress"]["id"] ?>"> 
					  			<b>Contacto: </b><?php echo $value['Adress']['name']; ?><br>
								<b>Ciudad: </b><?php echo $value['Adress']['city']; ?><br>|
								<b>Teléfono: </b><?php echo $value['Adress']['phone']; echo $value['Adress']['phone_two'] != null ? " - ".$value['Adress']['phone_two'] : "" ?> <br>
								<b>Dirección de entrega: </b><?php echo $value['Adress']['address']; ?> (<?php echo $value['Adress']['address_detail']; ?>)<br>
					  		</li>
					  	<?php endforeach ?>
					  </ul>
					</div>
			    </div>
			<?php else: ?>
				<p class="card-text text-danger">
					No existen direcciones de envío creadas, por favor agregue una nueva
				</p>
		  	<?php endif ?>
		  </div>
		</div>
	</div>
	<div class="col-md-8 col-sm-8">
		<div class="row">
			<div class="col-6">
				<div class="form-group">
					<?php 
						echo $this->Form->input('purchase_order',array('label' => 'Órden de compra','placeholder' => 'Por favor ingresa el número de la ordén de compra del cliente', "class" => "form-control", "required"));
					 ?>
				</div>		
			</div>	
			<div class="col-6">							
				<div class="form-group">
					<?php 
						echo $this->Form->input('type_payment',array('label' => ' Forma de pago:','options' => Configure::read("FORMA_PAGO"),"empty" => "Seleccionar", "required"));
					?>
				</div>
			</div>
			<div class="col-6">
				<div class="form-group">
					<?php 
						echo $this->Form->input('user_id',array('label' => 'Vendedor','placeholder' => 'Por favor selecciona el vendedor', "class" => "form-control", "required", "options" => $usuarios_asesores, "empty" => "Seleccionar"));
					 ?>
				</div>		
			</div>
			<div class="col-6">
				<div class="form-group">
					<label for="observations">Fecha de vencimiento</label>
					<?php 
						echo $this->Form->text('deadline',array('label' => 'Fecha de vencimiento', "class" => "form-control", "required", "type" => "date", "value" => date("Y-m-d")));
					 ?>
				</div>		
			</div>
			<div class="col-12">
				<div class="form-group">
					<label for="observations">Observaciones de la proforma</label>
					<?php 
						echo $this->Form->textarea('observations',array('label' => 'Observaciones','placeholder' => 'Por favor escribe las observaciones de esta factura', "class" => "form-control",));
					 ?>
				</div>		
			</div>	
			<div class="col-12">
				<div class="form-group">
					<input type="submit" value="Generar" class="btn btn-success mt-3 pull-right" >
				</div>		
			</div>				
		</div>	
	</div>
</div>