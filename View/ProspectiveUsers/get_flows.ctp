<div class="table-responsive">
	<div style="max-height: 400px; overflow-y: auto;">
		<table class="table-bordered table" id="flujosData" style="max-width: 100% !important;">
			<thead>
				<tr>
					<th class="noMostrar p-1" style="width: 80px !important;">
						FLUJO
					</th>
					<th class="noMostrar p-1">
						Cliente
					</th>
					<th class="noMostrar p-1">Origen</th>
					<th class="noMostrar p-1">
						Requerimiento
					</th>										
					<th class="p-1">
						Estado actual
					</th>
					<th class="noMostrar p-1">
						Asesor
					</th>
					<th class="noMostrar p-1">
						Valor
					</th>
				</tr>
			</thead>
			<?php foreach ($datos as $key => $value): ?>
				<tr>
					<td class="p-1" style="width: 80px !important;">

						<div class="dropdown d-inline styledrop">
							<a class="btn btn-success dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($key) ?>_<?php echo md5($value['ProspectiveUser']['id']) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?php echo $value['ProspectiveUser']['id'] ?>
							</a>

							<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($key) ?>_<?php echo md5($value['ProspectiveUser']['id']) ?>">
								<a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value['ProspectiveUser']['id']); ?>">Ver flujo</a>
								<?php if (in_array($value['ProspectiveUser']['state_flow'], [3,4,5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>														
									<a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($value['ProspectiveUser']['id']) ?>" href="#">Ver cotización</a>
								<?php endif ?>
								<?php if (in_array($value['ProspectiveUser']['state_flow'], [4,5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>
									<a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $value['ProspectiveUser']['id'] ?>">Ver órden de compra</a>
								<?php endif ?>
								<?php if (in_array($value['ProspectiveUser']['state_flow'], [5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>
									<a class="dropdown-item getPagos" href="#" data-flujo="<?php echo $value['ProspectiveUser']['id'] ?>">Ver comprobante(s) de pago</a>
								<?php endif ?>
							</div>
						</div>
					</td>
					<th class="p-0">
						 <?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']), 40,array('ellipsis' => '...','exact' => false)); ?>
					</th>
					<td class="p-0"><?php echo $value['ProspectiveUser']['origin'] ?></td>
					<td class="p-0"><?php echo $this->Text->truncate($this->Utilities->find_reason_prospective($value['ProspectiveUser']['id']), 50,array('ellipsis' => '...','exact' => false)); ?></td>
					<td class="p-0"><?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?></td>
					<td class="p-0"><?php echo $this->Text->truncate($this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']), 50,array('ellipsis' => '...','exact' => false)); ?></td>
					<td class="p-0">$<?php echo number_format($value['ProspectiveUser']['total'],2,",",".") ?></td>
				</tr>
			<?php endforeach ?>
		</table>
	</div>
</div>