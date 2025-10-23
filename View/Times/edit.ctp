<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Editar configuración de bloqueo</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="brands form">
			<?php echo $this->Form->create('Time'); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('user_id',['label'=>'Usuario Asesor']);
				echo $this->Form->input('minutes',['label'=>'Mínutos para bloquear en semana']);
				echo $this->Form->input('minutes_sat',['label'=>'Mínutos para bloquear el sábado']);
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="">
							Fecha inicio/fin vacaciones
						</label>
						<input type="text" value="<?php echo date("Y-m-d") ?>" id="fechasInicioFin" class="form-control">
					</div>
				</div>
			</div>
			<?php
				echo $this->Form->hidden('date_ini',['label'=>'Activar vacaciones','id' => 'input_date_inicio']);
				echo $this->Form->hidden('date_end',['label'=>'Activar vacaciones','id' => 'input_date_fin' ]);

				echo $this->Form->input('block_user', ['label'=>'¿Bloquear el usuario?','options' => ["1"=>"SI","0"=>"NO"]]);
			?>
		<?php echo $this->Form->end(__('Guardar')); ?>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
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
