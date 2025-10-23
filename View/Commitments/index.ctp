<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-secondary big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white	 bannerbig" >Módulo de gestión de compromisos</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h1 class="nameview">Gestión de mis compromisos </h1>
				<span class="subname">Gestión general de mis compromisos</span>
				<br>
				<a href="<?php echo $this->Html->url(["action" => "add", is_null($gestion) ? "" : $gestion ]) ?>" class="btn btn-info">
					<i class="fa fa-plus vtc"></i> Crear compromiso
				</a>
				<?php if (isset($sources)): ?>
					<a href="<?php echo $this->Html->url(["action" => "index", is_null($gestion) ? "" : $gestion ]) ?>" class="btn btn-success">
						<i class="fa fa-calendar vtc"></i> Ver listado
					</a>
				<?php else: ?>
					<a href="<?php echo $this->Html->url(["action" => "index", is_null($gestion) ? "" : $gestion, "?" => ["calendar" => time() ] ]) ?>" class="btn btn-success">
						<i class="fa fa-calendar vtc"></i> Ver calendario de pendientes
					</a>
				<?php endif ?>
				
			</div>
			<div class="col-md-6">
				<?php if (AuthComponent::user("role") == "Gerente General"): ?>
					<ul class="subpagos-box2 row">
						<li class="col-md-4 border-right border-white m-0 <?php echo is_null($gestion) ? "activesub" : "" ?>">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index')) ?>"> Gestionar mis compromisos</a>
						</li>	
						
						<li class="col-md-4 border-right border-white m-0 ">
							<a href="<?php echo $this->Html->url(array('controller'=>'cats','action'=>'index')) ?>">Gestionar mis categorías</a>
						</li>
						<li class=" border-right border-white col-md-4 m-0 <?php echo !is_null($gestion) ? "activesub" : "" ?>">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index',md5(AuthComponent::user("id")))) ?>">Compromisos de asesores</a>
						</li>					
					</ul>
				<?php else: ?>
					<ul class="subpagos-box row">
						<li class="border border-right border-white col-md-6 m-0 activesub">
							<a href="<?php echo $this->Html->url(array('controller'=>'commitments','action'=>'index')) ?>">Gestionar mis compromisos</a>
						</li>
						<li class="border border-right border-white col-md-6 m-0">
							<a href="<?php echo $this->Html->url(array('controller'=>'cats','action'=>'index')) ?>">Gestionar mis categorías</a>
						</li>
						
					</ul>
				<?php endif ?>
				
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<?php if (!isset($sources)): ?>
			<div class="table-responsive">
				<table cellpadding="0" cellspacing="0" class="table table-hovered">
					<thead>
						<tr>
								<th><?php echo $this->Paginator->sort('name','Nombre'); ?></th>
								<th><?php echo $this->Paginator->sort('description','Descripción'); ?></th>
								<th><?php echo $this->Paginator->sort('cat_id','Categoría'); ?></th>
								<th><?php echo $this->Paginator->sort('deadline','Vencimiento'); ?></th>
								<th><?php echo $this->Paginator->sort('date_end','Fecha terminado'); ?></th>
								<th><?php echo $this->Paginator->sort('user_id','Usuario asignado'); ?></th>
								<?php if (!is_null($gestion)): ?>
									<th><?php echo $this->Paginator->sort('assiged_by','Asigó'); ?></th>
								<?php endif ?>
								<!-- <th><?php echo $this->Paginator->sort('prospective_user_id','Flujo al que pertenece'); ?></th> -->
								<th><?php echo $this->Paginator->sort('state','Estado'); ?></th>
								<th><?php echo $this->Paginator->sort('created','Fecha de creación'); ?></th>
								<th class="actions"><?php echo __('Acciones'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($commitments as $commitment): ?>
						<tr>
							<td><?php echo h($commitment['Commitment']['name']); ?>&nbsp;</td>
							<td><?php echo h($commitment['Commitment']['description']); ?>&nbsp;</td>
							<td>
								<?php echo $commitment['Cat']['name']; ?>
							</td>
							<td><?php echo h($commitment['Commitment']['deadline']); ?>&nbsp;</td>
							<td><?php echo h($commitment['Commitment']['date_end']); ?>&nbsp;</td>
							<td>
								<?php echo $commitment['User']['name']; ?>
							</td>
							<?php if (!is_null($gestion)): ?>
								
								<td><?php echo $this->Utilities->find_name_adviser($commitment['Commitment']['assiged_by']); ?>&nbsp;</td>
							<?php endif ?>
							<!-- <td>
								<?php echo $commitment['ProspectiveUser']['id']; ?>
							</td> -->
							<td>
								<?php 
								switch ($commitment['Commitment']['state']) {
									case '1':
										echo "Activo sin completar";
										break;
									case '0':
										echo "Vencido";
										break;
									case '2':
										echo "Completado";
										break;
								}
							?>&nbsp;</td>
							<td><?php echo h($commitment['Commitment']['created']); ?>&nbsp;</td>
							<td class="actions">
								<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($commitment['Commitment']['id']))) ?>" data-toggle="tooltip" title="Ver compromiso"><i class="fa fa-fw fa-eye"></i>
							    </a>
							    <?php if ( is_null($gestion) && ( $commitment["Commitment"]["assiged_by"] == AuthComponent::user("id") || AuthComponent::user("role") == "Gerente General" ) && $commitment['Commitment']['state'] != 2 ): ?>
							    	
								    <a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($commitment['Commitment']['id']) )) ?>" data-toggle="tooltip" title="Editar compromiso">
								    	<i class="fa fa-fw fa-pencil"></i>
								    </a>
								<?php else: ?>
									<?php if ($commitment['Commitment']['state'] != 2): ?>
										
										<a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($commitment['Commitment']['id']),md5(AuthComponent::user("id")) )) ?>" data-toggle="tooltip" title="Editar compromiso">
									    	<i class="fa fa-fw fa-pencil"></i>
									    </a>
									<?php endif ?>
							    <?php endif ?>
							    <?php if (in_array($commitment["Commitment"]["state"], [0,1] )): ?>
							    	<a href="<?php echo $this->Html->url(["action" => "change_commitment", $this->Utilities->encryptString($commitment['Commitment']['id']), is_null($gestion) ? "" : $gestion ]) ?>" title="Marcar compromiso como completo" data-toggle="tooltip" class="changeState">
									<i class="fa fa-fw fa-check"></i>
								</a>
							    <?php endif ?>
							    
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
		<?php else: ?>
			<div id='calendarView'></div>
		<?php endif ?>
	</div>

</div>


<?php if (isset($sources)): ?>

	<?php $this->start('AppScript'); ?> ?>
		<script>
			var SOURCES = <?php echo json_encode($sources) ?>;
			var EVENTS = <?php echo json_encode($commitments) ?>;
		</script>
	<?php $this->end(); ?> ?>

	<?php
	echo $this->Html->css(array('lib/fullcalendar.min.css','https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@3.10.4/dist/scheduler.css'),												array('block' => 'AppCss'));
	echo $this->Html->script(array('lib/full_calendar/moment.min.js','lib/full_calendar/jquery-3.3.1.min.js?'.rand()),	array('block' => 'jqueryApp'));
	echo $this->Html->script(array('lib/full_calendar/fullcalendar.min.js','lib/full_calendar/scheduler.js','controller/commitments/callendar.js?'.rand()),		array('block' => 'AppScript'));
?>
	

<?php else: ?>

	<?php 
		echo $this->element("jquery"); 
		
	?>
<?php endif ?>