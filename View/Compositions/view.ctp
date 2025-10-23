<div class="compositions view">
<h2><?php echo __('Composition'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($composition['Composition']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Principal'); ?></dt>
		<dd>
			<?php echo h($composition['Composition']['principal']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product'); ?></dt>
		<dd>
			<?php echo $this->Html->link($composition['Product']['name'], array('controller' => 'products', 'action' => 'view', $composition['Product']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($composition['Composition']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($composition['Composition']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modifed'); ?></dt>
		<dd>
			<?php echo h($composition['Composition']['modifed']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Composition'), array('action' => 'edit', $composition['Composition']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Composition'), array('action' => 'delete', $composition['Composition']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $composition['Composition']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Compositions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Composition'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
