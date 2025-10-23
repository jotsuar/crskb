<!-- content -->
<section class="py-5">
  <div class="container">
    <div class="row gx-5">
      <aside class="col-lg-6">
        
        <?php 
			$imgsUrls = [];
										

			if(!is_null($dataProduct['Product']['img'])){
				$ruta = $this->Utilities->validate_image_products($dataProduct['Product']['img']);
				$imgsUrls[] = $this->Html->url('/img/products/'.$ruta);
			}
			if(!is_null($dataProduct['Product']['img2'])){
				$ruta = $this->Utilities->validate_image_products($dataProduct['Product']['img2']);
				$imgsUrls[] = $this->Html->url('/img/products/'.$ruta);
			}
			if(!is_null($dataProduct['Product']['img3'])){
				$ruta = $this->Utilities->validate_image_products($dataProduct['Product']['img3']);
				$imgsUrls[] = $this->Html->url('/img/products/'.$ruta);
			}
			if(!is_null($dataProduct['Product']['img4'])){
				$ruta = $this->Utilities->validate_image_products($dataProduct['Product']['img4']);
				$imgsUrls[] = $this->Html->url('/img/products/'.$ruta);
			}
			if(!is_null($dataProduct['Product']['img5'])){
				$ruta = $this->Utilities->validate_image_products($dataProduct['Product']['img5']);
				$imgsUrls[] = $this->Html->url('/img/products/'.$ruta);
			}

			$ruta = $this->Utilities->validate_image_products($dataProduct['Product']['img']);

		 ?>
		 <div class="border rounded-4 mb-3 d-flex justify-content-center">
          <a data-fslightbox="mygalley" class="rounded-4" target2="_blank" data-type="image" data-href="<?php echo $this->Html->url('/img/products/'.$ruta)?>">
            <img id="ppalImg" style="max-width: 100%; max-height: 100vh; margin: auto;" class="rounded-4 fit" src="<?php echo $this->Html->url('/img/products/'.$ruta)?>" />
          </a>
        </div>
        <div class="d-flex justify-content-center mb-3">
        	<?php foreach ($imgsUrls as $key => $value): ?>
        		<a data-fslightbox="mygalley" class="border mx-1 rounded-2 imglink" target2="_blank" data-type="image" href="<?php echo $value ?>" class="item-thumb">
		            <img width="60" height="60" class="rounded-2" src="<?php echo $value ?>" />
		          </a>
        	<?php endforeach ?>
        </div>
        <!-- thumbs-wrap.// -->
        <!-- gallery-wrap .end// -->
      </aside>
      <main class="col-lg-6">
        <div class="ps-lg-3">
          <h4 class="title text-dark">
            <?php echo $dataProduct["Product"]["name"] ?>
          </h4>
          <div class="d-flex flex-row my-3">
            
            <span class="text-success ms-2"><?php echo $dataProduct["Product"]["part_number"] ?></span>
          </div>

          <div class="mb-3">
            <span class="h5">
            	<?php if ($datosQuation["Quotation"]["show"] == 1): ?>
																
					<?php $precioCotizacionSubtotal = explode(",", $this->Utilities->total_item_products_quotations2($dataProductQt['QuotationsProduct']['quantity'],$dataProductQt['QuotationsProduct']['price'])) ?>
					 <b> 
					$<?php echo $precioCotizacionSubtotal[0] ?><span class="decimales simpledecimal">,<?php echo $precioCotizacionSubtotal[1] ?></span>
					</b> 
					<?php echo $dataProductQt['QuotationsProduct']['currency'] == "usd" ? " USD" : "" ?>
					<?php echo $datosQuation["ProspectiveUser"]["country"] == "Colombia" && $dataProductQt['QuotationsProduct']['iva'] == 1? "+IVA" : "" ?>
				<?php else: ?>
					&nbsp;&nbsp;&nbsp;				
				<?php endif ?>
            </span>
            <?php if ($datosQuation["Quotation"]["show_iva"] == 1 && $datosQuation["Quotation"]["show"] == 1 && $dataProductQt['QuotationsProduct']['iva'] == 1): ?>
            	<span class="text-muted">$<?php echo number_format($dataProductQt["QuotationsProduct"]["price"]*1.19,2,",",".") ?></span>
            <?php endif ?>
          </div>

          <p>
            <?php echo $dataProduct["Product"]["description"] ?>
          </p>

          <div class="row">
            <dt class="col-3">Marca:</dt>
            <dd class="col-9"><?php echo $dataProduct["Product"]["type"] == 0 ? "Kebco" : $dataProduct['Product']['brand'] ?></dd>

            <dt class="col-3">Referencia:</dt>
            <dd class="col-9"><?php echo $dataProduct["Product"]["part_number"] ?></dd>

            <dt class="col-3">Link:</dt>
            <dd class="col-9"><?php echo $dataProduct["Product"]["link"] ?></dd>
          </div>

          <hr />

          <div class="row mb-4">
          	<div class="col-md-12">
          		<a style="display:nones" target="_blank" href="<?php echo $this->html->url(["controller"=>"quotations","action"=>"action_payment_qtprod",$this->Utilities->encryptString($dataProductQt["QuotationsProduct"]["id"]),$this->Utilities->encryptString($datosQuation["Quotation"]["id"])]) ?>" class="btn btn-outline-primary">	
          			Pagar Producto 
					<img src="data:image/png;base64,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" alt="">
				</a>
          	</div>
        </div>
      </main>
    </div>
  </div>
