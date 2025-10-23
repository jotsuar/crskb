<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Te informamos sobre la importación de tus productos - KEBCO AlmacenDelPintor.com</title>
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
								<img src="http://almacendelpintor.com/solicitud.jpg">
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
									Hola, <?php echo $datosCliente["name"] ?>
								</b></h2>
								<p align="justify">Desde el área de Logística de Kebco, te informamos que la importación a cargo de el (la) asesor (a) <b style="color:#004598;"> <?php echo $datos_asesor["User"]["name"] ?> </b>. <br> Para el (los) siguiente (s) productos:</p>

								
							</td>
							<td style="width:25px;"></td>
						</tr>
						<tr>
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<h3 align="center" style="color: #004598; margin-top: 30px">
									PRODUCTOS SOLICITADOS
									<hr style="color: #004598;background-color: #004598;height: 2px;">
								</b></h3>
							</td>
							<td style="width:25px;"></td>
						</tr>
					</table>
				
					<?php foreach ($requestData["Product"] as $value): ?>
						<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
							<tr>
								<td style="width:50px;"></td>
								<td rowspan="3"><img width="150px" height="150px" src="<?php echo Router::url('/', true).'img/products/'.$value['img'] ?>"></td>
								<td style="width:20px;"></td>
								<td colspan="2">
									<h3 style="margin-bottom: 0px; color:#004598"><?php echo $value['name'] ?></h3>
									<h3 style="margin-bottom: 0px; color:#004598">Fecha posible de entrega: <span style="margin-bottom: 0px; color:#004598"><?php echo $value['ImportRequestsDetailsProduct']['delivery'] ?></span></h3>
									<p>Fabricante: <b><?php echo $value['brand'] ?></b> / N. Parte: <b><?php echo $value['part_number'] ?></b> / Cantidad <b><?php echo $value['ImportRequestsDetailsProduct']['quantity'] ?></b></p>
								</td>

								<td style="width:50px;"></td>
							</tr>

						</table>

					<?php endforeach ?>
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
