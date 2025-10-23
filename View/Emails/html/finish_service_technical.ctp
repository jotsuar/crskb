<!DOCTYPE html>
<html lang="es">
<head>
	<title>Cotización</title>
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
							<img src="http://crm.kebco.co/img/diagnostico-st.png">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:50px;"></td>
						<td style="width:500px;">
							<br>
							<h2><b style="color:#031730; text-transform: capitalize;">
								Hola <?php echo $this->Utilities->data_null(h($nameClient)) ?> 
							</b></h2>
							<p style="color:#6f6f6f;">El Técnico de Servicio <b style="color:#031730"><?php echo $this->Utilities->data_null(h($nameAsesor)) ?></b> de KEBCO S.A.S. ha finalizado la orden de servicio número <b style="color:#031730"><?php echo $codigo ?></b> y <b style="color: #2db700">HA GENERADO EL DIAGNÓSTICO</b> de tus equipos</p>
							<?php if (isset($cotizacion) && $cotizacion === false): ?>
							
								<p style="color:#6f6f6f;">
									Debido a que no se realizó cotización luego de terminado el diagnóstico, te informamos que tienes un plazo máximo de <b>30 días calendario</b> para recoger el equipo de nuestras instalaciones, luego de este tiempo no se responderá por el(los) equipos y se procederá al desecho de el(los) mismo(s).
								</p>
								
							<?php endif ?>
						</td>
						<td style="width:50px;"></td>
					</tr>
				</table>
				<br>

				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:50px;"></td>
						<td style="width:500px;">

							<p style="color:#6f6f6f;">Para ver el reporte de diagnóstico generado para tu orden, por favor has clic en el siguiente enlace.</p>
						</td>
						<td style="width:50px;"></td>
					</tr>
				</table>
				<br>
				<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
					<tr>
						<td width="225"></td>
						<td width="180" height="30" align="center">
							<a href="<?php echo Router::url('/', true).$ruta ?>" style="color:#ffffff; text-decoration:none;">
								<img src="http://crm.kebco.co/img/verdiagnostico.jpg">
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
							<hr>
							<p style="color:#6f6f6f;"><span>KEBCO S.A.S. &copy;</span></p><br>
						</td>
						<td style="width:50px;"></td>
					</tr>
				</table>


				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">

						<td style="width:430px;">

						</td>
						<td style="width:30px;">
							<a href="https://www.instagram.com/almacendelpintor/" target="_blank"><img src="https://www.almacendelpintor.com/images/instagram.png"></a>
						</td>

						<td style="width:30px;">
							<a href="https://es-la.facebook.com/almacendelpintor/" target="_blank"><img src="https://www.almacendelpintor.com/images/facebook.png"></a>
						</td>

						<td style="width:30px;">
							<a href="https://www.youtube.com/channel/UC6zCS454FXLuICISYmqtOtA" target="_blank"><img src="https://www.almacendelpintor.com/images/youtube.png"></a>
						</td>

						<td style="width:50px;">
						</td>

					</tr>
				</table>

			</td>
		</tr>
	</table>
</body>
</html>