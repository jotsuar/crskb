<div class="col-md-12 spacebtn20">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite headerinformelineal mb-3">
		<div class="row">
			<div class="col-md-12">
				<h1 class="nameview">INFORMES GENERADOS DE GESTIÓN DE FLUJOS <a href="<?php echo $this->Html->url(["controller"=>"prospective_users","action"=>'gestion_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("-1 day")), "end" => date("Y-m-d")  ) ]) ?>" class="btn btn-info">
					Generar informe nuevo <i class="fa fa-plus vtc"></i>
				</a> </h1>
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-bordered">
				<thead>
				<tr>
						<th><?php echo $this->Paginator->sort('user_id','Usuario que realizó el informe'); ?></th>
						<th><?php echo $this->Paginator->sort('rango','Rango del informe'); ?></th>
						<th><?php echo $this->Paginator->sort('created','Fecha de creación'); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($managements as $management): ?>
				<tr>
					<td>
						<?php echo $management['User']['name']; ?>
					</td>
					<td><?php echo h($management['Management']['rango']); ?>&nbsp;</td>
					<td><?php echo h($management['Management']['created']); ?>&nbsp;</td>
					<td class="actions">
						<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($management['Management']['id']))) ?>" data-toggle="tooltip" title="Ver Informe"><i class="fa fa-fw fa-eye"></i>
					            </a>
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


<?php 
	
echo $this->Html->script(array('//code.jquery.com/jquery-1.9.1.js'),array('block' => 'jqueryApp'));

?>