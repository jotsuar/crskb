<?php if (AuthComponent::user("id")): ?>
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
<?php endif ?>
<div class="container osspecialrsp" style="max-width: 1140px">

	<div class="col-md-12">

		<div class="technicalServices view ">
			<button id="imprimeData" class="btn btn-primary ">Imprimir <i class="fa fa-print vtc"></i></button>
			<div class="blockwhite " style="padding-left: 10%; padding-right: 10%;">
				<header>
					<div class="divContenedor2">
						<table cellpadding="0" cellspacing="0" class="tableproductsst w-100" >
								<tr>
									<td style="padding: 0px;">
										<div class="center">
											<?php echo $this->Html->image('/img/assets/logoproveedor.jpg',array('class' => 'imgFull', 'style' => 'width: 100% !important;' )) ?>
										</div>
										<p style="font-size: 10px !important; margin-top: -5px;" class="border ml-1 mr-1 text-center"><b>ACT. ECONÓMICA 4659. RESPONSABLE DE IVA</b> <br>NO AUTORRETENEDOR. NO GRAN CONTRIBUYENTE</p></p>
										<p>
									</td>	
									<td style="width: 10px" class="p-0">
										
									</td>	
									<td style="width: 600px;" class="p-0">
										<p style="display: block; text-align: center; font-size: 14px; color: #000;">
											<b><?php echo Configure::read("COMPANY.NAME") ?></b> <br>
											Nit: <?php echo Configure::read("COMPANY.NIT") ?>
											<br>Calle 10 No. 52A - 18, Bodega 104 ::: Teléfono: (4) 448 5566
										</p>
										<table cellpadding="0" cellspacing="0" class="tablageneral tablaTr w-100"  border="1">
											<tr>
												<th class="p-0" colspan="2" style="text-align: center; background-color: #ddd;" >
													COMPROBANTE DE ENTREGA DE PRODUCTOS KEB <?php echo $datosFlujo["ProspectiveUser"]["id"]; ?>
												</th>
											</tr>
										<!-- 	<tr>
												<td class="p-0" colspan="2" style="text-align: center;">
													<b>
														KEB <?php echo $datosFlujo["ProspectiveUser"]["id"]; ?>
													</b>
												</td>
											</tr> -->
											<tr>
												<td class="p-0" style="text-align: center;">
													<b>EMISIÓN</b>
												</td>
												<td class="p-0" style="text-align: center;">
													<b>VENCIMIENTO</b>
												</td>
											</tr>
											<tr>
												<td class="p-0" style="text-align: center;">
													<?php echo date("Y-m-d") ?>
												</td>
												<td class="p-0" style="text-align: center;">
													<?php echo date("Y-m-d") ?>
												</td>
											</tr>
											<tr>
												<td class="p-0" style="text-align: center;">
													<b>FORMA DE PAGO</b>
												</td>
												<th class="p-0" style="text-align: center;">
													CONTADO
												</th>
											</tr>
										</table>
						
									</td>
								</tr>
						</table>

						
					</div>
					
				</header>

				<section class="mt-3">
					<table cellpadding="0" cellspacing="0" class="tablageneral tablaTr w-100" style=""  border="1" >
							<tr>
								<th class="p-0 text-center" style="text-align: left; width: 180px">
									CLIENTE
								</th>
								<td class="p-0 pl-3	" colspan="4" style="color: #000;">
									<span><?php echo isset($cliente["legal"]) ? $cliente["legal"] ." - ".$cliente["name"] : $cliente["name"] ?></span>
								</td>
							</tr>
							<tr>
								<th class="p-0 text-center" style="text-align: left; width: 180px; color:#000;">
									# TOTAL DE PRODUCTOS
								</th>
								<td class="p-0 pl-3" width="200px">
									
								</td>
								<th class="p-0 text-center" style="text-align: left; color:#000;">
									<?php echo isset($cliente["legal"]) ? "NIT" : "IDENTIFICACIÓN" ?>
								</th>
								<td class="p-0 pl-3" style="color: #000">
									<span><?php echo $cliente["identification"] ?></span>
								</td>
								<th style="text-align: center;">
									VENDEDOR
								</th>
							</tr>
							<tr>
								<th class="p-0 text-center" style="text-align: left; width: 180px;color: #000">
									CIUDAD
								</th>
								<td class="p-0 pl-3" style="color: #000">
									<span><?php echo $cliente["city"] ?></span>
								</td>
								<th class="p-0 text-center" style="text-align: left;">
									TELÉFONO
								</th>
								<td class="p-0 text-center" style="color: #000">
									<span><?php echo $cliente["telephone"] ?></span>
								</td>
								<td class="p-0" style="text-align: center;">
									<?php echo $datosFlujo["User"]["name"] ?>
								</td>
							</tr>
					</table>
				</section>

				<div class="border border-dark mt-3 border-bottom-0" style="min-height: 400px; height: 400px;" class="w-100 bo">
					<table cellpadding="0" cellspacing="0"  class="tablageneral tablaTr w-100 "  >
						<tr style="border: 1px solid; border-left: 0px !important; border-right: 0px !important;">
							<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000; border-top: 0px !important;">
								Cant.
							</th>
							<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000; border-top: 0px !important;">
								Descripción.
							</th>
							<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000; border-top: 0px !important;">
								Referencia
							</th>
							<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000; border-top: 0px !important;">
								Unid.
							</th>
							<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000; border-top: 0px !important;">
								Valor unitario
							</th>
							<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000; border-top: 0px !important;">
								IVA
							</th>
							<th style="text-align: center; background-color: #ddd; border: 1px solid; color: #000; border-top: 0px !important;">
								Total
							</th>
						</tr>
						<?php $totalIVa 	= 0; ?>
						<?php foreach ($produtosCotizacion as $key => $value): ?>
							<tr style="border: none">
								<td style="border: none !important;text-align: center; color: #000"><?php echo $value["QuotationsProduct"]["quantity"] ?></td>
								<td style="border: none !important;text-align: center; color: #000"><?php echo strtoupper($value["Product"]["name"]) ?></td>
								<td style="border: none !important;text-align: center; color: #000"><?php echo strtoupper($value["Product"]["part_number"]) ?></td>
								<td style="border: none !important;text-align: center; color: #000">Und. </td>
								<td style="border: none !important;text-align: center; color: #000">$ <?php echo number_format($value["QuotationsProduct"]["price"],2,",",".") ?></td>
								<td style="border: none !important;text-align: center; color: #000"> <?php echo $value["QuotationsProduct"]["iva"] == 1 ? "19%" : "0%"  ?> </td>
								<td style="border: none !important;text-align: center; color: #000">
									$ <?php echo number_format($value["QuotationsProduct"]["price"]*$value["QuotationsProduct"]["quantity"],2,",",".") ?>
									<?php 
										
										if ($value["QuotationsProduct"]["iva"] == 1) {
											$totalIVa += ( $value['QuotationsProduct']['quantity'] * $value['QuotationsProduct']['price'] );
										}

									?>
								</td>
							</tr>
						<?php endforeach ?>
					</table>
				</div>						
				<table cellpadding="0" cellspacing="0" class="tablageneral tablaTr" style="border: 1px #000 solid;"  border="1" >
					<tr>
						<td class="py-0" rowspan="2" style="text-align: left; width: 380px">
							OBSERVACIONES: <?php echo empty($this->request->query["observaciones"]) ? "***" : $this->request->query["observaciones"] ?>
						</td>
						<th class="py-0">
							SUBTOTAL
						</th>
						<td class="py-0">
							$<?php echo number_format($total,2,",",".") ?>
						</td>
					</tr>
					<tr>
						<th class="py-0">
							DESCUENTO
						</th>
						<td class="py-0">
							$ <?php echo number_format($descuento,2,",",".") ?>
						</td>
					</tr>
					<tr>
						<td class="py-0" rowspan="2" style="text-align: left; width: 380px">
							SON: <?php echo strtoupper($letras) ?> <?php echo "PESOS" ?>
						</td>
						<th class="py-0">
							IVA
						</th>
						<td class="py-0">
							$ <?php echo number_format(($totalIVa-$descuento)*0.19,2,",",".") ?>
						</td>
					</tr>
					<tr>
						<th class="py-0">
							TOTAL DOCUMENTO
						</th>
						<td class="py-0">
							$ <?php echo number_format(($total-$descuento)+(($totalIVa-$descuento)*0.19),2,",",".") ?>
						</td>
					</tr>
					<tr>
						<td class="py-0" colspan="3" style="padding: 5px;">
							Titular de la Cuenta: KEBCO S.A.S. Cuenta Corriente BANCOLOMBIA 029-719631-94, Convenio 44103
							Enviar soporte de pago al correo contabilidad@almacendelpintor.com

						</td>
					</tr>
					<tr>
						<td colspan="3" class="p-0">
							<table cellpadding="0" cellspacing="0" class="tablageneral tablaTr w-100" style="border: 0px #000 solid;"  border="0">
								<tr>
									<th style="padding-bottom: 80px; border-right: 1px solid #000; width: 50%;" >
										VENDEDOR
									</th>
									<th style="padding-bottom: 80px;">
										RECIBE
									</th>
								</tr>
							</table>
						</td>
					</tr>	
				</table>

			</div>

			<div class="dataclientview" style="margin-top: -10px;">

			</div>

		
		</div>
	</div>
