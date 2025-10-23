<!DOCTYPE html>
<html lang="es">
<head>
	<title>Tu pedido ha sido Enviado</title>
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
							<img src="http://crm.kebco.co/img/enviamos-tus-productos.png">
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
							<p style="color:#6f6f6f;">Queremos informarte que tu pedido en KEBCO S.A.S. se encuentra en estado: 
								<b>
									
								<?php 
									switch ($shipping['Shipping']['state']) {
										case '1':
											echo "En preparación";
											break;
										case '2':
											echo "Enviado";
											break;
										case '3':
											echo "Entregado";
											break;
									}
								?>

								</b>
							</p>
						</td>
						<td style="width:50px;"></td>
					</tr>
				</table>
				<br>
				<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
					<tr align="center">
						<td style="width:600px;">
							<?php if ($shipping["Shipping"]["state"] == 2): ?>
								<p style="color:#6f6f6f;">A continuación te enviamos la información detallada del envío:</p>
								<p style="color:#031730;"><b>Número de guía: </b><?php echo $this->Utilities->data_null(h($shipping["Shipping"]["guide"])) ?></p>
								<p style="color:#031730;"><b>Fecha probable de entrega: </b><?php echo $shipping["Shipping"]["date_deadline"] ?></p>
								<p style="color:#031730;"><b>Transportadora: </b><?php echo $this->Utilities->data_null(h($shipping["Conveyor"]["name"])) ?></p>
								<p style="color:#031730;"><b>Comprobante de envio: </b>
									<img width="100%" src="<?php echo Router::url('/', true).$ruta ?>">
									<?php echo $comprobanteImg ?>
								</p>
							<?php endif ?>
						</td>
					</tr>  
				</table>

				<?php if ($shipping["Shipping"]["state"] == 2): ?>
					<br>
					<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr align="center">
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<br>
								<h2><b style="color:#031730; text-transform: capitalize;">
									Se enviaron los siguientes productos: 
								</b></h2>
							</td>
							<td style="width:50px;"></td>
						</tr>
					</table>
					<br>
					<?php foreach ($shipping["Product"] as $key => $product): ?>
						<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
							<tr>
								<td style="width:50px;"></td>
								<td rowspan="3"><img width="150px" height="150px" src="<?php echo Router::url('/', true).'img/products/'.$product['img'] ?>"></td>
								<td style="width:20px;"></td>
								<td colspan="2">
									<h3 style="margin-bottom: 0px; color:#004598"><?php echo $product['name'] ?></h3>
									<p>Fabricante: <b><?php echo $product['brand'] ?></b> / N. Parte: <b><?php echo $product['part_number'] ?></b></p>	
								</td>

								<td style="width:50px;"></td>
							</tr>

						</table>
					<?php endforeach ?>
				<?php endif ?>
				<br>
				<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
					<tr>
						<td width="225"></td>
						<td width="180" height="30"  align="center">
							<a href="<?php echo Router::url('/', true)."plataforma_clientes/loguin" ?>" style="text-decoration: none;background: #004990;padding: 10px;  border-radius: 11px; color: #fff;">
								Realizar seguimiento
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
