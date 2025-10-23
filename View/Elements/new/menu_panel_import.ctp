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
					<div class="col-md-3 item_menu_import">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'add_import')) ?>">
							<i class="fa d-xs-none fa-cart-plus vtc"></i>
							<span class="d-block"> Crear solicitud Interna</span>
						</a>
					</div>	
					<div class="col-md-3 item_menu_import">
						<a href="<?php echo $this->Html->url(["controller" => "products", "action" => "products_rotation" ]) ?>"><i class="fa d-xs-none fa-cogs vtc"></i>
							<span class="d-block"> Productos configurados</span>
						</a>
					</div>	
			</div>	
		</div>
		<div class="col-md-6">
			<div class="row">
				<h2 class="titlemenuline">GESTIÓN GERENCIAL</h2>
			</div>
			<div class="row pl-2">
					<div class="col-md-3 item_menu_import">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>">
							<i class="fa fa-list-alt d-xs-none vtc"></i> <i class="fa fa-plane d-xs-none vtc"></i>
							<span class="d-block"> Gestión y Aprobación</span> </a>
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
		</div>			
	</div>
</div>

