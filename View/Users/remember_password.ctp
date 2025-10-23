<div class="row">
	<div class="col-lg-12">

	</div>
</div>
<div class="container">
	<img class="brandlogin" src="<?php echo $this->Html->url('/img/assets/brand2.png'); ?>">
	<div class="container ">
		<div class="loginblock">
			<div class="page-header text-center">
				<h1 class="">KEBCO <span>CRM</span> </h1>
			</div>

			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Restablecer contraseña</h3>
				</div>
				<div class="panel-body">
						<div class="col-md-12 " >
							<?php echo $this->Form->create('User');?>
								<p>Por favor ingresa el correo electrónico</p>
							    <?php echo $this->Form->input('email',array('placeholder' => 'Correo electrónico','label' => false)); ?>
						</div>
				</div>	
			</div>		
		</div>
		<div class="botonsaction">
			<div class="col-md-12 tabs formend blue">
				<?php echo $this->Form->end('Enviar'); ?>
			</div>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>