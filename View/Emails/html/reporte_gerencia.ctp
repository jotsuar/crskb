<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));

?>

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
							<img src="https://www.almacendelpintor.com/images/bg-email.png" width="600px">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr>
						<td style="width:25px;"></td>
						<td style="width:550px; text-align: center;">
							<br>
							<h2>
								<b style="color:#004598; text-transform: capitalize;">
									Hola, se ha generado un nuevo reporte de gerencia para el día <br>  <?php echo date("m/d/Y",strtotime($fecha)) ?>
								</b>
							</h2>
						</td>
						<td style="width:25px;"></td>
					</tr>
				</table>
				<br>
				<br>
				<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
					<tr>
						<td width="225"></td>
						<td width="180" height="30"  align="center">
							<a href="<?php echo Router::url('/', true)."ProspectiveUsers/informe_general?date=".$fecha ?>" style="text-decoration: none;background: #004990;padding: 10px;  border-radius: 11px; color: #fff;">
								VER REPORTE
							</a>
						</td>
						<td width="225"></td>

					</tr>
				</table>
				<br>
				<br>
				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:25px;"></td>
						<td style="width:550px;">
							<img src="http://almacendelpintor.com/logo.jpg">
							<hr>
							<p style="color:#6f6f6f;"><small>Si tienes alguna inquietud puedes contactarnos en nuestras líneas de atención telefónica: </small>
								<b style="color:#004598">MEDELLÍN:</b> Calle 10 No. 52A-18 Int 104 Centro Integral La 10 PBX: (4) 448 5566
								<b style="color:#004598">BOGOTÁ:</b> Av. Calle 26 No. 85D-55, LE 25 C.C. Dorado Plaza PBX: (4) 448 5566
							</p><br>
						</td>
						<td style="width:25px;"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>