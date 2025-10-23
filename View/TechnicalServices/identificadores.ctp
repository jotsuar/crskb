<div class="container">
	<div class="col-md-12">
		<div class="identificadores">
			<div class="row">
				<div class="col-md-12">
					<h2 class="">Identificadores de Equipos - ORDEN <?php echo $technicalServices['TechnicalService']['code'] ?></h2>
				</div>					
			</div>
		</div>
	</div>
	<br>
	<div class="contentfichas col-md-12">
		<h2>Recibo para el cliente</h2>
		<div class="row">
			<?php $i = 1; foreach ($equipos_servicio as $value): ?>
				<div class="ficha col-md-6">
					<span class="numequipo"> <?php echo 'EQUIPO '.$i; ?></span>
					<div class="cutline">
						<div class="blockwhitebox">
							<h3 class="nequipoficha"><?php echo strtoupper($value['ProductTechnical']['equipment']).' '.$value['ProductTechnical']['part_number'] ?> </h3>
							<p class="reasonidentif"><?php echo $value['ProductTechnical']['reason'] ?></p>
							<h3 class="text-center"><span>Cliente:</span> <?php echo $this->Utilities->name_client_contact_services($technicalServices['TechnicalService']['id']); ?></h3>
							<hr>
							<div class="row">
								<div class="col-md-4">
									<h3><span>Serie:</span><?php echo $this->Utilities->data_null($value['ProductTechnical']['serial_number']) ?></h3>
									<h3><span>Serial:</span><?php echo $this->Utilities->data_null($value['ProductTechnical']['serial_garantia']) ?></h3>
								</div>
								<div class="col-md-4">
									<h3><span>Marca:</span> <?php echo $this->Utilities->data_null($value['ProductTechnical']['brand']) ?></h3>
									<h3><span>Fecha:</span> <?php echo $this->Utilities->date_castellano($value['ProductTechnical']['created']) ?></h3>
								</div>
								<div class="col-md-4">
									<h3><span>Orden:</span> <?php echo $technicalServices['TechnicalService']['code'].' - '.'0'.$i ?></h3>
								</div>						
							</div>	
						</div>
						<div class="footerficha">
							<img src="https://www.almacendelpintor.com/img/almacendelpintor-logo-1495159125.jpg" class="img-fluid">
						</div>
					</div>
				</div>
			<?php $i++; endforeach ?>
		</div>


	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>