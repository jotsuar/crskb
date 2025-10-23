<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-4">
				<h2 class="titleviewer">Remarketing de productos en base a cotizaciones</h2>
			</div>
			<div class="col-md-8">
				<a href="<?php echo $this->Html->url(array("controller" => "campaigns", "action" => "index")) ?>" class="btn btn-primary pull-right">
					Campañas activas
				</a>
				<a href="<?php echo $this->Html->url(array("controller" => "mailing_lists", "action" => "index","?"=>["type"=>"1"])) ?>" class="btn btn-warning pull-right mr-3">
					Listas de celulares creadas
				</a>
				<a href="<?php echo $this->Html->url(array("controller" => "mailing_lists", "action" => "index","?"=>["type"=>"2"])) ?>" class="btn btn-danger pull-right mr-3">
					Listas de correos creadas
				</a>
				<a href="<?php echo $this->Html->url(array("controller" => "products", "action" => "send_campana")) ?>" class="btn btn-info pull-right mr-3">
					Crear campaña independiente
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h2 class="titlesectionquote text-primary text-center"><strong class="text-success">PASO 1 - </strong> SELECCIÓN DE PRODUCTOS</h2>
			<div class=" blockwhiteabajo spacebtn20" id="paso1">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group mt-3">
							<label for="marcasData">Selección Marca</label>
							<select name="marcasData" id="marcasData">
								<option value=""> <?php echo isset($brandSelect) ? "No seleccionar marca" :  "Seleccionar marca" ?> </option>
								<?php foreach ($brands as $key => $value): ?>
									<option value="<?php echo $value["Brand"]["id"] ?>" <?php echo isset($brandSelect) && $value["Brand"]["id"] == $brandSelect ? "selected" : "" ?>><?php echo $value["Brand"]["name"] ?></option>
								<?php endforeach ?>
							</select>
							
						</div>
						<hr>

						<div class="form-group mt-4">
							<label for="">Selección por categorías y grupos</label>
							<hr>
						</div>
						<div class="form-group mt-2 categoriasData_1_ catDivs">
							<label for="categoryData">Grupo 1</label>
							<select name="category_1" id="category_1">
								<option value="">Seleccionar</option>
								<?php foreach ($categoriesInfoFinal[0] as $key => $value): ?>
									<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group mt-3 categoriasData_2_ catDivs">
							<label for="categoryData">Grupo 2</label>
							<select name="category_2" id="category_2">
								<option value="">Seleccionar</option>
							</select>
						</div>

						<div class="form-group mt-3 categoriasData_3_ catDivs">
							<label for="categoryData">Grupo 3</label>
							<select name="category_3" id="category_3">
								<option value="">Seleccionar</option>
							</select>
						</div>

						<div class="form-group mb-2 mt-3 categoriasData_4_ catDivs">
							<label for="categoryData">Grupo 4</label>
							<select name="category_4" id="category_4">
								<option value="">Seleccionar</option>
							</select>
						</div>
						
					</div>
					<div class="col-md-8">
						<div>
							<div class="typeahead__container">
								<div class="typeahead__field">
									<span class="typeahead__query">
										<input class="js-typeahead" type="search" autofocus autocomplete="off" placeholder="Busca tu producto por nombre o referencia">
									</span>
								</div>
							</div>
						</div>
						<div>
							<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
								<table class="table table-bordered">
									<thead class="backblue">
										<tr>
											<th class="text-center">Imagen</th>
											<th>Referencia</th>
											<th>Producto</th>
											<th>Marca</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody id="TBodyProducto">
										
									</tbody>
								</table>
								
							</div>
							<div class="col-md-12 text-center" id="botones" style="display: none">
								<select name="controlProductos" id="controlProductos" class="form-control mt-3 mb-2"> <br>
									<option value="all">Usar todos los productos para la búsqueda</option>
									<option value="one">Usar por lo menos uno de los productos para la búsqueda</option>
								</select>
								<a href="#" class="btn btn-secondary btnSearchData" data-type="cotizado">
									<b>VER COTIZADOS</b>
								</a>
								<a href="#" class="btn btn-success btnSearchData" data-type="venta">
									<b>VER VENDIDOS</b>	
								</a>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<div class="col-md-12">
			<h2 class="titlesectionquote text-primary text-center"><strong class="text-success">PASO 2 - </strong> SELECCIONA LOS DESTINATARIOS DE LA CAMPAÑA</h2>
			<div class=" blockwhiteabajo spacebtn20">
				<div class=" mb-5" id="resultado">
					
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<h2 class="titlesectionquote text-primary text-center"><strong class="text-success">PASO 3 - </strong> CONSTRUYE EL MENSAJE QUE ENVIARÁS A LOS CONTACTOS</h2>
			<div class=" blockwhiteabajo spacebtn20" id="paso3" >
				<div class="mt-3 mb-5" id="resultado3">
					
				</div>
			</div>
		</div>
	</div>
	
		

