<?php $clientesNaturalesNuevosConFlujo = $data["clientesNaturalesNuevosConFlujo"]; ?>
<?php $clientesLegalesNuevosConFlujo = $data["clientesLegalesNuevosConFlujo"]; ?>
<?php $clientesNaturalesViejosConFlujo = $data["clientesNaturalesViejosConFlujo"]; ?>
<?php $clientesLegalesViejosConFlujo = $data["clientesLegalesViejosConFlujo"]; ?>
<?php $clientesNaturalesNuevosConFlujo = $data["clientesNaturalesNuevosConFlujo"]; ?>
<?php $day = $data["day"]; ?>
<div class="col-md-12 col-lg-12">
<table class="table">
	<tr>
		
		<td>
			<table class="table">
				<tr>
					<td>Prospectos naturales nuevos en flujo - <?php echo date("d/m/Y",strtotime($day)) ?>:</td>
					<td><?php echo count($clientesNaturalesNuevosConFlujo) ?></td>
				</tr>
				<tr>
					<td>Prospectos juridicos nuevos en flujo - <?php echo date("d/m/Y",strtotime($day)) ?>:</td>
					<td><?php echo count($clientesLegalesNuevosConFlujo) ?></td>
				</tr>
				<tr>
					<td>Prospectos naturales antiguos en flujo - <?php echo date("d/m/Y",strtotime($day)) ?>:</td>
					<td><?php echo count($clientesNaturalesViejosConFlujo) ?></td>
				</tr>
				<tr>
					<td>Prospectos juridicos antiguos en flujo - <?php echo date("d/m/Y",strtotime($day)) ?>:</td>
					<td><?php echo count($clientesLegalesViejosConFlujo) ?></td>
				</tr>
				<tr>
					<td>
						Total:
					</td>
					<td>
						<?php echo count($clientesNaturalesNuevosConFlujo) + count($clientesLegalesNuevosConFlujo) + count($clientesNaturalesViejosConFlujo) + count($clientesLegalesViejosConFlujo) ?>
					</td>
				</tr>
			</table>
		</td>
		
	</tr>
</table>
</div>
<div class="col-md-6 col-lg-6">

<table class="table">
	<tr>
		
		<td>
			<h3>
				<b style="color:#004598;">
					Prospectos naturales nuevos en flujo: <u><?php echo count($clientesNaturalesNuevosConFlujo) ?></u>
				</b>
			</h3>
		</td>
		
	</tr>
</table>

<?php foreach ($clientesNaturalesNuevosConFlujo as $key => $value): ?>
	<table class="table">
		<tr>
			
			<td colspan="5">
				<p> Cliente: <b><?php echo $value["ClientsNatural"]["name"] ?></b></p>
				<p> Flujo: 
					<b>
						<a href="<?php echo Router::url("/",true)."prospectiveUsers/index?q=".$value["ProspectiveUser"]["id"] ?>" style="background-color: #004598; color:#fff; text-decoration: none; font-size: 11px; padding: 2px 5px; border-radius: 3px;" >
								<?php echo $value["ProspectiveUser"]["id"] ?>
						</a>
					</b>
				</p>
				<p> Medio de llegada: <b><?php echo $value["ProspectiveUser"]["origin"] ?></b></p>
				<p> Requerimiento: <b><?php echo $value["ProspectiveUser"]["description"] ?></b></p>
				<p> Estado actual del flujo: <b><?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?></b></p>
				<p> Usuario asignado: <b><?php echo $value["User"]["name"] ?></b></p>								
			</td>
			
		</tr>							
	</table>
	
					
<?php endforeach;  ?>
</div>
<div class="col-md-6 col-lg-6">
<table class="table">
	<tr>
		
		<td>
			<h3>
				<b style="color:#004598;">
					Prospectos naturales antiguos en flujo: <u><?php echo count($clientesNaturalesViejosConFlujo) ?></u>
				</b>
			</h3>
		</td>
		
	</tr>
