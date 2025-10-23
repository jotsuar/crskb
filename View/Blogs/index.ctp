<div class="blogs index">
	<h2><?php echo __('Blogs'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('carpeta_id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo $this->Paginator->sort('url'); ?></th>
			<th><?php echo $this->Paginator->sort('archivo_1'); ?></th>
			<th><?php echo $this->Paginator->sort('archivo_2'); ?></th>
			<th><?php echo $this->Paginator->sort('archivo_3'); ?></th>
			<th><?php echo $this->Paginator->sort('imagen_1'); ?></th>
			<th><?php echo $this->Paginator->sort('imagen_2'); ?></th>
			<th><?php echo $this->Paginator->sort('imagen_3'); ?></th>
			<th><?php echo $this->Paginator->sort('public'); ?></th>
			<th><?php echo $this->Paginator->sort('state'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($blogs as $blog): ?>
	<tr>
		<td><?php echo h($blog['Blog']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($blog['Carpeta']['name'], array('controller' => 'carpetas', 'action' => 'view', $blog['Carpeta']['id'])); ?>
		</td>
		<td><?php echo h($blog['Blog']['name']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['description']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['url']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['archivo_1']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['archivo_2']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['archivo_3']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['imagen_1']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['imagen_2']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['imagen_3']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['public']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['state']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['created']); ?>&nbsp;</td>
		<td><?php echo h($blog['Blog']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $blog['Blog']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $blog['Blog']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $blog['Blog']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $blog['Blog']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Blog'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Carpetas'), array('controller' => 'carpetas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Carpeta'), array('controller' => 'carpetas', 'action' => 'add')); ?> </li>
	</ul>
</div>
