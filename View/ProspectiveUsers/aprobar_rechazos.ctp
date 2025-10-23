<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-azul big">
             <i class="fa fa-1x flaticon-money"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión</h2>
		</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview">
					APROBACIÓN DE RECHAZO DE FLUJOS CREADOS POR CHAT


				</h1> 
				
			</div>
		</div>
	</div>


	<div class="blockwhite mb-3">
		<h1 class="nameview spacebtnm">Flujos por aprobar</h1>
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class='table-striped datosPendientesDespacho table-bordered'>
				<thead>
					<tr>
						<th>#</th>
						<th>Fecha de ingreso</th>
						<th>Id del flujo</th>
						<th>Asesor</th>
						<th>Razón del rechazo</th>
						<th>Evidencia</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($prospectiveUsers as $value): ?>
						<tr>
							<td><?php echo $value['ProspectiveUser']['id'] ?></td>
							<td><?php echo $this->Utilities->date_castellano($value['ProspectiveUser']['modified']); ?></td>
							<td>
								<div class="dropdown d-inline">
								  <a class="btn btn-success btn-sm dropdown-toggle p-1 rounded idflujotable" href="#" role="button" id="dropdownMenuLink_<?php echo $value['ProspectiveUser']['id'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <?php echo $value['ProspectiveUser']['id'] ?>
								  </a>

								  <div class="dropdown-menu styledrop" aria-labelledby="dropdownMenuLink_<?php echo $value['ProspectiveUser']['id'] ?>">
								    <a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value['ProspectiveUser']['id']); ?>">Ver flujo</a>
								  </div>
								</div>
								
							</td>
							<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
						<td>
							<?php echo $value['ProspectiveUser']['reject_reason']; ?> 							
						</td>
						<td class="comprobanteimgTd">
									<a datacomprobantet="<?php echo $this->Html->url('/img/chat_images/'.$value['ProspectiveUser']['reject_image']) ?>" src="<?php echo $this->Html->url('/img/chat_images/'.$value['ProspectiveUser']['reject_image']) ?>" class="reciboT btn btn-info" width="30px">Ver imagen</a> 
								</td>
							<td>

							<a href="javascript:void(0)" class="confirm_reject btn btn-success text-white" data-toggle="tooltip" title="Confirmar rechazo" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-user_id="<?php echo $value['ProspectiveUser']['user_id'] ?>" data-state="1" data-flujo_id="<?php echo $value['ProspectiveUser']['id'] ?>"><i class="fa fa-check vtc"></i></a>

								<a href="javascript:void(0)" class="confirm_reject btn btn-danger text-white" data-toggle="tooltip" title="No confirmar rechazo" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-user_id="<?php echo $value['ProspectiveUser']['user_id'] ?>" data-state="2" data-flujo_id="<?php echo $value['ProspectiveUser']['id'] ?>"><i class="fa fa-times vtc"></i></a>
							</td> 
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>	


<div class="popup2">
	<span class="cierra2"> <i class="fa fa-remove"></i> </span>
	<div class="contentpopup">
		<img src="" class="img-product" alt="">
	</div>
</div>
<div class="fondo"></div>




<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
echo $this->Html->script("controller/prospectiveUsers/verify_payment.js?".rand(),	array('block' => 'AppScript'));
?>

<?php echo $this->element("flujoModal"); ?>
