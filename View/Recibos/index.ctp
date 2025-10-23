<div class="col-md-12 spacebtn20">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Recibos de caja no procesados en CRM</h2>
	</div>
	<div class="blockwhite headerinformelineal">
		<div class="row">
			<div class="col-md-4">
				<h1 class="nameview">INFORME DE RECIBOS DE CAJA</h1>
			</div>
			<div class="col-md-8">
				<div class="rangofechas w-100">
					<!-- <?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100')); ?>
						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<span>Seleccionar rango de fechas:</span>
								</div>
								<div class="form-group">
									<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
								</div>
							</div>
							<div class="col-md-2 pt-4">
								<input type="date" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
								<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
								<button type="submit" class="btn btn-base pull-right" id="btn_find_adviser">Buscar</button>
							</div>
						</div>
						
						
					</form> -->
				</div>
			</div>
		</div>
	</div>
	<div class="blockwhite mt-3">
		<div class="table-responsive">
			<h2 class="text-center">Recibos sincronizados desde wo sin asignar en CRM</h2>

			<table cellpadding="0" cellspacing="0" class="table table-hovered">
				<thead>
					<tr>
							<th><?php echo $this->Paginator->sort('numero'); ?></th>
							<th><?php echo $this->Paginator->sort('fecha_recibo'); ?></th>
							<th><?php echo $this->Paginator->sort('fecha_gestion','Fecha de ingreso en WO'); ?></th>
							<th><?php echo $this->Paginator->sort('credito','Crédito'); ?></th>
							<th><?php echo $this->Paginator->sort('debito','Débito'); ?></th>
							<th><?php echo $this->Paginator->sort('created','Fecha de registro en CRM'); ?></th>
							<th class="actions"><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($recibos as $recibo): ?>
						<tr>
							<td><?php echo h($recibo['Recibo']['numero']); ?>&nbsp;</td>
							<td><?php echo date("Y-m-d",strtotime( $recibo['Recibo']['fecha_recibo'])); ?>&nbsp;</td>
							<td><?php echo date("Y-m-d H:i:s",strtotime( $recibo['Recibo']['fecha_gestion'])); ?>&nbsp;</td>
							<td>$ <?php echo number_format($recibo['Recibo']['credito']); ?>&nbsp;</td>
							<td>$ <?php echo number_format($recibo['Recibo']['debito']); ?>&nbsp;</td>
							<td><?php echo h($recibo['Recibo']['created']); ?>&nbsp;</td>
							<td class="actions">
								<a href="<?php echo $this->Html->url( array('action' => 'view', $recibo['Recibo']['id'])) ?>" class="btn btn-info viewRecibo">
									<i class="fa fa-eye vtc"></i>
								</a>
								<a href="<?php echo $this->Html->url( array('action' => 'view', $recibo['Recibo']['id'])) ?>"  data-numero="<?php echo $recibo['Recibo']['numero'] ?>" data-id="<?php echo $recibo['Recibo']['id'] ?>" class="btn btn-warning asignarFlujo">
									<i class="fa fa-plus vtc"></i> Asignar a flujo
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
</div>

<div class="modal fade" id="modalRecibo" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle recibo </h2>
      </div>
      <div class="modal-body" id="bodyRecibo">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade" id="modalReciboNuevo" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Añadir recibo a flujo </h2>
      </div>
      <div class="modal-body" id="bodyReciboNuevo">

      	<?php echo $this->Form->create('Receipt'); ?>
              <div class="row">
                <div class="col-md-11">
                	<input type="hidden" id="idReciboReceipt">
                	<input type="hidden" id="numeroReciboReceipt">
                  <select class="form-control" id="flujoFactBuscaListReceipt" placeholder="Escriba el número de flujo" aria-label="Escriba el número de flujo" aria-describedby="basic-addon2"></select>
                </div>
                <div class="col-md-1">
                  <button class="btn btn-outline-secondary btn-block" type="button" id="buttonSearchList">Buscar</button>
                </div>
              </div>
            <div class="col-md-12">
              <div id="listadoRecibosActuales"></div>
            </div>
				<?php echo $this->Form->end(); ?>

      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="recibodeCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">
        	Ingreso de información de recibo de caja
        </h5>
      </div>
      <div class="modal-body" id="cuerpoRecibo">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
	        Cerrar
	    </button>
      </div>
    </div>
  </div>
</div>



<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>

