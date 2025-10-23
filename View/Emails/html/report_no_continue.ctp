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
									Reporte de flujos en estado pagado sin gestionar
								</b>
							</h2>
						</td>
						<td style="width:25px;"></td>
					</tr>
				</table>
				<hr style="margin: 5px !important; border: 0; border-top: 1px solid #ccc;">
				<?php foreach ($flujos as $key => $value): ?>
					<table style="width:600px;">
						<tr>
							<td style="width:25px;"></td>
							<td colspan="5" style="width:550px;">
								<p> Cliente: <b><?php echo $this->Utilities->name_prospective($value["ProspectiveUser"]["id"]) ?></b></p>
								<p> Flujo: 
									<b>
										<a href="<?php echo Router::url("/",true)."prospectiveUsers/index?q=".$value["ProspectiveUser"]["id"] ?>" style="background-color: #004598; color:#fff; text-decoration: none; font-size: 11px; padding: 2px 5px; border-radius: 3px;" >
												<?php echo $value["ProspectiveUser"]["id"] ?>
										</a>
									</b>
								</p>
								<p> Medio de llegada: <b><?php echo $value["ProspectiveUser"]["origin"] ?></b></p>
								<p> Fecha de ingreso: <b><?php echo $value["ProspectiveUser"]["created"] ?></b></p>
								<p> Fecha pago validado: <b><?php echo $value["FlowStage"]["date_verification"] ?></b></p>
								<p> Requerimiento: <b><?php echo $value["ProspectiveUser"]["description"] ?></b></p>
								<p> Estado actual del flujo: <b><?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?></b></p>
								<p> Usuario asignado: <b><?php echo $this->Utilities->find_name_lastname_adviser($value["ProspectiveUser"]["user_id"]) ?></b></p>								
							</td>
							<td style="width:25px;"></td>
						</tr>							
					</table>
					<hr style="margin: 10px !important; border: 0; border-top: 1px solid #ccc;">
									
				<?php endforeach;  ?>
				
					
				<br>
				<table border="0" align="center" cellpadding="0" cellspacing="0" class="mbtn20 mtop10" cellmargin="0">
					<tr>
						<td width="225"></td>
						<td width="150" height="30"  align="center">
							<a href="<?php echo Router::url('/', true).'ProspectiveUsers/request_import_brands' ?>" style="color:#ffffff; text-decoration:none;">
								<img src="http://crm.kebco.co/img/verenflujos.jpg">
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