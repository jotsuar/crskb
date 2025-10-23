<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de categorías y subcategorías</h2>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('controller'=>'categories','action'=>'add')) ?>" class="crearclientej" ><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva categoría o subcategoría</span></a>
				
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDataCategory">
			  Ayuda informativa <i class="fa fa-info-circle vtc"></i>
			</button>

			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<h2 class="mb-2">Árbol de Categorías 
			<a href="<?php echo $this->Html->url(["controller" => "categories","action" => "export",0,time()]) ?>" target="_blank" class="btn btn-info export" data-id="0">
				<i class="fa fa-file-excel-o vtc"></i> Exportar Categorías principales
			</a>
		</h2>
		<div class="boxthree">
		<?php echo $this->element("categories_info",array("categories" => $categories, "parentId" => 0)) ?>
		</div>
	</div>
</div>

<!-- Modal para registrar un requerimiento -->
<div class="modal fade" id="modalDataCategory" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_requerimiento_label">Ayuda informativa de las categorías</h2>
      </div>

      <div class="modal-body">
		<div class="table-responsive">
			<h3 class="text-center text-info">En este listado tendrás un panorama general para lograr encontrar una categoría o subcategoría del panel principal.</h3>
			<table class="table table-striped table-hovered tblProcesoCotizacion">
				<thead>
					<tr>
						<th>IDBD</th>
						<th>Grupo 1</th>
						<th>Grupo 2</th>
						<th>Grupo 3</th>
						<th>Grupo 4</th>
						<th>Factor</th>
						<th>Margen IMPO</th>
						<th>Margen WO</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($categoriesData as $key => $value): ?>
						<tr>
							<td>
								<?php echo $key ?>
							</td>
							<?php $grupos = explode("->", $value); ?>
							<td><?php echo empty($grupos["0"]) ? "" : $grupos["0"] ?></td>
							<td><?php echo empty($grupos["1"]) ? "" : $grupos["1"] ?></td>
							<td><?php echo empty($grupos["2"]) ? "" : $grupos["2"] ?></td>
							<td><?php echo empty($grupos["3"]) ? "" : $grupos["3"] ?></td>
							<td>
								<?php echo $dataMargen[$key]["factor"] ?>
							</td>
							<td>
								<?php echo $dataMargen[$key]["margen"] ?>
							</td>
							<td>
								<?php echo $dataMargen[$key]["margen_wo"] ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
      </div>

      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>
    </div>
  </div>
</div>



<script>
		
	var listCategories = <?php echo json_encode((array)$categoriesList ); ?>

</script>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>
