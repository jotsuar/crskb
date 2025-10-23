<div class="carpetaDetalles view">
<h2><?php echo __('Carpeta Detalle'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($carpetaDetalle['CarpetaDetalle']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Carpeta'); ?></dt>
		<dd>
			<?php echo $this->Html->link($carpetaDetalle['Carpeta']['name'], array('controller' => 'carpetas', 'action' => 'view', $carpetaDetalle['Carpeta']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Document'); ?></dt>
		<dd>
			<?php echo $this->Html->link($carpetaDetalle['Document']['name'], array('controller' => 'documents', 'action' => 'view', $carpetaDetalle['Document']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Blog'); ?></dt>
		<dd>
			<?php echo $this->Html->link($carpetaDetalle['Blog']['name'], array('controller' => 'blogs', 'action' => 'view', $carpetaDetalle['Blog']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($carpetaDetalle['CarpetaDetalle']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($carpetaDetalle['CarpetaDetalle']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Carpeta Detalle'), array('action' => 'edit', $carpetaDetalle['CarpetaDetalle']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Carpeta Detalle'), array('action' => 'delete', $carpetaDetalle['CarpetaDetalle']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $carpetaDetalle['CarpetaDetalle']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Carpeta Detalles'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Carpeta Detalle'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Carpetas'), array('controller' => 'carpetas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Carpeta'), array('controller' => 'carpetas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Documents'), array('controller' => 'documents', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Document'), array('controller' => 'documents', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Blogs'), array('controller' => 'blogs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Blog'), array('controller' => 'blogs', 'action' => 'add')); ?> </li>
	</ul>
</div>
