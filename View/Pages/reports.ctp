<div class="col-md-12">
	<!-- Header principal -->
	<div class="widget-panel widget-style-2 bg-azulclaro big mb-4">
		<div class="d-flex align-items-center">
			<i class="vtc fa fa-2x flaticon-growth text-white me-3"></i>
			<div>
				<h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM</h2>
				<p class="text-white-50 mb-0">Informe de Conversaciones - <?php echo $fecha_consulta ?></p>
			</div>
		</div>
	</div>

	<!-- Filtro de fecha -->
	<?php if (AuthComponent::user("id")): ?>
	<div class="card mb-4">
		<div class="card-body">
			<?php echo $this->Form->create(false,array('class' => 'form w-100',"type"=>"GET")); ?>
			<div class="row align-items-center">
					<div class="col-md-4">
						<div class="form-group">
							<span>Seleccionar rango de fechas:</span>
						</div>
						<div class="form-group">
							<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
						</div>

					</div>
					<div style="display: none">
						<span>Desde</span>
						<input type="date" value="<?php echo $fechaInicioReporte ?>" class="form-control" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
						<span>Hasta</span>
						<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
					</div>
					<div class="col-md-2 spacetop">
						<button type="submit" class="btn btn-primary" id="btn_find_adviser">Buscar</button>
					
					</div>
				<div class="col-md-6 text-md-end mt-3 mt-md-0">
					<!-- <button class="btn btn-outline-secondary" onclick="exportarReporte()">
						<i class="vtc fa fa-download"></i> Exportar
					</button> -->
				</div>
			</div>
		</div>
		</form>
	</div>
	<?php endif ?>

	<!-- Resumen ejecutivo mejorado -->
	<div class="row mb-4">
		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-azul text-white">
					<h3 class="mb-0"><i class="vtc fa fa-tachometer-alt me-2"></i>Resumen Ejecutivo</h3>
				</div>
				<div class="card-body">
					<div class="row g-3">
						<div class="col-6 col-md-3">
							<div class="text-center p-3 bg-light rounded">
								<div class="h2 text-primary fw-bold"><?php echo count($reports); ?></div>
								<div class="text-muted small">Total de Conversaciones Analizadas</div>
							</div>
						</div>
						<div class="col-6 col-md-3">
							<div class="text-center p-3 bg-light rounded">
								<?php
								$totalTiempoRespuesta = 0;
								$conversacionesConTiempo = 0;
								$conversacionesSinContacto = 0;
								foreach($reports as $report) {
									$tiempoRespuesta = intval($report['Report']['tiempo_promedio_respuesta_segundos']);
									if($tiempoRespuesta > 0) {
										$totalTiempoRespuesta += $tiempoRespuesta;
										$conversacionesConTiempo++;
									} else {
										$conversacionesSinContacto++;
									}
								}
								$promedioTiempoRespuesta = $conversacionesConTiempo > 0 ? round($totalTiempoRespuesta / $conversacionesConTiempo, 0) : 0;
								$tiempoFormateado = formatearTiempoRespuesta($promedioTiempoRespuesta);
								?>
								<div class="h2 text-<?php echo $tiempoFormateado['color']; ?> fw-bold"><?php echo $tiempoFormateado['texto']; ?></div>
								<div class="text-muted small">Tiempo Promedio de Respuesta del Asesor</div>
								<?php if($conversacionesSinContacto > 0): ?>
									<small class="text-danger"><?php echo $conversacionesSinContacto; ?> sin contactar al cliente</small>
								<?php endif; ?>
							</div>
						</div>
						<div class="col-6 col-md-3">
							<div class="text-center p-3 bg-light rounded">
								<?php
								$totalCalificacion = 0;
								foreach($reports as $report) {
									$totalCalificacion += intval($report['Report']['calificacion_general_10']);
								}
								$promedioCalificacion = count($reports) > 0 ? round($totalCalificacion / count($reports), 1) : 0;
								?>
								<div class="h2 text-warning fw-bold"><?php echo $promedioCalificacion; ?>/10</div>
								<div class="text-muted small">Calificación Promedio de Satisfacción del Cliente</div>
							</div>
						</div>
						<div class="col-6 col-md-3">
							<div class="text-center p-3 bg-light rounded">
								<?php
								$totalMensajes = 0;
								foreach($reports as $report) {
									$totalMensajes += intval($report['Report']['cantidad_mensajes_cliente']) + intval($report['Report']['cantidad_mensajes_asesor']);
								}
								$promedioMensajes = count($reports) > 0 ? round($totalMensajes / count($reports), 0) : 0;
								?>
								<div class="h2 text-info fw-bold"><?php echo $promedioMensajes; ?></div>
								<div class="text-muted small">Promedio de Mensajes por Conversación</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Análisis de asesores -->
	<?php
	// Función para formatear tiempo de respuesta
	function formatearTiempoRespuesta($segundos) {
		// Si es 0, null o vacío, el asesor no contactó
		if (empty($segundos) || $segundos == 0) {
			return array('texto' => 'No contactó', 'color' => 'danger', 'valor' => 0);
		}
		
		$segundos = intval($segundos);
		
		// Si es menos de 60 segundos
		if ($segundos < 60) {
			return array('texto' => $segundos . 's', 'color' => 'success', 'valor' => $segundos);
		}
		
		// Si es menos de 3600 segundos (1 hora)
		if ($segundos < 3600) {
			$minutos = floor($segundos / 60);
			$segundosRestantes = $segundos % 60;
			$texto = $minutos . 'm';
			if ($segundosRestantes > 0) {
				$texto .= ' ' . $segundosRestantes . 's';
			}
			$color = $segundos <= 60 ? 'success' : ($segundos <= 300 ? 'info' : ($segundos <= 1800 ? 'warning' : 'danger'));
			return array('texto' => $texto, 'color' => $color, 'valor' => $segundos);
		}
		
		// Si es menos de 86400 segundos (1 día)
		if ($segundos < 86400) {
			$horas = floor($segundos / 3600);
			$minutos = floor(($segundos % 3600) / 60);
			$texto = $horas . 'h';
			if ($minutos > 0) {
				$texto .= ' ' . $minutos . 'm';
			}
			return array('texto' => $texto, 'color' => 'danger', 'valor' => $segundos);
		}
		
		// Si es más de 1 día
		$dias = floor($segundos / 86400);
		$horas = floor(($segundos % 86400) / 3600);
		$texto = $dias . 'd';
		if ($horas > 0) {
			$texto .= ' ' . $horas . 'h';
		}
		return array('texto' => $texto, 'color' => 'danger', 'valor' => $segundos);
	}
	
	// Función para calificar tiempo de respuesta de una conversación
	function calificarTiempoRespuesta($segundos) {
		if (empty($segundos) || $segundos == 0) {
			return 0; // No contactó = 0 puntos
		}
		
		$segundos = intval($segundos);
		
		// Calificación basada en tiempo de respuesta
		if ($segundos <= 30) {
			return 10; // Excelente: 0-30 segundos
		} elseif ($segundos <= 60) {
			return 9; // Muy bueno: 30-60 segundos
		} elseif ($segundos <= 120) {
			return 8; // Bueno: 1-2 minutos
		} elseif ($segundos <= 300) {
			return 7; // Regular: 2-5 minutos
		} elseif ($segundos <= 600) {
			return 6; // Aceptable: 5-10 minutos
		} elseif ($segundos <= 1800) {
			return 5; // Lento: 10-30 minutos
		} elseif ($segundos <= 3600) {
			return 4; // Muy lento: 30-60 minutos
		} elseif ($segundos <= 7200) {
			return 3; // Extremadamente lento: 1-2 horas
		} elseif ($segundos <= 86400) {
			return 2; // Inaceptable: 2-24 horas
		} else {
			return 1; // Crítico: más de 24 horas
		}
	}
	
	// Calcular estadísticas por asesor
	$asesores = array();
	foreach($reports as $report) {
		$asesorId = $report['Report']['user_id'];
		$asesorNombre = $report['Report']['asesor_nombre'];
		
		if(!isset($asesores[$asesorId])) {
			$asesores[$asesorId] = array(
				'nombre' => $asesorNombre,
				'conversaciones' => 0,
				'total_calificacion' => 0,
				'total_calificacion_tiempo' => 0,
				'total_tiempo_respuesta' => 0,
				'total_mensajes' => 0,
				'role' => $report['User']['role']
			);
		}
		
		$asesores[$asesorId]['conversaciones']++;
		$asesores[$asesorId]['total_calificacion'] += intval($report['Report']['calificacion_general_10']);
		$asesores[$asesorId]['total_tiempo_respuesta'] += intval($report['Report']['tiempo_promedio_respuesta_segundos']);
		$asesores[$asesorId]['total_mensajes'] += intval($report['Report']['cantidad_mensajes_cliente']) + intval($report['Report']['cantidad_mensajes_asesor']);
		
		// Calificar tiempo de respuesta de esta conversación
		$calificacionTiempo = calificarTiempoRespuesta($report['Report']['tiempo_promedio_respuesta_segundos']);
		$asesores[$asesorId]['total_calificacion_tiempo'] += $calificacionTiempo;
	}

	// Calcular promedios y puntuaciones
	$asesoresRanking = array();
	foreach($asesores as $id => $asesor) {
		$asesores[$id]['promedio_calificacion'] = round($asesor['total_calificacion'] / $asesor['conversaciones'], 1);
		$asesores[$id]['promedio_tiempo_respuesta'] = round($asesor['total_tiempo_respuesta'] / $asesor['conversaciones'], 0);
		$asesores[$id]['promedio_mensajes'] = round($asesor['total_mensajes'] / $asesor['conversaciones'], 0);
		$asesores[$id]['promedio_calificacion_tiempo'] = round($asesor['total_calificacion_tiempo'] / $asesor['conversaciones'], 1);
		
		// Nueva puntuación: promedio de calificaciones de conversaciones y calificaciones de tiempo de respuesta
		$promedioCalificaciones = $asesores[$id]['promedio_calificacion'];
		$promedioCalificacionTiempo = $asesores[$id]['promedio_calificacion_tiempo'];
		
		// Puntuación final es el promedio de ambas calificaciones
		$puntuacion = round(($promedioCalificaciones + $promedioCalificacionTiempo) / 2, 1);
		
		$asesoresRanking[] = array(
			'id' => $id,
			'nombre' => $asesor['nombre'],
			'role' => $asesor['role'],
			'conversaciones' => $asesor['conversaciones'],
			'promedio_calificacion' => $asesores[$id]['promedio_calificacion'],
			'promedio_calificacion_tiempo' => $asesores[$id]['promedio_calificacion_tiempo'],
			'promedio_tiempo_respuesta' => $asesores[$id]['promedio_tiempo_respuesta'],
			'promedio_mensajes' => $asesores[$id]['promedio_mensajes'],
			'puntuacion' => $puntuacion,
			'no_contacto' => ($asesores[$id]['promedio_tiempo_respuesta'] == 0)
		);
	}

	// Ordenar por puntuación descendente
	usort($asesoresRanking, function($a, $b) {
		if ($a['puntuacion'] == $b['puntuacion']) {
			return 0;
		}
		return ($a['puntuacion'] > $b['puntuacion']) ? -1 : 1;
	});
	?>

	<!-- Ranking de Asesores -->
	<div class="row mb-4">
		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-azul text-white">
					<h3 class="mb-0"><i class="vtc fa fa-trophy me-2"></i>Ranking de Asesores</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<?php foreach($asesoresRanking as $index => $asesor): ?>
							<?php 
							$esPrimero = $index === 0;
							$esSegundo = $index === 1;
							$esTercero = $index === 2;
							$bgClass = $esPrimero ? 'bg-warning' : ($esSegundo ? 'bg-light' : ($esTercero ? 'bg-light' : 'bg-white'));
							$borderClass = $esPrimero ? 'border-warning' : ($esSegundo ? 'border-secondary' : ($esTercero ? 'border-warning' : 'border-light'));
							$iconClass = $esPrimero ? 'fa-trophy text-warning' : ($esSegundo ? 'fa-medal text-secondary' : ($esTercero ? 'fa-medal text-warning' : 'fa-user text-muted'));
							?>
							<div class="col-12 mb-3">
								<div class="card <?php echo $bgClass; ?> <?php echo $borderClass; ?> border-2 shadow-sm">
									<div class="card-body p-3">
										<div class="row align-items-center">
											<div class="col-auto">
												<div class="d-flex align-items-center">
													<span class="badge bg-primary rounded-pill me-3" style="font-size: 1.2rem; min-width: 40px;">
														<?php echo $index + 1; ?>
													</span>
													<i class="vtc fa <?php echo $iconClass; ?> fa-2x me-3"></i>
													<div>
														<h5 class="mb-1 fw-bold"><?php echo htmlspecialchars($asesor['nombre']); ?></h5>
														<small class="text-muted"><?php echo htmlspecialchars($asesor['role']); ?></small>
														<?php if($asesor['no_contacto']): ?>
															<br><small class="text-danger"><i class="vtc fa fa-exclamation-triangle me-1"></i>No contactó a clientes</small>
														<?php endif; ?>
													</div>
												</div>
											</div>
											<div class="col">
												<div class="row text-center">
													<div class="col-3">
														<div class="h6 text-primary mb-0"><?php echo $asesor['conversaciones']; ?></div>
														<small class="text-muted">Conversaciones</small>
													</div>
													<div class="col-3">
														<div class="h6 text-warning mb-0"><?php echo $asesor['promedio_calificacion']; ?>/10</div>
														<small class="text-muted">Calificación Promedio de conversaciones</small>
													</div>
													<div class="col-3">
														<div class="h6 text-info mb-0"><?php echo $asesor['promedio_calificacion_tiempo']; ?>/10</div>
														<small class="text-muted">Calificación Promedio de Tiempo de Respuesta</small>
													</div>
													<div class="col-3">
														<?php
														$tiempoFormateado = formatearTiempoRespuesta($asesor['promedio_tiempo_respuesta']);
														?>
														<div class="h6 text-<?php echo $tiempoFormateado['color']; ?> mb-0"><?php echo $tiempoFormateado['texto']; ?></div>
														<small class="text-muted">Tiempo Promedio de Respuesta del Asesor</small>
													</div>
												</div>
											</div>
											<div class="col-auto">
												<div class="text-center">
													<div class="h3 fw-bold text-success mb-0"><?php echo $asesor['puntuacion']; ?></div>
													<small class="text-muted">Puntuación Final (Promedio de Calificaciones de Conversaciones y Tiempo de Respuesta)</small>
													<br>
													<small class="text-muted">(<?php echo $asesor['promedio_calificacion']; ?> + <?php echo $asesor['promedio_calificacion_tiempo']; ?>) ÷ 2</small>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="mt-3">
						<small class="text-muted">
							<i class="vtc fa fa-info-circle me-1"></i>
							Puntuación Final = (Calificación Promedio de Conversaciones + Calificación Promedio de Tiempo de Respuesta) ÷ 2
						</small>
						<br>
						<button type="button" class="btn btn-sm btn-outline-warning mt-2" data-toggle="modal" data-target="#calificacionTiempoModal">
							<i class="vtc fa fa-star me-1"></i>
							Ver Sistema de Calificación por Tiempo de Respuesta
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
// Calcular las 5 oportunidades de mejora y fortalezas más frecuentes
$oportunidadesTotales = array();
$fortalezasTotales = array();
foreach($reports as $report) {
	// Oportunidades de mejora
	if (!empty($report['Report']['oportunidades_mejora'])) {
		$op = @json_decode($report['Report']['oportunidades_mejora'], true);
		if (!is_array($op)) $op = array($report['Report']['oportunidades_mejora']);
		foreach($op as $item) {
			$item = trim($item);
			if ($item) $oportunidadesTotales[$item] = isset($oportunidadesTotales[$item]) ? $oportunidadesTotales[$item]+1 : 1;
		}
	}
	// Fortalezas detectadas
	if (!empty($report['Report']['fortalezas_detectadas'])) {
		$ft = @json_decode($report['Report']['fortalezas_detectadas'], true);
		if (!is_array($ft)) $ft = array($report['Report']['fortalezas_detectadas']);
		foreach($ft as $item) {
			$item = trim($item);
			if ($item) $fortalezasTotales[$item] = isset($fortalezasTotales[$item]) ? $fortalezasTotales[$item]+1 : 1;
		}
	}
}
arsort($oportunidadesTotales);
arsort($fortalezasTotales);
$topOportunidades = array_slice($oportunidadesTotales, 0, 5, true);
$topFortalezas = array_slice($fortalezasTotales, 0, 5, true);
?>

	<!-- Sección de Oportunidades y Fortalezas más visibles -->
	<div class="row mb-4">
		<div class="col-md-6 mb-3 mb-md-0">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-warning text-dark">
					<h5 class="mb-0"><i class="vtc fa fa-lightbulb me-2"></i>Oportunidades de Mejora Más Visibles</h5>
				</div>
				<div class="card-body">
					<?php if (count($topOportunidades)): ?>
						<ol class="mb-0 ps-3">
							<?php foreach($topOportunidades as $item => $count): ?>
								<li class="mb-2"><span class="fw-bold text-warning"><i class="vtc fa fa-arrow-right me-1"></i></span><?php echo htmlspecialchars($item); ?> <span class="badge bg-light text-dark ms-2"><?php echo $count; ?></span></li>
							<?php endforeach; ?>
						</ol>
					<?php else: ?>
						<p class="text-muted mb-0">No se identificaron oportunidades de mejora frecuentes.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-success text-white">
					<h5 class="mb-0"><i class="vtc fa fa-thumbs-up me-2"></i>Fortalezas Más Visibles</h5>
				</div>
				<div class="card-body">
					<?php if (count($topFortalezas)): ?>
						<ol class="mb-0 ps-3">
							<?php foreach($topFortalezas as $item => $count): ?>
								<li class="mb-2"><span class="fw-bold text-success"><i class="vtc fa fa-check me-1"></i></span><?php echo htmlspecialchars($item); ?> <span class="badge bg-light text-dark ms-2"><?php echo $count; ?></span></li>
							<?php endforeach; ?>
						</ol>
					<?php else: ?>
						<p class="text-muted mb-0">No se identificaron fortalezas frecuentes.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Gráficas con Highcharts -->
	<div class="row mb-4">
		<div class="col-lg-6 mb-4">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-success text-white">
					<h5 class="mb-0"><i class="vtc fa fa-star me-2"></i>Distribución de Calificaciones</h5>
				</div>
				<div class="card-body">
					<div id="calificacionesChart" style="height: 300px;"></div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 mb-4">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-warning text-dark">
					<h5 class="mb-0"><i class="vtc fa fa-comments me-2"></i>Tono del Asesor</h5>
				</div>
				<div class="card-body">
					<div id="tonoChart" style="height: 300px;"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mb-4">
		<div class="col-lg-6 mb-4">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-info text-white">
					<h5 class="mb-0"><i class="vtc fa fa-clock me-2"></i>Tiempo Promedio de Respuesta</h5>
				</div>
				<div class="card-body">
					<div id="tiempoRespuestaChart" style="height: 300px;"></div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 mb-4">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-primary text-white">
					<h5 class="mb-0"><i class="vtc fa fa-chart-line me-2"></i>Rendimiento por Asesor</h5>
				</div>
				<div class="card-body">
					<div id="rendimientoAsesoresChart" style="height: 300px;"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Tabla mejorada -->
	<div class="card border-0 shadow-sm">
		<div class="card-header bg-azul text-white">
			<h3 class="mb-0"><i class="vtc fa fa-table me-2"></i>Detalle de Conversaciones</h3>
		</div>
		<div class="card-body p-0">
			<!-- Vista móvil: Cards -->
			<div class="d-md-none">
				<?php foreach($reports as $report): ?>
				<div class="card mb-3 mx-3 border-0 shadow-sm">
					<div class="card-body">
						<div class="row g-2">
							<div class="col-12">
								<div class="d-flex justify-content-between align-items-start mb-2">
									<div>
										<h6 class="text-primary mb-1"><?php echo htmlspecialchars($report['Report']['cliente_nombre']); ?></h6>
										<small class="text-muted" style="display: inline-block !important;">ID: 
											<a target="_blank" href="<?php echo Configure::read('URL_CHAT_CONVERSATION') . $report['Report']['conversation_id']; ?>" target="_blank">
												<?php echo $report['Report']['conversation_id']; ?>
											</a>
										</small>
									</div>
									<button class="btn btn-sm btn-outline-primary" onclick="verDetalle(<?php echo $report['Report']['id']; ?>)">
										<i class="vtc fa fa-eye"></i>
									</button>
								</div>
							</div>
							
							<div class="col-6">
								<small class="text-muted d-block">Asesor:</small>
								<strong><?php echo htmlspecialchars($report['Report']['asesor_nombre']); ?></strong>
								<br><small class="text-muted"><?php echo htmlspecialchars($report['User']['role']); ?></small>
							</div>
							
							<div class="col-6">
								<small class="text-muted d-block">Fecha:</small>
								<strong><?php echo date('d/m/Y', strtotime($report['Report']['fecha'])); ?></strong>
								<br><small class="text-muted"><?php echo date('H:i', strtotime($report['Report']['inicio_conversacion'])); ?></small>
							</div>
							
							<div class="col-6">
								<small class="text-muted d-block">Tiempo Respuesta:</small>
								<?php
								$tiempoFormateado = formatearTiempoRespuesta($report['Report']['tiempo_promedio_respuesta_segundos']);
								?>
								<span class="text-white badge bg-<?php echo $tiempoFormateado['color']; ?>"><?php echo $tiempoFormateado['texto']; ?></span>
							</div>
							
							<div class="col-6">
								<small class="text-muted d-block">Calificación:</small>
								<?php
								$calificacion = intval($report['Report']['calificacion_general_10']);
								$color = $calificacion >= 8 ? 'success' : ($calificacion >= 6 ? 'warning' : 'danger');
								?>
								<span class="text-white badge bg-<?php echo $color; ?>"><?php echo $calificacion; ?>/10</span>
							</div>
							
							<div class="col-6">
								<small class="text-muted d-block">Calif. Tiempo:</small>
								<?php
								$calificacionTiempo = calificarTiempoRespuesta($report['Report']['tiempo_promedio_respuesta_segundos']);
								$colorTiempo = $calificacionTiempo >= 8 ? 'success' : ($calificacionTiempo >= 6 ? 'info' : ($calificacionTiempo >= 4 ? 'warning' : 'danger'));
								?>
								<span class="text-white badge bg-<?php echo $colorTiempo; ?>"><?php echo $calificacionTiempo; ?>/10</span>
							</div>
							
							<div class="col-6">
								<small class="text-muted d-block">Tono:</small>
								<?php
								$tono = $report['Report']['tono_asesor'];
								$tonoColor = 'secondary';
								if(strpos($tono, 'Cordial y proactivo') !== false) $tonoColor = 'success';
								elseif(strpos($tono, 'Cordial') !== false) $tonoColor = 'info';
								elseif(strpos($tono, 'Automático') !== false) $tonoColor = 'warning';
								elseif(strpos($tono, 'Repetitivo') !== false) $tonoColor = 'danger';
								?>
								<span class="text-white badge bg-<?php echo $tonoColor; ?>"><?php echo htmlspecialchars($tono); ?></span>
							</div>
							
							<div class="col-6">
								<small class="text-muted d-block">Protocolo:</small>
								<?php
								$protocolo = $report['Report']['cumplimiento_protocolo'];
								$protocoloColor = 'secondary';
								if($protocolo == 'Completo') $protocoloColor = 'success';
								elseif($protocolo == 'Parcial') $protocoloColor = 'warning';
								elseif(strpos($protocolo, 'No se cumplió') !== false) $protocoloColor = 'danger';
								?>
								<?php $protocolo = $this->Text->truncate(strip_tags($protocolo), 30,array('ellipsis' => '.','exact' => false)); ?>
								<span class="text-white badge bg-<?php echo $protocoloColor; ?>"><?php echo htmlspecialchars($protocolo); ?></span>
							</div>
							
							<div class="col-12">
								<small class="text-muted d-block">Resumen:</small>
								<div class="text-truncate" title="<?php echo htmlspecialchars($report["Report"]["analisis_completo"]); ?>">
									<?php echo htmlspecialchars(substr($report["Report"]["analisis_completo"], 0, 80)) . '...'; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			
			<!-- Vista desktop: Tabla -->
			<div class="d-none d-md-block">
				<div class="table-responsive">
					<table class="table table-hover mb-0" id="reportsTable">
						<thead>
							<tr>
								<th>Cliente</th>
								<th>Asesor</th>
								<th>Fecha</th>
								<th>Tiempo Respuesta</th>
								<th>Calificación</th>
								<th>Calif. Tiempo</th>
								<th>Tono</th>
								<th>Protocolo</th>
								<th>Resumen</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($reports as $report): ?>
							<tr>
								<td>
									<div class="d-flex flex-column">
										<strong class="text-primary"><?php echo htmlspecialchars($report['Report']['cliente_nombre']); ?></strong>
										<small class="text-muted">ID: 
											<a target="_blank" href="<?php echo Configure::read('URL_CHAT_CONVERSATION') . $report['Report']['conversation_id']; ?>" target="_blank">
												<?php echo $report['Report']['conversation_id']; ?>
											</a>
										</small>
									</div>
								</td>
								<td>
									<div class="d-flex align-items-center">
										<div>
											<strong><?php echo htmlspecialchars($report['Report']['asesor_nombre']); ?></strong>
											<br><small class="text-muted"><?php echo htmlspecialchars($report['User']['role']); ?></small>
										</div>
									</div>
								</td>
								<td>
									<div class="d-flex flex-column">
										<strong><?php echo date('d/m/Y', strtotime($report['Report']['fecha'])); ?></strong>
										<small class="text-muted"><?php echo date('H:i', strtotime($report['Report']['inicio_conversacion'])); ?></small>
									</div>
								</td>
								<td>
									<?php
									$tiempoFormateado = formatearTiempoRespuesta($report['Report']['tiempo_promedio_respuesta_segundos']);
									?>
									<span class="text-white badge bg-<?php echo $tiempoFormateado['color']; ?>"><?php echo $tiempoFormateado['texto']; ?></span>
								</td>
								<td>
									<?php
									$calificacion = intval($report['Report']['calificacion_general_10']);
									$color = $calificacion >= 8 ? 'success' : ($calificacion >= 6 ? 'warning' : 'danger');
									?>
									<span class="text-white badge bg-<?php echo $color; ?>"><?php echo $calificacion; ?>/10</span>
								</td>
								<td>
									<?php
									$calificacionTiempo = calificarTiempoRespuesta($report['Report']['tiempo_promedio_respuesta_segundos']);
									$colorTiempo = $calificacionTiempo >= 8 ? 'success' : ($calificacionTiempo >= 6 ? 'info' : ($calificacionTiempo >= 4 ? 'warning' : 'danger'));
									?>
									<span class="text-white badge bg-<?php echo $colorTiempo; ?>"><?php echo $calificacionTiempo; ?>/10</span>
								</td>
								<td>
									<?php
									$tono = $report['Report']['tono_asesor'];
									$tonoColor = 'secondary';
									if(strpos($tono, 'Cordial y proactivo') !== false) $tonoColor = 'success';
									elseif(strpos($tono, 'Cordial') !== false) $tonoColor = 'info';
									elseif(strpos($tono, 'Automático') !== false) $tonoColor = 'warning';
									elseif(strpos($tono, 'Repetitivo') !== false) $tonoColor = 'danger';
									?>
									<?php $tono = $this->Text->truncate(strip_tags($tono), 80,array('ellipsis' => '.','exact' => false)); ?>
									<span class="text-white badge bg-<?php echo $tonoColor; ?>"><?php echo htmlspecialchars($tono); ?></span>
								</td>
								<td>
									<?php
									$protocolo = $report['Report']['cumplimiento_protocolo'];
									$protocoloColor = 'secondary';
									if($protocolo == 'Completo') $protocoloColor = 'success';
									elseif($protocolo == 'Parcial') $protocoloColor = 'warning';
									elseif(strpos($protocolo, 'No se cumplió') !== false) $protocoloColor = 'danger';
									?>
									<?php $protocolo = $this->Text->truncate(strip_tags($protocolo), 30,array('ellipsis' => '.','exact' => false)); ?>
									<span class="text-white badge bg-<?php echo $protocoloColor; ?>"><?php echo htmlspecialchars($protocolo); ?></span>
								</td>
								<td>
									<div class="text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($report["Report"]["analisis_completo"]); ?>">
										<?php echo htmlspecialchars(substr($report["Report"]["analisis_completo"], 0, 100)) . '...'; ?>
									</div>
								</td>
								<td>
									<button class="btn btn-sm btn-outline-primary" onclick="verDetalle(<?php echo $report['Report']['id']; ?>)">
										<i class="vtc fa fa-eye"></i>
									</button>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal mejorado -->
