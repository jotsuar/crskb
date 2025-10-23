<div class="aditionals view">
<h2><?php echo __('Aditional'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($aditional['Aditional']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Accesorio'); ?></dt>
		<dd>
			<?php echo h($aditional['Aditional']['accesorio']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($aditional['Aditional']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($aditional['User']['name'], array('controller' => 'users', 'action' => 'view', $aditional['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($aditional['Aditional']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($aditional['Aditional']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Aditional'), array('action' => 'edit', $aditional['Aditional']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Aditional'), array('action' => 'delete', $aditional['Aditional']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $aditional['Aditional']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Aditionals'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Aditional'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
