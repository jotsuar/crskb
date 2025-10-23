
<div class="col-md-12">
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Mis solicitudes de compras internas</h2>
			</div>
			<div class="col-md-6">
				<a  href="<?php echo $this->Html->url(array('action'=>'add_import')) ?>" class="btn btn-primary pull-right">
					Realizar nueva solicitud
				</a>
			</div>
		</div>
	</div>
	<div class="col-md-12 mb-3">
		<div class="row">
			<?php if (true): ?>
				
				<div class="col-md-6">
					<div class="row">
						<h2 class="titlemenuline">GESTIÓN LOGÍSTICA</h2>
					</div>			
					<div class="row pr-2">
							<?php $roles = array(Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Logística')) ?>
								<?php if (in_array(AuthComponent::user('role'), $roles)): ?>
								<!-- <div class="activesub impblock-color1"> -->
								<div class="col-md-3 item_menu_import">
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands')) ?>">
										<i class="fa fa-list-alt d-xs-none vtc"></i>
										<span class="d-block"> Pedidos a Proveedores</span>
									</a>
								</div>
								<div class="col-md-3 item_menu_import">
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands','internacional')) ?>">
										<i class="fa fa-list-alt d-xs-none vtc"></i>
										<span class="d-block"> Pedidos Prov Internacionales</span>
									</a>
								</div>
							<?php endif ?>
							<?php $roles = array(Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Logística')) ?>
								<?php if (in_array(AuthComponent::user('role'), $roles)): ?>
								<div class="col-md-3 item_menu_import">
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'import_ventas')) ?>">
										<i class="fa d-xs-none fa-dropbox vtc"></i>
										<span class="d-block"> Reposición de Inventario</span>
									</a>
								</div>
							<?php endif ?>
							<div class="col-md-3 item_menu_import activeitem">
								<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'add_import')) ?>">
									<i class="fa d-xs-none fa-cart-plus vtc"></i>
									<span class="d-block"> Crear solicitud Interna</span>
								</a>
							</div>	
								
					</div>	
				</div>
				<div class="col-md-6">
					<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística"): ?>
						<div class="row">
							<h2 class="titlemenuline">GESTIÓN GERENCIAL</h2>
						</div>
						<div class="row pl-2">
								<div class="col-md-3 item_menu_import">
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>">
										<i class="fa fa-list-alt d-xs-none vtc"></i> <i class="fa fa-plane d-xs-none vtc"></i>
										<span class="d-block"> Gestión y Aprobación</span> </a>
								</div>					
								
								<div class="col-md-3 item_menu_import">
									<a href="<?php echo $this->Html->url(["controller" => "products", "action" => "products_rotation" ]) ?>"><i class="fa d-xs-none fa-cogs vtc"></i>
										<span class="d-block"> Productos configurados</span>
									</a>
								</div>		
								<div class="col-md-3 item_menu_import">
									<a href="<?php echo $this->Html->url(["controller" => "products", "action" => "new_panel" ]) ?>">
										<i class="fa d-xs-none fa-cloud-upload vtc"></i>
										<span class="d-block"> Solicitudes automáticas</span>
									</a>
								</div>
								<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística"): ?>						
									<div class="col-md-3 item_menu_import">
										<a href="<?php echo $this->Html->url(["controller" => "ProspectiveUsers", "action" => "solicitudes_internas" ]) ?>">
											<i class="fa d-xs-none fa-users vtc"></i>
											<span class="d-block"> Solicitudes internas</span>
										</a>
									</div>		
								<?php endif ?>

						</div>
					<?php endif ?>	
				</div>			
			<?php endif ?>
		</div>
	</div>

	<div class="blockwhite">
		<div class="contenttableresponsive">
			<table class="table-striped tableCotizacionesEnviadas table-bordered">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('ImportRequestsDetail.created', 'Fecha de la solicitud'); ?></th>
						<th><?php echo $this->Paginator->sort('ImportRequestsDetail.description', 'Razón de la importación'); ?></th>
						<th><?php echo $this->Paginator->sort('ImportRequest.brand_id', 'Marca'); ?></th>
						<th><?php echo $this->Paginator->sort('ImportRequestsDetail.nacional', 'Nacional/Internacional'); ?></th>
						<th>Estado actual</th>
						<th>Nota/Motivo rechazo</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($importaciones as $value): ?>
						<tr>
							<td><?php echo $this->Utilities->date_castellano($value['ImportRequestsDetail']['created']); ?></td>
							<td><?php echo $value['ImportRequestsDetail']['description'] ?></td>
							<td><?php echo $value['ImportRequest']['Brand']["name"] ?></td>
							<td><?php echo $value['ImportRequestsDetail']['internacional'] == "0" ? "Nacional" : "Internacional" ?></td>
							<td>
								<?php if ($value["ImportRequestsDetail"]["state"] == 2 && $value["ImportRequest"]["state"] == 1) {
									echo "Esperando aprobación";
								}elseif ($value["ImportRequestsDetail"]["state"] == 1 && $value["ImportRequest"]["state"] == 1) {
									echo "Esperando gestión desde logística";
								}elseif ($value["ImportRequest"]["state"] == 2 && !is_null($value["ImportRequest"]["import_id"]) && $value["ImportRequestsDetail"]["state"] == 3 ) {
									if ($value["ImportRequest"]["Import"]["state"] == 3 ) {
										echo "En aprobación de gerencia / Gestionado por logística ";
									}
								}elseif($value["ImportRequestsDetail"]["state"] == 0){
									echo "Rechazada";
								} ?>
							</td>
							<td><?php echo $value['ImportRequestsDetail']['motive'] ?></td>
							<td>
								<a href="<?php echo $this->Html->url(["action" => "detail_imports", "controller" => "import_requests", $this->Utilities->encryptString($value["ImportRequestsDetail"]["id"]) ]) ?>" class="btn btn-success viewDetailData">Ver detalle</a>
								<?php if ($value["ImportRequest"]["state"] == 2 && !is_null($value["ImportRequest"]["import_id"]) && $value["ImportRequest"]["Import"]["state"] == 1): ?>
									<a href="<?php echo $this->Html->url(["action" => "products_import", "controller" => "products", $this->Utilities->encryptString($value["ImportRequest"]["import_id"]) ]) ?>" class="btn btn-info">Ver OC</a>
								<?php endif ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
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

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/my_order.js?".rand(),			array('block' => 'AppScript'));
?>

<div class="modal fade" id="modalDetalleSolicitud" tabindex="-1" role="dialog" aria-labelledby="modalAddProduct" aria-hidden="true">
		<div class="modal-dialog modal-lg2" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">
						Detalle de solicitud
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="detalleSolicitudBody" >
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>