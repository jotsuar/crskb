<div class="container p-0">

	<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-azulclaro big">
			
				
					<i class="fa fa-1x flaticon-growth"></i>
            		<h2 class="m-0 text-white bannerbig">Módulo de Gestión de CRM </h2>
				
			
             
		</div>
		<div class="blockwhite spacebtn20">
			<div class="row">
				<div class="col-md-10">
					<h2 class="titleviewer">Editar imágenes de producto</h2>
					<br>
					<small class="text-info">
						Se recomienda un tamaño de 400x400
					</small>
				</div>
				<div class="col-md-2">
					<a href="<?php echo $this->Html->url(["controller"=>"products","action"=>"editor"]) ?>" class="btn btn-secondary" target="_blank">
						<i class="fa vtc fa-photo"></i> Editor de imagenes
					</a>
				</div>
			</div>
		</div>		
		<div class="products form blockwhite">
			<ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link" href="<?php echo $this->Html->url(["controller"=>"products","action"=>"edit",($datos["Product"]["id"])]) ?>" >Datos principales</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" id="home-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-controls="home" aria-selected="true">Imágenes y documentos</a>
				</li>
				<li class="nav-item">
				    <a class="nav-link" href="<?php echo $this->Html->url(["controller"=>"products","action"=>"caracteristicas",$this->Utilities->encryptString($datos["Product"]["id"])]) ?>" >Carateristicas</a>
				</li>
			</ul>
			<?php echo $this->Form->create('Product',array('type'=>"file",'data-parsley-validate','id' => 'form_product')); ?>
			<?php echo $this->Form->input('id',array('value' => $datos['Product']['id']));?>
				<div class="row">
					<div class="col-md-12 mb-3">
						<h3 class="text-center">
							Documentos o manuales del producto
						</h3>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?php 
								$labelFinal = "Manual 1";
								if (!empty($datos["Product"]["manual_1"])) {
									$labelFinal.= " - ".$this->Html->link("Ver Documento actual","/files/products/".$datos["Product"]["manual_1"],["class"=>"btn btn-info btn-sm","target"=>"_blank"])." ".$this->Html->link("X",["controller"=>"products","action"=>"delete_document",$this->Utilities->encryptString($datos["Product"]["id"]),1],["class"=>"btn btn-danger p-1 btn-sm"]);
								}
								echo $this->Form->label("Product.manual_1",$labelFinal,["escape"=>true]);
								echo $this->Form->input('manual_1',array('type' => 'file','label' => false,"div" => false,"data-allowed-file-extensions" => "pdf", "data-max-file-size" => "10M","class"=>"imagenesProducto"));

							?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?php 
								$labelFinal = "Manual 2";
								if (!empty($datos["Product"]["manual_2"])) {
									$labelFinal.= " - ".$this->Html->link("Ver Documento actual","/files/products/".$datos["Product"]["manual_2"],["class"=>"btn btn-info","target"=>"_blank"])." ".$this->Html->link("X",["controller"=>"products","action"=>"delete_document",$this->Utilities->encryptString($datos["Product"]["id"]),2],["class"=>"btn btn-danger p-1"]);
								}
								echo $this->Form->label("Product.manual_2",$labelFinal,["escape"=>true]);
								echo $this->Form->input('manual_2',array('type' => 'file','label' => false,"div" => false,"data-allowed-file-extensions" => "pdf", "data-max-file-size" => "10M","class"=>"imagenesProducto"));

							?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?php 
								$labelFinal = "Manual 3";
								if (!empty($datos["Product"]["manual_3"])) {
									$labelFinal.= " - ".$this->Html->link("Ver Documento actual","/files/products/".$datos["Product"]["manual_3"],["class"=>"btn btn-info","target"=>"_blank"])." ".$this->Html->link("X",["controller"=>"products","action"=>"delete_document",$this->Utilities->encryptString($datos["Product"]["id"]),3],["class"=>"btn btn-danger p-1"]);
								}
								echo $this->Form->label("Product.manual_3",$labelFinal,["escape"=>true]);
								echo $this->Form->input('manual_3',array('type' => 'file','label' => false,"div" => false,"data-allowed-file-extensions" => "pdf", "data-max-file-size" => "10M","class"=>"imagenesProducto"));

							?>
						</div>
					</div>
					<div class="col-md-12">
						<hr>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 mb-3">
						<h3 class="text-center">
							Imágenes del producto
						</h3>
					</div>
					

					<?php 
						$ruta = $this->Utilities->validate_image_products($datos['Product']['img']); 
						echo $this->Form->input('img',array('type' => 'file','label' => 'Imagen del producto Principal',"div" => "col-md-6 input file","data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M","class"=>"imagenesProducto","data-default-file" => $this->Html->url('/img/products/'.$ruta),"data-height"=>"400" ));

						$ruta = $this->Utilities->validate_image_products($datos['Product']['img2']); 
						echo $this->Form->input('img2',array('type' => 'file','label' => 'Imagen 2 del producto',"div" => "col-md-6 input file","data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M","class"=>"imagenesProducto","data-default-file" => $this->Html->url('/img/products/'.$ruta),"data-height"=>"400" ));

						$ruta = $this->Utilities->validate_image_products($datos['Product']['img3']); 
						echo $this->Form->input('img3',array('type' => 'file','label' => 'Imagen 3 del producto',"div" => "col-md-6 input file","data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M","class"=>"imagenesProducto","data-default-file" => $this->Html->url('/img/products/'.$ruta),"data-height"=>"400" ));

						$ruta = $this->Utilities->validate_image_products($datos['Product']['img4']); 
						echo $this->Form->input('img4',array('type' => 'file','label' => 'Imagen 4 del producto',"div" => "col-md-6 input file","data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M","class"=>"imagenesProducto","data-default-file" => $this->Html->url('/img/products/'.$ruta),"data-height"=>"400" ));

						$ruta = $this->Utilities->validate_image_products($datos['Product']['img5']); 
						echo $this->Form->input('img5',array('type' => 'file','label' => 'Imagen 5 del producto',"div" => "col-md-6 input file","data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M","class"=>"imagenesProducto","data-default-file" => $this->Html->url('/img/products/'.$ruta),"data-height"=>"400" )); 
					?>
				</div>
			<?php echo $this->Form->end('Actualizar'); ?>
		</div>
	</div>
</div>


<?php echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp')); ?>
<?php echo $this->Html->script("controller/product/images.js?".rand(),						array('block' => 'AppScript')); ?>

<style>
	.dropify-wrapper.has-preview {
     	height: 400px !important; 
	}
</style>