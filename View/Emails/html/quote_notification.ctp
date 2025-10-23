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
								Hola  
							</b></h2>
							<?php if ($type == "aprovee"): ?>
								<p style="color:#6f6f6f;">Desde el correo <b><?php echo $correoPrincipal ?></b> se aprobó la cotización del flujo: <b><?php echo $flujo ?></b> </p>
							<?php elseif ($type == "comment"): ?>
								<p style="color:#6f6f6f;">Desde el correo <b><?php echo $correoPrincipal ?></b> se ha enviado el un comentario para la cotización generada del flujo <b><?php echo $flujo ?></b> </p>
							<?php elseif ($type == "resend"): ?>
								<p style="color:#6f6f6f;">Desde el correo <b><?php echo $correoPrincipal ?></b> se ha reenviado la cotización al siguiente usuario: </p>
								<p><b>Nombre:</b> <?php echo $nombrePersona ?> </p>
								<p><b>Correo:</b> <?php echo $correoPersona ?> </p>
							<?php endif ?>
							
						</td>
						<td style="width:50px;"></td>
					</tr>
				</table>
				<br>
				<?php if ($type == "comment"): ?>					
					<table style="width: 600px; background-color:#fff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr align="center">
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<div style="background-color: #f9f9f9; padding: 20px 40px; font-size: 16px; font-style: italic;">
									<p style="color:#6f6f6f !important; font-size: 16px !important; font-family: inherit !important;" class="resetcopieasesor">
										<?php echo $comentarioQt; ?>
									</p>							
								</div>
							</td>
							<td style="width:50px;"></td>
						</tr>
					</table>
				<?php endif ?>
				<?php if ($type == "aprovee"): ?>					
					<table style="width: 600px; background-color:#fff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr align="center">
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<div style="background-color: #f9f9f9; padding: 20px 40px; font-size: 16px; font-style: italic;">
									<p style="color:#6f6f6f !important; font-size: 16px !important; font-family: inherit !important;" class="resetcopieasesor">
										<?php echo $comentarioCliente; ?>
									</p>							
								</div>
							</td>
							<td style="width:50px;"></td>
						</tr>
					</table>
				<?php endif ?>
				<br>
				<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
					<tr>
						<td width="225"></td>
						<td width="180" height="30"  align="center">
							<a href="<?php echo Router::url('/', true).$ruta ?>" style="color:#ffffff; text-decoration:none;">
								<img src="http://crm.kebco.co/img/vercotizacion.jpg">
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

<style type="text/css">
	.box span , .box p , .box h1 , .box h2 , .box h3 , .box h4 , .box h5 , .box h6 , .box b , .box u , .box strong , .box p , .box ul li , .box div{
		font-size: 1rem !important;
		font-family: Helvetica, Arial, Sans-Serif !important;
		text-align: center !important;
	}
</style>