<div class="modal fade" id="detalleModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-lg4" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title" id="exampleModalScrollableTitle"><i class="vtc fa fa-comments me-2"></i>Detalle de Conversación</h5>
			</div>
			<div class="modal-body" id="detalleContent">
				<!-- Contenido dinámico -->
			</div>
		</div>
	</div>
</div>

<!-- Modal de Calificación de Tiempo -->
<div class="modal fade" id="calificacionTiempoModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-warning text-dark">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title"><i class="vtc fa fa-star me-2"></i>Sistema de Calificación por Tiempo de Respuesta</h5>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-sm table-bordered">
						<thead class="table-warning">
							<tr>
								<th>Puntos</th>
								<th>Rango de Tiempo</th>
								<th>Nivel de Calidad</th>
								<th>Descripción</th>
							</tr>
						</thead>
						<tbody>
							<tr class="table-success">
								<td><strong>10</strong></td>
								<td>0-30 segundos</td>
								<td>Excelente</td>
								<td>Respuesta inmediata - Servicio excepcional</td>
							</tr>
							<tr class="table-info">
								<td><strong>9</strong></td>
								<td>30-60 segundos</td>
								<td>Muy bueno</td>
								<td>Respuesta rápida - Servicio muy bueno</td>
							</tr>
							<tr class="table-info">
								<td><strong>8</strong></td>
								<td>1-2 minutos</td>
								<td>Bueno</td>
								<td>Respuesta oportuna - Servicio bueno</td>
							</tr>
							<tr class="table-warning">
								<td><strong>7</strong></td>
								<td>2-5 minutos</td>
								<td>Regular</td>
								<td>Respuesta aceptable - Servicio regular</td>
							</tr>
							<tr class="table-warning">
								<td><strong>6</strong></td>
								<td>5-10 minutos</td>
								<td>Aceptable</td>
								<td>Respuesta tardía - Servicio aceptable</td>
							</tr>
							<tr class="table-warning">
								<td><strong>5</strong></td>
								<td>10-30 minutos</td>
								<td>Lento</td>
								<td>Respuesta muy tardía - Servicio lento</td>
							</tr>
							<tr class="table-danger">
								<td><strong>4</strong></td>
								<td>30-60 minutos</td>
								<td>Muy lento</td>
								<td>Respuesta inaceptable - Servicio muy lento</td>
							</tr>
							<tr class="table-danger">
								<td><strong>3</strong></td>
								<td>1-2 horas</td>
								<td>Extremadamente lento</td>
								<td>Muy mal servicio - Pérdida de confianza</td>
							</tr>
							<tr class="table-danger">
								<td><strong>2</strong></td>
								<td>2-24 horas</td>
								<td>Inaceptable</td>
								<td>Servicio crítico - Cliente insatisfecho</td>
							</tr>
							<tr class="table-danger">
								<td><strong>1</strong></td>
								<td>Más de 24 horas</td>
								<td>Crítico</td>
								<td>Servicio fallido - Pérdida de cliente</td>
							</tr>
							<tr class="table-secondary">
								<td><strong>0</strong></td>
								<td>No contactó</td>
								<td>Sin contacto</td>
								<td>Sin contacto - Pérdida total de cliente</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="alert alert-info mt-3">
					<strong><i class="vtc fa fa-info-circle me-2"></i>Nota:</strong>
					Esta calificación se promedia con la calificación de satisfacción del cliente para obtener la puntuación final del asesor.
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()), array('block' => 'jqueryApp'));
?>

