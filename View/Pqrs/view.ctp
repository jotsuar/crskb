<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión PQRS </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Gestión PQRS #<?php echo h($pqr['Pqr']['code']); ?></h2>
	</div>

	<div class="blockwhite spacebtn20">
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr>
					<th>Correo eléctronico</th>
					<td><?php echo h($pqr['Pqr']['email']); ?></td>
				</tr>
				<tr>
					<th>Asunto o tipo de solicitud</th>
					<td><?php echo h($pqr['Pqr']['subject']); ?></td>
				</tr>
				<tr>
					<th>Ciudad</th>
					<td><?php echo h($pqr['Pqr']['city']); ?></td>
				</tr>
				<tr>
					<th>Teléfono</th>
					<td><?php echo h($pqr['Pqr']['phone']); ?></td>
				</tr>
				<tr>
					<th>Descripción de la solicitud</th>
					<td><?php echo h($pqr['Pqr']['description']); ?></td>
				</tr>
				<tr>
					<th>Estado actual</th>
					<td><?php switch ($pqr['Pqr']['state']) {
								case '1':
									echo "Abierto";
									break;
								case '2':
									echo "Esperando respuesta del usuario";
									break;
								case '3':
									echo "Gestionado por el usuario";
									break;
								case '4':
									echo "Cerrado";
									break;
							} ?>&nbsp;
					</td>
				</tr>
				<tr>
					<th>Fecha de creación</th>
					<td><?php echo h($pqr['Pqr']['created']); ?></td>
				</tr>
				<tr>
					<th>
						Medio de respuesta
					</th>
					<td>
						<?php $medios = ["1" => "Correo electrónico", "2" => "Teléfono"]; ?>
						<?php echo h($medios[$pqr['Pqr']['response_type']]); ?>
					</td>
				</tr>
				<tr>
					<th>Documentos adjuntos</th>
					<td>
						<?php if (!empty($pqr["Pqr"]["file1"])): ?>
							<a href="<?php echo Router::url("/",true)."files/pqrs/".$pqr["Pqr"]["file1"] ?>" class="btn btn-danger" target="_blank">
								<i class="fa fa-file vtc"></i> Ver archivo
							</a>
						<?php endif ?>
						<?php if (!empty($pqr["Pqr"]["file2"])): ?>
							<a href="<?php echo Router::url("/",true)."files/pqrs/".$pqr["Pqr"]["file2"] ?>" class="btn btn-danger" target="_blank">
								<i class="fa fa-file vtc"></i> Ver archivo
							</a>
						<?php endif ?>
						<?php if (!empty($pqr["Pqr"]["file3"])): ?>
							<a href="<?php echo Router::url("/",true)."files/pqrs/".$pqr["Pqr"]["file3"] ?>" class="btn btn-danger" target="_blank">
								<i class="fa fa-file vtc"></i> Ver archivo
							</a>
						<?php endif ?>
					</td>
				</tr>
				
			</table>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Gestión realizada</h2>
		<?php if (empty($responses)): ?>
			<h3 class="mt-3 text-center text-danger">
				Todavía no hay gestión realizada
			</h3>
		<?php else: ?>
			<?php foreach ($responses as $key => $value): ?>
				<div class="table-responsive mt-2">
					<table class="table table-bordered">
						<tr>
							<th>Usuario / Asesor</th>
							<td>
								<?php if (empty($value["User"])): ?>
									<?php echo "Cliente"; ?>
								<?php else: ?>
									<?php echo $value["User"]["name"] ?>
								<?php endif ?>
							</td>
						</tr>
						<tr>
							<th>Descripción</th>
							<td>
								<?php echo $value["Responsepqr"]["description"] ?>
							</td>
						</tr>
						<tr>
							<th>Archivo adjunto</th>
							<td>
								<?php if (!empty($value["Responsepqr"]["file"])): ?>
									<a href="<?php echo Router::url("/",true)."files/responses/".$value["Responsepqr"]["file"] ?>" class="btn btn-danger" target="_blank">
										<i class="fa fa-file vtc"></i> Ver archivo
									</a>
								<?php endif ?>
							</td>
						</tr>
					</table>
				</div>
			<?php endforeach ?>
		<?php endif ?>
	</div>
	<?php if ($pqr["Pqr"]["state"] != 4 ): ?>
		<?php if ( (AuthComponent::user("id") && $pqr["Pqr"]["state"] != 2) || (!AuthComponent::user("id") && $pqr["Pqr"]["state"] == 2 ) ): ?>			
			<div class="blockwhite spacebtn20">
				<h2 class="titleviewer mb-3">Gestionar PQRS</h2>
				<?php echo $this->Form->create('Responsepqr',array('data-parsley-validate'=>true,"type" => "file","autocomplete" => "off")); ?>

					<?php
						echo $this->Form->input('id_pqr',["type" => "hidden", "value" => $this->Utilities->encryptString($pqr["Pqr"]["id"])]);
						if (!AuthComponent::user("role")) {
							echo $this->Form->input('state',["type" => "hidden", "value" => 3 ]);
						}else{
							echo $this->Form->input('state',["label" => "Siguiente estado", "options" => ["2" => "Esperando respuesta del usuario", "4" => "Finalizado" ] ]);
							echo $this->Form->input('response_type',["type" => "hidden", "value" => $pqr['Pqr']['response_type'] ]);
						}
						
						
						echo $this->Form->input('user_id',["type" => "hidden", "value" => AuthComponent::user("id") ? AuthComponent::user("id") : null ]);
						echo $this->Form->input('description',["label"=>"Descripción",]);
						echo $this->Form->input('file',["label" => "Archivo adjunto", "data-parsley-fileextension" => "1","type" =>"file" ]);
					?>
				<?php echo $this->Form->end(__('Gestionar')); ?>
			</div>
		<?php endif ?>
	<?php endif ?>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
	echo $this->Html->script("controller/pqrs/admin.js?".rand(),						array('block' => 'AppScript'));
?>