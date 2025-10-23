<!DOCTYPE html>
<html lang="es">
<head>
	<title>Alerta Servicio técnico</title>
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
					<tr align="center">
						<td style="width:50px;"></td>
						<td style="width:500px;">
							<br>
							<h2><b style="color:#031730; text-transform: capitalize;">
								Hola <?php echo $this->Utilities->data_null(h($name)); ?> 
							</b></h2>
							<p align="justify">
								Desde el área de <b>Servicio técnico de Kebco</b>, detectamos una demora de <b><?php echo $tiempo ?> días</b> en la continuidad del proceso de un equipo ingresado a nuestras instalaciones el día <b><?php echo date("Y-m-d",strtotime($servicio["created"])) ?></b> con el código de servicio <b><?php echo $servicio["code"] ?></b>.
							</p>
							<?php if ($tiempo == 100): ?>
								<br>
								<p align="justify">
									Según nuestras políticas el equipo procederá a ser desechado.	
								</p>
							<?php endif ?>
							<br>
							<p>Puedes comunicarte con el asesor encargado de tu proceso a los siguientes médios: </p>
							<p><b>Nombre:</b> <?php echo $asesor["name"] ?></p>
							<p><b>Celular:</b> <?php echo $asesor["cell_phone"] ?></p>
							<p><b>Teléfono:</b> <?php echo $asesor["telephone"] ?></p>
							<p><b>Correo electrónico:</b> <?php echo $asesor["email"] ?></p>

						</td>
						<td style="width:50px;"></td>
					</tr>
				</table>
				<br>
				<?php if (!empty($this->Utilities->find_id_document_quotation_send($servicio['prospective_users_id']))): ?>
					
					<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
						<tr>
							<td width="225"></td>
							<td width="180" height="30"  align="center">
								<a href="<?php echo $this->Html->url(['controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($this->Utilities->find_id_document_quotation_send($servicio['prospective_users_id']))],true) ?>" style="text-decoration: none;background: #004990;padding: 10px;  border-radius: 11px; color: #fff;">
									VER COTIZACIÓN
								</a>
							</td>
							<td width="225"></td>

						</tr>
					</table>
					<br>
				<?php endif ?>
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