<?php echo $this->element("picker"); ?>

<?php $this->start("AppScript") ?>

<!-- Estilos CSS personalizados para móviles -->
<style>
	/* Estilos para vista móvil */
	@media (max-width: 767.98px) {
		.card-body {
			padding: 1rem;
		}
		
		.badge {
			font-size: 0.75rem;
			padding: 0.375rem 0.75rem;
		}
		
		.btn-sm {
			padding: 0.25rem 0.5rem;
			font-size: 0.875rem;
		}
		
		/* Mejorar legibilidad en móviles */
		.text-truncate {
			max-width: 100%;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		
		/* Espaciado optimizado para móviles */
		.row.g-2 > [class*="col-"] {
			padding: 0.5rem;
		}
		
		/* Cards con mejor separación */
		.card.mb-3 {
			margin-bottom: 1rem !important;
		}
		
		/* Badges más compactos */
		.badge {
			display: inline-block;
			margin-bottom: 0.25rem;
		}
		
		/* Mejor contraste para texto pequeño */
		.text-muted {
			color: #6c757d !important;
		}
		
		/* Optimizar modal para móviles */
		.modal-dialog.modal-xl {
			margin: 0.5rem;
			max-width: calc(100% - 1rem);
		}
		
		/* Scroll horizontal suave */
		.table-responsive {
			-webkit-overflow-scrolling: touch;
		}
	}
	
	/* Animaciones suaves */
	.card {
		transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
	}
	
	.card:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
	}
	
	/* Mejorar accesibilidad */
	.btn:focus {
		box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
	}
	
	/* Estilos para tooltips en móviles */
	[title] {
		cursor: help;
	}
	
	/* Optimizar DataTable para móviles */
	@media (max-width: 767.98px) {
		.dataTables_wrapper .dataTables_length,
		.dataTables_wrapper .dataTables_filter,
		.dataTables_wrapper .dataTables_info,
		.dataTables_wrapper .dataTables_paginate {
			text-align: center;
			margin-bottom: 0.5rem;
		}
		
		.dataTables_wrapper .dataTables_paginate .paginate_button {
			padding: 0.25rem 0.5rem;
			margin: 0 0.125rem;
		}
	}
