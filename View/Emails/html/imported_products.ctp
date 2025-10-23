<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Importación finalizada</title>
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
								<h2>
									<b style="color:#004598; text-transform: capitalize;">
										Hola, 
									</b>
								</h2>
								<p align="justify">Los productos asociados a la importación <b style="color:#004598;"><?php echo $code_import ?></b> y a la cotización <b style="color:#004598;"><?php echo $codigoCotizacion ?></b>, ya se encuentran en en nuestras instalaciones y listos para ser enviados a su dirección a la mayor brevedad. Si aún tiene saldos pendientes por favor realizar los pagos correspondientes y adjuntar el soporte a su asesor para continuar con el proceso de despacho.</p>
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

					<?php foreach ($products as $value): ?>

						<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
							<tr>
								<td style="width:50px;"></td>
								<td rowspan="3"><img width="150px" height="150px" src="<?php echo Router::url('/', true).'img/products/'.$value['Product']['img'] ?>"></td>
								<td style="width:20px;"></td>
								<td colspan="2">
									<h3 style="margin-bottom: 0px; color:#004598"><?php echo $value['Product']['name'] ?></h3>
									<p>Fabricante: <b><?php echo $value['Product']['brand'] ?></b> / N. Parte: <b><?php echo $value['Product']['part_number'] ?></b> 
									</p>
								</td>

								<td style="width:50px;"></td>
							</tr>

						</table>

					<?php endforeach ?>

					<br>
					<h3 align="center" style="color: #004598">Gestiona <?php echo $nombreAsesor ?></h3>
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