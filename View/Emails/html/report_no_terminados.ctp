<!DOCTYPE html>
<html lang="es">
<head>
	<title>Confirmar registro</title>
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
				<br>
				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:50px;"></td>
						<td style="width:500px;">
							<h2><b style="color:#031730;">
								Hola <br>								
							</b></h2>
							<p align="justify">Este es el reporte de servicios técnicos actuales que se encuentran en proceso y no se han terminado:</p>
						</td>
						<td style="width:50px;"></td>
					</tr> 
				</table>
				<hr style="margin: 5px !important; border: 0; border-top: 1px solid #ccc;">
				<?php foreach ($services as $key => $value): ?>
					<table style="width:600px;">
						<tr>
							<td style="width:25px;"></td>
							<td colspan="5" style="width:550px;">
								<p>Fecha de ingreso: <b><?php echo $value["TechnicalService"]["created"] ?></b></p>
								<p>Cliente: <b><?php echo $value["TechnicalService"]["clients_natural_id"] == "0" ? $value["ClientsLegal"]["name"] : $value["ClientsNatural"]["name"] ?></b></p>
								<p> Código: 
									<b>
										<a href="<?php echo Router::url("/",true)."TechnicalServices/view/".$this->Utilities->encryptString($value["TechnicalService"]["id"]); ?>" style="background-color: #004598; color:#fff; text-decoration: none; font-size: 11px; padding: 2px 5px; border-radius: 3px;" >
												<?php echo $value["TechnicalService"]["code"] ?>
										</a>
									</b>
								</p>
								<p> Usuario asignado: <b><?php echo $value["User"]["name"] ?></b></p>
								<p> Requerimiento: <b><?php echo $value["ProductTechnical"][0]["reason"]." - ".$value["ProductTechnical"][0]["equipment"]. " - " .$value["ProductTechnical"][0]["part_number"];  ?></b></p>				
								<p> Dias sin terminar: <b><?php echo $this->Utilities->calculateDays($value["TechnicalService"]["created"],date("Y-m-d")); ?></b></p>				
							</td>
							<td style="width:25px;"></td>
						</tr>							
					</table>
					<hr style="margin: 10px !important; border: 0; border-top: 1px solid #ccc;">
				<?php endforeach;?>
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
				<br>
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





