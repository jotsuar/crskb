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
							<?php if ($transportadora == "Entrega en Oficina"): ?>
								<p style="color:#6f6f6f;">Queremos informarte que hemos entregado el pedido comprado en KEBCO S.A.S. en nuestra tienda asociado al flujo <b><?php echo $flujo ?></b></p>
							<?php else: ?>
								<p style="color:#6f6f6f;">Queremos informarte que hemos enviado el pedido comprado en KEBCO S.A.S. asociado al flujo <b><?php echo $flujo ?></b></p>
							<?php endif ?>
						</td>
						<td style="width:50px;"></td>
					</tr>
				</table>
				<?php if ($transportadora != "Entrega en Oficina"): ?>
					<br>
					<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr align="center">
							<td style="width:600px;">
								<p style="color:#6f6f6f;">A continuación te enviamos la información detallada del envío:</p>
								<p style="color:#031730;"><b>Número de guía: </b><?php echo $this->Utilities->data_null(h($numeroGuia)) ?></p>
								<p style="color:#031730;"><b>Transportadora: </b><?php echo $this->Utilities->data_null(h($transportadora)) ?></p>
								<p style="color:#031730;"><b>Fecha: </b><?php echo $this->Utilities->data_null(h(date("d-m-Y"))); ?></p>
								<p style="color:#031730;"><b>Comprobante de envio: </b>
									<img width="100%" src="<?php echo Router::url('/', true).$ruta ?>">
									<?php echo $comprobanteImg ?>
								</p>
							</td>
						</tr>  
					</table>
				<?php endif ?>
				<?php if (!empty($products)): ?>
					<br>
					<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr align="center">
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<br>
								<h2><b style="color:#031730; text-transform: capitalize;">
									Los productos son los siguientes:
								</b></h2>
							</td>
							<td style="width:50px;"></td>
						</tr>
					</table>
					<br>
					<?php foreach ($products as $value): ?>

						<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
							<tr>
								<td style="width:50px;"></td>
								<td rowspan="3"><img width="150px" height="150px" src="<?php echo Router::url('/', true).'img/products/'.$value['SendProduct']["Product"]['img'] ?>" style="width: 150px; height: 150px;"></td>
								<td style="width:20px;"></td>
								<td colspan="2">
									<h3 style="margin-bottom: 0px; color:#004598"><?php echo $value['SendProduct']["Product"]['name'] ?></h3>
									<p>N. Parte: <b><?php echo $value['SendProduct']["Product"]['part_number'] ?></b> / Cantidad <b><?php echo $value['SendProduct']['quantity'] ?></b></p>
								</td>

								<td style="width:50px;"></td>
							</tr>

						</table>

					<?php endforeach ?>
				<?php endif ?>
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
