<div class="col-md-12">
	<?php if (AuthComponent::user("id")): ?>
		<div class=" widget-panel widget-style-2 bg-green big">
	         <i class="fa fa-1x flaticon-growth"></i>
	        <h2 class="m-0 text-white bannerbig">BIBLIOTECA CRM KEBCO SAS </h2>
		</div>
		<div class=" blockwhite spacebtn20">
			<div class="row ">
				<div class="col-md-6">
					<h2 class="titleviewer">Visualización de artículos</h2>
				</div>
			</div>
		</div>
	<?php endif ?>
	<div class="mt-3">
		<div class="row">
			<?php if (!AuthComponent::user("id")): ?>
				<div class="col-md-12 text-center">
					<div class="row">
						<div class="col-md-12 px-0">
							<div class="blockwhite spacebtn20 mb-1">
								<h2 class="titleviewer text-center">
									BIBLIOTECA CRM KEBCO SAS
								</h2>
							</div>
						</div>
					</div>
				</div>
			<?php endif ?>
			<div class="col-md-9 px-1">
				<div class=" blockwhite spacebtn20 h-100">
					<div class="row ">
						<div class="col-md-12 text-center">
							<h2 class="titleviewer text-center">
								<?php echo h($blog['Blog']['name']); ?>
								&nbsp;
							</h2>
						</div>
						<div class="col-md-12">
							<div class="container">
								<?php echo ($blog['Blog']['description']); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3 px-0">
				<div class=" blockwhite spacebtn20 px-0 h-100">
					<div class="row ">

						<div class="col-md-12 text-center my-2">
							<h2 class="titleviewer text-center">
								Datos generales
							</h2>
						</div>
						<div class="col-md-12">
							<div class="container">
								<ul class="list-group">
									<li class="list-group-item">
										<b>Categoría: </b> <?php echo $blog['Carpeta']['name'] ?>
									</li>
									<li class="list-group-item">
										<b>Fecha de creación:</b> <?php echo $this->Utilities->date_castellano($blog["Blog"]["modified"]) ?>
									</li>
									<?php if (!empty($blog["Blog"]["url"])): ?>
										
										<li class="list-group-item">
											<b>Url externa: </b>	
											<a target="_blank" href="<?php echo $blog["Blog"]["url"] ?>"><?php echo $blog["Blog"]["url"] ?></a>
										</li>

									<?php endif ?>
									<li class="list-group-item">
										<?php 
											$imgsUrls = [];

											if(!is_null($blog["Blog"]['imagen_1'])){
												$ruta = $this->Utilities->validate_image_products($blog["Blog"]['imagen_1']);
												$imgsUrls[] = $this->Html->url('/files/blogs/'.$ruta);
											}
											if(!is_null($blog["Blog"]['imagen_2'])){
												$ruta = $this->Utilities->validate_image_products($blog["Blog"]['imagen_2']);
												$imgsUrls[] = $this->Html->url('/files/blogs/'.$ruta);
											}
											if(!is_null($blog["Blog"]['imagen_3'])){
												$ruta = $this->Utilities->validate_image_products($blog["Blog"]['imagen_3']);
												$imgsUrls[] = $this->Html->url('/files/blogs/'.$ruta);
											}
										 ?>
										<?php if (!empty($imgsUrls)): ?>											
											<div class="gallery" style="display:nones">
												<?php foreach ($imgsUrls as $keyUrl => $valueUrl): ?>
													<a id="<?php echo $keyUrl == 0 ? "firstImg" : "" ?>" href="<?php echo $valueUrl ?>" alt="hola">
														<img src="<?php echo $valueUrl ?>" alt="<?php echo $blog['Carpeta']['name'] ?>" class="img-fluid w-100">
													</a>
												<?php endforeach ?>
											</div>
										<?php endif ?>
									</li>
									<?php if (!empty($blog["Blog"]["archivo_1"]) || !empty($blog["Blog"]["archivo_2"]) || !empty($blog["Blog"]["archivo_3"])): ?>
										<li class="list-group-item text-center">
											<b>Archivos asociados</b>
										</li>
									<?php endif ?>
									<?php if (!empty($blog["Blog"]["archivo_1"])): ?>
										<li class="list-group-item">
											<?php
												$url = $this->Html->url("/files/blogs/".$blog["Blog"]["archivo_1"],true);
												$target = "_blank"; 
											?>
											<a href="<?php echo $url ?>" target="<?php echo $target ?>" class="btn btn-info btn-block">
												<i class="card-img-top fa fa-3x fa-newspaper-o mt-2 text-center"></i> Ver archivo
											</a>
										</li>
									<?php endif ?>

									<?php if (!empty($blog["Blog"]["archivo_2"])): ?>
										<li class="list-group-item">
											<?php
												$url = $this->Html->url("/files/blogs/".$blog["Blog"]["archivo_2"],true);
												$target = "_blank"; 
											?>
											<a href="<?php echo $url ?>" target="<?php echo $target ?>" class="btn btn-warning btn-block">
												<i class="card-img-top fa fa-3x fa-newspaper-o mt-2 text-center"></i> Ver archivo
											</a>
										</li>
									<?php endif ?>

									<?php if (!empty($blog["Blog"]["archivo_3"])): ?>
										<li class="list-group-item">
											<?php
												$url = $this->Html->url("/files/blogs/".$blog["Blog"]["archivo_3"],true);
												$target = "_blank"; 
											?>
											<a href="<?php echo $url ?>" target="<?php echo $target ?>" class="btn btn-blue btn-block">
												<i class="card-img-top fa fa-3x fa-newspaper-o mt-2 text-center"></i> Ver archivo
											</a>
										</li>
									<?php endif ?>		
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("magnificpopup.js?".rand(),			array('block' => 'AppScript')); 
	echo $this->Html->script("controller/blogs/admin.js?".rand(),			array('block' => 'AppScript')); 

?>

<style>
	.entry-content {
	    max-width: 100% !important;
	}
	div.contenido {
		max-width: 100% !important;
	}
</style>