</table>
<?php foreach ($clientesNaturalesViejosConFlujo as $key => $value): ?>
	<table class="table">
		<tr>
			
			<td colspan="5">
				<p> Cliente: <b><?php echo $value["ClientsNatural"]["name"] ?></b></p>
				<p> Flujo: 
					<b>
						<a href="<?php echo Router::url("/",true)."prospectiveUsers/index?q=".$value["ProspectiveUser"]["id"] ?>" style="background-color: #004598; color:#fff; text-decoration: none; font-size: 11px; padding: 2px 5px; border-radius: 3px;" >
								<?php echo $value["ProspectiveUser"]["id"] ?>
						</a>
					</b>
				</p>
				<p> Medio de llegada: <b><?php echo $value["ProspectiveUser"]["origin"] ?></b></p>
				<p> Requerimiento: <b><?php echo $value["ProspectiveUser"]["description"] ?></b></p>
				<p> Estado actual del flujo: <b><?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?></b></p>
				<p> Usuario asignado: <b><?php echo $value["User"]["name"] ?></b></p>								
			</td>
			
		</tr>							
	</table>
	
					
<?php endforeach;  ?>
</div>
<div class="col-md-6 col-lg-6">
<table class="table">
	<tr>
		
		<td>
			<h3>
				<b style="color:#004598;">
					Prospectos juridicos nuevos en flujo: <u><?php echo count($clientesLegalesNuevosConFlujo) ?></u>
				</b>
			</h3>
		</td>
		
	</tr>
</table>

<?php foreach ($clientesLegalesNuevosConFlujo as $key => $value): ?>
	<table class="table">
		<tr>
			
			<td colspan="5">
				<p> Cliente: <b><?php echo $value["ClientsLegal"]["name"] ?></b></p>
				<p> Contacto: <b><?php echo $value["ContacsUser"]["name"] ?></b></p>
				<p> Flujo: 
					<b>
						<a href="<?php echo Router::url("/",true)."prospectiveUsers/index?q=".$value["ProspectiveUser"]["id"] ?>" style="background-color: #004598; color:#fff; text-decoration: none; font-size: 11px; padding: 2px 5px; border-radius: 3px;" >
								<?php echo $value["ProspectiveUser"]["id"] ?>
						</a>
					</b>
				</p>
				<p> Medio de llegada: <b><?php echo $value["ProspectiveUser"]["origin"] ?></b></p>
				<p> Requerimiento: <b><?php echo $value["ProspectiveUser"]["description"] ?></b></p>
				<p> Estado actual del flujo: <b><?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?></b></p>
				<p> Usuario asignado: <b><?php echo $value["User"]["name"] ?></b></p>								
			</td>
			
		</tr>							
	</table>
	
					
<?php endforeach;  ?>
</div>
<div class="col-md-6 col-lg-6">
<table class="table">
	<tr>
		
		<td>
			<h3>
				<b style="color:#004598;">
					Prospectos juridicos antiguos en flujo (contacto nuevos) con flujo: <u><?php echo count($clientesLegalesViejosConFlujo) ?></u>
				</b>
			</h3>
		</td>
		
	</tr>
</table>

<?php foreach ($clientesLegalesViejosConFlujo as $key => $value): ?>
	<table class="table">
		<tr>
			
			<td colspan="5">
				<p> Cliente: <b><?php echo $value["ClientsLegal"]["name"] ?></b></p>
				<p> Contacto: <b><?php echo $value["ContacsUser"]["name"] ?></b></p>
				<p> Flujo: 
					<b>
						<a href="<?php echo Router::url("/",true)."prospectiveUsers/index?q=".$value["ProspectiveUser"]["id"] ?>" style="background-color: #004598; color:#fff; text-decoration: none; font-size: 11px; padding: 2px 5px; border-radius: 3px;" >
								<?php echo $value["ProspectiveUser"]["id"] ?>
						</a>
					</b>
				</p>
				<p> Medio de llegada: <b><?php echo $value["ProspectiveUser"]["origin"] ?></b></p>
				<p> Requerimiento: <b><?php echo $value["ProspectiveUser"]["description"] ?></b></p>
				<p> Estado actual del flujo: <b><?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?></b></p>
				<p> Usuario asignado: <b><?php echo $value["User"]["name"] ?></b></p>								
			</td>
			
		</tr>							
	</table>
	
					
<?php endforeach;  ?>
</div>