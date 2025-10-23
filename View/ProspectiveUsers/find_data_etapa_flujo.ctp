<p><b>Fecha y hora límite:</b> <?php echo $datos['Manage']['date'].' '.$datos['Manage']['time_end'] ?></p>
<p><b>Fecha y hora atendido:</b> <?php echo $atendidoRequerimiento ?></p>
<?php echo $this->Utilities->compararTiempoLimiteAtendidoFlujo($datos['Manage']['date'].' '.$datos['Manage']['time_end'],$atendidoRequerimiento); ?>