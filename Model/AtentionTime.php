<?php
App::uses('AppModel', 'Model');

class AtentionTime extends AppModel {

	public $belongsTo = array(
		'ProspectiveUser' => array(
			'className' => 'ProspectiveUser',
			'foreignKey' => 'prospective_users_id'
		)
	);

	public function get_data($id){
		$this->recursive 	= -1;
		$conditions			= array('AtentionTime.id' => $id);
		return $this->find('first',compact('conditions'));
	}

	public function get_data_id($flujo_id){
		$this->recursive 	= -1;
		$fields 			= array('AtentionTime.id');
		$conditions			= array('AtentionTime.prospective_users_id' => $flujo_id);
		$datos 				= $this->find('first',compact('conditions'));
		if (isset($datos['AtentionTime']['id'])) {
			$id = $datos['AtentionTime']['id'];
		} else {
			$id = 0;
		}
		return $id;
	}

	public function get_atentions(){
		$this->recursive 	= -1;
		return $this->find('all');
	}

	public function get_atentions_contactado_day(){
		$this->recursive 	= -1;
		$conditions = array('AtentionTime.contactado_date' => date("Y-m-d"));
		return $this->find('all',compact('conditions'));
	}

	public function get_atentions_cotizado_day(){
		$this->recursive 	= -1;
		$conditions = array('AtentionTime.cotizado_date' => date("Y-m-d"));
		return $this->find('all',compact('conditions'));
	}

	public function get_atentions_id(){
		$datos 					= $this->find('all');
		if (count($datos) > 0) {
			$result 			= Set::extract($datos, '{n}.AtentionTime');
			$resultFinal		= array();
			$i = 0;
			foreach ($result as $value) {
				$resultFinal[$i] = $value['prospective_users_id'];
				$i++;
			}
		} else {
			$resultFinal 		= 0;
		}
		return $resultFinal;
	}

	public function get_atentions_user($user_id){
		$order				= array('AtentionTime.id' => 'desc');
		$fields 			= array('AtentionTime.*');
		$conditions 		= array('ProspectiveUser.user_id' => $user_id);
		return $this->find('all',compact('conditions','order','fields'));
	}

	public function get_atentions_user_rango_fechas($user_id,$fecha_inicio,$fecha_fin){
		$order				= array('AtentionTime.id' => 'desc');
		$fields 			= array('AtentionTime.*');
		$conditions 		= array('ProspectiveUser.user_id' => $user_id,'AtentionTime.created >=' => $fecha_inicio,'AtentionTime.modified <=' => $fecha_fin);
		return $this->find('all',compact('conditions','order','fields'));
	}
	
	public function get_atentions_flujo($flujo_id){
		$conditions 		= array('AtentionTime.prospective_users_id' => $flujo_id);
		return $this->find('all',compact('conditions'));
	}

	public function get_atentions_user_flujo($user_id,$flujo_id){
		$order				= array('AtentionTime.id' => 'desc');
		$fields 			= array('AtentionTime.*');
		$conditions 		= array('ProspectiveUser.user_id' => $user_id,'AtentionTime.prospective_users_id' => $flujo_id);
		return $this->find('first',compact('conditions','order','fields'));
	}

	public function find_state_asignado_flujo_atentido($flujo_id){
		$fields 				= array('AtentionTime.asignado_date','AtentionTime.asignado_time');
		$conditions 			= array('AtentionTime.prospective_users_id' => $flujo_id);
		return $this->find('first',compact('conditions','fields'));
	}

	public function find_state_asignado_flujo_contactado($flujo_id){
		$fields 				= array('AtentionTime.contactado_date','AtentionTime.contactado_time');
		$conditions 			= array('AtentionTime.prospective_users_id' => $flujo_id);
		return $this->find('first',compact('conditions','fields'));
	}

