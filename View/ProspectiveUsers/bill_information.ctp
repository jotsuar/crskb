<?php 
	$importacion = false;
	$imports = array();

   	if(!empty($datosFlujo["Import"]) && empty($datosFlujo["Imports"]) ){
   		$imports[] 		= $datosFlujo["Import"];
   		$importacion 	= true;
   	}else if(!empty($datosFlujo["Imports"])){
   		$importacion 	= true;
   		$imports 		= $datosFlujo["Imports"];
   	}
 ?>
<?php echo $this->Form->create('ProspectiveUser',array('id' => 'form_bill','enctype'=>'multipart/form-data')); ?>
	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Form->hidden('id',array("disabled" => $disabled)); ?>
			<div class="row">
				
					<div class="col-md-4 mb-4">
						<?php $total = $cotizacion["Quotation"]["total"] * 1.19; ?>
						<h2>Precio cotización SIN IVA: <b><?php echo number_format($cotizacion["Quotation"]["total"], 2,",",".") ?></b></h2>
						<h2>Precio cotización IVA INCLUIDO: <b><?php echo number_format($total, 2,",",".") ?></b></h2>
						<?php echo $this->Form->input("qt_noiva", array( "type" => "hidden", "value" => $cotizacion["Quotation"]["total"] )) ?>
						<?php echo $this->Form->input("qt_iva", array( "type" => "hidden", "value" => $total )) ?>
					</div>
					<div class="col-md-4">
						<?php if (empty($datosFlujo["ProspectiveUser"]["bill_code"]) && $cotizacion["Quotation"]["header_id"] != 3 && isset($trm) ): ?>
						<h2>TRM con el que se realizó el pago: <b><?php echo number_format($trm, 2,",",".") ?></b></h2>
						<?php endif ?>
					</div>
					<div class="col-md-4">
						<?php if (empty($datosFlujo["ProspectiveUser"]["bill_code"]) && $cotizacion["Quotation"]["header_id"] != 3 ): ?>
							<?php echo $this->Form->input("change_trm", array("label" => "¿Deseas cambiar el trm con el que se realizó el pago para ajustar la factura ?" , "options" => ["0" => "NO", "1" => "SI"],"class" => "form-control" )) ?>
						<?php endif ?>
					</div>
					<?php if (empty($datosFlujo["ProspectiveUser"]["bill_code"]) && $cotizacion["Quotation"]["header_id"] != 3 ): ?>
						<div class="border border-info cambioTrm col-md-6 offset-md-3 p-3 mb-3" style="display: none;">
							<h3>Procesar cambio con el trm del día de pago: </h3>
							<select name="data[ProspectiveUser][trmDiaCambio]" id="trmDiaCambio" class="form-control">
								<option value="">Seleccionar TRM</option>
								<?php for ($day=0; $day <= 30; $day++) : ?>
									<?php $valor =  $this->Utilities->getDayTrm( date("Y-m-d",strtotime("- $day day")), $ajuste, $trmActual )[1]; ?>
									<option <?php echo $valor == $trm ? "selected" : "" ?> value=" <?php echo $valor ?>">
										<?php echo $this->Utilities->getDayTrm( date("Y-m-d",strtotime("- $day day")), $ajuste, $trmActual )[0]; ?>
									</option>				
								<?php endfor; ?>
							</select>

							<a href="#" class="btn btn-warning mt-2 mb-3 pull-right" id="btnProcesarCambioDolarTrm" data-flujo="<?php echo $prospective ?>" data-quotation="<?php echo $cotizacion["Quotation"]["id"] ?>">
								Procesar cambio
							</a>
						</div>
					<?php endif ?>					
				<div class="col-md-12 border p-3">
					<div class="row">
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<?php echo $this->Form->input("bill_code", array("label" => "Número de la factura" ,"placeholder" => "Ingrese el código de la factura" ,"required","onkeypress"=>"return valideKey(event);" )) ?>
							</div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<?php echo $this->Form->input("bill_prefijo", array("label" => "Prefijo de la factura" ,"placeholder" => "Ingrese el código de la factura" ,"required")) ?>
							</div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="form-group">

								<?php if (AuthComponent::user("role") == "Asesor Externo"): ?>
									<?php $usuariosAsesoresData = [ AuthComponent::user("id") => AuthComponent::user("name") ]; ?>
								<?php endif ?>

								<?php echo $this->Form->input("bill_user", array("label" => "Usuario que realizó la venta", "options" => $usuariosAsesoresData ,"placeholder" => "Ingrese el código de la factura" ,"required","default" => $datosFlujo["ProspectiveUser"]["user_id"] )) ?>
							</div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="form-group">
								<a href="" class="btn btn-info mt-1 validarFacturaWo">
									Validar factura con WO
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="border col-md-12 datosWo mt-3 py-2">
					
				</div>
				
			</div>
			
		</div>
	</div>	
</form>