</div>

<div class="modals fade" id="modalFirma" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Firmar documento de entrega</h5>
      </div>
      <div class="modal-body" id="cuerpoFirma">

      	<div class="row">

      		<div class="col-md-12">
      			<div class="row">
      				<div class="col-md-12">
						 		<canvas id="draw-canvas" class="d-block mx-auto" width="1024" height="175">
						 			No tienes un buen navegador.
						 		</canvas>
						 	</div>
						 	<div class="col-md-12 text-center">
								<input type="button" class="btn btn-secondary" id="draw-submitBtn" value="Crear firma"></input>
								<input type="button" class="btn btn-secondary" id="draw-clearBtn" value="Borrar imagen"></input>
							</div>
      			</div>
      		</div>
      		<div class="col-md-9 d-block mx-auto">
      			<?php echo $this->Form->create('ProspectiveUser',array('data-parsley-validate'=>true,'id'=>'form_service_firma','enctype'=>'multipart/form-data')); ?>
      				<?php echo $this->Form->input('id',array('value' => $datosFlujo['ProspectiveUser']['id'])); ?>
	      			<div class="row">
	      				
	      				<div class="col-md-12 p-3">
									<img id="draw-image" class="border w-100 text-danger" src="" alt="La firma es requerida!"/>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<?php echo $this->Form->input('firma_img',array('label' => 'Celular','placeholder' => 'Nombre de quien entrega',"type"=>"hidden","id"=>"draw-dataUrl",'class' => 'form-control','required' => true)); ?>
										</div>
										<div class="col-md-6">
											<?php echo $this->Form->input('identification_entrega',array('label' => 'Cédula','placeholder' => 'Nombre de quien entrega','data-parsley-type'=>"number",'class' => 'form-control','required' => true)); ?>
										</div>
										<div class="col-md-6">
											<?php echo $this->Form->input('celular_entrega',array('label' => 'Celular','placeholder' => 'Nombre de quien entrega','data-parsley-type'=>"number",'class' => 'form-control','required' => true)); ?>
										</div>
										<div class="col-md-12">
											<input type="submit" value="Guardar firma" class="btn btn-success btn-block">
										</div>
										
									</div>
								</div>

	      			</div>
      			<?php echo $this->Form->end(); ?>
      		</div>

					
				 	
				</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/technicalServices/index.js?".rand(),		array('block' => 'AppScript'));
