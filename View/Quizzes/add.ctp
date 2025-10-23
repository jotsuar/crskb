<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Crear encuesta</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<?php echo $this->Form->create('Quiz',array('data-parsley-validate'=>true,)); ?>

			<?php
				echo $this->Form->input('name',["label" => "Nombre"]);
				echo $this->Form->input('description', ["label" => "Descripción de la encuesta"]);				
				echo $this->Form->input('products',  ['label' => "¿Tendrá productos? ","options" => ["1" => "SI", "0" => "No", ], "default" => 0,"type" => "hidden", "value" => 0 ] );
				echo $this->Form->input('type',  ['label' => "Tipo de encuesta ","options" => ["1" => "Para el cliente", "0" => "Interna", ], "empty" => "Seleccionar", "required" ] );
			?>
			<div class="form-group">
				<label for="QuizDateIni">Fecha de inicio</label>
				<?php echo $this->Form->text('date_ini',["label" => "Fecha de inicio","type" => "date","class" => "form-control"]);
				?>
			</div>
			<div class="form-group mb-4">
				<label for="QuizDateEnd">Fecha de fin</label>
				<?php echo $this->Form->text('date_end',["label" => "Fecha de fin","type" => "date","class" => "form-control"]);  ?>
			</div>
			<div class="form-group mt-3">
				<input type="submit" value="Crear encuesta" class="btn btn-success">
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>

<div class="quizzes form">

</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
?>