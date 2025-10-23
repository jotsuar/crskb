<div class="carpetas view">
<h2><?php echo __('Carpeta'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($carpeta['Carpeta']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($carpeta['Carpeta']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($carpeta['Carpeta']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($carpeta['Carpeta']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($carpeta['User']['name'], array('controller' => 'users', 'action' => 'view', $carpeta['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($carpeta['Carpeta']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($carpeta['Carpeta']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Carpeta'), array('action' => 'edit', $carpeta['Carpeta']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Carpeta'), array('action' => 'delete', $carpeta['Carpeta']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $carpeta['Carpeta']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Carpetas'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Carpeta'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Blogs'), array('controller' => 'blogs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Blog'), array('controller' => 'blogs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Documents'), array('controller' => 'documents', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Document'), array('controller' => 'documents', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Blogs'); ?></h3>
	<?php if (!empty($carpeta['Blog'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Carpeta Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Url'); ?></th>
		<th><?php echo __('Archivo 1'); ?></th>
		<th><?php echo __('Archivo 2'); ?></th>
		<th><?php echo __('Archivo 3'); ?></th>
		<th><?php echo __('Imagen 1'); ?></th>
		<th><?php echo __('Imagen 2'); ?></th>
		<th><?php echo __('Imagen 3'); ?></th>
		<th><?php echo __('Public'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($carpeta['Blog'] as $blog): ?>
		<tr>
			<td><?php echo $blog['id']; ?></td>
			<td><?php echo $blog['carpeta_id']; ?></td>
			<td><?php echo $blog['name']; ?></td>
			<td><?php echo $blog['description']; ?></td>
			<td><?php echo $blog['url']; ?></td>
			<td><?php echo $blog['archivo_1']; ?></td>
			<td><?php echo $blog['archivo_2']; ?></td>
			<td><?php echo $blog['archivo_3']; ?></td>
			<td><?php echo $blog['imagen_1']; ?></td>
			<td><?php echo $blog['imagen_2']; ?></td>
			<td><?php echo $blog['imagen_3']; ?></td>
			<td><?php echo $blog['public']; ?></td>
			<td><?php echo $blog['state']; ?></td>
			<td><?php echo $blog['created']; ?></td>
			<td><?php echo $blog['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'blogs', 'action' => 'view', $blog['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'blogs', 'action' => 'edit', $blog['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'blogs', 'action' => 'delete', $blog['id']), array('confirm' => __('Are you sure you want to delete # %s?', $blog['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Blog'), array('controller' => 'blogs', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Documents'); ?></h3>
	<?php if (!empty($carpeta['Document'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('File'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Carpeta Id'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($carpeta['Document'] as $document): ?>
		<tr>
			<td><?php echo $document['id']; ?></td>
			<td><?php echo $document['file']; ?></td>
			<td><?php echo $document['type']; ?></td>
			<td><?php echo $document['name']; ?></td>
			<td><?php echo $document['description']; ?></td>
			<td><?php echo $document['carpeta_id']; ?></td>
			<td><?php echo $document['state']; ?></td>
			<td><?php echo $document['created']; ?></td>
			<td><?php echo $document['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'documents', 'action' => 'view', $document['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'documents', 'action' => 'edit', $document['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'documents', 'action' => 'delete', $document['id']), array('confirm' => __('Are you sure you want to delete # %s?', $document['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Document'), array('controller' => 'documents', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
