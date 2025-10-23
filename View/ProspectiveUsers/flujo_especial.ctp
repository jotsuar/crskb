<?php 
	echo $this->Html->css(array('lib/jquery.typeahead.css'),						array('block' => 'AppCss'));
?>

<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h2 class="titleviewer">Creación de flujo express</h2>
			</div>
		</div>
	</div>
	<div class="blockwhite">
		<?php echo $this->Form->create('ProspectiveUser',array("id" => "formCreateTienda", "type" => "file")); ?>
			<div class="row">
				<div class="col-md-12">
					<?php $clientes = array_merge($clientsLegals,$clientsNaturals); ?>
					<div class="row"> 	
						<div class="col-md-12" id="camposValidacion">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<?php echo $this->Form->input('phone_customer',array('label' => "Celular del cliente","placeholder"=>"Por favor ingrese un celular válido","id"=>"phoneCustomer"));?>
								</div>
								<div class="col-md-6 col-sm-6">
									<?php echo $this->Form->input('email_customer',array('label' => 'Correo electrónico del cliente',"placeholder"=>"Por favor ingrese un correo electrónico válido","id"=>"emailCliente","type"=>"email"));  ?>
								</div>
								<div class="col-md-12 col-sm-12">
									<a href="#" id="validarInput" class="btn btn-warning float-right mt-4">
										Validar información <i class="fa fa-check vtc"></i>
									</a>
								</div>
							</div>
						</div>			
						<div class="otros-campos w-100" style="display: none">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6 col-sm-6">
										<?php echo $this->Form->input('country',array('label' => "País* :","options" => Configure::read("PAISES"),"id"=>"paisesFlujoTienda"));?>
									</div>
									<div class="col-md-6 col-sm-6">
										<?php echo $this->Form->input('user_id',array('label' => 'Asignado al asesor* :','value'=>AuthComponent::user('id'), 'options' => (isset($usuarios)) ? $usuarios: "", "empty" => "Seleccionar asesor","required","id"=>"usuarioFlujoTienda"));  ?>
									</div>
								</div>
								<div id="especialCliente" style="display: none">
									<div class="clienteNaturalProspective" style="display: none">
										<div class="row">
											<div class="col-md-12 col-12">
												<?php echo $this->Form->input('clients_natural_id',array('label' => 'Cliente natural',"required", "id" => "flujoEspecialClienteNatural", "type" => "hidden" )); ?>
											</div>
											<div class="col-md-12 col-12">
												<?php echo $this->Form->input('clients_natural_name',array('label' => 'Nombre cliente', "id" => "flujoClienteNombre", "readonly" )); ?>
											</div>
										</div>
									</div>
									<div class="clienteLegalProspective" style="display: none">
										<div class="row">
											<div class="col-md-12 col-12">
												<?php echo $this->Form->input('contacs_users_id',array('label' => 'contacto',"required", "id" => "flujoEspecialClienteJuridico", "type" => "hidden" )); ?>
											</div>
											<div class="col-md-12 col-12">
												<?php echo $this->Form->input('clients_legal_name',array('label' => 'Nombre cliente *', "id" => "flujoClienteLegalNombre", "readonly" )); ?>
											</div>
											<div class="col-md-12 col-12">
												<?php echo $this->Form->input('contacts_legal_name',array('label' => 'Nombre contacto *', "id" => "flujoClienteLegalContactoNombre", "readonly" )); ?>
											</div>
										</div>
									</div>
								</div>
							</div>

							

					        <div class="col-md-12">
					          <?php echo $this->Form->input('origin',array('label' => 'Origen de la Solicitud* :', "required","id" => "originFlujoTienda", "options" => Configure::read("variables.origenContact"), "empty" => "Seleccionar" )); ?>
					        </div>
					        <div class="col-md-2 pt-3">
					          <?php echo $this->Form->input('flujo_no_valido',array('type' => 'hidden','label' => 'Flujo no válido * ','id' => "flujoNoValidoTienda","value" => 0));?>
					          <?php echo $this->Form->input('type',array('type' => 'hidden','label' => 'Flujo no válido','id' => "typeFlujo","value" => 0));?>
					          <?php echo $this->Form->input('user_receptor',array('type' => 'hidden','label' => 'Flujo no válido','id' => "typeFlujo","value" => AuthComponent::user("id")));?>
					        </div>
					        <div class="col-md-12">
					      	
						      <?php 
						        echo $this->Form->input('reason',array('label' => "Asunto/Motivo/Solicitud/Requerimiento* :",'placeholder' => 'Por favor ingresa un nombre para esta Solicitud o Requerimiento',"required","id" => "reasonFlujoTienda"));
						      ?>
						    </div>
						    <div class="row">						    	
							    <div class="col-md-6 pl-4">
							    	<?php echo $this->Form->input('image', array("label" => "Selecciona o arrastra la imagen del contacto con el cliente", "type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M", "class" => "dropify" )) ?>
							      
							    </div>
							    <div class="col-md-6">
							    	<?php echo $this->Form->input('image_paste', array("label" => "Copia y pega la captura del contacto con el cliente", "type" => "text" )) ?>

							      <div class="imagenPreview" style="display: none">
							      	<a href="" class="btn btn-danger deleteImg float-right">
							      		<i class="fa fa-trash"></i>
							      	</a>
							      	<img src="" alt="" id="previewData" class="img img-fluid w-50">
							      </div>
							    </div>
						    </div>
						    <div id="user_asignado_div" class="mt-2">
						        
						    </div>
						    <p style="color: red" id="validacion_texto">Todos los campos marcados con (*) son requeridos</p>
						    <div class="col-md-12">
						      	<input type="submit" value="Guardar flujo express" class="btn btn-success pull-right" >
						    </div>
					      </div>
					      
						</div>				
						
				</div>
				
			</div>
		</form> 
	</div>
</div>



<!-- Modal -->
<div class="modal fade " id="modalNewCustomerTienda" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Gestionar nuevo cliente</h5>
      </div>
      <div class="modal-body" id="cuerpoCustomer">
        <div class="cuerpoContactoClienteModal"></div>
        <div id="ingresoClienteModal"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>







<?php 
	
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script("lib/jquery.typeahead.js",								array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/prospectiveUsers/flujo_especial.js?".rand(),				array('block' => 'AppScript'));

 ?>