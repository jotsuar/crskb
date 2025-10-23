<?php 
$whitelist = array('127.0.0.1','::1' ); ?>
<?php echo $this->Html->css(array('lib/jquery.typeahead.css'), array('block' => 'AppCss'));?>
<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
	     <i class="fa fa-1x flaticon-growth"></i>
	    <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
</div>
<div class="blockwhite spacebtn20">
	<div class="row">
		<div class="col-md-12">
			<h2 class="titleviewer">Creación de orden de pedido - Paso negociado</h2>
		</div>
	</div>
</div>
<div class="spacebtn20">
	<?php echo $this->Form->create('Order',array('type'=>"file",'data-parsley-validate')); ?>
		<div class="row">
			<div class="col-md-12 ">
			<div class="blockwhite">
				<div class="row">
					<div class="col-md-12">
						<h2 class="text-info text-center mb-3">
							Datos del flujo
						</h2>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?php echo $this->Form->input('prospective_user_id',["label" => "Flujo asociado", "class" => "form-control", "type" => "text","required", "div" => false, "min" => 20, "value" => $flow, "readonly"]); ?>
							<?php echo $this->Form->input('header_id',["label" => "Flujo asociado", "class" => "form-control", "type" => "hidden","required", "div" => false, "value" => $datos["ProspectiveUser"]["country"] == "Colombia" ? 1 : 3, "readonly"]); ?>
							<!-- <a href="" class="btn btn-info mt-3 float-right" id="searchFlowOrder">
								<i class="fa fa-search vtc"></i> Buscar flujo
							</a> -->
						</div>
					</div>
					<div class="col-6 d-nones">
						<div class="form-group">

							<?php if (!$existWo): ?>
								<h2 class="text-center text-danger" >
									No se puede continuar debido a que el cliente no existe en la plataforma de World Office, solicita su creación.
									<i class="fa fa-x fa-warning" style="    animation: spinner-grow 0.5s ease-in-out infinite;"></i>

									<?php if (empty($requestExists)): ?>
										<a href="" class="btn btn-info requestCreateClient" data-type="<?php echo isset($datosCliente["legal"]) ? 'legal' : 'natural' ?>" data-uid="<?php echo $datosCliente['id'] ?>" data-flujo="<?php echo $flow ?>">
											Solicitar creación <i class="fa fa-plus vtc"></i>
										</a>
									<?php else: ?>
										<br>
										Ya se solicitó la creación
									<?php endif ?>

									
								</h2>
								<?php echo $this->Form->hidden('quotation_id',[]); ?>
							<?php else: ?>
								<?php echo $this->Form->input('quotation_id',["label" => "Cotización final (Tener en cuenta que con esta se validará finalmente al momento de aprobar) ", "class" => "form-control","required", "div" => false, "empty" => "seleccionar" ]); ?>
							<?php endif ?>

							
						</div>
					</div>	
					<div class="col-2 d-none">
							<a href="" class="btn btn-info mt-4 float-right" id="cargaCot">
								<i class="fa fa-check vtc"></i> Cargar información de cotización
							</a>
					</div>
				</div>
			</div>
			</div>
			<div class="col-md-12 mt-4">
				<div class="row">
					<div class="col-md-7 ">
					<div class=" blockwhite">
						<div class="row">
							<div class="col-md-12">
								<h2 class="text-info text-center mb-3">
									Datos del pedido
								</h2>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?php echo $this->Form->input('nacional',["label" => "Pedido nacional o internacional","options"=>["1"=>"Nacional","2" =>"Internacional"], "class" => "form-control","required", "div" => false, ]); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?php echo $this->Form->input('payment_type',["label" => "Tipo de pago","options"=>Configure::read("PAYMENT_TYPE"), "class" => "form-control","required", "div" => false, ]); ?>
								</div>
							</div>
							<div class="col-md-12 mt-2">
								<div class="form-group">
									<?php echo $this->Form->input('payment_text',["label" => "Texto adicional al pago", "class" => "form-control","type"=>"textarea", "div" => false, ]); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?php echo $this->Form->input('user_id',["label" => "Vendedor", "class" => "form-control","required", "div" => false, "value" => $default_user ]); ?>
									<?php echo $this->Form->input('factura',["type" => "hidden", "class" => "form-control","required", "div" => false, "value" => 0 ]); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="deadline">Fecha límite del pedido</label>
									<?php echo $this->Form->text('deadline',["label" => "Fecha límite", "class" => "form-control","required", "div" => false, "type" =>"date","min" => date("Y-m-d"),"value" => date("Y-m-d"),"readonly" ]); ?>
								</div>
							</div>
							<div class="col-md-8 mt-2">
								<div class="form-group">
									<?php echo $this->Form->input('note',["label" => "Comentarios adicional a la orden", "class" => "form-control","type"=>"textarea", "div" => false, ]); ?>
								</div>
							</div>
							<div class="col-md-4 mt-2">
								<div class="form-group">
									<?php echo $this->Form->input('document',["label" => "Archivo orden de compra del cliente", "class" => "form-control","type"=>"file", "div" => false,"data-allowed-file-extensions" => "pdf jpg png jpeg gif", "data-max-file-size" => "5M", "class" => "dropify" ]); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									
								</div>
							</div>
						</div>
					</div>
					</div>
					<div class="col-md-5 ">
					<div class="blockwhite">
						<div class="row">
							<div class="col-md-12">
								<h2 class="text-info text-center mb-3">
									Datos del cliente
								</h2>
							</div>
							<div class="col-md-12">
								<table class="table table-bordered">
									<?php echo $this->Form->input("cliente_id",["type" => "hidden", "value" => $datosCliente["id"] ]) ?>
									<?php if (isset($datosCliente)): ?>
										<tr>
											<th class="py-0">
												Tipo de cliente
											</th>
											<td>
												<?php if (isset($datosCliente["legal"])): ?>
													Empresa
													<?php echo $this->Form->input("clients_legal_id",["type" => "hidden", "value" => $datosCliente["clients_legals_id"] ]) ?>
													<?php echo $this->Form->input("contacs_user_id",["type" => "hidden", "value" => $datosCliente["id"] ]) ?>
												<?php else: ?>
													Persona Natural
													<?php echo $this->Form->input("clients_natural_id",["type" => "hidden", "value" => $datosCliente["id"] ]) ?>
												<?php endif ?>
											</td>
										</tr>
										<tr>
											<th class="py-0">
												Cliente
											</th>
											<td>
												<p>
													
													<?php echo isset($datosCliente["legal"]) ? $datosCliente["legal"] : $datosCliente["name"] ?>
												</p>
												<p>
													
													<?php $clientes = array_merge($clientsLegals,$clientsNaturals); ?>
													<?php if (in_array(AuthComponent::user("role"),["Logística","Gerente General"])): ?>
														
														<a href="" class="btn btn-sm btn-success btn-xs" id="showNewChange" data-type="1">
															<i class="fa fa-recycle vtc"></i> Cambiar cliente
														</a>
														<a href="" class="btn btn-sm btn-info btn-xs" id="<?php echo isset($datosCliente["legal"]) ? "editLegalCustomer" : "editCustomer" ?>" data-id="<?php echo $datosCliente["id"] ?>">
															<i class="fa fa-pencil vtc"></i> Editar cliente
														</a>
													<?php endif ?>
													<?php if (!isset($datosCliente["legal"])): ?>
														<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar" class="btn-sm btn-dark btn btn_change_juridico_cliente" data-uid="<?php echo $datosCliente['id'] ?>" data-flujo="<?php echo $flow ?>">
															Cambiar cliente a jurídico <i class="fa fa-fw fa-compass vtc"></i>
														</a>
													<?php endif ?>
												</p>

												<div class="row mt-4" id="nuevoClienteDiv" style="display:none">
													<div class="col-md-12">														
														<?php echo $this->Form->input('clients_natural_id',array('label' => 'Cambiar cliente',"required" => false, "options" => $clientes, "empty" => "Busca y selecciona un cliente", "id" => "flujoTiendaCliente" )); ?>
													</div>
													<div class="col-md-12">
														<div class="selectContactTienda"></div>
													</div>
													<div class="col-md-6">
														<a href="" class="btn btn-block btn-sm btn-xs btn-success" id="changeCustomerOk" data-type="1">
															<i class="fa fa-recycle vtc"></i> Cambiar cliente
														</a>
													</div>
													<div class="col-md-6">
														<a href="" class="btn btn-block btn-sm btn-warning" id="changeCustomerDatos" data-type="1">
															<i class="fa fa-plus vtc"></i> Crear nuevo cliente
														</a>
													</div>													
												</div>												
											</td>
										</tr>
										<tr>
											<th class="py-0">
												Contacto
											</th>
											<td>
												<?php echo isset($datosCliente["legal"]) ? $datosCliente["name"] : "N/A" ?>
											</td>
										</tr>
										<tr>
											<th class="py-0">
												Nit o identificación
											</th>
											<td>
												<?php echo $datosCliente["identification"] ?>
											</td>
										</tr>
										<tr>
											<th class="py-0">
												Teléfono(s)
											</th>
											<td>
												<?php echo empty($datosCliente["telephone"]) ? "N/A" : $datosCliente["telephone"] ?> / <?php echo !empty($datosCliente["cell_phone"]) ? $datosCliente["cell_phone"] : "" ?>
											</td>
										</tr>
										<tr>
											<th class="py-0">
												Correo electrónico
											</th>
											<td>
												<?php echo $datosCliente["email"] ?>
											</td>
										</tr>
									<?php else: ?>
										<tr>
											<th class="py-0">
												Tipo de cliente
											</th>
											<td>
												
											</td>
										</tr>
										<tr>
											<th class="py-0">
												Cliente
											</th>
											<td>
											</td>
										</tr>
										<tr>
											<th class="py-0">
												Contacto
											</th>
											<td>
												
											</td>
										</tr>
										<tr>
											<th class="py-0">
												Nit o identificación
											</th>
											<td>
												
											</td>
										</tr>
										<tr>
											<th class="py-0">
												Teléfono(s)
											</th>
											<td>
												
											</td>
										</tr>
										<tr>
											<th class="py-0">
												Correo electrónico
											</th>
											<td>
												
											</td>
										</tr>		
									<?php endif ?>
									
									
								</table>
							</div>
						</div>
						</div>
					</div>
					<div class="col-md-12 p-4 d-none">
							<div class="blockwhite">
								<div class="row my-3">
									<div class="col-md-4">
										<div class="form-group">
											<?php echo $this->Form->input("bill_code", array("label" => "Número de la factura" ,"placeholder" => "Ingrese el código de la factura" ,"required"=>false,"onkeypress"=>"return valideKey(event);" )) ?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php echo $this->Form->input("bill_prefijo", array("label" => "Prefijo de la factura" ,"placeholder" => "Ingrese el código de la factura" ,"required"=>false)) ?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<a href="" class="btn btn-warning cargaDocument mt-4">
												Cargar información desde factura
											</a>
											<a href="" class="btn btn-warning borraDocument mt-4" style="display:none">
												Borrar información de factura factura
											</a>
										</div>
									</div>
								</div>
							</div>
					</div>
					<div class="col-md-12 p-1">
						<button type="button" class="btn btn-success float-right" id="validateBtnForm">
							Guardar pedido y pasar a estado negociado
						</button>
					</div>
					
				</div>
			</div>
		</div>
	</form>
</div>

<!-- Modal -->
<div class="modal fade " id="modalChangeNatural" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Cambiar cliente natural a jurídico</h5>
      </div>
      <div class="modal-body" id="cuerpoCambia">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade " id="modalNewOrderTienda" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Gestionar cliente</h5>
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


<div class="modal fade " id="requestCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Solicitar crear cliente</h5>
      </div>
      <div class="modal-body" id="cuerpoRequest">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	
	var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";

</script>


<?php 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script("lib/jquery.typeahead.js",	array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/orders/admin.js?".rand(),				array('block' => 'AppScript'));
?>


