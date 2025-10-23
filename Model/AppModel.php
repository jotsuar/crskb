<?php
App::uses('Model', 'Model');

class AppModel extends Model {

    public $API = "https://kebcousa.com/htdocs/api/index.php/";

    public function renameFile($field, $currentName, $data, $options = array()) {
        $rand        = time();
        $rand_v2     = uniqid();
        $rand_v3     = strtotime(date('Y-m-d H:i:s'));
        $nameContent = explode(".", $currentName);
        $ext         = end($nameContent);
        if(count($nameContent) == 1){
            $ext = "jpg";
        }
        if($this->alias == "Document"){ 
           $this->data[$this->alias]["type"] = $ext;
           $this->data["type"] = $ext;
        } 
        $newName     = $this->alias."_{$rand}_{$rand_v2}_{$rand_v3}.{$ext}";
        return $newName;
    }
    
	public function finSemanaHorasTranscurridosRangoFechas($segundosFechaBusquedaInicial,$segundosFechaBusquedaFin){
        $segundosTranscurridos                      = $segundosFechaBusquedaFin - $segundosFechaBusquedaInicial;
        $diasTranscurridos                          = floor( $segundosTranscurridos / 86400);
        $diaSemanaEmpieza                           = date('N',$segundosFechaBusquedaInicial);
        $totaldias                                  = $diaSemanaEmpieza + $diasTranscurridos;
        $finSemanas                                 = intval( $totaldias/5) *2 ;
        $diaSabado                                  = $totaldias % 5;
        if ($diaSabado==6) {
            $finSemanas++;
        }
        return $finSemanas * 18;
    }

    public function horasNoTrabajadas($segundosFecha,$segundosLimite){
        $total_seconds      = $segundosFecha - $segundosLimite;
        $horas              = floor ( $total_seconds / 3600 );
        // $minutes            = ( ( $total_seconds / 60 ) % 60 );
        // $seconds            = ( $total_seconds % 60 );
        $numero_dias        = $horas / 24;
        return ((int) Configure::read('variables.horas_no_trabajadas') * $numero_dias);
    }

    /**
        * @author Diego Morales <dlmorales096@gmail.com>
        * @date(21-11-2019)
        * @description Metodo para buscar el proximo ID ya que aveces hay problemas con cakephp para insertar un nuevo registro
        * @return Proximo ID de la tabla(modelo)
    */
    public function new_row_model(){
        return $this->find('count');
    }
    
}
