<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Detalle de <?php echo $category["Category"]["category_id"] == "0" ? "categoría" : "subategoría" ?> :  <?php echo h($category['Category']['name']); ?></h2>
	</div>

	<div class="blockwhite spacebtn20">
		<p><b><?php echo __('Nombre: '); ?></b> <?php echo h($category['Category']['name']); ?></p>
		<p><b><?php echo __('Descripción: '); ?></b> <?php echo h($category['Category']['description']); ?></p>
		<p><b><?php echo __('Margen mínimo de importación: '); ?></b> <?php echo h($category['Category']['margen']); ?></p>
		<p><b><?php echo __('Margen mínimo COP: '); ?></b> <?php echo h($category['Category']['margen_wo']); ?></p>
		<p><b><?php echo __('Categoría principal: '); ?></b> <?php echo empty($category['FatherCategory']["name"]) ? "Ninguna" : $categoriesData[$category['Category']["category_id"]]; ?>&nbsp;</p>
	</div>
	<?php if (!empty($categories)): ?>
		<div class="blockwhite spacebtn20">
			<h2 class="titleviewer mb-3">Subcategorías actuales</h2>
			<?php echo $this->element("categories_info",array("categories" => $categories, "parentId" => $category["Category"]["id"])) ?>
		</div>
	<?php endif ?>
	<?php if (!empty($products)): ?>
		<div class="blockwhite spacebtn20">
			<div class="table-responsive">
				<h3 class="text-info text-center mt-2 mb-2">Productos asociados a esta categoría</h3>
				<table cellpadding="0" cellspacing="0" class="myTable table-striped table-bordered tblProcesoCotizacion">
					<thead>
						<tr>
							<th>Imagen</th>
							<th>Producto</th>
							<th>Referencia</th>
							<th>Categorías</th>
							<th>Marca</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($products as $key => $product): ?>
							<tr>
								<td>
									<?php $ruta = $this->Utilities->validate_image_products($product["Product"]['img']); ?>
									<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($product["Product"]['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="50px" height="45px" class="imgmin-product">
								</td>

								<td>
									<?php echo $product["Product"]["name"] ?>
								</td>
								<td>
									<?php echo $product["Product"]["part_number"] ?>
								</td>
								<td>
									<?php echo $product["Product"]["brand"] ?>
								</td>
								<td class="sizeprod nameuppercase">
									<?php 
										$categorias = $categoriesData[$product["Product"]["category_id"]]; 
									 	$grupos = explode("->",$categorias);
									 	$num = 1;
									 ?>
									<ul class="list-unstyled">
										<?php foreach ($grupos as $key => $value): ?>
											<li> <b>Grupo <?php echo $num; $num++; ?>:</b> <?php echo $value; ?> </li>
										<?php endforeach ?>
									</ul>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif ?>
</div>

<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
	echo $this->Html->script("controller/brands/save.js?".rand(),						array('block' => 'AppScript'));
?>