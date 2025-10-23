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
					<div class="col-md-3 item_menu_import">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'add_import')) ?>">
							<i class="fa d-xs-none fa-cart-plus vtc"></i>
							<span class="d-block"> Crear solicitud Interna</span>
						</a>
					</div>	
					<?php $roles = array(Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Logística')) ?>
						<?php if (in_array(AuthComponent::user('role'), $roles)): ?>
						<div class="col-md-3 item_menu_import">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'import_ventas')) ?>">
								<i class="fa d-xs-none fa-dropbox vtc"></i>
								<span class="d-block"> Reposición de Inventario</span>
							</a>
						</div>
					<?php endif ?>	
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
						<div class="col-md-3 text-center">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_approved')) ?>">
								<!-- <i class="fa fa-check d-xs-none vtc"></i> <span>   --> 
								<p class="m-0">SOLICITUDES EN PROCESO</p>
								<b><?php echo $this->Utilities->count_imports_approved(); ?></b>  
							</a>
						</div>
						<div class="col-md-3 text-center there">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_rejected')) ?>">
								<!-- <i class="fa fa-remove d-xs-none vtc"></i><span>  --> 
								<span class="">ESTÁS VIENDO</span>
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
	<div class="blockwhite">
		<div class="searchimport">
			<h2>Buscador de Compras</h2>
			<div class="input-group">
				<input type="search" id="txt_buscador" class="form-control" placeholder="Buscador por Código, Descripción o motivo" value="<?php echo isset($this->request->query["q"]) ? $this->request->query["q"] : "" ?>">
				<span class="input-group-addon btn_buscar">
	                <i class="fa fa-search"></i>
	            </span>
			</div>
		</div>
		<div class="contenttableresponsive">
			<table class="table-striped tableCotizacionesEnviadas table-bordered">
				<thead class="titlestab">
					<tr>
						<th><?php echo $this->Paginator->sort('Import.internacional', 'Nacional/Internacional'); ?></th>
						<th><?php echo $this->Paginator->sort('Import.code_import', 'Código'); ?></th>
						<th><?php echo $this->Paginator->sort('Import.created', 'Fecha'); ?></th>
						<th class="size7">Descripción</th>
						<th>Motivo Rechazo</th>
						<th>Solicita</th>
						<th class="text-center">Ver</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($importaciones as $value): ?>
						<tr>
							<td><?php echo $value['Import']['internacional'] == 1 ? "Internacional" : "Nacional"; ?></td>
							<td><?php echo $value['Import']['code_import'] ?></td>
							<td><?php echo $this->Utilities->date_castellano($value['Import']['created']); ?></td>
							<td><?php echo $value['Import']['description'] ?></td>
							<td><?php echo $value['Import']['motivo'] ?></td>
							<td><?php echo $value['User']['name'] ?></td>
							<td class="text-center">
								<a class="btn btn-outline-success" href="<?php echo $this->Html->url(array('controller' => 'Products','action' => 'products_import', $this->Utilities->encryptString($value['Import']['id']))) ?>" data-toggle="tooltip" title="Ver productos">
									<i class="fa fa-fw fa-eye vtc"></i>
								</a>
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
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),							array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/import/search.js?".rand(),		array('block' => 'AppScript'));
?>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

<?php 


echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));

echo $this->Html->script("controller/quotations/view.js?".rand(),			array('block' => 'AppScript')); 
 ?>