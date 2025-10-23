<?php
	echo $this->Html->css(array('lib/jquery.typeahead.css'),						array('block' => 'AppCss'));
?>
<div class=" widget-panel widget-style-2 bg-azulclaro big">
             <i class="fa fa-1x flaticon-growth"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
		</div>
<div class="col-md-12">

		
	<div class="templates form blockwhite">
		<?php echo $this->Form->create('Template',array('data-parsley-validate'=>true)); ?>
			<div class="row">
				<div class="col-md-6">
					<h2 class="titleviewer">Editar plantilla</h2>
				</div>
				<div class="col-md-6 text-right">	
					<a id="btn_delete_cache" data-toggle="tooltip" data-placement="right" title="Es necesario para borrar archivos temporales">Borrar caché</a>
				</div>	
			</div>

			<?php
				echo $this->Form->input('id',array('value' => $datosT['Template']['id']));
				echo $this->Form->input('name',array('label' => 'Nombre','placeholder'  => 'Nombre','maxlength'=>'120','value' =>  $datosT['Template']['name']));
				echo $this->Form->input('description',array('label' => 'Descripción','placeholder' => 'Descripción','value' =>  $datosT['Template']['description']));
			?>
			<div class="typeahead__container">
		        <div class="typeahead__field">
		            <span class="typeahead__query">
		                <input class="js-typeahead" type="search" autofocus autocomplete="off" placeholder="Busca tu producto por nombre o referencia">
		            </span>
		            <span class="typeahead__button">
		                <button class="btn btn-default" type="submit">
		                    <span class="typeahead__search-icon"></span>
		                </button>
		            </span>
		        </div>
		    </div>

			<h2>Productos añadidos a la plantilla</h2>
			<div id="contentproductquote">

				<table class="table table-bordered table-striped" id="details-country">
					<head>
						<tr>
							<th>Imagen</th>
							<th>Nombre</th>
							<th>Descripción</th>
							<th>Referencia</th>
							<th>Marca</th>
							<th>Acción</th>
						</tr>
					</head>
					<tbody id="tbody">
					<?php foreach ($datosTP as $value): ?>
						<tr id='<?php echo "tr_".$value['Product']['id'] ?>'>
							<td>
								<?php $ruta = $this->Utilities->validate_image_products($value['Product']['img']); ?>
								<img src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataimgpp="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="40px" class="imgmin-pp">
							</td>
							<td><?php echo $this->Utilities->data_null(h($value['Product']['name'])) ?></td>
							<td><?php echo $this->Utilities->data_null(h($value['Product']['description'])) ?></td>
							<td><?php echo $this->Utilities->data_null(h($value['Product']['part_number'])) ?></td>
							<td><?php echo $value['Product']['brand'] ?></td>								
							<td>
								<a data-uid="<?php echo $value['Product']['id'] ?>"  class="editPrductEdit">
									<i class="fa fa-remove" data-toggle="tooltip" data-placement="right" title="Eliminar producto de la plantilla"></i>
								</a>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			</div>
		<?php echo $this->Form->end('Actualizar'); ?>
	</div>
</div>
<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<div class="contentpopup">
			<img src="" class="img-productpp" alt="">
		</div>
	</div>
<div class="fondo"></div>

<?php 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script("lib/jquery.typeahead.js",								array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/quotations/add.js?".rand(),				array('block' => 'AppScript'));
	echo $this->Html->script("controller/templates/edit.js?".rand(),				array('block' => 'AppScript'));
?>