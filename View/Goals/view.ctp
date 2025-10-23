<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-verde big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Tesorería </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Visualizar Metas</h2>
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="goals view">
			<dl>
				<dt><?php echo __('Año'); ?></dt>
				<dd>
					<?php echo h($goal['Goal']['year']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Usuario'); ?></dt>
				<dd>
					<?php echo $goal['User']['name']; ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Nombre WO'); ?></dt>
				<dd>
					<?php echo h($goal['Goal']['name']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Enero'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['01']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Febrero'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['02']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Marzo'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['03']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Abril'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['04']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Mayo'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['05']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Junio'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['06']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Julio'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['07']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Agosto'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['08']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Septiembre'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['09']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Octubre'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['10']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Noviembre'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['11']); ?>
					&nbsp;
				</dd>
				<dt><?php echo __('Diciembre'); ?></dt>
				<dd>
					<?php echo number_format($goal['Goal']['12']); ?>
					&nbsp;
				</dd>
			</dl>
		</div>
	</div>
</div>

<?php 
	
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));

?>