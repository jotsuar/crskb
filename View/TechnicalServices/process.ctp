<div class="col-md-12 p-0">
	<div class="blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Órdenes de Servicio Técnico Finalizadas</h2>
			</div>
			<div class="col-md-6 text-right">
				<div class="float-right input-group stylish-input-group w-50">
					<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'add')) ?>" class="crearclientej">
						<i class="fa fa-plus-square vtc"></i> <span>Crear orden de servicio</span>
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
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("flujo_id", [ "label" => "ID de flujo","id" => "flujo_id", "placeholder" => "Ingrese el ID de flujo" , "value" => isset($q) ? $q["flujo_id"] : "","class" => "form-control", "type"=>"text"]) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<?php echo $this->Form->input("fechas", [ "label" => "Filtrar por fecha de ingreso","id" => "fechas", "options" => ["1"=>"SI","0"=>"NO"] , "value" => isset($q) ? $q["fechas"] : "","class" => "form-control", "empty" => "Seleccionar opción" ]) ?>
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
						<input type="submit" class="btn btn-block btn-warning mt-4" name="generate_excel" value="Exportar Excel">
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
								<div class="contentback1">
										<?php echo $this->Html->link('ÓRDENES SIN DIAGNÓSTICO', array('action' => 'index'),array('class' => '')); ?>
										<span class="numorden"><?php echo $this->Utilities->count_services_false(); ?></span>
								</div>
							</div>
							<div class="col-md-4 back2 px-0">
								<div class="contentback2">
									<?php echo $this->Html->link('ÓRDENES EN PROCESO', array('action' => 'technical'),array('class' => '')); ?>
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
		<div class="contenttableresponsive">
			<table id="tablaPendientes" class="myTableTechnicalServices table tablaPendientes table-striped table-bordered w-100">
				<thead>
					<tr>
						<th>Asesor</th>
						<th class="noShow">Código</th>
						<th>Cliente</th>
						<th class="noShow">F. de Ingreso</th>
						<th class="noShow">F. diagnóstico</th>
						<th class="noShow">F. límite</th>
						<th class="noShow">Id Flujo</th>
						<th>Estado Flujo</th>
						<th class="noShow">Cotización</th>
						<th class="noShow">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($technicalServices as $value): ?>
						<tr>
							<td>
								<?php echo $value["User"]["name"] ?>
							</td>
							<td><b><?php echo $value['TechnicalService']['code'] ?>&nbsp;</b></td>
							<td class="nameuppercase"><?php echo $this->Text->truncate($this->Utilities->name_client_contact_services($value['TechnicalService']['id']), 60,array('ellipsis' => '...','exact' => false)); ?></td>
							<td><?php echo $this->Utilities->date_castellano($value['TechnicalService']['created']); ?>&nbsp;</td>
							<td><?php echo $this->Utilities->date_castellano($value['TechnicalService']['date_end']); ?>&nbsp;</td>
							<td><?php echo $this->Utilities->date_castellano($value['TechnicalService']['deadline']); ?>&nbsp;</td>
							<td>
								<?php if ($value['TechnicalService']['prospective_users_id'] != 0){ ?>
									<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action' => 'flujos?q='.$value['TechnicalService']['prospective_users_id'])) ?>">
										<?php echo $value['TechnicalService']['prospective_users_id'] ?>
									</a>
								<?php } else { ?>
									<i class="fa fa-minus"></i>
								<?php } ?>
							</td>
							<td>
								<?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?>
							</td>
							<td class="nameuppercase">
								<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['TechnicalService']['prospective_users_id']))) { ?>
									<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['TechnicalService']['prospective_users_id'])) ?>">
										<?php echo $this->Utilities->find_name_document_quotation_send($value['TechnicalService']['prospective_users_id']) ?>
									</a>
								<?php } else { ?>
									<?php if ($value['TechnicalService']['prospective_users_id'] != 0): ?>										
										<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($this->Utilities->find_id_document_quotation_send($value['TechnicalService']['prospective_users_id'])))) ?>">
											<?php echo $this->Utilities->find_name_document_quotation_send($value['TechnicalService']['prospective_users_id']) ?>
										</a>
									<?php else: ?>
										No hay información
									<?php endif ?>
								<?php } ?>
							</td>
							<td class="actions">
								<a href="<?php echo $this->Html->url(["controller"=>"binnacles","action"=>"index",$value['TechnicalService']['id'] ]) ?>" class="listBinnacle" data-toggle="tooltip" title="Listar bitácora" ><i class="fa fa-list"></i> </a>
								<a href="<?php echo $this->Html->url(["action"=>"sendMessage",$value['TechnicalService']['id'] ]) ?>" class="sendMessageCustomer" data-toggle="tooltip" title="Enviar mensaje de demora" ><i class="fa fa-info-circle"></i> </a>
								<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($value['TechnicalService']['id']))) ?>" data-toggle="tooltip" title="Ver servicio"><i class="fa fa-fw fa-eye"></i>
					            </a>
					            <a href="<?php echo $this->Html->url(array('action' => 'report', $this->Utilities->encryptString($value['TechnicalService']['id']))) ?>" data-toggle="tooltip" title="Informe técnico"><i class="fa fa-book"></i>
					            </a>
					            <?php if ($value['TechnicalService']['prospective_users_id'] == 0): ?>
					            	<a href="#" class="btn_servicio_flujo" data-uid="<?php echo $value['TechnicalService']['id'] ?>" data-toggle="tooltip" title="Generar Flujo">
					            		<i class="fa fa-paper-plane"></i>
					           		</a>
					            <?php endif ?>
					        <?php if (!empty($value["TechnicalService"]["document"])): ?>
					        	<a href="<?php echo $this->Html->url("/files/servicios_tecnicos/".$value["TechnicalService"]["document"]) ?>" class="" data-toggle="tooltip" target="_blank" title="Ver documento firmado por el cliente">
											<i class="fa fa-upload vtc"></i>
										</a>
					        <?php endif ?>
					        <?php if (AuthComponent::user("role") == 'Gerente General' || AuthComponent::user("email") == "ventas2@almacendelpintor.com" ): ?>
					        	<a href="<?php echo $this->Html->url(array('action' => 'terminate', $this->Utilities->encryptString($value['TechnicalService']['id']))) ?>" data-toggle="tooltip" title="Terminar servicio" class="changeState"><i class="fa fa-fw fa-check"></i>
					            </a>
					        <?php endif ?>
							</td>
						</tr>
					<?php endforeach ?>
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
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),							array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/technicalServices/technical.js?".rand(),			array('block' => 'AppScript'));
?>


