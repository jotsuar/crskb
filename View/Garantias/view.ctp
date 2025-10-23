<div class="garantias view">
<h2><?php echo __('Garantia'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($garantia['Garantia']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Brand'); ?></dt>
		<dd>
			<?php echo $this->Html->link($garantia['Brand']['name'], array('controller' => 'brands', 'action' => 'view', $garantia['Brand']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($garantia['Garantia']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($garantia['Garantia']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($garantia['Garantia']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($garantia['Garantia']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($garantia['Garantia']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Garantia'), array('action' => 'edit', $garantia['Garantia']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Garantia'), array('action' => 'delete', $garantia['Garantia']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $garantia['Garantia']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Garantias'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Garantia'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Brands'), array('controller' => 'brands', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Brand'), array('controller' => 'brands', 'action' => 'add')); ?> </li>
	</ul>
</div>
