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
							<img src="http://crm.kebco.co/img/nueva-orden-st.png">
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
							<p style="color:#6f6f6f;">El Técnico de Servicio <b style="color:#031730"><?php echo $this->Utilities->data_null(h($nameAsesor)) ?></b> de KEBCO S.A.S. ha generado la <b style="color: #005cf0">ÓRDEN DE SERVICIO</b> número <b style="color:#031730"><?php echo $codigo ?></b> para tus equipos</p>
						</td>
						<td style="width:50px;"></td>
					</tr>
				</table>
				<br>

				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:50px;"></td>
						<td style="width:500px;">

							<p style="color:#6f6f6f;">Para observar la orden generada, por favor has clic en el siguiente enlace.</p>
						</td>
						<td style="width:50px;"></td>
					</tr>
				</table>
				<br>
				<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
					<tr>
						<td width="175"></td>
						<td width="280" height="30" align="center">
							<a href="<?php echo Router::url('/', true).$ruta ?>" style="color:#ffffff; text-decoration:none;">
								<img src="http://crm.kebco.co/img/verorden.jpg">
							</a>
						</td>
						<td width="175"></td>
					</tr>
				</table>
				<br>

				<br>
				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:50px;"></td>
						<td style="width:500px;">
							<hr>
							<p style="color:#6f6f6f;"><span>KEBCO S.A.S. &copy; </span></p><br>
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