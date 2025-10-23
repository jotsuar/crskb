<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12">
				<h2 class="titleviewer">Detalle de listas de distribución</h2>
			</div>
		</div>
	</div>

	<div class=" blockwhite spacebtn20">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Nombre</th>
					<td>
						<?php echo h($mailingList['MailingList']['name']); ?>
					</td>
				</tr>
				<tr>
					<th>Tipo de lista</th>
					<td>
						<?php echo $mailingList["MailingList"]["type"] == 1 ? "Whatsapp" : "Correos"; ?>
					</td>
				</tr>
				<tr>
					<th>Celulares / correos</th>
					<td>
						<ul>
							<?php $cels = explode(",", $mailingList['MailingList']['numbers']) ?>
							<?php foreach ($cels as $key => $value): ?>
								<li>
									<?php echo $value ?>
								</li>
							<?php endforeach ?>
						</ul>
					</td>
				</tr>
				<tr>
					<th>Fecha de creación</th>
					<td>
						<?php echo h($mailingList['MailingList']['created']); ?>
					</td>
				</tr>
			</thead>
		</table>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript'));
?>
