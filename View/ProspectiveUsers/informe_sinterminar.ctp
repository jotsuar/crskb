<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h2 class="titleviewer">Buscador de facturas por flujo y fecha</h2>
			</div>
			<div class="col-md-6 blockwhite" style="box-shadow: 0 0.5rem 0.88rem rgba(0, 0, 0, .09);">
				<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100',"type" => "get")); ?>
					<h1 class="nameview spacebtnm text-center">BUSCADOR POR FLUJO</h1>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="flujoSearch">Flujo</label>
								<input type="text" id="txt_buscador" name="q" value="<?php echo isset($this->request->query['q']) ? $this->request->query['q'] : ""; ?>" class="form-control" placeholder="Buscador por flujo">
								<!-- <button  type="button" id="reset" onclick="clearFields();" class="mt-4 btn btn-danger">Limpiar campos</button> -->
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">							
								<input type="submit" value="Buscar" class="btn btn-success pull-right mt-4">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="blockwhite">
		<div class="col-md-12">
			<h2 class="titleviewer">Facturas asociadas a Medellín</h2>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered <?php echo empty($datos["medellin"]) ? "" : "datosPendientesDespacho" ?>  table-hovered">
				<thead>
					<tr>						
						<th>Flujo</th>
						<th>Estado del flujo</th>
						<th>Cliente</th>
						<th>Vendedor</th>
						<th>Código Cotización</th>
						<th>Total productos sin facturar</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($datos["medellin"])): ?>
						<tr>
							<td colspan="8" class="text-center">
								<p class="text-danger mb-0">No existen registros sin facturar</p>
							</td>
						</tr>
					<?php else: ?>
						<?php foreach ($datos["medellin"] as $key => $value): ?>
							<tr>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable m-1 flujoModal" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $value["ProspectiveUser"]["id"] ?>
									</a>
								</td>
								<td>
									<?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?>
								</td>
								<td class="text-uppercase"><?php echo $this->Utilities->name_prospective_contact($value['ProspectiveUser']["id"]) ?></td>
								<td>
									<?php echo $this->Utilities->find_name_adviser($value["ProspectiveUser"]["user_id"]); ?>
								</td>
								<td><?php echo $value["Quotation"]["codigo"] ?></td>
								<td class="text-uppercase"><?php echo $value["0"]["total"] ?></td>
								<td> 
									<div class="dropdown d-inline styledrop ">
										<a class="btn btn-success dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($value["Quotation"]["id"]) ?>_<?php echo md5($value["ProspectiveUser"]["id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										   Acciones
										</a>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($value["Quotation"]["id"]) ?>_<?php echo md5($value["ProspectiveUser"]["id"]) ?>">
										    <a class="dropdown-item idflujotable flujoModal modalFlujoClick" href="#" data-uid="<?php echo $value["ProspectiveUser"]["id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value["ProspectiveUser"]["id"]); ?>">Ver flujo</a>
										    <a class="dropdown-item getQuotationId modalFlujoClick" data-quotation="<?php echo $this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]) ?>" href="#">Ver cotización</a>
										    <a class="dropdown-item getOrderCompra modalFlujoClick" href="#" data-flujo="<?php echo $value["ProspectiveUser"]["id"] ?>">Ver órden de compra</a>
										</div>
									</div>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="blockwhite">
		<div class="col-md-12">
			<h2 class="titleviewer">Facturas asociadas a Bogotá</h2>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered <?php echo empty($datos["bogota"]) ? "" : "datosPendientesDespacho" ?>  table-hovered">
				<thead>
					<tr>						
						<th>Flujo</th>
						<th>Estado del flujo</th>
						<th>Cliente</th>
						<th>Vendedor</th>
						<th>Código Cotización</th>
						<th>Total productos sin facturar</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($datos["bogota"])): ?>
						<tr>
							<td colspan="8" class="text-center">
								<p class="text-danger mb-0">No existen registros sin facturar</p>
							</td>
						</tr>
					<?php else: ?>
						<?php foreach ($datos["bogota"] as $key => $value): ?>
							<tr>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable m-1 flujoModal" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $value["ProspectiveUser"]["id"] ?>
									</a>
								</td>
								<td>
									<?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?>
								</td>
								<td class="text-uppercase"><?php echo $this->Utilities->name_prospective_contact($value['ProspectiveUser']["id"]) ?></td>
								<td>
									<?php echo $this->Utilities->find_name_adviser($value["ProspectiveUser"]["user_id"]); ?>
								</td>
								<td><?php echo $value["Quotation"]["codigo"] ?></td>
								<td class="text-uppercase"><?php echo $value["0"]["total"] ?></td>
								<td> 
									<div class="dropdown d-inline styledrop ">
										<a class="btn btn-success dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($value["Quotation"]["id"]) ?>_<?php echo md5($value["ProspectiveUser"]["id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										   Acciones
										</a>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($value["Quotation"]["id"]) ?>_<?php echo md5($value["ProspectiveUser"]["id"]) ?>">
										    <a class="dropdown-item idflujotable flujoModal modalFlujoClick" href="#" data-uid="<?php echo $value["ProspectiveUser"]["id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value["ProspectiveUser"]["id"]); ?>">Ver flujo</a>
										    <a class="dropdown-item getQuotationId modalFlujoClick" data-quotation="<?php echo $this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]) ?>" href="#">Ver cotización</a>
										    <a class="dropdown-item getOrderCompra modalFlujoClick" href="#" data-flujo="<?php echo $value["ProspectiveUser"]["id"] ?>">Ver órden de compra</a>
										</div>
									</div>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	function clearFields(){
		document.getElementById('dateBill').value= '';
		document.getElementById('txt_buscador').value= '';
	}
</script>

<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp'));

	echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));

	echo $this->Html->script("controller/quotations/view.js?".rand(),			array('block' => 'AppScript')); 
 ?>

 <?php echo $this->element("flujoModal"); ?>