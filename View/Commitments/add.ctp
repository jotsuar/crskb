<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-secondary big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white	 bannerbig" >Módulo de gestión de tareas y compromisos</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h1 class="nameview">Creación compromisos</h1>
				<br>
				<a href="<?php echo $this->Html->url(["action" => "index"]) ?>" class="btn btn-info">
					<i class="fa fa-list vtc"></i> Gestiónar compromisos
				</a>
			</div>
			<div class="col-md-6">
				<?php if (AuthComponent::user("role") == "Gerente General"): ?>
					<ul class="subpagos-box2 row">
						<li class="col-md-4 border-right border-white m-0 activesub">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index')) ?>"> Gestionar mis compromisos</a>
						</li>	
						
						<li class="col-md-4 border-right border-white m-0 ">
							<a href="<?php echo $this->Html->url(array('controller'=>'cats','action'=>'index')) ?>">Gestionar mis categorías</a>
						</li>
						<li class=" border-right border-white col-md-4 m-0 ">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index',md5(AuthComponent::user("id")))) ?>">Compromisos de asesores</a>
						</li>					
					</ul>
				<?php else: ?>
					<ul class="subpagos-box row">
						<li class="border border-right border-white col-md-6 m-0 activesub">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index')) ?>">Gestionar mis compromisos</a>
						</li>
						<li class="border border-right border-white col-md-6 m-0 ">
							<a href="<?php echo $this->Html->url(array('controller'=>'cats','action'=>'index')) ?>">Gestionar mis categorías</a>
						</li>
						
					</ul>
				<?php endif ?>
				
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="commitments form">
			<?php echo $this->Form->create('Commitment'); ?>
				<?php
					echo $this->Form->input('name',["label" => "Nombre compromiso", ]);
					echo $this->Form->input('description',["label" => "Descripcion compromiso", ]);
					
				?>

				<div class="row">
					<div class="col-md-4">
						<?php 
							echo $this->Form->input('cat_id',["label" => "Categoría", ]);
						?>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="CommitmentDeadline">Fechá límite</label>
							<?php 
								echo $this->Form->text('deadline',["label" => "Fecha límite", "class" => "form-control" , "value" => date("Y-m-d H:i:s"), "type" => "text" ]);
							?>
						</div>
					</div>
					<div class="col-md-4">
						<?php 
							if (!is_null($gestion)) {
								echo $this->Form->input('user_id',["label" => "Usuario asignado",  ]);
							}else{
								echo $this->Form->input('user_id',["type" => "hidden", "value" => AuthComponent::user("id"), "id" => "commitmentIdGeneral"  ]);								
							}
							echo $this->Form->input('assiged_by',["type" => "hidden", "value" => AuthComponent::user("id")  ]);
						?>
					</div>
				</div>	

			<?php echo $this->Form->end(__('Guardar compromiso')); ?>
		</div>
	</div>
</div>


<?php 
	
	echo $this->Html->css("https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css?".time(),				array('block' => 'AppScript'));
	echo $this->Html->css("timepicker.css?".time(),				array('block' => 'AppScript'));
	echo $this->element("jquery");
	echo $this->Html->script("//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js?".time(),				array('block' => 'AppScript'));
	echo $this->Html->script("lib/timepicker.js?".time(),				array('block' => 'AppScript'));
	echo $this->Html->script("controller/commitments/general.js?".time(),				array('block' => 'AppScript'));
 ?>
