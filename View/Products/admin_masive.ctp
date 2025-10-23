<div class="row">
	<h2 class="text-center text-info w-100">
		<?php echo $this->request->data["type"] == "update" ? "Actualización masiva" : "Creación masiva de productos" ?>
		<a class="ml-5" target="_blank" href="<?php echo $this->Html->url(array("controller"=> "products","action" => "export_template",$this->request->data["type"], time())) ?>">Descargar plantilla <i class="fa fa-file vtc"></i></a>
	</h2>

	
</div>
<div class="row">
	
</div>

<div class="row">
	<?php echo $this->Form->create('Product',array('enctype'=>"multipart/form-data",'data-parsley-validate'=>true,'id' => 'form_masive','class' => "w-100 mt-5")); ?>
		<div class="col-md-12">
			<?php echo $this->Form->hidden("type",array("value" => $this->request->data["type"])); ?>
			<div class="form-group w-100">
				<?php echo $this->Form->input('file',array('type' => 'file','label' => 'Archivo',"required")); ?>
			</div>
			<input type="submit" value="<?php echo $this->request->data["type"] == "update" ? "Actualización masiva" : "Creación masiva de productos" ?>" class="btn btn-success btn-sm pull-right">
		</div>
	<?php echo $this->Form->end(); ?>
</div>