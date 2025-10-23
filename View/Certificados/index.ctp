<div class="certificados index">
	<h2><?php echo __('Certificados'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('clients_natural_id'); ?></th>
			<th><?php echo $this->Paginator->sort('clients_legal_id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('identification'); ?></th>
			<th><?php echo $this->Paginator->sort('course'); ?></th>
			<th><?php echo $this->Paginator->sort('city_date'); ?></th>
			<th><?php echo $this->Paginator->sort('imagename'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($certificados as $certificado): ?>
	<tr>
		<td><?php echo h($certificado['Certificado']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($certificado['ClientsNatural']['name'], array('controller' => 'clients_naturals', 'action' => 'view', $certificado['ClientsNatural']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($certificado['ClientsLegal']['name'], array('controller' => 'clients_legals', 'action' => 'view', $certificado['ClientsLegal']['id'])); ?>
		</td>
		<td><?php echo h($certificado['Certificado']['name']); ?>&nbsp;</td>
		<td><?php echo h($certificado['Certificado']['identification']); ?>&nbsp;</td>
		<td><?php echo h($certificado['Certificado']['course']); ?>&nbsp;</td>
		<td><?php echo h($certificado['Certificado']['city_date']); ?>&nbsp;</td>
		<td><?php echo h($certificado['Certificado']['imagename']); ?>&nbsp;</td>
		<td><?php echo h($certificado['Certificado']['created']); ?>&nbsp;</td>
		<td><?php echo h($certificado['Certificado']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $certificado['Certificado']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $certificado['Certificado']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $certificado['Certificado']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $certificado['Certificado']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Certificado'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Clients Naturals'), array('controller' => 'clients_naturals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Natural'), array('controller' => 'clients_naturals', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clients Legals'), array('controller' => 'clients_legals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Legal'), array('controller' => 'clients_legals', 'action' => 'add')); ?> </li>
	</ul>
</div>
