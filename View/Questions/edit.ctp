<?php $tipos = ["1" => "Selección múltiple", "2" => "Selección única", "3" => "Rango de valores", 5 => "Escritura libre" ]; ?>
<?php echo $this->Form->create('Question'); ?>
	<h3>Formulario de edición preguntas</h3>
	<?php echo $this->Form->input('quiz_id',["type" => "hidden" ]); ?>
	<?php echo $this->Form->input('id',["type" => "hidden" ]); ?>
	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Form->input('title',["label"=>"Título de la pregunta"]); ?>
		</div> 
		<div class="col-md-12 <?php echo $totalAnswers == 0 ? "" : "d-none" ?>">
			<?php echo $this->Form->input('type',["label" => "Tipo de pregunta", "options" =>  $tipos ]); ?>
		</div>
		<div class="col-md-12">
			<?php echo $this->Form->input('required',["label" => "¿Es requerido?", "options" => ["1" => "SI", "0" => "NO"] ]); ?>
		</div>
		<div class="col-md-4 d-none">
			<?php echo $this->Form->input('have_points',["label" => "Tendrá asignación de puntos", "options" => ["1" => "SI", "0" => "NO",],"type" => "hidden"]); ?>
		</div>
		<div class="col-md-4 d-none">
			<?php echo $this->Form->input('points',["label" => "Puntor para asignar","value" => "0", "min" => 0,"type" => "hidden"]); ?>
		</div>
	</div>
	<div class="form-group mt-3">
		<input type="submit" value="Guardar pregunta" class="btn btn-success">
	</div>
<?php echo $this->Form->end(); ?>