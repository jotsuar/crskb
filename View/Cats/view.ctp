<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-secondary big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white	 bannerbig" >Módulo de gestión de tareas y compromisos</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h1 class="nameview">Visualización categorías de compromisos y tareas</h1>
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
	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-4">
				<b>Categoría:</b> <?php echo $cat["Cat"]["name"] ?>
			</div>
			<div class="col-md-4">
				<b>Descripción:</b> <?php echo h($cat['Cat']['description']); ?>
			</div>
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<b>Notificaciones por correo electrónico: </b> <?php echo ($cat['Cat']['email']) == 1 ? "Si" : "No"; ?>&nbsp;
			</div>
			<div class="col-md-4">
				<b>Notificaciones por whatsapp: </b><?php echo ($cat['Cat']['whatsapp']) == 1 ? "Si" : "No"; ?>&nbsp;
			</div>
			<div class="col-md-4">
				<b>Fecha de creación: </b> <?php echo h($cat['Cat']['created']); ?>
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h2 class="text-center">
					Compromisos asociados
				</h2>
			</div>
		</div>
	</div>
</div>

<?php 
	
	echo $this->element("jquery");

 ?>

<div class="related">
	<?php if (!empty($cat['Commitment'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Cat Id'); ?></th>
		<th><?php echo __('Deadline'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Assiged By'); ?></th>
		<th><?php echo __('Prospective User Id'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($cat['Commitment'] as $commitment): ?>
		<tr>
			<td><?php echo $commitment['id']; ?></td>
			<td><?php echo $commitment['name']; ?></td>
			<td><?php echo $commitment['description']; ?></td>
			<td><?php echo $commitment['cat_id']; ?></td>
			<td><?php echo $commitment['deadline']; ?></td>
			<td><?php echo $commitment['user_id']; ?></td>
			<td><?php echo $commitment['assiged_by']; ?></td>
			<td><?php echo $commitment['prospective_user_id']; ?></td>
			<td><?php echo $commitment['state']; ?></td>
			<td><?php echo $commitment['type']; ?></td>
			<td><?php echo $commitment['created']; ?></td>
			<td><?php echo $commitment['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'commitments', 'action' => 'view', $commitment['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'commitments', 'action' => 'edit', $commitment['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'commitments', 'action' => 'delete', $commitment['id']), array('confirm' => __('Are you sure you want to delete # %s?', $commitment['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
