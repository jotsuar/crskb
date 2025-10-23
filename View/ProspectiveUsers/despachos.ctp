<?php $clientes = array_merge($clientsLegals,$clientsNaturals); ?>
<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-aguamarina big">
         <i class="fa fa-1x flaticon-logistics-delivery-truck-and-clock"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Despachos</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
					<a href="<?php echo $this->Html->url(array('controller'=>'conveyors','action'=>'index')) ?>" class="btn btn-info pull-right">
			            Gestión de transportadoras
			        </a>
				<?php endif ?>
				<h1 class="nameview spacebtnm">BUSCADOR DE DESPACHOS REALIZADOS</h1>
				<ul class="subdespachos">
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'information_dispatches')) ?>">Flujos pendientes y parciales de despacho</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches')) ?>"> Despachos por confirmar</a>
					</li>
					<li class="activesub">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>"> Despachos enviados / Finalizados</a>
					</li>
				</ul> 
			</div>
		</div>
	</div>

	<div class="row">			
		<div class="col-md-3">
			<div class="blockwhite">
				<h1 class="nameview spacebtnm">BUSCADOR POR FILTROS</h1>
				<?php echo $this->Form->create(false,["type" => "get"]); ?>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<?php echo $this->Form->input("txt_buscador_flujo", [ "label" => "Por flujo","id" => "txt_buscador_flujo", "placeholder" => "Ingresa el ID", "value" => isset($q) ? $q["txt_buscador_flujo"] : ""  ]) ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<?php echo $this->Form->input("state_flow", [ "label" => "Por estado de flujo","id" => "state_flow", "options" => ["5"=>"Pago","6"=>"Despacho parcial","8"=>"Terminado"] , "value" => isset($q) ? $q["state_flow"] : "", "empty" => "Seleccionar"  ]) ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<?php echo $this->Form->input("txt_buscador_cliente", ["options" => $clientes, "empty" => "Seleccionar y buscar por cliente", "label" => "Por cliente","id" => "flujoTiendaCliente", "value" => isset($q) ? $q["txt_buscador_cliente"] : "" ]) ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<?php echo $this->Form->input("txt_buscador_transportadora", ["options" => $conveyors, "empty" => "Seleccionar y buscar por transportadora", "label" => "Por transportadora","id" => "txt_buscador_transportadora", "value" => isset($q) ? $q["txt_buscador_transportadora"] : "" ]) ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<?php echo $this->Form->input("txt_buscador_contacto", [ "label" => "Buscador por contacto","id" => "txt_buscador_contacto", "placeholder" => "Por contacto", "value" => isset($q) ? $q["txt_buscador_contacto"] : "" ]) ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<?php echo $this->Form->input("txt_buscador_guia", [ "label" => "Por número de guia","id" => "txt_buscador_guia", "placeholder" => "Buscador por número de guia", "value" => isset($q) ? $q["txt_buscador_guia"] : ""  ]) ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_buscador_fecha">Buscador por fecha</label>
								<?php echo $this->Form->text("txt_buscador_fecha", [ "label" => "Por fecha","id" => "txt_buscador_fecha", "placeholder" => "Buscador por fecha", "type" => "date", "max" => date("Y-m-d"), "class" => "form-control", "value" => isset($q) ? $q["txt_buscador_fecha"] : "" ]) ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<button class="btn btn-info btn-block mt-4">
									Buscar <i class="fa fa-search vtc"></i>
								</button>
							</div>
						</div>
					</div>
				</form>	
			</div>
		</div>

		<div class="col-md-9">
			<div class="blockwhite mb-3 text-center">
				<h2 class="nameview color-info">Resultados de la búsqueda - Mostrando (<?php echo count($despachos); ?>) despachos </h2>
			</div>
												
			<?php if (!empty($despachos)): ?>
				<?php foreach ($despachos as $key => $value): ?>
					<div class="blockwhite mb-3 pt-2">
							<div class="row bg-gris p-2">
								<div class="<?php echo count($value["ProspectiveUser"]["despachos"]) > 2 ? "col-md-12" : "col-md-4" ?>">
									<div>
										<strong class="uppercase titlecolumbox">Datos del flujo</strong>
										<hr>
									</div>
									<div class="dinlinebox">
										<b>Flujo</b>: 
										<a class="idflujotable flujoModal modalFlujoClick" href="#" data-uid="<?php echo $value["ProspectiveUser"]["id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value["ProspectiveUser"]["id"]); ?>">
											<?php echo $value['ProspectiveUser']['id'] ?>
										</a>
										<div class="dropdown d-inline styledrop ">
											<a class="btn btn-outline-secondary dropdown-toggle btn-sm p-1 rounded mt-1" href="#" role="button" id="gruposproducts<?php echo $value["ProspectiveUser"]["id"] ?>" data-toggle="dropdown" aria-expanded="false">Opciones  								  
											</a>

											<div class="dropdown-menu" aria-labelledby="gruposproducts<?php echo $value["ProspectiveUser"]["id"] ?>">												
												<?php if (!empty($value["ProspectiveUser"]["bill_code"])): ?>
													<a class="dropdown-item pointer listFact" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>">
														<i class="fa fa-1x fa-eye vtc"></i>
														Ver factura								
													</a>
												<?php endif ?>
												<?php if ($value["ProspectiveUser"]["state"] != 3): ?>							
													<a class="dropdown-item <?php echo empty($value["ProspectiveUser"]["bill_code"]) ? 'pointer': 'pointer' ?> info_bill" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-bill="<?php echo $value["ProspectiveUser"]["bill_code"] ?>">
														<i class="fa fa-1x fa-pencil vtc"></i>
														<?php echo !empty($value["ProspectiveUser"]["bill_code"]) ? "Nueva factura" : "Ingresar factura" ?>	
													</a>
												<?php endif ?>
												<?php if ($value["ProspectiveUser"]["state"] == 5 && $value["ProspectiveUser"]["origin"] == "Tienda"): ?>
														<a class="ingresoPagoTienda dropdown-item " data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>">
															<i class="fa fa-1x fa-money vtc"></i>
															Ingresar comprobante de pago								
														</a>
												<?php endif ?>
												<?php if ($value["ProspectiveUser"]["origin"] == "Tienda"): ?>
													
														<a class="dropdown-item " target="_blank" href="<?php echo $this->Html->url(["action" => "generate_document", $value['ProspectiveUser']['id']]) ?>">
															<i class="fa fa-1x fa-file vtc"></i>
															Generar documento de entrega								
														</a>

												<?php endif ?>

												<a href="javascript:void(0)" class="dropdown-item btn_confirm_entrega" data-uid="<?php echo $value["ProspectiveUser"]["id"] ?>"><i class="fa fa-1x fa-check vtc"></i>	Confirmar recibido</a>

							  				</div>
										</div>
										<br>

										<b>Cliente</b>: <?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']), 130,array('ellipsis' => '...','exact' => false)); ?> <br>
										<b>Estado:</b><?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?> <br>
										<b>Requerimiento</b>: <?php echo $value["ProspectiveUser"]["description"] ?><br>

										<b> Usuario asignado:</b> <?php echo $this->Utilities->find_name_adviser($value["ProspectiveUser"]["user_id"]) ?>	<br>
										<b>Cotización:</b>   <a class="btnmin getQuotationId modalFlujoClick" data-quotation="<?php echo $this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]) ?>" href="#">Ver cotización</a>
										
									</div>
								</div>
								<?php foreach ($value["ProspectiveUser"]["despachos"] as $keyDespacho => $valueDespachos): ?>
									<div class="col-md-4">
										<?php if ($valueDespachos["FlowStage"]["state_flow"] == "Despachado"): ?>
											<div class="mb-4">
												<strong class="uppercase titlecolumbox">Datos del despacho</strong>
												<hr>
											</div>
											<div class="dinlinebox">
												<b>Transportadora: </b> <?php echo $valueDespachos["FlowStage"]["conveyor"] ?> <br>
												<b>Guía: </b> <?php echo $valueDespachos["FlowStage"]["number"] ?> 
													<?php if (!empty($valueDespachos["FlowStage"]["conveyor_id"]) && ($valueDespachos["FlowStage"]["number"] != "" && $valueDespachos["FlowStage"]["number"] != "0" ) ): ?>
														<a class="btn btn-info btn-sm" href="<?php echo $this->Utilities->getUrlConveyor($valueDespachos["FlowStage"]["conveyor_id"]) ?>" target="_blank">Rastrear</a>
													<?php endif ?>
												 <br>
												<b>Fecha: </b> <?php echo $valueDespachos["FlowStage"]["created"] ?> <br>
												<b>Cantidad enviada: </b> <?php echo $valueDespachos["FlowStage"]["products_send"] ?> <br>
												<b>Comprobante: </b>
												<?php if ($valueDespachos['FlowStage']['document'] != ''){ ?>
													<span class="imgmin-product"  dataname="Guia: <?php echo $valueDespachos["FlowStage"]["number"] ?>" dataimg="<?php echo $this->Html->url('/img/flujo/despachado/'.$valueDespachos['FlowStage']['document']) ?>" datacomprobantet="<?php echo $this->Html->url('/img/flujo/despachado/'.$valueDespachos['FlowStage']['document']) ?>">
														Ver
													</span><br>
												<?php } else { ?>
													No se adjunto <br>
												<?php } ?>
												<b>Fotos: </b>
												<?php if ($valueDespachos['FlowStage']['image_products'] != ''){ ?>
													<span dataimg="<?php echo $this->Html->url('/img/flujo/despachado/'.$valueDespachos['FlowStage']['image_products']) ?>" datacomprobantet="<?php echo $this->Html->url('/img/flujo/despachado/'.$valueDespachos['FlowStage']['image_products']) ?>"  class="imgmin-product" dataname="Guia: <?php echo $valueDespachos["FlowStage"]["number"] ?>">
														Ver
													</span>
												<?php } else { ?>
													No se adjunto
												<?php } ?>
											</div>
										<?php else: ?>
											<div>
												<strong class="uppercase titlecolumbox">Datos del contacto</strong>
												<hr>
											</div>
											<div class="dinlinebox">											
												<b>Recibe: </b> <?php echo $valueDespachos["FlowStage"]["contact"] ?> <br>
												<b>Teléfono: </b> <?php echo $valueDespachos["FlowStage"]["telephone"] ?> <br>
												
												<b>Ciudad: </b> <?php echo $this->Text->truncate(strip_tags($valueDespachos['FlowStage']['city']), 20,array('ellipsis' => '.','exact' => false)); ?>
												<br>

												<b>Dirección: </b> <?php echo $valueDespachos["FlowStage"]["address"] ?> <?php echo !empty($valueDespachos["FlowStage"]["additional_information"]) ?> <br>
												<b>Flete: </b> <?php echo $valueDespachos["FlowStage"]["flete"] ?> <br>

												<b>Fecha: </b> <?php echo $valueDespachos["FlowStage"]["created"] ?> <br>
											</div>
										<?php endif ?>
									</div>
								<?php endforeach ?>
							</div>
					</div>
				<?php endforeach ?>

			<?php else: ?>
				<div class="text-center">
					<h2>Tu búsqueda no ha arrojado resultados, inténtalo nuevamente</h2>
				</div>
			<?php endif ?>

	
		<div class="row numberpages">
			<?php
				echo $this->Paginator->first('<< ', array('class' => 'prev'), null);
				echo $this->Paginator->prev('< ', array(), null, array('class' => 'prev disabled'));
				echo $this->Paginator->counter(array('format' => '{:page} de {:pages}'));
				echo $this->Paginator->next(' >', array(), null, array('class' => 'next disabled'));
				echo $this->Paginator->last(' >>', array('class' => 'next'), null);
			?>
			<b> <?php echo $this->Paginator->counter(array('format' => '{:count} en total')); ?></b>
		</div>
	</div>
