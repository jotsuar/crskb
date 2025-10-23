<?php 
	
	$rolesPriceImport = array(
		Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),
		Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'),
		Configure::read('variables.roles_usuarios.Administración'),
		Configure::read('variables.roles_usuarios.Gerente General'),
		Configure::read('variables.roles_usuarios.Logística'),		
		Configure::read('variables.roles_usuarios.Asesor Comercial'),		
	);

?>
<div class="products form">
	<?php echo $this->Form->create('Product',array("enctype"=>"multipart/form-data","id"=>"formuploadajax")); ?>
		<?php echo $this->Form->hidden('id',array('value' => (isset($datos['Product']['id'])) ? $datos['Product']['id']:""));?>
		<div class="form-row"> 
			<div class="col">
				<?php if ($action == 'add'): ?>
						<a id="btn_find_existencia" data-toggle="tooltip" data-placement="right" title="Comprobar que no esté creado"><i class="fa fa-refresh"></i>Validar existencia</a>
				<?php endif ?>
				<?php $readonly = (isset($datos['Product']['part_number'])) && !in_array(AuthComponent::user("role"), ["Logística","Gerente General","Gerente línea Productos Pelican"]) ? "readonly" : "" ?>
				<?php echo $this->Form->input('part_number',array('label' => 'Referencia','placeholder' => 'Ingresa la referencia del producto','value' => (isset($datos['Product']['part_number'])) ? $datos['Product']['part_number']:"", $readonly));?>
				<datalist id="references">
				    <?php foreach ($referencias as $key => $value): ?>
				    	<option value="<?php echo $value ?>"><?php echo $value ?></option>
				    <?php endforeach ?>
				</datalist>
			</div>
			<div class="col">
				<?php echo $this->Form->input('name',array('label' => 'Nombre','placeholder' => 'Ingresa la nombre del producto','value' => (isset($datos['Product']['name'])) ? $datos['Product']['name']:""));?>
			</div>	
		</div>
		<label>Descripción</label>
		<?php echo $this->Form->input('description',array('placeholder' => 'Agrega una descripción detallada del producto','label' => false,'value' => (isset($datos['Product']['description'])) ? $datos['Product']['description']:"", "id" => "ProductDescriptionForm"));?>
		Caracteres utilizados <span id="lbl_caracteres_utilizados">0</span>,caracteres faltantes <span id="lbl_caracteres_faltantes">500</span>, caracteres permitidos 500
		<?php echo $this->Form->input('brand',array('label' => 'Marca:', 'options' => $marca,'value' => (isset($datos['Product']['brand_id'])) ? $datos['Product']['brand_id']:"")); ?>
		
		<?php $label = "categoría"; $key = "1"; ?>
		<?php if (isset($categoriesDataForEdit)): ?>
			<input type="hidden" id="category1Select" value="<?php echo isset($category1Select) ? 
			$category1Select : "" ?>">
			<input type="hidden" id="category2Select" value="<?php echo isset($category2Select) ? 
			$category2Select : "" ?>">
			<input type="hidden" id="category3Select" value="<?php echo isset($category3Select) ? 
			$category3Select : "" ?>">
			<input type="hidden" id="category4Select" value="<?php echo isset($category4Select) ? 
			$category4Select : "" ?>">
		<?php endif ?>
		<div class="col-md-12 col-sm-12">
			<div class="form-group mt-3 categoriasData_<?php echo $key ?> catDivs">
				<label for="categoryData">Categoría por grupos</label>
				<hr>
			</div>
			<div class="form-group mt-3 categoriasData_1_<?php echo $key ?> catDivs">
				<label for="categoryData">Grupo 1</label>
				<select name="data[Product][category_1]" id="category_1" required="">
					<option value="">Seleccionar</option>
					<?php foreach ($categoriesInfoFinal[0] as $key => $value): ?>
						<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
					<?php endforeach ?>
				</select>
			</div>

			<div class="form-group mt-3 categoriasData_2_<?php echo $key ?> catDivs" required>
				<label for="categoryData">Grupo 2</label>
				<select name="data[Product][category_2]" id="category_2">
					<option value="">Seleccionar</option>
				</select>
			</div>

			<div class="form-group mt-3 categoriasData_3_<?php echo $key ?> catDivs" required>
				<label for="categoryData">Grupo 3</label>
				<select name="data[Product][category_3]" id="category_3">
					<option value="">Seleccionar</option>
				</select>
			</div>

			<div class="form-group mb-2 mt-3 categoriasData_4_<?php echo $key ?> catDivs" required>
				<label for="categoryData">Grupo 4</label>
				<select name="data[Product][category_4]" id="category_4">
					<option value="">Seleccionar</option>
				</select>
			</div>
			<hr>
		</div>

		<?php echo $this->Form->input('link',array('label' => 'Link (Enlace)','value' => (isset($datos['Product']['link'])) ? $datos['Product']['link']:"")); ?>
		<div class="form-row"> 
			<div class="col-11">
				<?php echo $this->Form->input('img',array('type' => 'file','label' => 'Imagen del producto'));?>
			</div>
			<div class="col-1">
				<?php if (isset($datos['Product']['id'])){ ?>
					<?php $ruta = $this->Utilities->validate_image_products($datos['Product']['img']); ?>
					<div class="imgbox" style="background: url(<?php echo $this->Html->url('/img/products/'.$ruta) ?>);"></div>
				<?php } ?>				
			</div>	
		</div>
		<div class="form-row"> 
			<div class="col-11">
				<?php echo $this->Form->input('img2',array('type' => 'file','label' => 'Imagen 2 del producto')); ?>
			</div>
			<div class="col-1">
				<?php if (isset($datos['Product']['id']) && !is_null($datos["Product"]["img2"])){ ?>
					<?php $ruta = $this->Utilities->validate_image_products($datos['Product']['img2']); ?>
					<div class="imgbox" style="background: url(<?php echo $this->Html->url('/img/products/'.$ruta) ?>);"></div>
				<?php } ?>				
			</div>	
		</div>
		<div class="form-row"> 
			<div class="col-11">
				<?php echo $this->Form->input('img3',array('type' => 'file','label' => 'Imagen 3 del producto')); ?>
			</div>
			<div class="col-1">
				<?php if (isset($datos['Product']['id']) && !is_null($datos["Product"]["img3"])){ ?>
					<?php $ruta = $this->Utilities->validate_image_products($datos['Product']['img3']); ?>
					<div class="imgbox" style="background: url(<?php echo $this->Html->url('/img/products/'.$ruta) ?>);"></div>
				<?php } ?>				
			</div>	
		</div>
		<div class="form-row"> 
			<div class="col-11">
				<?php echo $this->Form->input('img4',array('type' => 'file','label' => 'Imagen 4 del producto')); ?>
			</div>
			<div class="col-1">
				<?php if (isset($datos['Product']['id']) && !is_null($datos["Product"]["img4"])){ ?>
					<?php $ruta = $this->Utilities->validate_image_products($datos['Product']['img4']); ?>
					<div class="imgbox" style="background: url(<?php echo $this->Html->url('/img/products/'.$ruta) ?>);"></div>
				<?php } ?>				
			</div>	
		</div>
		<div class="form-row"> 
			<div class="col-11">
				<?php echo $this->Form->input('img5',array('type' => 'file','label' => 'Imagen 5 del producto')); ?>
			</div>
			<div class="col-1">
				<?php if (isset($datos['Product']['id']) && !is_null($datos["Product"]["img5"])){ ?>
					<?php $ruta = $this->Utilities->validate_image_products($datos['Product']['img5']); ?>
					<div class="imgbox" style="background: url(<?php echo $this->Html->url('/img/products/'.$ruta) ?>);"></div>
				<?php } ?>				
			</div>	
		</div>
		<?php echo $this->Form->input('url_video',array('type' => 'url','label' => 'Url YOUTUBE')); ?>
		<?php echo $this->Form->input('min_stock',array('type' => 'number','label' => 'Stock mínimo de reposición',"min" => 0, "value" => isset($datos["Product"]["min_stock"]) ? $datos["Product"]["min_stock"] : 0 )); ?>

		<?php
			echo $this->Form->input('list_price_usd',array('label' => 'Precio','placeholder' => 'Ingresa el precio del producto','type' => "tex",'value' => (isset($datos['Product']['list_price_usd'])) ? $datos['Product']['list_price_usd']:""));
		?>
		<?php if (in_array(AuthComponent::user('role'), $rolesPriceImport) || AuthComponent::user("email") == "ventas2@almacendelpintor.com"): ?>
			
			<div class="col-md-12">
				<div class="row">						
					<?php								
						echo $this->Form->input('purchase_price_usd',array('label' => 'Costo USD','placeholder' => '¿Cuánto cuesta el producto para reposición USD?',"type" => "text","required","default" => isset($datos['Product']['purchase_price_usd']) ? $datos['Product']['purchase_price_usd']:"0" ,"div" => "col input"));
						echo $this->Form->input('aditional_usd',array('label' => 'Costo adicional  USD','placeholder' => '¿Hay algún gasto adicional en USD?',"type" => "text","required","default" => isset($datos['Product']['aditional_usd']) ? $datos['Product']['aditional_usd']:"0" ,"div" => "col input"));
						echo $this->Form->input('purchase_price_cop',array('label' => 'Costo local COP','placeholder' => '¿Hay algún gasto adicional en USD?',"type" => "text","required","default" => isset($datos['Product']['purchase_price_cop']) ? $datos['Product']['purchase_price_cop']:"0" ,"div" => "col input"));
						echo $this->Form->input('aditional_cop',array('label' => 'Costo adicional COP','placeholder' => '¿Hay algún gasto adicional en COP?',"type" => "text","required","default" => isset($datos['Product']['aditional_cop']) ? $datos['Product']['aditional_cop']:"0" ,"div" => "col input"));
						
					?>
				</div>
			</div>
		<?php endif ?>
		<p id="validacion_texto">Todos los campos son requeridos</p>
	</form>
</div>