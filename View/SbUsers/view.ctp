<div class="sbUsers view">
<h2><?php echo __('Sb User'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('First Name'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['first_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Name'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['last_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Password'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['password']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Profile Image'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['profile_image']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Type'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['user_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Creation Time'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['creation_time']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Token'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['token']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Activity'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['last_activity']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Typing'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['typing']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Online'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['online']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Department'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['department']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Response Crm'); ?></dt>
		<dd>
			<?php echo h($sbUser['SbUser']['response_crm']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Sb User'), array('action' => 'edit', $sbUser['SbUser']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Sb User'), array('action' => 'delete', $sbUser['SbUser']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $sbUser['SbUser']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Sb Users'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Sb User'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Sb Tiempos'), array('controller' => 'sb_tiempos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Sb Tiempo'), array('controller' => 'sb_tiempos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Sb Tiempos'); ?></h3>
	<?php if (!empty($sbUser['SbTiempo'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Sb User Id'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Fecha'); ?></th>
		<th><?php echo __('Date Ini'); ?></th>
		<th><?php echo __('Date End'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($sbUser['SbTiempo'] as $sbTiempo): ?>
		<tr>
			<td><?php echo $sbTiempo['id']; ?></td>
			<td><?php echo $sbTiempo['sb_user_id']; ?></td>
			<td><?php echo $sbTiempo['type']; ?></td>
			<td><?php echo $sbTiempo['fecha']; ?></td>
			<td><?php echo $sbTiempo['date_ini']; ?></td>
			<td><?php echo $sbTiempo['date_end']; ?></td>
			<td><?php echo $sbTiempo['created']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'sb_tiempos', 'action' => 'view', $sbTiempo['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'sb_tiempos', 'action' => 'edit', $sbTiempo['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'sb_tiempos', 'action' => 'delete', $sbTiempo['id']), array('confirm' => __('Are you sure you want to delete # %s?', $sbTiempo['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Sb Tiempo'), array('controller' => 'sb_tiempos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
