<?php //$clientes = array_merge($clientsLegals,$clientsNaturals); ?>

<?php echo $this->Form->create('ProspectiveUser',array("id" => "formGeneralCustomer","type" => "file")); ?>
      <div class="row"> 
		
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<?php echo $this->Form->input('country',array('label' => "País","options" => Configure::read("PAISES")));?>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="clienteNaturalProspective">
				<div class="row">
					<div class="col-md-9 col-lg-9">
						<?php echo $this->Form->input('clients_natural_id',array('label' => 'Selecionar Cliente',"required" , "type" => "select" , "options" => [], "empty" => "Busca y selecciona un cliente" )); ?>
					</div>
					
					<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("email") == "servicioalcliente@kebco.co" || AuthComponent::user("email") == "ventas2@almacendelpintor.com" ): ?>
					<?php endif ?>

						<div class="col-md-3 col-lg-3 text-center ">
							<div class="btn btn-success alignforlabel">	
								<i class="fa fa-x fa-plus-circle addNewCustomerProspective" data-type="1"> <span>Crear nuevo cliente</span></i> 
							</div>
						</div>
				</div>
				<div class="selectContact"></div>
			</div>
		</div>

        <div class="col-md-10">
          <?php echo $this->Form->input('origin',array('label' => 'Origen de la Solicitud:', 'options' => Configure::read("variables.origenContact"), "empty" => "Seleccionar origen de la solicitud", "required" )); ?>
        </div>
        <div class="col-md-2 pt-3" style="display:none;">
          <?php echo $this->Form->input('flujo_no_valido',array('type' => 'checkbox','label' => 'Flujo no válido'));?>
        </div>
      </div>
      <?php 
        echo $this->Form->input('reason',array('label' => "Asunto/Motivo/Solicitud/Requerimiento",'placeholder' => 'Por favor ingresa un nombre para esta Solicitud o Requerimiento',"required"));
        echo $this->Form->input('description',array('type' => 'textarea','rows'=>'3','label' => "Detalle de la Solicitud",'placeholder' => 'Por favor detalla el requerimiento del cliente',"required"));
      ?>
      <div id="user_asignado_div" class="mt-2">
        <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
          <?php echo $this->Form->input('user_id',array('label' => 'Asignado al asesor:','value'=>AuthComponent::user('id'), 'options' => (isset($usuarios)) ? $usuarios: [],"required"));  ?>
        <?php else: ?>
          <?php echo $this->Form->input('user_id',array('type' => 'hidden','value'=>AuthComponent::user('id') ));  ?>
          
        <?php endif ?>
      </div>
      <?php echo $this->Form->input('image', array("label" => "Selecciona o arrastra la imagen del contacto con el cliente", "type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M", "class" => "dropify" )) ?>
      <?php echo $this->Form->input('image_paste', array("label" => "Copia y pega la captura del contacto con el cliente", "type" => "text" )) ?>

      <div class="imagenPreview" style="display: none">
      	<a href="" class="btn btn-danger deleteImg float-right">
      		<i class="fa fa-trash"></i>
      	</a>
      	<img src="" alt="" id="previewData" class="img img-fluid w-100">
      </div>

      <input type="submit" value="Guardar oportunidad de venta" class="btn btn-success pull-right" >
</form> 

<!-- formulario llamado desde el navbar con botòn azul -->