<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-rojo big">
         <i class="fa fa-1x flaticon-settings-1"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Servicio Técnico</h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Órdenes en Proceso</h2>
			</div>
			<div class="col-md-6 text-right">
				<div class="input-group stylish-input-group">
					<a href="<?php echo $this->Html->url(array('controller'=>'aditionals','action'=>'index')) ?>" class="crearclientej">
						<i class="fa flaticon-settings vtc"></i> <span>Gestión de accesoros</span>
					</a>
					<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'add')) ?>" class="crearclientej">
						<i class="fa fa-plus-square vtc"></i> <span>Nuevo Ingreso</span>
					</a>
					<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'flujos')) ?>" class="crearclientej">
						<i class="fa fa-plus-square vtc"></i> <span>Flujos de Servicio</span>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<?php echo $this->Form->create(false,["type" => "get"]); ?>
			<div class="row">
				<div class="col-md-12">
					<h2 class="bg-azul mb-2 px-2 text-white w-25">
						Búsqueda inteligente <a href="javascript:void(0)" class="btn" id="muestra" data-mod="<?php echo $filter ? "1" : "0" ?>">
								<i class="fa fa-plus <?php echo $filter ? "d-none" : "" ?>" ></i>
								<i class="fa fa-minus <?php echo !$filter ? "d-none" : "" ?>"></i>
						</a>
					</h2>
				</div>
			</div>
			<div class="row" style="display:<?php echo !$filter ? "none" : "flex" ?>" id="principalBusqueda" >				
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("brand", [ "label" => "Marca","id" => "brand", "options" => $brands , "value" => isset($q) ? $q["brand"] : "", "empty" => "Seleccionar marca","class" => "form-control"  ]) ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("cliente", [ "label" => "Cliente","id" => "clientes", "options" => $clientes , "value" => isset($q) ? $q["cliente"] : "", "empty" => "Seleccionar cliente","class" => "form-control"  ]) ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("user_id", [ "label" => "Asesor asignado","id" => "asesor", "options" => $usuarios_asesores , "value" => isset($q) ? $q["user_id"] : "", "empty" => "Seleccionar asesor asignado" ,"class" => "form-control" ]) ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("equipment", [ "label" => "Nombre equipo","id" => "equipment", "placeholder" => "Buscar por nombre de equipo" , "value" => isset($q) ? $q["equipment"] : "","class" => "form-control" ]) ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("part_number", [ "label" => "N° Parte","id" => "part_number", "placeholder" => "Buscar por número de parte" , "value" => isset($q) ? $q["part_number"] : "","class" => "form-control" ]) ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("serial_number", [ "label" => "N° serie","id" => "serial_number", "placeholder" => "Buscar por número de serie" , "value" => isset($q) ? $q["serial_number"] : "","class" => "form-control" ]) ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("serial_garantia", [ "label" => "N° serie (Garantía)","id" => "serial_garantia", "placeholder" => "Buscar por número de serie para Garantía" , "value" => isset($q) ? $q["serial_garantia"] : "","class" => "form-control" ]) ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("flujo", [ "label" => "Generó flujo","id" => "flujo", "options" => ["1"=>"SI","0"=>"NO"] , "value" => isset($q) ? $q["flujo"] : "","class" => "form-control", "empty" => "Seleccionar opción" ]) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("fechas", [ "label" => "Filtrar por fechas","id" => "fechas", "options" => ["1"=>"SI","0"=>"NO"] , "value" => isset($q) ? $q["fechas"] : "","class" => "form-control", "empty" => "Seleccionar opción" ]) ?>
					</div>
					
				</div>
				<div class="col-md-3">
					<input type="date" value="<?php echo $fechaInicioReporte ?>" class="form-control" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
					<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
					<div class="form-group">
						<span>Seleccionar rango de fechas:</span>
					</div>
					<div class="form-group">
						<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("txt_buscador", [ "label" => "Código","id" => "txt_buscador", "placeholder" => "Buscar código" , "value" => isset($q) ? $q["txt_buscador"] : "","class" => "form-control" ]) ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<input type="submit" class="btn btn-block btn-success mt-4" value="Buscar">
					</div>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
	<div class="technicalServices index blockwhite">
		<div class="row">		
			<nav class="navbar navbar-expand-lg navbar-light bg-filter">
				<div class="collapse navbar-collapse spacebt" id="navbarTogglerDemo01">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-4 back1 px-0">
								<div class="contentback2">
									<?php echo $this->Html->link('ÓRDENES SIN DIAGNÓSTICO', array('action' => 'index'),array('class' => '')); ?>
									<span class="numorden"><?php echo $this->Utilities->count_services_false(); ?></span>
								</div>
							</div>
							<div class="col-md-4 back2 px-0">
								<div class="border-right contentback1">
									<?php echo $this->Html->link('ÓRDENES EN PROCESO', array('action' => 'process'),array('class' => '')); ?>
									<span class="numorden"><?php echo $this->Utilities->count_services_true(true); ?></span>
								</div>
							</div>
							<div class="col-md-4 back2 px-0">
								<div class="contentback1">
									<?php echo $this->Html->link('ÓRDENES FINALIZADAS', array('action' => 'technical'),array('class' => '')); ?>
									<span class="numorden"><?php echo $this->Utilities->count_services_true(); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</nav>
		</div>
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class="myTableTechnicalServices table-striped table-bordered">
				<thead>
					<tr>
						<th>Código</th>
						<th>Cliente</th>
						<th>Técnico encargado</th>
						<th>Asignar o cambiar técnico</th>
						<th><?php echo $this->Paginator->sort('TechnicalService.created', 'F. de Ingreso'); ?></th>
						<th><?php echo $this->Paginator->sort('TechnicalService.deadline', 'F. límite de entrega'); ?></th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($technicalServices as $value): ?>
						<tr>
							<td><b><?php echo $value['TechnicalService']['code'] ?>&nbsp;</b></td>
							<td class="nameuppercase"><?php echo $this->Text->truncate($this->Utilities->name_client_contact_services($value['TechnicalService']['id']), 60,array('ellipsis' => '...','exact' => false)); ?></td>
							<?php $encargado = $this->Utilities->servicio_tecnico_encargado($value['TechnicalService']['user_id']); ?>
							<td><?php echo $this->Utilities->find_name_adviser($value['TechnicalService']['user_id']) ?></td>
							<td class="asignartecnicotable">
								<?php echo $this->Form->input('asignado',array('label' => false, 'options' => $usuarios_asesores,'empty' => 'Asignar Técnico',"id" => "user_asignado_".$value['TechnicalService']['id'])); ?>
								<button class="btn_update_user" data-uid="<?php echo $value['TechnicalService']['id'] ?>"><i class="fa fa-refresh"></i></button>
							</td>
							<td><?php echo $this->Utilities->date_castellano($value['TechnicalService']['created']); ?>&nbsp;</td>
							<td><?php echo $this->Utilities->date_castellano($value['TechnicalService']['deadline']); ?>&nbsp;</td>
							<td class="actions">
								<a href="<?php echo $this->Html->url(["controller"=>"binnacles","action"=>"index",$value['TechnicalService']['id'] ]) ?>" class="listBinnacle" data-toggle="tooltip" title="Listar bitácora" ><i class="fa fa-list"></i> </a>
								<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($value['TechnicalService']['id']))) ?>" data-toggle="tooltip" title="Ver orden"><i class="fa fa-fw fa-eye"></i>
								</a>
								<a href="<?php echo $this->Html->url(array('action' => 'edit', $value['TechnicalService']['id'])) ?>" data-toggle="tooltip" title="Editar orden"><i class="fa fa-fw fa-pencil"></i>
								</a>
								<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'identificadores',$value['TechnicalService']['id'])) ?>" data-toggle="tooltip" title="Generar identificadores"><i class="fa fa-fw fa-clone"></i>
								</a>
								<?php if ($encargado != 'Sin asginar'): ?>

									<?php if (empty($value["TechnicalService"]["document"]) && empty($value["TechnicalService"]["firma_cliente"])): ?>
										<a href="<?php echo $this->Html->url(["action"=>"upload_document",$this->Utilities->encryptString($value['TechnicalService']['id'])]) ?>" class="btn btn-warning btn-sm cargaDocumento" data-toggle="tooltip" title="Cargar documento firmado por el cliente">
											<i class="fa fa-upload vtc"></i>
										</a>
									<?php else: ?>
										<?php if (!empty($value["TechnicalService"]["document"]) || !empty($value["TechnicalService"]["firma_cliente"])): ?>							


											<?php if (!is_null($value["TechnicalService"]["deadline"]) && $value["TechnicalService"]["deadline"] < date("Y-m-d") ): ?>
												<a href="javascript:void(0)" class="classInformeCliente btn btn-danger btn-xs" data-uid="<?php echo $value['TechnicalService']['id'] ?>"  data-toggle="tooltip" title="Informar Demora"><i class="fa fa-check"></i> Informar demora</a>
											<?php else: ?>
												<a href="javascript:void(0)" class="btn_finalizar" data-uid="<?php echo $value['TechnicalService']['id'] ?>"  data-toggle="tooltip" title="Generar diagnostico"><i class="fa fa-check"></i></a>
											<?php endif ?>

										<?php endif ?>
										<?php if (!empty($value["TechnicalService"]["document"])): ?>
											<a href="<?php echo $this->Html->url("/files/servicios_tecnicos/".$value["TechnicalService"]["document"]) ?>" class="" data-toggle="tooltip" target="_blank" title="Ver documento firmado por el cliente">
												<i class="fa fa-upload vtc"></i>
											</a>
										<?php endif ?>
									<?php endif ?>
								<?php endif ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
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
</div>

<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/technicalServices/index.js?".rand(),			array('block' => 'AppScript'));
?>

<?php echo $this->element("picker"); ?>

<!-- Modal -->
<div class="modal fade" id="modalListVitacora" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Listar bitácora de trabajo para este servicio</h5>
      </div>
      <div class="modal-body" id="cuerpoListBit">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDocumento" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Asociar documento firmado por el cliente</h5>
      </div>
      <div class="modal-body" id="cuerpoDocument">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>