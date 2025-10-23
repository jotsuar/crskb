<div class="col-md-12 spacebtn20 p-0">
	<?php if (is_null($report)): ?>		
		<div class=" widget-panel widget-style-2 bg-cafe big">
	         <i class="fa fa-1x flaticon-report-1"></i>
	        <h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
		</div>
	<?php endif ?>
	<div class="blockwhite spacebtn20 <?php echo !is_null($report) ? "p-2" : "" ?>">
		<div class="row">
			<div class="col-md-8">
				<h1 class="nameview">STATUS DE ATENCIÓN DE FLUJOS GENERAL DE LA EMPRESA</h1>
				<span class="subname">Mostrar flujos con retraso y el numero de horas aproximadas.</span>
				
			</div>
			<div class="col-md-4 pull-right text-right">
				<div class="rangofechas">
					<span>Seleccionar rango de fechas:</span>
					<div class="form-group">
						<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="w-50">
						<a id="btn_buscar_datos_empresa" class="btn-primary btn">Buscar</a>
					</div>

					<div style="display: none">
						<div class="form-group">
						  	<span>Desde</span>
						</div>
						<div class="form-group">
							<input type="date" value="<?php echo $fechaInicioReporte; ?>" class="form-control" id="input_date_inicio_empresa" style="display: none">
						</div>
					</div>
					<div style="display: none">
						<div class="form-group">
						  	<span>Hasta</span>
						</div>
						<div class="form-group">
							<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin_empresa" style="display: none">
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<?php if (is_null($report)): ?>
		<div class="blockwhite spacebtn20">
			<ul class="subinforme">
				<li class="activesub">
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_adviser',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_adviser'),true ) ?>" class="informeWeb">Informe de atención de flujos</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_management',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_management'),true ) ?>" class="informeWeb">Informe de Gestión Comercial</a>
				</li>				
			</ul>
		</div>
	<?php endif ?>
</div>

<div class="col-md-12 spacebtn20 p-0">
	<div class="blockwhite div_info_empresa <?php echo !is_null($report) ? "p-0" : "" ?>"></div>
</div>

<!-- 
<div class="col-md-12 spacebtn20 p-0">
	<div class="users index blockwhite">
		<h2 class="titleviewer">INFORME DE ATENCIÓN DE FLUJOS</h2>
		<hr>
		<div class="row mt-3">
			<div class="col-md-11 spacebtn20">
				<h2 class="nameview ">Por favor selecciona un usuario para mostrar los flujos que le han asignado</h2>
				<?php 
					echo $this->Form->input('user',array('label' => false, 'id' => 'usuario', 'options' => $usuarios));
				?>
			</div>
			<div class="col-md-1">
				<br>
				<button class="btn" id="btn_find_adviser">Buscar</button>
			</div>
		</div>
	</div>
</div>

<div class="col-md-12 p-0">
	<div class="resultadoFiltro">
		
		<div class="blockwhite cuadroInformacion">
			<h2 class="titleviewer nameget">
				<?php // echo $title ?>
			</h2>
			<hr>
			<div class="contenttableresponsive table-responsive">
				<table class="table-hover table-striped table_resultados hidden">
					<thead class="stylebold">
						<tr>
				        	<td class="cliente">Cliente</td>
				        	<td class="requerimiento">Requerimiento</td>
							<td>Asesor</td>
							<td>Etapa</td>
							<td>Fecha</td>
							<?php // if (isset($this->request->query['find'])){ ?>
								<?php // if ($this->request->query['find'] == 'flujos_demorados'): ?>
									<td>Fecha limite (contactar)</td>
									<td>Fecha contactado</td>
									<td>Fecha limite (cotizar)</td>
									<td>Fecha cotizado</td>
								<?php // endif ?>
							<?php // } ?>
						</tr>
					</thead>
					<tbody>
					<?php // if (empty($datos)): ?>
						<tr>
							<td colspan="<?php // echo isset($this->request->query['find']) && $this->request->query['find'] == 'flujos_demorados' ? 9 : 5 ?>">
								Actualmente no hay datos para mostrar
							</td>
						</tr>
					<?php // else: ?>
						<?php // foreach ($datos as $value): ?>
							<tr>
								<td class="uppercase">
						          <?php // echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']), 45,array('ellipsis' => '...','exact' => false)); ?>
						        </td>
								<td class="uppercase">
						          <?php // echo $this->Text->truncate($this->Utilities->find_reason_prospective($value['ProspectiveUser']['id']), 25,array('ellipsis' => '...','exact' => false)); ?>
						        </td>			        
								<td><?php // echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']); ?></td>
								<td><?php // echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?></td>
								<td><?php // echo $this->Utilities->date_castellano2($value['ProspectiveUser']['created']) ?></td>
								<?php // if (isset($this->request->query['find'])){ ?>
									<?php // if ($this->request->query['find'] == 'flujos_demorados'): ?>
										<td><?php // echo $this->Utilities->date_castellano2($value['AtentionTime']['limit_contactado_date'].' '.$value['AtentionTime']['limit_contactado_time']) ?>
										</td>
										<td>
											<?php // echo $this->Utilities->date_castellano2($value['AtentionTime']['contactado_date'].' '.$value['AtentionTime']['contactado_time']) ?>
											<?php // echo $this->Utilities->compararTiempoLimiteAtendidoFlujo($value['AtentionTime']['limit_contactado_date'].' '.$value['AtentionTime']['limit_contactado_time'],$value['AtentionTime']['contactado_date'].' '.$value['AtentionTime']['contactado_time']); ?>
										</td>
										<td><?php // echo $this->Utilities->date_castellano2($value['AtentionTime']['limit_cotizado_date'].' '.$value['AtentionTime']['limit_cotizado_time']) ?>
										</td>
										<td>
											<?php // echo $this->Utilities->date_castellano2($value['AtentionTime']['cotizado_date'].' '.$value['AtentionTime']['cotizado_time']) ?>
											<?php // echo $this->Utilities->compararTiempoLimiteAtendidoFlujo($value['AtentionTime']['limit_cotizado_date'].' '.$value['AtentionTime']['limit_cotizado_time'],$value['AtentionTime']['cotizado_date'].' '.$value['AtentionTime']['cotizado_time']); ?>
										</td>
									<?php // endif ?>
								<?php // } ?>
							</tr>
						<?php // endforeach ?>
					<?php // endif ?>
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

/ -->

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),														array('block' => 'jqueryApp'));
	echo $this->Html->script(array('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js?'.rand()),		array('block' => 'AppScript'));
	echo $this->Html->script("controller/prospectiveUsers/report_adviser.js?".rand(),									array('block' => 'AppScript'));
?>

<?php echo $this->element("picker"); ?>