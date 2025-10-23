<div class="customerRequests form">
<?php echo $this->Form->create('CustomerRequest',["type" => "file"]); ?>
	<?php
		echo $this->Form->input('name',["label"=>"Nombre cliente/contacto", "value" => $type == 'natural' ? $customer_data["ClientsNatural"]["name"] : $customer_data["ContacsUser"]["name"] ]);
		echo $this->Form->input('company',["label"=>"Nombre empresa (si aplica)", "value" => $type == 'natural' ? '' : $customer_data["ClientsLegal"]["name"] ]);
		echo $this->Form->input('type',["label" => "Tipo de identificación", "value" => $type == 'natural' ? 'CC':'NIT', "options" => ["CC"=>"CC","NIT"=>"NIT"] ]);
		echo $this->Form->input('identification',["label" => "Número de identificación o NIT", "value" => $type == 'natural' ? $customer_data["ClientsNatural"]["identification"] : $customer_data["ClientsLegal"]["nit"] ] );
		echo $this->Form->input('email',["label" => "Correo Facturación Electrónica", "value" => $type == 'natural' ? $customer_data["ClientsNatural"]["email"] : $customer_data["ContacsUser"]["email"]]);
		echo $this->Form->input('address',["label" => "Dirección"]);
		echo $this->Form->input('phone',["label" => "Teléfono",'type'=>'number', "value" => $type == 'natural' ? $customer_data["ClientsNatural"]["cell_phone"] : $customer_data["ContacsUser"]["cell_phone"]]);
		echo $this->Form->input('city',["label" => "Ciudad", "value" => $type == 'natural' ? $customer_data["ClientsNatural"]["city"] : $customer_data["ContacsUser"]["city"]]);
		echo $this->Form->input('rut', array("label" => "RUT", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "5M", "class" => "dropify","required" => false  ));
		echo $this->Form->hidden('user_id',["value" => AuthComponent::user("id")]);
		echo $this->Form->hidden('prospective_user_id',["value" => $flujo]);
	?>
<?php echo $this->Form->end(__('Guardar')); ?>
</div>

