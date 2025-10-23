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
						<td style="width:550px;">
							<br>
							<h2><b style="color:#004598; text-transform: capitalize;">
								Hola,
							</b></h2>
							<p align="justify">Actualmente se tienen las siguientes solicitudes de importación por marca, que deben ser enviadas a gerencia:</p>
							<b>NOTA: Tener presente las que pertenecen a un flujo y están proximas a vencer</b>
						</td>
						<td style="width:25px;"></td>
					</tr>
				</table>
				<?php if (!empty($requests)): ?>
					
					<?php foreach ($requests as $key => $request): ?>
						<table style="width: 600px; background-color:#ffffff; color:#111E2D; display:block; margin: 0 auto; border-collapse: collapse; font-family: Helvetica, Arial, Sans-Serif;">
							<tr>
								<td style="width:25px;"></td>
								<td style="width:550px;">
									<h2>
										<b style="color:#004598;">
											Proveedor: <u><?php echo $request["Brand"]["name"] ?></u>
										</b>
									</h2>
								</td>
								<td style="width:25px;"></td>
							</tr>
						</table>
						<?php foreach ($request["ImportRequestsDetail"] as $keyDetails => $details): ?>
							<?php if (!isset($details["InfoProducts"]) || empty($details["InfoProducts"])): ?>
								<?php continue; ?>
							<?php endif ?>
							<table style="width:600px;">
								<tr>
									<td style="width:25px;"></td>
									<td colspan="5" style="width:550px;">
										<b> 
											<?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$details["type_request"]) ?> 
											<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
												(<?php echo $details["prospective_user_id"] ?>)
											<?php endif ?>
										</b><br>
										<small>Solicita <?php echo $details["InfoProducts"]["User"]["name"] ?> 
											<?php if (isset($details["created"]) && !empty($details["created"])): ?>
												el <?php echo $this->Utilities->date_castellano(h($details['created'])) ?>
											<?php endif ?>

									</small>
										<br>
										<?php if (isset($details["type_request"]) && $details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
											<b>Flujo de negocio</b>: 
											<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$details["prospective_user_id"])) ?>" style="background-color: #004598; color:#fff; text-decoration: none; font-size: 11px; padding: 2px 5px; border-radius: 3px;" >
												Ver
											</a>
											<?php else: ?>
												<?php if (isset($details["description"])): ?>
													<b>Razón de la importación</b>: <?php echo $details["description"] ?>													
												<?php endif ?>
											<?php endif;?>
									</td>
									<td style="width:25px;"></td>

									</tr>
									<tr>
										<td style="width:25px;"></td>
										<td><b>Cant.</b></td>
										<td><b>Foto</b></td>
										<td><b>Producto</b></td>
										<td><b>Ref.</b></td>
										<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
										<td style="width: 25% !important; padding-left: 15px;"><b>F. Entrega</b></td>
										<?php endif;?>
										<td style="width:25px;"></td>	
									</tr>
										<?php if (isset($details["InfoProducts"]["Product"]) && !empty($details["InfoProducts"]["Product"]) ): ?>
											<?php foreach ($details["InfoProducts"]["Product"] as $idProduct => $value): ?>
											<tr>
												<td style="width:25px;"></td>
												<td style="text-align: center;">
													<?php echo $value["ImportRequestsDetailsProduct"]["quantity"] ?>
												</td>
												<td>
													<?php $ruta = $this->Utilities->validate_image_products($value['img']); ?>
													<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value['name']); ?>" src="https://crm.kebco.co/img/products/<?php echo $ruta ?>" width="100px" height="100px" class="imgmin-product">
												</td>
												
												<td style="text-transform: uppercase;">
													<?php echo $this->Text->truncate(strip_tags($value['name']), 50,array('ellipsis' => '...','exact' => false)); ?>
												</td>
												<td>
													<?php echo $value["part_number"]?>
												</td>
												<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
												<td style="width: 25% !important; padding-left: 15px;">
												<?php 
												$fecha = $this->Utilities->calculateFechaFinalEntrega($details["created"],Configure::read("variables.entregaProductValues.".$value["ImportRequestsDetailsProduct"]["delivery"]));
												$dataDay = $this->Utilities->getClassDate($fecha);
												?>
												<span class=" font-weight-bold "><?php echo $fecha; ?> </span>
												<br>
												<?php if ($dataDay == 0): ?>
													<span style="background-color: red; color: #fff; font-size: 11px; padding: 2px 5px; border-radius: 3px;">!Entrega hoy!</span>
													<?php elseif($dataDay > 0): ?>
													<span style="background-color: red; color: #fff; font-size: 11px; padding: 2px 5px; border-radius: 3px;">!Retraso de <?php echo abs($dataDay) ?> día(s)</span>
													<?php elseif($dataDay <= -5): ?>
													<span style="background-color: green; color: #fff; font-size: 11px; padding: 2px 5px; border-radius: 3px;">Faltan <?php echo abs($dataDay) ?> día(s)</span>
													<?php else: ?>
													<span style="background-color: yellow; color: #fff; font-size: 11px; padding: 2px 5px; border-radius: 3px;">Faltan  <?php echo abs($dataDay) ?> día(s)</span>
													<?php endif ?>
												</td>
												<?php endif;?>
											</tr>
										<?php endforeach ?>
									<?php endif ?>
									
							</table>
							<hr style="margin: 20px !important; border: 0; border-top: 1px solid #ccc;">
							<?php endforeach;  ?>
													
						<?php endforeach;  ?>
				<?php endif ?>
					
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