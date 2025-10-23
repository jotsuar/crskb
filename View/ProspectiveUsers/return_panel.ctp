<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h2 class="titleviewer">Panel de solicitudes de devolución de flujos</h2>
			</div>	
		</div>	
	</div>

	<div class="blockwhite">
		<div class="contenttableresponsive">
			<?php if (empty($prospectos)): ?>
				<h1 class="text-center">
					No hay solicitudes por aprobar
				</h1>
			<?php endif ?>
			<div class="tab-content" id="myTabContent">
				
				<table class="table-hovered tableApprove table table-bordered datosPendientesDespachoDn">
					<thead>
						<tr>
							<th>Flujo</th>
							<th style="max-width: 300px;">Cliente</th>
							<th>Estado actual</th>
							<th>Razón de la solictud</th>
							<th>Usuario solicita</th>
							<th>Última modificación</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($prospectos as $key => $value): ?>
						<tr class="trInfoApprove">
							<td>
								<div class="col-md-12">
								<?php $valores = explode("|", $value["ProspectiveUser"]["bill_file"]); $user_id = $valores[1]; ?>
								<div class="dropdown d-inline styledrop">
									<a class="btn btn-success dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($key) ?>_<?php echo md5($value['ProspectiveUser']['id']) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<?php echo $value['ProspectiveUser']['id'] ?>
									</a>

									<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($key) ?>_<?php echo md5($value['ProspectiveUser']['id']) ?>">
										<a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value['ProspectiveUser']['id']); ?>">Ver flujo</a>
										<?php if (in_array($value['ProspectiveUser']['state_flow'], [3,4,5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>														
											<a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($value['ProspectiveUser']['id']) ?>" href="#">Ver cotización</a>
										<?php endif ?>
										<?php if (in_array($value['ProspectiveUser']['state_flow'], [4,5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>
											<a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $value['ProspectiveUser']['id'] ?>">Ver órden de compra</a>
										<?php endif ?>
										<?php if (in_array($value['ProspectiveUser']['state_flow'], [5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>
											<a class="dropdown-item getPagos" href="#" data-flujo="<?php echo $value['ProspectiveUser']['id'] ?>">Ver comprobante(s) de pago</a>
										<?php endif ?>
									</div>
								</div>

								 <?php if (AuthComponent::user("ids") != $user_id): ?>
									<a class="btnSm btn-success approveAndSend approveAllP<?php echo $user_id ?> rejectAllP<?php echo $user_id ?> approveAndSendUser<?php echo $value["ProspectiveUser"]["id"] ?>" data-toggle="tooltip" data-placement="bottom" title="Aprobar y envíar" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>" href="<?php echo $this->Html->url(["action"=>"approve_return",1,$value["ProspectiveUser"]["id"],base64_encode($valores[0]), $valores[2] ]) ?>"><i class="fa fa-check vtc">	</i></a>
									<a class="btnSm btn-danger reject rejectUser<?php echo $value["ProspectiveUser"]["id"] ?>" data-toggle="tooltip" data-placement="bottom" title="Rechazar cotización" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>" href="<?php echo $this->Html->url(["action"=>"approve_return",0,$value["ProspectiveUser"]["id"],base64_encode($valores[0]), $valores[2] ]) ?>">
										<i class="fa fa-times vtc">	</i>
									</a>
								<?php endif ?>
								</div>
							</td>
							<td style="max-width: 300px;">
								<?php echo $this->Utilities->name_prospective($value['ProspectiveUser']["id"],true); ?>
								<?php echo $this->Utilities->name_prospective_dni($value['ProspectiveUser']["id"],true) ?>
							</td>
							<td class="uppercase">
								<?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?>
							</td>
							<td>
								<?php echo $valores['0'] ?>
							</td>
							
							<td><?php echo $this->Utilities->find_name_adviser($valores[1]); ?></td>
							<td><?php echo $this->Utilities->date_castellano(h($value['ProspectiveUser']['modified'])); ?></td>
							
						</tr>

						<?php endforeach ?>
					</tbody>
				</table>

			</div>

			</div>
			
		</div>
	</div>
</div>

<?php 
	$whitelist = array(
            '127.0.0.1',
            '::1'
        ); 
?>

<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/quotations/actions_qts.js?".rand(),			array('block' => 'AppScript')); 
?>

<script>
	var roleGerente = "<?php echo AuthComponent::user("role") == "Gerente General" ? 1 : 0 ?>";
	var actual_uri2 = "<?php echo Router::reverse($this->request, true) ?>";
    var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";
</script>


<?php echo $this->element("flujoModal",["aprobar" => true]); ?>

<script>
	
	setTimeout(function() {
		$('.datosPendientesDespachoDn').DataTable({
	        'iDisplayLength': 18,
	        "language": {"url": "https://crm.kebco.co/Spanish.json",},
	        "order": [[ 0, "desc" ]],
	        "lengthMenu": [ [21,50, 100, -1], [21,50, 100, "Todos"] ]
	    });
	}, 2000);
</script>


<style>	

		.btnSm{
			padding: 0.25rem 0.5rem !important;
		    font-size: 0.875rem !important;
		    line-height: 1.5 !important;
		    border-radius: 0.2rem !important;
		}

		.bgInfo {
		    background-color: #3f5dcb1f !important;
		}

</style>