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
<div class="container p-0">

	<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-azulclaro big">
             <i class="fa fa-1x flaticon-growth"></i>
            <h2 class="m-0 text-white bannerbig">Módulo de Gestión de CRM </h2>
		</div>
		<div class="blockwhite spacebtn20">
			<h2 class="titleviewer">Editar producto</h2>
		</div>		
		<div class="products form blockwhite">
			<ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
				<li class="nav-item">
				    <a class="nav-link active" id="home-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-controls="home" aria-selected="true">Datos principales</a>
				</li>
				<li class="nav-item">
				    <a class="nav-link" href="<?php echo $this->Html->url(["controller"=>"products","action"=>"images",$this->Utilities->encryptString($datos["Product"]["id"])]) ?>" >Imágenes y documentos</a>
				</li>
				<li class="nav-item">
				    <a class="nav-link" href="<?php echo $this->Html->url(["controller"=>"products","action"=>"caracteristicas",$this->Utilities->encryptString($datos["Product"]["id"])]) ?>" >Carateristicas</a>
				</li>
			</ul>
			<?php echo $this->Form->create('Product',array('enctype'=>"multipart/form-data",'data-parsley-validate'=>true,'id' => 'form_product')); ?>
				<div class="row">
					<?php echo $this->Form->input('id',array('value' => $datos['Product']['id']));?>
					<div class="col-6">
						<?php echo $this->Form->input('name',array('label' => 'Nombre','placeholder' => 'Ingresa el nombre del equipo'));?>
						<?php echo $this->Form->input('sub_name',array('label' => 'Sub-Título (Aplica para cotizaciones nuevas)','placeholder' => 'Ingresa el sub-nombre del equipo'));?>
					</div>
					<div class="col-6">
			  			<a id="btn_find_existencia" data-toggle="tooltip" data-placement="right" title="Comprobar que no esté creado">
							<i class="fa fa-refresh"></i>Validar existencia
						</a>
						
						<?php echo $this->Form->input('part_number',array('label' => 'Número de parte','placeholder' => 'Ingresa la referencia del equipo que da el fabricante','class' => "flexdatalist", "list" => "references","data-min-length" => 1,"readonly" => !in_array(AuthComponent::user("role"), ["Logística","Gerente General","Gerente línea Productos Pelican"])));?>
						<datalist id="references">
						    <?php foreach ($referencias as $key => $value): ?>
						    	<option value="<?php echo $value ?>"><?php echo $value ?></option>
						    <?php endforeach ?>
						</datalist>
			  		</div>
			  		<?php $label = "categoría"; $key = "1"; ?>
					<div class="col-md-12 col-sm-12">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group mt-3 categoriasData_1_<?php echo $key ?> catDivs">
									<label for="categoryData">Categoría Grupo 1</label>
									<select name="data[Product][category_1]" id="category_1" required="">
										<option value="">Seleccionar</option>
										<?php foreach ($categoriesInfoFinal[0] as $key => $value): ?>
											<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mt-3 categoriasData_2_<?php echo $key ?> catDivs">
									<label for="categoryData">Categoría Grupo 2</label>
									<select name="data[Product][category_2]" id="category_2" <?php echo !in_array(AuthComponent::user("role"), ["Logística","Gerente General"]) ? "required" : "" ?> >
										<option value="">Seleccionar</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mt-3 categoriasData_3_<?php echo $key ?> catDivs">
									<label for="categoryData">Categoría Grupo 3</label>
									<select name="data[Product][category_3]" id="category_3" <?php echo !in_array(AuthComponent::user("role"), ["Logística","Gerente General"]) ? "required" : "" ?> >
										<option value="">Seleccionar</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mb-2 mt-3 categoriasData_4_<?php echo $key ?> catDivs">
									<label for="categoryData">Categoría Grupo 4</label>
									<select name="data[Product][category_4]" id="category_4" <?php echo !in_array(AuthComponent::user("role"), ["Logística","Gerente General"]) ? "required" : "" ?> >
										<option value="">Seleccionar</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<?php echo $this->Form->input('quantity_min',array('label' => 'Cantidad mínima para cotizar sin inventario:',"required","value"=>$datos["Product"]["quantity_min"])); ?>
							</div>
							<div class="col-md-6">
								<?php echo $this->Form->input('delivery_min',array('label' => 'Tiempo de cotización cuando no se cumple la cantidad mínima:', 'options' => Configure::read("variables.entregaProduct") ,"required" => false,"value"=>$datos["Product"]["delivery_min"], "empty" => "No configurar" )); ?>
							</div>
						</div>
					</div>
					<div class="col-md-12 mt-3">

						<ul class="nav nav-tabs" id="myTab" role="tablist">
						  <li class="nav-item">
						    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Descripción (Resumen)</a>
						  </li>
						  <li class="nav-item">
						    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Descripción completa</a>
						  </li>
						</ul>
						<div class="tab-content" id="myTabContent">
						  <div class="tab-pane pt-1 fade show active validatecaracter" id="home" role="tabpanel" aria-labelledby="home-tab">
						  	<?php
								echo $this->Form->input('description',array('label' => false,'placeholder' => 'Describe el equipo que estás creando, puedes detallar que incluye...',"required"));
							?>
							<button class="btn btn-info btn-sm" id="generateDescription">
								<i class="fa fa-check vtc"></i> Generar descripción con IA
							</button>
							Caracteres utilizados <span id="lbl_caracteres_utilizados">0</span>,caracteres faltantes <span id="lbl_caracteres_faltantes"><?php echo strlen($datos["Product"]["description"]) > 700 ? 3500 : 3500 ?></span>, caracteres permitidos <?php echo strlen($datos["Product"]["description"]) > 700 ? 3500 : 3500 ?>
						  </div>
						  <div class="tab-pane pt-1 fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
						  	<?php
								echo $this->Form->input('long_description',array('label' => false,'placeholder' => 'Describe el equipo que estás creando, puedes detallar que incluye...'));
							?>
						  </div>
						</div>
						
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('link',array('label' => 'Link (Enlace)','type' => "text")); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('brand_id',array('label' => 'Marca:', 'options' => $marca,"required","value"=>$datos["Product"]["brand_id"])); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('list_price_usd',array('label' => 'Precio','placeholder' => '¿Cuánto cuesta el producto?','type' => "text" ,"div" => "input")); ?>
					</div>
					<div class="col-md-3" <?php echo in_array(AuthComponent::user("role"), ["Gerente General","Logística"] ) ? '' : 'display:none' ?>>
						<?php echo $this->Form->input('fixed_price',array('label' => 'Precio fijo de venta con inventario','placeholder' => '¿Cuánto cuesta el producto?','type' => "text" ,"div" => "input")); ?>
						
					</div>
					<div class="col-md-3" <?php echo in_array(AuthComponent::user("role"), ["Gerente General","Logística"] ) ? '' : 'display:none' ?>>
						<?php echo $this->Form->input('fixed_cop',array('label' => 'Precio fijo de venta sin inventario','placeholder' => '¿Cuánto cuesta el producto?','type' => "text" ,"div" => "input")); ?>
						
					</div>
					<div class="col-md-3" <?php echo in_array(AuthComponent::user("role"), ["Gerente General","Logística"] ) ? '' : 'display:none' ?>>
						<?php echo $this->Form->input('fixed_usd',array('label' => 'Precio fijo de venta USD','placeholder' => '¿Cuánto cuesta el producto?','type' => "text" ,"div" => "input", "step" => '0.01')); ?>
						
					</div>
					<div class="col-md-3" <?php echo in_array(AuthComponent::user("role"), ["Gerente General","Logística"] ) ? '' : 'display:none' ?>>
						<?php echo $this->Form->input('margen_usd',array("label" => "Margen mínimo de importación","min" => 0, "type"=>"number","max"=>100)); ?>
					</div>
					<div class="col-md-3" <?php echo in_array(AuthComponent::user("role"), ["Gerente General","Logística"] ) ? '' : 'display:none' ?>>
						<?php		echo $this->Form->input('margen_wo',array("label" => "Margen mínimo de venta costo WO ","min" => 0, "type"=>"number","max"=>100)); ?>
					</div>
					<div class="col-md-3" <?php echo in_array(AuthComponent::user("role"), ["Gerente General","Logística"] ) ? '' : 'display:none' ?>>
						<?php		echo $this->Form->input('factor',array("label" => "Factor de importación", "min"=>0, "type"=>"number", "step" => '0.01' )); ?>
					</div>
					<div class="col-md-3" <?php echo in_array(AuthComponent::user("role"), ["Gerente General","Logística"] ) ? '' : 'display:none' ?>>
						<?php		echo $this->Form->input('free_price',array("label" => "Precio libre de venta", "options" => ['0'=>"NO","1"=>'SI'] )); ?>
					</div>
					<div class="col-md-6">
						<?php echo $this->Form->input('url_video',array('type' => 'url','label' => 'Url VIDEO YOUTUBE', "div" => "input")); ?>
						<?php echo $this->Form->input('action', array('class' => 'form-control border-input label-price only-numbers limit-price','label'=>false,'div'=>false, "value" => $action, "type" => "hidden" )); ?>
						<?php echo $this->Form->input('id_product', array('class' => 'form-control border-input label-price only-numbers limit-price','label'=>false,'div'=>false, "value" => $id, "type" => "hidden" )); ?>
					</div>
					<div class="col-md-3">
						<?php echo $this->Form->input('currency',array('label' => 'Moneda de compra local','placeholder' => '¿En que moneda lo compras localmente?',"options" => [1=> "COP",2 => "USD"] ,"required","value" => "cop","div" => "input")); ?>
					</div>
					<div class="col-md-9">
						<div class="row">
							<?php echo $this->Form->input('normal',array('label' => 'Tipo de producto',"options" => ["1" => "Normal", "2" => "Compuesto"] ,"div" => "col-md-4 input" )); ?>
						</div>
						<div class="col-md-12 compuestoData p-0"></div>
					</div>
					<?php 
					
						if (in_array(AuthComponent::user('role'), $rolesPriceImport) || AuthComponent::user("email") == "ventas2@almacendelpintor.com") {
							echo $this->Form->input('min_stock',array('type' => 'number','label' => 'Stock mínimo de reposición',"min" => 0, "div" => "col-md-4 input"));
							echo $this->Form->input('reorder',array('type' => 'number','label' => 'Cantidad para reordenar el producto',"min" => 0 ,"div" => "col-md-4 input"));
							echo $this->Form->input('max_cost',array('type' => 'number','label' => 'Stock máximo de reposición',"min" => 0, "div" => "col-md-4 input"));						
						}else{
							echo $this->Form->input('min_stock',array('type' => 'number','label' => 'Stock mínimo de reposición', "type" => "hidden"));
							echo $this->Form->input('reorder',array('type' => 'number','label' => 'Cantidad para reordenar el producto' ,"type" => "hidden"));
							echo $this->Form->input('max_cost',array('type' => 'number','label' => 'Stock máximo de reposición', "type" => "hidden"));	
						}
					?>
					<div class="<?php echo !in_array(AuthComponent::user('role'), $rolesPriceImport) ? "d-none" : "" ?> col-md-12">
						<div class="row">						
							<?php								
								echo $this->Form->input('purchase_price_usd',array('label' => 'Costo USD','placeholder' => '¿Cuánto cuesta el producto para reposición USD?',"type" => "text","required","default" => 0,"div" => "col input"));
								
								echo $this->Form->input('purchase_price_wo',array('label' => 'Costo WO (COP)','placeholder' => '¿Cuánto cuesta el producto en WO (COP)?',"type" => "text","required","default" => 0,"div" => "col input"));
								echo $this->Form->input('purchase_price_cop',array('label' => 'Costo local COP','placeholder' => '¿Hay algún gasto adicional en USD?',"type" => "text","required","default" => 0,"div" => "col input"));
								echo $this->Form->input('aditional_cop',array('label' => 'Costo adicional COP','placeholder' => '¿Hay algún gasto adicional en COP?',"type" => "text","required","default" => 0,"div" => "col input"));
								echo $this->Form->input('aditional_usd',array('label' => 'Costo adicional  USD','placeholder' => '¿Hay algún gasto adicional en USD?',"type" => "text","required","default" => 0,"div" => "col input"));
							?>
						</div>
					</div>
			  	</div>
			

		<?php echo $this->Form->end('Actualizar'); ?>
		</div>
	</div>
</div>
<div class="modal fade" id="modalIngrediente" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Agregar producto </h4>
      </div>
      <div class="modal-body" id="cuerpoAdd">
      </div>
      <div class="modal-footer m-t-4">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<script>

	const CHAR_NUM = <?php echo strlen($datos["Product"]["description"]) > 700 ? 3500 : 3500 ?> ;
	var editProduct = true;
	var categoriesInfoFinal = <?php echo json_encode($categoriesInfoFinal) ?>;

    var category1Select = <?php echo isset($category1Select) ? $category1Select : "null" ?>;
    var category2Select = <?php echo isset($category2Select) ? $category2Select : "null" ?>;
    var category3Select = <?php echo isset($category3Select) ? $category3Select : "null" ?>;
    var category4Select = <?php echo isset($category4Select) ? $category4Select : "null" ?>;

</script>
<?php echo $this->Html->css('jquery.flexdatalist.min.css') ?>
<?php echo $this->element("categories_select", array("categorias" => $categoriesInfoFinal)); ?>

<?php 
	echo $this->Html->script("jquery.flexdatalist.min.js?".rand(),						array('block' => 'AppScript'));
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/product/add.js?".rand(),						array('block' => 'AppScript'));
?>