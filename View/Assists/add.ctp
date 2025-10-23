
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-secondary big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white	 bannerbig" >Módulo para registro de asistencia y salida </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h1 class="nameview">Registro fotográfico</h1>
				
			</div>
			<div class="col-md-6">
				<a href="<?php echo $this->Html->url(["action" => "index"]) ?>" class="btn btn-info float-right">
					<i class="fa fa-list vtc"></i> Listar mis registros
				</a>				
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<?php if (!$movileAccess): ?>
			
		<div class="commitments d-xs-none form">
			<?php echo $this->Form->create('Assist',['data-parsley-validate=""',"type"=>"file"]); ?>
			<?php
				echo $this->Form->input('user_id',["value" => AuthComponent::user("id"), "type" => "hidden", "required" ]);
				echo $this->Form->input('image_file',["type" => "hidden", "required" ]);
			?>
			<div class="row">
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-12">
							<select id="select" style="display:none;">
			                  <option></option>
			                </select>
							<video id="video" class="w-100"  autoplay playsinline></video>
						</div>
						<div class="col-md-6">
							<a href="javascript:void(0)" class="btn btn-info fotoBtn btn-block" data-canvas="canvasFotoUp" id="fotoUpFile" data-input="AssistImageFile" data-img="imgUpFile"> Encender cámara </a>
						</div>
						<div class="col-md-6">
							<a href="javascript:void(0)" class="btn btn-warning btn-block" id="tomaFoto"> Tomar foto </a>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<canvas id="canvasFotoUp" style="display: none;" class="w-100"></canvas>
					<img src="" id="imgUpFile" class="img-fluid">
				</div>
			</div>	
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->Form->input('note',["label"=>"Nota adicional", "required" => false ]); ?>

					<?php echo $this->Form->input('file_excuse',["label"=>"Imagen adicional (excusa)", "required" => false, "type" => "file", "data-allowed-file-extensions" => "png jpg jpeg pdf", "data-max-file-size" => "5M", "class" => "dropify"  ]); ?>
				</div>
				<div class="col-md-6">
					
				</div>
			</div>
			<?php echo $this->Form->end(__('Enviar asistencia')); ?>
		</div>
		<?php else: ?>
			<h2>No es posible desde dispositivos móviles</h2>
		<?php endif ?>
	</div>
</div>


<?php echo $this->element("jquery") ?>	
<?php echo $this->Html->script("lib/photo.js?".rand(),				array('block' => 'AppScript'));
 ?>

