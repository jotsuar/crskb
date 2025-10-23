<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-rojo big">
         <i class="fa fa-1x flaticon-settings-1"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Servicio Técnico</h2>
	</div>
</div>

<div class="col-md-12">
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-8">
				<h1 class="nameview">
					INFORME DE VENTAS SERVICIO TÉCNICO <?php echo !is_null($totalVentasServicio) ? "Total vendido: $".number_format($totalVentasServicio,2,",",".") : "" ?>
					<?php if (!empty($totalByUser)): ?>
						<br>
						<?php foreach ($totalByUser as $key => $value): ?>
							<b><?php echo $users[$key] ?></b> : <?php echo number_format($value,2,",",".") ?> <br>
						<?php endforeach ?>
					<?php endif ?>
				</h1>
			</div>
			<div class="col-md-4 pull-right text-right">
				<div class="rangofechas">
					<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100')); ?>
						<span>Seleccionar rango de fechas:</span>
						<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="">
						
						
						<div style="display: none">
							<span>Desde</span>
							<input type="date" value="<?php echo $fechaInicioReporte ?>" class="form-control" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
						</div>

						<div style="display: none">
							<span>Hasta</span>
							<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
						</div>
						<button type="submit" class="btn btn-primary" id="btn_find_adviser">Buscar</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="blockwhite">
		<div class="table-responsive">
			<table class="table table-bordered table-striped <?php echo empty($sales) ? "" : "tablaPendientes" ?>  table-hovered w-100" id="tablaPendientes">
				<thead>
					<tr>						
						<th>Asesor</th>
						<th class="noShow">Flujo</th>
						<th class="noShow">Órden de servicio</th>
						<th class="noShow">Cliente</th>
						<th class="noShow">Nit o identificación</th>
						<th class="noShow">Código de factura</th>
						<th class="noShow">Valor factura</th>
						<th class="noShow">Fecha de factura</th>
						<th class="noShow">Acciones</th>
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($sales)): ?>
						<tr>
							<td colspan="9" class="text-center">
								<?php if ($filter): ?>
									<strong>No existen registros de facturación</strong>
								<?php else: ?>
									<h2 class="text-danger">! Para ver datos por favor realiza una búsqueda ¡</h2>									
								<?php endif ?>
							</td>
						</tr>
					<?php else: ?>
						<?php foreach ($sales as $key => $value): ?>
							<tr>
								<td>
									<?php echo $this->Utilities->find_name_adviser($value["ProspectiveUser"]["user_id"]) ?>
								</td>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable m-1 flujoModal" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $value["ProspectiveUser"]["id"] ?>
									</a>
								</td>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action' => 'view/'.$this->Utilities->encryptString($value["TechnicalService"][0]["id"]) )) ?>" class="idflujotable btn-sm m-1" target="_blank" data-uid="<?php echo $value['TechnicalService'][0]['id'] ?>"  data-type="<?php echo $value['TechnicalService'][0]['contacs_users_id'] ?>">
												<?php echo $value["TechnicalService"][0]["code"] ?>
									</a>
								</td>
								<td class="text-uppercase"><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?></td>
								<td><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["identification"] : $value["ContacsUser"]["ClientsLegal"]["nit"] ?></td>
								<td class="text-uppercase"><?php echo $value["ProspectiveUser"]["bill_code"] ?></td>
								<td> $ <?php echo number_format($value["ProspectiveUser"]["bill_value"],0,".",",") ?></td>
								<td><?php echo $this->Utilities->date_castellano($value["ProspectiveUser"]["bill_date"]) ?></td>
								<td>
									<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$value["ProspectiveUser"]["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary btn-sm <?php echo is_null($value["ProspectiveUser"]["bill_file"]) ? "mostradDatosFact" : "" ?>" data-id="KEB_<?php echo md5($value["ProspectiveUser"]["bill_code"]) ?>">
										Ver factura <i class="fa fa-file"></i>
									</a>
								</td>
							</tr>
							<?php if (!empty($value["facturas"])): ?>

								<?php foreach ($value["facturas"] as $keyFactura => $valueFactura): ?>
									<tr>
										<td>
											<?php echo $this->Utilities->find_name_adviser($value["ProspectiveUser"]["user_id"]) ?>
										</td>
										<td>
											<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable m-1 flujoModal" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
												<?php echo $value["ProspectiveUser"]["id"] ?>
											</a>
										</td>
										<td>
											<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action' => 'view/'.$this->Utilities->encryptString($value["TechnicalService"][0]["id"]) )) ?>" class="idflujotable btn-sm m-1" target="_blank" data-uid="<?php echo $value['TechnicalService'][0]['id'] ?>"  data-type="<?php echo $value['TechnicalService'][0]['contacs_users_id'] ?>">
												<?php echo $value["TechnicalService"][0]["code"] ?>
											</a>
										</td>
										<td class="text-uppercase"><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?></td>
										<td><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["identification"] : $value["ContacsUser"]["ClientsLegal"]["nit"] ?></td>
										<td class="text-uppercase"><?php echo $valueFactura["Salesinvoice"]["bill_code"] ?></td>
										<td> $ <?php echo number_format($valueFactura["Salesinvoice"]["bill_value"],0,".",",") ?></td>
										<td><?php echo $this->Utilities->date_castellano($valueFactura["Salesinvoice"]["bill_date"]) ?></td>
										<td>
											<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$valueFactura["Salesinvoice"]["bill_file"] ) ?>" target="blank" class="btn btn-sm btn-info btn-secondary <?php echo is_null($valueFactura["Salesinvoice"]["bill_file"]) ? "mostradDatosFact" : "" ?>" data-id="KEB_<?php echo md5($valueFactura["Salesinvoice"]["bill_code"]) ?>">
												Ver factura <i class="fa fa-file vtc"></i>
											</a>
										</td>
									</tr>
								<?php endforeach ?>
							<?php endif ?>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php if (!empty($sales)): ?>

	<?php foreach ($sales as $key => $sale): ?>

		<div id="KEB_<?php echo md5($sale["ProspectiveUser"]["bill_code"]) ?>" class="table-responsive" style="display: none;">
			<?php $factValue = (array) json_decode($sale["ProspectiveUser"]["bill_text"]); ?>
			<?php echo $this->element("vistaFacturaWo", ["factValue" => $factValue]); ?>
		</div>

		<?php if (!empty($sale["facturas"])): ?>
			<?php foreach ($sale["facturas"] as $keyFactura => $valueFactura): ?>
				<div id="KEB_<?php echo md5($valueFactura["Salesinvoice"]["bill_code"]) ?>" class="table-responsive" style="display: none;">
					<?php $factValue = (array) json_decode($valueFactura["Salesinvoice"]["bill_text"]); ?>
					<?php echo $this->element("vistaFacturaWo", ["factValue" => $factValue]); ?>
				</div>
			<?php endforeach ?>								
		<?php endif ?>

	<?php endforeach; ?>
	
<?php endif ?>


<div class="modal fade" id="modalFacturaDetalle" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle factura </h2>
      </div>
      <div class="modal-body" id="bodyDetalleFactura">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/reporte_tecnico.js?".rand(),			array('block' => 'AppScript')); 
	echo $this->Html->script("controller/prospectiveUsers/index.js?".rand(),			array('block' => 'AppScript'));
?>

<?php echo $this->element("picker"); ?>
<?php echo $this->element("flujoModal"); ?>

<?php 
    $this->start('AppScript'); ?>

<script>
	$('#tablaPendientes thead tr')
		        .clone(true)
		        .addClass('filters')
		        .appendTo('#tablaPendientes thead');


    $('#tablaPendientes').DataTable( {
    	'iDisplayLength': 10,
    	"lengthMenu": [ [5,10,20,50, 100, -1], [5,10,20,50, 100, "Todos"] ],
    	"ordering": false,
    	paging: true,
    	"language": {"url": "<?php echo Router::url("/",true) ?>Spanish.json",},
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                if (column[0] == 0) {

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
