<div class="customerRequests view">
<h2><?php echo __('Customer Request'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Identification'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['identification']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Address'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['phone']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('City'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['city']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rut'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['rut']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($customerRequest['User']['name'], array('controller' => 'users', 'action' => 'view', $customerRequest['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Create'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['user_create']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Created'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['date_created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($customerRequest['CustomerRequest']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Customer Request'), array('action' => 'edit', $customerRequest['CustomerRequest']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Customer Request'), array('action' => 'delete', $customerRequest['CustomerRequest']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $customerRequest['CustomerRequest']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Customer Requests'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Customer Request'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
