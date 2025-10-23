<?php echo $this->Form->create('Answer'); ?>
	<h3 class="text-center my-2">Formulario de edición respuestas</h3>
	<div class="row">
		<div class="col-md-12">
			<?php 
				echo $this->Form->input('id',["label" => "Título de respuesta"]);
				echo $this->Form->input('title',["label" => "Título de respuesta"]);
				echo $this->Form->input('question_id',["type" => "hidden", "value" => $question["Question"]["id"] ]);
			?>
		</div>										
		<?php if ($question["Question"]["type"] == "3"): ?>
			<div class="col-md-12">
				<?php echo $this->Form->input('range', ["label"=>"Rango","type" => "range", "min" => 1, "step"=>1, "max" => 50,'onchange'=>'updateTextInput(this.value);']); ?>
				<input type="text" id="textInput" value="" class="text-center form-control" readonly style="margin-top: -20px">
		<?php endif ?>
	</div>
	<div class="form-group mt-3">
		<input type="submit" value="Guardar respuesta" class="btn btn-primary">
	</div>
<?php echo $this->Form->end(); ?>