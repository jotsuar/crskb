<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Cliente asignado</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta charset="utf-8" />solicitud de importacion
	</head>
	<body style="background-color:#f8f8f8;">
		<table align="center" style="width: 600px; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;background-color:#ffffff;">
			<tr>
				<td>
					<table style="width: 600px;  display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr>
							<td>
								<img src="http://almacendelpintor.com/enproceso.jpg">
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
								<p align="justify">Su orden de importación ha sido creada con los (el) siguiente(s) producto(s):</p>
								<u>
									<ul style="color: #004598">
										<?php foreach ($products as $value): ?>
											<li style="line-height: 1.5"><?php echo $value['Product']['part_number'].' '.$value['Product']['name'] ?> </li>	
										<?php endforeach ?>
									</ul>
								</u>
								<?php if ($solicitud == 0) { ?>
									<p align="justify">A partir de ahora esta órden se identifica con el ID <b style="color:#004598"><?php echo $code_import ?></b>, recuerde que el tiempo estimado de importación es de hasta <b style="color:#004598">10 - 12 días hábiles</b> a partir de esta fecha.</p>
								<?php } else { ?>
									<p align="justify">correspondientes a la cotización <b style="color:#004598"> <?php echo $codigoCotizacion ?></b>, a partir de ahora esta órden se identifica con el ID <b style="color:#004598"><?php echo $code_import ?></b>, recuerde que el tiempo estimado de importación es de hasta <b style="color:#004598">10 - 12 días hábiles</b> a partir de esta fecha.</p>
								<?php } ?>
								<br>
								<h3 align="center" style="color: #004598">Gestiona <?php echo $nombreAsesor ?></h3>
							</td>
							<td style="width:25px;"></td>
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