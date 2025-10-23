<div class="col-md-12">
	<div class="headers form blockwhite">
		<div class="row">
			<div class="col-md-6">
				<h2>Editar header</h2>
			</div>
		</div>
		<?php echo $this->Form->create('Header',array('enctype'=>"multipart/form-data",'data-parsley-validate'=>true)); ?>
			<?php
				echo $this->Form->input('id',array('value' => $header['Header']['id']));
				echo $this->Form->input('name',array('label' => 'Nombre','placeholder' => 'Nombre','value' => $header['Header']['name']));
			?>
			<?php
				echo $this->Form->input('big_img',array('label' => 'Imagen grande (Header)','type' => 'file'));
			?>
			<img src="<?php echo $this->Html->url('/img/header/header/'.$header['Header']['img_big']) ?>" width="30px" height="22px" class="imgmin-product">&nbsp;
			<?php
				echo $this->Form->input('small_img',array('label' => 'Imagen pequeña (Miniatura)','type' => 'file'));
			?>
			<img src="<?php echo $this->Html->url('/img/header/miniatura/'.$header['Header']['img_small']) ?>" width="30px" height="22px" class="imgmin-product">&nbsp;
			<br>
		<?php echo $this->Form->end('Actualizar'); ?>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>