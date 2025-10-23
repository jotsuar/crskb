<?php echo $this->Form->create('ProspectiveUser',array('id' => 'form_bill2','enctype'=>'multipart/form-data')); ?>
	<?php echo $this->Form->hidden('id',array("value" => $id)); ?>
	<?php echo $this->Form->hidden('flow_qt',array("value" => $flow_qt)); ?>
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
				<a href="" class="btn btn-info mt-4 validarFacturaWo2">
					Validar factura con WO
				</a>
			</div>
		</div>
		<div class="datosWo2 col-md-12">
			
		</div> 
		<div class="col-md-12 noShow" style="display: none">
			<div class="form-group">
				<input type="submit" class="form-control btn btn-success btn-block" value="Crear Cotización">
			</div>
		</div>
	</div>

</form>