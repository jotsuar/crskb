<?php 
	$importacion = false;
	$imports = array();

   	if(!empty($datosFlujo["Import"]["id"]) && empty($datosFlujo["Imports"]) ){
   		$imports[] 		= $datosFlujo["Import"];
   		$importacion 	= true;
   	}else if(!empty($datosFlujo["Imports"])){
   		$importacion 	= true;
   		$imports 		= $datosFlujo["Imports"];
   	}
 ?>
<div class="col-md-12 col-sm-12">
	<div class="card">
	  <div class="card-header">
	    Productos a facturar para el flujo: <?php echo $datosFlujo["ProspectiveUser"]["id"] ?>
	  </div>
	  <div class="card-body">
	    <div class="card-text">
	    	<?php if (!empty($produtosCotizacion)): ?>
	    		
		    	<?php $num = 0; $i = 1; foreach ($produtosCotizacion as $value): ?>
					<div class="row">
						<div class="col-md-2">
							<?php $ruta = $this->Utilities->validate_image_products($value['Product']['img']); ?>
							<img class="img-fluid" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>">
						</div>
						<div class="col-md-10 modaldatadespacho">
							<div class="dataproductview2">
								<strong class="text-success">Referencia: <?php echo $value['Product']['part_number'] ?> / Marca: <?php echo $value['Product']['brand'] ?></strong>
								<h3 class=""><?php echo $this->Text->truncate(strip_tags($value['Product']['name']), 70,array('ellipsis' => '...','exact' => false)); ?></h3>
								<p class="descriptionview nc dnone">
									<?php echo $this->Text->truncate(strip_tags($value['Product']['description']), 400,array('ellipsis' => '...','exact' => false)); ?>
								</p>

								<div class="row nomr">
									<div class="col-md-3 pdnone">
										<p class="cantidadquote2">Cantidad: <b><?php echo $value['QuotationsProduct']['quantity'] ?></b></p>
									</div>	
									<div class="col-md-9 pdnone">
										<p class="priceview2">Entrega: <b><?php echo $value['QuotationsProduct']['delivery'] ?></b> -
										<span class="statemodald">
											<?php if (!is_null($value["QuotationsProduct"]["id_llc"])): ?>
												<?php switch ($value["QuotationsProduct"]["state_llc"]) {
													case '0':
														echo 'Factura creada LLC';
														break;
													
													case '1':
														echo 'Factura gestionada sin terminar LLC';
														break;
													case '2':
														echo 'Factura gestionada terminada LLC';
														break;
													default:
														echo "";
												} ?>
											<?php else: ?>
												<?php echo $this->Utilities->find_state_dispatches($value['QuotationsProduct']['state']); ?>
											<?php endif ?>
											
										</span></p>
									</div>												
								</div>
							</div>

						<div class="contentsend">
							<div class="contentchecksend text-center">

								<?php $llc = false; ?>
								<?php if (!is_null($value["QuotationsProduct"]["id_llc"]) && $value["QuotationsProduct"]["state_llc"] == 2): ?>
									<?php $llc = true; ?>
								<?php endif ?>

								<?php $estado 				= array('1','3','4','5','6'); ?>
								<?php $estadosImportaciones = array(1,2,3,4,5,6,7); ?>

								<?php 

									$aprobado = true;
									$estadoImportacion = 0;

									if($importacion){
										$estadoImportacion = $this->Utilities->validateImportToSend($imports, $value["Product"]["id"], $datosFlujo["ProspectiveUser"]["id"], $value['QuotationsProduct']['quantity']);
									}else{
										if($value['QuotationsProduct']['state'] == 2){
											$estadoImportacion = 5;
										}
									}
								?>
								
								<?php if (  ( in_array($value['QuotationsProduct']['biiled'], [0,1,2,3])  && (in_array($value['QuotationsProduct']['state'],$estado) || in_array($estadoImportacion, $estadosImportaciones) || $llc)) || ( 
									in_array($value['QuotationsProduct']['biiled'], [0,1,2,3]) && $aprobado && in_array($value['QuotationsProduct']['state'],['0'])
								)  ) { ?>
										<?php echo $this->Form->input('importacion_'.$i,array('type' => 'checkbox','label' => 'Facturar','value' => $value['QuotationsProduct']['id'], "required" ,"class" => "productsEnvioCheck"));?>
										<?php $num++; ?>
								<?php } else {
									$i = $i - 1;
								} ?>
							</div>
							
							<?php if (!is_null($value["QuotationsProduct"]["id_llc"])): ?>
								
							<?php else: ?>
								<span class="statemodald text-body mt-2"><?php echo $this->Utilities->getStateImport($estadoImportacion); ?></span>
							<?php endif ?>
						</div>


						</div>	

					</div>
					<hr>
				<?php $i++; endforeach; ?>
	    	<?php endif ?>
			<div class="form-group">
				<?php 
					echo $this->Form->hidden('prospective_user_id',array('value' => $datosFlujo['ProspectiveUser']['id'], "id" => "cargaFacturaIDFlujo" ));
				?>
			</div>	

	    </div>
	    <?php if ($num > 0 ): ?>		    	
	    	<button type="button" class="btn btn-success pull-right mt-3" id="terminarSeleccion"> Terminar Selección </button>
	    <?php endif ?>
	  </div>
	</div>
</div>