</div>
</div>


<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>



<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp')); 
	echo $this->Html->script("controller/prospectiveUsers/flujo_tienda.js?".rand(),	array('block' => 'AppScript'));
	echo $this->Html->script("controller/prospectiveUsers/verify_payment.js?".rand(),	array('block' => 'AppScript'));
?>

 <?php echo $this->element("flujoModal"); ?>




<!-- Modal -->
<div class="modal fade " id="modalBillInformation" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document" style="max-width: 85% !important;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Datos de factura de venta</h5>
      </div>
      <div class="modal-body" id="cuerpoBill">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="modalBillList" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Listado de facturas ingresadas</h5>
      </div>
      <div class="modal-body" id="cuerpoBillList">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade " id="modalIngresoPago" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
    <div class="modal-content">
	    <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	        <h5 class="modal-title" id="exampleModalScrollableTitle">Ingreso de pago por venta en tienda</h5>
	    </div>
      	<div class="modal-body" id="cuerpoPago">
	      	<?php echo $this->Form->create('ProspectiveUser',array('id' => 'formPagoTienda','enctype'=>'multipart/form-data',"url" => array("controller" => "ProspectiveUsers","action"=>"savePaymentTienda"))); ?>
		        <div class="form-group">
		        	<?php echo $this->Form->input('flujo_id',array("readonly", "label" => "Flujo: ", "type" => "text", "required" => true, "class" => "form-control" )); ?>	        
		        </div>
		        <div class="form-group">
		        	<?php echo $this->Form->input('value',array("label" => "Valor del pago", "required" => true, "class" => "form-control" )); ?>	        
		        </div>
		        <div class="form-group">
		        	<?php echo $this->Form->input('identificator',array("label" => "Número de Voucher o ID de transacción del pago", "required" => true, "class" => "form-control" )); ?>	        
		        </div>
		        <div class="form-group">
		        	<?php echo $this->Form->input('img',array("label" => "Comprobande de pago","type" => "file", "required" => true, "class" => "form-control" )); ?>	        
		        </div>
		        <div class="type_payu">
							<?php
								echo $this->Form->input('payment',array('label' => 'Medio de pago','options' => $medios, "default" => "Efectivo"));
							?>
							<small class="text-danger mb-5" id="datafonoText">Recuerda que para pago por datáfono debes indicarle al cliente que debe pagar el 3% adcional por comision en el banco o modificar la cotización agregando dicho valor</small>

						</div>
						<p class="copiealert mt-2">
							Recuerda que cuando proceses este flujo a “pagado”, se generará una alerta para que el área de contabilidad verifique y apruebe este pago con el comprobante que estas adjuntando
						</p>
		        <div class="form-group">
		        	<input type="submit" class="btn btn-success float-right" value="Guardar pago">
		        </div>
	      	</form>
      	</div>
      	<div class="modal-footer">
        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      	</div>
    </div>
  </div>
</div>

