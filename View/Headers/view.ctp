<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
			<div class="headers view blockwhite">
				<p><b>Nombre: </b><?php echo $this->Utilities->data_null(h($header['Header']['name'])); ?>&nbsp;</p>

				<p><b>Imagen (header): </b><img src="<?php echo $this->Html->url('/img/header/header/'.$header['Header']['img_big']) ?>" width="80px" height="60px" class="imgmin-product">&nbsp;</p>

				<p><b>Imagen (header): </b><img src="<?php echo $this->Html->url('/img/header/miniatura/'.$header['Header']['img_small']) ?>" width="80px" height="60px" class="imgmin-product">&nbsp;</p>
			</div>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>