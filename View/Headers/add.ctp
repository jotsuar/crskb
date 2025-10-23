<div class="col-md-12">
	<div class="headers form blockwhite">
		<div class="row">
			<div class="col-md-6">
				<h2 class="titleviewer">Registrar header</h2>
			</div>
		</div>
		<?php echo $this->Form->create('Header',array('enctype'=>"multipart/form-data",'data-parsley-validate'=>true)); ?>
			<?php
				echo $this->Form->input('name',array('label' => 'Nombre','placeholder' => 'Nombre'));
				echo $this->Form->input('big_img',array('label' => 'Imagen grande (Header)','type' => 'file'));
				echo $this->Form->input('small_img',array('label' => 'Imagen pequeña (Miniatura)','type' => 'file'));
			?>
		<?php echo $this->Form->end('Registrar'); ?>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>