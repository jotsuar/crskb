<?php 
	echo $this->Html->css(array('lib/jquery.typeahead.css'),						array('block' => 'AppCss'));
?>
<div class=" widget-panel widget-style-2 bg-azulclaro big">
             <i class="fa fa-1x flaticon-growth"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
		</div>

	<div class="col-md-12 p-0">
		
		<div class="templates form blockwhite">
			<?php echo $this->Form->create('Template',array('data-parsley-validate'=>true)); ?>
				<div class="row">
					<div class="col-md-6">
						<h2 class="titleviewer">Crear plantilla</h2>
					</div>
					<div class="col-md-6 text-right">	
						<a id="btn_delete_cache">Borrar caché</a>
					</div>	
				</div>

				<?php 
					echo $this->Form->input('name',array('label' => 'Nombre','maxlength'=>'120','placeholder' => 'Ingresa el nombre de la plantilla'));
					echo $this->Form->input('description',array('label' => 'Descripción','maxlength'=>'400','placeholder' => 'Ingresa la descripción'));
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
					<div class="contenttableresponsive">
						<table class="table table-bordered table-striped" id="details-country">
							<tr class="titles-tablest">
								<td>Imagen</td>
								<td>Nombre</td>
								<td>Descripción</td>
								<td>Referencia</td>
								<td>Marca</td>
								<td>Acción</td>
							</tr>
						</table>
					</div>
				</div>
			<?php echo $this->Form->end('Crear'); ?>
		</div>
	</div>

<?php
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script("lib/jquery.typeahead.js",								array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/quotations/add.js?".rand(),				array('block' => 'AppScript'));
	echo $this->Html->script("controller/templates/add.js?".rand(),					array('block' => 'AppScript'));
?>