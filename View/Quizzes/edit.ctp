<?php $tipos = ["1" => "Selección múltiple", "2" => "Selección única", "3" => "Rango de valores", 5 => "Escritura libre" ]; ?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Editar encuesta</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<?php echo $this->Form->create('Quiz',array('data-parsley-validate'=>true,)); ?>
			<?php
				echo $this->Form->input('id',["label" => "id"]);
				echo $this->Form->input('name',["label" => "Nombre"]);
				echo $this->Form->input('type',  ['label' => "Tipo de encuesta ","options" => ["1" => "Para el cliente", "0" => "Interna", ], "empty" => "Seleccionar", "required" ] );
				echo $this->Form->input('description', ["label" => "Descripción de la encuesta"]);				
				echo $this->Form->input('products',  ['label' => "¿Tendrá productos? ","options" => ["1" => "SI", "0" => "No", ], "default" => 0 ,"type" => "hidden", "value" => 0 ] );
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
				<input type="submit" value="Editar información de encuesta" class="btn btn-success">
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
	<div class="blockwhite spacebtn20 mt-5" id="content">
		<h2 class="titleviewer">Preguntas y respuestas</h2>
		<?php if (!empty($questions)): ?>
		<div class="mt-5 mb-5">
			<div class="accordion" id="accordionExample">
				<?php foreach ($questions as $keyQuestion => $question): ?>
					<div class="card" id="<?php echo md5("Qts_".$question["Question"]["id"]) ?>">
					    <div class="card-header" id="heading<?php echo md5($question["Question"]["id"]) ?>">
						    <h5 class="mb-0">
						        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_<?php echo md5($question["Question"]["id"]) ?>" aria-expanded="true" aria-controls="collapse_<?php echo md5($question["Question"]["id"]) ?>">
							        <h2 class="text-info">
										<?php echo $keyQuestion+1 ?>. <?php echo $question["Question"]["title"] ?> - <?php echo $tipos[$question["Question"]["type"]] ?> 
										<a href="<?php echo $this->Html->url(["controller" => "questions", "action" => "edit", $this->Utilities->encryptString($question["Question"]["id"]),$this->Utilities->encryptString($this->request->data["Quiz"]['id']) ]) ?>" class="btn btn-danger editQuestion">
											<i class="fa fa-pencil vtc"></i>
										</a>
										<?php if ($this->request->data["Quiz"]["state"] == 1): ?>
											<a href="<?php echo $this->Html->url(["controller" => "questions", "action" => "borrar", $this->Utilities->encryptString($question["Question"]["id"]),$this->Utilities->encryptString($this->request->data["Quiz"]['id']) ]) ?>" class="btn btn-info deleteQuestion" data-id="<?php echo md5("Qts_".$question["Question"]["id"]) ?>">
												<i class="fa fa-trash vtc"></i>
											</a>
										<?php endif ?>						
									</h2>
						        </button>
						    </h5>
					    </div>
					    <!-- *********************** -->
					    <div id="collapse_<?php echo md5($question["Question"]["id"]) ?>" class="collapse" aria-labelledby="heading<?php echo md5($question["Question"]["id"]) ?>" data-parent="#accordionExample">
						    <div class="card-body">
						        <?php if (!empty($question["Answer"])): ?>
									<div class="p-1">
										<ul>
											<?php foreach ($question["Answer"] as $key => $value): ?>
												<?php if ($value["state"] == 1): ?>													
													<li class="list-group-item p-1 mb-1">
														<?php echo $value["title"] ?> <a href="<?php echo $this->Html->url(["controller" => "answers", "action" => "edit", $this->Utilities->encryptString($value['id']),$this->Utilities->encryptString($this->request->data["Quiz"]['id']) ]) ?>" class="btn btn-warning p-0 editAnswer"> <i class="fa fa-pencil vtc"></i> </a>
														<?php if ($this->request->data["Quiz"]["state"] == 1): ?>
															<a href="<?php echo $this->Html->url(["controller" => "answers", "action" => "borrar", $this->Utilities->encryptString($value['id']),$this->Utilities->encryptString($this->request->data["Quiz"]['id']) ]) ?>" class="btn btn-info deleteAnswer">
																<i class="fa fa-trash vtc"></i>
															</a>
														<?php endif ?>
														<?php if (!empty($value["range"]) && $question["Question"]["type"] == 3 ): ?> 
															<br>
															<?php for ($i=1; $i <= $value["range"]; $i++): ?>
																<?php echo $i ?>
																<input type="radio" disabled>
															<?php endfor; ?>
														<?php endif ?>
														<?php if ($question["Question"]["type"] == 5 ): ?> 
															<br>
															<textarea name="" id="" disabled cols="30" rows="10" class="form-control"></textarea>
														<?php endif ?>
													</li>
												<?php endif ?>
											<?php endforeach ?>
										</ul>
									</div>
								<?php endif ?>
								<?php if ((!empty($question["Answer"]) && $question["Question"]["type"] == 3) || (!empty($question["Answer"]) && $question["Question"]["type"] == 5)  ): ?>
									
								<?php else: ?>
									<div class="pl-5">
										<?php echo $this->Form->create('Answer',["url" => ["controller"=>"answers","action" => "add"]]); ?>
											<h3 class="text-center my-2">Formulario de creación respuestas</h3>
											<?php if (in_array($question["Question"]["type"], [1,2]) && count($question["Answer"]) < 2): ?>
												<p class="text-danger">
													<b>NOTA: Las preguntas de selección múltiple o unica respuesta deben tener mínimo dos respuestas</b>
												</p>
											<?php endif ?>
											<div class="row">
												<div class="col-md-12">
													<?php 
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
									</div>
								<?php endif ?>
							</div>
						</div>
					    <!-- *********************** -->
					</div>
				<?php endforeach ?>
			</div>
		</div>		
		<?php endif ?>
		<div class="mt-5">
			<hr>
			<?php echo $this->Form->create('Question',["url" => ["controller"=>"questions","action" => "add"] ]); ?>
				<h3>Formulario de creación preguntas</h3>
				<?php echo $this->Form->input('quiz_id',["type" => "hidden","value" => $this->request->data["Quiz"]["id"] ]); ?>
				<div class="row">
					<div class="col-md-6">
						<?php echo $this->Form->input('title',["label"=>"Título de la pregunta"]); ?>
					</div> 
					<div class="col-md-6">
						<?php echo $this->Form->input('type',["label" => "Tipo de pregunta", "options" =>  $tipos ]); ?>
					</div>
					<div class="col-md-4">
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
			</div>
		</div>	
	</div>
</div>


<div class="modal fade " id="modalEditarPregunta" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Edición de pregunta</h5>
      </div>
      <div class="modal-body" id="cuerpoPregunta">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade " id="modalEditarRespuesta" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Edición de respuesta</h5>
      </div>
      <div class="modal-body" id="cuerpoRespuesta">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php echo $this->Html->css(array('lib/jquery.typeahead.css'), array('block' => 'AppCss'));?>
<?php 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/quiz/admin.js?".rand(),						array('block' => 'AppScript'));
?>
<?php 
	
		$whitelist = array(
            '127.0.0.1',
            '::1'
        ); 

 ?>



<script>
	function updateTextInput(val) {
          document.getElementById('textInput').value = "1 - "+ val; 
        }
    document.getElementById('textInput').value = "1 - "+ $("#AnswerRange").val(); 
</script>