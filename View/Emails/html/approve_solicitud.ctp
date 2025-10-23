<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Solicitud de importación</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta charset="utf-8" />
	</head>
	<body style="background-color:#f8f8f8;">
		<table align="center" style="width: 600px; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;background-color:#ffffff;">
			<tr>
				<td>
					<table style="width: 600px;  display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr>
							<td>
								<img src="https://www.almacendelpintor.com/images/bg-email.png">
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr>
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<br>
								<h2><b style="color:#004598; text-transform: capitalize;">
									Hola
								</b></h2>
								<p align="justify">
									Se aprobó tu solicitud interna de importación con los siguientes productos
								</p>
								<?php if (isset($nota) && !empty($nota)): ?>
									<p align="justify">
										<b>El asesor: <?php echo $nombreAsesor ?>, agrega la siguiente nota: </b> 
										<?php echo $nota ?>.
									</p>									
								<?php endif ?>								
							</td>
							<td style="width:25px;"></td>
						</tr>
					</table>
				
					<br>
					<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<thead>
							<tr style="text-align: center">
								<th style="width:50px;"></th>
								<th><b>Foto</b></th>
								<th><b>Producto</b></th>
								<th><b>Cant.</b></th>
								<th style="width:50px;"></th>	
							</tr>
						</thead>
							<?php if (isset($products) && !empty($products) ): ?>
								<?php foreach ($products as $idProduct => $value): ?>
								<?php if ($value["ImportRequestsDetailsProduct"]["state"] != 1): ?>
									<?php continue; ?>
								<?php endif ?>
								<tr>
									<td style="width:50px;"></td>
									
									<td>
										<?php $ruta = $this->Utilities->validate_image_products($value['img']); ?>
										<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value['name']); ?>" src="https://crm.kebco.co/img/products/<?php echo $ruta ?>" width="100px" height="100px" class="imgmin-product">
									</td>
									
									<td style="text-transform: uppercase;">
										<b><?php echo $value["part_number"]?></b> <br>
										<?php echo $this->Text->truncate(strip_tags($value['name']), 50,array('ellipsis' => '...','exact' => false)); ?>
									</td>
									<td style="text-align: center;">
										<?php echo $value["ImportRequestsDetailsProduct"]["quantity"] ?>
									</td>
									<td style="width:50px;"></td>
								</tr>
							<?php endforeach ?>
						<?php endif ?>
					</table>
					<br>
					<br>
					<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr align="center">
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<img src="http://almacendelpintor.com/logo.jpg">
								<hr>
								<p style="color:#6f6f6f;"><small>Si tienes alguna inquietud puedes contactarnos en nuestras líneas de atención telefónica: </small>
									<b style="color:#004598">MEDELLÍN:</b> Calle 10 No. 52A-18 Int 104 Centro Integral La 10 PBX: (4) 448 5566
									<b style="color:#004598">BOGOTÁ:</b> Av. Calle 26 No. 85D-55, LE 25 C.C. Dorado Plaza PBX: (4) 448 5566
									</p><br>
							</td>
							<td style="width:50px;"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>