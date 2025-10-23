<div class="col-md-12">
			<div class=" widget-panel widget-style-2 bg-azulclaro big">
             <i class="fa fa-1x flaticon-growth"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
		</div>
	<div class="row">
		<div class="col-md-12">
			<div class="templates view blockwhite">
				<small class="themename">PLANTILLA</small>
				<div class="row">
					<div class="col-md-8">
						<h2><?php echo $this->Utilities->data_null(h($template['Template']['name'])); ?>&nbsp;<small>creada por <b><?php echo $template['User']['name'] ?></b></small></h2>
					</div>
					<div class="col-md-4 text-right">
						<h2><small><b>Creado: </b><?php echo $this->Utilities->date_castellano(h($template['Template']['created'])); ?></small></h2>
					</div>
				</div>
				<hr>
				<p><b>Descripción: </b><?php echo $this->Utilities->data_null(h($template['Template']['description'])); ?>&nbsp;</p>
				<div class="contenttableresponsive">
				<table class="table table-bordered table-striped">
					<head>
						<tr>
							<th>Imagen</th>
							<th>Referencia</th>
							<th>Nombre</th>
							<th>Descripción</th>
							<th>Marca</th>
						</tr>
					</head>
					<tbody>
					<?php foreach ($datosTP as $value): ?>
						<tr>
							<td>
								<?php $ruta = $this->Utilities->validate_image_products($value['Product']['img']); ?>
								<img src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataimgpp="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="40px" class="imgmin-pp">
							</td>
							<td><?php echo $this->Utilities->data_null(h($value['Product']['part_number'])) ?></td>
							<td><?php echo $this->Utilities->data_null(h($value['Product']['name'])) ?></td>
							<td><?php echo $this->Utilities->data_null(strip_tags($value['Product']['description'])) ?></td>
							<td><?php echo $this->Utilities->data_null(h($value['Product']['brand'])) ?></td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<div class="contentpopup">
			<img src="" class="img-productpp" alt="">
		</div>
	</div>
<div class="fondo"></div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>