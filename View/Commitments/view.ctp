<?php $gestion = null ?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-secondary big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white	 bannerbig" >Módulo de gestión de compromisos</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h1 class="nameview">Gestión de mis compromisos </h1>
				<span class="subname">Visualización de compromiso</span>
				<br>
				<a href="<?php echo $this->Html->url(["action" => "add", is_null($gestion) ? "" : $gestion ]) ?>" class="btn btn-info">
					<i class="fa fa-plus vtc"></i> Crear compromiso
				</a>
				<a href="<?php echo $this->Html->url(["action" => "index", is_null($gestion) ? "" : $gestion ]) ?>" class="btn btn-success">
					<i class="fa fa-calendar vtc"></i> Ver listado
				</a>
				<a href="<?php echo $this->Html->url(["action" => "index", is_null($gestion) ? "" : $gestion, "?" => ["calendar" => time() ] ]) ?>" class="btn btn-warning">
					<i class="fa fa-calendar vtc"></i> Ver calendario de pendientes
				</a>
				
			</div>
			<div class="col-md-6">
				<?php if (AuthComponent::user("role") == "Gerente General"): ?>
					<ul class="subpagos-box2 row">
						<li class="col-md-4 border-right border-white m-0 <?php echo is_null($gestion) ? "activesub" : "" ?>">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index')) ?>"> Gestionar mis compromisos</a>
						</li>	
						
						<li class="col-md-4 border-right border-white m-0 ">
							<a href="<?php echo $this->Html->url(array('controller'=>'cats','action'=>'index')) ?>">Gestionar mis categorías</a>
						</li>
						<li class=" border-right border-white col-md-4 m-0 <?php echo !is_null($gestion) ? "activesub" : "" ?>">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index',md5(AuthComponent::user("id")))) ?>">Compromisos de asesores</a>
						</li>					
					</ul>
				<?php else: ?>
					<ul class="subpagos-box row">
						<li class="border border-right border-white col-md-6 m-0 activesub">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index')) ?>">Gestionar mis compromisos</a>
						</li>
						<li class="border border-right border-white col-md-6 m-0">
							<a href="<?php echo $this->Html->url(array('controller'=>'cats','action'=>'index')) ?>">Gestionar mis categorías</a>
						</li>
						
					</ul>
				<?php endif ?>
				
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<dl>

		<dt><?php echo __('Nombre compromiso'); ?></dt>
		<dd>
			<?php echo h($commitment['Commitment']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripción del compromiso'); ?></dt>
		<dd>
			<?php echo h($commitment['Commitment']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Categoría'); ?></dt>
		<dd>
			<?php echo $this->Html->link($commitment['Cat']['name'], array('controller' => 'cats', 'action' => 'view', $commitment['Cat']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fechá límite'); ?></dt>
		<dd>
			<?php echo h($commitment['Commitment']['deadline']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Usuario asignado'); ?></dt>
		<dd>
			<?php echo $commitment['User']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Asignó'); ?></dt>
		<dd>
			<?php echo $this->Utilities->find_name_adviser($commitment['Commitment']['assiged_by']); ?>&nbsp;
			&nbsp;
		</dd>
<!-- 		<dt><?php echo __('Prospective User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($commitment['ProspectiveUser']['id'], array('controller' => 'prospective_users', 'action' => 'view', $commitment['ProspectiveUser']['id'])); ?>
			&nbsp;
		</dd> -->
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php 
				switch ($commitment['Commitment']['state']) {
					case '1':
						echo "Activo sin completar";
						break;
					case '0':
						echo "Vencido";
						break;
					case '2':
						echo "Completado";
						break;
				}
			?>&nbsp;
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha de creación'); ?></dt>
		<dd>
			<?php echo h($commitment['Commitment']['created']); ?>
			&nbsp;
		</dd>
	</dl>
	</div>
</div>

<?php 
	echo $this->element("jquery"); 
	
?>