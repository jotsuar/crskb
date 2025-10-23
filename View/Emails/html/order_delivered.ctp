<!DOCTYPE html>
<html lang="es">
<head>
	<title>Confirmada la entrega del producto</title>
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
							<img src="http://crm.kebco.co/img/gracias-por-comprar.png">
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
							<p style="color:#6f6f6f;">Estamos agradecidos de que hayas comprado en KEBCO S.A.S.</p>
						</td>
						<td style="width:50px;"></td>
					</tr>
				</table>

				<?php if (!empty($productos)): ?>
					
					<br>
					<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr align="center">
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<br>
								<h2>El flujo de trabajo asociado a tu compra fue: <b style="color:#031730; text-transform: capitalize;">
									<?php echo $flujo ?>
								</b></h2>
								<p style="color:#6f6f6f;"> <b></b></p>
							</td>
							<td style="width:50px;"></td>
						</tr>
					</table>

					<br>

					<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr align="center">
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<br>
								<h2><b style="color:#031730; text-transform: capitalize;">
									Los productos asociados son: 
								</b></h2>
								<p style="color:#6f6f6f;"></b></p>
							</td>
							<td style="width:50px;"></td>
						</tr>
					</table>
					<br>
					<?php foreach ($productos as $key => $product): ?>
						<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
							<tr>
								<td style="width:50px;"></td>
								<td rowspan="3"><img width="150px" height="150px" src="<?php echo Router::url('/', true).'img/products/'.$product["Product"]['img'] ?>"></td>
								<td style="width:20px;"></td>
								<td colspan="2">
									<h3 style="margin-bottom: 0px; color:#004598"><?php echo $product["Product"]['name'] ?></h3>
									<p>Fabricante: <b><?php echo $product["Product"]['brand'] ?></b> / N. Parte: <b><?php echo $product["Product"]['part_number'] ?></b> / Cantidad: <b><?php echo $product["QuotationsProduct"]['quantity'] ?></b></p>
								</td>

								<td style="width:50px;"></td>
							</tr>

						</table>
					<?php endforeach ?>


				<?php endif ?>

				<br>
				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:600px;">
							<p style="color:#6f6f6f;">Esperamos seguir contando contigo en futuras compras, no olvides que si tienes algún inconveniente o deseas reportarnos alguna novedad puedes hacerlo mediante los canales de atención que tenemos dispuestos para atenderte</p>

						</td>
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