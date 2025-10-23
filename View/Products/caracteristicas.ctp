<div class="container p-0">

	<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-azulclaro big">
			
				
			<i class="fa fa-1x flaticon-growth"></i>
    		<h2 class="m-0 text-white bannerbig">Módulo de Gestión de CRM </h2>
				
			
             
		</div>
		<div class="blockwhite spacebtn20">
			<div class="row">
				<div class="col-md-12">
					<h2 class="titleviewer">Gestión de características del producto: <?php echo $datos["Product"]["part_number"] ?> - <?php echo $datos["Product"]["name"] ?> </h2>
					<br>
					<small class="text-info">
						Se recomienda un tamaño de 400x400
					</small>
				</div>

			</div>
		</div>		
		<div class="products form blockwhite">
			<ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link" href="<?php echo $this->Html->url(["controller"=>"products","action"=>"edit",($datos["Product"]["id"])]) ?>" >Datos principales</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="home-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-controls="home" aria-selected="true">Imágenes y documentos</a>
				</li>
				<li class="nav-item">
				    <a class="nav-link active" href="<?php echo $this->Html->url(["controller"=>"products","action"=>"caracteristicas",$this->Utilities->encryptString($datos["Product"]["id"])]) ?>" >Carateristicas</a>
				</li>
			</ul>
			<?php echo $this->Form->create('Product',array('type'=>"file",'data-parsley-validate','id' => 'form_product')); ?>
				<?php echo $this->Form->input('id',array('value' => $datos['Product']['id']));?>
				<div class="row">
					<div class="col-md-5">
						<div class="col-md-12 m-auto p-4" >
							<div class="row">
								<div class="col-md-12">
									<h3 class="text-center">
										Bullets Points | Destacados  <a href="" class="btn btn-info btn-sm" id="newBullet">Agregar bullet <i class="fa fa-plus vtc"></i></a>
									</h3>
								</div>
								<div class="col-md-12">
									<div  id="bullets_list">
										<?php if (!empty($datos["Bullet"])): ?>
											<?php foreach ($datos["Bullet"] as $key => $value): ?>
												<div class="row">	
	
													<div class="col-md-9">
														<?php
														$id = "Bid_".time();
														echo $this->Form->input('bullets.title.',["id" => $id,"value"=>$value["title"]]);
													?>
													</div>
													<div class="col-md-3">
														<a href="#" class="btn btn-danger mt-4 deleteBull" data-id="<?php echo $id ?>">
															<i class="fa fa-trash"></i>
														</a>
													</div>
												</div>
												<?php sleep(1); ?>
											<?php endforeach ?>
										<?php endif ?>
									</div>
								</div>
							</div>
							
						</div>
					</div>
					<div class="col-md-7">
						<div class="col-md-12 m-auto p-4" >
							<div class="row">
								<div class="col-md-12">
									<h3 class="text-center">
										Características actuales del producto  <a href="" class="btn btn-info btn-sm" id="newFeature">Agregar caracteristica <i class="fa fa-plus vtc"></i></a>
									</h3>
								</div>
								<div class="col-md-12">
									<div  id="features_list">
										<?php if (!empty($datos["FeaturesValue"])): ?>
											<?php foreach ($datos["FeaturesValue"] as $key => $value): ?>
												<?php echo $this->element('row_feature',["features"=>$features,"seleted_value" => $value["feature_id"], "seleted_valor" => $value["id"] ]) ?>
												<?php sleep(1); ?>											
											<?php endforeach ?>
										<?php endif ?>
									</div>
								</div>
							</div>
							
						</div>
					</div>
					
					
					
				</div>
			<?php echo $this->Form->end('Actualizar'); ?>
		</div>
	</div>
</div>


<?php echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp')); ?>
<?php echo $this->Html->script("controller/product/features.js?".rand(),						array('block' => 'AppScript')); ?>

<style>
	.dropify-wrapper.has-preview {
     	height: 400px !important; 
	}
</style>