<?php foreach ($datos_busqueda as $model): ?>
	<?php foreach ($model as $value): ?>
		<?php 
			$datosM 			= $this->Utilities->find_seeker_data_model($value);
			$nombreModelo 		= $this->Utilities->find_nombre_modelo($value);
			$id_model 			= $this->Utilities->id_model_array($value, $model);
			$datos 				= $this->Utilities->valid_position_array($datosM,key($value));
		?>
		<?php 
			$array_model_not_position_name = array('ProspectiveUser','TechnicalService');
			if (in_array(key($value), $array_model_not_position_name)) {
		?>
			<?php 
				if (key($value) == 'ProspectiveUser') { 
					if ($datos['type'] == 0) {
						$nombreItem 	= 'Flujo de control';
						$action 		= 'index'.'?q='.$id_model;
						$model 			= $this->Utilities->model_key_array($value);
					} else {
						$nombreItem 	= 'Flujo de servicio técnico';
						$action 		= 'flujos'.'?q='.$id_model;
						$model 			= 'TechnicalServices';
					}
				} else {
					$nombreItem 		= 'Servicio técnico';
					$action 			= 'view/'.$this->Utilities->encryptString($id_model);
					$model 				= 'TechnicalServices';
				}
			?>
		<?php } else { ?>
			<?php
				$action 			= 'view/'.$this->Utilities->encryptString($id_model);
				$nombreItem 		= $datos['name'];
				$model 				= $this->Utilities->model_key_array($value);
			?>
		<?php } ?>
		<a href="<?php echo $this->Html->url(array('controller'=>$model,'action'=>$action),true) ?>" class="list-group-item intro-results" target="_blank">
			<p><?php echo $nombreItem ?> <span class="tag-search <?php echo $nombreModelo ?>"><?php echo $nombreModelo ?></span></p>
			<i class="fa fa-angle-right alignright"></i>
		</a>
	<?php endforeach ?>
<?php endforeach ?>