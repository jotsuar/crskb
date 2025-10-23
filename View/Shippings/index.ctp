<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
	     <i class="fa fa-1x flaticon-growth"></i>
	    <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
</div>

<div class=" blockwhite spacebtn20">
	<div class="row ">
		<div class="col-md-6">
			<h2 class="titleviewer">Solicitudes de gestión despachos y/o facturación</h2>
		</div>
		<div class="col-md-6 text-right">
			<?php if ( date("H:i:s") <= $max_hour_envoice || date("H:i:s") <= $max_hour_remission || date("H:i:s") <= $max_hour_shipping): ?>
				
				<a href="<?php echo $this->Html->url(array('controller'=>'shippings','action'=>'add')) ?>" class="crearclientej crearDespachoBtn"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva solicitud</span></a>

			<?php endif ?>
		</div>
	</div>
</div>

<div class="blockwhite spacebtn20">
		<?php echo $this->Form->create(false,["type" => "get"]); ?>
			<div class="row" >		
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("id", [ "label" => "ID de solicitud","id" => "txt_id", "value" => isset($q) ? $q["id"] : "","class" => "form-control" ]) ?> 
					</div>
				</div>	
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("state", [ "label" => "Estado de solicitud","id" => "txt_state", "options" => ["0" => "Solicitud creada", "1" => "Solicitud en preparación", "2" => "Solicitud enviada y/o facturada","3" => "Solicitud entregada" ], "empty" => "Seleccionar" , "value" => isset($q) ? $q["state"] : "","class" => "form-control" ]) ?> 
					</div>
				</div>		
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("user_id", [ "label" => "Asesor asignado","id" => "asesor", "options" => $usuarios , "value" => isset($q) ? $q["user_id"] : "", "empty" => "Seleccionar asesor asignado" ,"class" => "form-control" ]) ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("flujo_id", [ "label" => "Buscar por flujo","id" => "asesor", "value" => isset($q) ? $q["flujo_id"] : "" ,"class" => "form-control", "type" => "number" ]) ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("cliente_id", [ "label" => "Buscar por cliente","id" => "cliente_id", "placeholder" => "Buscar por cliente" , "value" => isset($q) ? $q["cliente_id"] : "","class" => "form-control", "options" => $clientes, "empty" => "Seleccionar clientes" ]) ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<?php echo $this->Form->input("conveyor", [ "label" => "Buscar por empresa de transporte","id" => "conveyor", "placeholder" => "Buscar por número de parte" , "value" => isset($q) ? $q["conveyor"] : "","class" => "form-control", "options" => $conveyors, "empty" => "Seleccionar" ]) ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<?php echo $this->Form->input("guia", [ "label" => "No. Guia","id" => "guia" , "value" => isset($q) ? $q["guia"] : "","class" => "form-control", "placeholder" => "Buscar por número de guía" ]) ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<?php echo $this->Form->input("request_type", [ "label" => "Tipo de solicitud","id" => "txt_buscador", "options" => ["0" => "Despacho sin o con remisión", "1" => "Facturación", "2" => "Despacho y Facturación", "3" => "Remisión" ], "empty" => "Seleccionar" , "value" => isset($q) ? $q["request_type"] : "","class" => "form-control" ]) ?> 
					</div>
				</div>
				
			</div>
			<div class="row">
				
				<div class="col-md-4">
					<div class="form-group">
						<?php echo $this->Form->input("fechas", [ "label" => "Filtrar por fechas","id" => "fechas", "options" => ["1"=>"Fecha de Solicitud","2"=>"Fecha de Preparación","3" => "Fecha de envio", "4"=>"Fecha de finalización"] , "value" => isset($q) ? $q["fechas"] : "","class" => "form-control", "empty" => "Seleccionar opción" ]) ?>
					</div>
					
				</div>
				<div class="col-md-4">
					<input type="date" value="<?php echo $fechaInicioReporte ?>" class="form-control" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
					<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
					<div class="form-group">
						<span>Seleccionar rango de fechas:</span>
					</div>
					<div class="form-group">
						<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="form-group">
						<div class="row">
							<div class="col">
								<input type="submit" class="btn btn-block btn-success mt-4" value="Buscar">								
							</div>
							<?php if (isset($q) && !empty($q)): ?>
								
							<div class="col">
								<a href="<?php echo $this->Html->url(["action"=>"index"]) ?>" class="btn btn-block btn-warning mt-4" > Eliminar Filtro </a>								
							</div>
							<?php endif ?>
						</div>
					</div>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>

