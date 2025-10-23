<div class="col-md-12">
	<div class="row">
		<div class="col-md-12 allflujo">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<h2>Flujo de Negocios</h2>
						</div>
					</div>
				</div>	
				<div class="row">			
					<nav class="navbar navbar-expand-lg navbar-light bg-filter">
						<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
							<b>
								<?php 
									echo $this->Html->link('Todos', array('action' => 'index'),array('class' => '')); 
								?>
							</b>
						</div>

						<span class="validonot">
							<?php
								echo $this->Html->link('No válidos <i class="fa fa-window-close vtc"></i>', 
								array('action'=>'index','?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_no_valido'))),array('escape' => false));
							?>
						</span>

						<span class="cancelx">
							<?php
								echo $this->Html->link('Cancelados <i class="fa fa-lg fa-times"></i>', 
								array('action'=>'index','?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_cancelado'))),array('escape' => false));
							?>
						</span>

						<span class="finalc">
							<?php
								echo $this->Html->link('Finalizados <i class="fa fa-lg fa-check"></i>', 
								array('action'=>'index','?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_finalizado'))),array('escape' => false));
							?>
						</span>
						
						<div class="dropdown text-right">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
								Filtrar etapas
							</button>
							<div class="dropdown-menu datastates">
								<?php
									$todo 			= 'Flujos en proceso '.$count_todo_habilitado;
									echo $this->Html->link($todo, array(),array('class' => 'dropdown-item'));

									$asignado 		= 'Asignado '.$count_asignado;
									echo $this->Html->link($asignado, array('?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_asignado'))),array('class' => 'dropdown-item'));

									$contactado 	= 'Contactado '.$count_contactado;
									echo $this->Html->link($contactado, array('?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_contactado'))),array('class' => 'dropdown-item'));

									$cotizado 		= 'Cotizado '.$count_cotizado;
									echo $this->Html->link($cotizado, array('?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_cotizado'))),array('class' => 'dropdown-item'));

									$negociado 		= 'Negociado '.$count_negociado;
									echo $this->Html->link($negociado, array('?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_negociado'))),array('class' => 'dropdown-item'));

									$pagado 		= 'Pagado '.$count_pagado;
									echo $this->Html->link($pagado, array('?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_pagado'))),array('class' => 'dropdown-item'));

									$despachado 	= 'Despachado '.$count_despachado;
									echo $this->Html->link($despachado, array('?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_despachado'))),array('class' => 'dropdown-item'));
								?>
								<div class="dropdown-divider"></div>
								<?php
									$cancelado = 'Cancelado '.$count_cancelado;
									echo $this->Html->link($cancelado, array('action'=>'index','?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_cancelado'))),array('class' => 'dropdown-item'));

									$terminado = 'Terminados '.$count_terminados;
									echo $this->Html->link($terminado, array('action'=>'index','?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_finalizado'))),array('class' => 'dropdown-item'));
								?>
							</div>
						</div>
						|
						<div class="dropdown text-right">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
								Filtrar asesores
							</button>
							<div class="dropdown-menu datastates">
								<?php foreach ($usuariosAsesores as $valueUsuarios):
									echo $this->Html->link($valueUsuarios['User']['name'], array('?' => array('filterAsesores' => $valueUsuarios['User']['id'])),array('class' => 'dropdown-item'));
								endforeach ?>
							</div>
						</div>
					</nav>
				</div>
			</div>
			
			<div class="prospectiveUsers index row">
				<div class="col-md-12 text-center">
					<h2>No existe ningún flujo de negocio por la busqueda realizada</h2>	
				</div>
			</div>
		</div>
	</div>	
</div>

<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>