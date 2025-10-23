<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h2 class="titleviewer">Panel de gestión de flujos perdidos</h2>
			</div>	
		</div>	
	</div>

	<div class="blockwhite">
		<div class="contenttableresponsive">
			<?php if (empty($users)): ?>
				<h1 class="text-center">
					No hay flujos perdidos por gestión
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
									<th>Fecha de perdida de flujo</th>
									<th>Flujo</th>
									<th style="max-width: 300px;">Cliente</th>
									<th>Fecha límite de gestión</th>
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

											<a class="btnSm btn-success approveAndSend approveAllP<?php echo $user_id ?> rejectAllP<?php echo $user_id ?> approveAndSendUser<?php echo $value["ProspectiveUser"]["id"] ?>" data-toggle="tooltip" data-placement="bottom" title="Retornar flujo al asesor" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>" href="#"><i class="fa fa-check vtc">	</i></a>
											<a class="btnSm btn-danger reject rejectUser<?php echo $value["ProspectiveUser"]["id"] ?> " data-toggle="tooltip" data-placement="bottom" title="Cancelar definitivamente el flujo" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>" href="#">
												<i class="fa fa-times vtc">	</i>
											</a>
											<a class="btnSm btn-warning reasign ml-1 reasignUser<?php echo $value["ProspectiveUser"]["user_id"] ?> " data-toggle="tooltip" data-placement="bottom" title="Activar y reasignar" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>" href="#">
												<i class="fa fa-retweet vtc">	</i>
											</a>
										</div>
									</td>
									
									<td>
										<?php echo $value["ProspectiveUser"]["date_lose"] ?>
									</td>
									<td>
										<?php echo $value["ProspectiveUser"]["id"] ?>
									</td>
									<td style="max-width: 300px;">
										<?php echo $this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']) ?> 
										<br>	
										<a href="#" data-toggle="tooltip" data-placement="right" title="Ver flujo" class="idflujotable flujoModal modalFlujoClick" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value['ProspectiveUser']['id']); ?>">
											Ver flujo
										</a>
									</td>
									<td class="uppercase">
										<?php echo $value["ProspectiveUser"]["deadline_notified"] ?>
									</td>
									
								</tr>

								<?php endforeach ?>
								<?php if (AuthComponent::user("role") == "Gerente General"): ?>
									<tfoot>										
										<tr>
											<td>
												<a class="btnSm btn-success btn-block approveAndSendAll" data-user="<?php echo $user_id ?>" href="#">
													<i class="fa fa-check vtc">	</i> 
													Activar todos
												</a>
												<a class="btnSm btn-danger rejectAll btn-block mt-2" data-user="<?php echo $user_id ?>" href="#">
													<i class="fa fa-times vtc">	</i>
													Cancelar todos
												</a>
												<a class="btnSm btn-warning reasignAll btn-block mt-2" data-user="<?php echo $user_id ?>" href="#">
													<i class="fa fa-times vtc">	</i>
													Reasignar todos
												</a>
											</td>
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
	echo $this->Html->script("controller/quotations/actions_gests.js?".rand(),			array('block' => 'AppScript')); 
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

	var USUARIOS_ASESORES = <?php echo json_encode($usuarios); ?>

</script>

<div class="modal fade" id="modaReasigna" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Reasginar flujo(s) a otro asesor</h5>
      </div>
      <div class="modal-body" id="bodyReasigna">
      	<?php echo $this->Form->create('ProspectiveUser',["type" => "file",'data-parsley-validate'=>true, "url"=>["action"=>"reasingar_no_gest"]  ]); ?>
      		<?php echo $this->Form->input('id',["type" => "hidden", "id" => "flowReasigna" ]); ?>
      		<?php echo $this->Form->input('user_id',["label" => "Usuario para asignar", "options" => $usuarios, "required" => true, "empty" => "Seleccionar", "id" => "userIdReasign" ]); ?>
      		<input type="submit" class="btn btn-success float-right mt-4" value="Reasignar" >
      	<?php echo $this->Form->end(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


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