<?php 
	echo $this->Html->css(array('lib/jquery.typeahead.css'),						array('block' => 'AppCss'));
?>

<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h2 class="titleviewer">Creación de flujo en tienda</h2>
			</div>
		</div>
	</div>
	<div class=" blockwhite">
		<?php echo $this->Form->create('ProspectiveUser',array("id" => "formCreateTienda")); ?>
			<div class="row">
				<div class="col-md-8">
					<?php //$clientes = array_merge($clientsLegals,$clientsNaturals); ?>
					<div class="row"> 								
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<?php echo $this->Form->input('country',array('label' => "País","options" => Configure::read("PAISES"),"id"=>"paisesFlujoTienda"));?>
								</div>
								<div class="col-md-6 col-sm-6">
									<?php echo $this->Form->input('user_id',array('label' => 'Asignado al asesor:','value'=>AuthComponent::user('id'), 'options' => (isset($usuarios)) ? $usuarios: "", "empty" => "Seleccionar asesor","required","id"=>"usuarioFlujoTienda"));  ?>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="clienteNaturalProspective">
								<div class="row">
									<div class="col-md-9 col-9">
										<?php echo $this->Form->input('clients_natural_id',array('label' => 'Selecionar Cliente',"required", "options" => [], "type" => "select" , "empty" => "Busca y selecciona un cliente", "id" => "flujoTiendaCliente" )); ?>
									</div>
									<div class="col-md-3 col-3 text-center ">
										<div class="btn btn-secondary alignforlabel addNewCustomerProspectiveCliente" data-type="1">	
											<i class="fa fa-x fa-plus-circle"></i> <span>Crear</span>
										</div>
									</div>
								</div>
								<div class="selectContactTienda"></div>
							</div>
						</div>

				        <div class="col-md-10">
				          <?php echo $this->Form->input('origin',array('label' => 'Origen de la Solicitud:', 'value' => "Presencial", "required","id" => "originFlujoTienda", "type" => "hidden" )); ?>
				        </div>
				        <div class="col-md-2 pt-3">
				          <?php echo $this->Form->input('flujo_no_valido',array('type' => 'hidden','label' => 'Flujo no válido','id' => "flujoNoValidoTienda","value" => 0));?>
				        </div>
				      </div>
				      <?php 
				        echo $this->Form->input('reason',array('label' => "Asunto/Motivo/Solicitud/Requerimiento",'placeholder' => 'Por favor ingresa un nombre para esta Solicitud o Requerimiento',"required","id" => "reasonFlujoTienda"));
				      ?>
				      <div id="user_asignado_div" class="mt-2">
				        
				      </div>
				      <p style="color: red" id="validacion_texto">Todos los campos son requeridos</p>
				      
				</div>
				<div class="col-md-12 ">
					<h3 class="text-info">
						Por favor busque la referencia o el nombre de los productos vendido(s)
					</h3>
					<div class="typeahead__container">
				        <div class="typeahead__field">
				            <span class="typeahead__query">
				                <input class="js-typeahead" type="search" autofocus autocomplete="off" placeholder="Busca tu producto por nombre o referencia">
				            </span>
				        </div>
				    </div>
				    <div class="productos">
				    	<div class="row">
				    		<div class="col-md-12">
				    			<div class="table-responsive">
					    			<table class="table table-striped">
										<thead>
											<tr>
												<th>Foto</th>
												<th>Producto</th>
												<th>Margen</th>
												<th>Entrega</th>
												<th>Inventario actual</th>
												<th>Precio</th>
												<th>Cant.</th>
												<th>Subtotal</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody id="milista" class="removecolumns"></tbody>
									</table>
									<div class="form-group">
										<?php echo $this->Form->input('total',array('id'=>'totalCalculado','readonly' => true,'value' => '0',"label" => "Valor total")); ?>
									</div>
					    		</div>
					    		<input type="submit" value="Guardar venta en tienda" class="btn btn-success pull-right" >
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


<style>
	.listado_tabla_ordenada {
    	 cursor: initial !important; 
	}
</style>




<?php 
	
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script("lib/jquery.typeahead.js",								array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/prospectiveUsers/flujo_tienda.js?".rand(),				array('block' => 'AppScript'));

 ?>

 <?php echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); ?>
