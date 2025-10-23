<div class="responsepqrs view">
<h2><?php echo __('Responsepqr'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($responsepqr['Responsepqr']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Id Pqr'); ?></dt>
		<dd>
			<?php echo h($responsepqr['Responsepqr']['id_pqr']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($responsepqr['User']['name'], array('controller' => 'users', 'action' => 'view', $responsepqr['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($responsepqr['Responsepqr']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('File'); ?></dt>
		<dd>
			<?php echo h($responsepqr['Responsepqr']['file']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($responsepqr['Responsepqr']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($responsepqr['Responsepqr']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Responsepqr'), array('action' => 'edit', $responsepqr['Responsepqr']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Responsepqr'), array('action' => 'delete', $responsepqr['Responsepqr']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $responsepqr['Responsepqr']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Responsepqrs'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Responsepqr'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
