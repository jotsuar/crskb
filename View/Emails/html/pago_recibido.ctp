
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Cliente asignado</title>
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
								Hola <?php echo $this->Utilities->data_null(h($name)) ?> 
							</b></h2>
							<p style="color:#6f6f6f;">
								Hola, el cliente <b><?php echo $cliente ?></b>, con correo registrado en el CRM <b><?php echo $email ?></b>. Ha realizado un pago por medio de la plataforma <b>PSE</b>
							</p>
							<p style="color:#6f6f6f;">
								La cotización a la cual realizó el pago fue: <b><?php echo $datosQuation["Quotation"]["codigo"] ?></b> 
								<?php if (!empty($datosProduct)): ?>
									y el producto: <b><?php echo $datosProduct["Product"]["part_number"]  ?></b>									
								<?php else: ?>
									| <?php echo $datosQuation["Quotation"]["name"] ?>
								<?php endif ?>
							</p>
						</td>
						<td style="width:25px;"></td>
					</tr>
				</table>
				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:50px;"></td>
						<td style="width:500px;">
							<p style="color:#6f6f6f;">Los datos del pago son los siguientes</p>
						</td>
						<td style="width:25px;"></td>
					</tr>
				</table>
				<br>
				<br>
				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:600px;">
							<table>
								<tbody>
									<?php foreach ($reqData as $key => $value): ?>
										<tr>
											<th>
												<?php echo strtoupper($key) ?>
											</th>
											<td>
												<?php echo $value ?>
											</td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
				<br>
				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:50px;"></td>
						<td style="width:500px;">
							<p style="color:#6f6f6f;">
								Por favor contacta a el cliente lo más pronto posible y realiza la gestión del flujo correspondiente
							</p>
						</td>
						<td style="width:25px;"></td>
					</tr>
				</table>
				<br>
				<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
					<tr>
						<td width="225"></td>
						<td width="150" height="30"  align="center">
							<a href="<?php echo Router::url('/', true)."prospective_users/index?q=".$datosQuation["Quotation"]["prospective_users_id"] ?>" style="color:#ffffff; text-decoration:none;">
								<img src="http://crm.kebco.co/img/verenflujos.jpg">
							</a>
						</td>
						<td width="225"></td>

					</tr>
				</table>
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






