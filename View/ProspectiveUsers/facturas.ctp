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
					<h1 class="nameview spacebtnm text-center">BUSCADOR POR FLUJOS Y FECHA</h1>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="flujoSearch">Flujo</label>
								<input type="text" id="txt_buscador" name="q" value="<?php echo isset($this->request->query['q']) ? $this->request->query['q'] : ""; ?>" class="form-control" placeholder="Buscador por flujo">
								<button  type="button" id="reset" onclick="clearFields();" class="mt-4 btn btn-danger">Limpiar campos</button>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="dateBill">Fecha factura</label>
								<input type="date" value="<?php echo isset($this->request->query['dateBill']) ? $this->request->query['dateBill'] : "" ?>" id="dateBill" name="dateBill" class="form-control">								
								<input type="submit" value="Buscar" class="btn btn-success pull-right mt-4">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="blockwhite">
		<div class="table-responsive">
			<table class="table table-bordered <?php echo empty($sales) ? "" : "datosPendientesDespacho" ?>  table-hovered">
				<thead>
					<tr>						
						<th>Flujo</th>
						<th>Cliente</th>
						<th>Vendedor</th>
						<th>NIT / CC</th>
						<th>Factura</th>
						<th>Valor</th>
						<th>Fecha Factura</th>
						<th>Archivo</th>
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($prospectiveUsers)): ?>
						<tr>
							<td colspan="8" class="text-center">
								<p class="text-danger mb-0">No existen registros de facturación</p>
							</td>
						</tr>
					<?php else: ?>
						<?php foreach ($prospectiveUsers as $key => $value): ?>
							<tr>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable m-1 flujoModal" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
																		<?php echo $value["ProspectiveUser"]["id"] ?>
																	</a>
								</td>
								<td class="text-uppercase"><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?></td>
								<td>
									<?php echo $this->Utilities->find_name_adviser($value["ProspectiveUser"]["bill_user"]); ?>
								</td>
								<td><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["identification"] : $value["ContacsUser"]["ClientsLegal"]["nit"] ?></td>
								<td class="text-uppercase"><?php echo $value["ProspectiveUser"]["bill_code"] ?></td>
								<td> $ <?php echo number_format($value["ProspectiveUser"]["bill_value"],0,".",",") ?></td>
								<td><?php echo $this->Utilities->date_castellano($value["ProspectiveUser"]["bill_date"]) ?></td>
								<td>
									<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$value["ProspectiveUser"]["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary">
										Ver factura <i class="fa fa-file"></i>
									</a>
								</td>
							</tr>
							<?php if (!empty($value["facturas"])): ?>

								<?php foreach ($value["facturas"] as $keyFactura => $valueFactura): ?>
									<tr>
										<td>
											<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable m-1 flujoModal" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
												<?php echo $value["ProspectiveUser"]["id"] ?>
											</a>
										</td>
										<td class="text-uppercase"><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?></td>
										<td>
											<?php echo $this->Utilities->find_name_adviser($value["ProspectiveUser"]["bill_user"]); ?>
										</td>
										<td><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["identification"] : $value["ContacsUser"]["ClientsLegal"]["nit"] ?></td>
										<td class="text-uppercase"><?php echo $valueFactura["Salesinvoice"]["bill_code"] ?></td>
										<td> $ <?php echo number_format($valueFactura["Salesinvoice"]["bill_value"],0,".",",") ?></td>
										<td><?php echo $this->Utilities->date_castellano($valueFactura["Salesinvoice"]["bill_date"]) ?></td>
										<td>
											<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$valueFactura["Salesinvoice"]["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary">
												Ver factura <i class="fa fa-file vtc"></i>
											</a>
										</td>
									</tr>
								<?php endforeach ?>
								
							<?php endif ?>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
		</div>
		<div class="row numberpages">
			<?php
				echo $this->Paginator->first('<< ', array('class' => 'prev'), null);
				echo $this->Paginator->prev('< ', array(), null, array('class' => 'prev disabled'));
				echo $this->Paginator->counter(array('format' => '{:page} de {:pages}'));
				echo $this->Paginator->next(' >', array(), null, array('class' => 'next disabled'));
				echo $this->Paginator->last(' >>', array('class' => 'next'), null);
			?>
			<b> <?php echo $this->Paginator->counter(array('format' => '{:count} en total')); ?></b>
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
 ?>