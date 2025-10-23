<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Cambio de estado despacho CRM</title>
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
						<tr>
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<br>
								<h2><b style="color:#004598;">
									Hola,
									<p align="justify">El asesor <?php echo $nameAsesor ?>, ha cambiado la solicitud de despacho y/o facturación para la Orden de pedido # <b> <?php echo $order_num ?> </b> relacionada al flujo <?php echo $flujo ?> </b> </p>
									<p align="justify">El estado de la solicitud es: <b>
										<?php 

											switch ($shipping['Shipping']['state']) {
												case '-1':
													echo "Solicitud cancelada rechazada";
													break;
												case '0':
													echo "Solicitud creada";
													break;
												case '1':
													echo "Solicitud en preparación";
													break;
												case '2':
													echo "Solicitud enviada y/o facturada";
													break;
												case '3':

													if ($shipping["Shipping"]["request_envoice"] == 1) {
														echo "Despacho enviado y factura solicitada";
													}elseif($shipping["Shipping"]["request_envoice"] == 2){
														echo "Despacho enviado y factura cargada";
													}elseif ($shipping["Shipping"]["request_shipping"] == 1) {
														echo "Despacho solicitado y factura cargada";
													}elseif($shipping["Shipping"]["request_shipping"] == 2){
														echo "Despacho enviado y factura cargada";
													}else{
														echo "Solicitud entregada";
													}
													
													break;
											}

										 ?>
									</b> </p>
									<?php if ($shipping["Shipping"]["state"] == -1): ?>
										<p align="justify">El motivo de la cancelación es: <b><?php echo $shipping["Shipping"]["note_bill"] ?></b></p>
									<?php else: ?>
										<?php if (!empty($shipping["Shipping"]["note_bill"])): ?>
											<p align="justify">Nota de gestión: <b><?php echo $shipping["Shipping"]["note_bill"] ?></b> </p> <br><br>
										<?php endif ?>
										<p align="justify">Por favor revísalo y has la gestión que aplique.</p>
									<?php endif ?>
									
								<br>
								<br>
							</td>
							<td style="width:25px;"></td>
						</tr>
					</table>				
					<br>
					<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
						<tr>
							<td width="155"></td>
							<td width="460" height="30"  align="center">
								<a href="<?php echo Router::url('/', true) ?>shippings/view/<?php echo $this->Utilities->encryptString($id) ?>" style="text-decoration: none;background: #004990;padding: 10px;  border-radius: 11px; color: #fff;">
									VER SOLICITUD DE DESPACHO Y/O FACTURACIÓN
								</a>
							</td>
							<td width="215"></td>

						</tr>
					</table>
					<br>
					<br>
					<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
						<tr align="center">
							<td style="width:50px;"></td>
							<td style="width:500px;">
								<img src="http://almacendelpintor.com/logo.jpg">
								<hr>
								<p style="color:#6f6f6f;"><small>Si tienes alguna inquietud puedes contactarnos en nuestras líneas de atención telefónica: </small>
									<b style="color:#004598">MEDELLÍN:</b> Calle 10 No. 52A-18 Int 104 Centro Integral La 10 PBX: (4) 448 5566
									<b style="color:#004598">BOGOTÁ:</b> Av. Calle 26 No. 85D-55, LE 25 C.C. Dorado Plaza PBX: (4) 448 5566
									</p><br>
							</td>
							<td style="width:50px;"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>