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
								<h4 style="text-align: center;">Se ha generado un nuevo movimiento de inventario para el producto</h4> 
								<div style="padding: 15px 30px; background: #f8f8f8;">
									<h2 style="font-size: 1.3em; line-height: 17px; margin-bottom: 0px; margin-top: 0px"><b style="color:#004598; text-transform: uppercase;"><?php echo $product["Product"]["name"] ?> - <?php echo $product["Product"]["part_number"] ?>
									</h2>	
									<p style="margin-top: 0px; font-size: 1.2em; color: #0060b9; text-decoration: underline; font-weight: bold; line-height: 17px;">Cantidad: <?php echo $inventoryData["Inventory"]["quantity"] ?> Unidad(es)</p>

									
									<p style="text-align: justify;"><b>Tipo de movimiento:</b> <?php echo Configure::read("INVENTORY_TYPE_REASON.". $inventoryData["Inventory"]["type_movement"]) ?></p>
									<p style="text-align: justify;"><b>Razón del movimiento:</b> <?php echo $inventoryData["Inventory"]["reason"] ?></p>
								</div>
								<?php if ($inventoryData["Inventory"]["type"] == Configure::read("TYPES_MOVEMENT.SALIDA_INVENTARIO")): ?>
									<h4 style="text-align: center;">Recuerda que para que sea descontado del inventario debes aprobar esta salida.</h4>
								<?php endif ?>
								
							</td>
							<td style="width:25px;"></td>
						</tr>
					</table>
				
					<br>
					<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
						<tr>
							<td width="225"></td>
							<td width="150" height="30"  align="center">

								<a href="<?php echo Router::url('/', true).'inventories/index/'.$this->Utilities->encryptString($product['Product']['id']) ?>" style="color: #004794; background: #ffda01; font-size: 18px; padding: 5px 12px; border-radius: 5px; font-weight: bold;    text-decoration: none;">
									IR A APROBAR
								</a>

							</td>
							<td width="225"></td>

						</tr>
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