<div class="concessions view">
<h2><?php echo __('Concession'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($concession['Concession']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($concession['Concession']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($concession['Concession']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Clients Legal'); ?></dt>
		<dd>
			<?php echo $this->Html->link($concession['ClientsLegal']['name'], array('controller' => 'clients_legals', 'action' => 'view', $concession['ClientsLegal']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($concession['Concession']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($concession['Concession']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Concession'), array('action' => 'edit', $concession['Concession']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Concession'), array('action' => 'delete', $concession['Concession']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $concession['Concession']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Concessions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Concession'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clients Legals'), array('controller' => 'clients_legals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Legal'), array('controller' => 'clients_legals', 'action' => 'add')); ?> </li>
	</ul>
</div>
