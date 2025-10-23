<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-green big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">BIBLIOTECA CRM KEBCO SAS </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Creación de documentos</h2>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<?php echo $this->Form->create('Document',array('data-parsley-validate'=>true,"type" => "file")); ?>
		<?php
			echo $this->Form->input('image', ["label" => "Imagen de portada","type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M", "class" => "dropify" ]);
			echo $this->Form->input('file', ["label" => "Archivo","type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif pdf docx doc xls xlsx ppt pptx", "data-max-file-size" => "25M", "class" => "dropify" ]);
			echo $this->Form->input('name', ["label" => "Nombre" ]);
			echo $this->Form->input('description', ["label" => "Descripción" ]);
			echo $this->Form->input('carpeta_id',["value" => $id ,"type" => "hidden"]);
		?>		
		<?php echo $this->Form->end(__('Guardar')); ?>	
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/carpetas/admin.js?".rand(),    array('block' => 'AppScript'));
?>
