<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-verde big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Tesorería</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview">LIQUIDACIÓN DE COMISIONES</h1>
			</div>
		</div>
	</div>
</div>

<div class="col-md-12">
	<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100')); ?>
	<?php if (!in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])) {
		$usuarios = [AuthComponent::user("id") => AuthComponent::user("name")];
	} ?>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h1 class="nameview2">LIQUIDACIÓN DE COMISIONES realizada para el usuario: <?php echo $liquidation["User"]["name"] ?></h1>
				<span class="subname">Fechas: <?php echo $liquidation["Liquidation"]["date_ini"] ?> - <?php echo $liquidation["Liquidation"]["date_end"] ?></span>
			</div>
			<div class="col-md-8">
				<div class="row">
			
					
				</div>
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
						<th>Factura</th>
						<th>Fecha factura</th>
						<th>Recibo</th>
						<th>Fecha recibo</th>
						<th>Valor venta</th>
						<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
						<th>Utilidad</th>
							<?php endif ?>
						<th>Valor recaudo <br> con IVA</th>
						<th>Base</th>
						<th>Días pago</th>
						<th>% Com. <br> por recaudo </th>
						<th>Comisión <br> por recaudo</th>
						<th>% Com. <br> por utilidad </th>
						<th>Comisión <br> por utilidad</th>
						<th>Notas</th>
						<th>Factura</th>
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($sales)): ?>
						<tr>
							<td colspan="18" class="text-center">
								<?php if ($filter): ?>
									<p class="text-danger mb-0">No existen registros de facturación</p>
								<?php else: ?>
									<p class="text-danger mb-0">! Para ver datos por favor realiza una búsqueda ¡</p>									
								<?php endif ?>
							</td>
						</tr>
					<?php else: ?>
						<?php  foreach ($sales as $key => $value): ?>
							<tr>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable flujoModal m-1" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
																		<?php echo $value["ProspectiveUser"]["id"] ?>
																	</a>
								</td>

								<td class="text-uppercase">
									<?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?>
								</td>
								<td class="text-uppercase"><?php echo $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_code") ?></td>
								<td style="width: 75px;"><?php echo ( $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date")); ?></td>
								<td class="text-uppercase"><?php echo $value["Receipt"]["code"] ?></td>
								<td style="width: 75px;"><?php echo ($value["Receipt"]["date_receipt"]) ?></td>
								<td> $ <?php echo number_format($this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_value"),0,".",",") ?></td>
								<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
									<td>
										
										 <?php echo number_format($value["Valores"]["utilidad_factura"],2,",","."); ?>$ 

									</td>
								<?php endif ?>
								<td> $ <?php echo number_format($value["Receipt"]["total"],0,".",",") ?></td>
								<td> $ <?php echo number_format($value["Receipt"]["total_iva"],0,".",",") ?></td>
								<td><?php $dias = $value["Finals"]["dias"]; echo $dias; ?></td>
								<td>
									<?php 
										$percentaje =  $value["Finals"]["percentaje"];
									?>

									<b class="font16"><?php echo is_null($value['Receipt']['percent_value']) ? $percentaje : $value['Receipt']['percent_value'] ?></b>
								</td>
								<td>$ <?php

										$pagar 		  =  $value["Finals"]["pagar"];

										echo number_format($pagar);
										?>
								</td>
								<td >
									<?php echo $value["Finals"]["percentUtility"]  ?>
								</td>
								<td>
									<?php echo number_format($value["Finals"]["pagarByPercent"]) ?> 
								</td>
								<td>
									<?php echo $value["Finals"]["nota"]  ?>
								</td>
								<td style="    width: 100px;">
									<?php $dataBill = $this->Utilities->getDataBill($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"]); ?>

									<?php if (empty($dataBill)): ?>
										<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$dataBill["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary">
											Ver factura <i class="fa fa-file vtc"></i>
										</a>
									<?php else: ?>
										<a href="#" target="blank" class="btn btn-info btn-secondary mostradDatosFact btn-sm" data-id="KEB_<?php echo md5($dataBill["bill_code"]) ?>">
											Ver factura <i class="fa fa-file vtc"></i>
										</a>

										<div id="KEB_<?php echo md5($dataBill["bill_code"]) ?>" class="table-responsive" style="display: none;">
											<?php $factValue = (array) json_decode($dataBill["bill_text"]); ?>
											<?php echo $this->element("vistaFacturaWo", ["factValue" => $factValue]); ?>
										</div>

									<?php endif ?>

									
								</td>
							</tr>
						<?php endforeach ?>
						<tr class="font22 text-center">
							<td colspan="18">
								<div class="row">
									<div class="col-md-5">

										<div class="card">
										  <div class="bg-blue card-header font-weight-bold">
										    Metas y valores
										  </div>
										  <ul class="list-group list-group-flush">
										    
										    <li class="list-group-item font20 border-top mb-5 mt-5">
										    	<strong>
										    		Meta de ventas:
										    	</strong>
										    	$<?php echo number_format($metaVentas) ?> / <strong>
										    		Total ventas:
										    	</strong>
										    	$<?php echo number_format($totalVentas) ?>
										    </li>
										    <li class="list-group-item font20 mb-5">
										    	<strong>
										    		Meta de recaudo:
										    	</strong>
										    	$<?php echo number_format($meta) ?> / <strong>
										    		Total recaudado:
										    	</strong>
										    	$<?php echo number_format($saldos) ?>
										    </li>
										    <li class="list-group-item font20 mb-5 border-bottom">
										    	<strong>
										    		Efectividad periodo:
										    	</strong>
										    	<?php echo isset($dataEfectivity["efectividad"]) ? $dataEfectivity["efectividad"] : 0 ?>% / <strong>
										    		% a pagar según efectividad:
										    	</strong>
										    	<?php echo $percentajeDb["Effectivity"]["value"] ?> %	
										    </li>
										  </ul>
										</div>										
									</div>
									<div class="col-md-7">
										<div class="card">
										  <div class="bg-blue card-header font-weight-bold">
										    Valores a pagar
										  </div>
										  <ul class="list-group list-group-flush">
										  	<li class="list-group-item">
										  		<div class="row">
										  			
										  			
										  			
										  			
										  			
										  			
										  			<div class="col-md-12">
										  				<!--  -->

										  					<div class="border-left border-success bg-success card h-10000 shadow mt-2">
			                            <div class="card-body" style="    padding: 5px !important;">
			                                <div class="row no-gutters align-items-center">
			                                    <div class="col mr-2">
			                                    		<div class="row">
			                                    			<div class="col">
			                                    				<div class="text-xs font-weight-bold text-white text-uppercase alignforlabel">
			                                    					<span><?php echo $liquidation["User"]["name"] ?></span> <br>
					                                        	<span class="pt-2">
					                                        		Total a pagar final: $<?php echo number_format($liquidation["Liquidation"]["valor_a_pagar"] ,0,".",",") ?>
					                                        	</span>
					                                        	<h4 class="font-italic font-weight-light text-white">Fechas: <?php echo $liquidation["Liquidation"]["date_ini"] ?> - <?php echo $liquidation["Liquidation"]["date_end"] ?></h4>
					                                        </div>
			                                    			</div>
			                                    			<div class="col">
			                                    				<div class="h5 mb-0 font-weight-bold text-gray-800 font14 d-inline ">
			                                        	<table class="table table-hovered alignforlabel" style="color: #ffffffc4">
			                                        		<tr>
			                                        			<th class="p-0" style="border:none !important;">
			                                        				Total comisiones: 
			                                        			</th>
			                                        			<td class="p-0" style="border:none !important;">
			                                        				+ $<?php echo number_format($liquidation["Liquidation"]["valor_efectividad"]) ?>
			                                        			</td>
			                                        		</tr>
			                                        		<tr>
			                                        			<th class="p-0" style="border:none !important;">
			                                        				Total por cumplir meta: 
			                                        			</th>
			                                        			<td class="p-0" style="border:none !important;">
			                                        				+ $<?php echo $totalVentas >= $metaVentas ? number_format($liquidation["Liquidation"]["valor_bono"],0,".",",") : '0' ?>
			                                        			</td>
			                                        		</tr>
			                                        		<tr>
			                                        			<th class="p-0" style="border:none !important;">
			                                        				Total bono gerencia: 
			                                        			</th>
			                                        			<td class="p-0" style="border:none !important;">
			                                        				+ $<?php echo number_format($liquidation["Liquidation"]["valor_bono_gerencia"],0,".",",") ?>
			                                        			</td>
			                                        		</tr>
			                                        		<?php  if ($liquidation["Liquidation"]["valor_servicios"] > 0): ?>
			                                        		<tr>
			                                        			<th class="p-0" style="border:none !important;">
			                                        				Total servicios técnicos:
			                                        			</th>
			                                        			<td class="p-0" style="border:none !important;">
			                                        				+ $<?php echo number_format($liquidation["Liquidation"]["valor_servicios"]) ?>
			                                        			</td>
			                                        		</tr>
			                                        		<?php endif ?>
			                                        		<tr>
			                                        			<th class="p-0" style="border:none !important;">
			                                        				Descuento por no cobro de comisión bancaria:
			                                        			</th>
			                                        			<td class="p-0" style="border:none !important;">
			                                        				- $<?php echo number_format($totalAdescontar) ?>
			                                        			</td>
			                                        		</tr>
			                                        		<tr>
			                                        			<td class="p-0" style="border:none !important;"></td>
			                                        			<td class="p-0" style="border:none !important;     border-top: 1px solid black !important;">
			                                        				$<?php echo number_format($liquidation["Liquidation"]["valor_a_pagar"],0,".",",") ?>
			                                        			</td>
			                                        		</tr>
			                                        	</table>
			                                        </div>
			                                    			</div>
			                                    		</div>
			                                        
			                                        
			                                    </div>
			                                    <div class="col-auto">
			                                        <i class="fa fa-dollar fa-2x font28 text-azul"></i>
			                                    </div>
			                                </div>
			                            </div>
				                        </div>

										  				<!--  -->
										  			</div>
										  		</div>
										  	</li>
										  </ul>									
										</div>	
									</div>
								</div>
								
							</td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>

	</form>
	
</div>



<div class="modal fade" id="modalFacturaDetalle" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle factura </h2>
      </div>
      <div class="modal-body" id="bodyDetalleFactura">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>



<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/index.js?".rand(),			array('block' => 'AppScript')); 
	echo $this->Html->script(array('lib/jquery.jeditable.min.js?'.rand()),						array('block' => 'AppScript'));
	echo $this->Html->script("controller/prospectiveUsers/comisiones_panel.js?".time(),			array('block' => 'AppScript')); 
?>


<?php echo $this->element("picker"); ?>
<?php echo $this->element("flujoModal"); ?>

<style>
	#formularioQueCambia{
		z-index: 1000000;
	}
</style>