</section>
<!-- content -->

<section class="bg-light border-top py-4">
  <div class="container">
    <div class="row gx-4">
      <div class="col-lg-8 mb-4">
        <div class="border rounded-2 px-3 py-2 bg-white">

        	<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
			  <li class="nav-item" role="presentation">
			    <button class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Caracteristicas</button>
			  </li>
			  <li class="nav-item" role="presentation">
			    <button class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Ficha Técnica</button>
			  </li>
			  <li class="nav-item" role="presentation">
			    <button class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Archivos o manuales</button>
			  </li>
			</ul>
			<div class="tab-content" id="pills-tabContent">
			  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
			  	<?php echo $dataProduct["Product"]["long_description"] ?>
			  </div>
			  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
			  	<table class="table">
			  		<?php foreach ($dataProduct["FeaturesValue"] as $key => $value): ?>
			  			<tr>
			  				<th><?php echo $value["feature_name"] ?></th>
			  				<td><?php echo $value["name"] ?></td>
			  			</tr>
			  		<?php endforeach ?>
			  	</table>
			  </div>
			  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
			  	<?php if ($dataProduct['Product']['manual_1'] != "" ) { ?> 
					<p>
						<i class="bg-red fa fa-file-pdf-o fa-2x vtc"></i> Manual 1:
						<a target="_blank" href="<?php echo $this->Html->url("/files/products/".$dataProduct["Product"]["manual_1"]) ?>">Clic aquí para ver el manual</a>
					</p>														
				<?php } ?>

				<?php if ($dataProduct['Product']['manual_2'] != "" ) { ?> 
					<p>
						<i class="bg-red fa fa-file-pdf-o fa-2x vtc"></i> Manual 2:
						<a target="_blank" href="<?php echo $this->Html->url("/files/products/".$dataProduct["Product"]["manual_2"]) ?>">Clic aquí para ver el manual</a>
					</p>														
				<?php } ?>

				<?php if ($dataProduct['Product']['manual_3'] != "" ) { ?> 
					<p>
						<i class="bg-red fa fa-file-pdf-o fa-2x vtc"></i> Manual 3:
						<a target="_blank" href="<?php echo $this->Html->url("/files/products/".$dataProduct["Product"]["manual_3"]) ?>">Clic aquí para ver el manual</a>
					</p>														
				<?php } ?>
			  </div>
			</div>

        </div>
      </div>
      <div class="col-lg-4">
        <div class="px-0 border rounded-2 shadow-0">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Accesorios sugeridos</h5>
              <?php if (!empty($suggested)): ?>
              	<?php foreach ($suggested as $productIDSuggested => $value): ?>
              		<?php foreach ($value["suggested"] as $key => $suggested): ?>
              			<div class="d-flex mb-3">
			                <a href="javascript::void(0)" class="me-3">
			                	<?php $ruta = $this->Utilities->validate_image_products($suggested["Product"]['img']); ?>
			                  <img src="<?php echo $this->Html->url('/img/products/'.$ruta)?>" style="min-width: 96px; height: 96px;" class="img-md img-thumbnail" />
			                </a>
			                <div class="info px-3">
			                	<p class="h6">
				                  <?php echo $suggested["Product"]["part_number"] ?> - <?php echo $suggested["Product"]["name"] ?> 
				              	</p>
			                  	<div class="d-flex align-items-center justify-content-between mt-1">
	                                <h6 class="font-weight-bold my-2">
	                                	<b><?php echo $suggested["SuggestedProduct"]["quantity"] ?> Unidad(es)</b> <br>
	                                	<span class=" text-success">
	                                		
	                                	<?php if ($currencys[$value["id"]] == "usd" ): ?>
	                                		<?php echo number_format($suggested["SuggestedProduct"]["price_usd"]*$suggested["SuggestedProduct"]["quantity"]) ?> USD
	                                	<?php else: ?>
	                                		<?php echo number_format( round( ($suggested["SuggestedProduct"]["price_usd"]*$suggested["SuggestedProduct"]["quantity"]) * $trm_suggest) ) ?> COP
	                                	<?php endif ?>
											
											+ IVA

	                                	</span>
	                                </h6>
	                            </div>
			                </div>
			              </div>
              		<?php endforeach ?>
              	<?php endforeach ?>
							
							

			 <?php endif ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<?php 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	// echo $this->Html->script("lib/fslightbox.js?".rand(),			array('block' => 'AppScript')); 
	
?>
