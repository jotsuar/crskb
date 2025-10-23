<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-aguamarina big">
         <i class="fa fa-1x flaticon-logistics-delivery-truck-and-clock"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Despachos</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<a href="<?php echo $this->Html->url(array('controller'=>'conveyors','action'=>'index')) ?>" class="btn btn-info pull-right">
            Gestión de transportadoras
        </a>
		<h1 class="nameview spacebtnm upper" style="font-size: 2.0rem !important;">Flujos pendientes y parciales de despacho</h1>
			<ul class="subdespachos">
				<?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
					<a href="<?php echo $this->Html->url(array('controller'=>'conveyors','action'=>'index')) ?>" class="btn btn-info pull-right">
			            Gestión de transportadoras
			        </a>
				<?php endif ?>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches')) ?>"> Despachos por confirmar</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>"> Despachos enviados / Finalizados</a>
				</li>
			</ul> 

	</div>
	<div class="blockwhite p-2">
		<div class="contenttableresponsive bg-gris p-2">
			<div class="col-md-12">
				<h2 class="text-center mb-2">Flujos pendientes de despacho</h2>						
			</div>
			<table cellpadding="0" id="tablaPendientes" cellspacing="0" style="width: 100% !important;" class='table table-striped tablaPendientes table-bordered'>
				<thead>
					<tr>
						<!-- <th>#</th> -->
						<th>Asesor</th>
						<th class="borra">Flujo</th>
						<th class="borra">Fecha de gestión</th>
						<th class="borra">Fecha estimada importación</th>
						<th>Cliente</th>
						<th class="borra">Requerimiento inicial</th>
						<th class="borra">Teléfono</th>
						<th class="borra">Datos de despacho</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($datos as $key => $value): ?>
					<tr>
						<!-- <td>
							<?php // echo $value['FlowStage']['id'] ?> 
						</td> -->		
						<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
						<td>

							<div class="dropdown d-inline styledrop">
								<a class="btn btn-success btn-sm dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($key) ?>_<?php echo md5($value['FlowStage']['prospective_users_id']) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<?php echo $value['FlowStage']['prospective_users_id'] ?>
								</a>

								<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($key) ?>_<?php echo md5($value['FlowStage']['prospective_users_id']) ?>">
									<a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $value['FlowStage']['prospective_users_id'] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value['FlowStage']['prospective_users_id']); ?>">Ver flujo</a>
									<?php if (in_array($value['ProspectiveUser']['state_flow'], [3,4,5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>														
										<a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($value['FlowStage']['prospective_users_id']) ?>" href="#">Ver cotización</a>
									<?php endif ?>
									<?php if (in_array($value['ProspectiveUser']['state_flow'], [4,5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>
										<a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $value['FlowStage']['prospective_users_id'] ?>">Ver órden de compra</a>
									<?php endif ?>
									<?php if (in_array($value['ProspectiveUser']['state_flow'], [5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>
										<a class="dropdown-item getPagos" href="#" data-flujo="<?php echo $value['FlowStage']['prospective_users_id'] ?>">Ver comprobante(s) de pago</a>
									<?php endif ?>
								</div>
							</div>
						</td>
						<td>
							<?php echo date("Y-m-d",strtotime($value['FlowStage']['created'])); ?>
						</td>
						<td>
							<?php $datosImport = $this->Utilities->getDataDeadline($value['ProspectiveUser']['id']); ?>
							<?php if (!empty($datosImport) && !empty($datosImport["ImportRequestsDetail"]["deadline"]) ): ?>
								<?php echo $datosImport["ImportRequestsDetail"]["deadline"] ?> | 
							<?php endif ?>

							<?php if (!empty($datosImport) && isset($datosImport["Import"]) ): ?>
								<a href="<?php echo $this->Html->url(array('controller' => 'Products','action' => 'products_import', $this->Utilities->encryptString($value['Import']['id']))) ?>" class="btn btn-outline-success" data-toggle="tooltip" title="Ver detalle">
										Detalle importación
									<i class="fa fa-fw fa-eye vtc"></i>
								</a>
							<?php endif ?>
							
						</td>																
						<td>
							<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['FlowStage']['prospective_users_id']), 40,array('ellipsis' => '...','exact' => false)); ?> 							
						</td>
						<td>
							<?php echo $this->Text->truncate($this->Utilities->find_reason_prospective($value['FlowStage']['prospective_users_id']), 40,array('ellipsis' => '...','exact' => false)); ?> 
						</td>
						<td>
							<?php echo $this->Utilities->telephone_client_contact_prospective($value['FlowStage']['prospective_users_id']) ?>
						</td>
						<td>
							<!-- <span class="btn btn-success igualb btn-sm"><?php echo $this->Utilities->validate_active_pago_flujo(3,$value['FlowStage']['prospective_users_id'],$value["ProspectiveUser"]); ?></span> -->
						</td>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="blockwhite mt-2 p-2">
		<div class="contenttableresponsive bg-gris p-2">
			<div class="col-md-12">
				<h2 class="text-center mb-2">Flujos parciales con despacho</h2>						
			</div>
			<table cellpadding="0" cellspacing="0" class='table-striped table datosPendientesDespacho23 table-bordered'>
				<thead>
					<tr>
						<th>Flujo</th>
						<th>Fecha</th>
						<th class="asesorTh" style="width:80px !important">Asesor</th>
						<th>Cliente</th>
						<th>Recibe</th>
						<th>Dirección</th>
						<th>Ciudad</th>
						<th>Flete</th>
						<th class="size3">Acción</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($pendingDispatches as $value): ?>
					<tr>
						<td>
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'Index?q='.$value['ProspectiveUser']['id'])) ?>" class="idflujotable">
								<?php echo $value['ProspectiveUser']['id'] ?>
							</a>
						</td>
						<td><?php echo $this->Utilities->date_castellano3(h($value['FlowStage']['created'])); ?></td>
						<td class="nameuppercase asesorTh"><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
						<td class="nameuppercase" style="width:80px !important; font-size: 12px;">
							<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']), 40,array('ellipsis' => '...','exact' => false)); ?> 	
						</td>
						<td class="nameuppercase" style="font-size: 12px;"><?php echo $value['FlowStage']['contact']; ?></td>
						<td>
							<div class="infoAdd">
								<?php echo $value['FlowStage']['address']; ?> (<?php echo $value['FlowStage']['additional_information']; ?>)
							</div>
						</td>
						<td><?php echo $value['FlowStage']['city']; ?></td>
						<td><?php echo $value['FlowStage']['flete']; ?></td>
						<td>
							

										
							<?php if (!empty($value["ProspectiveUser"]["bill_code"])): ?>
								<a class="listFact btn btn-warning btn-sm" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>">
									Facturas 								
								</a>
							<?php endif ?>
								<?php if ($value["ProspectiveUser"]["state"] != 3): ?>
									<!-- <span class="btn btn-success igualb btn-sm"><?php echo $this->Utilities->validate_active_pago_flujo(3,$value['FlowStage']['prospective_users_id'],$value["ProspectiveUser"]); ?></span> -->
									<a class=" btn btn-success btn-sm text-white <?php echo empty($value["ProspectiveUser"]["bill_code"]) ? 'pointer': 'pointer' ?>  info_bill" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-bill="<?php echo $value["ProspectiveUser"]["bill_code"] ?>">
										<?php echo !empty($value["ProspectiveUser"]["bill_code"]) ? "Nueva factura" : "Ingresar factura" ?>	</a>

								<?php endif ?>
				
						</td>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),									array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/information_dispatches.js?".rand(),		array('block' => 'AppScript'));
?>

<!-- Modal -->
<div class="modal fade " id="despachoDeProductos" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Gestión de despacho de productos</h5>
      </div>
      <div class="modal-body" id="cuerpoDespacho">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="modalBillInformation" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Datos de factura de venta</h5>
      </div>
      <div class="modal-body" id="cuerpoBill">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php echo $this->element("flujoModal"); ?>
<?php echo $this->element("address"); ?>
<?php echo $this->Html->script("controller/prospectiveUsers/despacho.js?".rand(),			array('block' => 'AppScript'));  ?>



<?php 
	echo $this->Html->script("controller/prospectiveUsers/pending_dispatches.js?".rand(),	array('block' => 'AppScript'));
	echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); 
	echo $this->Html->script("https://rawgit.com/dtasic/show-more-plugin/master/jquery.show-more.js?".rand(),    array('block' => 'AppScript')); 
?>

<!-- Modal -->
<div class="modal fade " id="modalBillInformation" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Datos de factura de venta</h5>
      </div>
      <div class="modal-body" id="cuerpoBill">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade " id="modalBillList" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Listado de facturas ingresadas</h5>
      </div>
      <div class="modal-body" id="cuerpoBillList">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="popup" style="width: 60%;">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>



<?php 
    $this->start('AppScript'); ?>


<script>
	//ampliar imagen del pago
	$("body").on( "click", ".Comprobanteacep", function() {
	    var comprobante = $(this).children("img").attr("datacomprobante");
	    console.log(comprobante);
	    $(".img-product,#img-product").attr('src',comprobante);
	    $(".fondo").fadeIn();
	    $(".popup2,.popup").fadeIn();
	});
	$('.infoAdd').showMore({
	    minheight: 0,
	    animationspeed: 250,
	    buttontxtmore: "Ver más +",
        buttontxtless: "Ver menos -",
        buttoncss: "btn btn-primary btn-sm",
	});
	$('#tablaPendientes thead tr')
		        .clone(true)
		        .addClass('filters')
		        .appendTo('#tablaPendientes thead');


    $('#tablaPendientes').DataTable( {
    	'iDisplayLength': 10,
    	"lengthMenu": [ [5,10,20,50, 100, -1], [5,10,20,50, 100, "Todos"] ],
    	"ordering": true,
    	paging: true,
    	"language": {"url": "<?php echo Router::url("/",true) ?>Spanish.json",},
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                if (column[0] == 0 || column[0] == 3) {

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
        }
    } );

    $('.datosPendientesDespacho23').DataTable( {
    	'iDisplayLength': 10,
    	"lengthMenu": [ [5,10,20,50, 100, -1], [5,10,20,50, 100, "Todos"] ],
    	"ordering": false,
    	paging: true,
    	"language": {"url": "<?php echo Router::url("/",true) ?>Spanish.json",},
    } );

</script>

<?php
    $this->end();
 ?>

<style>
	.showmore-button {
	    cursor: pointer; 
	    background-color: #999; 
	    color: white; 
	    text-transform: uppercase; 
	    text-align: center; 
	    padding: 7px 5px 5px 5px; 
	    margin-top: 5px;
	}
	.asesorTh{
		width: 80px !important;
	}

	.btn-sm {
	    padding: 0.5px !important;
	}

	td, th {
	    padding: 2px 3px !important;
	}

	.select2-container .select2-selection--single .select2-selection__rendered {
	    padding-left: 2px !important;
	    padding-right: 2px !important;
	}
	.select2-container--default .select2-selection--single {
	    min-height: 20px !important;
    	max-height: 29px;
    	padding-top: 0px !important;
    }
</style>