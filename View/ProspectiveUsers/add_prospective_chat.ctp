<?php //$clientes = array_merge($clientsLegals,$clientsNaturals); ?>

<?php echo $this->Form->create('ProspectiveUser',array("id" => "formGeneralCustomer","type" => "file", "url" => Router::url( $this->here, true )  )); ?>
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
								<?php echo $this->Form->input('client_data',array("required" , "type" => "hidden" , "id" => "clientDataInfo" )); ?>
							</div>
						</div>
					</div>
				</div>
        <div class="col-md-12">
          <?php echo $this->Form->input('origin',array('label' => 'Origen de la Solicitud:', 'value' => 'Chat', "type" => "hidden" )); ?>
        </div>
      </div>
      <?php 
        echo $this->Form->input('reason',array('label' => "Asunto/Motivo/Solicitud/Requerimiento",'placeholder' => 'Por favor ingresa un nombre para esta Solicitud o Requerimiento',"required"));
        echo $this->Form->input('description',array('type' => 'textarea','rows'=>'3','label' => "Detalle de la Solicitud",'placeholder' => 'Por favor detalla el requerimiento del cliente',"required"));
      ?>
      <div id="user_asignado_div" class="mt-2">
        <?php echo $this->Form->input('user_id',array('label' => 'Asignado al asesor:','default'=>$user_id, 'options' => (isset($usuarios)) ? $usuarios: "", "empty" => "Seleccionar asesor","required"));  ?>
      </div>

      <input type="submit" value="Guardar oportunidad de venta" class="btn btn-success pull-right" >
</form> 

<!-- formulario llamado desde el navbar con botòn azul -->