	public function time_demora_contactado($fecha_inicio,$fecha_fin,$user_id, $total = true){
		$fields 				= array('AtentionTime.id','AtentionTime.limit_contactado_date','AtentionTime.limit_contactado_time','AtentionTime.contactado_date','AtentionTime.contactado_time');
		$conditions 			= array('AtentionTime.limit_contactado_date >=' => $fecha_inicio,'AtentionTime.limit_contactado_date <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id,"ProspectiveUser.state_flow != " => Configure::read("variables.control_flujo.flujo_cancelado"),"DATE(ProspectiveUser.created) >="=> $fecha_inicio, "DATE(ProspectiveUser.created) <="=>$fecha_fin );
		$datos 					= $this->find('all',compact('conditions','fields'));

		$horas = 2;
		$horas_demoras 			= 0;
		$totalFlujos 			= 0;
		foreach ($datos as $key => $value) {
			$limiteDate = $value["AtentionTime"]["limit_contactado_date"]." ".$value["AtentionTime"]["limit_contactado_time"];
			$contactDate = $value["AtentionTime"]["contactado_date"]." ".$value["AtentionTime"]["contactado_time"];
			if(strtotime($limiteDate) >= strtotime($contactDate) ){
				continue;
			}else{
				$period = $this->getDatesFromRange($limiteDate,$contactDate,"d-m-Y");
				foreach ($period as $date) {
					$horas_demoras+= $this->getHoursByDay($limiteDate,$date,$contactDate,$fecha_fin);
			    }
			    $totalFlujos++;
			}
		}
		return $total ?  round($horas_demoras) : [ "total" => round((($horas_demoras)/24)*8), "totalFlujos" => $totalFlujos ] ;
	}


	private function getHoursByDay($limitDateTime, $dateValidate, $contactDateTime, $fechaFinal ){
		$hours = 0;
		$dateLimit 		= date("d-m-Y",strtotime($limitDateTime));
		$dateContact 	= empty($contactDateTime) || is_null($contactDateTime) ? $fechaFinal : date("d-m-Y",strtotime($contactDateTime));

		if($dateLimit === $dateValidate){
			$dateEnd = $dateLimit. " ".Configure::read("variables.hora_fin_trabajo").":00:00";
			
			$dateInitial 	= new DateTime($limitDateTime);
			$dateFinal 		= new DateTime($dateEnd);
			$diff			= $dateFinal->diff($dateInitial);

			$hours = $diff->d + ($diff->i / 60);

		}else{

			if($dateContact === $dateValidate){

				$dateIni = $dateContact. " ".Configure::read("variables.hora_inicial_trabajo").":00:00";
				
				$dateInitial 	= new DateTime($dateIni);
				$dateFinal 		= new DateTime($contactDateTime);
				$diff			= $dateFinal->diff($dateInitial);

				$hours = $diff->d + ($diff->i / 60);

			}else{
				$day 		= date("D",strtotime($dateValidate));
				$dateini 	= $dateContact. " ".Configure::read("variables.hora_inicial_trabajo").":00:00";
				if(!in_array($dateValidate, Configure::read("variables.diasFestivos") ) && !in_array($day, Configure::read("variables.dias_no_habiles")) ){
					$hours = 9;
				}
			}

		}
		return $hours;
	}

	private function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
 
	    $days = array(); 

	    $interval = new DateInterval('P1D'); 
	  
	    $realEnd = new DateTime($end); 
	    $realEnd->add($interval); 
	  
	    $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
	  
	    foreach($period as $date) {                  
	        $days[] = $date->format($format);  
	    } 
	  
	    return $days; 
	} 

