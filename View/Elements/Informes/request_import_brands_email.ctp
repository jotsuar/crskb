<?php foreach ($requests as $key => $request): ?>
	<table class="table">
		<tr>
			<td>
				<h2>
					<b style="color:#004598;" >
						Proveedor: <u class="uppercase"><?php echo $request["Brand"]["name"] ?></u>
					</b>
				</h2>
			</td>
		</tr>
	</table>
	<?php foreach ($request["ImportRequestsDetail"] as $keyDetails => $details): ?>
		<?php if (!isset($details["InfoProducts"]) || empty($details["InfoProducts"])): ?>
			<?php continue; ?>
		<?php endif ?>
		<table class="table">
			<tr>
				<td colspan="5" style="width:550px;">
					<b> 
						<?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$details["type_request"]) ?> 
						<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
							(<?php echo $details["prospective_user_id"] ?>)
						<?php endif ?>
					</b><br>
					<small>Solicita <?php echo $details["InfoProducts"]["User"]["name"] ?> 
						<?php if (isset($details["created"]) && !empty($details["created"])): ?>
							el <?php echo $this->Utilities->date_castellano(h($details['created'])) ?>
						<?php endif ?>

				</small>
					<br>
					<?php if (isset($details["type_request"]) && $details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
						<b>Flujo de negocio</b>: 
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$details["prospective_user_id"])) ?>" style="background-color: #004598; color:#fff; text-decoration: none; font-size: 11px; padding: 2px 5px; border-radius: 3px;" >
							Ver
						</a>
						<?php else: ?>
							<?php if (isset($details["description"])): ?>
								<b>Razón de la importación</b>: <?php echo $details["description"] ?>													
							<?php endif ?>
						<?php endif;?>
				</td>

			</tr>
			<tr>
				<td><b>Cant.</b></td>
				<td><b>Foto</b></td>
				<td><b>Producto</b></td>
				<td><b>Ref.</b></td>
				<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
				<td style="width: 25% !important; padding-left: 15px;"><b>F. Entrega</b></td>
				<?php endif;?>

			</tr>
			<?php if (isset($details["InfoProducts"]["Product"]) && !empty($details["InfoProducts"]["Product"]) ): ?>
				<?php foreach ($details["InfoProducts"]["Product"] as $idProduct => $value): ?>
				<tr>

					<td style="text-align: center;">
						<?php echo $value["ImportRequestsDetailsProduct"]["quantity"] ?>
					</td>
					<td>
						<?php $ruta = $this->Utilities->validate_image_products($value['img']); ?>
						<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value['name']); ?>" src="https://crm.kebco.co/img/products/<?php echo $ruta ?>" width="100px" height="100px" class="imgmin-product">
					</td>
					
					<td style="text-transform: uppercase;">
						<?php echo $this->Text->truncate(strip_tags($value['name']), 50,array('ellipsis' => '...','exact' => false)); ?>
					</td>
					<td>
						<?php echo $value["part_number"]?>
					</td>
					<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
					<td style="width: 25% !important; padding-left: 15px;">
					<?php 
					$fecha = $this->Utilities->calculateFechaFinalEntrega($details["created"],Configure::read("variables.entregaProductValues.".$value["ImportRequestsDetailsProduct"]["delivery"]));
					$dataDay = $this->Utilities->getClassDate($fecha);
					?>
					<span class=" font-weight-bold "><?php echo $fecha; ?> </span>
					<br>
					<?php if ($dataDay == 0): ?>
						<span style="background-color: red; color: #fff; font-size: 11px; padding: 2px 5px; border-radius: 3px;">!Entrega hoy!</span>
						<?php elseif($dataDay > 0): ?>
						<span style="background-color: red; color: #fff; font-size: 11px; padding: 2px 5px; border-radius: 3px;">!Retraso de <?php echo abs($dataDay) ?> día(s)</span>
						<?php elseif($dataDay <= -5): ?>
						<span style="background-color: green; color: #fff; font-size: 11px; padding: 2px 5px; border-radius: 3px;">Faltan <?php echo abs($dataDay) ?> día(s)</span>
						<?php else: ?>
						<span style="background-color: yellow; color: #fff; font-size: 11px; padding: 2px 5px; border-radius: 3px;">Faltan  <?php echo abs($dataDay) ?> día(s)</span>
						<?php endif ?>
					</td>
					<?php endif;?>
				</tr>
			<?php endforeach ?>
		<?php endif ?>
				
	</table>
		<hr style="margin: 20px !important; border: 0; border-top: 1px solid #ccc;">
	<?php endforeach;  ?>
								
<?php endforeach;  ?>