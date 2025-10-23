<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-green big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">BIBLIOTECA CRM KEBCO SAS </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Creación de artículos</h2>
			</div>
		</div>
	</div>
	<div class="mt-3">
			<?php echo $this->Form->create('Blog',['data-parsley-validate'=>true, "type" => "file"]); ?>
		<div class="row">
			<div class="col-md-9">
				<div class=" blockwhite spacebtn20">
					<div class="row ">
						<div class="col-md-12 text-center">
							<h2 class="titleviewer text-center">Información principal</h2>
						</div>
						<div class="col-md-12">
							<?php 
								echo $this->Form->input('carpeta_id',["label" => "Categoría", "div" => "form-group mt-2", "class" => "form-control", "default" => $id ]);
								echo $this->Form->input('name',["label" => "Nombre", "div" => "form-group mt-2", "class" => "form-control", ]); 
								echo $this->Form->input('short_description',["label" => "Descripción corta", "div" => "form-group mt-2", "class" => "form-control", "type" => "textarea", "required" => true ]);
								echo $this->Form->input('description',["label" => "Contenido del artículo", "div" => "form-group mt-2", "class" => "form-control", ]);
								
							?>
							<div class="row">
								<div class="col-md-6">
									<?php echo $this->Form->input('url',["label" => "URL externa", "div" => "form-group mt-2", "class" => "form-control", ]);  ?>
								</div>
								<div class="col-md-6">
									<?php
										echo $this->Form->input('public',["label" => "¿Será visible al público?", "div" => "form-group mt-2", "class" => "form-control","options" => ["1" => "Si", "2" => "No"] ]);
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class=" blockwhite spacebtn20">
					<div class="row ">
						<div class="col-md-12 text-center">
							<h2 class="titleviewer text-center">Archivos e imágenes</h2>
						</div>
						<div class="col-md-12 border mt-3 py-2 ">
							<h3 class="text-center">
								Fotos
							</h3>
							<hr>
							<?php 
								echo $this->Form->input('imagen_1',["label" => "Imagen 1", "div" => "form-group mt-2", "class" => "form-control dropify","type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif webp", "data-max-file-size" => "5M", ]);
								echo $this->Form->input('imagen_2',["label" => "Imagen 2", "div" => "form-group mt-2", "class" => "form-control dropify","type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif webp", "data-max-file-size" => "5M", ]);
								echo $this->Form->input('imagen_3',["label" => "Imagen 3", "div" => "form-group mt-2", "class" => "form-control dropify","type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif webp", "data-max-file-size" => "5M", ]);
							?>
						</div>
						<div class="col-md-12 border mt-3 py-2 ">
							<h3 class="text-center">
								Archivos
							</h3>
							<hr>
							<?php 
								echo $this->Form->input('archivo_1',["label" => "Archivo 1", "div" => "form-group mt-2", "class" => "form-control dropify","type" => "file", "data-allowed-file-extensions" => "pdf docx doc xls xlsx ppt pptx", "data-max-file-size" => "5M", ]);
								echo $this->Form->input('archivo_2',["label" => "Archivo 2", "div" => "form-group mt-2", "class" => "form-control dropify","type" => "file", "data-allowed-file-extensions" => "pdf docx doc xls xlsx ppt pptx", "data-max-file-size" => "5M", ]);
								echo $this->Form->input('archivo_3',["label" => "Archivo 3", "div" => "form-group mt-2", "class" => "form-control dropify","type" => "file", "data-allowed-file-extensions" => "pdf docx doc xls xlsx ppt pptx", "data-max-file-size" => "5M", ]);
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class=" blockwhite spacebtn20">
					<div class="row ">
						<div class="col-md-12 text-center">
							<input type="submit" value="Guardar" class="btn btn-primary float-right">
						</div>
					</div>
				</div>
			</div>
		</div>
			<?php echo $this->Form->end(); ?>
	</div>
</div>

<?php
	echo $this->Html->script("controller/blogs/admin.js?".rand(),    array('block' => 'AppScript'));
?>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>