<?php if (empty($products)): ?>
	<tr>
		<td colspan="7" class="text-center">
			No se encontraron resultados
		</td>
	</tr>
<?php endif ?>
<?php foreach ($products as $key => $product): ?>

	<?php if (isset($this->request->data["productsIDs"]) && !in_array($product["Product"]["id"], $this->request->data["productsIDs"] ) ): ?>
		<?php continue; ?>
	<?php endif ?>

	<tr id="trID_<?php echo $product["Product"]["id"] ?> " class="tr_remarket" data-id="<?php echo $product["Product"]["id"] ?>">
		<td class="resetpd text-center">
			<?php $ruta = $this->Utilities->validate_image_products($product['Product']['img']); ?>
			<img class="minprods" minprod="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>"  width="50px" >
		</td>
		<td>
			<?php echo $product["Product"]["part_number"] ?>
		</td>
		<td>
			<?php echo $product["Product"]["name"] ?>
		</td>
		<td>
			<?php echo $product["Product"]["brand"] ?>
		</td>	
		<td>
			<div style="display:none !important">
				<?php echo $this->Form->input('product_id.'.$product["Product"]["id"],array("value" => $product["Product"]["id"],"type" => "hidden", "class" => "product_select_inp" )); ?>
			</div>
			<a href="#" class="btn btnDelete" data-id="<?php echo $product["Product"]["id"] ?>">
				<i class="fa fa-1x fa-trash vtc"></i>
			</a>
		</td>
	</tr>
<?php endforeach ?>