<header>
	<div class="row">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<img class="brandlogin" src="<?php echo $this->Html->url('/img/assets/brand2.png'); ?>">
				</div>	
			</div>
			<div class="loginblock">
				<div class="page-header text-center">
					<h1 class="">CLIENTES KEBCO <span>CRM</span> </h1>
				</div>
				<div class="panel panel-info pb-5">
					<div class="panel-heading">
						<h3 class="panel-title text-center py-3">Iniciar sesión</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6 col-md-12 ">
								<ul class="dataitems">
									<li><i class="fa fa-check" aria-hidden="true"></i>Ordenes de compra realizadas</li>
									<li><i class="fa fa-check" aria-hidden="true"></i>Seguimiento a despachos realizados</li>
									<li><i class="fa fa-check" aria-hidden="true"></i>Volver a pedir un producto</li>
									<li><i class="fa fa-check" aria-hidden="true"></i>PQRS</li>
									<!-- <li><i class="fa fa-check" aria-hidden="true"></i>Acceso web, disponibilidad 24/7</li> -->
								</ul>
							</div>

							<div class="col-lg-6 col-md-12 line">
								<?php echo $this->Form->create('User',array('data-parsley-validate')); ?>
								<?php echo $this->Form->input('email',array('placeholder' => 'Ingrese su correo electrónico','label' => 'Correo electrónico')); ?>
								<a href="" class="btn btn-warning btn-block" id="envioCodigo"> Enviar código de valdiación </a>
								<?php 
									echo $this->Form->input('code',array('placeholder' => '*******','label' => 'Código enviado al correo electrónico', 'div' => "codeEmail form-group", "class" => "form-control" ));
								?>
								<a href="" class="btn btn-success btn-block my-2" id="validarCodigo"> Validar código </a>
							</div>
						</div>
					</div>	
				</div>		
			</div>
			<div class="botonsaction">
				<div class="col-md-12 tabs formend" id="btnIngreso">
					<?php echo $this->Form->end('Ingresar'); ?>
				</div>
			</div>
	</div>
</header>
<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
echo $this->Html->script("controller/clientes/login.js?".rand(),					array('block' => 'AppScript'));
?>

<style>
	.codeEmail,#btnIngreso,#validarCodigo{
		display:none;
	}
</style>