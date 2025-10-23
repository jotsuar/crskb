<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-verde big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Tesorería</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview">LIQUIDACIÓN DE COMISIONES 
					<?php if (AuthComponent::user("role") == "Gerente General"): ?>
						
					<a href="<?php echo $this->Html->url(["controller"=>"prospective_users","action"=>"liquidation"]) ?>" class="btn btn-warning float-right">Nueva liquidación <i class="vtc fa fa-plus"></i></a>
					<?php endif ?>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="col-md-12">
	<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100')); ?>
	<?php if (!in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])) {
		$usuarios = [AuthComponent::user("id") => AuthComponent::user("name")];
	} ?>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-4">
				<h1 class="nameview2">LIQUIDACIÓN DE COMISIONES</h1>
				<span class="subname">Loquidación de comisiones teniendo en cuenta las facturas y los recibos de caja</span>
			</div>
			<div class="col-md-8">
			</div>
		</div>
	</div>
	<div class="blockwhite">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-hovered">
				<thead>
					<tr>
							<!-- <th><?php echo $this->Paginator->sort('valor_a_pagar','Valor a pagar'); ?></th> -->
							<th><?php echo $this->Paginator->sort('date_ini','Fecha inicio liquidación'); ?></th>
							<th><?php echo $this->Paginator->sort('date_end','Fecha fin liquidación'); ?></th>
							<th><?php echo $this->Paginator->sort('Usuario'); ?></th>
							<th><?php echo $this->Paginator->sort('created','Fecha liquidado'); ?></th>
							<th class="actions"><?php echo __('Acciones'); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($liquidations as $liquidation): ?>
					<tr>
						<!-- <td><?php echo number_format($liquidation['Liquidation']['valor_a_pagar']); ?>&nbsp;</td> -->
						<td><?php echo h($liquidation['Liquidation']['date_ini']); ?>&nbsp;</td>
						<td><?php echo h($liquidation['Liquidation']['date_end']); ?>&nbsp;</td>
						<td><?php echo h($liquidation['User']['name']); ?>&nbsp;</td>
						<td><?php echo h($liquidation['Liquidation']['created']); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link(__('Ver detalle'), array("controller"=>'prospective_users','action' => 'view_liquidation', $this->Utilities->encryptString($liquidation['Liquidation']['id']) , ),[ "class" => 'btn btn-info']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="row numberpages">
			<?php
				echo $this->Paginator->first('<< ', array('class' => 'prev'), null);
				echo $this->Paginator->prev('< ', array(), null, array('class' => 'prev disabled'));
				echo $this->Paginator->counter(array('format' => '{:page} de {:pages}'));
				echo $this->Paginator->next(' >', array(), null, array('class' => 'next disabled'));
				echo $this->Paginator->last(' >>', array('class' => 'next'), null);
			?>
			<b> <?php echo $this->Paginator->counter(array('format' => '{:count} en total')); ?></b>
		</div>
	</div>
</div>





<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>
