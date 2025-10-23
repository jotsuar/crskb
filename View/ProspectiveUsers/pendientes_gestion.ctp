<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h2 class="titleviewer">Panel de flujos pendientes de gestionar en estado Cotizado que pasaran a telemercadeo</h2>
			</div>	
		</div>	
	</div>
	<div class="blockwhite">
		<div class="contenttableresponsive">
			<table class="table table-hovered">
				<thead>
					<tr>
						<?php if (AuthComponent::user("role") == "Gerente General"): ?>
							<th>
								Asesor
							</th>
						<?php endif ?>
						<th>Flujo</th>
						<th>Cliente</th>
						<th>
							Cotización
						</th>
						<th>
							Tiempo restante
						</th>
						<td>
							Acciones
						</td>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($listPendind)): ?>
						<?php foreach ($listPendind as $key => $value): ?>
							<tr>
								<?php if (AuthComponent::user("role") == "Gerente General"): ?>
									<td>
										<?php echo $value["User"]["name"] ?>
									</td>
								<?php endif ?>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable flujoModal m-1" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $value["ProspectiveUser"]["id"] ?>
									</a>
								</td>
								<td class="text-uppercase">
									<?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["name"]." - ".$value["ContacsUser"]["empresa"] ?> 
								</td>
								<td>
									<a data-clase="btn_pending_<?php echo $value["ProspectiveUser"]["id"] ?>" class="btn btn-warning getQuotationId viewQuotationData" data-quotation="<?php echo $this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]) ?>" href="#">Ver cotización</a>
								</td>
								<td>
									<p class="font-weight-bold font16 text-danger timeRemainingTxt" data-deadline="<?php echo $value["ProspectiveUser"]["deadline_notified"] ?>" data-id="FLUJO_<?php echo md5($value["ProspectiveUser"]["id"]) ?>">
								        <span id="FLUJO_<?php echo md5($value["ProspectiveUser"]["id"]) ?>_days"></span> días / <span id="FLUJO_<?php echo md5($value["ProspectiveUser"]["id"]) ?>_hours"></span> horas / <span id="FLUJO_<?php echo md5($value["ProspectiveUser"]["id"]) ?>_minutes"></span> minutos / <span id="FLUJO_<?php echo md5($value["ProspectiveUser"]["id"]) ?>_seconds"></span> segundos
								    </p>
								</td>
								<td>
									<?php if (AuthComponent::user("id") == $value["ProspectiveUser"]["user_id"] || (AuthComponent::user("email") == "logistica@kebco.co" && $value["ProspectiveUser"]["user_id"] == 13 ) || AuthComponent::user("email") == "jotsuar@gmail.com" ): ?>
										<a href="javascript:void(0)" data-id="<?php echo $this->Utilities->encryptString($value["ProspectiveUser"]["id"]) ?>" class="btn btn-info gestionBtn <?php echo $value["ProspectiveUser"]["origin"] == 'Robot' ? 'hidden-data' : '' ?> btn_pending_<?php echo $value["ProspectiveUser"]["id"] ?>">
											Gestionar flujo
										</a>
										<a href="javascript:void(0)" data-quotation="<?php echo $this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]) ?>" data-id="<?php echo $this->Utilities->encryptString($value["ProspectiveUser"]["id"]) ?>" class="btn btn-warning sendEmail <?php echo $value["ProspectiveUser"]["origin"] == 'Robot' ? 'hidden-data' : '' ?> btn_pending_<?php echo $value["ProspectiveUser"]["id"] ?>" data-toggle="tooltip" title="Envíar Correo">
											<svg style="display: block;" fill="#ffffff" version="1.1" baseProfile="tiny" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
												 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
												  width="20px" height="20px" viewBox="-0.5 0.5 42 42" xml:space="preserve">
											<path d="M40.5,31.5v-18c0,0-18.2,12.7-19.97,13.359C18.79,26.23,0.5,13.5,0.5,13.5v18c0,2.5,0.53,3,3,3h34
												C40.029,34.5,40.5,34.061,40.5,31.5z M40.471,9.971c0-1.821-0.531-2.471-2.971-2.471h-34c-2.51,0-3,0.78-3,2.6l0.03,0.28
												c0,0,18.069,12.44,20,13.12C22.57,22.71,40.5,10.1,40.5,10.1L40.471,9.971z"/>
											</svg>
										</a>
										<a href="<?php echo $this->Html->url(["action"=>"gest_wpp",$this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]), $this->Utilities->encryptString($value["ProspectiveUser"]["id"])  ]) ?>" data-quotation="<?php echo $this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]) ?>" data-id="<?php echo $this->Utilities->encryptString($value["ProspectiveUser"]["id"]) ?>" class="btn btn-success sendWpp <?php echo $value["ProspectiveUser"]["origin"] == 'Robot' ? 'hidden-data' : '' ?> btn_pending_<?php echo $value["ProspectiveUser"]["id"] ?>" data-toggle="tooltip" title="Envíar mensaje por Whatsapp">
											<i class="fa fa-whatsapp vtc"></i>
										</a>
									<?php endif ?>
								</td>
							</tr>
						<?php endforeach ?>
					<?php else: ?>
						<tr>
							<td colspan="<?php echo AuthComponent::user("role") == "Gerente General" ? 6 : 5 ?>">
								No hay pendientes de gestión
							</td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal fade" id="modalGestion" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg4" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Gestión flujo </h2>
      </div>
      <div class="modal-body" id="cuerpoGestion">

      </div>
      <div class="modal-footer">
        <a class="btn btn-outline-dark cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<div class="popup" style="width: 60%;">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>

<div class="modal fade" id="modalCorreo" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg4" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Gestión flujo </h2>
      </div>
      <div class="modal-body" id="cuerpoCorreo">

      </div>
      <div class="modal-footer">
        <a class="btn btn-outline-dark cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>




<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/index.js?".rand(),			array('block' => 'AppScript')); 
	echo $this->Html->script("controller/prospectiveUsers/gestion.js?".rand(),			array('block' => 'AppScript')); 
?>

<?php echo $this->element("flujoModal"); ?>
