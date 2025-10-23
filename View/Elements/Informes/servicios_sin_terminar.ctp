<?php 
	
	$usuarios = [];
	$dataUsers = [];

	foreach ($services as $key => $value) {
		$usuarios[$value["User"]["id"]] = $value["User"]["name"];
	}

	foreach ($services as $key => $value) {
		$dataUsers[$value["User"]["id"]][] = $value;
	}
 ?>
<div class="col-md-12">
	<div class="row">
		<?php foreach ($usuarios as $key => $valueData): ?>
			<div class="col-md-6">
				<h2 class="text-info text-center" style="font-size: 1.5rem;">
					Servicios de <?php echo $valueData; ?>
				</h2>
				<div class="table-responsive">
					<?php foreach ($dataUsers[$key] as $userID => $value): ?>
						<table class="table">
							<tr>
								<td>
									<p>Fecha de ingreso: <b><?php echo $value["TechnicalService"]["created"] ?></b></p>
									<p>Cliente: <b><?php echo $value["TechnicalService"]["clients_natural_id"] == "0" ? $value["ClientsLegal"]["name"] : $value["ClientsNatural"]["name"] ?></b></p>
									<p> Código: 
										<b>
											<a href="<?php echo Router::url("/",true)."TechnicalServices/view/".$this->Utilities->encryptString($value["TechnicalService"]["id"]); ?>" style="background-color: #004598; color:#fff; text-decoration: none; font-size: 11px; padding: 2px 5px; border-radius: 3px;" >
													<?php echo $value["TechnicalService"]["code"] ?>
											</a>
										</b>
									</p>
									<?php if (!empty($value["User"]["name"])): ?>
										<p> Usuario asignado: <b><?php echo $value["User"]["name"] ?></b></p>
									<?php endif ?>
									<p> Requerimiento: <b><?php echo $value["ProductTechnical"][0]["reason"]." - ".$value["ProductTechnical"][0]["equipment"]. " - " .$value["ProductTechnical"][0]["part_number"];  ?></b></p>		
									<?php if ($value["TechnicalService"]["state"] == 0): ?>							
										<p> Dias sin terminar: <b><?php echo $this->Utilities->calculateDays($value["TechnicalService"]["created"],date("Y-m-d")); ?></b></p>				
									<?php endif ?>	
									<?php if ($value["TechnicalService"]["state"] == 1 && $value["TechnicalService"]["prospective_users_id"] != 0): ?>
										<p> Estado actual del flujo: <b><?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?></b></p>
										<p> Flujo: 
											<b>
												<a href="<?php echo Router::url("/",true)."prospectiveUsers/index?q=".$value["TechnicalService"]["prospective_users_id"] ?>" style="background-color: #004598; color:#fff; text-decoration: none; font-size: 11px; padding: 2px 5px; border-radius: 3px;" >
														<?php echo $value["TechnicalService"]["prospective_users_id"] ?>
												</a>
											</b>
										</p>
									<?php endif ?>	
								</td>
							</tr>							
						</table>
					<?php endforeach ?>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>		