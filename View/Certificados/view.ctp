<div class="certificados view">
<h2><?php echo __('Certificado'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($certificado['Certificado']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Clients Natural'); ?></dt>
		<dd>
			<?php echo $this->Html->link($certificado['ClientsNatural']['name'], array('controller' => 'clients_naturals', 'action' => 'view', $certificado['ClientsNatural']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Clients Legal'); ?></dt>
		<dd>
			<?php echo $this->Html->link($certificado['ClientsLegal']['name'], array('controller' => 'clients_legals', 'action' => 'view', $certificado['ClientsLegal']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($certificado['Certificado']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Identification'); ?></dt>
		<dd>
			<?php echo h($certificado['Certificado']['identification']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course'); ?></dt>
		<dd>
			<?php echo h($certificado['Certificado']['course']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('City Date'); ?></dt>
		<dd>
			<?php echo h($certificado['Certificado']['city_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Imagename'); ?></dt>
		<dd>
			<?php echo h($certificado['Certificado']['imagename']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($certificado['Certificado']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($certificado['Certificado']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Certificado'), array('action' => 'edit', $certificado['Certificado']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Certificado'), array('action' => 'delete', $certificado['Certificado']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $certificado['Certificado']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Certificados'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Certificado'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clients Naturals'), array('controller' => 'clients_naturals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Natural'), array('controller' => 'clients_naturals', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clients Legals'), array('controller' => 'clients_legals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Legal'), array('controller' => 'clients_legals', 'action' => 'add')); ?> </li>
	</ul>
</div>