	public function time_demora_cotizado($fecha_inicio,$fecha_fin,$user_id, $total = true){
		$fields 				= array('AtentionTime.id','AtentionTime.limit_cotizado_date','AtentionTime.limit_cotizado_time','AtentionTime.cotizado_date','AtentionTime.cotizado_time');
		$conditions 			= array('AtentionTime.limit_cotizado_date >=' => $fecha_inicio,'AtentionTime.limit_cotizado_date <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id,"ProspectiveUser.state_flow != " => Configure::read("variables.control_flujo.flujo_cancelado"),"DATE(ProspectiveUser.created) >="=> $fecha_inicio, "DATE(ProspectiveUser.created) <="=>$fecha_fin );
		$datos 					= $this->find('all',compact('conditions','fields'));
		$horas_demoras 			= 0;
		$totalFlujos 			= 0;
		foreach ($datos as $key => $value) {
			$limiteDate = $value["AtentionTime"]["limit_cotizado_date"]." ".$value["AtentionTime"]["limit_cotizado_time"];
			$contactDate = $value["AtentionTime"]["cotizado_date"]." ".$value["AtentionTime"]["cotizado_time"];

			if($contactDate == "0000-00-00 00:00:00")
			{
				$contactDate =  $fecha_fin." ".Configure::read("variables.hora_fin_trabajo").":00:00";
			}	

			if(strtotime($limiteDate) >= strtotime($contactDate) ){
				continue;
			}else{
				$period = $this->getDatesFromRange($limiteDate,$contactDate,"d-m-Y");
				foreach ($period as $date) {
					$horas_demoras+= $this->getHoursByDay($limiteDate,$date,$contactDate,$fecha_fin);
			    }
			    $totalFlujos++;
			}
		}
		return $total ?  round($horas_demoras) : [ "total" => round((($horas_demoras)/24)*8) , "totalFlujos" => $totalFlujos ] ;
	}

	public function count_flujos_demorados(){
		$fields 			= array('DISTINCT(AtentionTime.prospective_users_id)');
		$conditions			= array('OR' => array(
							            'AtentionTime.demorado_contactado' => '1',
							            'AtentionTime.demorado_cotizado' => '1'
							        )
								);
		return $this->find('count',compact('conditions','fields'));
	}

	public function flujos_demorados(){
		$fields 			= array('DISTINCT(AtentionTime.prospective_users_id)','AtentionTime.*','ProspectiveUser.*');
		$conditions			= array('OR' => array(
							            'AtentionTime.demorado_contactado' => '1',
							            'AtentionTime.demorado_cotizado' => '1'
							        )
								);
		return $this->find('all',compact('conditions','fields'));
	}

	public function time_demora_contactado_rango_fechas_user($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$fields 				= array('AtentionTime.id','AtentionTime.prospective_users_id','AtentionTime.limit_contactado_date','AtentionTime.limit_contactado_time','AtentionTime.contactado_date','AtentionTime.contactado_time');
		$conditions 			= array('AtentionTime.limit_contactado_date >=' => $fecha_inicio, 'AtentionTime.limit_contactado_date <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id,"ProspectiveUser.state_flow != " => Configure::read("variables.control_flujo.flujo_cancelado"));
		$datos 					= $this->find('all',compact('fields','conditions'));
		$horas_demoras 			= 0;
		foreach ($datos as $key => $value) {
			for ($i=0; $i < count($lista_ids); $i++) {
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $value['AtentionTime']['prospective_users_id']) {
					$limiteDate = $value["AtentionTime"]["limit_contactado_date"]." ".$value["AtentionTime"]["limit_contactado_time"];
					$contactDate = $value["AtentionTime"]["contactado_date"]." ".$value["AtentionTime"]["contactado_time"];
					if(strtotime($limiteDate) >= strtotime($contactDate) ){
						continue;
					}else{
						$period = $this->getDatesFromRange($limiteDate,$contactDate,"d-m-Y");
						foreach ($period as $date) {
							$horas_demoras+= $this->getHoursByDay($limiteDate,$date,$contactDate,$fecha_fin);
					    }
					}
				}
			}
			
		}
		return round($horas_demoras);
	}

	public function time_demora_cotizado_rango_fechas_user($fecha_inicio,$fecha_fin,$user_id,$lista_ids){
		$fields 				= array('AtentionTime.id','AtentionTime.prospective_users_id','AtentionTime.limit_cotizado_date','AtentionTime.limit_cotizado_time','AtentionTime.cotizado_date','AtentionTime.cotizado_time');
		$conditions 			= array('AtentionTime.limit_cotizado_date >=' => $fecha_inicio, 'AtentionTime.limit_cotizado_date <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id,"ProspectiveUser.state_flow != " => Configure::read("variables.control_flujo.flujo_cancelado"));
		$datos 					= $this->find('all',compact('fields','conditions'));
		$horas_demoras 			= 0;
		foreach ($datos as $value) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $value['AtentionTime']['prospective_users_id']) {
					$limiteDate = $value["AtentionTime"]["limit_cotizado_date"]." ".$value["AtentionTime"]["limit_cotizado_time"];
					$contactDate = $value["AtentionTime"]["cotizado_date"]." ".$value["AtentionTime"]["cotizado_time"];
					if($contactDate == "0000-00-00 00:00:00")
					{
						$contactDate =  $fecha_fin." ".Configure::read("variables.hora_fin_trabajo").":00:00";;
					}
					if(strtotime($limiteDate) >= strtotime($contactDate) ){
						continue;
					}else{
						$period = $this->getDatesFromRange($limiteDate,$contactDate,"d-m-Y");
						foreach ($period as $date) {
							$horas_demoras+= $this->getHoursByDay($limiteDate,$date,$contactDate,$fecha_fin);
					    }
					}
				}
			}
		}
		return round($horas_demoras);
	}

	

	public function time_demora_contactado_all(){
		$fields 				= array('AtentionTime.id','AtentionTime.limit_contactado_date','AtentionTime.limit_contactado_time','AtentionTime.contactado_date','AtentionTime.contactado_time');
		$conditions 			= array("ProspectiveUser.state_flow != " => Configure::read("variables.control_flujo.flujo_cancelado"));
		$datos 					= $this->find('all',compact('fields','conditions'));
		$horas_demoras 			= 0;
		foreach ($datos as $key => $value) {
			for ($i=0; $i < count($lista_ids); $i++) {
				if ($lista_ids[$i]['FlowStage']['prospective_users_id'] == $value['AtentionTime']['prospective_users_id']) {
					$limiteDate = $value["AtentionTime"]["limit_contactado_date"]." ".$value["AtentionTime"]["limit_contactado_time"];
					$contactDate = $value["AtentionTime"]["contactado_date"]." ".$value["AtentionTime"]["contactado_time"];
					if(strtotime($limiteDate) >= strtotime($contactDate) ){
						continue;
					}else{
						$period = $this->getDatesFromRange($limiteDate,$contactDate,"d-m-Y");
						foreach ($period as $date) {
							$horas_demoras+= $this->getHoursByDay($limiteDate,$date,$contactDate,date("d-m-Y"));
					    }
					}
				}
			}
			
		}
		return round($horas_demoras);
	}

	public function time_demora_cotizado_all(){
		$fields 				= array('AtentionTime.id','AtentionTime.limit_cotizado_date','AtentionTime.limit_cotizado_time','AtentionTime.cotizado_date','AtentionTime.cotizado_time');
		$conditions 			= array("ProspectiveUser.state_flow != " => Configure::read("variables.control_flujo.flujo_cancelado"));
		$datos 					= $this->find('all',compact('fields'));
		$horas_demoras 			= 0;
		foreach ($datos as $key => $value) {
			$limiteDate = $value["AtentionTime"]["limit_cotizado_date"]." ".$value["AtentionTime"]["limit_cotizado_time"];
			$contactDate = $value["AtentionTime"]["cotizado_date"]." ".$value["AtentionTime"]["cotizado_time"];
			if($contactDate == "0000-00-00 00:00:00")
			{
				$contactDate =  $fecha_fin." ".Configure::read("variables.hora_fin_trabajo").":00:00";;
			}
			if(strtotime($limiteDate) >= strtotime($contactDate) ){
				continue;
			}else{
				$period = $this->getDatesFromRange($limiteDate,$contactDate,"d-m-Y");
				foreach ($period as $date) {
					$horas_demoras+= $this->getHoursByDay($limiteDate,$date,$contactDate,date("d-m-Y"));
			    }
			}
		}
		return round($horas_demoras);
	}

	public function restaHoraAtendidoLimite($horaLimit, $horaFin,$fechaLimit,$fechaFin,$fila_id) {
		$fechaActual 		= false;
		$mayor 				= false;
		if ($horaFin == '00:00:00') {
			$fechaFin 		= date("Y-m-d");
			$horaFin 		= date("H:i:s");
			$fechaActual 	= true;
		}
		if (strtotime($fechaFin.' '.$horaFin) > strtotime($fechaLimit.' '.$horaLimit)) {
			$mayor = true;
		}
		if ($mayor) {
			$total_seconds 				= strtotime($fechaFin.' '.$horaFin) - strtotime($fechaLimit.' '.$horaLimit);
			$horas             			= floor ( $total_seconds / 3600 );
			if ($fechaActual) {
				$horasNoTrabajadas 		= $this->horasNoTrabajadas(strtotime($fechaFin.' '.$horaFin),strtotime($fechaLimit.' '.$horaLimit));
				$finSemanaHora 			= $this->finSemanaHorasTranscurridosRangoFechas(strtotime($fechaFin.' '.$horaFin),strtotime($fechaLimit.' '.$horaLimit));
				return ($horas - $horasNoTrabajadas) - $finSemanaHora;
			} else {
				return $horas;
			}
		} else {
			return 0;
		}
	}

	public function validate_date_atention_cotizado(){
		$fields 				= array('AtentionTime.id','AtentionTime.limit_cotizado_date','AtentionTime.limit_cotizado_time','AtentionTime.cotizado_date','AtentionTime.cotizado_time');
		$conditions 			= array('AtentionTime.demorado_cotizado' => '0','AtentionTime.limit_cotizado_time !=' => '0000-00-00');
		$datos 					= $this->find('all',compact('fields','conditions'));
		foreach ($datos as $value) {
			$this->validate_time_user($value['AtentionTime']['limit_cotizado_time'],$value['AtentionTime']['cotizado_time'],$value['AtentionTime']['limit_cotizado_date'],$value['AtentionTime']['cotizado_date'],$value['AtentionTime']['id'],'cotizado');
		}
	}

	public function validate_date_atention_contactado(){
		$fields 				= array('AtentionTime.id','AtentionTime.limit_contactado_date','AtentionTime.limit_contactado_time','AtentionTime.contactado_date','AtentionTime.contactado_time');
		$conditions 			= array('AtentionTime.demorado_contactado' => '0','AtentionTime.limit_contactado_date !=' => '0000-00-00');
		$datos 					= $this->find('all',compact('fields','conditions'));
		foreach ($datos as $value) {
			$this->validate_time_user($value['AtentionTime']['limit_contactado_time'],$value['AtentionTime']['contactado_time'],$value['AtentionTime']['limit_contactado_date'],$value['AtentionTime']['contactado_date'],$value['AtentionTime']['id'],'contatado');
		}
	}

	public function validate_time_user($horaLimit, $horaFin,$fechaLimit,$fechaFin,$fila_id,$type){
		if ($horaFin == '00:00:00') {
			$fechaFin 		= date("Y-m-d");
			$horaFin 		= date("H:i:s");
		}
		if (strtotime($fechaFin.' '.$horaFin) > strtotime($fechaLimit.' '.$horaLimit)) {
			$mayor = true;
		} else {
			$mayor = false;
		}
		if ($mayor) {
			if ($type == 'cotizado') {
				$this->update_row_demora_cotizado($fila_id);
			} else {
				$this->update_row_demora_contactado($fila_id);
			}
		}
		return true;
	}

	public function update_row_demora_cotizado($atention_id){
		$this->updateAll(
	    	array('AtentionTime.demorado_cotizado' => '1'), array('AtentionTime.id' => $atention_id)
	    );
	}
	
	public function update_row_demora_contactado($atention_id){
		$this->updateAll(
	    	array('AtentionTime.demorado_contactado' => '1'), array('AtentionTime.id' => $atention_id)
	    );
	}









	// REPORTE ADVISER
	public function time_demora_contactado_rango_fechas($fecha_inicio,$fecha_fin){
		$fields 				= array('AtentionTime.id','AtentionTime.limit_contactado_date','AtentionTime.limit_contactado_time','AtentionTime.contactado_date','AtentionTime.contactado_time');
		$conditions 			= array('AtentionTime.limit_contactado_date >=' => $fecha_inicio, 'AtentionTime.limit_contactado_date <=' => $fecha_fin,"ProspectiveUser.state_flow != " => Configure::read("variables.control_flujo.flujo_cancelado"));

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}
		$datos 					= $this->find('all',compact('fields','conditions'));
		$horas_demoras 			= 0;
		foreach ($datos as $key => $value) {
			$limiteDate = $value["AtentionTime"]["limit_contactado_date"]." ".$value["AtentionTime"]["limit_contactado_time"];
			$contactDate = $value["AtentionTime"]["contactado_date"]." ".$value["AtentionTime"]["contactado_time"];
			if($contactDate == "0000-00-00 00:00:00")
			{
				$contactDate =  $fecha_fin." ".Configure::read("variables.hora_fin_trabajo").":00:00";;
			}
			if(strtotime($limiteDate) >= strtotime($contactDate) ){
				continue;
			}else{
				$period = $this->getDatesFromRange($limiteDate,$contactDate,"d-m-Y");
				foreach ($period as $date) {
					$horas_demoras+= $this->getHoursByDay($limiteDate,$date,$contactDate,$fecha_fin);
			    }
			}
			
		}
		return round($horas_demoras);
	}

	public function time_demora_cotizado_rango_fechas($fecha_inicio,$fecha_fin, $numberTotal = null){
		$fields 				= array('AtentionTime.id','AtentionTime.limit_cotizado_date','AtentionTime.limit_cotizado_time','AtentionTime.cotizado_date','AtentionTime.cotizado_time');
		$conditions 			= array('AtentionTime.limit_cotizado_date >=' => $fecha_inicio, 'AtentionTime.limit_cotizado_date <=' => $fecha_fin,"ProspectiveUser.state_flow != " => Configure::read("variables.control_flujo.flujo_cancelado"));

		if (AuthComponent::user("role") == "Asesor Externo") {
			$conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
		}
		$datos 					= $this->find('all',compact('fields','conditions'));
		$horas_demoras 			= 0;
		$totalNum 				= 0;
		foreach ($datos as $key => $value) {
			$limiteDate = $value["AtentionTime"]["limit_cotizado_date"]." ".$value["AtentionTime"]["limit_cotizado_time"];
			$contactDate = $value["AtentionTime"]["cotizado_date"]." ".$value["AtentionTime"]["cotizado_time"];
			if($contactDate == "0000-00-00 00:00:00")
			{
				$contactDate =  $fecha_fin." ".Configure::read("variables.hora_fin_trabajo").":00:00";;
			}
			if(strtotime($limiteDate) >= strtotime($contactDate) ){
				continue;
			}else{
				$period = $this->getDatesFromRange($limiteDate,$contactDate,"d-m-Y");
				foreach ($period as $date) {
					$horas_demoras+= $this->getHoursByDay($limiteDate,$date,$contactDate,$fecha_fin);
			    }
			    $totalNum;
			}
		}
		return is_null($numberTotal) ? round($horas_demoras): $totalNum;
	}

	public function time_demora_contactado_rango_fechas_user_fatal($fecha_inicio,$fecha_fin,$lista_ids){
		$fields 				= array('AtentionTime.id','AtentionTime.prospective_users_id','AtentionTime.limit_contactado_date','AtentionTime.limit_contactado_time','AtentionTime.contactado_date','AtentionTime.contactado_time');
		$conditions 			= array('AtentionTime.limit_contactado_date >=' => $fecha_inicio, 'AtentionTime.limit_contactado_date <=' => $fecha_fin,"ProspectiveUser.state_flow != " => Configure::read("variables.control_flujo.flujo_cancelado"));
		$datos 					= $this->find('all',compact('fields','conditions'));
		$total 			= array();
		foreach ($datos as $key => $value) {
			for ($i=0; $i < count($lista_ids); $i++) {
				if ($lista_ids[$i]['ProspectiveUser']['id'] == $value['AtentionTime']['prospective_users_id']) {
					$limiteDate = $value["AtentionTime"]["limit_contactado_date"]." ".$value["AtentionTime"]["limit_contactado_time"];
					$contactDate = $value["AtentionTime"]["contactado_date"]." ".$value["AtentionTime"]["contactado_time"];
					if(strtotime($limiteDate) >= strtotime($contactDate) ){
						continue;
					}else{
						$total[] = $value['AtentionTime']['prospective_users_id'];
					}
				}
			}
			
		}
		return $total;
	}

	public function time_demora_cotizado_rango_fechas_fatal($fecha_inicio,$fecha_fin,$lista_ids){
		$fields 				= array('AtentionTime.id','AtentionTime.prospective_users_id','AtentionTime.limit_cotizado_date','AtentionTime.limit_cotizado_time','AtentionTime.cotizado_date','AtentionTime.cotizado_time');
		$conditions 			= array('AtentionTime.limit_cotizado_date >=' => $fecha_inicio, 'AtentionTime.limit_cotizado_date <=' => $fecha_fin,"ProspectiveUser.state_flow != " => Configure::read("variables.control_flujo.flujo_cancelado"));
		$datos 					= $this->find('all',compact('fields','conditions'));
		$total 			= array();
		foreach ($datos as $value) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['ProspectiveUser']['id'] == $value['AtentionTime']['prospective_users_id']) {
					$limiteDate = $value["AtentionTime"]["limit_cotizado_date"]." ".$value["AtentionTime"]["limit_cotizado_time"];
					$contactDate = $value["AtentionTime"]["cotizado_date"]." ".$value["AtentionTime"]["cotizado_time"];
					if($contactDate == "0000-00-00 00:00:00")
					{
						$contactDate =  $fecha_fin." ".Configure::read("variables.hora_fin_trabajo").":00:00";;
					}
					if(strtotime($limiteDate) >= strtotime($contactDate) ){
						continue;
					}else{
						$total[] = $value['AtentionTime']['prospective_users_id'];
					}
				}
			}
		}
		return $total;
	}

	public function count_flujos_demorados_rango_fechas($fecha_inicio,$fecha_fin,$lista_ids){
		$dataContactado = $this->time_demora_contactado_rango_fechas_user_fatal($fecha_inicio,$fecha_fin,$lista_ids);
		$dataCotizado 	= $this->time_demora_cotizado_rango_fechas_fatal($fecha_inicio,$fecha_fin,$lista_ids);
		$todos 		  	= array_unique(array_merge($dataContactado,$dataCotizado));
		return 			count($todos);
		/****Se cambia método de conteo***/
		$fields 			= array('DISTINCT(AtentionTime.prospective_users_id)');
		$conditions			= array('AtentionTime.created >=' => $fecha_inicio,'AtentionTime.modified <=' => $fecha_fin
										,'OR' => array(
								            'AtentionTime.demorado_contactado' => '1',
								            'AtentionTime.demorado_cotizado' => '1'
								        )
									);
		$datos 				= $this->find('all',compact('conditions','fields'));
		$contadorFinal 		= 0;
		foreach ($datos as $valueRegistros) {
			for ($i=0; $i < count($lista_ids); $i++) { 
				if ($lista_ids[$i]['ProspectiveUser']['id'] == $valueRegistros['AtentionTime']['prospective_users_id']) {
					$contadorFinal++;
				}
			}
		}
		return $contadorFinal;
	}

	public function all_flujos_demorados_rango_fechas($fecha_inicio,$fecha_fin,$lista_ids){
		$dataContactado = $this->time_demora_contactado_rango_fechas_user_fatal($fecha_inicio,$fecha_fin,$lista_ids);
		$dataCotizado 	= $this->time_demora_cotizado_rango_fechas_fatal($fecha_inicio,$fecha_fin,$lista_ids);
		$todos 		  	= array_unique(array_merge($dataContactado,$dataCotizado));
		$fields 			= array('DISTINCT(AtentionTime.prospective_users_id)','AtentionTime.*','ProspectiveUser.*');
		$conditions			= array('AtentionTime.prospective_users_id' => $todos );
		$datos 				= $this->find('all',compact('conditions','fields'));

		return $datos;
	}









}