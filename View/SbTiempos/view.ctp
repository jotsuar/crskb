<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12">
				<h1 class="nameview text-center">
					INFORME DE TIEMPOS DE CONEXIÓN DETALLADO PARA EL USUARIO: <?php echo $user["SbUser"]["first_name"]." ".$user["SbUser"]["last_name"] ?>
					
				</h1>
				<a href="<?php echo $this->Html->url(array('controller'=>'SbTiempos','action'=>'index',"?"=>array("ini" =>$this->request->query["ini"], "end" => $this->request->query["end"]  ))) ?>" class="btn btn-info pull-right">Volver</a>
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive px-5">
			<h2 class="text-center mb-2">
					RANGO DE FECHAS: <?php echo $this->request->query["ini"] ?> - <?php echo $this->request->query["end"] ?>
			</h2>
			<table cellpadding="0" cellspacing="0" class="table table-hovered table-bordered">
				<thead>
				<tr>
						<th><?php echo $this->Paginator->sort('type','Tipo'); ?></th>
						<th><?php echo $this->Paginator->sort('date_ini','Inicio'); ?></th>
						<th><?php echo $this->Paginator->sort('date_end','Fin'); ?></th>
						<th><?php echo __('Tiempo transcurrido'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($sbTiempos as $sbTiempo): ?>
					<tr class="<?php echo ($sbTiempo['SbTiempo']['type']) == 1 ? "text-success" : "text-danger"; ?> text-white">
						<td><strong><?php echo ($sbTiempo['SbTiempo']['type']) == 1 ? "Activo" : "Inactivo"; ?>&nbsp;</strong></td>
						<td><?php echo h($sbTiempo['SbTiempo']['date_ini']); ?>&nbsp;</td>
						<td><?php echo h($sbTiempo['SbTiempo']['date_end']); ?>&nbsp;</td>
						<td><?php echo $this->Utilities->totalTimeLapsed($sbTiempo['SbTiempo']['date_ini'],$sbTiempo['SbTiempo']['date_end']); ?>&nbsp;</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row numberpages">
		<?php
			echo $this->Paginator->first('<< ', array('class' => 'prev'), null);
			echo $this->Paginator->prev('< ', array(), null, array('class' => 'prev disabled'));
			echo $this->Paginator->counter(array('format' => '{:page} de {:pages}'));
			echo $this->Paginator->next(' >', array(), null, array('class' => 'next disabled'));
			echo $this->Paginator->last(' >>', array('class' => 'next'), null);
		?>
		<b> <?php echo $this->Paginator->counter(array('format' => '{:count} en total')); ?></b>
	</div>
</div>

<?php 
	
echo $this->Html->script(array('//code.jquery.com/jquery-1.9.1.js'),array('block' => 'jqueryApp'));

?>
<?php echo $this->element("picker"); ?>

<?php 
	$this->start('AppScript'); ?>

	<script>
		$("#btn_find_adviser").click(function(event) {
			var actual_query        =  URLToArray(actual_uri);

			actual_query["ini"] = $("#input_date_inicio").val();
			actual_query["end"] = $("#input_date_fin").val();
			location.href = actual_url+$.param(actual_query);
			console.log(actual_query)
		});

		$("#guardaInforme").click(function(event) {
			$("#ManagementContent").val($("#ventas").html());
			$("#ManagementAddForm").trigger('submit')
		});

	</script>

<?php
	$this->end();
 ?>
