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
				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:50px;"></td>
						<td style="width:500px;">
							<br>
							<h2><b style="color:#031730; text-transform: capitalize;">
								Hola, 
							</b></h2>
							<p style="color:#6f6f6f;">
								<?php if (!isset($account_review)): ?>
									Se ha registrado un cambio en la cuenta de cobro: 
								<?php else: ?>
									El asesor externo: <b><?php echo $account["User"]["name"] ?></b> realizó una solicitud de cuenta de cobro por un valor de: <b>$<?php echo number_format($account["Account"]["initial_value"]) ?></b>
								<?php endif ?>
							</p>
						</td>
						<td style="width:50px;"></td>
					</tr>
				</table>
				<br>
				<?php if (!isset($account_review)): ?>					
					<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr align="center">
							<td style="width:600px;">
								<p style="color:#6f6f6f;">Estado: 
									<?php $estados = ["0" => "Solicitada", "1" => "En gestión", "2" => "Pagada", "3" => "Rechazada" ]; ?>
	                				<?php echo $estados[$account['Account']['state']] ?>
								</p>
								<?php if ($account["Account"]["state"] == 1): ?>
									<p style="color:#031730;">Fecha de gestión: <b> <?php echo $account['Account']['date_gest'] ?> </b></p>
									<p style="color:#031730;">Posible fecha de pago: <b> <?php echo $account['Account']['date_deadline'] ?> </b></p>
								<?php endif ?>
								<?php if ($account["Account"]["state"] == 2): ?>
									<p style="color:#031730;">Fecha de pago: <b> <?php echo $account['Account']['date_payment'] ?> </b></p>
									<p style="color:#031730;">Valor de pago: <b> $<?php echo number_format($account['Account']['value_payment']) ?> </b></p>
									<p style="color:#031730;">Notas adicionales: <b> <?php echo $account['Account']['notes'] ?> </b></p>
								<?php endif ?>
								<?php if ($account["Account"]["state"] == 3): ?>
									<p style="color:#031730;">Fecha de rechazo: <b> <?php echo $account['Account']['modified'] ?> </b></p>
									<p style="color:#031730;">Motivo adicionales: <b> <?php echo $account['Account']['notes'] ?> </b></p>
								<?php endif ?>
							</td>

						</tr>  
					</table>
				<?php endif ?>
				<br>

				<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
					<tr>
						<td width="225"></td>
						<td width="150" height="30" bgcolor="#031730" align="center">
							<a href="<?php echo Router::url('/', true).'ProspectiveUsers/informe_comisiones_externals_gest' ?>" style="color:#ffffff; text-decoration:none;">
								<span style="font-family:arial; font-size:14px;color:#ffffff;">REVISAR INFORMACIÓN</span>
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