<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
?>
<?php $tipos = ["1" => "Selección múltiple", "2" => "Selección única", "3" => "Rango de valores", 5 => "Escritura libre" ]; ?>
<?php if (!$valid): ?>
	<div class="col-md-12">
		<div class="blockwhite spacebtn20">
			<h2 class="titleviewer"> Está encuesta no es válida </h2>
		</div>	
	</div>
<?php else: ?>

	<div class="col-md-12">
		<?php echo $this->Form->create('Result',array('data-parsley-validate'=>true,)); ?>
		<div class="blockwhite spacebtn20">
			<h2 class="titleviewer"> <?php echo h($quiz['Quiz']['name']); ?> </h2>
			<br>
			<small>
				Por favor diligencie la siguientes preguntas, para nosotros es muy importante su respuesta ya que nos ayudará a mejorar cada día.
			</small>
		</div>	
		<?php if (AuthComponent::user("id") ): ?>
			<?php echo $this->Form->input("Result.user_id",["type" => "hidden", "value" => AuthComponent::user("id")]) ?>
		<?php else: ?>
			<?php 

				if (isset($this->request->query["m"]) && filter_var($this->Utilities->desencriptarCadena($this->request->query["m"]), FILTER_VALIDATE_EMAIL) ) {
					$value = $this->Utilities->desencriptarCadena($this->request->query["m"]);
				}else{
					$value = "";
				}

				if (isset($this->request->query["v"])){
					$valor = base64_decode($this->request->query["v"]);
				}else{
					$valor = "";
				}
			 ?>
			<?php if (!empty($value)): ?>
				<?php echo $this->Form->input("Result.email",["class" => "form-control","label" => "Por favor ingrese su correo electrónico","required","value" => $value, "type" => "hidden"]) ?>
			<?php else: ?>				
				<div class="blockwhite spacebtn20">
					<?php echo $this->Form->input("Result.email",["class" => "form-control","label" => "Por favor ingrese su correo electrónico","required"]) ?>
					
				</div>	
			<?php endif ?>
		<?php endif ?>
		<?php $num = 1; ?>
		<?php foreach ($questions as $key => $question): ?>
			<div class="blockwhite spacebtn20">
				<p class="titleviewer mb-0"> <?php echo h($question['Question']['title']); ?> </p><br>
				<small>
				<?php if ($question["Question"]["type"] == 1): ?>
					Por favor seleccione una o más respuestas.
				<?php elseif ($question["Question"]["type"] == 2 ): ?>
					Por favor seleccione una de las respuestas.
				<?php elseif ($question["Question"]["type"] == 3 ): ?>
					Por favor seleccione una respuesta en el rango de valores.
				<?php elseif ($question["Question"]["type"] == 5 ): ?>
					Por favor escriba su respuesta
				<?php endif ?>
				</small>
				<hr>
				<div class="row mt-1">
					<div class="col-md-12">
					<?php 
						$options = [];
						$label = [];
						$type = [];
					?>
					<?php foreach ($question["Answer"] as $key => $answer): ?>
						<?php if ($question["Question"]["type"] == 1): ?>
							<?php $options[$answer["id"]] = $answer["title"]; ?>
						<?php elseif ($question["Question"]["type"] == 2 ): ?>
							<?php if (!empty($valor) && strtolower($valor) == strtolower($answer["title"])): ?>
								<?php $valor = $answer["id"]; ?>
							<?php endif ?>
							<?php $options[$answer["id"]] = $answer["title"]; ?>
						<?php elseif ($question["Question"]["type"] == 3 ): ?>
							<?php $options = array_combine(range(1,$answer["range"]), range(1,$answer["range"]) ); ?>
						<?php elseif ($question["Question"]["type"] == 5 ): ?>
							Por favor escriba su respuesta
						<?php endif ?>
					<?php endforeach ?>
					
					<?php if ($question["Question"]["type"] == 1): ?>
						<?php foreach ($question["Answer"] as $key => $answer): ?>
							<?php echo $this->Form->input("Result.".$question["Question"]["id"].".",[ "type" => "checkbox","value"=> $answer["id"],"label" => $answer["title"] ,"required" => $question["Question"]["required"] == 1 ? true : false, "div" => ["class" => "pl-0 custom-control custom-checkbox"],"class" => "mr-2","hiddenField" => false ]) ?>
						<?php endforeach ?>
					<?php elseif ($question["Question"]["type"] == 2 ): ?>					
							<?php echo $this->Form->input("Result.".$question["Question"]["id"],["empty" => "Seleccionar respuesta", "class" => "form-control", "options" => $options,"default" => $valor, "label" => false,"required" => $question["Question"]["required"] == 1 ? true : false ]) ?> 
					<?php elseif ($question["Question"]["type"] == 3 ): ?>
						<?php echo $this->Form->input("Result.".$question["Question"]["id"]."R".$answer["id"],[ "class" => "w-auto d-inline mx-2","legend"=>false, "type" => "radio", "options" => $options,"default" => $valor, "label" => false,"required" => $question["Question"]["required"] == 1 ? true : false ]) ?> 
					<?php elseif ($question["Question"]["type"] == 5 ): ?>
						<?php echo $this->Form->input("Result.".$question["Question"]["id"]."T".$answer["id"],["type" => "textarea", "class" => "form-control", "label" => false,"required" => $question["Question"]["required"] == 1 ? true : false ]) ?> 
					<?php endif ?>

					</div>
				</div>

			</div>	
		<?php endforeach ?>
		<div class="blockwhite spacebtn20 pb-5">
			<input type="submit" class="btn btn-success float-right" value="Enviar encuesta">
		</div>
		<?php echo $this->Form->end(); ?>
	</div>

<?php endif ?>