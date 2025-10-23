<?php echo $this->Form->create('TechnicalService',array('data-parsley-validate'=>true,'id'=>'form_serviceSend','enctype'=>'multipart/form-data')); ?>
	<div class="row">
		<div class="col-md-12">
			<h2 class="text-center">
				<?php 

					$name    = is_null($technical["ClientsNatural"]["name"]) ? $technical["ContacsUser"]["name"]. " - ".$technical["ClientsLegal"]["name"] : $technical["ClientsNatural"]["name"];

				?>
				Informar demora al cliente <b><?php echo $name ?></b>, sobre el servicio: <b><?php echo $technical["TechnicalService"]["code"] ?></b>
			</h2>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<?php echo $this->Form->input('id'); ?>
				<?php echo $this->Form->input('message',["label" => "Mensaje al cliente","class" => "form-control","type"=>"textarea" ]); ?>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<input type="submit" class="btn btn-success" value="Enviar mensaje">
			</div>
		</div>
	</div>
	
<?php echo $this->Form->end(); ?>