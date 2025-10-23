<?php 	
	$rolesPriceImport = array(
		Configure::read('variables.roles_usuarios.Logística'),
		Configure::read('variables.roles_usuarios.Gerente General')
	);
	$validRole = in_array(AuthComponent::user("role"), $rolesPriceImport) ? true : false;
	$totalValueFinal = 0;
?>
<div class="col-md-12 p-0">
	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1 class="nameview">PANEL PRINCIPAL DE COMPRAS</h1>
				<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'flujos_import')) ?>" class="pull-right btn btn-info">					
					<span class="d-block"><i class="fa flaticon-growth ml-0"></i> Flujos con importaciones en proceso</span>
				</a>
			</div>
		</div>
	</div>
	<?php if ($movileAccess): ?>
		<?php echo $this->element("order_responsive"); ?>
	<?php endif ?>	
	<div class="mb-4 subpmenu">
		
		<div class="col-md-12">
	<div class="row">
		<div class="col-md-6">
			<div class="row">
				<h2 class="titlemenuline">GESTIÓN LOGÍSTICA</h2>
			</div>			
			<div class="row pr-2">
					<?php $roles = array(Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Logística')) ?>
						<?php if (in_array(AuthComponent::user('role'), $roles)): ?>
						<!-- <div class="activesub impblock-color1"> -->
						<div class="col-md-3 item_menu_import ">
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
					<div class="col-md-3 item_menu_import">
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
						<div class="col-md-3 item_menu_import activeitem">
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
							<div class="col-md-3 item_menu_import">
								<a href="<?php echo $this->Html->url(["controller" => "ProspectiveUsers", "action" => "solicitudes_internas" ]) ?>">
									<i class="fa d-xs-none fa-users vtc"></i>
									<span class="d-block"> Solicitudes internas</span>
								</a>
							</div>		
				</div>
			<?php endif ?>		
		</div>			
	</div>
	</div>
	</div>

	<div class="blockwhite-import spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h2>Solicitudes de Importación</h2>
			</div>
			<div class="col-md-6">
				<div class=" col-md-12 submenu_solicitudes">	
					<div class="row">	
						<div class="col-md-3 text-center ">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>">
								<!-- <i class="fa fa-spinner d-xs-none vtc"></i><span>  -->
								<p class="m-0">SOLICITUDES PENDIENTES</p>
								<b><?php echo $this->Utilities->count_importaciones_revisiones(); ?> </b> 
							</a>
						</div>
						<div class="col-md-3 text-center there">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_approved')) ?>">
								<!-- <i class="fa fa-check d-xs-none vtc"></i> <span>   --> 
								<span class="">ESTÁS VIENDO</span>
								<p class="m-0">SOLICITUDES EN PROCESO</p>
								<b><?php echo $this->Utilities->count_imports_approved(); ?></b>  
							</a>
						</div>
						<div class="col-md-3 text-center">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_rejected')) ?>">
								<!-- <i class="fa fa-remove d-xs-none vtc"></i><span>  --> 
								<p class="m-0">SOLICITUDES RECHAZADAS</p>
								<b><?php echo $this->Utilities->count_imports_rejected(); ?></b> 
							</a>
						</div>
						<div class="col-md-3 text-center">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'import_finalizadas')) ?>">
								<!-- <i class="fa fa-external-link d-xs-none vtc"></i> <span>  -->  
								<p class="m-0">SOLICITUDES FINALIZADAS</p>
								<b><?php echo $this->Utilities->count_imports_process(); ?></b> 
							</a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="blockwhite spacebtn20">
		<div class="searchimport">
			<h2>Buscador de compras</h2>
			<div class="input-group">
				<input type="search" id="txt_buscador" class="form-control" placeholder="Buscador por Código, Descripción o número de orden al proveedor" value="<?php echo isset($this->request->query["q"]) ? $this->request->query["q"] : "" ?>">
				<span class="input-group-addon btn_buscar">
	                <i class="fa fa-search"></i>
	            </span>
			</div>
		</div>
		<div class="contenttableresponsive">
			<table class="table-striped tableCotizacionesEnviadas table-bordered">
				<thead class="titlestab">
					<tr>
						<th><?php echo $this->Paginator->sort('Import.internacional', 'NAC/INT'); ?></th>
						<th><?php echo $this->Paginator->sort('Import.code_import', 'Código'); ?></th>
						<th><?php echo $this->Paginator->sort('Import.purchase_order', '# Orden'); ?></th>
						<th><?php echo $this->Paginator->sort('Import.brand_id', 'Marca'); ?></th>
						<th style="width: 150px"><?php echo $this->Paginator->sort('Import.orden_proveedor', 'Orden generada en el sistema del proveedor'); ?> <small class="text-danger">Aplica para la marca GRACO</small></th>
						<th><?php echo $this->Paginator->sort('Import.created', 'Fecha solicitud'); ?></th>
						<th style="width: 150px"><?php echo $this->Paginator->sort('Import.fecha_gerencia', 'Fecha de aprobación y envio por parte de gerencía'); ?></th>
						<th><?php echo $this->Paginator->sort('Import.deadline', 'Fecha de arribo'); ?></th>
						<th>Descripción</th>
						<th>Solicita</th>
						<th>Flujos</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($importaciones as $value): ?>
						<tr>
							<td><?php echo $value['Import']['internacional'] == 1 ? "Internacional" : "Nacional"; ?></td>
							<td><?php echo $value['Import']['code_import'] ?></td>
							<td>
								<button class="btn btn-info boton-actualizar" data-import="<?php echo $value["Import"]["code_import"] ?>" data-id="<?php echo $value["Import"]["id"] ?>" data-actual="<?php echo !empty($value['Import']['purchase_order']) ? $value['Import']['purchase_order'] : $this->Utilities->getIdNext() ?>" ><?php echo !empty($value['Import']['purchase_order']) ? $value['Import']['purchase_order'] : 'Asignar' ?></button>
							</td>
							<td><?php echo isset($value['ImportRequest']['brand_id']) && !is_null($value['ImportRequest']['brand_id']) ? $this->Utilities->getInfoByBrand($value['ImportRequest']['brand_id'])["brand"]["Brand"]["name"] : "" ?> <?php echo $value['ImportRequest']['brand_id'] == $value["Brand"]["id"] ? "" : " - ".$value["Brand"]["name"] ?> </td>
							<td>
								<?php echo $value["Import"]["orden_proveedor"] ?>
								<?php $claseView=""; ?>
								<?php if (empty($value["Import"]["orden_proveedor"]) && $value["Import"]["brand_id"] == 4 && in_array(AuthComponent::user("role"), [Configure::read('variables.roles_usuarios.Logística'),"Gerente General"] ) ): ?>
									<a href="" class="btn btn-info btn-sm newOrder" data-id="<?php echo $value["Import"]["id"] ?>">
										<i class="fa fa-plus vtc"></i> Adjuntar orden
									</a>
									<?php $claseView="viewInModal"; ?>
								<?php endif ?>
							</td>
							<td><?php echo $this->Utilities->date_castellano($value['Import']['created']); ?></td>
							<td><?php echo $this->Utilities->date_castellano($value['Import']['fecha_gerencia']); ?></td>
							<td>
								<?php if ($value["Import"]["state"] == 1 && !is_null($value["Import"]["deadline"])): ?>							
								
									<?php $fecha = $value["Import"]["deadline"];
												$dataDay = $this->Utilities->getClassDate($fecha); ?>
									<?php if ($dataDay == 0): ?>
										<span class="bgs-danger text-danger">
											Se entrega hoy
										</span>
									<?php elseif($dataDay > 0): ?>
										<span class="bgs-danger text-danger">
											Retraso de <?php echo $dataDay ?> día(s) <?php echo date("Y-m-d",strtotime("-".$dataDay." day")) ?>
										</span>
									<?php elseif($dataDay <= -5): ?>
										<span class="bgs-success text-success">Se entrega en  <?php echo abs($dataDay) ?> día(s) <?php echo date("Y-m-d",strtotime("+".abs($dataDay)." day")) ?></span>
									<?php else: ?>
										<span class="bgs-warning text-success">Se entrega en <?php echo abs($dataDay) ?> día(s) <?php echo date("Y-m-d",strtotime("+".abs($dataDay)." day")) ?></span>
									<?php endif ?>
								<?php endif ?>
							</td>
							<td><?php echo $value['Import']['description'] ?></td>
							<td><?php echo $value['User']['name'] ?></td>
							<td>
								<?php $flujos = $this->Utilities->extratctFlujos($value); ?>
								<?php if ($flujos): ?>
									<?php foreach ($flujos as $keyPU => $valuePU): ?>
										
										<div class="dropdown d-inline">
										  <a class="btn btn-success btn-sm dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($valuePU) ?>_<?php echo md5($value["Import"]["id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										   <?php echo $valuePU ?>
										  </a>

										  <div class="dropdown-menu styledrop" aria-labelledby="dropdownMenuLink_<?php echo md5($valuePU) ?>_<?php echo md5($value["Import"]["id"]) ?>">
										    <a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $valuePU ?>" data-type="<?php echo $this->Utilities->getTypeProspective($valuePU); ?>">Ver flujo</a>
										    <a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($valuePU) ?>" href="#">Ver cotización</a>
										    <a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $valuePU ?>">Ver órden de compra</a>
										  </div>
										</div>
									<?php endforeach ?>
								<?php endif ?>
							</td>
							<td class="text-center">
								<a href="<?php echo $this->Html->url(array('controller' => 'Products','action' => 'products_import', $this->Utilities->encryptString($value['Import']['id']))) ?>" class="btn btn-outline-success <?php echo $claseView ?>" data-toggle="tooltip" title="Ver productos">
									<i class="fa fa-fw fa-eye vtc"></i>
								</a>
									<button type="button" class="btn btn-outline-primary" data-uid="<?php echo $this->Utilities->encryptString($value['ImportRequest']['id']) ?>" id="pdfGenerate" data-toggle="tooltip" title="Visualizar OC en .pdf" data-original-title="Visualizar OC en .pdf">
									   <i class="fa fa-file-text-o vtc"></i>
									</button>
								<?php if ($validRole): ?>			
									<button type="button" class="btn btn-outline-secondary" data-uid="<?php echo $this->Utilities->encryptString($value['ImportRequest']['id']) ?>" id="pdfGenerateDetail" data-toggle="tooltip" title="Visualizar OC con detalles de entrega" data-original-title="Visualizar OC con detalles de entrega">
										    <i class="fa fa-file-pdf-o vtc"></i>
									</button>
									<?php if ($value["Import"]["brand_id"] == 4): ?>
										<a target="_blank" href="<?php echo $this->Html->url(["action" => "download_csv",$this->Utilities->encryptString($value['Import']['id'])]) ?>" class="btn btn-warning" data-toggle="tooltip" title="Exportar CSV plataforma GRACO" data-original-title="Exportar CSV plataforma GRACO"><i class="fa fa-file-o"></i></a>
									<?php endif ?>
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

<div class="modal fade" id="modalViewImport" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg4" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle de importación </h2>
      </div>
      <div class="modal-body" id="cuerpoViewImport">
      </div>
      <div class="modal-footer">
        <a class="btn btn-outline-dark cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>
<?php echo $this->element("flujoModal"); ?>
<?php
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),							array('block' => 'jqueryApp'));
	//echo $this->Html->script("controller/product/products_import.js?".rand(),					array('block' => 'AppScript'));
	echo $this->Html->script("controller/prospectiveUsers/imports_revisions.js?".rand(),		array('block' => 'AppScript'));
	
	echo $this->Html->script("controller/import/search.js?".rand(),		array('block' => 'AppScript'));
?>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

<?php 


echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));

echo $this->Html->script("controller/quotations/view.js?".rand(),			array('block' => 'AppScript')); 
 ?>

<style>
	.btn-sm {
	    padding: 0.5px !important;
	}
</style>