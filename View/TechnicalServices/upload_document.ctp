<div class="row">
	<div class="col-md-12">
		<h2 class="text-center">Cargar documento para la orden de servicio: <b><?php echo $technicalService["TechnicalService"]["code"] ?></b></h2>
	</div>
	<div class="col-md-12">
		<?php echo $this->Form->create('TechnicalService',["type" => "file",'data-parsley-validate'=>true]); ?>

			<?php echo $this->Form->input('id',["type" => "hidden","value" => $technicalService["TechnicalService"]["id"] ]); ?>
			<div class="form-group">
				<?php echo $this->Form->input('document',["required"=>true,"label" => "Documento adjunto", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "5M", "class" => "dropify","required" ]); ?>				
			</div>

		<div class="form-group">
			<input type="submit" class="btn btn-success btn-block" value="Cargar documento">
		</div>


	<?php echo $this->Form->end(); ?>
	</div>						
</div>