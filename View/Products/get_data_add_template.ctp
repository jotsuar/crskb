<tr id="<?php echo "tr_".$datos['Product']['id'] ?>">
	<td>
		<?php $ruta = $this->Utilities->validate_image_products($datos['Product']['img']); ?>
		<img src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>"  width="40px" class="imgmin-product">
	</td>
	<td class="nameget"><?php echo $datos['Product']['name'] ?></td>
	<td class="descriptionget"><?php echo $this->Text->truncate(strip_tags($datos['Product']['description']), 120,array('ellipsis' => '...sigue','exact' => false)); ?></td>
	<td><?php echo $datos['Product']['part_number'] ?></td>
	<td><?php echo $datos['Product']['brand'] ?></td>
	<td>
		<a data-uid="<?php echo $datos['Product']['id'] ?>"  class="deleteProduct">
			<i class="fa fa-remove" data-toggle="tooltip" data-placement="right" title="Eliminar producto"></i>
		</a>
	</td>
</tr>