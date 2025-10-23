<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-green big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">BIBLIOTECA CRM KEBCO SAS </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de categorías y archivos y artículos</h2>
              <a href="<?php echo $this->Html->url(array("controller" => "pages", "action" => "preguntas")) ?>" class="btn btn-info text-white" style="height: 28px; padding-top: 4px !important;"  target="_blank">
                <i class="fa fa-x vtc fa-question-circle"></i> 
                Ayuda CRM
              </a>

			</div>
			<div class="col-md-6 text-right">
				<?php if ($permision): ?>
					<a href="<?php echo $this->Html->url(array('controller'=>'carpetas','action'=>'add')) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva categoría</span></a>					
				<?php endif ?>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por nombre o razón social">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por nombre">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-3">
				<div class="blockwhite">
					<h2 class="text-info text-center mb-3">
						Categorías <i class="fa fa-list vtc"></i>
					</h2>
					<!-- <hr> -->
					<ul class="list-group">
						<li class="list-group-item pointer carpetaLi <?php echo $ids == "" ? "active" : "" ?>">
					  		<a class="w-100 <?php echo $ids == "" ? "text-white" : "" ?>" href="<?php echo $this->Html->url(["controller"=> "carpetas","action" => "index" ]) ?>">
					  			<i class="fa fa-folder vtc"></i> Todas las categorías
					  		</a>
					  	</li>
						<?php foreach ($carpetas as $key => $value): ?>
						  	<li class="list-group-item pointer carpetaLi <?php echo $ids == $value["Carpeta"]["id"] ? "active" : "" ?>">
						  		<a class="w-100 <?php echo $ids == $value["Carpeta"]["id"] ? "text-white" : "" ?>" href="<?php echo $this->Html->url(["controller"=> "carpetas","action" => "index", $this->Utilities->encryptString($value["Carpeta"]["id"]) ]) ?>">
						  			<i class="fa fa-folder vtc"></i> <?php echo $value["Carpeta"]["name"] ?>
						  		</a>

						  		
						  	</li>
						  	<?php if (!empty($value["Sub"])): ?>
						  		<?php foreach ($value["Sub"] as $keySub => $valueSub): ?>
						  			<li class="list-group-item pointer pl-5 carpetaLi <?php echo $ids == $valueSub["id"] ? "active" : "" ?>">
						  				<a class="w-100 <?php echo $ids == $valueSub["id"] ? "text-white" : "" ?>" href="<?php echo $this->Html->url(["controller"=> "carpetas","action" => "index", $this->Utilities->encryptString($valueSub["id"]) ]) ?>">
								  			<i class="fa fa-minus vtc"></i> <?php echo $valueSub["name"] ?>
								  		</a>
					  				</li>
						  		<?php endforeach ?>
					  		<?php endif ?>					  		
						<?php endforeach ?>
					</ul>
				</div>
				
			</div>
			<div class="col-md-9">
				<div class="blockwhite">
					<div class="row">
						<?php if (isset($capetaInfo)): ?>							
							<div class="col-md-12">
								<p class="text-left mb-0">
									<b>Categoría:</b> <?php echo $capetaInfo["Carpeta"]["name"] ?> 
									<?php if ($permision): ?>
										<a href="<?php echo $this->Html->url(["controller"=> "carpetas","action" => "edit", $this->Utilities->encryptString($capetaInfo["Carpeta"]["id"]) ]) ?>" data-toggle="tooltip" title="Editar Carpeta" class="btn btn-warning">
											<i class="fa fa-pencil vtc"></i>
										</a>	
									<?php endif ?>
								</p>
								<p class="text-left mb-0">
									<b>Descripción:</b> <?php echo $capetaInfo["Carpeta"]["description"] ?>
								</p>
								<p class="text-left mb-0">
									<b>Fecha de creación:</b> <?php echo $capetaInfo["Carpeta"]["created"] ?>
								</p>
								<?php if (!empty($capetaInfo["Sub"])): ?>
									<p class="text-left mb-0">
										<b>Sub Categorías: </b> 
										<?php foreach ($capetaInfo["Sub"] as $keySubData => $valueSubData): ?>
											<a class="btn btn-outline-info" href="<?php echo $this->Html->url(["controller"=> "carpetas","action" => "index", $this->Utilities->encryptString($valueSubData["id"]) ]) ?>">
									  			<?php echo $valueSubData["name"] ?>
									  		</a>
										<?php endforeach ?>
									</p>
								<?php endif ?>
								<?php if (!empty($capetaInfo["Father"]["id"])): ?>
					  				<b>Categoría principal: </b> 
					  				<a class="btn btn-outline-info" href="<?php echo $this->Html->url(["controller"=> "carpetas","action" => "index", $this->Utilities->encryptString($capetaInfo["Father"]["id"]) ]) ?>">
							  			<?php echo $capetaInfo["Father"]["name"] ?>
							  		</a>
					  			<?php endif ?>
								<?php if ($permision): ?>
									<p>
										<a href="<?php echo $this->Html->url(["controller" => "documents", "action" => "add", $this->Utilities->encryptString($capetaInfo["Carpeta"]["id"])]) ?>" class="btn btn-primary">
											Subir archivo <i class="fa fa-file-o vtc"></i>
										</a>
										<a href="<?php echo $this->Html->url(["controller" => "blogs", "action" => "add", $this->Utilities->encryptString($capetaInfo["Carpeta"]["id"])]) ?>" class="btn btn-primary">
											Crear artículo <i class="fa fa-newspaper-o vtc"></i>
										</a>
									</p>
								<?php endif ?>
								<hr>
							</div>
						<?php endif ?>
						<div class="col-md-12">
							<h2 class="text-center">
								Archivos y/o artículos
							</h2>
							<hr>

							<div class="contenido">
								<?php if (empty($detalles)): ?>
									<h3 class="text-danger text-center">
										No hay archivos ni artículos
									</h3>
								<?php else: ?>
									<div class="cards w-100">
										<div class="row">
											
  
										<?php foreach ($detalles as $key => $value): ?>
											<div class="col-md-3">
												
												<?php 
													if (is_null($value["CarpetaDetalle"]["document_id"])) {
														$url = $this->Html->url(["controller"=>"blogs","action" => "view", $this->Utilities->encryptString($value["CarpetaDetalle"]["blog_id"]) ]);
														$target = "self";
													}else{
														$url = $this->Html->url("/files/documents/".$value["Document"]["file"],true);
														$target = "_blank";
													}
												?>

												<a href="<?php echo $url ?>" target="<?php echo $target ?>">

													<div class="card">
														<?php if ($value["CarpetaDetalle"]["document_id"] == null): ?>
															<?php if (!empty($value["Blog"]["imagen_1"])): ?>
												      			<img src="<?php echo $this->Html->url("/files/blogs/".$value["Blog"]["imagen_1"]) ?>" alt="" class="img-fluid w-100" style="max-height: 180px; min-height: 180px;">
												      		<?php else: ?>
												      			<i class="card-img-top fa fa-5x fa-newspaper-o mt-2 text-center"></i>
												      		<?php endif ?>
												      		
												      	<?php else: ?>
												      		<?php if (!empty($value["Document"]["image"])): ?>
												      			<img src="<?php echo $this->Html->url("/files/documents/".$value["Document"]["image"]) ?>" alt="" class="img-fluid w-100" style="max-height: 180px; min-height: 180px;">
												      		<?php else: ?>
												      			<i class="card-img-top fa fa-5x fa-file mt-2 text-center"></i>
												      		<?php endif ?>
												      	<?php endif ?>
													    
													    <div class="card-body" style="min-height: 200px; max-height: 300px;">
														    <h5 class="card-title">
														      	<?php if ($value["CarpetaDetalle"]["document_id"] == null): ?>
														      		<?php echo $value["Blog"]["name"] ?>
														      	<?php else: ?>
														      		<?php echo $value["Document"]["name"] ?>
														      	<?php endif ?>
														    </h5>
													      	<p class="card-text">
													      		<p>
													      			<b>Categoría: </b> 
													      			<?php if ($value["CarpetaDetalle"]["document_id"] == null): ?>
														      			<?php echo $this->Text->truncate(h($value["Carpeta"]["name"]), 135,array('ellipsis' => '...','exact' => true)); ?>&nbsp;
														      		<?php else: ?>
														      			<?php echo $this->Text->truncate(h($value["Carpeta"]["name"]), 135,array('ellipsis' => '...','exact' => true)); ?>&nbsp;
														      		<?php endif ?>
													      		</p>
													      		<?php if ($value["CarpetaDetalle"]["document_id"] == null): ?>
													      			<?php echo $this->Text->truncate(h($value["Blog"]["short_description"]), 135,array('ellipsis' => '...','exact' => true)); ?>&nbsp;
													      		<?php else: ?>
													      			<?php echo $this->Text->truncate(h($value["Document"]["description"]), 135,array('ellipsis' => '...','exact' => true)); ?>&nbsp;
													      		<?php endif ?>
													      	</p>
													    </div>
													    <div class="card-footer">
													      	<small class="text-muted">
														      	<?php if ($value["CarpetaDetalle"]["document_id"] == null): ?>
														      		<?php echo $this->Utilities->date_castellano($value["Blog"]["modified"]) ?>
														      	<?php else: ?>
														      		<?php echo $this->Utilities->date_castellano($value["Document"]["modified"]) ?>
														      	<?php endif ?>
													     	</small>
													      	<?php if ($permision): ?>
														      	<div class="row">
														      		<div class="col-md-12 float-right">
														      			<?php if ($value["CarpetaDetalle"]["document_id"] == null): ?>
														      				<a href="<?php echo $this->Html->url(["controller"=>"blogs","action"=>"edit",$this->Utilities->encryptString($value["Blog"]["id"])]) ?>" class="btn btn-warning">Editar</a>
																      	<?php else: ?>
																      		<a href="<?php echo $this->Html->url(["controller"=>"documents","action"=>"edit",$this->Utilities->encryptString($value["Document"]["id"])]) ?>" class="btn btn-warning">Editar</a>
																      	<?php endif ?>
														      			
														      			<?php if ($value["CarpetaDetalle"]["document_id"] == null): ?>
														      				<a href="<?php echo $this->Html->url(["controller"=>"blogs","action"=>"delete",$this->Utilities->encryptString($value["Blog"]["id"])]) ?>" class="btn btn-danger changeState" data-controller="carpetas" data-action="index" data-id="<?php echo $this->Utilities->encryptString($value["CarpetaDetalle"]["carpeta_id"]) ?>">Eliminar</a>
																      	<?php else: ?>
																      		<a href="<?php echo $this->Html->url(["controller"=>"documents","action"=>"delete",$this->Utilities->encryptString($value["Document"]["id"])]) ?>" class="btn btn-danger changeState" data-controller="carpetas" data-action="index" data-id="<?php echo $this->Utilities->encryptString($value["CarpetaDetalle"]["carpeta_id"]) ?>">Eliminar</a>
																      	<?php endif ?>
														      		</div>
														      	</div>
													      	<?php endif ?>
													    </div>
													</div>
												</a>												
											</div>
										<?php endforeach ?>
										</div>
									</div>
									<div class="row numberpages">
										<?php
											echo $this->Paginator->first('<< ', array('class' => 'prev'), null);
											echo $this->Paginator->prev('< ', array(), null, array('class' => 'prev disabled'));
											echo $this->Paginator->counter(array('format' => '{:page} de {:pages}'));
											echo $this->Paginator->next(' >', array(), null, array('class' => 'next disabled'));
											echo $this->Paginator->last(' >>', array('class' => 'next'), null);
										?>
										<b> <?php echo $this->Paginator->counter(array('format' => '{:count} en total')); ?></b>
									</div>
								<?php endif ?>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var isRole = 1;
</script>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js'),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/carpetas/admin.js",    array('block' => 'AppScript'));
	echo $this->Html->script("controller/clientsLegal/index.js",				array('block' => 'AppScript'));
?>
