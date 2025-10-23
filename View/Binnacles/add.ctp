<div class="binnacles form">
<?php echo $this->Form->create('Binnacle'); ?>
	<?php
		echo $this->Form->input('note',["label"=>"Nota del trabajo realizado"]);
		echo $this->Form->input('technical_service_id',["value"=>$technical_id,"type"=>"hidden"]);
		echo $this->Form->input('user_id',["value" => AuthComponent::user("id"),"type" => "hidden"]);
	?>

	<div class="row">
		<div class="col-md-6">
			<div class="form-group mt-1">
				<label for="DateIniBinnacle">Fecha y hora de inicio</label>
				<?php echo $this->Form->text('date_ini',["required","type"=>"datetime-local", "class"=>"form-control"]); ?>				
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group mt-1">
				<label for="DateEndBinnacle">Fecha y hora de fin</label>
				<?php echo $this->Form->text('date_end',["required","type"=>"datetime-local", "class"=>"form-control" ]); ?>
			</div>
		</div>
	</div>
	<div class="form-group mt-3">
		<input type="submit" class="btn btn-success centerbtn mt-3" value="Guardar Bitácora">
	</div>
</div>
