<div class="col-md-12">
		<div class=" widget-panel widget-style-2 bg-cafe big">
	         <i class="fa fa-1x flaticon-report-1"></i>
	        <h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
		</div>
	<div class="blockwhite headerinformelineal spacebtn20">
		<div class="row">
			<div class="col-md-8">
				<h1 class="nameview">INFORME DE GESTIÓN DE NUEVOS CLIENTES</h1>
			</div>
			<div class="col-md-4 pull-right text-right">
				<div class="rangofechas">
				    <input type="date" value="<?php echo $fechaInicioReporte; ?>" class="" id="input_date_inicio" placeholder="Desde" style="display: none">
				    <input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="">
				    <input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="" id="input_date_fin" placeholder="Desde" style="display: none">
					<a class="btn-primary btn" id="btn_find_adviser">Filtrar Fechas</a>
				</div>
			</div>			
		</div>


	</div>
		<div class="blockwhite spacebtn20">
			<ul class="subinforme">
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  )) ) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos'),true ) ?>" class="informeWeb">Informe de prospectos</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_adviser',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_adviser'),true ) ?>" class="informeWeb">Informe de atención de flujos</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_management',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_management'),true ) ?>" class="informeWeb">Informe de Gestión Comercial</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_advisers',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_advisers'),true ) ?>" class="informeWeb">Informe de Asesores</a>
				</li>
				<!-- <li class="activesub">
					<a href="<?php //echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_customer_new',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" data-url = "<?php //echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_customer_new'),true ) ?>" class="informeWeb">Informe de gestión de nuevos clientes</a>
				</li> -->					
			</ul>
		</div>	

	<div class="blockwhite div_resultado"></div>
</div>
<br>
<div class="col-md-12">
	<div class="resultadoFiltro">
		<div class="blockwhite cuadroInformacion">
			<div class="contenttableresponsive">
				<table cellpadding="0" cellspacing="0" class="myTable table table-hover table_resultados table-inbox hidden">
					<thead class="stylebold">
						<tr>
				        	<td class="cliente">Cliente</td>
				        	<td class="requerimiento">Requerimiento</td>
							<td>Nombre asesor</td>
							<td>Etapa (Estado)</td>
							<td>Fecha de ingreso de requerimiento</td>
							<?php if (isset($this->request->query['find'])){ ?>
								<?php if ($this->request->query['find'] == 'flujos_demorados'): ?>
									<td>Fecha y hora limite para atender (contactado)</td>
									<td>Fecha y hora atendido (contactado)</td>
									<td>Fecha y hora limite para atender (cotizado)</td>
									<td>Fecha y hora atendido (cotizado)</td>
								<?php endif ?>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($datos as $value): ?>
						<tr>
							<td class="uppercase">
					          <?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']), 25,array('ellipsis' => '...','exact' => false)); ?>
					        </td>
							<td class="uppercase">
					          <?php echo $this->Text->truncate($this->Utilities->find_reason_prospective($value['ProspectiveUser']['id']), 25,array('ellipsis' => '...','exact' => false)); ?>
					        </td>			        
							<td><?php echo $this->Utilities->find_name_lastname_adviser($value['ProspectiveUser']['user_id']); ?></td>
							<td><?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?></td>
							<td><?php echo $this->Utilities->date_castellano($value['ProspectiveUser']['created']) ?></td>
							<?php if (isset($this->request->query['find'])){ ?>
								<?php if ($this->request->query['find'] == 'flujos_demorados'): ?>
									<td><?php echo $this->Utilities->date_castellano($value['AtentionTime']['limit_contactado_date'].' '.$value['AtentionTime']['limit_contactado_time']) ?>
									</td>
									<td>
										<?php echo $this->Utilities->date_castellano($value['AtentionTime']['contactado_date'].' '.$value['AtentionTime']['contactado_time']) ?>
										<?php echo $this->Utilities->compararTiempoLimiteAtendidoFlujo($value['AtentionTime']['limit_contactado_date'].' '.$value['AtentionTime']['limit_contactado_time'],$value['AtentionTime']['contactado_date'].' '.$value['AtentionTime']['contactado_time']); ?>
									</td>
									<td><?php echo $this->Utilities->date_castellano($value['AtentionTime']['limit_cotizado_date'].' '.$value['AtentionTime']['limit_cotizado_time']) ?>
									</td>
									<td>
										<?php echo $this->Utilities->date_castellano($value['AtentionTime']['cotizado_date'].' '.$value['AtentionTime']['cotizado_time']) ?>
										<?php echo $this->Utilities->compararTiempoLimiteAtendidoFlujo($value['AtentionTime']['limit_cotizado_date'].' '.$value['AtentionTime']['limit_cotizado_time'],$value['AtentionTime']['cotizado_date'].' '.$value['AtentionTime']['cotizado_time']); ?>
									</td>
								<?php endif ?>
							<?php } ?>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),								array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/report_customer_new.js?".rand(),		array('block' => 'AppScript'));
?>
<?php echo $this->element("picker"); ?>