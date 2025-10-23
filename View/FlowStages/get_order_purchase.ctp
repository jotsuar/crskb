<div class="comentariosnegociado"><b>Comentarios: </b> <?php echo $ordenCompra['FlowStage']['description'] ?></div>
<?php if ($ordenCompra['FlowStage']['document'] != ''): ?>
	<a class="alingicon" target="_blank" href="<?php echo $this->Html->url('/files/flujo/negociado/'.$ordenCompra['FlowStage']['document']) ?>">
		Ver aprobación &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
	</a>
<?php else: ?>
	<p class="text-danger">
		No se adjunto órden de compra
	</p>
<?php endif ?>