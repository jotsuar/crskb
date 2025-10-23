<div class="col-md-12">
	<div class="row">
		<div class="col-md-4 col-6 spacebtn20">
			<div class="blockreportadviser completados findInformationFlujos" data-cuadro="1">
				<a href="javascript:void(0)">
					<h2><?php echo $countClientesNuevos; ?></h2>
					<h3>Flujos Totales</h3>
				</a>
			</div>
		</div>
		<div class="col-md-4 col-6 spacebtn20">
			<div class="blockreportadviser asignados findInformationFlujos" data-cuadro="2">
				<a href="javascript:void(0)">
					<h2><?php echo $countVentasClientesNuevos; ?></h2>
					<h3>Total de ventas realizadas</h3>
				</a>
			</div>
		</div>
		<div class="col-md-4 col-6 spacebtn20">
			<div class="blockreportadviser abiertos findInformationFlujos" data-cuadro="3">
				<a href="javascript:void(0)">
					<h2><?php echo number_format($totalVentas,0,",","."); ?></h2>
					<h3>Total en ventas</h3>
				</a>
			</div>
		</div>

	</div>
</div>