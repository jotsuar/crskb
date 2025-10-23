<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h2 class="titleviewer">Panel de aprobación de cotizaciones</h2>
			</div>	
		</div>	
	</div>

	<div class="blockwhite">
		<div class="contenttableresponsive">
			<?php if (empty($users)): ?>
				<h1 class="text-center">
					No hay cotizaciones por aprobar
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
									<th>VER</th>
									<th style="max-width: 300px;">Cliente</th>
									<th>Nombre de la cotización</th>
									<th>Solicita pago de comisión diferencial</th>
									<th>Comisión a pagar</th>
									<th><?php echo $this->Paginator->sort('FlowStage.id', '#'); ?></th>
									<th><?php echo $this->Paginator->sort('FlowStage.codigoQuotation', 'Código'); ?></th>
									<th>Valor</th>
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
											
										<a class="btn-info btnSm getQuotationId2 modalFlujoClick mr-1 mb-2" data-toggle="tooltip" data-placement="bottom" title="Ver cotización" data-user="<?php echo $value["Approve"]["quotation_id"] ?>" data-quotation="<?php echo $value["Approve"]["quotation_id"] ?>" href="#">
										 	<i class="fa fa-eye vtc"></i>
										 </a>

										<a class="btnSm btn-success approveAndSend approveAllP<?php echo $user_id ?> rejectAllP<?php echo $user_id ?> approveAndSendUser<?php echo $value["Approve"]["quotation_id"] ?> <?php echo $value["Quotation"]["reason"] != null && $value["Quotation"]["reason"] != "" ? "d-none" : "" ?>" data-toggle="tooltip" data-placement="bottom" title="Aprobar y envíar" data-id="<?php echo $value["Approve"]["id"] ?>" href="#"><i class="fa fa-check vtc">	</i></a>
										<a class="btnSm btn-danger reject rejectUser<?php echo $value["Approve"]["quotation_id"] ?> <?php echo $value["Quotation"]["reason"] != null && $value["Quotation"]["reason"] != "" ? "d-none" : "" ?>" data-toggle="tooltip" data-placement="bottom" title="Rechazar cotización" data-id="<?php echo $value["Approve"]["id"] ?>" href="#">
											<i class="fa fa-times vtc">	</i>
										</a>
										<?php if ($value["Quotation"]["reason"] != null && $value["Quotation"]["reason"] != ""): ?>
											<a href="javascript:void(0)" class="btn-warning btnSm ml-1" role="button" data-toggle="popover" data-trigger="focus" title="Razónes para cotizar debajo del margen mínimo" data-content="<?php echo addslashes(trim($value["Quotation"]["reason"])) ?>">
												<i class="vtc fa fa-comments"></i>
											</a>
										<?php endif ?>

										<?php if ($value["Quotation"]["currency"] == "money_COP"): ?>
											<br>
											<span class="bg-primary p-1 mt-3 text-white spinner-grow" style='border-radius: 0px;    width: auto;    height: auto;    animation: spinner-grow 1.5s ease-in-out infinite;'>
												Cotización en PESOS
											</span>
										<?php endif ?>
										</div>
										<div class="col-md-12 mt-2">
											
										<?php if (!empty($value["otrasCliente"])): ?>
											<button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Ver cotizaciones activas
											</button>
										    <div class="dropdown-menu">
										    	<?php foreach ($value["otrasCliente"] as $key => $valueOtro): ?>
										    		<a class="dropdown-item getQuotationId" data-user="<?php echo $valueOtro["Quotation"]["id"] ?>" data-quotation="<?php echo $valueOtro["Quotation"]["id"] ?>" href="#"><?php echo $valueOtro["Quotation"]["codigo"] ?> - <?php echo $value["Quotation"]["name"] ?> </a>
										    	<?php endforeach ?>
										    </div>
										<?php endif ?>
										</div>
									</td>
									<td style="max-width: 300px;">
										<?php echo $this->Utilities->name_prospective_contact($value['Quotation']['prospective_users_id']) ?> 
										<br>	
										<a href="#" data-toggle="tooltip" data-placement="right" title="Ver flujo" class="idflujotable flujoModal modalFlujoClick" data-uid="<?php echo $value["Approve"]["flujo_id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value["Approve"]["flujo_id"]); ?>">
											Ver flujo
										</a>
									</td>
									<td class="uppercase">
										<?php echo $value['Quotation']['name']; ?> 
									</td>
									<td class="uppercase">
										<span>
											<b>
												<?php echo $value['Approve']['comision_completa'] == 1 ? 'Si' : 'No'; ?> 	

												<?php if ($value["Approve"]["comision_completa"] == '1'): ?>
															
													<i class="fa fa-warning fa-x" style="animation: spinner-grow 1s ease-in-out infinite;"></i>									
												<?php endif ?>		

											</b>
										</span>
									</td>
									<td>
										<?php 
											$percentaje = 0;
										?>

										<div class="pointer text-center price-purchase_price_usd <?php echo in_array(AuthComponent::user("role"), array("Gerente General","Logística")) ? "cambioCostoDataUsd" : "" ?>" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>" data-type="purchase_price_usd" data-price="<?php echo is_null($value['ProspectiveUser']['comision']) ? $percentaje : $value['ProspectiveUser']['comision'] ?>" data-currency="USD">
											<b class="font16"><?php echo is_null($value['ProspectiveUser']['comision']) ? $percentaje : $value['ProspectiveUser']['comision'] ?></b>
										</div>
									</td>
									
									<td>
										<?php echo $value['Quotation']['id'] ?>
									</td>
									
									<td>
										<?php echo $value['Quotation']['codigo']; ?>
									</td>
									<td>$ <?php echo number_format(doubleval($value['Quotation']['total']),2,",","."); ?></td>
									
									<td><?php echo $this->Utilities->date_castellano(h($value['Approve']['created'])); ?></td>
									
								</tr>

								<?php endforeach ?>
								<?php if (AuthComponent::user("role") == "Gerente General"): ?>
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
	echo $this->Html->script(array('lib/jquery.jeditable.min.js?'.rand()),						array('block' => 'AppScript'));
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