?>

<?php echo $this->Html->script("printArea.js?".rand(),           array('block' => 'jqueryApp')); ?>
<?php echo $this->Html->script("firma_orden.js?".rand(),           array('block' => 'AppScript')); ?>
<script>
    $("#imprimeData").click(function(event) {
        window.print();
    });
</script>

<style>
	#draw-canvas {
  border: 2px dotted #CCCCCC;
  border-radius: 5px;
  cursor: crosshair;
}

</style>

<style media="print">
	.condiciones > p {
		font-size: 16px !important;
	}
	.condiciones {
		font-size: 16px !important;
	}
     .mCustomScrollbar,.widget-panel,.nav_menu,#imprimeData {
        display:none;
     }          
     .right_col{
     	background: #fff !important;
     }
     .right_col,.content-all{
     	padding: 1px !important;
     	margin: 0 !important;
     }
     .col-md-12{
     	padding: 10px !important;
     }
     body{
	  float: none !important;
	  width: auto !important;
	  margin:  0 !important;
	  padding: 0 !important;
	  font-weight: bold !important;
	  background-color: transparent !important;
	  padding: 0px !important;
	  background: #fff !important;
	}
	.osspecialrsp{
		width: 100% !important;
	}
	.container{
		margin: unset !important;
	}
	.body{
		background: #fff !important;
	}

	.centerimg{
		width: 100px !important;
		display: inline-block;
	}
	.centerimg > img{
		width: 100px !important;
	}
	.col-md-3{
		width: 100%;
	}
	.p-0 {
    padding: 0!important;
}
	h2.titulost{
		font-size: 20px !important;
		padding: 0px !important; 
	}
	.dataFull,#firmarOrden{
		display: none !important;
	}
	.data-textData{
		display: block !important;
	}
	.dataclientview {
		margin-top: -20px;
	}
	/*body { color: #000 !important|; font: 100%/150% Georgia, "Times New Roman", Times, serif; }*/

</style>    