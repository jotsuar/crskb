<div class="col-md-12">
<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Detalle de Marca <?php echo h($brand['Brand']['name']); ?></h2>
		<small>Detalle de proveedor <?php echo h($brand['Brand']['provider]); ?></small>
	</div>

	<div class="blockwhite spacebtn20">
		<div class="brands view">
				<p><b><?php echo __('Identificación'); ?></b> <?php echo h($brand['Brand']['dni']); ?></p>
				<p><b><?php echo __('Nombre de contacto'); ?></b> <?php echo h($brand['Brand']['contact_name']); ?></p>
				<p><b><?php echo __('Dirección'); ?></b> <?php echo h($brand['Brand']['address']); ?></p>
				<p><b><?php echo __('Ciudad'); ?></b> <?php echo h($brand['Brand']['city']); ?></p>
				<p><b><?php echo __('Teléfono'); ?></b> <?php echo h($brand['Brand']['phone']); ?></p>
				<p><b><?php echo __('Correo eléctronico'); ?></b> <?php echo h($brand['Brand']['email']); ?></p>
				<p><b><?php echo __('Precion mínimo de importación'); ?></b> $<?php echo h($brand['Brand']['min_price_importer']); ?></p>
				<p><b><?php echo __('Marca principal'); ?></b> <?php echo $brand['Brand']['brand_id'] == 0 ? "Ninguna" : $brand["Father"]["name"]; ?></p>

				<p><b><?php echo __('Estado'); ?></b> <?php echo h($brand['Brand']['state']) == "1" ? "Activo" : "Inactivo"; ?></p>
				<p><b><?php echo __('Fecha de creación'); ?></b> <?php echo h($brand['Brand']['created']); ?></p>
				<p><b><?php echo __('Fecha de modificación'); ?></b> <?php echo h($brand['Brand']['modified']); ?></p>
				<?php if (!empty($brand["Brand"]["id_llc"])): ?>
					<p><b><?php echo __('Marca LLC: '); ?></b> <?php echo h($marcaLlc->code_fournisseur); ?> | <?php echo h($marcaLlc->name); ?></p>
				<?php endif ?>
			<?php if (!empty($brand["Children"])): ?>

				<div class="table-responsive">
					<h2 class="text-center">Marcas asociadas</h2>
					<table class="table table-bordered mt-3">
						<?php foreach ($brand["Children"] as $key => $value): ?>
							<tr>
								<th>
									Nombre
								</th>
								<td>
									<?php echo $value["name"] ?>
								</td>
								<th>
									Razón social
								</th>
								<td>
									<?php echo $value["social_reason"] ?>
								</td>
							</tr>
						<?php endforeach ?>
					</table>
				</div>
				
			<?php endif ?>
		</div>
	</div>


	</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
	echo $this->Html->script("controller/brands/save.js?".rand(),						array('block' => 'AppScript'));
?>