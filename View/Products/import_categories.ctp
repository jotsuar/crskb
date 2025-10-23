<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp'));
 ?>

 <div class="container p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h2 class="titleviewer">Importar archivo de productos categorizados</h2>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h3 class="text-info text-center">Carga masiva de productos con categorías</h3>
			</div>
			<div class="col-md-12">
				<?php echo $this->Form->create('Producto',array("id" => "formProducto","type" => "file")); ?>
					<div class="form-group">
						<?php echo $this->Form->input('file',array('label' => "Cargar archivo de productos","required","type" => "file"));?>
					</div>
					<div class="col-md-12">
						<input type="submit" class="btn btn-success float-right" value="Guardar">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>