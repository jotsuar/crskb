<div class="col-md-12">

	<div class="blockwhite mb-3 p-2">
		<div class="card bg-gris border-0 p-3">
			<div class="row">
				<?php if (isset($id_busca) && !isset($empleadosDeuda[$id_busca])) {
					$empleadosDeuda[$id_busca] = 0;
				} ?>
				<div class="<?php echo !isset($filtroADM) && AuthComponent::user("role") == "Gerente General" ? 'col-md-6' : 'col-md-12' ?>">
					<a href="javascript:void(0)" class="btn btnEmp text-center d-block" data-name='0'><h2>Cartera vencida ($ <?php echo isset($id_busca) ?  number_format($empleadosDeuda[$id_busca],"0",".",".") : number_format($totalDeuda,"0",".",".") ?>)</h2></a>
					<?php if ((AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Contabilidad") && !isset($filtroADM) ): ?>
						<hr>
						<div class="table-responsive">

							<h3 class="text-center">
								Cartera vencida por empleado
							</h3>
							<table class="table-bordered table">
								<thead>
									<tr>
										<th class="p-0">
											Empleado
										</th class="p-0">
										<th>
											Cartera total
										</th>
									</tr>
									
								</thead>
								<tbody>
									<?php foreach ($empleadosDeuda as $key => $value): ?>
										<tr>
											<th class="p-0">
												<a href="" style="padding: 3px !important;" class="btn btnEmp" data-name="<?php echo $empleados[$key] ?>">
													<?php echo $empleados[$key] ?>
												</a>
											</th>
											<td class="p-0">
												$ <?php echo number_format($value,2,",",".") ?>
											</td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>							
						</div>
							<hr>
							<h3 class="text-center">
								<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Contabilidad" ): ?>
								<div class="row">
									<div class="col-md-4">
										<label for="empleado">Empleado</label>
										<div class="empleadoBtnDat"></div>
									</div>
									<div class="col-md-8">Cartera vencida general <span class="deudaGeneral"></span></div>
								</div>
								<?php else: ?>
									Cartera vencida general <span class="deudaGeneral"></span>
								<?php endif ?>
							</h3>
					<?php endif ?>
				</div>
				<div class="<?php echo !isset($filtroADM) && AuthComponent::user("role") == "Gerente General" ? 'col-md-6' : 'col-md-12' ?> p-2">
					<div class="table-responsive">

						<div style="max-height: 500px; overflow-y: auto;">
							<table class="table-bordered table" id="debtsData<?php echo AuthComponent::user("role") != "Gerente General" && $empleadosDeuda[$id_busca] == 0 ? "1" :"" ?>" >
								<thead>
									<tr>
										<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Contabilidad" ): ?>
											<th class="p-1 empleado">
												Empleado
											</th>
										<?php endif ?>
										<th class="p-0">
											ID / NIT
										</th>
										<th class="p-0">
											Factura
										</th>
										<th class="p-0" style="max-width: 150px !important">
											Cliente
										</th>
										<th class="p-0 esconder">
											Valor
										</th>
										<th class="p-0 esconder">
											Fecha Límite
										</th>
										<th class="esconder">
											Días vencidos
										</th>
									</tr>
								</thead>
								<?php foreach ($deudas as $key => $value): ?>
									<tr>
										<?php if (in_array(AuthComponent::user("role"), $roles) || isset($filtroADM) ): ?>
											<?php if ($value["Cc_Empleado"] != $id_busca): ?>
												<?php continue; ?>
											<?php endif ?>
										<?php endif ?>
										<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Contabilidad" ): ?>
											<td class="p-1 empleado <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>">
												<?php echo $value["Empleado"] ?>
											</td>
										<?php endif ?>
										<th class="p-0 <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>">
											<?php echo $value["Identificacion"] ?>
										</th>
										<td class="p-0 <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>">
											<?php echo $value["prefijo"]. " ".$value["DocumentoNúmero"] ?>
										</td>
										<td class="p-0 <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>" style="max-width: 150px !important">
											<?php echo $value["Nombres_terceros"] ?>
										</td>
										<td class="p-0 valorData <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>" data-value="<?php echo floatVal($value["Saldo"]) ?>">
											$ <?php echo number_format(intval($value["Saldo"]),"0",".",",") ?>
										</td>
										<td class="p-0 <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>">
											<?php echo str_replace("00:00:00.000", "", $value["Vencimiento"]) ?>
										</td>
										<td class="p-0 <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>">
											<b><?php echo $value["DIAS"] ?></b>
										</td>
									</tr>
								<?php endforeach ?>
							</table>
						</div>
					</div>
				</div>
			</div>
			
			
		</div>
	</div>			
</div>