</div>

<!-- Modal -->
<div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Crear lista de difusión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       	<?php echo $this->Form->create('MailingList',array('id' => 'form_product_difusion')); ?>
			<?php echo $this->Form->input('name',array('label' => 'Ingrese el nombre de la nueva lista de difusión:', "type" => "text","required" )); ?>
			<?php echo $this->Form->input('numbers',array('label' => 'Celulares/Correos que conformarán la lista de difusión', "type" => "textarea","required","autocapitalize" => "characters","rows" => 50,"readonly", "id" => "WhatsappListEmails" )); ?>
			<?php echo $this->Form->input('type',array('label' => 'Celulares que conformarán la lista de difusión', "type" => "hidden","required","readonly", "id" => "typeEnvio" )); ?>
			<button type="submit" class="btn btn-success float-right">
				Guardar lista
			</button>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="listModalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Seleccionar lista de difusión</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       	<div class="form-group">
       		<label for="listadoName">Selecciona el nombre de la lista que deseas agregar</label>
       		<select name="listadoName" id="listadoWhatsapp" class="form-control listadoName">
       			<option value="">Seleccionar</option>
       			<?php foreach ($lists as $key => $value): ?>
       				<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
       			<?php endforeach ?>
       		</select>
       		<select name="listadoName" id="listadoCorreos" class="form-control listadoName">
       			<option value="">Seleccionar</option>
       			<?php foreach ($listEmails as $key => $value): ?>
       				<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
       			<?php endforeach ?>
       		</select>
       		<textarea name="listadoCompleto" id="listadoCompleto" cols="30" rows="10" class="form-control mt-3" readonly=""></textarea>
       		<input type="hidden" id="typeSendList">
       		<a href="#" class="btn btn-success selectList mt-3 pull-right" style="display: none"> Seleccionar lista</a>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php echo $this->Html->css(array('lib/jquery.typeahead.css'), array('block' => 'AppCss'));?>
<?php echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));?>
<?php 
	
		$whitelist = array(
            '127.0.0.1',
            '::1'
        ); 

 ?>

 <?php echo $this->element("flujoModal"); ?>
<script>
	var actual_uri  = "<?php echo Router::reverse($this->request, true) ?>";
    var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";
</script>

<script>
	var categorySelect = <?php echo isset($categorySelect) ? "parseInt(".$categorySelect.");" : "null" ?>;
	var categoriesInfoFinal = <?php echo json_encode($categoriesInfoFinal); ?>;
	var category1Select  = null;
	var category2Select  = null;
	var category3Select  = null;
	var category4Select  = null;
	<?php if(!empty($lists)): ?>
		var lists = <?php echo json_encode($lists); ?>;
	<?php endif; ?>
	<?php if(!empty($listEmails)): ?>
		var listEmails 	= <?php echo json_encode($listEmails); ?>;
	<?php endif; ?>
</script>

<?php
	echo $this->Html->script("lib/jquery.typeahead.js",								array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/product/remarketing.js?".rand(),						array('block' => 'AppScript'));
	echo $this->Html->script("controller/categories/categories_down.js?".rand(),						array('block' => 'AppScript'));
?>

<style>
	div#accordion.import .table-bordered thead td, .table-bordered thead th {
    background: #004990 !important;
	}
</style>	