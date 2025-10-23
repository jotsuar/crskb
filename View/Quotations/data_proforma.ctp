<div class="clientsNaturals form">
	<?php echo $this->Form->create('Proforma',["url" => ["controller" => "quotations","action"=>"proforma",$this->Utilities->encryptString($id),"ext"=>"pdf"],"type" => "get","target"=>"_blank" ]); ?>
		<?php
			echo $this->Form->input('proforma',array('type' => 'hidden',"value"=> md5($datosCliente["name"]).md5($datosCliente["name"]).md5($datosCliente["name"]).md5($datosCliente["name"]).md5($datosCliente["name"]).md5($datosCliente["name"]) ,"required"));
			echo $this->Form->input('name',array('label' => 'Nombre *',"value"=>$datosCliente["name"],"required"));
			echo $this->Form->input('identification',array('label' => 'Identificación *',"value" => $datosCliente["identification"],"required"));
			echo $this->Form->input('nro_orden',array('label' => 'Nro. de órden del cliente',"value" => ""));
			echo $this->Form->input('telephone',array('label' => 'Teléfono *','value' => $datosCliente["cell_phone"],"required"));
			echo $this->Form->input('address',array('label' => 'Dirección','placeholder' => 'Ingresa la dirección del cliente',"required"));
			echo $this->Form->input('city',array('label' => 'Ciudad *',"value" => $datosCliente["city"],"required"));
			echo $this->Form->input('currency',array('label' => 'Moneda *',"options" => $currencys,"required"));
			echo $this->Form->input('payment',array('label' => 'Formas de pago *',"options" => $formasPago,"required"));
			if ($country) {
				$numbersImpuestos[0] = "No aplica";
				for ($i=1; $i <= 100 ; $i++) { 
					$key = ( 1 + ($i/100) );
					$numbersImpuestos[strVal($key)] = $i." %";
				}

				echo $this->Form->input('shipping',array('label' => 'Método de envío *',"options" => $formaEnvio,"required"));
				echo $this->Form->input('iva',array('label' => 'Aplica Impuesto *',"options" => $numbersImpuestos,"required",));
			}
			echo $this->Form->input('contacto',array('label' => 'Contacto',));
			echo $this->Form->input('observaciones',array('label' => 'observaciones',"type" => "textarea"));
			echo "<label> Fecha límite </label>";
			echo $this->Form->text('fecha_limite',array('label' => 'Fecha límite *',"type"=>"date","min"=>date("Y-m-d"),"required","class"=>"form-control","value" => date("Y-m-d")));
		?>
		<div class="form-group">
			<input type="submit" value="Generar proforma" class="mt-4 btn btn-success pull-right">
		</div>
	</form>
</div>
