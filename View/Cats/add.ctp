
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-secondary big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white	 bannerbig" >Módulo de gestión de compromisos</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h1 class="nameview">Creación categorías de compromisos </h1>
				<br>
				<a href="<?php echo $this->Html->url(["action" => "index"]) ?>" class="btn btn-info">
					<i class="fa fa-list vtc"></i> Gestión de categorías
				</a>
			</div>
			<div class="col-md-6">
				<?php if (AuthComponent::user("role") == "Gerente General"): ?>
					<ul class="subpagos-box2 row">
						<li class="col-md-4 border-right border-white m-0 ">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index')) ?>"> Gestionar mis compromisos</a>
						</li>	
						
						<li class="col-md-4 border-right border-white m-0 activesub">
							<a href="<?php echo $this->Html->url(array('controller'=>'cats','action'=>'index')) ?>">Gestionar mis categorías</a>
						</li>
						<li class=" border-right border-white col-md-4 m-0 ">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index',md5(AuthComponent::user("id")))) ?>">Compromisos de asesores</a>
						</li>					
					</ul>
				<?php else: ?>
					<ul class="subpagos-box row">
						<li class="border border-right border-white col-md-6 m-0 ">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index')) ?>">Gestionar mis compromisos</a>
						</li>
						<li class="border border-right border-white col-md-6 m-0 activesub">
							<a href="<?php echo $this->Html->url(array('controller'=>'cats','action'=>'index')) ?>">Gestionar mis categorías</a>
						</li>
						
					</ul>
				<?php endif ?>
				
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="cats form">
			<?php echo $this->Form->create('Cat'); ?>
				<?php
					echo $this->Form->input('name',["label" => "Nombre"]);
					echo $this->Form->input('description',["label" => "Descripción"]);
					echo $this->Form->input('user_id',["type" => "hidden", "value" => AuthComponent::user("id") ]);
					echo $this->Form->input('email', ["label" => "Notificación por correo electrónico", "options" => ["1" => "Si", "0" => "No"] ]);
					echo $this->Form->input('whatsapp', ["label" => "Notificación por Whatsapp", "options" => ["1" => "Si", "0" => "No"] ]);
				?>
			<?php echo $this->Form->end(__('Guardar')); ?>
		</div>
	</div>
</div>


<?php 
	
	echo $this->element("jquery");

 ?>