<div class="col-md-12 p-0">
	<div class="row">
		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser completados findInformationFlujos" data-cuadro="all">
				<a href="javascript:void(0)">
					<h2><?php echo $countFlujosTotalesRangoFechas; ?></h2>
					<h3>Flujos Totales</h3>
				</a>
			</div>
		</div>
		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser completados findInformationFlujos" data-cuadro="completed">
				<a href="javascript:void(0)">
					<h2><?php echo $countFlujosTerminadosRangoFechas; ?></h2>
					<h3>Flujos Completados</h3>
				</a>
			</div>
		</div>		
		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser completados findInformationFlujos" data-cuadro="completed">
				<a href="javascript:void(0)">
					<h2><?php echo bcdiv($porcentajeFlujosCompletadosRangoFechas, '1', 2); ?> %</h2>
					<h3>Porcentaje Completados</h3>
				</a>
			</div>
		</div>		
		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser asignados findInformationFlujos" data-cuadro="assigned">
				<a href="javascript:void(0)">
					<h2><?php echo $countFlujosAsignadosRangoFechas; ?></h2>
					<h3>En etapa Asignado</h3>
				</a>
			</div>
		</div>
		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser abiertos findInformationFlujos" data-cuadro="proccess">
				<a href="javascript:void(0)">
					<h2><?php echo $countFlujosProcesoRangoFechas; ?></h2>
					<h3>Flujos en Proceso</h3>
				</a>
			</div>
		</div>
		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser porcentajecotizados findInformationFlujos" data-cuadro="quotation">
				<a href="javascript:void(0)">
					<h2><?php echo bcdiv($porcentajeFlujosCotizadosRangoFecha, '1', 2); ?> %</h2>
					<h3>Porcentaje Cotizados</h3>
					<small>(Proceso / Cotizados)</small>
				</a>
			</div>
		</div>
		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser hretraso findInformationFlujos" data-cuadro="quotation">
				<a href="javascript:void(0)">
					<h2><?php echo $countFlujosCotizadosRangoFechas ?></h2>
					<h3>Flujos Cotizados</h3>
				</a>
			</div>
		</div>

		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser hretraso" style="cursor: text;">
				<a href="javascript:void(0)">
					<h2><?php echo $countFlujosRetrasoRangoFechas ?></h2>
					<h3>Flujos con retraso</h3>
					<small>
						Aprox.
						<?php if ($totalHorasDemoraFlujosRangoFechas == 0){ ?>
							0<span> dia(s)</span></h3>
						<?php } else { ?>

							<?php echo floor($this->Utilities->return_hours_in_days($totalHorasDemoraFlujosRangoFechas)); ?><span> dia(s)</span></h3>

						<?php } ?>
					</small>
				</a>
			</div>
		</div>
		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser hretraso">
				<a href="javascript:void(0)" style="cursor: text;">
					<?php if ($demoraFlujosContactadoRangoFechas == 0){ ?>
						<h2>0<span> dia(s)</span></h2>
					<?php } else { ?>

						<h2><?php echo floor($this->Utilities->return_hours_in_days($demoraFlujosContactadoRangoFechas)); ?><span> dia(s)</span></h2>

					<?php } ?>
					<h3>Retraso en contactar</h3>
					<small>Aproximadamente</small>
				</a>
			</div>
		</div>
		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser hretraso">
				<a href="javascript:void(0)" style="cursor: text;">
					<?php if ($demoraFlujosCotizarRangoFechas == 0){ ?>
						<h2>0<span> dia(s)</span></h2>
					<?php } else { ?>

						<h2><?php echo floor($this->Utilities->return_hours_in_days($demoraFlujosCotizarRangoFechas)); ?><span> dia(s)</span></h2>

					<?php } ?>
					<h3>Retraso en cotizar</h3>
					<small>Aproximadamente</small>
				</a>
			</div>
		</div>
		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser cancelados findInformationFlujos" data-cuadro="canceled">
				<a href="javascript:void(0)">
					<h2><?php echo $countFlujosCanceladosRangoFechas; ?></h2>
					<h3>Flujos Cancelados</h3>
				</a>
			</div>
		</div>

		<div class="col-md-2 col-6 spacebtn20">
			<div class="blockreportadviser cancelados findInformationFlujos" data-cuadro="invalid">
				<a href="javascript:void(0)">
					<h2><?php echo $countFlujosNoValidos; ?></h2>
					<h3>Flujos no válidos</h3>
				</a>
			</div>
		</div>		
	</div>
</div>