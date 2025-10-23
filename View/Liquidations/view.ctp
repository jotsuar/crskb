<div class="liquidations view">
<h2><?php echo __('Liquidation'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($liquidation['Liquidation']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor Recaudo'); ?></dt>
		<dd>
			<?php echo h($liquidation['Liquidation']['valor_recaudo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor Tiempo'); ?></dt>
		<dd>
			<?php echo h($liquidation['Liquidation']['valor_tiempo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor Efectividad'); ?></dt>
		<dd>
			<?php echo h($liquidation['Liquidation']['valor_efectividad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor Bono'); ?></dt>
		<dd>
			<?php echo h($liquidation['Liquidation']['valor_bono']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor A Pagar'); ?></dt>
		<dd>
			<?php echo h($liquidation['Liquidation']['valor_a_pagar']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Total Recaudado'); ?></dt>
		<dd>
			<?php echo h($liquidation['Liquidation']['total_recaudado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Total Ventas'); ?></dt>
		<dd>
			<?php echo h($liquidation['Liquidation']['total_ventas']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($liquidation['Liquidation']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($liquidation['Liquidation']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($liquidation['Liquidation']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Liquidation'), array('action' => 'edit', $liquidation['Liquidation']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Liquidation'), array('action' => 'delete', $liquidation['Liquidation']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $liquidation['Liquidation']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Liquidations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Liquidation'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Receipts'), array('controller' => 'receipts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Receipt'), array('controller' => 'receipts', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Receipts'); ?></h3>
	<?php if (!empty($liquidation['Receipt'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Prospective User Id'); ?></th>
		<th><?php echo __('Salesinvoice Id'); ?></th>
		<th><?php echo __('Account Id'); ?></th>
		<th><?php echo __('Code'); ?></th>
		<th><?php echo __('Total'); ?></th>
		<th><?php echo __('Total Iva'); ?></th>
		<th><?php echo __('Date Receipt'); ?></th>
		<th><?php echo __('Retefuente'); ?></th>
		<th><?php echo __('Reteiva'); ?></th>
		<th><?php echo __('Otras'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('User Liquidated'); ?></th>
		<th><?php echo __('Liquidation Id'); ?></th>
		<th><?php echo __('Percent Value'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($liquidation['Receipt'] as $receipt): ?>
		<tr>
			<td><?php echo $receipt['id']; ?></td>
			<td><?php echo $receipt['prospective_user_id']; ?></td>
			<td><?php echo $receipt['salesinvoice_id']; ?></td>
			<td><?php echo $receipt['account_id']; ?></td>
			<td><?php echo $receipt['code']; ?></td>
			<td><?php echo $receipt['total']; ?></td>
			<td><?php echo $receipt['total_iva']; ?></td>
			<td><?php echo $receipt['date_receipt']; ?></td>
			<td><?php echo $receipt['retefuente']; ?></td>
			<td><?php echo $receipt['reteiva']; ?></td>
			<td><?php echo $receipt['otras']; ?></td>
			<td><?php echo $receipt['state']; ?></td>
			<td><?php echo $receipt['user_id']; ?></td>
			<td><?php echo $receipt['user_liquidated']; ?></td>
			<td><?php echo $receipt['liquidation_id']; ?></td>
			<td><?php echo $receipt['percent_value']; ?></td>
			<td><?php echo $receipt['created']; ?></td>
			<td><?php echo $receipt['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'receipts', 'action' => 'view', $receipt['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'receipts', 'action' => 'edit', $receipt['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'receipts', 'action' => 'delete', $receipt['id']), array('confirm' => __('Are you sure you want to delete # %s?', $receipt['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Receipt'), array('controller' => 'receipts', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
