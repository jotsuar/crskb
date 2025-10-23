<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-5">
				<h2 class="titleviewer">Exportar productos por categoría</h2> 
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 d-block m-auto">
				<?php echo $this->Form->create('Product',array('data-parsley-validate'=>true,)); ?>
					<div class="blockwhite">
						<h3 class="text-center text-info">
							Filtros
						</h3>
						<hr>
						<?php $key = 0; ?>
						<div class="form-group mt-3 categoriasData_<?php echo $key ?> catDivs">
							<label for="categoryData">Categoría por grupos</label>
							<hr>
						</div>
						<?php $label = "categoría"; ?>
						<div class="form-group mt-3 categoriasData_1_<?php echo $key ?> catDivs">
							<label for="categoryData">Grupo 1</label>
							<select name="category_1" id="category_1">
								<option value="">Seleccionar</option>
								<?php foreach ($categoriesInfoFinal[0] as $key => $value): ?>
									<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group mt-3 categoriasData_2_<?php echo $key ?> catDivs">
							<label for="categoryData">Grupo 2</label>
							<select name="category_2" id="category_2">
								<option value="">Seleccionar</option>
							</select>
						</div>

						<div class="form-group mt-3 categoriasData_3_<?php echo $key ?> catDivs">
							<label for="categoryData">Grupo 3</label>
							<select name="category_3" id="category_3">
								<option value="">Seleccionar</option>
							</select>
						</div>

						<div class="form-group mb-2 mt-3 categoriasData_4_<?php echo $key ?> catDivs">
							<label for="categoryData">Grupo 4</label>
							<select name="category_4" id="category_4">
								<option value="">Seleccionar</option>
							</select>
						</div>
						<hr>
						<div class="form-group">
							<label for="selectAll">Todos los productos <input type="checkbox" name="select_all"> </label>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-success btn-block" value="EXPORTAR PRODUCTOS">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	var categoriesInfoFinal = <?php echo json_encode($categoriesInfoFinal) ?>;
	var category1Select = <?php echo isset($category1Select) ? $category1Select : "null" ?>;
    var category2Select = <?php echo isset($category2Select) ? $category2Select : "null" ?>;
    var category3Select = <?php echo isset($category3Select) ? $category3Select : "null" ?>;
    var category4Select = <?php echo isset($category4Select) ? $category4Select : "null" ?>;
</script>

<?php echo $this->element("categories_select", array("categorias" => $categoriesInfoFinal)); ?>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>