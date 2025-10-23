<div class="row">
<?php if (!empty($quotations)): ?>
	<div class="col-md-12">
		
	<button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		Ver cotizaciones activas que contienen uno o varios productos en esta cotizacion
	</button>
    <div class="dropdown-menu">
    	<?php foreach ($quotations as $key => $value): ?>
    		<a class="dropdown-item getQuotationId" data-user="<?php echo $value["Quotation"]["id"] ?>" data-quotation="<?php echo $value["Quotation"]["id"] ?>" href="#"><?php echo $value["Quotation"]["codigo"] ?> - <?php echo $value["Quotation"]["name"] ?> </a>
    	<?php endforeach ?>
    </div>
	</div>
<?php endif ?>
<?php if (!empty($quotationsCliente)): ?>
	<div class="col-md-12">
		
	<button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		Ver cotizaciones activas que contienen uno o varios productos en esta cotizacion para el mismo cliente
	</button>
    <div class="dropdown-menu">
    	<?php foreach ($quotationsCliente as $key => $value): ?>
    		<a class="dropdown-item getQuotationId" data-user="<?php echo $value["Quotation"]["id"] ?>" data-quotation="<?php echo $value["Quotation"]["id"] ?>" href="#"><?php echo $value["Quotation"]["codigo"] ?> - <?php echo $value["Quotation"]["name"] ?> </a>
    	<?php endforeach ?>
    </div>
	</div>
<?php endif ?>
</div>
