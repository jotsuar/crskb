<h2 class="text-center mb-2 text-warning">
	Solicitudes de importación activas relacionadas al flujo: <b><?php echo $this->request->data["id"] ?>			</b>
</h2>

<div class="table-responsive">
	<table class="table-striped table-bordered myTable">
	<tbody>
		<?php foreach ($requests as $key => $value): ?>
			<?php $productInfo = end($value["Product"]); ?>
			<tr>
				<td colspan="5" class="text-center uppercase"><strong><?php echo $productInfo["brand"] ?></strong></td>
			</tr>
			<tr>
				<th>Imagen</th>
				<th>Producto</th>
				<th>Referencia</th>
				<th style="min-width: 380px !important">Fecha de entrega</th>
				<th>Cant. solicitada</th>		
			</tr>
			<?php foreach ($value["Product"] as $keyProduct => $valueProduct): ?>
				<tr>
					<td>
						<?php $ruta = $this->Utilities->validate_image_products($valueProduct['img']); ?>
						<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($valueProduct['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="40px" height="40px" class="imgmin-product">
					</td>
					<td>
						<?php echo $valueProduct["name"] ?>
					</td>
					<td>
						<?php echo $valueProduct["part_number"] ?>
					</td>
					<td>
						<?php 
							$fecha = $this->Utilities->calculateFechaFinalEntrega($value["ImportRequestsDetail"]["created"],Configure::read("variables.entregaProductValues.".$valueProduct["ImportRequestsDetailsProduct"]["delivery"]));
							$dataDay = $this->Utilities->getClassDate($fecha);
						?>
						<span class="">
							<?php echo $this->Utilities->date_castellano($fecha); ?>
						</span>
						<br>
						<?php if ($dataDay == 0): ?>
							<span class="bg-danger text-white">¡Se debe entregar hoy el producto!</span>
						<?php elseif($dataDay > 0): ?>
							<span class="bg-danger text-white">¡Se debió entregar hace <?php echo $dataDay ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
						<?php elseif($dataDay <= -5): ?>
							<span class="bg-success text-white">¡Se debe entregar en   <?php echo abs($dataDay) ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
						<?php else: ?>
							<span class="bg-warning text-white">¡Se debe entregar en  <?php echo abs($dataDay) ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
						<?php endif ?>
					</td>
					<td>
						<?php echo $valueProduct["ImportRequestsDetailsProduct"]["quantity"]; ?>
					</td>
				</tr>
			<?php endforeach ?>
		<?php endforeach ?>
	</tbody>
</div>