<div class=" blockwhite spacebtn20">
	<div class="table-responsive">
		<table cellpadding="0" cellspacing="0" class="table table-hovered">
		<thead>
		<tr>
				<th><?php echo $this->Paginator->sort('id',"# Solicitud"); ?></th>
				<th><?php echo $this->Paginator->sort('request_type',"Tipo de solicitud"); ?></th>
				<th><?php echo $this->Paginator->sort('document',"Ver guía/Remisión"); ?></th>
				<th><?php echo $this->Paginator->sort('bill_file',"Ver Factura"); ?></th>
				<th><?php echo $this->Paginator->sort('flow',"Flujo"); ?></th>
				<th>Cliente</th>
				<th><?php echo $this->Paginator->sort('user_id',"Solicita"); ?></th>
				<th><?php echo $this->Paginator->sort('guide',"Número de gúia"); ?></th>
				<th><?php echo $this->Paginator->sort('conveyor_id',"Transportadora"); ?></th>
				<th><?php echo $this->Paginator->sort('state',"Estado"); ?></th>
				<th><?php echo $this->Paginator->sort('created',"Fecha creado"); ?></th>
				<th><?php echo $this->Paginator->sort('date_preparation',"Fecha preparación"); ?></th>
				<th><?php echo $this->Paginator->sort('date_send',"Fecha enviado"); ?></th>
				<th><?php echo $this->Paginator->sort('date_end',"Fecha entregado"); ?></th>
				<th class="actions"><?php echo __('Acciones'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($shippings as $shipping): ?>
		<tr>
			<td class="text-center">
				#<?php echo $shipping["Shipping"]["id"] ?>
			</td>
			<td>
				<?php 

									switch ($shipping["Shipping"]["request_type"]) {
										case '0':
											echo "Despacho";
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
			<td>
				<?php if (!empty($shipping["Shipping"]["document"])): ?>					
					<a class="btn btn-info btn-sm" href="<?php echo $this->Html->url('/files/flujo/despachado/'.$shipping['Shipping']['document']) ?>" target="_blank" data-toggle="tooltip" title="Ver guía">
						 <i class="fa-1x fa fa-eye vtc"></i>
					</a>
				<?php endif ?>
				<?php if (!empty($shipping["Shipping"]["remision"])): ?>					
					<a class="btn btn-warning btn-sm" href="<?php echo $this->Html->url('/files/flujo/remisiones/'.$shipping['Shipping']['remision']) ?>" target="_blank" data-toggle="tooltip" title="Ver remisión">
						 <i class="fa-1x fa fa-file vtc"></i>
					</a>
				<?php endif ?>
			</td>
			<td>
				<?php if (!empty($shipping["Shipping"]["bill_file"])): ?>					
					<a class="btn btn-primary btn-sm" href="<?php echo $this->Html->url('/files/flujo/facturas/'.$shipping['Shipping']['bill_file']) ?>" target="_blank" data-toggle="tooltip" title="Ver factura">
						<i class="fa-1x fa fa-eye vtc"></i>
					</a>
				<?php endif ?>
			</td>
			<td><?php echo h($shipping['Order']['prospective_user_id']); ?>&nbsp;</td>
			<td><?php echo $this->Utilities->name_prospective($shipping['Order']['prospective_user_id'],true); ?>&nbsp;</td>
			<td><?php echo h($shipping['User']['name']); ?>&nbsp;</td>
			<td><?php echo h($shipping['Shipping']['guide']); ?>&nbsp;</td>
			<td>
				<?php echo $shipping['Conveyor']['name']; ?>
			</td>
			<td><?php 

					switch ($shipping['Shipping']['state']) {
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
								echo $this->Html->link('Despacho solicitado y factura cargada','javascript:void(0)',["class"=>"text-white btn-sm bg-danger"]);
							}elseif($shipping["Shipping"]["request_shipping"] == 2){
								echo "Despacho enviado y factura cargada";
							}else{
								echo "Solicitud entregada";
							}
							break;
					}

			 ?>&nbsp;</td>
			<td><?php echo h($shipping['Shipping']['created']); ?>&nbsp;</td>
			<td><?php echo h($shipping['Shipping']['date_preparation']); ?>&nbsp;</td>
			<td><?php echo h($shipping['Shipping']['date_send']); ?>&nbsp;</td>
			<td><?php echo h($shipping['Shipping']['date_end']); ?>&nbsp;</td>
			<td class="actions">
				<?php if ($shipping["Shipping"]["state"] == 1 && (AuthComponent::user("email") == "jotsuar@gmail.com" || in_array(AuthComponent::user("role"),["Gerente General","Logística"])) ): ?>
					<a href="<?php echo $this->Html->url(["action" => "return_request", $this->Utilities->encryptString($shipping['Shipping']['id']) ]) ?>" class="btn btn-danger btn-sm">
						Retornar estado  <i class="fa fa-retweet vtc"></i>
					</a>
				<?php endif ?>
				<?php if ($shipping["Shipping"]["state"] == 2 && (AuthComponent::user("email") == "jotsuar@gmail.com" || in_array(AuthComponent::user("role"),["Gerente General","Logística"])) ): ?>
					<a href="<?php echo $this->Html->url(["action" => "return_request_prepare", $this->Utilities->encryptString($shipping['Shipping']['id']) ]) ?>" class="btn btn-danger btn-sm">
						Retornar a preparación  <i class="fa fa-retweet vtc"></i>
					</a>
				<?php endif ?>
				<?php if ( ( $shipping["Shipping"]["state"] != 3 || ($shipping["Shipping"]["state"] == 3 && $shipping["Shipping"]["request_envoice"] == 1) || ($shipping["Shipping"]["state"] == 3 && $shipping["Shipping"]["request_shipping"] == 1) ) &&  (in_array(AuthComponent::user("email"), Configure::read("email_shippings") ) || AuthComponent::user("role") == "Gerente General" )  ): ?>

					<?php if (empty($flujos_without_shipping) || ( !empty($flujos_without_shipping) && in_array($shipping["Shipping"]["id"], $flujos_without_shipping) ) ): ?>
						<a href="<?php echo $this->Html->url(["action" => "change", $this->Utilities->encryptString($shipping['Shipping']['id']) ]) ?>" class="btn btn-warning btn-sm btnChangeState">
							Cambiar estado <i class="fa fa-pencil vtc"></i>
						</a>
					<?php endif ?>
				<?php endif ?>
				<?php if (empty($flujos_without_shipping) || ( !empty($flujos_without_shipping) && in_array($shipping["Shipping"]["id"], $flujos_without_shipping) ) ): ?>
					<a href="<?php echo $this->Html->url(["action" => "view", $this->Utilities->encryptString($shipping['Shipping']['id']) ]) ?>" class="btn btn-info btn-sm">
						Ver detalle  <i class="fa fa-eye vtc"></i>
					</a>
				<?php endif ?>
				<?php if ( 

					(

						(in_array(AuthComponent::user("email"), Configure::read("email_shippings") ) || AuthComponent::user("role") == "Gerente General" ) || $shipping["Shipping"]["user_id"] == AuthComponent::user("id")

					) && ($shipping["Shipping"]["request_type"] == 0 || $shipping["Shipping"]["request_type"] == 3) && $shipping['Shipping']['state'] == 3 && $shipping["Shipping"]["request_envoice"] == 0 && date("H:i:s") <=  $max_hour_envoice

				): ?>
					<?php if (empty($flujos_without_shipping) || ( !empty($flujos_without_shipping) && in_array($shipping["Shipping"]["id"], $flujos_without_shipping) ) ): ?>
						<a href="<?php echo $this->Html->url(["action" => "request_envoice", $this->Utilities->encryptString($shipping['Shipping']['id']) ]) ?>" class="btn btn-success btn-sm request_envoice">
							Solicitar facturación  <i class="fa fa-eye vtc"></i>
						</a>
					<?php endif ?>
				<?php endif ?>
				<?php if ( 
						(
							(in_array(AuthComponent::user("email"), Configure::read("email_shippings") ) || AuthComponent::user("role") == "Gerente General" ) || $shipping["Shipping"]["user_id"] == AuthComponent::user("id")

						) && $shipping["Shipping"]["request_type"] == 1 && $shipping['Shipping']['state'] == 3 && $shipping["Shipping"]["request_shipping"] == 0 && date("H:i:s") <=  $max_hour_shipping

					): ?>
						<a href="<?php echo $this->Html->url(["action" => "request_shipping", $this->Utilities->encryptString($shipping['Shipping']['id']) ]) ?>" class="btn btn-danger btn-sm request_shipping" target="_blank">
							Solicitar despacho  <i class="fa fa-eye vtc"></i>
						</a>
					<?php endif ?>
			</td>
		</tr>
	<?php endforeach; ?>
		</tbody>
		</table>
	</div>
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
<?php if ( date("H:i:s") <= $max_hour_envoice || date("H:i:s") <= $max_hour_remission || date("H:i:s") <= $max_hour_shipping): ?>
<!-- Modal -->
<div class="modal fade " id="flujoModalAddNuevoDespacho" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Crear nueva solicitd de despacho</h5>
      </div>
      <div class="modal-body" id="cuerpoFlujoModalAddNuevoDespacho">
        <div class="row">
        	<div class="col-md-12">
        		<?php echo $this->Form->input("flows", array("label" => "Por favor búsque el flujo que desea gestionar", "id" =>"flowsForShipping", "options" => [], "required","multiple" => false)) ?>
        	</div>
        	<div class="col-md-12 mt-2 text-right">
        		<button class="btn btn-success" id="btnCrearSolicitud">
        			Crear solicitud
        		</button>
        	</div>
        </div>
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

<?php  endif; ?>

<?php 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/shippings/admin.js?".rand(),				array('block' => 'AppScript'));
?>

<?php echo $this->element("picker"); ?>