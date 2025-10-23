<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h2 class="titleviewer">Panel de aprobación de cancelaciones de flujos</h2>
			</div>	
		</div>	
	</div>

	<div class="blockwhite">
		<div class="contenttableresponsive">
			<?php if (empty($users)): ?>
				<h1 class="text-center">
					No hay cancelaciones de flujos por aprobar
				</h1>
			<?php endif ?>
			<ul class="nav nav-tabs resetostab" id="myTab" role="tablist">
			  <?php $num = 1; ?>
			  <?php foreach ($users as $key => $user_id): ?>
			  	<li class="nav-item navData">
			    	<a class="nav-link navLinkData <?php echo (isset($this->request->query["tab"]) && $this->request->query["tab"] == $num) || ( isset($this->request->query["tab"]) && $this->request->query["tab"] > count($users) && $num == 1 ) || (!isset($this->request->query["tab"]) && $num == 1  )  ? "active" : "" ?>" data-tab="<?php echo $num ?>" id="user-<?php echo $user_id ?>" data-toggle="tab" href="#user_<?php echo $user_id ?>" role="tab" aria-controls="user_<?php echo $user_id ?>" aria-selected="true">
			    		<?php echo $this->Utilities->find_name_lastname_adviser($user_id); $num++; ?>
			    	</a>
			  	</li>
			  <?php endforeach ?>
			</ul>
			<div class="tab-content" id="myTabContent">
				<?php $num = 1; ?>
				<?php foreach ($users as $key => $user_id): ?>
					<div class="tab-pane pt-3 fade <?php echo (isset($this->request->query["tab"]) && $this->request->query["tab"] == $num) || ( isset($this->request->query["tab"]) && $this->request->query["tab"] > count($users) && $num == 1 ) || (!isset($this->request->query["tab"]) && $num == 1  )  ? "show active" : "" ?>" id="user_<?php echo $user_id ?>" role="tabpanel" aria-labelledby="user-<?php echo $user_id ?>">
						<?php $num++; ?>
	
						<table class="table-hovered tableApprove table table-bordered datosPendientesDespachoDn">
							<thead>
								<tr>
									<th>Acciones</th>
									<th>Tipo</th>
									<th style="max-width: 300px;">Cliente</th>
									<th>Razón de la cancelación</th>
									<th><?php echo $this->Paginator->sort('FlowStage.created', 'Fecha de envio'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($cotizaciones_enviadas[$user_id])): ?>
									<tr>
										<td colspan="7" class="text-center">
											No existen cotizaciones 
											<?php if (isset($this->request->query['q'])): ?>								
												relacionadas con la búsqueda
											<?php endif ?>
										</td>
									</tr>
								<?php endif ?>
								<?php foreach ($cotizaciones_enviadas[$user_id] as $value): ?>
								<tr class="trInfoApprove">
									<td>
										<div class="col-md-12">

											<a class="btnSm btn-success approveAndSend approveAllP<?php echo $user_id ?> rejectAllP<?php echo $user_id ?> approveAndSendUser<?php echo $value["Approve"]["id"] ?>" data-toggle="tooltip" data-placement="bottom" title="Aprobar y envíar" data-id="<?php echo $value["Approve"]["id"] ?>" href="#"><i class="fa fa-check vtc">	</i></a>
											<a class="btnSm btn-danger reject rejectUser<?php echo $value["Approve"]["id"] ?> " data-toggle="tooltip" data-placement="bottom" title="Rechazar cancelación" data-id="<?php echo $value["Approve"]["id"] ?>" href="#">
												<i class="fa fa-times vtc">	</i>
											</a>
										</div>
									</td>
									<td>
										<?php switch ($value["Approve"]["type_aprovee"]) {
											case '2':
												echo '<span class="bg-red">Cancelación en etapa de contacto inicial</span>';
												break;
											case '3':
												echo '<span class="bg-red"> Cancelación de flujo en proceso </span>';
												break;
											case '4':
												echo 'Creación de nueva cotización';
												break;
											case '5':
												echo 'Prorroga final para gestión de flujo';
												break;
											default:
												// code...
												break;
										} ?>
									</td>
									<td style="max-width: 300px;">
										<?php echo $this->Utilities->name_prospective_contact($value['Approve']['flujo_id']) ?> 
										<br>	
										<a href="#" data-toggle="tooltip" data-placement="right" title="Ver flujo" class="idflujotable flujoModal modalFlujoClick" data-uid="<?php echo $value["Approve"]["flujo_id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value["Approve"]["flujo_id"]); ?>">
											Ver flujo
										</a>
									</td>
									<td class="uppercase">
										<?php if ($value["Approve"]["type_aprovee"] == 3 || $value["Approve"]["type_aprovee"] == 4  || $value["Approve"]["type_aprovee"] == 5): ?>
											<?php echo $value["Approve"]["copias_email"] ?>

											<?php if ($value["Approve"]["type_aprovee"] == 5): ?>
												<h2>Fecha solicitada: <small><?php echo $value["Approve"]["deadline"] ?></small> </h2>
											<?php endif ?>

										<?php else: ?>
											<?php echo $value['FlowStage']['description']; ?> 
										<?php endif ?>
									</td>
									
									<td><?php echo $this->Utilities->date_castellano(h($value['Approve']['created'])); ?></td>
									
								</tr>

								<?php endforeach ?>
								<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística"): ?>
									<tfoot>										
										<tr>
											<td>
												<a class="btnSm btn-success btn-block approveAndSendAll" data-user="<?php echo $user_id ?>" href="#">
													<i class="fa fa-check vtc">	</i> 
													Aprobar y envíar todos
												</a>
												<a class="btnSm btn-danger rejectAll btn-block mt-2" data-user="<?php echo $user_id ?>" href="#">
													<i class="fa fa-times vtc">	</i>
													Rechazar todas
												</a>
											</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tfoot>
								<?php endif ?>
							</tbody>
						</table>

					</div>
				<?php endforeach ?>

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
	echo $this->Html->script("controller/quotations/actions_cancel.js?".rand(),			array('block' => 'AppScript')); 
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