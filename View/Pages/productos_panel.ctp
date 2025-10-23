<div style="max-height: 400px; overflow-y: auto;">
	<table class="table-bordered table" id="productosData" style="max-width: 100% !important;">
		<thead>
			<tr>
				<th class="noMostrar2 p-1">
					Img
				</th>
				<th>
					Producto
				</th>
				<th>
					Cantidad
				</th>
				<th>
					Tipo solicitud
				</th>
				<th>
					Flujo
				</th>
				<th >
					Cliente
				</th>
				<th class="noMostrar2 p-1">
					Motivo
				</th>		
				<th>
					Asesor
				</th>								
				<th class="p-1">
					Fecha solicitud
				</th>
				<th class=" p-1">
					Fecha probable de entrega
				</th>
			</tr>
		</thead>
		<?php foreach ($productosImport as $key => $value): ?>
			<tr>
				<td class="p-1" style="width: 80px !important;">

					<?php $ruta = $this->Utilities->validate_image_products($value["Product"]['img']); ?>
					<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value["Product"]['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="55px" height="55px" class="imgmin-product">
				</td>
				<td>
					<?php echo $value["Product"]['part_number'] ?> /
					<?php echo $value["Product"]['name'] ?>
				</td>
				<td>
					<?php echo $value["ImportRequestsDetailsProduct"]["quantity"] ?>
				</td>
				<td>
					<?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$value["ImportRequestsDetail"]["type_request"]) ?>
				</td>
				<td>
					<?php echo $value["ImportRequestsDetail"]["prospective_user_id"] ?>
				</td>
				<th class="p-0">
					 <?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value["ImportRequestsDetail"]["prospective_user_id"]), 40,array('ellipsis' => '...','exact' => false)); ?>
				</th>
				<td class="p-0"><?php echo $value['ImportRequestsDetail']['description'] ?></td>
				<td class="p-0"><?php echo $this->Text->truncate($this->Utilities->find_name_adviser($value['ImportRequestsDetail']['user_id']), 50,array('ellipsis' => '...','exact' => false)); ?></td>
				<td class="p-0"><?php echo $value['ImportRequestsDetail']['created'] ?></td>
				<td class="p-0">
					
					<?php if ($value["ImportRequestsDetail"]["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
						<?php 
							$fecha = !is_null($value["ImportRequestsDetail"]["deadline"]) ? $value["ImportRequestsDetail"]["deadline"] : $this->Utilities->calculateFechaFinalEntrega($value["ImportRequestsDetail"]["created"],Configure::read("variables.entregaProductValues.".$value["ImportRequestsDetailsProduct"]["delivery"]));
							$dataDay = $this->Utilities->getClassDate($fecha);
						?>
						<span class="">
							<?php echo $this->Utilities->date_castellano($fecha); ?>
						</span>
						<br>
						<?php if ($dataDay == 0): ?>
							<span class="bg-danger text-white">¡Para entrega hoy!</span>
						<?php elseif($dataDay > 0): ?>
							<span class="bg-danger text-white">¡Retraso de <?php echo $dataDay ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
						<?php elseif($dataDay <= -5): ?>
							<span class="bg-success text-white">¡Para entrega en  <?php echo abs($dataDay) ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
						<?php else: ?>
							<span class="bg-warning">¡Para entrega en  <?php echo abs($dataDay) ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
						<?php endif ?>
					<?php endif;?>

				</td>
			</tr>
		<?php endforeach ?>
	</table>
</div>