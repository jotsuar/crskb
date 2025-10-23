<div class="md-accordion" id="accordion<?php echo $parentId ?>" role="tablist" >
	<?php foreach ($categories[$parentId] as $key => $value): ?>
		<div class="card">
		    <div class="card-header p-0 collapsed" id="heading<?php echo $value["id"] ?>" data-toggle="collapse" data-target="#collapse<?php echo $value["id"] ?>" aria-expanded="true" aria-controls="collapse<?php echo $value["id"] ?>" data-parent="#accordion<?php echo $parentId ?>">
		      <h5 class="mb-0">
		        <button class="btn btn-link" >
		      		<i class="fa fa-angle-down rotate-icon vtc"></i>
		           <?php echo $value["name"] ?> 
		        </button>
		          <?php if (isset($categories[$value["id"]])): ?>
			         <span class="float-right iconsublevel">
			          	<small ><b><?php echo count($categories[$value["id"]]) ?></b> subcategorías</small>
			         </span>

			         <a href="<?php echo $this->Html->url(["controller" => "categories","action" => "export",$value["id"],uniqid()]) ?>" target="_blank" class="btn btn-info export" data-id="0">
						<i class="fa fa-file-excel-o vtc"></i> Exportar Subcategorías de <?php echo $value["name"] ?>
					</a>

		          <?php endif ?>
		        <?php $texto = $parentId == 0 ? "categoría" : "subcategoría"; ?>
		        <a class="float-right iconedit" href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($value['id']) )) ?>" data-toggle="tooltip" title="Editar <?php echo $texto ?>">
		        	 <small><i class="fa fa-pencil-square-o vtc"></i></small>
		        </a>
		        <a class="float-right iconopen" href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($value['id']))) ?>" data-toggle="tooltip" title="Ver <?php echo $texto ?>">
					<small><i class="fa fa-external-link vtc"></i> </small>
	            </a>
		      </h5>
		    </div>

		    <div id="collapse<?php echo $value["id"] ?>" class="collapse" aria-labelledby="heading<?php echo $value["id"] ?>" data-parent="#accordion<?php echo $parentId ?>">
		      <div class="card-body">
		        <b>Descripción: </b> <?php echo $value["description"] ?>  <br>
		        <b>Requiere garantia para cotizar: </b> <?php echo $value["grupo"] == 1 ? "Si" : "No	" ?>  <br>

				<span><h2 class="mb-2">
					
					Margen USD:  <?php echo $value["margen"] ?>% 
					Margen COP:  <?php echo $value["margen_wo"] ?>%
					Factor IMP:  <?php echo $value["factor"] ?>%

					<?php if (isset($categories[$value["id"]])): ?>
						<br>
						<center>Subcategorías de <?php echo $value["name"] ?></center>
					<?php endif ?>
				</h2>
					
				</span>
				<?php if (isset($categories[$value["id"]])): ?>
					
					<?php echo $this->element("categories_info",array("categories" => $categories, "parentId" => $value["id"] )) ?>
				<?php endif ?>

		      </div>
		    </div>
	  </div>
	<?php endforeach ?>
</div>