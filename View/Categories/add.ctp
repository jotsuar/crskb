<?php $categoriesInfoFinal[0] ?>
<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Crear categoría o subcategoría</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="categories form">
			<?php echo $this->Form->create('Category',["type"=>"file"]); ?>
					<div class="col-md-12 col-sm-12">
						<div class="form-group mt-3 catDivs">
							<label for="categoryData">Categoría por grupos</label>
							<hr>
						</div>
						<div class="form-group mt-3 catDivs">
							<label for="categoryData">Grupo 1</label>
							<select name="data[Category][category_1]" id="category_1" required="">
								<option value="">Seleccionar</option>
								<?php foreach ($categoriesInfoFinal[0] as $key => $value): ?>
									<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group mt-3 categoriasData_2_<?php echo $key ?> catDivs">
							<label for="categoryData">Grupo 2</label>
							<select name="data[Category][category_2]" id="category_2">
								<option value="">Seleccionar</option>
							</select>
						</div>

						<div class="form-group mt-3 categoriasData_3_<?php echo $key ?> catDivs">
							<label for="categoryData">Grupo 3</label>
							<select name="data[Category][category_3]" id="category_3">
								<option value="">Seleccionar</option>
							</select>
						</div>
						<hr>
					</div>	
					<?php
						echo $this->Form->input('name',array("label" => "Nombre "));
						echo $this->Form->input('description',array("label" => "Descripción","type" => "text", "id" => "descriptionCategory" ));
						echo $this->Form->input('margen',array("label" => "Margen mínimo de importación","min" => 0, "value" => 0));
						echo $this->Form->input('margen_wo',array("label" => "Margen mínimo de venta costo WO ","min" => 0, "value" => 0));
						echo $this->Form->input('imagen',array("label" => "Imagen de refeferencia para lista de precios", "required" => false,"type"=>"file"));
						echo $this->Form->input('grupo',array("label" => "Requiere garantía","options"=> ["0" => "NO", "1" => "SI"] , "default" => 0));
						echo $this->Form->input('show_cost',array("label" => "Mostrar costo real de wo","options"=> ["0" => "NO", "1" => "SI"] , "default" => 1));
						echo $this->Form->input('factor',array("label" => "Factor de importación", "min"=>0, "value" => 1.1));
						
					?>
					<div class="form-group mb-5">
						<button class="btn btn-success float-right" type="submit">
							Guardar información
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	var categorySelect = <?php echo isset($categorySelect) ? "parseInt(".$categorySelect.");" : "null" ?>;
	var categoriesInfoFinal = <?php echo json_encode($categoriesInfoFinal); ?>;
	var category1Select  = null;
	var category2Select  = null;
	var category3Select  = null;
	var category4Select  = null;
</script>
<?php //echo $this->element("categories_select", array("categorias" => $categoriesSelect)); ?>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
	echo $this->Html->script("controller/categories/categories_down.js?".rand(),						array('block' => 'AppScript'));
?>
