<div class="accounts view">
<h2><?php echo __('Account'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($account['Account']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Send'); ?></dt>
		<dd>
			<?php echo h($account['Account']['date_send']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Initial Value'); ?></dt>
		<dd>
			<?php echo h($account['Account']['initial_value']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($account['User']['name'], array('controller' => 'users', 'action' => 'view', $account['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Gest'); ?></dt>
		<dd>
			<?php echo h($account['Account']['date_gest']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Deadline'); ?></dt>
		<dd>
			<?php echo h($account['Account']['date_deadline']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Payment'); ?></dt>
		<dd>
			<?php echo h($account['Account']['date_payment']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Value Payment'); ?></dt>
		<dd>
			<?php echo h($account['Account']['value_payment']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Document'); ?></dt>
		<dd>
			<?php echo h($account['Account']['document']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Notes'); ?></dt>
		<dd>
			<?php echo h($account['Account']['notes']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($account['Account']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($account['Account']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($account['Account']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Account'), array('action' => 'edit', $account['Account']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Account'), array('action' => 'delete', $account['Account']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $account['Account']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Accounts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Receipts'), array('controller' => 'receipts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Receipt'), array('controller' => 'receipts', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Receipts'); ?></h3>
	<?php if (!empty($account['Receipt'])): ?>
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
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($account['Receipt'] as $receipt): ?>
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
