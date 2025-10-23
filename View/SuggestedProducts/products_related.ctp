<?php if (!empty($products)): ?>
	<?php foreach ($products as $key => $product): ?>
		<tr id="trID_<?php echo $product["Product"]["id"] ?> " class="tr_remarket" data-id="<?php echo $product["Product"]["id"] ?>">
			<td class="resetpd text-center">
				<?php $ruta = $this->Utilities->validate_image_products($product['Product']['img']); ?>
				<img class="minprods" minprod="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>"  width="50px" >
			</td>
			<td>
				<?php echo $this->Form->input('product_id.'.$product["Product"]["id"],array('label' => false, 'type' => "hidden", "min" => "1", "value" => $product["Product"]["id"] , "step" => '0.5'  ));?>
				<?php echo $product["Product"]["part_number"] ?>
			</td>
			<td>
				<?php echo $product["Product"]["name"] ?>
			</td>
			<td>
				<?php echo $product["Product"]["brand"] ?>
			</td>	
			
			<td>
				<?php echo $this->Form->input('delivery.'.$product["Product"]["id"],array('label' => false, 'options' => Configure::read('variables.entregaProduct'), "value" => $product["SuggestedProduct"]["delivery"]  ));?>
			</td>
			<td>
				<?php echo $this->Form->input('quantity.'.$product["Product"]["id"],array('label' => false, 'type' => "number", "min" => "1", "value" => $product["SuggestedProduct"]["quantity"]  ));?>
			</td>
			<td>
				<?php echo $product["Product"]["categoria"] ?>
			</td>
			<td>
				<?php echo $product["Product"]["purchase_price_usd"] ?> USD
			</td>
			<td>
				<?php $precioVenta = $product["SuggestedProduct"]["price_usd"] ?>
				<?php echo $this->Form->input('price_usd.'.$product["Product"]["id"],array('label' => false, 'type' => "number", "min" => "1", "value" => round($precioVenta,2) , "step" => '0.01'  ));?>
			</td>
			<td>
				<a href="#" class="btn btnDelete" data-id="<?php echo $product["Product"]["id"] ?>">
					<i class="fa fa-1x fa-trash vtc"></i>
				</a>
			</td>
		</tr>
	<?php endforeach ?>
<?php endif ?>