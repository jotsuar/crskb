<div class="adresses view">
<h2><?php echo __('Adress'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($adress['Adress']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($adress['Adress']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Address'); ?></dt>
		<dd>
			<?php echo h($adress['Adress']['address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Address Detail'); ?></dt>
		<dd>
			<?php echo h($adress['Adress']['address_detail']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('City'); ?></dt>
		<dd>
			<?php echo h($adress['Adress']['city']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone'); ?></dt>
		<dd>
			<?php echo h($adress['Adress']['phone']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone Two'); ?></dt>
		<dd>
			<?php echo h($adress['Adress']['phone_two']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($adress['Adress']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Clients Legal'); ?></dt>
		<dd>
			<?php echo $this->Html->link($adress['ClientsLegal']['name'], array('controller' => 'clients_legals', 'action' => 'view', $adress['ClientsLegal']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Clients Natural'); ?></dt>
		<dd>
			<?php echo $this->Html->link($adress['ClientsNatural']['name'], array('controller' => 'clients_naturals', 'action' => 'view', $adress['ClientsNatural']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Default'); ?></dt>
		<dd>
			<?php echo h($adress['Adress']['default']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($adress['Adress']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated'); ?></dt>
		<dd>
			<?php echo h($adress['Adress']['updated']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Adress'), array('action' => 'edit', $adress['Adress']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Adress'), array('action' => 'delete', $adress['Adress']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $adress['Adress']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Adresses'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Adress'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clients Legals'), array('controller' => 'clients_legals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Legal'), array('controller' => 'clients_legals', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clients Naturals'), array('controller' => 'clients_naturals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Natural'), array('controller' => 'clients_naturals', 'action' => 'add')); ?> </li>
	</ul>
</div>