<?php echo $this->element("picker"); ?>

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

<div class="modal fade" id="modalEnvio" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">
        	Enviar mensaje de demora al cliente
        </h5>
      </div>
      <div class="modal-body" id="cuerpoEnvioSend">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<?php 
    $this->start('AppScript'); ?>


<script>
	$('#tablaPendientes2 thead tr')
		        .clone(true)
		        .addClass('filters')
		        .appendTo('#tablaPendientes thead');


    $('#tablaPendientes2').DataTable( {
    	'iDisplayLength': 10,
    	"lengthMenu": [ [5,10,20,50, 100, -1], [5,10,20,50, 100, "Todos"] ],
    	"ordering": true,
    	paging: true,
    	"language": {"url": "<?php echo Router::url("/",true) ?>Spanish.json",},
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                if (column[0] == 0 || column[0] == 2 || column[0] == 6) {

                	var select = $('<select class="buscaSelect select_'+column[0]+'"><option value="">Seleccionar</option></select>')
	                    .appendTo( $( column.header()).empty() )
	                    .on( 'change', function () {
	                        var val = $.fn.dataTable.util.escapeRegex(
	                            $(this).val()
	                        );
	                        console.log(val);
	                        column
	                            .search( val ? '^'+val+'$' : '', true, false )
	                            .draw();
	                    } );
	                column.data().unique().sort().each( function ( d, j ) {
	                    select.append( '<option value="'+d+'">'+d+'</option>' )
	                } );
                } 		                
            } );
        	$(".filters .borra").html("");
            $(".buscaSelect").select2();
            $(".filters .noShow").html("");
        }
    } );



</script>

<?php
    $this->end();
 ?>
