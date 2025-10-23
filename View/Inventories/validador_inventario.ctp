<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp'));
 ?>

 <div class="container p-0">
	<div class=" widget-panel widget-style-2 bg-morado big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Validación de inventario y actualización de costos </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-4">
				<h2 class="titleviewer">Formulario de validación</h2>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<?php echo $this->Form->create('',array("id" => "formCreateTienda","type" => "file")); ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<?php echo $this->Form->input('file',array('label' => "Cargar de inventario generado desde WO","required","type" => "file"));?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php echo $this->Form->input('costos',array('label' => "Actualizar costos","required","options" => ["0" => "NO","1" => "SI"]));?>
							</div>
						</div>
					</div>				
					
					<div class="col-md-12">
						<input type="submit" class="btn btn-success float-right" value="Guardar">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>