</style>

<!-- Scripts para las gráficas -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>

	const DATA_REPORTS = <?php echo json_encode($reports); ?>

	// Datos para las gráficas
	<?php
	// Calificaciones
	$calificaciones = array_fill(1, 10, 0);
	foreach($reports as $report) {
		$cal = intval($report['Report']['calificacion_general_10']);
		if($cal >= 1 && $cal <= 10) {
			$calificaciones[$cal]++;
		}
	}

	// Tonos
	$tonos = array();
	foreach($reports as $report) {
		$tono = $report['Report']['tono_asesor'];
		if(!isset($tonos[$tono])) $tonos[$tono] = 0;
		$tonos[$tono]++;
	}

	// Tiempo de respuesta por rangos
	$tiemposRespuesta = array('No contactó' => 0, '0-30s' => 0, '30-60s' => 0, '1-5min' => 0, '5-30min' => 0, '30min-2h' => 0, '2h-1d' => 0, '1d+' => 0);
	foreach($reports as $report) {
		$tiempo = intval($report['Report']['tiempo_promedio_respuesta_segundos']);
		if($tiempo == 0 || empty($tiempo)) {
			$tiemposRespuesta['No contactó']++;
		} elseif($tiempo <= 30) {
			$tiemposRespuesta['0-30s']++;
		} elseif($tiempo <= 60) {
			$tiemposRespuesta['30-60s']++;
		} elseif($tiempo <= 300) {
			$tiemposRespuesta['1-5min']++;
		} elseif($tiempo <= 1800) {
			$tiemposRespuesta['5-30min']++;
		} elseif($tiempo <= 7200) {
			$tiemposRespuesta['30min-2h']++;
		} elseif($tiempo <= 86400) {
			$tiemposRespuesta['2h-1d']++;
		} else {
			$tiemposRespuesta['1d+']++;
		}
	}

	// Preparar datos para gráfica de tonos
	$tonosData = array();
	foreach($tonos as $tono => $count) {
		$tonosData[] = array('name' => $tono, 'y' => $count);
	}

	// Preparar datos para gráfica de rendimiento por asesor
	$nombresAsesores = array();
	$puntuacionesAsesores = array();
	$coloresAsesores = array();
	foreach($asesoresRanking as $asesor) {
		$nombresAsesores[] = addslashes($asesor['nombre']);
		$puntuacionesAsesores[] = $asesor['puntuacion'];
		// Color rojo si no contactó, azul si contactó
		$coloresAsesores[] = $asesor['no_contacto'] ? '#dc3545' : '#007bff';
	}
	?>

	// Configuración común para las gráficas
	const chartOptions = {
		responsive: true,
		maintainAspectRatio: false,
		plugins: {
			legend: {
				position: 'bottom',
				labels: {
					padding: 20,
					usePointStyle: true,
					font: {
						size: window.innerWidth < 768 ? 10 : 12
					}
				}
			}
		}
	};

	// Gráfica de calificaciones
	Highcharts.chart('calificacionesChart', {
		chart: {
			type: 'pie',
			height: 300
		},
		title: {
			text: 'Distribución de Calificaciones por Nivel de Satisfacción',
			style: {
				fontSize: '16px',
				fontWeight: 'bold'
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				innerSize: '60%',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f}%'
				},
				showInLegend: true
			}
		},
		colors: ['#dc3545', '#fd7e14', '#ffc107', '#28a745', '#20c997', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14', '#6c757d'],
		series: [{
			name: 'Conversaciones',
			colorByPoint: true,
			data: [
				{name: 'Calificación 1', y: <?php echo $calificaciones[1]; ?>},
				{name: 'Calificación 2', y: <?php echo $calificaciones[2]; ?>},
				{name: 'Calificación 3', y: <?php echo $calificaciones[3]; ?>},
				{name: 'Calificación 4', y: <?php echo $calificaciones[4]; ?>},
				{name: 'Calificación 5', y: <?php echo $calificaciones[5]; ?>},
				{name: 'Calificación 6', y: <?php echo $calificaciones[6]; ?>},
				{name: 'Calificación 7', y: <?php echo $calificaciones[7]; ?>},
				{name: 'Calificación 8', y: <?php echo $calificaciones[8]; ?>},
				{name: 'Calificación 9', y: <?php echo $calificaciones[9]; ?>},
				{name: 'Calificación 10', y: <?php echo $calificaciones[10]; ?>}
			]
		}],
		tooltip: {
			formatter: function() {
				return '<b>' + this.point.name + '</b><br/>' +
					   '<b>Conversaciones:</b> ' + this.y + '<br/>' +
					   '<b>Porcentaje:</b> ' + this.percentage.toFixed(1) + '%';
			}
		}
	});

	// Gráfica de tonos
	Highcharts.chart('tonoChart', {
		chart: {
			type: 'pie',
			height: 300
		},
		title: {
			text: 'Distribución de Conversaciones por Tono de Atención del Asesor',
			style: {
				fontSize: '16px',
				fontWeight: 'bold'
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f}%'
				},
				showInLegend: true
			}
		},
		colors: ['#28a745', '#17a2b8', '#ffc107', '#fd7e14', '#dc3545', '#6f42c1', '#e83e8c', '#20c997', '#6c757d', '#007bff'],
		series: [{
			name: 'Conversaciones',
			colorByPoint: true,
			data: <?php echo json_encode($tonosData); ?>
		}],
		tooltip: {
			formatter: function() {
				return '<b>' + this.point.name + '</b><br/>' +
					   '<b>Conversaciones:</b> ' + this.y + '<br/>' +
					   '<b>Porcentaje:</b> ' + this.percentage.toFixed(1) + '%';
			}
		}
	});

	// Gráfica de tiempo de respuesta
	Highcharts.chart('tiempoRespuestaChart', {
		chart: {
			type: 'column',
			height: 300
		},
		title: {
			text: 'Distribución de Conversaciones por Tiempo Promedio de Respuesta del Asesor',
			style: {
				fontSize: '16px',
				fontWeight: 'bold'
			}
		},
		xAxis: {
			categories: ['No contactó', '0-30s', '30-60s', '1-5min', '5-30min', '30min-2h', '2h-1d', '1d+'],
			title: {
				text: 'Rangos de Tiempo'
			}
		},
		yAxis: {
			title: {
				text: 'Número de Conversaciones'
			},
			min: 0
		},
		plotOptions: {
			column: {
				colorByPoint: true
			}
		},
		colors: ['#dc3545', '#28a745', '#17a2b8', '#ffc107', '#fd7e14', '#6f42c1', '#e83e8c', '#6c757d'],
		series: [{
			name: 'Conversaciones',
			data: [<?php echo implode(',', array_values($tiemposRespuesta)); ?>],
			dataLabels: {
				enabled: true,
				format: '{y}'
			}
		}],
		tooltip: {
			formatter: function() {
				return '<b>' + this.x + '</b><br/>' +
					   '<b>Conversaciones:</b> ' + this.y;
			}
		}
	});

	// Gráfica de rendimiento por asesor
	Highcharts.chart('rendimientoAsesoresChart', {
		chart: {
			type: 'column',
			height: 300
		},
		title: {
			text: 'Ranking de Rendimiento General por Asesor',
			style: {
				fontSize: '16px',
				fontWeight: 'bold'
			}
		},
		xAxis: {
			categories: [<?php echo "'" . implode("','", $nombresAsesores) . "'"; ?>],
			title: {
				text: 'Asesores'
			}
		},
		yAxis: {
			title: {
				text: 'Puntuación'
			},
			min: 0,
			max: 10
		},
		plotOptions: {
			column: {
				colorByPoint: true
			}
		},
		colors: [<?php 
			$colores = array();
			$paletaColores = array('#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#e83e8c', '#17a2b8', '#6c757d');
			foreach($asesoresRanking as $index => $asesor) {
				if ($asesor['no_contacto']) {
					$colores[] = "'#dc3545'"; // Rojo para no contactó
				} else {
					$colores[] = "'" . $paletaColores[$index % count($paletaColores)] . "'";
				}
			}
			echo implode(',', $colores);
		?>],
		series: [{
			name: 'Puntuación',
			data: [<?php 
				$datosGrafica = array();
				foreach($asesoresRanking as $asesor) {
					$datosGrafica[] = $asesor['puntuacion'];
				}
				echo implode(',', $datosGrafica);
			?>],
			dataLabels: {
				enabled: true,
				format: '{y:.1f}'
			}
		}],
		tooltip: {
			formatter: function() {
				var asesor = this.x;
				var noContacto = false;
				<?php foreach($asesoresRanking as $asesor): ?>
				if (asesor === '<?php echo addslashes($asesor['nombre']); ?>') {
					noContacto = <?php echo $asesor['no_contacto'] ? 'true' : 'false'; ?>;
				}
				<?php endforeach; ?>
				return '<b>' + asesor + (noContacto ? ' (No contactó)' : '') + '</b><br/>' +
					   '<b>Puntuación:</b> ' + this.y.toFixed(1);
			}
		}
	});

	// Función para ver detalles mejorada
	function verDetalle(id) {
		const reporte = DATA_REPORTS.find(report => report.Report.id == id);

		if (!reporte) {
			$('#detalleContent').html('<div class="alert alert-danger">No se encontró el reporte solicitado.</div>');
			$('#detalleModal').modal('show');
			return;
		}

		const report = reporte.Report;
		const user = reporte.User;

		// Calcular duración formateada
		const duracion = parseInt(report.duracion_total_minutos);
		const horas = Math.floor(duracion / 60);
		const minutos = duracion % 60;
		const duracionFormateada = horas > 0 ? `${horas}h ${minutos}m` : `${minutos}m`;

		// Calcular tiempo promedio de respuesta
		const tiempoPromedio = parseInt(report.tiempo_promedio_respuesta_segundos);
		let tiempoRespuestaFormateado = 'No contactó';
		let tiempoRespuestaColor = 'danger';
		
		if (tiempoPromedio > 0) {
			if (tiempoPromedio < 60) {
				tiempoRespuestaFormateado = tiempoPromedio + 's';
				tiempoRespuestaColor = 'success';
			} else if (tiempoPromedio < 3600) {
				const minutos = Math.floor(tiempoPromedio / 60);
				const segundos = tiempoPromedio % 60;
				tiempoRespuestaFormateado = minutos + 'm';
				if (segundos > 0) {
					tiempoRespuestaFormateado += ' ' + segundos + 's';
				}
				tiempoRespuestaColor = tiempoPromedio <= 60 ? 'success' : (tiempoPromedio <= 300 ? 'info' : (tiempoPromedio <= 1800 ? 'warning' : 'danger'));
			} else if (tiempoPromedio < 86400) {
				const horas = Math.floor(tiempoPromedio / 3600);
				const minutos = Math.floor((tiempoPromedio % 3600) / 60);
				tiempoRespuestaFormateado = horas + 'h';
				if (minutos > 0) {
					tiempoRespuestaFormateado += ' ' + minutos + 'm';
				}
				tiempoRespuestaColor = 'danger';
			} else {
				const dias = Math.floor(tiempoPromedio / 86400);
				const horas = Math.floor((tiempoPromedio % 86400) / 3600);
				tiempoRespuestaFormateado = dias + 'd';
				if (horas > 0) {
					tiempoRespuestaFormateado += ' ' + horas + 'h';
				}
				tiempoRespuestaColor = 'danger';
			}
		}

		// Calcular calificación de tiempo de respuesta
		let calificacionTiempo = 0;
		if (tiempoPromedio > 0) {
			if (tiempoPromedio <= 30) calificacionTiempo = 10;
			else if (tiempoPromedio <= 60) calificacionTiempo = 9;
			else if (tiempoPromedio <= 120) calificacionTiempo = 8;
			else if (tiempoPromedio <= 300) calificacionTiempo = 7;
			else if (tiempoPromedio <= 600) calificacionTiempo = 6;
			else if (tiempoPromedio <= 1800) calificacionTiempo = 5;
			else if (tiempoPromedio <= 3600) calificacionTiempo = 4;
			else if (tiempoPromedio <= 7200) calificacionTiempo = 3;
			else if (tiempoPromedio <= 86400) calificacionTiempo = 2;
			else calificacionTiempo = 1;
		}
		const calificacionTiempoColor = calificacionTiempo >= 8 ? 'success' : (calificacionTiempo >= 6 ? 'info' : (calificacionTiempo >= 4 ? 'warning' : 'danger'));

		// Calcular promedio de tiempo de respuesta del asesor
		let promedioTiempoAsesor = 0;
		let totalCalificacionTiempo = 0;
		let conversacionesAsesor = 0;
		DATA_REPORTS.forEach(r => {
			if (r.Report.user_id == report.user_id) {
				const tiempoR = parseInt(r.Report.tiempo_promedio_respuesta_segundos);
				let califR = 0;
				if (tiempoR > 0) {
					if (tiempoR <= 30) califR = 10;
					else if (tiempoR <= 60) califR = 9;
					else if (tiempoR <= 120) califR = 8;
					else if (tiempoR <= 300) califR = 7;
					else if (tiempoR <= 600) califR = 6;
					else if (tiempoR <= 1800) califR = 5;
					else if (tiempoR <= 3600) califR = 4;
					else if (tiempoR <= 7200) califR = 3;
					else if (tiempoR <= 86400) califR = 2;
					else califR = 1;
				}
				totalCalificacionTiempo += califR;
				conversacionesAsesor++;
			}
		});
		promedioTiempoAsesor = conversacionesAsesor > 0 ? Math.round((totalCalificacionTiempo / conversacionesAsesor) * 10) / 10 : 0;
		const promedioTiempoColor = promedioTiempoAsesor >= 8 ? 'success' : (promedioTiempoAsesor >= 6 ? 'info' : (promedioTiempoAsesor >= 4 ? 'warning' : 'danger'));

		// Determinar colores para badges
		const calificacion = parseInt(report.calificacion_general_10);
		const calificacionColor = calificacion >= 8 ? 'success' : (calificacion >= 6 ? 'warning' : 'danger');

		const tono = report.tono_asesor;
		let tonoColor = 'secondary';
		if(tono.includes('Cordial y proactivo')) tonoColor = 'success';
		else if(tono.includes('Cordial')) tonoColor = 'info';
		else if(tono.includes('Automático')) tonoColor = 'warning';
		else if(tono.includes('Repetitivo')) tonoColor = 'danger';

		const protocolo = report.cumplimiento_protocolo;
		let protocoloColor = 'secondary';
		if(protocolo === 'Completo') protocoloColor = 'success';
		else if(protocolo === 'Parcial') protocoloColor = 'warning';
		else if(protocolo.includes('No se cumplió')) protocoloColor = 'danger';

		// Parsear arrays JSON
		let oportunidadesMejora = [];
		let fortalezasDetectadas = [];

		try {
			oportunidadesMejora = JSON.parse(report.oportunidades_mejora);
		} catch(e) {
			oportunidadesMejora = [report.oportunidades_mejora];
		}

		try {
			fortalezasDetectadas = JSON.parse(report.fortalezas_detectadas);
		} catch(e) {
			fortalezasDetectadas = [report.fortalezas_detectadas];
		}

		// Crear el HTML del modal mejorado
		const modalContent = `
			<div class="row">
				<!-- Información principal -->
				<div class="col-lg-12">
					<div class="card mb-3 border-0 shadow-sm">
						<div class="card-header bg-primary text-white">
							<h5 class="mb-0"><i class="vtc fa fa-comments me-2"></i>Información General de la Conversación</h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<p><strong>Cliente:</strong> ${report.cliente_nombre}</p>
									<p><strong>Asesor:</strong> ${report.asesor_nombre}</p>
									<p><strong>ID Conversación:</strong> 
										<a target="_blank" href="<?php echo Configure::read('URL_CHAT_CONVERSATION'); ?>${report.conversation_id}" class="text-primary">
											${report.conversation_id}
										</a>
									</p>
									<p><strong>Fecha de Conversación:</strong> ${report.fecha}</p>
									<p><strong>Calificación del Cliente:</strong></p>
									<span class="text-white badge bg-${calificacionColor} mb-2">${report.calificacion_general_10}/10</span>
									<p><strong>Cumplimiento del Protocolo:</strong></p>
									<span class="text-white badge bg-${protocoloColor} mb-2">${report.cumplimiento_protocolo}</span>
								</div>
								<div class="col-md-6">
									<p><strong>Hora de Inicio:</strong> ${new Date(report.inicio_conversacion).toLocaleString('es-ES')}</p>
									<p><strong>Hora de Finalización:</strong> ${new Date(report.fin_conversacion).toLocaleString('es-ES')}</p>
									<p><strong>Duración Total:</strong> <span class="text-white badge bg-info">${duracionFormateada}</span></p>
									<p><strong>Tiempo Promedio de Respuesta del Asesor:</strong> <span class="text-white badge bg-${tiempoRespuestaColor}">${tiempoRespuestaFormateado}</span></p>
									<p><strong>Calificación de Tiempo de Respuesta:</strong> <span class="text-white badge bg-${calificacionTiempoColor}">${calificacionTiempo}/10</span></p>
									<p><strong>Promedio de Calificación de Tiempo del Asesor:</strong> <span class="text-white badge bg-${promedioTiempoColor}">${promedioTiempoAsesor}/10</span></p>
									<p><strong>Tono de Atención del Asesor:</strong></p>
									<span class="text-white badge bg-${tonoColor} mb-2">${report.tono_asesor}</span>
								</div>
							</div>
						</div>
					</div>

					<div class="card mb-3 border-0 shadow-sm">
						<div class="card-header bg-info text-white">
							<h6 class="mb-0"><i class="vtc fa fa-chart-bar me-2"></i>Métricas Detalladas de la Conversación</h6>
						</div>
						<div class="card-body">
							<div class="row text-center">
								<div class="col-3">
									<div class="h4 text-primary">${report.cantidad_mensajes_cliente}</div>
									<small class="text-muted">Mensajes del Cliente</small>
								</div>
								<div class="col-3">
									<div class="h4 text-success">${report.cantidad_mensajes_asesor}</div>
									<small class="text-muted">Mensajes del Asesor</small>
								</div>
								<div class="col-3">
									<div class="h4 text-warning">${report.calificacion_general_10}/10</div>
									<small class="text-muted">Calificación de Satisfacción</small>
								</div>
								<div class="col-3">
									<div class="h4 text-${tiempoRespuestaColor}">${tiempoRespuestaFormateado}</div>
									<small class="text-muted">Tiempo de Respuesta Promedio</small>
								</div>
							</div>
							<div class="row text-center mt-3">
								<div class="col-4">
									<div class="h5 text-${calificacionTiempoColor}">${calificacionTiempo}/10</div>
									<small class="text-muted">Calificación de Tiempo</small>
								</div>
								<div class="col-4">
									<div class="h5 text-${promedioTiempoColor}">${promedioTiempoAsesor}/10</div>
									<small class="text-muted">Promedio del Asesor</small>
								</div>
								<div class="col-4">
									<div class="h5 text-info">${duracionFormateada}</div>
									<small class="text-muted">Duración Total</small>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Análisis completo -->
			<div class="row">
				<div class="col-12">
					<div class="card mb-3 border-0 shadow-sm">
						<div class="card-header bg-success text-white">
							<h6 class="mb-0"><i class="vtc fa fa-search me-2"></i>Análisis Completo de la Conversación</h6>
						</div>
						<div class="card-body">
							<p class="text-justify">${report.analisis_completo}</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Razones -->
			<div class="row">
				<div class="col-md-4">
					<div class="card mb-3 border-0 shadow-sm">
						<div class="card-header bg-info text-white">
							<h6 class="mb-0"><i class="vtc fa fa-question-circle me-2"></i>Razón Principal de No Cotización</h6>
						</div>
						<div class="card-body">
							<p class="small">${report.razon_de_no_cotizacion || 'No aplica'}</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card mb-3 border-0 shadow-sm">
						<div class="card-header bg-warning text-dark">
							<h6 class="mb-0"><i class="vtc fa fa-times-circle me-2"></i>Razón Principal de No Venta</h6>
						</div>
						<div class="card-body">
							<p class="small">${report.razon_de_no_venta || 'No aplica'}</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card mb-3 border-0 shadow-sm">
						<div class="card-header bg-success text-white">
							<h6 class="mb-0"><i class="vtc fa fa-check-circle me-2"></i>Razón Principal de Venta</h6>
						</div>
						<div class="card-body">
							<p class="small">${report.razon_de_venta || 'No aplica'}</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Fortalezas y Oportunidades -->
			<div class="row">
				<div class="col-md-6">
					<div class="card mb-3 border-0 shadow-sm">
						<div class="card-header bg-success text-white">
							<h6 class="mb-0"><i class="vtc fa fa-thumbs-up me-2"></i>Fortalezas Detectadas en la Atención</h6>
						</div>
						<div class="card-body">
							${fortalezasDetectadas.length > 0 ?
								`<ul class="list-unstyled">
									${fortalezasDetectadas.map(fortaleza => `<li><i class="vtc fa fa-check text-success me-2"></i>${fortaleza}</li>`).join('')}
								</ul>` :
								'<p class="text-muted">No se detectaron fortalezas específicas en esta conversación.</p>'
							}
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card mb-3 border-0 shadow-sm">
						<div class="card-header bg-warning text-dark">
							<h6 class="mb-0"><i class="vtc fa fa-lightbulb me-2"></i>Oportunidades de Mejora Identificadas</h6>
						</div>
						<div class="card-body">
							${oportunidadesMejora.length > 0 ?
								`<ul class="list-unstyled">
									${oportunidadesMejora.map(oportunidad => `<li><i class="vtc fa fa-arrow-right text-warning me-2"></i>${oportunidad}</li>`).join('')}
								</ul>` :
								'<p class="text-muted">No se identificaron oportunidades de mejora específicas.</p>'
							}
						</div>
					</div>
				</div>
			</div>
		`;

		$('#detalleContent').html(modalContent);
		$('#detalleModal').modal('show');
	}

	// Función para exportar reporte
	function exportarReporte() {
		// Implementar exportación a Excel/PDF
		alert('Función de exportación en desarrollo');
	}

	// Inicializar DataTable solo para desktop
	$(document).ready(function() {
		// Solo inicializar DataTable en desktop
		if (window.innerWidth >= 768) {
			$('#reportsTable').DataTable({
				"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
				},
				"pageLength": 25,
				"order": [[2, "desc"]],
				"responsive": true,
				"dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
					   '<"row"<"col-sm-12"tr>>' +
					   '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
				"columnDefs": [
					{
						"targets": [8, 9], // Protocolo, Resumen y Acciones
						"orderable": false
					}
				]
			});
		}
		
		// Agregar tooltips para móviles
		$('[title]').each(function() {
			$(this).attr('data-bs-toggle', 'tooltip');
			$(this).attr('data-bs-placement', 'top');
		});
		
		// Inicializar tooltips si Bootstrap está disponible
		if (typeof bootstrap !== 'undefined') {
			var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
			var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl);
			});
		}
	});

	// Evento para buscar por fecha
	$('.btn_buscar').click(function() {
		const fecha = $('#fecha_consulta').val();
		if(fecha) {
			window.location.href = window.location.pathname + '?fecha_consulta=' + fecha;
		}
	});

	// Evento para buscar con Enter
	$('#fecha_consulta').keypress(function(e) {
		if(e.which == 13) {
			$('.btn_buscar').click();
		}
	});

	// Detectar cambios de tamaño de ventana
	$(window).resize(function() {
		// Redibujar gráficas si es necesario
		if (window.innerWidth < 768) {
			// Ajustar configuración para móviles
			$('.chart-container').css('height', '200px');
		} else {
			$('.chart-container').css('height', 'auto');
		}
	});

	// Mejorar experiencia táctil en móviles
	if ('ontouchstart' in window) {
		// Agregar delay para evitar clics accidentales
		$('.btn').on('touchstart', function() {
			$(this).addClass('active');
		}).on('touchend', function() {
			$(this).removeClass('active');
		});
		
		// Mejorar scroll en móviles
		$('.table-responsive').css({
			'-webkit-overflow-scrolling': 'touch',
			'overflow-x': 'auto'
		});
	}
</script>

<?php $this->end(); ?>


<style>
	svg {
		width: 100% !important;
		height: 100% !important;
		display: block;
	}
</style>