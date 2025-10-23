<header>
	<div class="row">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<img class="brandlogin" src="<?php echo $this->Html->url('/img/assets/brand2.png'); ?>">
				</div>	
			</div>
			<div class="loginblock" style="    margin-top: 20px;    padding: 25px !important;">
				<div class="page-header text-center">
					<h1 class="">KEBCO <span>CRM</span> </h1>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Iniciar sesión</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6 col-md-12 ">
								<ul class="dataitems">
									<li><i class="fa fa-check" aria-hidden="true"></i>Cotizaciones más rápidas y personalizadas</li>
									<li><i class="fa fa-check" aria-hidden="true"></i>Actividades y tareas automatizadas</li>
									<li><i class="fa fa-check" aria-hidden="true"></i>Monitorio de ventas en directo</li>
									<li><i class="fa fa-check" aria-hidden="true"></i>Gestión de prospectos por estados</li>
									<li><i class="fa fa-check" aria-hidden="true"></i>Acceso web, disponibilidad 24/7</li>
								</ul>
							</div>

							<div class="col-lg-6 col-md-12 line">
								<?php echo $this->Form->create('User',array('data-parsley-validate')); ?>
								<?php 
								echo $this->Form->input('email',array('placeholder' => 'Ingrese su correo electrónico','label' => 'Correo electrónico'));
								echo $this->Form->input('password',array('placeholder' => '*******','label' => 'Contraseña'));
								?>
							</div>
						</div>
					</div>	
				</div>		
			</div>
			<div class="botonsaction">
				<div class="col-md-12 tabs formend">
					<?php echo $this->Form->end('Ingresar'); ?>
				</div>
				<div class="row">
					<div class="col-md-6">
						<a class="resset mt-1" href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'remember_password')) ?>" >Restablecer contraseña </a>
					</div>
					<div class="col-md-6">
						<a class="resset mt-1" href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'register')) ?>" >Registro vendedores </a>
					</div>
				</div>
				
			</div>
	</div>
</header>
<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>

<style>
	.userslogin .submit input, .Usersremember_password .submit input, .usersremember_password_step_2 .submit input {
    	padding-bottom: 0.5rem !important;
    	padding-top: 0.5rem !important;
	}
</style>