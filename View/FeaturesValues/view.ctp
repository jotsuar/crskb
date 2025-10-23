<div class="featuresValues view">
<h2><?php echo __('Features Value'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($featuresValue['FeaturesValue']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Feature'); ?></dt>
		<dd>
			<?php echo $this->Html->link($featuresValue['Feature']['name'], array('controller' => 'features', 'action' => 'view', $featuresValue['Feature']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($featuresValue['FeaturesValue']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Link'); ?></dt>
		<dd>
			<?php echo h($featuresValue['FeaturesValue']['link']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($featuresValue['FeaturesValue']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($featuresValue['FeaturesValue']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($featuresValue['FeaturesValue']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Features Value'), array('action' => 'edit', $featuresValue['FeaturesValue']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Features Value'), array('action' => 'delete', $featuresValue['FeaturesValue']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $featuresValue['FeaturesValue']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Features Values'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Features Value'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Features'), array('controller' => 'features', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Feature'), array('controller' => 'features', 'action' => 'add')); ?> </li>
	</ul>
</div>
