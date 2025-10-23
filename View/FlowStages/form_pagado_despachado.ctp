<p class="copiealert">Hola <?php echo AuthComponent::user('name'); ?> recuerda ingresar esta información de manera precisa para que el área de Logística pueda enviar sin problemas tu pedido</p>
<?php echo $this->Form->create('FlowStage'); ?>
	
	<div class="form-row"> 
		<div class="col-6">
			<?php echo $this->Form->hidden('flujo_id',array('value' => $datos['ProspectiveUser']['id']));?>
			<?php echo $this->Form->input('contact',array('placeholder' => 'Nombre de la persona que recibe el envío','label' => 'Quién recibe?'));?>
		</div>
		<div class="col-6">
			<?php echo $this->Form->input('address',array('placeholder' => 'Dirección de envío','label' => 'Por favor detalla la dirección de envío'));?>
		</div>
	</div>
	<?php 
		echo $this->Form->input('information',array('type' => 'textarea','maxlength'=>'400','placeholder' => 'Información adicional para localizar más fácil la dirección','label' => 'Completa la dirección del cliente'));
	?>
	<div class="form-row"> 
		<div class="col-6">
			<?php echo $this->Form->input('telephone',array('label' => "Teléfono",'placeholder' => 'Teléfono')); ?>
		</div>
		<div class="col-6">
			<?php echo $this->Form->input('flete',array('label' => 'Flete del envio:', 'options' => $flete)); ?>
		</div>
	</div>
	<?php echo $this->Form->input('copias_email'); ?>
</form>

