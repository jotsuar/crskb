<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-secondary big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white	 bannerbig" >Módulo de gestión de compromisos</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h1 class="nameview">Gestión de mis categorías de compromisos</h1>
				<br>
				<a href="<?php echo $this->Html->url(["action" => "add"]) ?>" class="btn btn-info">
					<i class="fa fa-plus vtc"></i> Crear nueva categoría
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
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por nombre o descripción">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por nombre o descripción">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-hovered">
				<thead>
				<tr>
						<th><?php echo $this->Paginator->sort('name','Nombre'); ?></th>
						<th><?php echo $this->Paginator->sort('description','Descripción'); ?></th>
						<th><?php echo $this->Paginator->sort('state','Estado'); ?></th>
						<th><?php echo $this->Paginator->sort('email','Notificación por email'); ?></th>
						<th><?php echo $this->Paginator->sort('whatsapp','Notificación por whatsapp'); ?></th>
						<th><?php echo $this->Paginator->sort('created','Fecha de creación'); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($cats as $cat): ?>
				<tr>
					<td><?php echo h($cat['Cat']['name']); ?>&nbsp;</td>
					<td><?php echo h($cat['Cat']['description']); ?>&nbsp;</td>
					<td><?php echo ($cat['Cat']['state']) == "1" ? "Activo" : "Inactivo" ; ?>&nbsp;</td>
					<td><?php echo ($cat['Cat']['email']) == 1 ? "Si" : "No"; ?>&nbsp;</td>
					<td><?php echo ($cat['Cat']['whatsapp']) == 1 ? "Si" : "No"; ?>&nbsp;</td>
					<td><?php echo h($cat['Cat']['created']); ?>&nbsp;</td>
					<td class="actions">
						<?php if ($cat["Cat"]["general"] != 1): ?>
							
						<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($cat['Cat']['id']))) ?>" data-toggle="tooltip" title="Ver categoría"><i class="fa fa-fw fa-eye"></i>
					    </a>
					    <a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($cat['Cat']['id']) )) ?>" data-toggle="tooltip" title="Editar categoría">
					    	<i class="fa fa-fw fa-pencil"></i>
					    </a>
					    <a href="<?php echo $this->Html->url(["action" => "change", $this->Utilities->encryptString($cat['Cat']['id']) ]) ?>" title="<?php echo $cat["Cat"]["state"] == 1 ? "Desactivar" : "Activar" ?> categoría" class="btnChangeState">
							<?php if ($cat["Cat"]["state"] == 1): ?>
								<i class="fa fa-fw fa-times"></i>
							<?php else: ?>
								<i class="fa fa-fw fa-times"></i>
							<?php endif ?>
						</a>
						<?php endif ?>
					</td>
				</tr>
			<?php endforeach; ?>
				</tbody>
				</table>
		</div>
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

<?php 
	
	echo $this->element("jquery");
	echo $this->Html->script("controller/clientsLegal/index.js",				array('block' => 'AppScript'));
 ?>