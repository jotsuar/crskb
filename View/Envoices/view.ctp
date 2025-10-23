<div class="envoices view">
<h2><?php echo __('Envoice'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($envoice['Envoice']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($envoice['User']['name'], array('controller' => 'users', 'action' => 'view', $envoice['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Gest'); ?></dt>
		<dd>
			<?php echo h($envoice['Envoice']['user_gest']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order'); ?></dt>
		<dd>
			<?php echo $this->Html->link($envoice['Order']['id'], array('controller' => 'orders', 'action' => 'view', $envoice['Order']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Prefijo'); ?></dt>
		<dd>
			<?php echo h($envoice['Envoice']['prefijo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Identificator'); ?></dt>
		<dd>
			<?php echo h($envoice['Envoice']['identificator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Note'); ?></dt>
		<dd>
			<?php echo h($envoice['Envoice']['note']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($envoice['Envoice']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Initial'); ?></dt>
		<dd>
			<?php echo h($envoice['Envoice']['date_initial']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date End'); ?></dt>
		<dd>
			<?php echo h($envoice['Envoice']['date_end']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($envoice['Envoice']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($envoice['Envoice']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Envoice'), array('action' => 'edit', $envoice['Envoice']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Envoice'), array('action' => 'delete', $envoice['Envoice']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $envoice['Envoice']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Envoices'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Envoice'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Products'); ?></h3>
	<?php if (!empty($envoice['Product'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Id Llc'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Long Description'); ?></th>
		<th><?php echo __('Notes'); ?></th>
		<th><?php echo __('Category Id'); ?></th>
		<th><?php echo __('Garantia Id'); ?></th>
		<th><?php echo __('Img'); ?></th>
		<th><?php echo __('Img2'); ?></th>
		<th><?php echo __('Img3'); ?></th>
		<th><?php echo __('Img4'); ?></th>
		<th><?php echo __('Img5'); ?></th>
		<th><?php echo __('Manual 1'); ?></th>
		<th><?php echo __('Manual 2'); ?></th>
		<th><?php echo __('Manual 3'); ?></th>
		<th><?php echo __('Url Video'); ?></th>
		<th><?php echo __('Part Number'); ?></th>
		<th><?php echo __('List Price Usd'); ?></th>
		<th><?php echo __('Currency'); ?></th>
		<th><?php echo __('Purchase Price Usd'); ?></th>
		<th><?php echo __('Aditional Usd'); ?></th>
		<th><?php echo __('Purchase Price Wo'); ?></th>
		<th><?php echo __('Purchase Price Cop'); ?></th>
		<th><?php echo __('Aditional Cop'); ?></th>
		<th><?php echo __('Brand'); ?></th>
		<th><?php echo __('Group Margen'); ?></th>
		<th><?php echo __('Brand Id'); ?></th>
		<th><?php echo __('Deleted'); ?></th>
		<th><?php echo __('Link'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Normal'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Quantity'); ?></th>
		<th><?php echo __('Quantity Bog'); ?></th>
		<th><?php echo __('Quantity Stm'); ?></th>
		<th><?php echo __('Quantity Stb'); ?></th>
		<th><?php echo __('Quantity Back'); ?></th>
		<th><?php echo __('Stock'); ?></th>
		<th><?php echo __('Min Stock'); ?></th>
		<th><?php echo __('Max Cost'); ?></th>
		<th><?php echo __('Reorder'); ?></th>
		<th><?php echo __('Last Change'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($envoice['Product'] as $product): ?>
		<tr>
			<td><?php echo $product['id']; ?></td>
			<td><?php echo $product['id_llc']; ?></td>
			<td><?php echo $product['name']; ?></td>
			<td><?php echo $product['description']; ?></td>
			<td><?php echo $product['long_description']; ?></td>
			<td><?php echo $product['notes']; ?></td>
			<td><?php echo $product['category_id']; ?></td>
			<td><?php echo $product['garantia_id']; ?></td>
			<td><?php echo $product['img']; ?></td>
			<td><?php echo $product['img2']; ?></td>
			<td><?php echo $product['img3']; ?></td>
			<td><?php echo $product['img4']; ?></td>
			<td><?php echo $product['img5']; ?></td>
			<td><?php echo $product['manual_1']; ?></td>
			<td><?php echo $product['manual_2']; ?></td>
			<td><?php echo $product['manual_3']; ?></td>
			<td><?php echo $product['url_video']; ?></td>
			<td><?php echo $product['part_number']; ?></td>
			<td><?php echo $product['list_price_usd']; ?></td>
			<td><?php echo $product['currency']; ?></td>
			<td><?php echo $product['purchase_price_usd']; ?></td>
			<td><?php echo $product['aditional_usd']; ?></td>
			<td><?php echo $product['purchase_price_wo']; ?></td>
			<td><?php echo $product['purchase_price_cop']; ?></td>
			<td><?php echo $product['aditional_cop']; ?></td>
			<td><?php echo $product['brand']; ?></td>
			<td><?php echo $product['group_margen']; ?></td>
			<td><?php echo $product['brand_id']; ?></td>
			<td><?php echo $product['deleted']; ?></td>
			<td><?php echo $product['link']; ?></td>
			<td><?php echo $product['state']; ?></td>
			<td><?php echo $product['type']; ?></td>
			<td><?php echo $product['normal']; ?></td>
			<td><?php echo $product['user_id']; ?></td>
			<td><?php echo $product['quantity']; ?></td>
			<td><?php echo $product['quantity_bog']; ?></td>
			<td><?php echo $product['quantity_stm']; ?></td>
			<td><?php echo $product['quantity_stb']; ?></td>
			<td><?php echo $product['quantity_back']; ?></td>
			<td><?php echo $product['stock']; ?></td>
			<td><?php echo $product['min_stock']; ?></td>
			<td><?php echo $product['max_cost']; ?></td>
			<td><?php echo $product['reorder']; ?></td>
			<td><?php echo $product['last_change']; ?></td>
			<td><?php echo $product['created']; ?></td>
			<td><?php echo $product['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'products', 'action' => 'view', $product['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'products', 'action' => 'edit', $product['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'products', 'action' => 'delete', $product['id']), array('confirm' => __('Are you sure you want to delete # %s?', $product['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
