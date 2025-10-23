<div class="carpetaDetalles form">
<?php echo $this->Form->create('CarpetaDetalle'); ?>
	<fieldset>
		<legend><?php echo __('Edit Carpeta Detalle'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('carpeta_id');
		echo $this->Form->input('document_id');
		echo $this->Form->input('blog_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('CarpetaDetalle.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('CarpetaDetalle.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Carpeta Detalles'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Carpetas'), array('controller' => 'carpetas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Carpeta'), array('controller' => 'carpetas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Documents'), array('controller' => 'documents', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Document'), array('controller' => 'documents', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Blogs'), array('controller' => 'blogs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Blog'), array('controller' => 'blogs', 'action' => 'add')); ?> </li>
	</ul>
</div>
