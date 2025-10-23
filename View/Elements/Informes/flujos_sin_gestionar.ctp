<?php foreach ($flujos as $key => $value): ?>
	<div class="col-md-6 col-lg-6">
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
	</div>
					
<?php endforeach;  ?>