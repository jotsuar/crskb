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
	<?php if (isset($noExisten)): ?>
		<div class="blockwhite spacebtn20">
			<div class="row">
				<div class="col-md-12">
					<h2 class="titleviewer">Referencias inexistentes en CRM:</h2>
				</div>
				<div class="col-md-12">
					<p>
						<?php echo implode(", ", $noExisten); ?>
					</p>
				</div>
			</div>
		</div>
	<?php endif ?>
	<?php if (isset($diferencias)): ?>
		<div class="blockwhite spacebtn20">
			<div class="row">
				<div class="col-md-12">
					<h2 class="titleviewer">Diferencias encontradas:</h2>
				</div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-hovered">
							<thead>
								<tr>
									<th>Número de parte</th>
									<th>Product</th>
									<th>Inventario actual</th>
									<th>Inventario WO</th>
									<th>Diferencia</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($diferencias as $key => $value): ?>
									<tr>
										<td><?php echo $value["part"] ?></td>
										<td><?php echo $value["product"] ?></td>
										<td><?php echo $value["inventory"] ?></td>
										<td><?php echo $value["inventory_wo"] ?></td>
										<td><?php echo $value["diference"] ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>		
				</div>
			</div>
		</div>
	<?php endif ?>
</div>