<?php 
    $this->start('AppScript'); ?>

    <script>
    	$(".viewRecibo").click(function(event) {
    		event.preventDefault();
    		const URL = $(this).attr("href");
    		$(".body-loading").show();
    		$.get(URL, function(data) {
    			$(".body-loading").hide();
    			$("#bodyRecibo").html(data);
    			$("#modalRecibo").modal('show');
    		});
    	});

    	$(".asignarFlujo").click(function(event) {
    		event.preventDefault();
    		const ID = $(this).data("id");
    		const NUMERO = $(this).data("numero");
    		$("#idReciboReceipt").val(ID)
    		$("#numeroReciboReceipt").val(NUMERO)
    		$("#modalReciboNuevo").modal('show');	
    	});


    	function getFlowPicker(id,dropDown = null,multiple = false){
			    var options = {
			        placeholder: "Buscar flujo",
			        minimumInputLength: 5,
			        multiple: multiple,
			        language: "es",
			        ajax: {
			            url: copy_js.base_url+"prospective_users/get_flow_data",
			            dataType: 'json',
			             data: function (params) {
			              return {
			                q: params.term, // search term
			                page: params.page
			              };
			            },
			            processResults: function (data, params) {
			              params.page = params.page || 1;

			              return {
			                results: data.items,
			                // pagination: {
			                //   more: (params.page * 30) < data.total_count
			                // }
			              };
			            },
			        }
			    };

			    if (dropDown != null) {
			        options["dropdownParent"] = $(dropDown);
			    }

			    $("body").find(id).select2(options).on("select2:select", function (e) {  $("#buttonSearchList").trigger('click');  });
			}

			if ($("#flujoFactBuscaListReceipt").length) {
				getFlowPicker("#flujoFactBuscaListReceipt","#modalReciboNuevo");
			}

			$("body").on('click', '#buttonSearchList', function(event) {
				event.preventDefault();
				var id = $("#flujoFactBuscaListReceipt").val();
				if (id != "") {
					$.post(copy_js.base_url+'ProspectiveUsers'+'/info_receipts', {id}, function(data, textStatus, xhr) {
				        $("#listadoRecibosActuales").html(data);
				    });
				}else{
					message_alert("Por favor ingresa el Número del flujo","error");
				}
			});

			$("body").on('click', '.nuevoReciboBtn', function(event) {
				event.preventDefault();
				
				var id = $(this).data("recibo");
				$("#cuerpoRecibo").html("");
				$("#recibodeCaja h5.modal-title").html("Ingreso de información de recibo de caja");
				$.get(copy_js.base_url+'receipts/edit', {id}, function(data, textStatus, xhr) {
					$("#cuerpoRecibo").html(data);
					$("body").find('#ReceiptDateReceipt').attr("type", "date");
					if($("body").find('#ReceiptDateReceipt').val() == ""){

						var today = new Date();
						var dd = String(today.getDate()).padStart(2, '0');
						var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
						var yyyy = today.getFullYear();

						today = yyyy + "-"+ mm + "-"+ dd;
						$("body").find('#ReceiptDateReceipt').val(today)
					}
					$("#recibodeCaja").modal("show");

					$("#ReceiptCode").val($("#numeroReciboReceipt").val());
					$('#validarValor').trigger('click');
				});
			});	


			$("body").on('click', '#validarValor', function(event) {
					event.preventDefault();
					var code = $("#ReceiptCode").val();
					if (code != "") {
						$("#loaderKebco").show();
						$.post(copy_js.base_url+'receipts'+'/get_info_wo', {code}, function(data, textStatus, xhr) {
					        const response = JSON.parse(data);
					        $("#loaderKebco").hide();
					        if (response.hasOwnProperty("Total")) {
					        	$("#ReceiptTotal").val(response.Total);
					        	$("#ReceiptDateReceipt").val(response.Fecha);
					        	$("#ReceiptTotal").trigger("change");
					        }else{
					        	message_alert("No se encontró información sobre este recibo","error");
					        }
					        console.log(response)
					    });
					}else{
						message_alert("Por favor ingresa el número o código del recibo ","error");
					}
				});

			$("body").on('click', '.btnEditRecipe', function(event) {
				event.preventDefault();
				var id = $(this).data("id");
				$("#recibodeCaja h5.modal-title").html("Edición de información de recibo de caja");
				$.get(copy_js.base_url+'receipts/edit_recipe', {id}, function(data, textStatus, xhr) {
					console.log(data);
					$("#cuerpoRecibo").html(data);
					$("body").find('#ReceiptDateReceipt').attr("type", "date");
					if($("body").find('#ReceiptDateReceipt').val() == ""){

						var today = new Date();
						var dd = String(today.getDate()).padStart(2, '0');
						var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
						var yyyy = today.getFullYear();

						today = yyyy + "-"+ mm + "-"+ dd;
						$("body").find('#ReceiptDateReceipt').val(today)
					}
					$("#recibodeCaja").modal("show");
				});
			});

			$("body").on('change', '#ReceiptTotal', function(event) {
				if($(this).val() != "" && $(this).val() != "0"){
					var total = parseFloat($(this).val()/1.19);
					$("body").find("#ReceiptTotalIva").val( total.toFixed(0) );
				}
			});

			$("body").on('click', '#buttonSearchList', function(event) {
				event.preventDefault();
				var id = $("#flujoFactBuscaListReceipt").val();
				if (id != "") {
					$.post(copy_js.base_url+'ProspectiveUsers'+'/info_receipts', {id}, function(data, textStatus, xhr) {
				        $("#listadoRecibosActuales").html(data);
				    });
				}else{
					message_alert("Por favor ingresa el Número del flujo","error");
				}
			});	


			$("body").on('submit', '#ReceiptEditForm,#ReceiptEditRecipeForm', function(event) {
					event.preventDefault();
					id = $("body").find('#ReceiptProspectiveUserId').val();
					var formData = $(this).serialize();
					if( $("body").find('#ReceiptTotalIva').val() != "" ){
						$(".body-loading").show();
						$.ajax({
					        type: 'POST',
					        url: copy_js.base_url+'receipts/edit?id='+id,
					        data: formData,
					        success: function(result){
					        	const receipt_id = $.trim(result);
					        	const recibo_id  = $("#idReciboReceipt").val()
					        	$.post(copy_js.base_url+'recibos/update_receipt', {recibo_id,receipt_id}, function(data, textStatus, xhr) {
					        		$(".body-loading").hide();
					        		location.reload();
					        	});

					        },error: function(){
					        	message_alert("Error al guardar","error");
					        }
					    });
					}else{
						message_alert("Debe ingresar un valor","error");
					}
					
				});

    </script>

<?php
    $this->end();
 ?>

<?php echo $this->element("picker"); ?>
