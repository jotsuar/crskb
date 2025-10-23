
<div class="clientsLegalsForm form">
	<?php if (isset($this->request->data["flujo"])): ?>
	
		<a href="#" class="returnContactProspective btn btn-success"> <i class="fa fa-arrow-left vtc"></i> Regresar</a>

	<?php endif ?>

	<?php echo $this->Form->create('Customer',array("id" => "formGeneralCustomer")); ?>	
		<div class="row">
			<div class="col-md-12">
				<?php if (AuthComponent::user("role") == "Gerente General" || in_array(AuthComponent::user("email"),["ventas@kebco.co","ventas2@almacendelpintor.com","gestion@kebco.co"]) ): ?>
					<?php echo $this->Form->input('nacional',array('label' => "Nacionalidad cliente","options" => ["1" => "Nacional","0" => "Internacional"],"default" => 1 ));?>
				<?php else: ?>
					<?php echo $this->Form->input('nacional',array('label' => "Nacionalidad cliente","type"=>"hidden","value" => 1 ));?>
				<?php endif ?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('type',array('label' => "Tipo de cliente","options" => Configure::read("CUSTOMERS_TYPE_TEXT"), "value" => $this->request->data["type"]));?>
			</div>
			
			<div class="col-md-6">
				<?php echo $this->Form->input('identification',array('label' => "NIT (Sin dígito de verificación) o CC",'placeholder' => 'NIT (Sin dígito de verificación) o CC')); ?>
			</div>
			<div class="col-md-6 divCustomerEmail" style="display: <?php echo $this->request->data["type"] == 2 ? "none" : "block"  ?>">
				<?php			
					echo $this->Form->input('email',array('label' => "Correo eléctronico cliente",'placeholder' => 'Email'));
				?>
			</div>
			<div class="col-md-6">
				<a id="<?php echo !isset($this->request->data["flujo"]) ? "btn_find_existencia_customr" : "btn_validate_exist_customer" ?>" data-placement="right" data-toggle="tooltip" title="Comprobar que no exita el cliente" class="btn btn-block btn-info btn-outline-success mt-4">
					Validar existencia <i class="fa fa-refresh  vtc"></i>
				</a>
			</div>
		</div>	
		
		
		
	</form>
</div>