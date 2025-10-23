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
									Hola,
								</b></h2>
								<p align="justify">Los siguientes productos se encuentran bloqueados, requieren tu gestión para que sean desbloqueados y se puedan usar en la aplicación: </p>

								
							</td>
							<td style="width:25px;"></td>
						</tr>
						<tr>
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<h3 align="center" style="color: #004598; margin-top: 30px">
									PRODUCTOS BLOQUEADOS
									<hr style="color: #004598;background-color: #004598;height: 2px;">
								</b></h3>
							</td>
							<td style="width:25px;"></td>
						</tr>
					</table>
					
					<?php foreach ($products as $key => $product): ?>
						<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
							<tr>
								<td style="width:50px;"></td>
								<td rowspan="3"><img width="150px" height="150px" src="<?php echo Router::url('/', true).'img/products/'.$product["Product"]['img'] ?>"></td>
								<td style="width:20px;"></td>
								<td colspan="2">
									<h3 style="margin-bottom: 0px; color:#004598"><?php echo $product["Product"]['name'] ?></h3>
									<p>Fabricante: <b><?php echo $product["Product"]['brand'] ?></b> / N. Parte: <b><?php echo $product["Product"]['part_number'] ?></b> / Costo actual <b><?php echo $product["Product"]['purchase_price_usd'] ?></b></p>
									<p>Último usuario en interactuar con el producto: 
										<?php if (!is_null($product["Product"]["user_id"])): ?>
											<?php echo $this->Utilities->find_name_adviser($product["Product"]["user_id"]) ?> / <?php echo $product["Product"]["modified"] ?>
										<?php endif ?>										
									</p>
								</td>

								<td style="width:50px;"></td>
							</tr>

						</table>
					<?php endforeach ?>

					

					
					<br>
					<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
						<tr>
							<td width="225"></td>
							<td width="150" height="30"  align="center">
								<a href="<?php echo Router::url('/', true).'Products/unlock_products/' ?>" style="color:#ffffff; text-decoration:none;">
									<img src="http://crm.kebco.co/img/verenflujos.jpg">
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
