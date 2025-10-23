<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-secondary big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white	 bannerbig" >Módulo para registro de asistencias y salidas laborales </h2>	
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12">
				<h1 class="nameview">Listado de registros generados</h1>
				<span class="subname">
					<?php if (AuthComponent::user("role") == "Gerente General"): ?>
						Registros de todos los empleados
					<?php else: ?>
						Mis registros
					<?php endif ?>
				</span>
				<br>
				<a href="<?php echo $this->Html->url(["action" => "add",	]) ?>" class="btn btn-info">
					<i class="fa fa-plus vtc"></i> Crear registro
				</a>
				
				<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Logistica"])): ?>
					<a href="<?php echo $this->Html->url(["action" => "add", "controller" => "excludes"	]) ?>" class="btn btn-warning float-right">
						<i class="fa fa-plus vtc"></i> Registrar día excluido
					</a>
				<?php endif ?>

			</div>
			<?php if (AuthComponent::user("role") == "Gerente General"): ?>
				<div class="form-group">
					<div class="form-group ">
							<?php $actuales=[]; ?>
							<?php echo $this->Form->input('user_id',array('label' => false,"options" => $usuarios, "value" => isset($this->request->query["user_id"]) ? $this->request->query["user_id"]: '' ,"multiple" => false, "empty" => "Todos" ));?>
					</div>
				</div>
				<div class="rangofechas">
					<input type="date" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
					<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="">
					<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
					<a class="btn-primary btn" id="btn_find_adviser">Filtrar Fechas</a>
				</div>		
			<?php endif ?>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-hovered">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('user_id','Asesor'); ?></th>
						<th><?php echo $this->Paginator->sort('image_file','Foto'); ?></th>
						<th><?php echo $this->Paginator->sort('created','Fecha'); ?></th>
						<th><?php echo 'Demora'; ?></th>
						<th><?php echo $this->Paginator->sort('note','Nota adicional/Foto'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($assists as $key => $assist): ?>
						<tr>
							<td><?php echo $assist['User']['name'] ?></td>
							<td>
								<div class="Comprobanteacep imgbuy test-popup-link" href="<?php echo $this->Html->url('/img/asistencias/imagenes/'.$assist['Assist']['image_file']) ?>">
									<img datacomprobante="<?php echo $this->Html->url('/img/asistencias/imagenes/'.$assist['Assist']['image_file']) ?>" src="<?php echo $this->Html->url('/img/asistencias/imagenes/'.$assist['Assist']['image_file']) ?>" class="comprobanteimg" width="0px">
									Ver imagen
								</div>
								<!-- a class="test-popup-link" href="<?php //echo $this->Html->url('/img/asistencias/imagenes/'.$assist['Assist']['image_file']) ?>">
									<img src="<?php //echo $this->Html->url('/img/asistencias/imagenes/'.$assist['Assist']['image_file']) ?>" width="100" height="100" class="imgmin-product">
								</a> -->
							</td>
							<td><?php echo h($assist['Assist']['created']); ?>&nbsp;</td>	
							<td>
								<?php if ($assist["Assist"]["demora"]["dias"] > 0): ?>
									Días: <?php echo $assist["Assist"]["demora"]["dias"] ?>
								<?php endif ?>
								<?php if ($assist["Assist"]["demora"]["horas"] > 0): ?>
									Horas: <?php echo $assist["Assist"]["demora"]["horas"] ?>
								<?php endif ?>
								<?php if ($assist["Assist"]["demora"]["minutos"] >= 0): ?>
									Minutos: <?php echo $assist["Assist"]["demora"]["minutos"] ?>
								<?php endif ?>
								<?php // echo json_encode($assist["Assist"]["demora"]) ?>

							</td>
							<td>
								<?php echo $assist["Assist"]["note"] ?>
								<?php if (!empty($assist["Assist"]["file_excuse"])): ?>
									<br>
									<div class="Comprobanteacep imgbuy test-popup-link" href="<?php echo $this->Html->url('/img/asistencias/imagenes/'.$assist['Assist']['file_excuse']) ?>">
									<img datacomprobante="<?php echo $this->Html->url('/img/asistencias/imagenes/'.$assist['Assist']['file_excuse']) ?>" src="<?php echo $this->Html->url('/img/asistencias/imagenes/'.$assist['Assist']['file_excuse']) ?>" class="comprobanteimg" width="0px">
									Ver escusa
								</div>
								<?php endif ?>
							</td>	
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
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
</div>

<?php echo $this->element("jquery") ?>	
<?php echo $this->Html->script("lib/magnific.js?".rand(),				array('block' => 'AppScript'));
 ?>
<?php 
    $this->start('AppScript'); ?>

<script>
	$('.test-popup-link').magnificPopup({
		  type: 'image'
		  // other options
		});
</script>

<?php
    $this->end();
 ?>

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
			actual_query["user_id"] = $("#user_id").val();
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
