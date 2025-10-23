<?php echo $this->Form->create('Shipping',["type" => "file",'data-parsley-validate'=>true]); ?>

	<?php if ($shipping["Shipping"]["type"] == 10 && $shipping["Shipping"]["request_type"] == 0): ?>
		<?php $estados = ["3" => "Entregado","-1"=>"Rechazado"]; ?>
		<?php echo $this->Form->input('date_preparation',["type" => "hidden", "value" => date("Y-m-d H:i:s") ]); ?>
		<?php echo $this->Form->input('date_send',["type" => "hidden", "value" => date("Y-m-d H:i:s") ]); ?>
		<?php echo $this->Form->input('date_end',["type" => "hidden", "value" => date("Y-m-d H:i:s") ]); ?>
		<?php echo $this->Form->input("note_bill", array("label" => "Notas adicionales (en caso de rechazo)" ,"placeholder" => "Escriba notas adicionales" ,"required" => false,) ) ?>
	<?php else: ?>
		<?php if ($shipping["Shipping"]["state"] == 0): ?>
			<?php $estados = ["1" => "En preparación","-1"=>"Rechazado"]; ?>
			<?php echo $this->Form->input("note_bill", array("label" => "Notas adicionales (en caso de rechazo)" ,"placeholder" => "Escriba notas adicionales" ,"required" => false,) ) ?>
			<?php echo $this->Form->input('date_preparation',["type" => "hidden", "value" => date("Y-m-d H:i:s") ]); ?>
		<?php elseif($shipping["Shipping"]["state"] == 1): ?>
			<?php $estados = ["2" => "Enviado y/o facturado"]; ?>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="">
							Fecha probable de entrega
						</label>
						<?php echo $this->Form->text('date_deadline',["type" => "date", "value" => date("Y-m-d"),"label" => "Fecha probable de entrega", "class" => "form-control" ]); ?>
					</div>
				</div>
				<div class="col-md-6" style="<?php echo $shipping["Shipping"]["request_type"] == "1" || $shipping["Shipping"]["request_type"] == "3" ? 'display:none !important' : '' ?>">
					<div class="form-group">
						<?php echo $this->Form->input('conveyor_id',["label" => "Transportista", "empty" => "Seleccionar" ]); ?>
						<?php echo $this->Form->input('request_type',["type" => "hidden", "value" => $shipping["Shipping"]["request_type"] ]); ?>
					</div>
				</div>
				<div class="col-md-4" style="<?php echo $shipping["Shipping"]["request_type"] == "1" || $shipping["Shipping"]["request_type"] == "3" ? 'display:none !important' : '' ?>">
					<?php echo $this->Form->input('date_send',["type" => "hidden", "value" => date("Y-m-d H:i:s") ]); ?>
					<?php echo $this->Form->input('guide',["required"=>true,"label" => "# Guia", "value" => $shipping["Shipping"]["request_type"] == 2 || $shipping["Shipping"]["request_type"] == 0 ? "" : "0" ]); ?>
				</div>
				<div class="col-md-4" style="<?php echo $shipping["Shipping"]["request_type"] == "1" || $shipping["Shipping"]["request_type"] == "3" ? 'display:none !important' : '' ?>">
					<?php echo $this->Form->input('document',["required"=>$shipping["Shipping"]["request_type"] == 2 || $shipping["Shipping"]["request_type"] == 0 ? true : false,"label" => "Documento Guía adjunto * (requerido)", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "5M", "class" => "dropify", ]); ?>
				</div>
				<div class="col-md-4" style="<?php echo $shipping["Shipping"]["request_type"] == "1" ? 'display:none !important' : '' ?>">
					<?php echo $this->Form->input('remision',["required"=>false,"label" => "Documento de remisión", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "5M", "class" => "dropify", ]); ?>
				</div>
				<div class="col-md-12">
					<hr>
				</div>

				<div class="col-md-6">
					<?php if ($shipping["Shipping"]["request_type"] == 2 || $shipping["Shipping"]["request_type"] == 1): ?>
						<?php echo $this->Form->input('bill_file',["required"=>true,"label" => "Factura generada * (requerido)", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "10M", "class" => "dropify" ]); ?>
						
					<?php endif ?>
				</div>
				<div class="row" style="<?php echo $shipping["Shipping"]["request_type"] == "0" ? 'display:none !important' : '' ?>">
					
				
					<div class="col-md-6">
						<?php echo $this->Form->input("note_bill", array("label" => "Notas adicionales de la factura" ,"placeholder" => "Escriba notas adicionales a la factura " ,"required" => false,) ) ?>
							
					</div>
					<div class="col-md-6">
						<?php echo $this->Form->input("bill_prefijo", array("label" => "Prefijo de la factura" ,"placeholder" => "Ingrese el código de la factura" ,"required","options" => ["KE" => "KE", "KEB" => "KEB"]) ) ?>						
					</div>
					
					
					<div class="col-md-6">
						<?php echo $this->Form->input("bill_code", array("label" => "Número de la factura" ,"placeholder" => "Ingrese el código de la factura" ,"required" => $shipping["Shipping"]["request_type"] == 2 || $shipping["Shipping"]["request_type"] == 1 ? true : false ,"onkeypress"=>"return valideKey(event);" )) ?>

						<a href="javascript:void(0)" class="btn btn-info validateWOInfo">
							Validar con WO <i class="fa fa-check vtc"></i>
						</a>
						<span class="btn btn-outline-primary disabled">
							<strong class="text-info">Estado</strong>: <span id="stateValidate">
								<?php if ($shipping["Shipping"]["request_type"] == 2 || $shipping["Shipping"]["request_type"] == 1): ?>
									Sin validar
								<?php else: ?>
									Validada
								<?php endif ?>
						</span>
							<?php echo $this->Form->input("bill_valid", array("label" => false ,"placeholder" => "Ingrese el código de la factura" ,"required","onkeypress"=>"return valideKey(event);", "value" => $shipping["Shipping"]["request_type"] == 2 || $shipping["Shipping"]["request_type"] == 1 ?  "" : 1, "style" => "display:none" )) ?>
						</span>
					</div>
				</div>
				<div class="col-md-12" id="valdiateWoFact">

				</div>

			</div>
			
			
			
			
			

			

		<?php elseif($shipping["Shipping"]["state"] == 2): ?>
			<?php $estados = ["3" => "Entregado"]; ?>
			<?php echo $this->Form->input('date_end',["type" => "hidden", "value" => date("Y-m-d H:i:s") ]); ?>
		<?php elseif($shipping["Shipping"]["state"] == 3 && $shipping["Shipping"]["request_envoice"] == 1): ?>
			<?php $estados = ["3" => "Facturado"]; ?>
			<?php echo $this->Form->input('date_end',["type" => "hidden", "value" => date("Y-m-d H:i:s") ]); ?>
			<?php echo $this->Form->input('request_type',["type" => "hidden", "value" => $shipping["Shipping"]["request_type"] ]); ?>
			<?php echo $this->Form->input('request_envoice',["type" => "hidden", "value" => 2 ]); ?>
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->Form->input('bill_file',["required"=>true,"label" => "Factura generada * (requerido)", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "10M", "class" => "dropify" ]); ?>
				</div>
				<div class="col-md-6">
					<?php echo $this->Form->input("note_bill", array("label" => "Notas adicionales de la factura" ,"placeholder" => "Escriba notas adicionales a la factura " ,"required" => false,) ) ?>
				</div>

				<div class="col-md-6">
					<?php echo $this->Form->input("bill_prefijo", array("label" => "Prefijo de la factura" ,"placeholder" => "Ingrese el código de la factura" ,"required","options" => ["KE" => "KE", "KEB" => "KEB"]) ) ?>						
				</div>
				
				
				<div class="col-md-6">
					<?php echo $this->Form->input("bill_code", array("label" => "Número de la factura" ,"placeholder" => "Ingrese el código de la factura" ,"required" => $shipping["Shipping"]["request_type"] == 2 || $shipping["Shipping"]["request_type"] == 1 ? true : false ,"onkeypress"=>"return valideKey(event);" )) ?>

					<a href="javascript:void(0)" class="btn btn-info validateWOInfo">
						Validar con WO <i class="fa fa-check vtc"></i>
					</a>
					<span class="btn btn-outline-primary disabled">
						<strong class="text-info">Estado</strong>: <span id="stateValidate">
								Sin validar
					</span>
						<?php echo $this->Form->input("bill_valid", array("label" => false ,"placeholder" => "Ingrese el código de la factura" ,"required","onkeypress"=>"return valideKey(event);", "value" => "", "style" => "display:none" )) ?>
					</span>
				</div>
				<div class="col-md-12" id="valdiateWoFact">

				</div>

			</div>
		<?php elseif($shipping["Shipping"]["state"] == 3 && $shipping["Shipping"]["request_shipping"] == 1): ?>
			<?php $estados = ["3" => "Enviado"]; ?>
			<?php echo $this->Form->input('date_end',["type" => "hidden", "value" => date("Y-m-d H:i:s") ]); ?>
			<?php echo $this->Form->input('request_type',["type" => "hidden", "value" => $shipping["Shipping"]["request_type"] ]); ?>
			<?php echo $this->Form->input('request_shipping',["type" => "hidden", "value" => 2 ]); ?>
			<div class="row">
				
				<div class="col-md-6">
					<div class="form-group">
						<label for="">
							Fecha probable de entrega
						</label>
						<?php echo $this->Form->text('date_deadline',["type" => "date", "value" => date("Y-m-d"),"label" => "Fecha probable de entrega", "class" => "form-control" ]); ?>
					</div>
				</div>
				<div class="col-md-6" >
					<div class="form-group">
						<?php echo $this->Form->input('conveyor_id',["label" => "Transportista", "empty" => "Seleccionar" ]); ?>
						<?php echo $this->Form->input('request_type',["required"=>true,"type" => "hidden", "value" => $shipping["Shipping"]["request_type"] ]); ?>
					</div>
				</div>
				<div class="col-md-6">
					<?php echo $this->Form->input('date_send',["type" => "hidden", "value" => date("Y-m-d H:i:s") ]); ?>
					<?php echo $this->Form->input('guide',["required"=>true,"label" => "# Guia" ]); ?>
				</div>
				<div class="col-md-6">
					<?php echo $this->Form->input('document',["required"=>true,"label" => "Documento Guía adjunto * (requerido)", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "5M", "class" => "dropify", "required" => true ]); ?>
				</div>

			</div>
		<?php endif ?>
	<?php endif ?>
	<?php if (!empty($estados)): ?>		
		<div class="form-group">
			<?php echo $this->Form->input('state',["label" => "Estado", "options" => $estados, "class" => "form-control" ]); ?>		
		</div>
	<?php endif ?>

	<small class="text-danger mt-3">
		Nota: Todos los campos son requeridos
	</small>

	<div class="form-group">
		<input type="submit" class="btn btn-success btn-block" value="Actualizar despacho">
	</div>


<?php echo $this->Form->end(); ?>