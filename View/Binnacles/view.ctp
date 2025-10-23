<div class="binnacles view">
<h2><?php echo __('Binnacle'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($binnacle['Binnacle']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Note'); ?></dt>
		<dd>
			<?php echo h($binnacle['Binnacle']['note']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Technical Service'); ?></dt>
		<dd>
			<?php echo $this->Html->link($binnacle['TechnicalService']['id'], array('controller' => 'technical_services', 'action' => 'view', $binnacle['TechnicalService']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($binnacle['User']['name'], array('controller' => 'users', 'action' => 'view', $binnacle['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Ini'); ?></dt>
		<dd>
			<?php echo h($binnacle['Binnacle']['date_ini']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date End'); ?></dt>
		<dd>
			<?php echo h($binnacle['Binnacle']['date_end']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($binnacle['Binnacle']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($binnacle['Binnacle']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($binnacle['Binnacle']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Binnacle'), array('action' => 'edit', $binnacle['Binnacle']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Binnacle'), array('action' => 'delete', $binnacle['Binnacle']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $binnacle['Binnacle']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Binnacles'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Binnacle'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Technical Services'), array('controller' => 'technical_services', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Technical Service'), array('controller' => 'technical_services', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
