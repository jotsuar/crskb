 <ul class="navbar-nav sidenav-toggler">
	<li class="nav-item">
		<a class="nav-link text-center" id="sidenavToggler">
			<!--<i class="fa fa-fw fa-angle-left"></i>--> CRM
		</a>
	</li>
</ul> 
<ul class="navbar-nav ml-auto">
	<li class="nav-item">
		<a href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "flujo_especial")) ?>" class="nav-link" data-toggle="tooltip" data-placement="right" title="Crear flujo" id="simpleflow">
			<i class="fa fa-x fa-plus-circle"></i> 
			Crear flujo express
		</a>
	</li>
	<li class="nav-item">
		<a href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "flujo_tienda")) ?>" class="nav-link" data-toggle="tooltip" data-placement="right" title="Crear flujo" id="nuevoFlujoTienda">
			<i class="fa fa-x fa-plus-circle"></i> 
			Crear venta en tienda
		</a>
	</li>
	<li class="nav-item newoport">
		<a href="#" class="nav-link" data-toggle="tooltip" data-placement="right" title="Crear flujo" id="nuevoFlujoCrmData">
			<i class="fa fa-x fa-plus-circle"></i> 
			Crear  oportunidad
		</a>
	</li>
	<li class="nav-item contentavatar">
		<p class="nameavatar">
			<span>Hola, </span>

			<a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'profile')) ?>" >
				<?php
					$nombreusuario = AuthComponent::user('name');$solonombre = explode(" ",$nombreusuario);echo $solonombre[0];
				?>
            </a>
		</p>
			<a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'profile')) ?>" >
				<span class="avatar" style="background-image: url('<?php echo $this->Html->url('/img/users/'.AuthComponent::user('img')); ?>')"></span>
			</a>	
	</li>
	<li class="nav-item datehour">	
		<p><?php echo date("g:i a"); ?></p>	
	</li>
	<?php if (in_array(AuthComponent::user("role"), ['Gerente General','Asesor Comercial'])): ?>
		<li class="nav-item datehour">	
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprovee')) ?>" class="btn btn-danger counterAprove mt-2" style="    padding: 0 10px !important; font-size: 20px !important;">0</a>	
		</li>
	<?php endif ?>

	<li class="nav-item dropdown ">

		<a class="nav-link dropdown-toggle" id="alertsDropdown" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-bell"></i>
			<span class="indicator" id="count_notificaciones">
				<?php echo $this->Utilities->count_notificaciones_user(); ?>
			</span>
		</a>
		<div class="dropdown-menu dropdown-menu-right" id="paint_notificaciones" aria-labelledby="alertsDropdown">
		</div>

	</li>
	

	<li class="nav-item closew">
		<a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'logout')) ?>" class="nav-link">
			<i class="fa fa-1x fa-power-off"></i>
		</a>
	</li>
</ul>

