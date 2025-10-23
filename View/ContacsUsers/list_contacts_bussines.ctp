<h2>Contactos registrados</h2>
<br>

<?php if (!empty($consesiones)): ?>
	<div class="accordion" id="accordionExample">

		<?php foreach ($consesiones as $key => $value): ?>
			 <div class="card">
			    <div class="card-header p-0" id="heading_<?php echo $key ?>">
			      <h2 class="mb-0">
			        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse_<?php echo $key ?>" aria-expanded="true" aria-controls="collapse_<?php echo $key ?>">
			          <?php echo $value ?>
			        </button>
			      </h2>
			    </div>

			    <div id="collapse_<?php echo $key ?>" class="collapse <?php echo $key == 0 ? 'show' : '' ?>" aria-labelledby="heading_<?php echo $key ?>" data-parent="#accordionExample">
			      <div class="card-body">
			        <!--  -->
			        <ul class="listcontacts">
			        	<?php $id = $key == 0 ? null : $key; ?>
						<?php if (count($users) > 0){ ?>
							<?php  foreach ($users as $value) { ?>
								<?php if ($value["ContacsUser"]["concession_id"] == $id): ?>
									<li>	
										<span><?php echo $value['ContacsUser']['name'] ?> </span>
										<span class="actionscontact">
											<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Agregar requerimiento" data-uid='<?php echo $value['ContacsUser']['id'] ?>' id='btn_agregar'>
												<i class="fa fa-plus-circle"></i>
											</a>
											<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Ver contacto" data-uid='<?php echo $value['ContacsUser']['id'] ?>' id='btn_vista'>
									        	<i class="fa fa-fw fa-eye"></i>
									        </a>
									        <?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística"): ?>
									        	
												<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar contacto" data-uid='<?php echo $value['ContacsUser']['id'] ?>' class="btn_editar_contacto" style="float: none !important;">
										        	<i class="fa fa-fw fa-pencil" ></i>
										        </a>
									        <?php endif ?>
									        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php echo $value["ContacsUser"]["state"] == 1 ? "Deshabilitar contacto" : "Habilitar contacto" ?>" data-uid='<?php echo $value['ContacsUser']['id'] ?>' class='btn_changeState' >
									        	<?php if ($value["ContacsUser"]["state"] == 1): ?>
									        		<i class="fa fa-trash"></i>
									        	<?php else: ?>
									        		<i class="fa fa-check"></i>		        			        		
									        	<?php endif ?>
									        </a>
									    </span>   
							        </li>
								<?php endif ?>
							<?php } ?>
							<?php } else {?>
								<p>Aún no se registran contactos para el cliente juridico</p>
							<?php } ?>
						</ul>
			        <!--  -->
			      </div>
			    </div>
			  </div>
		<?php endforeach ?>

	 
	  
	  
	</div>
<?php else: ?>		

	<ul class="listcontacts">
	<?php if (count($users) > 0){ ?>
		<?php  foreach ($users as $value) { ?>
			<li>	
				<span><?php echo $value['ContacsUser']['name'] ?> </span>
				<span class="actionscontact">
					<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Agregar requerimiento" data-uid='<?php echo $value['ContacsUser']['id'] ?>' id='btn_agregar'>
						<i class="fa fa-plus-circle"></i>
					</a>
					<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Ver contacto" data-uid='<?php echo $value['ContacsUser']['id'] ?>' id='btn_vista'>
			        	<i class="fa fa-fw fa-eye"></i>
			        </a>
					<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar contacto" data-uid='<?php echo $value['ContacsUser']['id'] ?>' class="btn_editar_contacto" style="float: none !important;">
			        	<i class="fa fa-fw fa-pencil" ></i>
			        </a>
			        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="<?php echo $value["ContacsUser"]["state"] == 1 ? "Deshabilitar contacto" : "Habilitar contacto" ?>" data-uid='<?php echo $value['ContacsUser']['id'] ?>' class='btn_changeState' >
			        	<?php if ($value["ContacsUser"]["state"] == 1): ?>
			        		<i class="fa fa-trash"></i>
			        	<?php else: ?>
			        		<i class="fa fa-check"></i>		        			        		
			        	<?php endif ?>
			        </a>
			    </span>   
	        </li>
		<?php } ?>
		<?php } else {?>
			<p>Aún no se registran contactos para el cliente juridico</p>
		<?php } ?>
	</ul>
<?php endif; ?>
