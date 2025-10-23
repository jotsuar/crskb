<div class="importProducts view">
<h2><?php echo __('Import Product'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Import'); ?></dt>
		<dd>
			<?php echo $this->Html->link($importProduct['Import']['id'], array('controller' => 'imports', 'action' => 'view', $importProduct['Import']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product'); ?></dt>
		<dd>
			<?php echo $this->Html->link($importProduct['Product']['name'], array('controller' => 'products', 'action' => 'view', $importProduct['Product']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Quotations Products'); ?></dt>
		<dd>
			<?php echo $this->Html->link($importProduct['QuotationsProducts']['id'], array('controller' => 'quotations_products', 'action' => 'view', $importProduct['QuotationsProducts']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Prospective User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($importProduct['ProspectiveUser']['id'], array('controller' => 'prospective_users', 'action' => 'view', $importProduct['ProspectiveUser']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Quantity'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['quantity']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Quantity Final'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['quantity_final']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Quantity Back'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['quantity_back']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Quantity Back Total'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['quantity_back_total']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Currency'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['currency']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Price'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['price']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numero Orden'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['numero_orden']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Proveedor'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['proveedor']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha Orden'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['fecha_orden']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Link'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['link']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha Estimada'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['fecha_estimada']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha Miami'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['fecha_miami']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numero Guia'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['numero_guia']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Transportadora'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['transportadora']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha Nacionalizacion'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['fecha_nacionalizacion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha Producto Empresa'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['fecha_producto_empresa']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State Import'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['state_import']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($importProduct['ImportProduct']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Import Product'), array('action' => 'edit', $importProduct['ImportProduct']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Import Product'), array('action' => 'delete', $importProduct['ImportProduct']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $importProduct['ImportProduct']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Import Products'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Import Product'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Imports'), array('controller' => 'imports', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Import'), array('controller' => 'imports', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Quotations Products'), array('controller' => 'quotations_products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Quotations Products'), array('controller' => 'quotations_products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Prospective Users'), array('controller' => 'prospective_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prospective User'), array('controller' => 'prospective_users', 'action' => 'add')); ?> </li>
	</ul>
</div>
