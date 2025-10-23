<div class="col-md-3">
	<div class="blockwhiteinforme">
		<h2 class="text-center"><?php echo $dias_date_data['name_asesor'] ?></h2>
		<br>
		<div class="graficaporasesor">
			<div class="textCargando">Cargando ...</div>
			<canvas id="<?php echo 'myChart'.$position ?>"></canvas>
		</div>
		<div class="barrademoras">
			<span class="contactar">D. en contactar Aprox.<p class="valordemoracontactar"><?php echo floor($dias_date_data['demora_contactar']) ?>h</p></span>
			<span class="cotizar">D. en cotizar Aprox.<p class="valordemoracontactar"><?php echo floor($dias_date_data['demora_cotizar']) ?>h</p></span>
		</div>
		<div class="datauserinforme">
			<div class="leftdata">
				<p><b><?php echo $dias_date_data['asignados'] ?></b>Prospectos Asignados</p>
				<p><b><?php echo $dias_date_data['contactados'] ?></b>Prospectos Contactados</p>
				<p><b><?php echo $dias_date_data['cotizados'] ?></b>Negocios cotizados</p>
				<p><b><?php echo $dias_date_data['ventas'] ?></b>Ventas realizadas</p>
			</div>
			<div class="rightdata">
				<span><?php echo bcdiv($dias_date_data['efectividad'], '1', 2); ?>%</span>
				<p>EFECTIVIDAD</p>
			</div>
		</div>
	</div>
</div>
<br>