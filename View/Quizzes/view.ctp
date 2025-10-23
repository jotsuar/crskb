<?php $tipos = ["1" => "Selección múltiple", "2" => "Selección única", "3" => "Rango de valores", 5 => "Escritura libre" ]; ?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Detalle de encuesta encuesta</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr>
					<th>
						Nombre
					</th>
					<td>
						<?php echo h($quiz['Quiz']['name']); ?>
						&nbsp;
					</td>
				</tr>
				<tr>
					<th>
						Descripción
					</th>
					<td>
						<?php echo h($quiz['Quiz']['description']); ?>
						&nbsp;
					</td>
				</tr>
				<tr>
					<th>
						Fecha de inico
					</th>
					<td>
						<?php echo h($quiz['Quiz']['date_ini']); ?>
						&nbsp;
					</td>
				</tr>
				<tr>
					<th>
						Fecha de fin
					</th>
					<td>
						<?php echo h($quiz['Quiz']['date_end']); ?>
						&nbsp;
					</td>
				</tr>
				<!-- <tr>
					<th>
						Estado
					</th>
					<td>
						<?php echo ($quiz['Quiz']['state']) == "1" ? "Activa" : "Inactiva"; ?>
						&nbsp;
					</td>
				</tr> -->
				<tr>
					<th>
						Estado
					</th>
					<td>
						<?php echo ($quiz['Quiz']['type']) == "1" ? "Para el cliente" : "Interna"; ?>
						&nbsp;
					</td>
				</tr>
				<tr>
					<th>
						Fecha de creación
					</th>
					<td>
						<?php echo h($quiz['Quiz']['created']); ?>
						&nbsp;
					</td>
				</tr>
			</table>
		</div>
	</div>	
	<div class="blockwhite spacebtn20 mt-5" id="content">
		<h2 class="titleviewer">Preguntas y respuestas</h2>
		<?php if (!empty($questions)): ?>
		<div class="mt-1 mb-5">
			<div class="accordion" id="accordionExample">
				<?php foreach ($questions as $keyQuestion => $question): ?>
					<div class="card" id="<?php echo md5("Qts_".$question["Question"]["id"]) ?>">
					    <div class="card-header" id="heading<?php echo md5($question["Question"]["id"]) ?>">
						    <h5 class="mb-0">
						        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_<?php echo md5($question["Question"]["id"]) ?>" aria-expanded="true" aria-controls="collapse_<?php echo md5($question["Question"]["id"]) ?>">
							        <h2 class="text-info">
										<?php echo $keyQuestion+1 ?>. <?php echo $question["Question"]["title"] ?> - <?php echo $tipos[$question["Question"]["type"]] ?> 				
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
														<?php echo $value["title"] ?> 
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
							</div>
						</div>
					    <!-- *********************** -->
					</div>
				<?php endforeach ?>
			</div>
		</div>		
		<?php endif ?>
		</div>	
	</div>
	<?php if (empty($results)): ?>
		<div class="blockwhite spacebtn20">
			<h2 class="titleviewer">Esta encuesta todavía no ha sido contestada</h2>
		</div>
	<?php else: ?>
		<div class="blockwhite spacebtn20">
			<h2 class="titleviewer">
				Respuestas realizadas por <?php echo $quiz["Quiz"]["type"] == "1" ? "los clientes" : "los asesores"; ?>
			</h2>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-bordered table-hovered">
							<thead>
								<tr>
									<th>Pregunta</th>
									<th><?php echo $quiz["Quiz"]["type"] == "1" ? "Cliente" : "Asesor"; ?></th>
									<th>Respuesta</th>
									<th>Fecha de respuesta</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($results as $key => $value): ?>
									<tr>
										<td><?php echo $value["Question"]["title"] ?></td>
										<td><?php echo $quiz["Quiz"]["type"] == 1 ? $value["Result"]["email"] : $value["User"]["name"]  ?></td>
										<td>
											<?php echo $value["Result"]["response"] ?>
										</td>
										<td>
											<?php echo $value["Result"]["created"] ?>
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>
</div>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
?>