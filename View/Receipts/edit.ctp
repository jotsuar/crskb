<div class="receipts form">
	<div class="row">
		<div class="col-md-12 contentnums">
			<div class="row">
				<?php $totalNoIvaOtras = 0;	$totalIvaOtras = 0; ?>
				<?php if (!empty($valorQuotation)): ?>
					<div class="col-md-6 col-lg-4">
							<p>Precio de cotización con IVA: </p>
							<div class="contentnum color1">$ <?php echo number_format(($valorQuotation * 1.19), 2, ".",",") ?> </div>
					</div>
				<?php endif ?>
				<?php if (!empty($valorQuotation)): ?>
					<div class="col-md-6 col-lg-4">
							<p>Precio de cotización sin IVA: </p>
							<div class="contentnum color2">$ <?php echo number_format($valorQuotation, 2, ".",",") ?> </div>
					</div>
				<?php endif ?>
				<?php if (!empty($prospetive["ProspectiveUser"]["bill_value"])): ?>
					<?php if(!empty($facturasAdicionales)){						
						foreach ($facturasAdicionales as $key => $value) {
							$totalNoIvaOtras+=$value["Salesinvoice"]["bill_value"];
							$totalIvaOtras+=$value["Salesinvoice"]["bill_value_iva"];
						}
					} ?>
					<div class="col-md-6 col-lg-4">
						<p>Precio de factura sin IVA: </p>
						<div class="contentnum color3">$ <?php echo number_format(($prospetive["ProspectiveUser"]["bill_value"] + $totalNoIvaOtras), 2, ".",",") ?> </div>
					</div>
				<?php endif ?>
				<?php if (!empty($prospetive["ProspectiveUser"]["bill_value_iva"])): ?>
					<div class="col-md-6 col-lg-4">
							<p>Precio de factura con IVA: </p>
							<div class="contentnum color4">$ <?php echo number_format(($prospetive["ProspectiveUser"]["bill_value_iva"] + $totalIvaOtras), 2, ".",",") ?> </div>
					</div>	
				<?php endif ?>
				<div class="col-md-6 col-lg-4">
						<p>Total ingresado en recibos de caja </p>
						<div class="contentnum color5">$ <?php echo number_format($totalActual, 2, ".",",") ?> </div>
				</div>	
				<div class="col-md-6 col-lg-4">
					<?php if ($totalActual >= 0): ?>
						<p>Faltante por ingresar recibos de caja </p>
						<div class="contentnum color6">$ <?php echo number_format( (($valorQuotation * 1.19) - $totalActual) , 2, ".",",") ?> </div>
					<?php endif ?>
				</div>					

			</div>
		</div>
	</div>

<?php echo $this->Form->create('Receipt'); ?>
	<?php
		echo $this->Form->input('id', array("type" => "hidden"));
		echo $this->Form->input('prospective_user_id', array("type" => "hidden", "value" => $id));	

		if (empty($facturasAdicionales)) {
			$this->Form->input("salesinvoice_id",array("type" => "hidden", "value" => 0));
		}
		
	?>
	<div class="row">
		<div class="col-md-6 col-sm-6">
			<div class="input-group form-group">
				<?php echo $this->Form->input('code',array("required" => true, "min" => "0", "label" => "Código o número de recibo", "placeholder" => "Código o número de recibo",'class'=>'form-control w-75',"type" => "text","div" => false)); ?>
				<div class="input-group-append">
				    <button class="btn btn-outline-success" type="button" id="validarValor">Validar <i class="fa fa-check vtc"></i></button>
				  </div>
			</div>
		</div>
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<?php echo $this->Form->input('total',array("required" => true, "min" => "0", "label" => "Total Recibo con IVA", "placeholder" => "Total Recibo con IVA","type" => "text")); ?>
			</div>
		</div>
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<?php echo $this->Form->input('total_iva',array("required" => true, "min" => "1", "label" => "Valor para comisión", "placeholder" => "Valor para comisión")); ?>
			</div>
		</div>
		
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<?php echo $this->Form->input('date_receipt',array("required" => true, "label" => "Fecha del recibo","type" => "text")); ?>
			</div>
		</div>
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<?php echo $this->Form->input('user_id',array("required" => true, "label" => "Usuario","empty" => "Seleccionar","default"=>$prospetive["ProspectiveUser"]["user_id"])); ?>
			</div>
		</div>
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<?php echo $this->Form->input('retefuente',array("required" => true, "label" => "Aplica RETEFUENTE" ,"options" => Configure::read("IMPUESTOS"))); ?>
			</div>
		</div>
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<?php echo $this->Form->input('reteiva', array("required" => true, "label" => "Aplica RETEIVA" ,"options" => Configure::read("IMPUESTOS"))); ?>
			</div>
		</div>
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<?php echo $this->Form->input('otras',array("required" => true, "label" => "Aplica OTRAS RETENCIONES" ,"options" => Configure::read("IMPUESTOS"))); ?>
			</div>
		</div>
		<?php if (!empty($facturasAdicionales)): ?>
			<div class="col-md-6 col-sm-6">
				<div class="form-group">
					<?php 

						if ($prospetive["ProspectiveUser"]["locked"] == 0) {
							$facturas = array( "0" => $prospetive["ProspectiveUser"]["bill_code"]. " - $ ". $prospetive["ProspectiveUser"]["bill_value"]);
						}

						foreach ($facturasAdicionales as $key => $value) {
							$facturas[$value["Salesinvoice"]["id"]] = $value["Salesinvoice"]["bill_code"]. " - $ ". $value["Salesinvoice"]["bill_value"];
						}

					 ?>
					<?php echo $this->Form->input('salesinvoice_id',array("required" => true, "label" => "Factura asociada" ,"options" => $facturas)); ?>
				</div>
			</div>
		<?php endif ?>
		<div class="col-md-12 col-sm-12">
			<div class="form-group">
				<input type="submit" id="btnFormSubmit" value="Guardar información" class="btn btn-success pull-right mt-3">
			</div>
		</div>
	</div>
	
</div>

</div>
