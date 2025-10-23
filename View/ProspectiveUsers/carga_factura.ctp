<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h2 class="titleviewer">Ingreso de facturas CRM</h2>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class=" blockwhite spacebtn20">
			<div class="row">
				<?php echo $this->Form->create('ProspectiveUser',array('id' => 'form_bill_carga_factura','enctype'=>'multipart/form-data', "class" => "col-md-12 w-100")); ?>
					<div class="row">
						
						<div class="col-md-8">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-4 col-sm-4">
										<div class="form-group">
											<?php echo $this->Form->input("bill_prefijo", array("label" => "Prefijo de la factura" ,"placeholder" => "Ingrese el código de la factura" ,"required","options" => ["KE"=>"KE","KEB"=>"KEB"], "id" => "ProspectiveUserBillPrefijoCargaFactura","required" )) ?>
										</div>
									</div>
									<div class="col-md-4 col-sm-4">
										<div class="form-group">
											<?php echo $this->Form->input("bill_code", array("label" => "Número de la factura" ,"placeholder" => "Ingrese el código de la factura" ,"required","onkeypress"=>"return valideKey(event);", "id" => "ProspectiveUserBillCodeCargaFactura","required" )) ?>
										</div>
									</div>
									
									<div class="col-md-4 col-sm-4">
										<div class="form-group">

											<?php if (AuthComponent::user("role") == "Asesor Externo"): ?>
												<?php $usuarios_asesores = [ AuthComponent::user("id") => AuthComponent::user("name") ]; ?>
											<?php endif ?>

											<?php echo $this->Form->input("bill_user", array("label" => "Usuario que realizó la venta", "options" => $usuarios_asesores ,"placeholder" => "Ingrese el código de la factura" ,"required","default" => AuthComponent::user("id") )) ?>
										</div>
									</div>
									<div class="col-md-4 col-sm-4">
										<div class="form-group">
											<a href="" class="btn btn-warning mt-1 borraDatos" style="display:none;">
												Borrar información
											</a>
											<a href="" class="btn btn-info mt-1 validarFacturaWoCa">
												Validar factura con WO
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="border col-md-12 datosWoCarga mt-3 py-2">
								
							</div>
						</div>
						<div class="col-md-4">
							<div class="col-md-12" id="datosBuscaSd" style="display:none;">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="BuscaCliente">Flujos asociados</label>
											<?php echo $this->Form->input("flows", array("label" => false, "id" =>"BuscaCliente", "options" => [], "required","multiple" => true)) ?>
											<?php echo $this->Form->input("products_data", array("label" => false, "id" =>"products_data", "type" => "hidden")) ?>
										</div>
									</div>
									<div class="col-md-12">
										<input type="submit" value="Guardar información" class="btn btn-success mt-4">
									</div>
								</div>
							</div>
						</div>	
					</div>
					
				<?php echo $this->Form->end() ?>
			</div>
		</div>
	</div>

</div>

<div class="modal fade" id="modalSeleccionProductos" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="display: none !important;">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Selección de productos a facturar </h2>
      </div>
      <div class="modal-body" id="cuerpoSeleccion">
      </div>
      <div class="modal-footer">
        <a class="btn btn-outline-dark cancelmodal" data-id="0" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>



<?php echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>

<?php 	echo $this->Html->script("controller/prospectiveUsers/carga_factura.js?".rand(),			array('block' => 'AppScript'));  ?>