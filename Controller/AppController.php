<?php
require_once ROOT.'/app/Vendor/sendgrid/vendor/autoload.php';
set_time_limit(0);


App::uses('Controller', 'Controller');
App::uses('CakePdf', 'CakePdf.Pdf');
App::uses('HttpSocket', 'Network/Http');


class AppController extends Controller {

    public $helpers                            = array('Utilities');
	public $components                         = array(
                                                    'Session',
                                                    'Auth' => array(
                                                       'authenticate' => array(
                                            	            'Form' => array(
                                            	                'fields' => array('username' => 'email')
                                            	            )
                                            			)
                                                    )
                                                );
    public $uses                                = ["Config"];

    public $API = "https://kebcousa.com/htdocs/api/index.php/";

    public $usuarios_sistema    = [];
    public $usuarios_names      = [];
    public $usuarios_search     = [];

    public $meses2 = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09"=> "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"     ];

    public $meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9"=> "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"   ];

    public function updateCostCol($productId){
        $this->loadModel("Config");
        $this->loadModel("Product");

        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $factorImport   = $config["Config"]["factorUSA"];

        $typeProduct    = $this->Product->field("type",["id"=>$productId]);

        if ($typeProduct == 1) {
            $usdCost = $this->Product->field("purchase_price_usd",["id"=>$productId]);
            $this->Product->updateAll( ["Product.purchase_price_cop" => $trmActual*$factorImport*$usdCost ], ["Product.id" => $productId ] );
        }

    }


    public function gestMailAuto($flowData){

        $this->loadModel("ProspectiveUser");
        $this->loadModel("Quotation");
        $this->loadModel("FlowStage");

        $id                 = $flowData["ProspectiveUser"]["id"];

        $id_etapa_cotizado  = $this->FlowStage->id_latest_regystri_state_cotizado($id);
        $datosFlowstage     = $this->FlowStage->get_data($id_etapa_cotizado);

        $quotation          = empty($datosFlowstage) ? null : $datosFlowstage['FlowStage']['document'];

        if(!is_null($quotation)){
            
            try {
                
                //////////////////////////////////////////////////////////////////////////////////////////////


                $quotationData      = $this->Quotation->findById($quotation);
                $quotationProducts  = $this->Quotation->QuotationsProduct->findAllByQuotationId($quotation);
                $customer_data      = $this->getDataCustomer($id);


                $infoFlow = $flowData;
                $flowData = $flowData["ProspectiveUser"];

                $type     = "prorroga";

                if ( 
                    strtotime($flowData["date_alert"]) <= strtotime(date("Y-m-d")) && $flowData["alert_one"] == 0 && is_null($flowData["date_final_alert"]) && $flowData["alert_two"] == 0 && $flowData["prorroga_one"] == 0 && $flowData["prorroga_two"] == 0 && $flowData['notified'] == 1 
                ) {
                    $type = "alert";
                }elseif ( 
                    strtotime($flowData["date_final_alert"]) <= strtotime(date("Y-m-d")) && $flowData["alert_one"] == 1 && !is_null($flowData["date_alert"]) && $flowData["alert_two"] == 0 && $flowData["prorroga_one"] == 0 && $flowData["prorroga_two"] == 0 && $flowData['notified'] == 1
                ) {
                    $type = "alert_two";
                }elseif ( 
                    strtotime($flowData["date_prorroga"]) <= strtotime(date("Y-m-d")) && $flowData["alert_one"] == 1 && !is_null($flowData["date_alert"]) && $flowData["alert_two"] == 1 && !is_null($flowData["date_final_alert"]) && $flowData["prorroga_one"] == 0 && $flowData["prorroga_two"] == 0 && $flowData['notified'] == 1
                ) {
                    $type = "prorroga";
                }

                $datosNota['ProgresNote']['description']                = "Gestión realizada vía correo electrónico automaticamente el día: ".date("Y-m-d H:i:s");
                $datosNota['ProgresNote']['etapa']                      = "Cotizado";
                $datosNota['ProgresNote']['prospective_users_id']       = $id;
                $datosNota['ProgresNote']['user_id']                    = AuthComponent::user('id');

                if ($type == "alert") {
                    $infoFlow["ProspectiveUser"]["alert_one"]        = 1;
                    $infoFlow["ProspectiveUser"]["date_final_alert"] = date("Y-m-d",strtotime("+".Configure::read("DIAS_NOTIFY_TWO")." day"));
                    $infoFlow["ProspectiveUser"]["notified"]         = 0;
                }elseif ($type == "alert_two") {
                    $infoFlow["ProspectiveUser"]["alert_two"]     = 1;
                    $infoFlow["ProspectiveUser"]["date_prorroga"] = date("Y-m-d",strtotime("+".Configure::read("DIAS_PRORROGA_ONE")." day"));
                    $infoFlow["ProspectiveUser"]["notified"]      = 0;
                }elseif ($type == "prorroga") {
                    $infoFlow["ProspectiveUser"]["prorroga_one"]        = 0;
                    $infoFlow["ProspectiveUser"]["notified"]            = 0;
                    $infoFlow["ProspectiveUser"]["date_prorroga"] = date("Y-m-d",strtotime("+30 day"));
                }

                $infoFlow["ProspectiveUser"]["deadline_notified"] = null;
            
                $this->ProspectiveUser->save($infoFlow["ProspectiveUser"]);
                $this->ProspectiveUser->ProgresNote->save($datosNota);

                $datos_asesor       = $this->ProspectiveUser->User->findById($infoFlow["ProspectiveUser"]["user_id"]);
                $rutaURL            = 'Quotations/view/'.$this->encryptString($quotationData['Quotation']['id']);

                $msgToSend      = '<p> Buen día '.$customer_data["name"].', desde KEBCO SAS esperamos que se encuentre muy bien.</p><p>Le escribo con relación a la cotización en asunto, enviada el pasado '.$this->date_castellano2( date("Y-m-d", strtotime($flowData["ProspectiveUser"]["date_quotation"])) ).', en donde le cotizamos con el asunto '.$quotationData["Quotation"]["name"].'</p><p>Quisiéramos saber si ha podido revisar la cotización la cual adjunto nuevamente, si tiene alguna duda al respecto o si ha tomado alguna decisión.</p><p>Estamos atentos para seguir asesorándolo</p><p>Feliz día.</p> Mi nombre es '.$datos_asesor["User"]["name"];

                if(!empty($quotationProducts)){
                    $msgToSend.= "el/Los producto(s) cotizados fueron <br>";
                    foreach ($quotationProducts as $key => $value) {
                        if(in_array($value["Product"]["part_number"], [ 'S-003','SER-AE']) && $quotationData["Quotation"]["show_ship"] == 0){
                            continue;
                        }
                        $msgToSend.= "Referencia: ". $value["Product"]["part_number"]." Nombre: ".$value["Product"]["name"]." Precio: ".$value["QuotationsProduct"]["price"]." ".$value["QuotationsProduct"]["currency"]. "  Cantidad: ".$value["QuotationsProduct"]["quantity"]." <br>";
                    }
                }

                $contenido          = null;

                $respuestaIntencion = $this->callOpenAI($msgToSend);

                //     // Extraer la intención del modelo
                $contenido = isset($respuestaIntencion['choices'][0]['message']['content']) ? $respuestaIntencion['choices'][0]['message']['content'] : null ;

                if(!is_null($contenido)){
                    $options  = array(
                        'to'        => $customer_data["email"],
                        'template'  => 'quote_sent_gest',
                        'cc'        => AuthComponent::user("email"),
                        'subject'   => 'Seguimiento a la cotización '.$quotationData["Quotation"]["codigo"].' | KEBCO AlmacenDelPintor.com',
                        'vars'      => array('contenido' => $contenido,'ruta'=>$rutaURL,"quotationID" =>$quotationData['Quotation']['id'] )
                    );

                    if(!empty($datos_asesor["User"]["password_email"])){
                        $email      = $datos_asesor["User"]["email"] == "jotsuar@gmail.com" ? "sistemas@almacendelpintor.com" : $datos_asesor["User"]["email"];
                        $password   = str_replace("@@KEBCO@@", "", base64_decode($datos_asesor["User"]["password_email"]) );
                        $config     = array("username" => $email, "password" => $password);

                        $this->sendMail($options, null, $config);
                    }else{
                        $this->sendMail($options);
                    }
                }           


                //////////////////////////////////////////////////////////////////////////////////////////////


            } catch (Exception $e) {
                
            }

        }

    }

    public function callOpenAI($mensajeUsuario, $maxTokens = 1500) {


        $promptIntencion = "Se tiene el siguiente mensaje a enviar ': \"$mensajeUsuario\"'. Puedes mejorarlo para tratar de incentivar la compra por parte del cliente o una respuesta. La respuesta debe ser html para enviar por email";


        $openaiApiKey = '';    

        $url = 'https://api.openai.com/v1/responses';

        $data = [
            'model' => 'gpt-5-nano',
            'input' => [
                ['role' => 'system', 'content' => 'Eres un asesor de ventas de KEBCO SAS. Debes generar un seguimiento de cotización en HTML para enviar a un cliente y persuadirlo a concretar la compra.  

                La respuesta debe contener únicamente el contenido en HTML, sin incluir etiquetas `<html>`, `<head>` o `<body>`.  

                El mensaje debe ser conciso y mantener los datos del cliente, la fecha y el asunto de la cotización sin cambios.  

                Debes enfatizar los beneficios del producto o servicio cotizado, destacar la disponibilidad limitada o ventajas exclusivas, e incluir una llamada a la acción clara.  

                Evita frases genéricas como "tu nombre" o "tu correo". Personaliza el contenido para que el cliente sienta que es un seguimiento a la cotización directo y exclusivo.  

                Informale al cliente que ofrecemos:
                Beneficio de garantia del mejor precio del mercado, el mejor servicio de posventa en las ciudades de medellin y bogotá, también contamos con la posibilidad de desplazarnos en sitio si lo requiere. Tenémos una política de mantener una garantia del mejor precio igualando otras cotizaciones mejores. 

                Se cordial con la persona trata de no sonar muy técnico. 

                Organiza bien la información de los productos con el email para que suene lo más profesional posible.

                No incluir enlaces mailto o botones con etiqueta <a>

                '],
                ['role' => 'user', 'content' => $promptIntencion]
            ],
            // 'max_output_tokens' => $maxTokens
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $openaiApiKey
            ]);

            $response = curl_exec($ch);

            curl_close($ch);

            return json_decode($response, true);
        } catch (Exception $e) {
            return [];
        }
    }

    public function calcularRetraso($userId, $fechaInicio, $fechaFin) {
        $this->loadModel("Assist");
        $this->loadModel("Exclude");
        $totalDelayMinutes = 0;
        // Convertir las fechas a timestamps
        $currentDate = strtotime($fechaInicio);
        $endDate = strtotime($fechaFin);

        $excludes = $this->Exclude->find('list', array(
            'conditions' => array(
                'Exclude.user_id' => $userId,
                "Exclude.date_excluded >=" => $fechaInicio,
                "Exclude.date_excluded <=" => $fechaFin,
            ),
            "fields" => ["id","date_excluded"]
        ));
        
        // Recorremos el rango de fechas
        while ($currentDate <= $endDate) {

            // Obtenemos el día de la semana: 1 (lunes) a 7 (domingo)

            $diaSemana = date('N', $currentDate);
            $fechaStr  = date('Y-m-d', $currentDate);

            // Consideramos de lunes a sábado
            if (!in_array($fechaStr,$excludes) && date("Y-m-d",$currentDate) >= '2025-02-24' && $diaSemana < 7 && !in_array($fechaStr, Configure::read('variables.diasFestivos')) ) {
                // Construir la fecha esperada con la hora de entrada a las 08:00
                
                $expectedTime = (date("Y-m-d",$currentDate) >= '2025-07-17' && $user_id == 127) ? ($fechaStr . ' 08:30:00') : ($fechaStr . ' 08:00:00');
                $expectedTimestamp = strtotime($expectedTime);
                
                // Buscar el registro de asistencia para el usuario en el día actual
                $assist = $this->Assist->find('first', array(
                    'conditions' => array(
                        'Assist.user_id' => $userId,
                        "DATE(Assist.created)" => $fechaStr
                    )
                ));

                


                
                if (!empty($assist)) {
                    // Se tiene registro; se calcula la diferencia de tiempo (en segundos)
                    $entradaTimestamp = strtotime($assist['Assist']['created']);
                    $delay = $entradaTimestamp - $expectedTimestamp;
                    
                    // Si el usuario llegó después de las 08:00 se suma la diferencia en minutos
                    if ($delay > 0) {
                        $totalDelayMinutes += floor($delay / 60);
                    }
                } else {
                    // No se registró asistencia, se suma 8.5 horas (510 minutos) de retraso
                    $totalDelayMinutes += 510;
                }
            }
            // Avanzar al siguiente día
            $currentDate = strtotime('+1 day', $currentDate);
        }
        
        // Convertir los minutos totales acumulados a días, horas y minutos.
        // Aquí consideramos que 1 "día" equivale a 8.5 horas (510 minutos) de jornada.
        $dias = floor($totalDelayMinutes / 510);
        $resto = $totalDelayMinutes % 510;
        $horas = floor($resto / 60);
        $minutos = $resto % 60;
        
        return compact('dias', 'horas', 'minutos');
    }

    public function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        switch(strlen($hex)){
            case 1:
                $hex = $hex.$hex;
            case 2:
                $r = hexdec($hex);
                $g = hexdec($hex);
                $b = hexdec($hex);
                break;
            case 3:
                $r = hexdec(substr($hex,0,1).substr($hex,0,1));
                $g = hexdec(substr($hex,1,1).substr($hex,1,1));
                $b = hexdec(substr($hex,2,1).substr($hex,2,1));
                break;
            default:
                $r = hexdec(substr($hex,0,2));
                $g = hexdec(substr($hex,2,2));
                $b = hexdec(substr($hex,4,2));
                break;
        }

        $rgb = array($r, $g, $b);
        return implode(",", $rgb); 
    }

    public function getPriceForProduct($product, $currency = 'cop', $costos, $posNacional = true){

        $this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];

        if(!is_null($product["Product"]["margen_wo"]) && $product["Product"]["margen_wo"] > 0 ){
            $product["Category"]["margen_wo"] = $product["Product"]["margen_wo"];
        }

        if(!is_null($product["Product"]["margen_usd"]) && $product["Product"]["margen_usd"] > 0 ){
            $product["Category"]["margen"] = $product["Product"]["margen_usd"];
        }

        if(!is_null($product["Product"]["factor"]) && $product["Product"]["factor"] > 0 ){
            $product["Category"]["factor"] = $product["Product"]["factor"];
        }
        
        $costo    = $product["Product"]["purchase_price_wo"];
        $costoRep = round($product["Category"]["factor"] * ($product["Product"]["purchase_price_usd"] * $trmActual),2);
        $typeCost = "CRM";

        if($posNacional === false ){
            $precio      = $product["Product"]["purchase_price_usd"] + $product["Product"]["aditional_usd"];
            $precioVenta = $precio / 0.8;
            $precioVenta = round(($precioVenta),2);
            $currency    = "usd";
            $typeCost    = "REPOSICION";
        }else{
            $currency    = "cop";
            if($product["Product"]["type"] == 0){
                $precio         = $product["Product"]["purchase_price_cop"] + $product["Product"]["aditional_cop"];
                $precioVenta    = $precio * 1 / ( 1 - ($product["Category"]["margen_wo"]/100) );
                $typeCost       = "CRM";
            }else{
                if ( isset( $inventory[ $product["Product"]["part_number"] ]) && $inventory[ $product["Product"]["part_number"] ] > 0 && isset($costos[$product["Product"]["part_number"]]) && ($costos[$product["Product"]["part_number"]] * 1) >=  $costoRep ) {
                    $precio = $costos[$product["Product"]["part_number"]]*1;
                    $typeCost    = "WO";
                }else{
                    $precio   = $costoRep;
                    $typeCost = "REPOSICION";
                }
                $precioVenta    = round($precio / ( 1 - ($product["Category"]["margen_wo"]/100) ) ) * 1 ;                            
            }
        }

        return $precioVenta;
    }

    public function get_image_certificate($nombre = '', $identification = '', $course = "", $city_date = "" ){
            $image       = AuthComponent::user("email") == 'serviciotecnicokebco@gmail.com' ? imagecreatefrompng(WWW_ROOT.'certificados'.DS.'DiplomaBrayan.png') : imagecreatefrompng(WWW_ROOT.'certificados'.DS.'Diploma.png') ;

            $fonts       = [
                "ArialMT-Bold" => WWW_ROOT."fonts".DS.'ArialMT-Bold.ttf',
                "berlinsans" => WWW_ROOT."fonts".DS.'berlinsans.ttf',
                "sans-serif" => WWW_ROOT."fonts".DS.'opensans.ttf',
            ];

            $font         = $fonts['ArialMT-Bold'];

            $font_size    = round(40 * 0.75);

            $color        = "#565c5f";

            $textColorRgb = explode(",",$this->hex2rgb($color));

            $fontwidth    = imagefontwidth(3);

            $text_color   = imagecolorallocate($image, intval($textColorRgb[0]), intval($textColorRgb[1]), intval($textColorRgb[2]));

            $textFinal    = $nombre;

            $divData      = strlen($textFinal) >= 50 ? 2.75 : 2.5;

            $center       = (imagesx($image)/$divData) - ($fontwidth*(strlen($textFinal)/2));

            imagettftext($image, $font_size, 0, $center, 600, $text_color, $font, $textFinal);

            $font_size    = round(30 * 0.75);

            $textFinal    = "Con número de identificación: C.C. ".$identification;

            imagettftext($image, $font_size, 0, 480, 650, $text_color, $font, $textFinal);

            $textFinal    = 'Cumplio con los objetivos de la capacitación';

            imagettftext($image, $font_size, 0, 500, 705, $text_color, $font, $textFinal);

            $textFinal    = '"'.$course.'"';

            $divData      = strlen($textFinal) >= 50 ? 2.75 : 2.5;
            
            $center       = (imagesx($image)/$divData) - ($fontwidth*(strlen($textFinal)/2));

            imagettftext($image, $font_size, 0, $center, 735, $text_color, $font, $textFinal);

            $textFinal    = 'Se expide en '.$city_date;

            $divData      = strlen($textFinal) >= 50 ? 2.75 : 2.6;

            $center       = (imagesx($image)/$divData) - ($fontwidth*(strlen($textFinal)/2));

            imagettftext($image, $font_size, 0, $center, 765, $text_color, $font, $textFinal);

            return $image;
    }

    public function getOrCreateClient($data, $user_id = null){
        $this->loadModel("ClientsNatural");
        $this->loadModel("ContacsUser");

        if(!empty($data["C"])){
            $data["C"]  = str_replace("+57", "", $data["C"]);
            $natural    = $this->ClientsNatural->findByTelephoneOrCellPhoneOrEmail($data["C"],$data["C"],$data["B"]);
            $contacto   = $this->ContacsUser->findByTelephoneOrCellPhoneOrEmail($data["C"],$data["C"],$data["B"]);
        }else{
            $natural    = $this->ClientsNatural->findByEmail($data["B"]);
            $contacto   = $this->ContacsUser->findByEmail($data["B"]);
        }

        if(!empty($natural)){
            return array("type" => "natural", "id" => $natural["ClientsNatural"]["id"],"name" => $natural["ClientsNatural"]["name"] );
        }
        if(!empty($contacto)){
            return array("type" => "legal", "id" => $contacto["ContacsUser"]["id"],"name" => $contacto["ContacsUser"]["name"]);
        }

        if(empty($natural) && empty($contacto)){
            $datos = array(
                "ClientsNatural" => array(
                    "name"          => $data["A"],
                    "telephone"     => "",
                    "cell_phone"    => $data["C"],
                    "city"          => empty($data["D"])? null: $data["D"],
                    "email"         => $data["B"],
                    "user_receptor" => is_null(AuthComponent::user("id")) ? 1 : AuthComponent::user("id"),
                )
            );

            if ($user_id != null) {
                $datos["ClientsNatural"]["user_receptor"] = $user_id;
            }

            $this->ClientsNatural->create();
            $this->ClientsNatural->save($datos);
            return array("type" => "natural", "id" => $this->ClientsNatural->id, "name" => $data["A"]); 
        }
        return null;
    }

    
    public function getClientByPhone($phone){
        $this->loadModel("ClientsNatural");
        $this->loadModel("ContacsUser");

        $phone      = str_replace("+57", "", $phone);
        $natural    = $this->ClientsNatural->findByTelephoneOrCellPhone($phone,$phone);
        $contacto   = $this->ContacsUser->findByTelephoneOrCellPhone($phone,$phone);


        if(!empty($natural)){
            return array("type" => "natural", "id" => $natural["ClientsNatural"]["id"]);
        }
        if(!empty($contacto)){
            return array("type" => "legal", "id" => $contacto["ContacsUser"]["id"], "clients_legal_id" => $contacto["ContacsUser"]["clients_legal_id"] );
        }

        return null;
    }

    public function name_state_flujo($state){ //Devuelve el nombre del estado en el que se encuentra el flujo
        switch ($state) {
            case 1:
                $nombre = 'Asignado';
                break;
            case 2:
                $nombre = 'Contactado';
                break;
            case 3:
                $nombre = 'Cotizado';
                break;
            case 4:
                $nombre = 'Negociado';
                break;
            case 5:
                $nombre = 'Pagado';
                break;
            case 6:
                $nombre = 'Despachado';
                break;

            default:
                $nombre = 'Null';
                break;
        }
        return $nombre;
    }

    public function get_data_abrasivo($ref,$quantity = 25){

        $this->loadModel('Abrasivo');

        $referencesAbrasivo         = ['M-8','M-25','M-60'];
        $referencesAbrasivoEsferico = ['S230','S280','S330'];
        $referencesAbrasivoAngular  = ['GL50', 'GL18'];
        $referencesAbrasivoGarnet   = ['GHP 30/60MESH'];
        $typeAbrasivo               = null;
        $unitPrice                  = null;
        $division                   = 25;
        $precio                     = null;

        $isRef = false;
        if(in_array($ref, $referencesAbrasivo) || in_array($ref, $referencesAbrasivoEsferico) || in_array($ref, $referencesAbrasivoAngular) || in_array($ref, $referencesAbrasivoGarnet)){
            $isRef = true;
            if(in_array($ref, $referencesAbrasivo)){
                $typeAbrasivo = 'normal';
                $precio       = 21865 + (25 * 21) + ( 25 * 33 ) ;
                
            }
            if(in_array($ref, $referencesAbrasivoEsferico)){
                $typeAbrasivo = 'esferica';
                $precio       = 32500 + 3455 + (25*3455) + (33*25) ;
            }
            if(in_array($ref, $referencesAbrasivoAngular)){
                $precio       = 32500 + 3615 + (25*3615) + (33*25) ;
                $typeAbrasivo = 'angular';
            }
            if(in_array($ref, $referencesAbrasivoGarnet)){
                $typeAbrasivo = 'garnet';
                $precio       = 32500 + 2445 + (25*2445) + (33*25) ;
            }

            $precio           /= $division;
            $unitPrice        = round($this->Abrasivo->field("unit_price",["kgs"=>$quantity,"type"=>$typeAbrasivo]) / $division);
            // $precio        = round($Abrasivo->field("price_cost",["kgs"=>$quantity,"type"=>$typeAbrasivo]) / $division);
        }
        return compact('isRef','typeAbrasivo','precio','unitPrice');
    }
    
	public function beforeFilter(){
        App::uses('Configuration', 'Vendor'); 
        $whitelist = array(
            '127.0.0.1',
            '::1'
        );
        if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            $this->forgeSSL();
        }
        $this->validateSessionActive();

        if($this->request->controller !== "inventories" && !$this->request->is("ajax") && !in_array($this->request->controller, ["css","img","js"])){
            $this->Session->delete("productId");
        }

        if ($this->request->is('mobile')) {
            $this->MobileDetect = $this->Components->load('MobileDetect.MobileDetect');

            $result = $this->MobileDetect->detect('isTablet');
            $result1 = $this->MobileDetect->detect('isMobile');
            $movileAccess = $result ? true : $this->MobileDetect->detect('isMobile');
        }else{
            $movileAccess = false;
        }

        $this->set("movileAccess",$movileAccess);

        // $this->configurations();
        $this->validatePassword();
        header('Access-Control-Allow-Origin: *');
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->validateApprobes();
        $this->validateTechnical();
        $this->validateGestLogistics();
        // $this->validateMesages();
        // $this->validateGestOrders();
        $this->validateShipping();
        // $this->validateDeletesAndNoGest();
        $this->verifyRejected();
    }

    public function verifyRejected(){
        if (!AuthComponent::user("id") || $this->request->is("ajax")  ) {
            return false;
        }
        $this->loadModel("ProspectiveUser");
        $flowRejected = $this->ProspectiveUser->findByRejectedAndUserId(1,AuthComponent::user("id"));
        if(!empty($flowRejected)){
            $this->set("rejected_flow",$flowRejected["ProspectiveUser"]);
        }

    }

    public function find_name_document_quotation_send($prospective_id){
        $this->loadModel("FlowStage");
        $this->loadModel("Quotation");
        if ($prospective_id == 0) {
            $nombre                 = 'Importacion solicitada';
        } else {
            $existeEtapa            = $this->FlowStage->exist_state_cotizado_prospective($prospective_id);
            if ($existeEtapa > 0) {
                $datos          = $this->FlowStage->id_name_document_latest_regystri_state_cotizado($prospective_id);
                $quotation      = $this->Quotation->get_data($datos['FlowStage']['document']);
                $nombre         = $quotation['Quotation']['codigo']." ".$quotation['Quotation']['name'];
            } else {
                $nombre = 'La cotización NO ha sido enviada';
            }
        }
        return $nombre;
    }

    public function getClientsLegals($term = null){
        $this->loadModel("ProspectiveUser");
        $recursive      = -1;
        $legales        = array();
        $order          = array("name" => "ASC");
        $conditions     = [];


        if (is_null($term)) {
            $clientsLegals  = $this->ProspectiveUser->ContacsUser->ClientsLegal->find('all',compact("recursive","order","conditions"));
        }else{
            $clientsLegals  = $this->ProspectiveUser->ContacsUser->ClientsLegal->find_seeker($term);

             if (AuthComponent::user("role") == "Asesor Externo") {
                $conditions[]   = ["OR" => ['LOWER(ClientsLegal.name) LIKE'    => '%'.$term.'%',
                                        'LOWER(ClientsLegal.nit) LIKE'      => '%'.$term.'%'] ];
                $conditions["OR"]["OR"] = [["ClientsLegal.user_receptor" => AuthComponent::user("id")]];
                $this->loadModel("ClientsUser");
                $clientsAsign = $this->ClientsUser->find("list",["conditions"=>["user_id"=>AuthComponent::user("id"), "clients_legal_id !=" => null],"fields"=>["clients_legal_id","clients_legal_id"]]);
                if (!empty($clientsAsign)) {
                    $conditions["OR"]["OR"][] = ["ClientsLegal.id" => array_values($clientsAsign) ];
                }
                $clientsLegals  = $this->ProspectiveUser->ContacsUser->ClientsLegal->find('all',compact("recursive","order","conditions"));
            }
        }

        if (!empty($clientsLegals)) {
            foreach ($clientsLegals as $key => $value) {
                $legales[$value["ClientsLegal"]["id"]."_LEGAL"] = trim($value["ClientsLegal"]["nit"])." | ".trim($value["ClientsLegal"]["name"]);
            }
        }
        return $legales;
    }

    public function getClientsNaturals($term = null){
        $this->loadModel("ProspectiveUser");
        
        $recursive      = -1;
        $naturales      = array();
        $order          = array("name" => "ASC");
        $conditions     = [];

        if (is_null($term)) {
            $clientsNaturals    = $this->ProspectiveUser->ClientsNatural->find('all',compact("recursive","order","conditions"));
        }else{
            if (AuthComponent::user("role") == "Asesor Externo") {

                $conditions[]         = array('OR' => array(
                                        'LOWER(ClientsNatural.name) LIKE'   => '%'.$term.'%',
                                        'LOWER(ClientsNatural.email) LIKE'  => '%'.$term.'%',
                                        'LOWER(ClientsNatural.identification) LIKE'     => '%'.$term.'%',
                                        'LOWER(ClientsNatural.telephone) LIKE'  => '%'.$term.'%',
                                        'LOWER(ClientsNatural.cell_phone) LIKE'     => '%'.$term.'%',
                                    )
                                );

                $conditions["OR"]["OR"] = [["ClientsNatural.user_receptor" => AuthComponent::user("id")]];
                $this->loadModel("ClientsUser");
                $clientsAsign = $this->ClientsUser->find("list",["conditions"=>["user_id"=>AuthComponent::user("id"), "clients_natural_id !=" => null],"fields"=>["clients_natural_id","clients_natural_id"]]);
                if (!empty($clientsAsign)) {
                    $conditions["OR"]["OR"][] = ["ClientsNatural.id" => array_values($clientsAsign) ];
                }
                $clientsNaturals    = $this->ProspectiveUser->ClientsNatural->find('all',compact("recursive","order","conditions"));
            }else{
                $clientsNaturals  = $this->ProspectiveUser->ClientsNatural->find_seeker($term);
            }
        }

        if (!empty($clientsNaturals)) {
            foreach ($clientsNaturals as $key => $value) {
                $naturales[$value["ClientsNatural"]["id"]."_NATURAL"] = trim($value["ClientsNatural"]["identification"])." | ".trim($value["ClientsNatural"]["name"])." | ".trim($value["ClientsNatural"]["email"]);
            }
        }
        return $naturales;
    }

    public function validateDeletesAndNoGest(){
        if (!AuthComponent::user("id") || AuthComponent::user("role") == "Gerente General2" || $this->request->is("ajax") || AuthComponent::user("id") == 2 ) {
            return 0;
        }

        $this->loadModel("Approve");
        $this->loadModel("ProspectiveUser");

        $totalDeleted = $this->Approve->find("count",["conditions"=> ["Approve.user_id" => AuthComponent::user("id"), "Approve.state" => 0,"Approve.type_aprovee" => [2,3] ] ]);

        $totalLosed = $this->ProspectiveUser->find("count",["conditions"=> ["ProspectiveUser.user_id" => AuthComponent::user("id"), "ProspectiveUser.state" => 3,"ProspectiveUser.state_flow" => 10 ] ]);

        $totalDeledValidate = Configuration::get_flow("flows_deleted");
        $totalGestValidate  = Configuration::get_flow("flows_no_gests");

        $totalDeleted = 0;

        if ($totalDeleted >= $totalDeledValidate || $totalLosed >= $totalGestValidate) {
            $actions = ["logout","home_adviser"];

            if ($totalDeleted >= $totalDeledValidate && $totalLosed >= $totalGestValidate) {
                $this->Session->setFlash(__('Tienes '.$totalDeleted. ' flujos por solicitud de cancelación y '.$totalDeleted.' flujos perdidos por no realizar gestión a tiempo, comunícate con la gerencia por favor para que valide estas solicitudes.'),'Flash/error');
            }else{
                if ($totalDeleted >= $totalDeledValidate) {
                    $this->Session->setFlash(__('Tienes '.$totalDeleted. ' flujos por solicitud de cancelación comunícate con la gerencia por favor para que valide estas solicitudes.'),'Flash/error');
                }

                if ($totalLosed >= $totalGestValidate) {
                    $this->Session->setFlash(__('Tienes '.$totalLosed. ' flujos perdidos por no realizar gestión a tiempo, comunícate con la gerencia por favor para que valide estas solicitudes.'),'Flash/error');
                }
            }

            if (!in_array($this->request->controller, ["Users","Pages"])) {
                if ($this->request->controller == "Pages" && $this->request->action == "home_adviser") {
                    return true;
                }elseif ($this->request->controller == "Users" && $this->request->action == "logout") 
                {
                    return true;
                }else{
                     $this->redirect(["controller"=>"Pages","action"=>"home_adviser"]);
                }
            }elseif ($this->request->controller == "Pages" && $this->request->action != "home_adviser") {
                $this->redirect(["controller"=>"Pages","action"=>"home_adviser"]);
            }
        }

    }



    public function validateTimes($return = false){
        $hours_contact      = Configuration::get_flow("hours_contact");
        $hours_quotation    = Configuration::get_flow("hours_quotation");
        $flows_cntact       = Configuration::get_flow("flows_cntact");
        $flows_quotation    = Configuration::get_flow("flows_quotation");

        $this->loadModel("ProspectiveUser","FlowStage");

        $flows_no_contact     = $this->ProspectiveUser->find("list",["fields"=>["id","id"],"conditions" => ["valid"=>0,"type"=>0,"state_flow" => 1, "time_contact !=" => null, "time_contact <=" => date("Y-m-d H:i:s"), "user_id" => AuthComponent::user("id") ] ]);

        $flows_no_quotation   = $this->ProspectiveUser->find("list",["fields"=>["id","id"],"conditions" => ["valid"=>0,"type"=>0,"state_flow" => 2, "time_quotation !=" => null,"time_quotation <=" => date("Y-m-d H:i:s"), "user_id" => AuthComponent::user("id") ] ]);

        $flows_no_contact     = count($flows_no_contact) < $flows_cntact || AuthComponent::user("role") == "Gerente General" ? [] : $flows_no_contact;
        $flows_no_quotation   = count($flows_no_quotation) < $flows_quotation || AuthComponent::user("role") == "Gerente General" ? [] : $flows_no_quotation;

        if ($return) {
            if (!empty($flows_no_contact) || !empty($flows_no_quotation)) {
                $this->Session->setFlash(__('Tienes pendientes '. ( count($flows_no_contact) + count($flows_no_quotation) ) . ' flujos por gestionar' . "Flujos sin contactar: ".implode(",", $flows_no_contact). " | Flujos sin cotizar: ".implode(",",$flows_no_quotation)),'Flash/error');
            }
            return [ "contact" => $flows_no_contact, "quotation" => $flows_no_quotation  ];
        }else{
            if (!empty($flows_no_contact) || !empty($flows_no_quotation)) {
                $this->Session->setFlash(__('Tienes pendientes '. ( count($flows_no_contact) + count($flows_no_quotation) ) . ' flujos por gestionar' . "Flujos sin contactar: ".implode(",", $flows_no_contact). " | Flujos sin cotizar: ".implode(",",$flows_no_quotation)),'Flash/error');
                $this->redirect(["controller"=>"prospective_users","action"=>"adviser"]);
            }            
        }

    }


    public function datos_clientes($getDataClient = false) {


        $options          = ["ini" => date("2020-01-01"), "end" => date("Y-m-d") ];
        $allDocument      = $getDataClient ? $this->postWoApi($options,"documents") : $this->getAllFacturas();

        $newClients     = $this->postWoApi($options,"clientes");
        $mesesCats      = $this->meses2;
        $anios          = [];
        $totalByCompany = [];
        $clientesByAnio = [];

        for ($i=date("Y"); $i >= date("Y",strtotime($options["ini"])) ; $i--) { 
            $anios[] = intval($i);
            $clientesByAnio[$i] = [];
            foreach ($mesesCats as $mesNumber => $mesName) {
                $totalByCompany[$i][$mesName] = 0;
            }
        }

        $totalNumberByMonth  = $totalByCompany;
        $totalPaymentByMonth = $totalByCompany;
        $totalComprasMes     = $totalByCompany;
        $ventasPercentaje    = $totalByCompany;
        $totalPercentaje     = $totalByCompany;

        $clientesNuevosArr   = [];
        $ventasNuevosArr     = [];

        $nombresClientes          = [];
        $clientesByAnioDetail     = [];

        $allDocument->listado = empty($allDocument->listado) ? [] : $allDocument->listado;
        foreach ($allDocument->listado as $key => $value) {
            $nombresClientes[$value->Identificacion] = $value;
            
            $dateIni = explode(" ", $value->Fecha);
            $date    = explode("-", $dateIni[0])[1];
            $anio    = explode("-", $dateIni[0])[0];
            $totalByCompany[$anio][ $this->meses2[$date] ] += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);
            $totalComprasMes[$anio][ $this->meses2[$date] ] ++;
            $clientesByAnio[$anio][] = $value->Identificacion;
            $clientesByAnioDetail[$anio][$value->Identificacion][] = $value;
        }       

        $nombreClientesCache    = Cache::read("CacheDataClients");

        if (is_null($nombreClientesCache) || ( !is_null($nombreClientesCache) && count($nombreClientesCache) < count($nombresClientes) ) ) {
            Cache::write("CacheDataClients",$nombresClientes);
        }

        $dataByAnio = [];
        $copyAnios  = $anios;

        sort($copyAnios);

        foreach ($nombresClientes as $identification => $value) {
            $keyFinal = [];
            foreach ($copyAnios as $key => $anio) {
                if (in_array($identification, $clientesByAnio[$anio])) {
                    $keyFinal[] = $anio;
                }
            }
            $dataByAnio[implode("-", $keyFinal)][] = $value;
        }

        if ($getDataClient) {
            return compact("dataByAnio","nombresClientes","clientesByAnioDetail");
        }

        ksort($dataByAnio);
        $dataOnlyYear = [];

        foreach ($copyAnios as $key => $value) {
            $dataOnlyYear[$value] = $dataByAnio[$value];
            unset($dataByAnio[$value]);
        }

        $newClients->listado = empty($newClients->listado) ? [] : $newClients->listado;

        foreach ($newClients->listado as $key => $value) {
            $totalPaymentByMonth[$value->Anio][ $this->meses2[$value->Mes] ] += floatval(is_null($value->Comprado) ? 0 : $value->Comprado );
            $totalNumberByMonth[$value->Anio][ $this->meses2[$value->Mes] ] ++;
        }

        foreach ($totalPaymentByMonth as $anio => $datos) {
            $ventasObj = new stdClass();
            $ventasObj->name = strval($anio);
            $ventasObj->data = [];
            foreach ($datos as $mes => $valor) {
                $valoresObj = new stdClass();
                $valoresObj->y = $valor;
                $valoresObj->utilidad = round( ( ($valor * 0.35) - 5000000 ) ,2);
                $ventasPercentaje[$anio][$mes] = $valor != 0 ?  round($valor / $totalByCompany[$anio][$mes] * 100,2) : 0;
                $ventasObj->data[] = $valoresObj;
            }
            $ventasNuevosArr[$anio] = $ventasObj;
        }

        foreach ($totalNumberByMonth as $anio => $datos) {

            $countObj = new stdClass();
            $countObj->name = strval($anio);
            $countObj->data = [];

            foreach ($datos as $mes => $valor) {
                $totalPercentaje[$anio][$mes] = $valor != 0 ?  round($valor / $totalComprasMes[$anio][$mes] * 100,2) : 0;
                $countObj->data[] = $valor;
            }
            $clientesNuevosArr[$anio] = $countObj;
        }

        $this->set("anios",$anios);
        $this->set("clientesNuevosArr",$clientesNuevosArr);
        $this->set("ventasNuevosArr",$ventasNuevosArr);
        $this->set("dataByAnio",$dataByAnio);
        $this->set("dataOnlyYear",$dataOnlyYear);
        $this->set("nombresClientes",$nombresClientes);
        $this->set("clientesByAnio",$clientesByAnio);

        $this->set("labelsMes",array_values($mesesCats));




        $copyTotalByCompany         = $totalByCompany;
        $copytotalPaymentByMonth    = $totalPaymentByMonth;
        $totalByCompany             = [];
        $totalPaymentByMonth        = [];


        foreach ($copyTotalByCompany as $anio => $datosMeses) {
            $totalByCompany[$anio]      = [];
            $totalPaymentByMonth[$anio] = [];
        }

        foreach ($copyTotalByCompany as $anio => $datosMeses) {
            foreach ($datosMeses as $mes => $valor) {
                $totalByCompany[$anio][] = [$mes,$valor];
            }
        }

        $dataClientAntiguo = [];

        $valoresClientesNuevos = [];
        $valoresClientesViejos = [];
        $valoresCumplimientoNv = [];
        $valoresCumplimientoVj = [];

        foreach ($copytotalPaymentByMonth as $anio => $datosMeses) {
            foreach ($datosMeses as $mes => $valor) {
                $objDatos = new stdClass();
                $objDatos->name = $mes;
                $objDatos->y = $valor;
                $objDatos->cumplimiento          = $ventasPercentaje[$anio][$mes];
                $totalPaymentByMonth[$anio][]    = $objDatos;
                if ($valor > 0) {
                    $valoresClientesNuevos[$anio][]  = $valor;                  
                    $valoresCumplimientoNv[$anio][]  = $objDatos->cumplimiento;                 
                }

                $objAntiguo                      = new stdClass();
                $objAntiguo->name                = $mes;
                $objAntiguo->y                   = round($copyTotalByCompany[$anio][$mes]-$copytotalPaymentByMonth[$anio][$mes]);
                $objAntiguo->cumplimiento        = round(100-$ventasPercentaje[$anio][$mes],2);
                $dataClientAntiguo[$anio][]      = $objAntiguo;
                if ($objAntiguo->y > 0) {
                    $valoresClientesViejos[$anio][]  = $objAntiguo->y;
                    $valoresCumplimientoVj[$anio][]  = $objAntiguo->cumplimiento;
                }
            }
        }

        $promediosByAnioNuevos = [];
        $promediosByAnioViejos = [];

        foreach ($valoresClientesNuevos as $anio => $valores) {
            $valoresClientesNuevos[$anio] = number_format(array_sum($valores));
        }

        foreach ($valoresCumplimientoNv as $anio => $values) {
            $promediosByAnioNuevos[$anio] = number_format(round(array_sum($values)/count($values),2),2);            
        }

        foreach ($valoresCumplimientoVj as $anio => $values) {
            $promediosByAnioViejos[$anio] = number_format(round(array_sum($values)/count($values),2),2);            
        }

        foreach ($valoresClientesViejos as $anio => $valores) {
            $valoresClientesViejos[$anio] = number_format(array_sum($valores));
        }

        $this->set("totalByCompany",($totalByCompany));
        $this->set("totalPaymentByMonth",($totalPaymentByMonth));

        $this->set("dataClientAntiguo",($dataClientAntiguo));
        $this->set("promediosByAnioNuevos",($promediosByAnioNuevos));
        $this->set("valoresClientesNuevos",($valoresClientesNuevos));
        $this->set("promediosByAnioViejos",($promediosByAnioViejos));
        $this->set("valoresClientesViejos",($valoresClientesViejos));

        $copytotalComprasMes        = $totalComprasMes;
        $copytotalNumberByMonth     = $totalNumberByMonth;
        $totalComprasMes            = [];
        $totalNumberByMonth         = [];

        foreach ($copytotalComprasMes as $anio => $datosMeses) {
            $totalComprasMes[$anio]         = [];
            $totalNumberByMonth[$anio] = [];
        }

        foreach ($copytotalComprasMes as $anio => $datosMeses) {
            foreach ($datosMeses as $mes => $valor) {
                $totalComprasMes[$anio][] = [$mes,$valor];
            }
        }

        foreach ($copytotalNumberByMonth as $anio => $datosMeses) {
            foreach ($datosMeses as $mes => $valor) {

                $objDatos = new stdClass();
                $objDatos->name = $mes;
                $objDatos->y = $valor;
                $objDatos->cumplimiento = $totalPercentaje[$anio][$mes] ;
                $totalNumberByMonth[$anio][] = $objDatos;

                // $totalPaymentByMonth[$anio][] = [$mes,$valor];
            }
        }

        $this->set("totalComprasMes",($totalComprasMes));
        $this->set("totalNumberByMonth",($totalNumberByMonth));



        // echo "<pre>";
        // print_r($clientesNuevosArr);
        // print_r($ventasNuevosArr);
        // die;


        // var_dump($totalComprasMes);
        // var_dump($totalNumberByMonth);
        // var_dump($totalPercentaje);


        // var_dump("-----------------------");

        // var_dump($totalByCompany);
        // var_dump($totalPaymentByMonth);
        // var_dump($ventasPercentaje);


        // var_dump("-----------------------");
        
        // var_dump($mesesCats);
        // var_dump($anios);
        // die;
    }

    public function getAllFacturas(){
        $options          = ["ini" => date("2020-01-01"), "end" => date("Y-m-d") ];
        $optionsCount     = ["ini" => date("2020-01-01"), "end" => date("Y-m-d"), "only_number" => 1 ];
        $allDocumentCount = $this->postWoApi($optionsCount,"documents");
        $countDatabase    = Cache::read("CountDocuments");

        if (is_null($countDatabase) || $countDatabase < $allDocumentCount->listado) {
            $allDocument  = $this->postWoApi($options,"documents");
            Cache::write("CountDocuments",$allDocumentCount->listado);
            Cache::write("allDocument",$allDocument);
        }else{
            $allDocument  = Cache::read("allDocument");
            if (is_null($allDocument)) {
                $allDocument  = $this->postWoApi($options,"documents");
                Cache::write("allDocument",$allDocument);
            }
        }
        return $allDocument;
    }

    public function validateGarantia($brand_id){
        $this->loadModel("Garantia");
        $idGarantia = $this->Garantia->field("id",["brand_id" =>$brand_id, "state" => 1 ]);
        return $idGarantia === false ? true : false;
    }

    public function validateProducts($datos_factura,$data){
        $this->loadModel("FlowStage");
        $flujo_id               = $data["ProspectiveUser"]["id"];
        $id_etapa_cotizado      = $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
        $id_etapa_pagado        = $this->FlowStage->id_latest_regystri_state_pagado($flujo_id);
        $datos                  = $this->FlowStage->get_data($id_etapa_cotizado);
        $produtosCotizacion     = $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datos['FlowStage']['document']);

        $locked                 = 0;

        $dataQtProducts         = [];
        $totalFacturaNoIVa      = 0;
        $totalFactura           = 0;

        $productosValidar       = [];

        if (isset($data["ProspectiveUser"]["products_data"]) && isset($data["ProspectiveUser"]["products_data"][0]->productos) && !empty($data["ProspectiveUser"]["products_data"][0]->productos)) {
            $productosValidar = $this->request->data["ProspectiveUser"]["products_data"][0]->productos;
        }


        foreach ($produtosCotizacion as $key => $value) {
            
            if (!empty($productosValidar) && !in_array($value["QuotationsProduct"]["id"], $productosValidar) ) {
                continue;
            }

            if (!array_key_exists($value["Product"]["part_number"], $dataQtProducts )) {
                $dataQtProducts[ $value["Product"]["part_number"] ] = $value["QuotationsProduct"]["quantity"];
            }else{
                $dataQtProducts[ $value["Product"]["part_number"] ] += $value["QuotationsProduct"]["quantity"];
            }
            $totalFacturaNoIVa += ($value["QuotationsProduct"]["quantity"] * $value["QuotationsProduct"]["price"]);
        }


        if(!empty($datos_factura["productos_factura"])){
            foreach ($datos_factura["productos_factura"] as $key => $value) {
                $totalFactura += $value->Precio * intval($value->Cantidad);
            }
        }



        if (empty($datos_factura["productos_factura"]) || (!empty($datos_factura["productos_factura"]) && count($datos_factura["productos_factura"]) != count($dataQtProducts) ) ) {
            $locked =  1;
        }else{
            foreach ($datos_factura["productos_factura"] as $key => $value) {

                if ( array_key_exists( $value->CódigoInventario , $dataQtProducts) && $dataQtProducts[ $value->CódigoInventario ] == $value->Cantidad ) {
                    $locked = 0;
                }else{
                    $locked = 1;
                    break;
                }
            }
        }   

        if ($locked == 0) {
            $diference = $totalFactura - $totalFacturaNoIVa;
            if ( abs($diference) > 100000 ) {
                $locked = 1;
            }
        }

        if($locked > 0){
            $otras_facturas = $this->getOthersFacts($flujo_id,$data["ProspectiveUser"]["bill_code"]);

            if(!empty($otras_facturas)){
                foreach ($otras_facturas as $keyFact => $valueFact) {
                    $factValue = (array) json_decode($valueFact);
                    if(!empty($factValue)){

                        foreach ($factValue["productos_factura"] as $key => $value) {
                            $totalFactura += ($value->Cantidad*$value->Precio);
                        }
                        
                    }
                }
            }
            
            $diference = $totalFactura - $totalFacturaNoIVa;
            if ( abs($diference) < 100000 ) {
                $locked = 0;
            }
        }

        return $locked;

    }

    public function getOthersFacts($flujo_id, $code){
        $this->loadModel("Salesinvoice");
        $this->loadModel("ProspectiveUser");
        $facturas  = [];
        $flujoData = $this->ProspectiveUser->find("first",["conditions"=>["id"=>$flujo_id],"recursive"=>-1]);

        if($flujoData["ProspectiveUser"]["locked"] != 1 &&  trim($flujoData["ProspectiveUser"]["bill_code"]) != trim($code) ){
            $facturas[trim($flujoData["ProspectiveUser"]["bill_code"])] = $flujoData["ProspectiveUser"]["bill_text"];
        }

        $salesinvoices = $this->Salesinvoice->find("all",["conditions"=>["prospective_user_id"=>$flujo_id,"locked"=>0],"recursive" => -1 ]);

        if(!empty($salesinvoices)){
            foreach ($salesinvoices as $key => $value) {
                if(trim($value["Salesinvoice"]["bill_code"]) != trim($code)){
                    $facturas[trim($value["Salesinvoice"]["bill_code"])] = $value["Salesinvoice"]["bill_text"];
                }
            }
        }

        return $facturas;

    }

    private function getTotalNoGest($requests){
        $total = 0;

        foreach ($requests as $key => $value) {
            if (isset($value["ImportRequestsDetail"]["details"])) {
                foreach ($value["ImportRequestsDetail"]["details"] as $keyDetail => $valueDetail) {

                    $fecha = strtotime("-7 days",strtotime($valueDetail["deadline"]));
                    if ( (strtotime("now") > $fecha || strtotime($valueDetail["deadline"]) <= strtotime("now")) && $valueDetail["gest"] == 0 && !is_null($valueDetail["prospective_user_id"])   ) {
                        $total ++;
                    }
                }
            }
            if (isset($value["ImportRequestsDetail"]["otherDetails"])) {
                foreach ($value["ImportRequestsDetail"]["otherDetails"] as $keyOther => $valueOther) {
                    $valueOther = $valueOther[0];
                    $fecha = strtotime("-7 days",strtotime($valueOther["deadline"]));
                    if ( (strtotime("now") > $fecha || strtotime($valueOther["deadline"]) <= strtotime("now")) && $valueOther["gest"] == 0 && !is_null($valueOther["prospective_user_id"])   ) {
                        $total ++;
                    }
                }
            }
        }
        return $total;
    }

    private function getTotalNoGestPrimary($requests){
        $total = 0;
        $flujos = [];
        foreach ($requests as $key => $value) {
            if (isset($value["ImportRequestsDetail"]["details"])) {
                foreach ($value["ImportRequestsDetail"]["details"] as $keyDetail => $valueDetail) {

                    $fecha = strtotime($valueDetail["created"]." +1 day");
                    if ( (strtotime("now") > $fecha || strtotime($valueDetail["deadline"]) <= strtotime("now")) && !is_null($valueDetail["prospective_user_id"]) && $valueDetail["state"] == 1 ) {
                        $total ++;
                        $flujos[] = $valueDetail["prospective_user_id"]."normal".$valueDetail["id"];
                    }
                }
            }
        }
        return $total;
    }

    public function validateMesages(){
        if ($this->request->is("ajax")) {
            return true;
        }
        $this->loadModel("Manage");
        $datePres = date("Y-m-d H:i:s",strtotime("-24 hour"));
        $total    = $this->Manage->find("count",["conditions" => ["Manage.state" => 0, "Manage.user_id" => AuthComponent::user("id"), "Manage.created <" => $datePres, "Manage.type" => 1,],"limit" => 100]);

        $redirect = $this->Session->read("REDIRECT");

        if ($total > 0 && is_null($redirect) ) {
            $actions = ["logout","index"];
            $this->Session->setFlash(__('Tienes pendientes '.$total. ' notificaciones obligatorias de lectura'),'Flash/error');
            if (!in_array($this->request->controller, ["Users","Manages"])) {
                if ($this->request->controller == "Manages" && $this->request->action == "index") {
                    return true;
                }elseif ($this->request->controller == "Users" && $this->request->action == "logout") 
                {
                    return true;
                }else{
                     $this->redirect(["controller"=>"Manages","action"=>"index"]);
                }
            }elseif ($this->request->controller == "Manages" && $this->request->action != "index") {
                $this->redirect(["controller"=>"Manages","action"=>"index"]);
            }
        }
    }

    public function validateGestLogistics(){
        $this->loadModel("ImportRequest");
        if ($this->request->is("ajax") || in_array(strtolower($this->request->controller), ["users","technical_services","technicalservices",'assists','Assists']) ) {
            return true;
        }

        $rolesImports = array( Configure::read('variables.roles_usuarios.Logística'),"Gerente General2");
        if(in_array(AuthComponent::user('role'), $rolesImports) ){  
            $requestsNac = [];
            $requestsInt = [];
            if (AuthComponent::user("email") == "logistica@kebco.co") {
                $requestsNac        = $this->ImportRequest->getImportRequestBrands();
                $requestsInt        = $this->ImportRequest->getImportRequestBrands(1);
            }
            if (AuthComponent::user("email") == "logistica@almacendelpintor.com") {
                $requestsInt        = $this->ImportRequest->getImportRequestBrands(1);
            }            
            $totalNoGest        = [];
            $totalNacNoGest     = $this->getTotalNoGestPrimary($requestsNac);
            $totalIntNoGest     = $this->getTotalNoGestPrimary($requestsInt);
            $total              = $totalNacNoGest + $totalIntNoGest; 

            if ($total > 0) {
                $actions = ["logout","request_import_brands"];
                $this->Session->setFlash(__('Tienes pendientes '.$total. ' solicitudes por gestionar pertenecientes a flujos'),'Flash/error');
                if (!in_array($this->request->action, $actions)) {
                     $this->redirect(["controller"=>"ProspectiveUsers","action"=>"request_import_brands"]);
                }
            }else{
                $this->loadModel("Import");
                $fechaValida = date("Y-m-d H:i:s",strtotime("-3 day"));
                $totalNoOrder = $this->Import->find("count",["conditions" => ["Import.brand_id" => 4, "Import.fecha_gerencia !=" => null, "Import.fecha_gerencia <=" => $fechaValida, "orden_proveedor" => null,"Import.state" => 1  ] ]);

                if ($totalNoOrder > 0) {

                    $total   = 0;

                    $all_Imports = $this->Import->find("all",["conditions" => ["Import.brand_id" => 4, "Import.fecha_gerencia !=" => null, "Import.fecha_gerencia <=" => $fechaValida, "orden_proveedor" => null,"Import.state" => 1  ], "recursive" => -1 ]);

                    foreach ($all_Imports as $key => $value) {
                        $total+= $value["Import"]["total_price"];
                    }

                    $actions = ["logout","imports_approved"];
                    $this->Session->setFlash(__('Tienes pendientes '.$totalNoOrder. ' solicitudes realizadas a graco por confirmar orden a proveedor'),'Flash/error');
                    if (!in_array($this->request->action, $actions) && $total >= 500 ) {
                         $this->redirect(["controller"=>"ProspectiveUsers","action"=>"imports_approved"]);
                    }
                }
            }
        }
    }

    public function validateGestOrders(){

        $this->loadModel("ImportRequest");
        if ($this->request->is("ajax")) {
            return true;
        }
        if (AuthComponent::user("role") == Configure::read('variables.roles_usuarios.Logística')) {
            $this->ImportRequest->ImportRequestsDetail->updateAll(
                ["ImportRequestsDetail.gest" => 0],
                ["ImportRequestsDetail.prospective_user_id !=" => null, "ImportRequestsDetail.deadline <" => date("Y-m-d") ]
            );
        }
        

        $rolesImports = array( Configure::read('variables.roles_usuarios.Logística'));
        if(in_array(AuthComponent::user('role'), $rolesImports) ){
            
            $requestsNac        = $this->ImportRequest->getImportRequestBrands();
            $requestsInt        = $this->ImportRequest->getImportRequestBrands(1);
            $totalNoGest        = [];
            $totalNacNoGest     = $this->getTotalNoGest($requestsNac);
            $totalIntNoGest     = $this->getTotalNoGest($requestsInt);
            $total              = $totalNacNoGest + $totalIntNoGest; 
            if ($total > 0) {
                $actions = ["logout","request_import_brands"];
                $this->Session->setFlash(__('Tienes pendientes '.$total. ' solicitudes por gestionar'),'Flash/error');
                if (!in_array($this->request->action, $actions)) {
                     $this->redirect(["controller"=>"ProspectiveUsers","action"=>"request_import_brands"]);
                }
            } 
        }
    }

    public function validateTechnical(){
        if (!AuthComponent::user("id") || !in_array(AuthComponent::user("id"),[ 7,122])  ) {
            return true;
        }
        $this->loadModel("TechnicalService");
        $datePres = date("Y-m-d H:i:s",strtotime("-72 hour"));

        if (!$this->request->is("ajax")) {            
            $total    = $this->TechnicalService->find("count",["conditions" => ["TechnicalService.state" => 0, "TechnicalService.user_id" => AuthComponent::user("id"), "TechnicalService.created <" => $datePres ]]);
            if ($total > 0) {
                $actions = ["logout","index","upload_document"];
                $this->Session->setFlash(__('Tienes pendientes '.$total. ' servicios sin generar un diagnostico'),'Flash/error');
                if (!in_array(strtolower($this->request->controller), ["users","technical_services","technicalservices",'assists','Assists'])) {
                    if ($this->request->controller == "TechnicalServices" && $this->request->action == "index") {
                        return true;
                    }elseif ($this->request->controller == "TechnicalServices" && $this->request->action == "upload_document") {
                        return true;
                    }elseif ($this->request->controller == "Users" && $this->request->action == "logout") 
                    {
                        return true;
                    }else{
                         $this->redirect(["controller"=>"TechnicalServices","action"=>"index"]);
                    }
                }elseif ($this->request->controller == "TechnicalServices" && !in_array($this->request->action,["index","view","upload_document","edit"]) ) {
                    $this->redirect(["controller"=>"TechnicalServices","action"=>"index"]);
                }
            }
        }
    }

    private function set_url_aprove_internas($totalFacts){
        $actions = ["logout","solicitudes_internas"];
        $this->Session->setFlash(__('Tienes pendientes '.$totalFacts. ' solicitudes internas por aprobar'),'Flash/error');
        if (!in_array($this->request->action, $actions)) {
             $this->redirect(["controller"=>"ProspectiveUsers","action"=>"solicitudes_internas"]);
        }
    }

    private function set_url_aprove_facts($totalFacts){
        $actions = ["logout","facturas_bloqueadas"];
        $this->Session->setFlash(__('Tienes pendientes '.$totalFacts. ' facturas por aprobar'),'Flash/error');
        if (!in_array($this->request->action, $actions)) {
             $this->redirect(["controller"=>"ProspectiveUsers","action"=>"facturas_bloqueadas"]);
        }
    }

    public function validateApprobes(){
        if (!AuthComponent::user("id") || $this->request->is("ajax")) {
            return true;
        }
        $totalFacts     = 0;
        $totalInternas  = 0;
        // if (AuthComponent::user("role") == "Asesor Comercial" && AuthComponent::user("email") != "jotsuar@gmail.com") {
        if (AuthComponent::user("role") == "Gerente General" && AuthComponent::user("email") != "jotsuar@gmail.com" && AuthComponent::user("email") != "ventas@kebco.co" ) {
            $this->loadModel("ProspectiveUser");
            $this->loadModel("Salesinvoice");
            

            $prospective_users = $this->ProspectiveUser->find("count",["conditions" => ["ProspectiveUser.locked" => 1, "ProspectiveUser.date_locked <" => date("Y-m-d H:i:s",strtotime("-24 hour")) ]  ]);
            $salesinvoices     = $this->Salesinvoice->find("count",["conditions" => ["Salesinvoice.locked" => 1, "Salesinvoice.date_locked <" => date("Y-m-d H:i:s",strtotime("-24 hour")) ]  ]);
            $totalFacts        = ($prospective_users) + ($salesinvoices);

        }

        if (AuthComponent::user("role") == "Gerente General" && AuthComponent::user("email") != "jotsuar@gmail.com") {
            $this->loadModel("ImportRequest");
            $solicitudesActivas = $this->ImportRequest->ImportRequestsDetail->find("all",["conditions" => ["ImportRequest.state" => 1,"ImportRequestsDetail.state"=>2,"ImportRequestsDetail.type_request" =>2,"ImportRequestsDetail.created <" => date("Y-m-d H:i:s",strtotime("-24 hour")) ] ]);
            $products = [];

            if (!empty($solicitudesActivas)) {
                foreach ($solicitudesActivas as $key => $value) {
                    foreach ($value["Product"] as $keyProduct => $product) {
                        if ($product["ImportRequestsDetailsProduct"]["state"] == 1) {
                            if (!array_key_exists($product["id"], $products)) {
                                $products[$product["id"]] = ["Product"=>$product];
                            }
                        }else{
                            unset($solicitudesActivas[$key]["Product"][$keyProduct]);
                            continue;
                        }
                    }
                }
            }

            $totalInternas  = count($solicitudesActivas);
            if ($totalInternas > 0) {
                 $this->set_url_aprove_internas($totalInternas); 
            }
        }
    
        $ids = AuthComponent::user("email") != "jotsuar@gmail.com" ?  AuthComponent::user("users") : "" ;
        if (!empty($ids) && !$this->request->is("ajax") && $totalFacts == 0 && AuthComponent::user("role") == "Gerente General" ) {
            $users = explode(",", $ids);
            if (!empty($users)) {
                $this->loadModel("Approve");

                $datePres = date("Y-m-d H:i:s",strtotime("-2 hour"));

                $total    = $this->Approve->find("count",["conditions" => ["Approve.state" => 0, "Approve.user_id" => $users, "Approve.created <" => $datePres, 'Approve.type_aprovee' => 1 ]]);

                if ($total > 0) {   
                    $actions = ["logout","aprovee"];
                    $this->Session->setFlash(__('Tienes pendientes '.$total. ' cotizaciones por aprobar'),'Flash/error');
                    if (!in_array($this->request->action, $actions)) {
                        if (AuthComponent::user("role") != "Gerente General") {
                            $this->Session->write("REDIRECT",true);
                            $this->redirect(["controller"=>"ProspectiveUsers","action"=>"aprovee"]);
                        }
                    }elseif($this->request->action == "aprovee"){
                        $this->Session->write("REDIRECT",true);
                    }
                }else{
                    $this->Session->write("REDIRECT",null);
                }

            }
        }elseif (!$this->request->is("ajax") && !$this->request->is("post") && $totalFacts > 0 && AuthComponent::user("role") != "Gerente General") {
            $this->Session->write("REDIRECT",true);
            $this->set_url_aprove_facts($totalFacts); 
        }elseif (!$this->request->is("ajax") && $totalInternas > 0) {
            $this->Session->write("REDIRECT",true);
            $this->set_url_aprove_internas($totalInternas); 
        }else{
            $this->Session->write("REDIRECT",null);
        }
    }

    public function getUsersByCity($city){
        return explode(",", $this->Config->field("users_$city",["id" => 1]));
    }


    public function validateCotizacion(){
        
        if(!AuthComponent::user("id") || ( AuthComponent::user("id") && $this->request->is("ajax") ) ){
            return false;
        }

        $this->loadModel("ProspectiveUser");
        $this->ProspectiveUser->unBindModel([
            "belongsTo" => array_keys($this->ProspectiveUser->belongsTo),
            "hasMany" => array_keys($this->ProspectiveUser->hasMany),
            "hasAndBelongsToMany" => array_keys($this->ProspectiveUser->hasAndBelongsToMany),
        ]);
        $this->ProspectiveUser->setAttentioData();
        $flujos = $this->ProspectiveUser->findAllByUserIdAndStateFlow(AuthComponent::user("id"),2);
        $datos  = [];

        if (!empty($flujos)) {
            $this->loadModel("Approve");
            foreach ($flujos as $key => $value) {
                $fechaCotizado = $value["AtentionTime"]["limit_cotizado_date"]." ".$value["AtentionTime"]["limit_cotizado_time"];
                $this->Approve->recursive = 1;
                if (strtotime($fechaCotizado) < strtotime(date("Y-m-d H:i:s")) && empty($this->Approve->findByStateAndFlujoId(0,$value["ProspectiveUser"]["id"])) ) {
                    $datos[] = $value["ProspectiveUser"]["id"];
                }
            }

            if (!empty($datos)) {
                $actions = ["index","logout","adviser","add","view"];
                $this->Session->setFlash(__('Los siguientes flujos deben pasar a cotizado para poder continuar: '.implode(", ", $datos)),'Flash/error');
                if (!in_array($this->request->action, $actions) && !in_array(strtolower($this->request->controller), ["prospective_users","quotations"])) {

                    $this->redirect(["controller"=>"ProspectiveUsers","action"=>"adviser"]); 
                }
            }

        }
    }

    public function validateShipping($return = false){
        // return false;
        if(!$return && ( $this->request->action == 'change' || !AuthComponent::user("id") || ( AuthComponent::user("id") && $this->request->is("ajax") ) ) ){
            return false;
        }

        $ids    = Configuration::get_flow("user_shipping");
        $hours  = Configuration::get_flow("hours_shipping");

        if (!is_null($ids) && $ids > 0 && $ids == AuthComponent::user("id") && $hours > 0 ) {
            $this->loadModel("Shipping");

            $dateCompare = date("Y-m-d H:i:s");
            $this->Shipping->recursive = -1;
            $pendingDispatches = $this->Shipping->findAllByState([0,1]);

            if (!empty($pendingDispatches)) {
                $flujos    = [];
                $shippings = [];

                foreach ($pendingDispatches as $key => $value) {
                    if ( strtotime($value["Shipping"]["created"]. " + $hours hour") < strtotime($dateCompare) || strtotime($value["Shipping"]["date_preparation"]. " + $hours hour") < strtotime($dateCompare) ) {
                        $flujos[]    = $value["Shipping"]["id"];
                        $shippings[] = $value["Shipping"]["id"];
                    }
                }

                if ($return) {
                    $this->set("flujos_without_shipping",$shippings);
                    return $flujos;
                }

                if (!empty($flujos)) {

                    $actions = ["index","logout","view",'change'];

                    $this->Session->setFlash(__('Tienes pendientes '.count($flujos). ' solicitudes de facturación y/o despacho por gestionar pertenecientes a flujos'),'Flash/error');
                    if ( !in_array(strtolower($this->request->controller), ["shippings","orders",'users','assists']) || (
                        in_array(strtolower($this->request->controller), ["shippings","orders",'users']) && !in_array($this->request->action, $actions)
                    ) ) {
                        $this->redirect(["controller"=>"shippings","action"=>"index"]); 
                    }
                }
            }

        }

    }

    public function validateBilling(){

        if(!AuthComponent::user("id") || ( AuthComponent::user("id") && $this->request->is("ajax") ) ){
            return false;
        }

        $config = $this->Config->findById(1);

        $usersBillingMed = explode(",", $config["Config"]["users_billing"]); 
        $usersBillingBog = explode(",", $config["Config"]["users_billing_bog"]); 

        $idsMed = $this->getUsersByCity("med");
        $idsBog = $this->getUsersByCity("bog");

        $ids    = [];

        if (in_array(AuthComponent::user("id"), $usersBillingMed) && in_array(AuthComponent::user("id"), $usersBillingBog) ) {
            $ids = array_merge($idsMed,$idsBog);
        }elseif(in_array(AuthComponent::user("id"), $usersBillingMed)){
            $ids = $idsMed;
        }elseif (in_array(AuthComponent::user("id"), $usersBillingBog)) {
            $ids = $idsBog;
        }

        if(!empty($ids)){
            $this->loadModel("ProspectiveUser");
            $flujosBilling = $this->ProspectiveUser->find("all",["conditions" => ["ProspectiveUser.status_bill" => 0, "ProspectiveUser.user_id" => $ids ],"recursive" => -1]);

            if (!empty($flujosBilling)) {
                $flujos = Set::extract($flujosBilling,"{n}.ProspectiveUser.id");
                $actions = ["logout","pending_dispatches","status_dispatches_finish","status_dispatches"];
                $this->Session->setFlash(__('Los siguientes flujos se deben facturar para poder continuar: '.implode(", ", $flujos)),'Flash/error');
                if (!in_array($this->request->action, $actions)) {
                    $this->redirect(["controller"=>"ProspectiveUsers","action"=>"status_dispatches"]);
                }
            }
        }
    }


    public function getTotalInventoryProduct($productId, $return = false){

        if (is_null($productId) || $productId == false) {
            return null;
        }


        $this->loadModel("Inventory");       
        $productData = $this->Inventory->Product->find("first",["recursive" => -1, "conditions" => ["Product.id" => $productId] ])["Product"];

        $totalEntTrn = $this->Inventory->field("SUM(quantity)",["product_id" => $productId, "state" => 1, "type" => 1, "warehouse" => "Transito"]);
        $totalSalTrn = $this->Inventory->field("SUM(quantity)",["product_id" => $productId, "state" => 1, "type" => 2, "warehouse" => "Transito"]);

        $productData["quantity_back"]   = $totalEntTrn - $totalSalTrn;
        $productData["total"] = $productData["quantity"] + $productData["quantity_back"];

        if ($return) {
            return $productData["total"];
        }

        return $productData;
    }

    public function validateQuiz(){
        if (!AuthComponent::user("id")) {
            return false;
        }
        $this->loadModel("Quiz");
        
        $options = array('conditions' => array('Quiz.type' => 0, "Quiz.state" => 1 , "Quiz.date_ini <=" => date("Y-m-d"), "Quiz.date_end >=" =>  date("Y-m-d")  ),"recursive" => 1);
        $quiz = $this->Quiz->find('first', $options);
        if (!empty($quiz)) {
            $this->loadModel("Result");
            $questions = Set::extract($quiz["Question"],"{n}.id");
            $responds = $this->Result->find("count",["conditions" => ["Result.user_id" => AuthComponent::user("id"), "Result.question_id" => $questions ]]);
            if ($responds == 0) {
                $this->set("quizDataUser",$quiz["Quiz"]);
            }
        }
    }

    private function getUrl(){
        if(in_array($_SERVER['REMOTE_ADDR'],['127.0.0.1', '::1'])){

            $exec = exec("hostname"); //the "hostname" is a valid command in both windows and linux
            $hostname = trim($exec); //remove any spaces before and after
            $ip = gethostbyname($hostname); 

            $ip = file_get_contents('https://api.ipify.org');

            if ($ip == "201.184.244.178") {
                $return = "http://192.168.1.50:8080/";
            }else{
                $return = "http://201.184.244.178:8080/";
            }
        }else{
            $return = "http://201.184.244.178:8080/";
        }
        return $return;
    }

    public function getDataDocument($params){
        $HttpSocket = new HttpSocket();
        $URL        = $this->getUrl()."crmconnect/api/envoices";
        try {
            $resultsMsg  = $HttpSocket->post($URL,$params);
            $resp        = json_decode($resultsMsg->body);

            return $resp->result;
            
        } catch (Exception $e) {
            var_dump($e->getMessage());
        } 
    }

    public function getDocumentAuto($params){
        return $this->postWoApi('documents',$params);
        $HttpSocket = new HttpSocket();
        $URL        = $this->getUrl()."/crmconection/pages/get_facturas_data.json";
        try {
            $resultsMsg  = $HttpSocket->post($URL,$params);
            $resp        = json_decode($resultsMsg->body);
            return $resp->result;
            
        } catch (Exception $e) {
            var_dump($e->getMessage());
        } 
        return [];
    }

    public function postWoApi($params,$method){

        if(!in_array($method,['documents','customer','customers','debts','receipts','get_cost','inventory_exists'])){
            return [];
        }
        $HttpSocket = new HttpSocket();
        $URL        = $this->getUrl()."crmconnect/api/${method}";
        try {
            $resultsMsg  = $HttpSocket->post($URL,$params);
            $resp        = json_decode($resultsMsg->body);

            return $resp->result;
            
        } catch (Exception $e) {
            $this->log($e->getMessage(),"debug");
        } 
        return [];
    }

    public function object_to_array($data) {

        if (is_array($data) || is_object($data))
        {
            $result = array();
            foreach ($data as $key => $value)
            {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }


    public function save_data_document($flujo,$dataDocument){
        $this->loadModel("ProspectiveUser");
        $this->loadModel("Salesinvoice");

        $prospectiveData["ProspectiveUser"]["id"]                = $flujo;
        $prospectiveData["ProspectiveUser"]["bill_code"]         = $dataDocument->datos_factura->prefijo." ".$dataDocument->datos_factura->Id;

        $existeFact = $this->ProspectiveUser->find("count",["conditions"=>["ProspectiveUser.id" => $flujo, "ProspectiveUser.bill_code" => $prospectiveData["ProspectiveUser"]["bill_code"] ]]);

        $existeFactInvoice = $this->Salesinvoice->find("count",["conditions"=>["Salesinvoice.prospective_user_id" => $flujo, "Salesinvoice.bill_code" => $prospectiveData["ProspectiveUser"]["bill_code"] ]]);

        if ($existeFact > 0 || $existeFactInvoice > 0) {
            return null;
        }

        $prospectiveData["ProspectiveUser"]["bill_file"]         = null;
        $prospectiveData["ProspectiveUser"]["bill_text"]         = json_encode($dataDocument);

        if (!empty($dataDocument) && isset($dataDocument->valores_factura) && !empty($dataDocument->valores_factura)) {
            foreach ($dataDocument->valores_factura as $key => $value) {
                if (!is_null($value->IdClasificación)) {
                    $prospectiveData["ProspectiveUser"]["bill_value"] = floatval($value->Crédito);
                }
                if (is_null($value->IdClasificación)) {
                    $prospectiveData["ProspectiveUser"]["bill_value_iva"] = floatval($value->Débito);
                }
            }
        }else{
            return null;
        }

        if (floatval($prospectiveData["ProspectiveUser"]["bill_value"]) == 0 || floatval($prospectiveData["ProspectiveUser"]["bill_value_iva"]) == 0 ) {
            return null;
        }

        if (!empty($dataDocument) && isset($dataDocument->datos_factura)) {
            $prospectiveData["ProspectiveUser"]["bill_date"] = date("Y-m-d",strtotime($dataDocument->datos_factura->Fecha));
            $prospectiveData["ProspectiveUser"]["bill_user"] = $this->ProspectiveUser->User->field("id",["identification"=>$dataDocument->datos_factura->IdVendedor]) ;

            if (is_null($prospectiveData["ProspectiveUser"]["bill_user"])) {
                return null;
            }

        }else{
            return null;
        }

        $prospective = $this->ProspectiveUser->findById($prospectiveData['ProspectiveUser']['id']);

        if(!empty($prospective["ProspectiveUser"]["bill_code"])){
            
            $salesinvoiceData = $this->request->data["ProspectiveUser"];
            $salesinvoiceData["prospective_user_id"] = $salesinvoiceData["id"];
            $salesinvoiceData["user_id"] = $salesinvoiceData["bill_user"];
            unset($salesinvoiceData["id"]);
            $this->Salesinvoice->create();
            $this->Salesinvoice->save($salesinvoiceData);
            if (!empty($dataDocument) && isset($dataDocument->productos_factura) && !empty($dataDocument->productos_factura)) {
                $this->discountInventoryWo($dataDocument->productos_factura,$prospectiveData["ProspectiveUser"]["id"]);
            }
        }else{
            $prospective["ProspectiveUser"]["bill_value"]       = floatval($prospectiveData["ProspectiveUser"]["bill_value"]);
            $prospective["ProspectiveUser"]["bill_value_iva"]   = floatval($prospectiveData["ProspectiveUser"]["bill_value_iva"]);
            $prospective["ProspectiveUser"]["bill_code"]        = $prospectiveData["ProspectiveUser"]["bill_code"];
            $prospective["ProspectiveUser"]["bill_user"]        = $prospectiveData["ProspectiveUser"]["bill_user"];
            $prospective["ProspectiveUser"]["bill_date"]        = $prospectiveData["ProspectiveUser"]["bill_date"];
            $prospective["ProspectiveUser"]["bill_file"]        = $prospectiveData["ProspectiveUser"]["bill_file"];
            $prospective["ProspectiveUser"]["bill_text"]        = $prospectiveData["ProspectiveUser"]["bill_text"];
            $prospective["ProspectiveUser"]["status_bill"]      = 0;
            $prospective["ProspectiveUser"]["updated"]          = date("Y-m-d H:i:s");
            
            if ($this->ProspectiveUser->save($prospective)) {
                if (!empty($dataDocument) && isset($dataDocument->productos_factura) && !empty($dataDocument->productos_factura)) {
                    $this->discountInventoryWo($dataDocument->productos_factura,$prospectiveData["ProspectiveUser"]["id"]);
                }
            }
        }
        $this->validateBillFinal($flujo,$prospectiveData["ProspectiveUser"]["bill_value"]);

    }

    public function validateBillFinal($flujo,$valorFactura){
        $this->loadModel("ProspectiveUser");
        $id_etapa_cotizado          = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($flujo);
        $datosFlowstage             = $this->ProspectiveUser->FlowStage->get_data($id_etapa_cotizado);
        if (is_numeric($datosFlowstage['FlowStage']['document'])) {
            $quotation = $datosFlowstage['FlowStage']['document'];
            $this->loadModel("Quotation");
            $valueQuotation = $this->Quotation->field("total",["id" => $quotation]);
            $prospective = $this->ProspectiveUser->findById($flujo);
            if (floatval($valueQuotation) == $valorFactura ) {
                $prospective["ProspectiveUser"]["bill_state"] = 1;
                $this->ProspectiveUser->save($prospective["ProspectiveUser"]);
            }else{
                $totalFactura = $prospective["ProspectiveUser"]["bill_value"];
                $this->loadModel("Salesinvoice");
                $this->Salesinvoice->recursive = -1;
                $totalInvoices = $this->Salesinvoice->find("all",["conditions"=>["Salesinvoice.prospective_user_id"=>$flujo]]);

                if(count($totalInvoices) > 0){
                    foreach ($totalInvoices as $key => $value) {
                        $totalFactura+=$value["Salesinvoice"]["bill_value"];
                    }
                    if ( round($totalFactura) == round(floatval($valueQuotation)) ) {
                        $prospective["ProspectiveUser"]["bill_state"] = 1;
                        $this->ProspectiveUser->save($prospective["ProspectiveUser"]);
                    }
                }

            }
        }
    }

    private function discountInventoryWo($products,$flujo){
        $id_etapa_cotizado          = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($flujo);
        $datosFlowstage             = $this->ProspectiveUser->FlowStage->get_data($id_etapa_cotizado);
        if (is_numeric($datosFlowstage['FlowStage']['document'])) {
            $quotation = $datosFlowstage['FlowStage']['document'];
            $this->loadModel("Product");
            foreach ($products as $key => $value) {
                $productId = $this->Product->field("id",["LOWER(part_number)" => strtolower($value->CódigoInventario) ]);

                if (is_null($productId) || $productId == false) {
                    continue;
                }

                $productInventoryData = $this->getTotalInventoryProduct($productId);

                if ($value->Bodega == "MED" ) {
                    if ($productInventoryData["quantity"] >= intval($value->Cantidad)) {
                        $bodega = "Medellín";
                    }else{
                        $bodega = "Bogotá";
                    }                        
                }else{
                    if ($productInventoryData["quantity_bog"] >= intval($value->Cantidad)) {
                        $bodega = "Bogotá";
                    }else{
                        $bodega = "Medellín";
                    } 
                }

                $productData    = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->findAllByProductIdAndQuotationIdAndBiiled($productId,$quotation,0);
                $totalQuantity  = 0;

                foreach ($productData as $keyProduct => $valueProduct) {
                    $totalQuantity+=$valueProduct["QuotationsProduct"]["quantity"];
                }

                if (count($productData) == 1 && $totalQuantity == intval($value->Cantidad) ) {
                    $productData[0]["QuotationsProduct"]["biiled"] = 2;
                    $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->save($productData[0]["QuotationsProduct"]);
                }elseif (count($productData) > 1 && $totalQuantity == intval($value->Cantidad)) {
                    foreach ($productData as $keyProduct => $valueProduct) {
                        $valueProduct["QuotationsProduct"]["biiled"] = 2;
                        $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->save($productData["QuotationsProduct"]);
                    }
                }else{
                    foreach ($productData as $keyProduct => $valueProduct) {
                        if ($valueProduct["QuotationsProduct"]["quantity"] == intval($value->Cantidad)) {
                            $valueProduct["QuotationsProduct"]["biiled"] = 2;
                            $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->save($productData["QuotationsProduct"]);
                        }
                    }
                }

                $this->loadModel("Inventory");
                $inventory = array(
                    "Inventory" => array(
                        "product_id"    => $productId,
                        "quantity"      => intval($value->Cantidad),
                        "warehouse"     => $bodega,
                        "type"          => Configure::read("TYPES_MOVEMENT.SALIDA_INVENTARIO"),
                        "type_movement" => Configure::read("INVENTORY_TYPE.SALIDA_VENTA_NORMAL"),
                        "reason"        => Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.SALIDA_VENTA_NORMAL")),
                        "user_id"       => AuthComponent::user("id"),
                        "prospective_user_id" => $flujo
                    )
                );
                $this->Inventory->create();
                $this->Inventory->save($inventory);
                $this->loadModel("ProductsLock");
                $bloqueos = $this->ProductsLock->find("all",["recursive" => -1, "conditions" => ['ProductsLock.state' => 1, 'ProductsLock.product_id' => $productId, 'ProductsLock.prospective_user_id' => $flujo]]);

                if(!empty($bloqueos)){
                    foreach ($bloqueos as $keyBloqueo => $valueBloqueo) {
                        $valueBloqueo["ProductsLock"]["state"] = 4;
                        $valueBloqueo["ProductsLock"]["unlock_date"] = date("Y-m-d H:i:s");
                        $this->ProductsLock->save($valueBloqueo);
                    }
                }              
            }

        }
    }

    private function countDataProducts($arrDatas, $productId){
        $count = 0;
        foreach ($arrDatas as $key => $arrProducts) {
            foreach ($arrProducts as $keyProducts => $value) {
                if ($value == $productId) {
                    $count++;
                }
            }
        }
        return $count;
    }

    public function sugestions($product_id = 2){
        $this->loadModel("Quotation");
        $lastQt = $this->Quotation->QuotationsProduct->find("list",["fields" => ["quotation_id", "quotation_id"], "conditions" => ["product_id" => $product_id], "limit" => 10, "order" => ["created" => "DESC"]  ]);

        $productsFinals = [];

        if (!empty($lastQt)) {
            $this->Quotation->unBindModel(["belongsTo" => array_keys($this->Quotation->belongsTo)]);
            $quotations = $this->Quotation->findAllById($lastQt);

            $dataFilter = [];
            $allProducts = [];

            foreach ($quotations as $key => $value) {
                $products = Set::extract($value["QuotationsProduct"],"{n}.product_id");
                if (count($products) > 1) {
                    $dataFilter[$value["Quotation"]["id"]] = $products;
                    $allProducts = array_merge($allProducts,$products);                                       
                }
            }

            usort($dataFilter, function($a, $b) {
                return count($b) - count($a);
            });

            $allProducts = array_unique($allProducts);
            $exists      = [];

            foreach ($allProducts as $key => $value) {
                if ($value != 2) {
                    $exists[$value] = $this->countDataProducts($dataFilter,$value);
                }
            }
            arsort($exists);
            $copyExists = $exists;

            $idsFinals = [];

            if (count($copyExists) >= 2) {
                for ($i=0; $i < 2; $i++) { 
                    $keyFinal = array_search(max($copyExists), $copyExists);
                    unset($copyExists[$keyFinal]);
                    if ($keyFinal != $product_id) {
                        $idsFinals[] = $keyFinal;
                    }
                }
                $this->Quotation->QuotationsProduct->Product->recursive = -1;
                $productsFinals = $this->Quotation->QuotationsProduct->Product->findAllById($idsFinals);
            }else{
                $productsFinals = $this->Quotation->QuotationsProduct->Product->findAllById(array_keys($copyExists));                
            }
        }
        $inventarioWo     = $this->getValuesProductsWo($productsFinals);
        return compact('inventarioWo','productsFinals');
    }

    public function getCantidades($partNumber){
        $HttpSocket = new HttpSocket();
        $URL        = $this->getUrl()."crmconnect/api/products";
        $params     = ["part_number" => is_array($partNumber) ? implode(",", $partNumber) : $partNumber ];
        $params["part_number"] = base64_encode($params["part_number"]);
        try {
            $resultsMsg  = $HttpSocket->post($URL,$params);
            $resp        = json_decode($resultsMsg->body);

            if (isset($resp->results)) {
                return is_array($partNumber) ? (array) $resp->results : (array) $resp->results;
            }
            
        } catch (Exception $e) {
            $this->log($e->getMessage(),"debug");
        } 
    }

    public function getPrices($productsData){
        $products = [];
        foreach ($productsData as $key => $value) {
            if ($key == "Reserva") {
                continue;
            }
            if (!empty($value) && $value["0"]["utilidad"] > 1) {
                $products[$key] = $value["0"]["precio"];
            }
        }
        return $products;
    }

    public function getCosts($productsData){
        $products = [];
        foreach ($productsData as $key => $value) {
            if ($key == "Reserva") {
                continue;
            }
            if (isset($value["0"]["costo"])) {
                $products[$key] = $value["0"]["costo"];
            }
        }
        return $products;
    }

    public function getValuesProductsWo($products,$getTotal = false){
        $partsData              = [];
        $parts                  = Set::extract($products,"{n}.Product.part_number");
        $partsString            = [];

        if (empty($parts)) {
            return [];
        }

        $this->loadModel("Product");

        foreach ($parts as $key => $value) {
            $value              = strtoupper($value);
            $partsString[] = "$value";
            $partsData[$value] = []; 
        }

        $inventarioWo = $this->getCantidades($partsString);

        if (!empty($inventarioWo)) {
            $reserva = [];
            // $reserva = $inventarioWo["Reserva"];
            // unset($inventarioWo["Reserva"]);
            foreach ($inventarioWo as $key => $value) {
                $precio = $value->costo*$value->utilidad;
                $partsData[strtoupper($value->part_number)][] = [ 
                    "bodega"    => $value->Codigo_Bodega, 
                    "total"     => intval($value->Existencia), 
                    "costo"     => round(floatval($value->costo),2),
                    "utilidad"  => round(floatval($value->utilidad),2), 
                    "precio"    => round(floatval($precio)) 
                ];

                $rest = $this->Product->updateAll(
                    array('Product.purchase_price_wo' => round(floatval($value->costo),2)),
                    array('UPPER(Product.part_number)' => strtoupper($value->part_number)),false
                );
            }
            $partsData["Reserva"] = [];
            // $partsData["Reserva"] = $this->object_to_array($reserva);
        }

        if ($getTotal) {
            $arrCopy    = $partsData;
            $partsData  = [];
            foreach ($arrCopy as $parte => $bodegas) {
                if ($parte === "Reserva" ) {
                    continue;
                }
                $total = 0;
                foreach ($bodegas as $key => $value) {
                    
                    $total+=$value["total"];
                }
                $partsData[$parte] = $total;
            }
        }

        return $partsData;
    }


    public function getTotalesStock($partsData){
        $arrCopy    = $partsData;
        $partsData  = [];
        foreach ($arrCopy as $parte => $bodegas) {
            if ($parte === "Reserva" ) {
                continue;
            }
            $total = 0;
            foreach ($bodegas as $key => $value) {
                $total+=$value["total"];
            }
            $partsData[$parte] = $total;
        }
        return $partsData;
    }

    /******* Whatsapp ******/

    public function setTextMsg($phone, $msg, $pre = false){
        
        $HttpSocket = new HttpSocket();
        $urlMsg     = Configure::read("Application.URL_WPP")."sendMessage?token=".Configure::read("Application.TOKEN_WPP");
        $urlCheck   = Configure::read("Application.URL_WPP")."checkPhone?token=".Configure::read("Application.TOKEN_WPP")."&phone=";

        try {
            $premsg         = !$pre ? "57" : "";
            $exists         = $HttpSocket->get($urlCheck.$premsg.$phone);
            $paramsMessage  = array(
                "phone" => $premsg.$phone,
                "body"  => $msg
            );

            if(isset($exists->body)){
                $resp = json_decode($exists->body);
                if($resp->result == "exists"){
                    $resultsMsg  = $HttpSocket->post($urlMsg,$paramsMessage);
                    $resp        = json_decode($resultsMsg->body);
                }
            }               
        } catch (Exception $e) {
            $this->log($e->getMessage());
            var_dump($e->getMessage());
        }

    }

    public function setFileMsg($phone, $msg, $file, $fileMsg){
        
        $HttpSocket = new HttpSocket();
        $urlMsg     = Configure::read("Application.URL_WPP")."sendFile?token=".Configure::read("Application.TOKEN_WPP");
        $urlCheck   = Configure::read("Application.URL_WPP")."checkPhone?token=".Configure::read("Application.TOKEN_WPP")."&phone=";

        $path_parts = pathinfo($file["name"]);

        try {
            $exists         = $HttpSocket->get($urlCheck.$phone);
            $paramsMessage  = array(
                "phone" => $phone,
                "body"  => $fileMsg,
                "filename" => time().".".$path_parts["extension"],
                "caption" => $msg,
            );

            if(isset($exists->body)){
                $resp = json_decode($exists->body);
                if($resp->result == "exists"){
                    $ruta_img = WWW_ROOT.'files/Whatsapp/';
                    if (!file_exists($ruta_img)) {
                        mkdir($ruta_img, 0777, true);
                    }
                    if(move_uploaded_file($file['tmp_name'], $ruta_img.$paramsMessage["filename"])) {
                        $paramsMessage["body"] = Router::url("/",true)."files/Whatsapp/".$paramsMessage["filename"];
                        $resultsMsg  = $HttpSocket->post($urlMsg,$paramsMessage);
                        $resp2       = json_decode($resultsMsg);
                        if(!in_array($path_parts["extension"], ["jpg","jpeg","png","gif"])){
                            $this->setTextMsg($phone,$msg,true);
                        }
                    }
                }
            }               
        } catch (Exception $e) {
            $this->log($e->getMessage());
            var_dump($e->getMessage());
        }
    }


    public function setFileMsgMasive($phone, $msg, $fileName, $fileMsgUrl){
        
        $HttpSocket = new HttpSocket();
        $urlMsg     = Configure::read("Application.URL_WPP")."sendFile?token=".Configure::read("Application.TOKEN_WPP");

        try {
            $paramsMessage  = array(
                "phone" => $phone,
                "body"  => $fileMsgUrl,
                "filename" => $fileName,
                "caption" => $msg,
            );
            $resultsMsg  = $HttpSocket->post($urlMsg,$paramsMessage);
            $resp2       = json_decode($resultsMsg);            
        } catch (Exception $e) {
            $this->log($e->getMessage());
            var_dump($e->getMessage());
        }
    }

    public function getConfig($campo){
        $this->loadModel("Config");
        return $this->Config->field($campo,["id" => 1]);
    }

    public function validateAddProducts(){
        $rolesAdd = $this->getConfig("roles_add");
        $rolesAdd = explode(",", $rolesAdd);
        return in_array(AuthComponent::user("role"), $rolesAdd);
    }

    public function validateEditProducts(){
        $rolesEdit = $this->getConfig("roles_edit");
        $rolesEdit = explode(",", $rolesEdit);
        return in_array(AuthComponent::user("role"), $rolesEdit) || AuthComponent::user('email') == 'ventas2@almacendelpintor.com';;
    }

    public function validateUnlockProducts(){
        $rolesUnlock = $this->getConfig("roles_unlock");
        $rolesUnlock = explode(",", $rolesUnlock);
        return in_array(AuthComponent::user("role"), $rolesUnlock);
    }

    public function getCategoriesForProduct($id_category){
        $categories = [$id_category];
        $categoriesFinal = $this->getCategoryFathers($categories,$id_category);

        $totalCats = count($categoriesFinal);

        foreach ($categoriesFinal as $key => $value) {
            $this->set("category".$totalCats."Select", $value);
            $totalCats--;
        }

        if(!empty($categoriesFinal)){
            $this->set("categoriesDataForEdit",true);
        }
    }

    private function getCategoryFathers($categories,$idCat){
        $this->loadModel("Category");
        $category = $this->Category->findById($idCat);
        if(!empty($category)){
            if($category["Category"]["category_id"] != "0"){
                $categories[] = $category["Category"]["category_id"];
                $categories = $this->getCategoryFathers($categories,$category["Category"]["category_id"]);
            }
        }
        return $categories;
    }

    public function getCategoryInfo($br = false, $ppalData = false){
        $this->loadModel("Category");
        $this->Category->unbindModel(["belongsTo" => ["FatherCategory"],"hasMany"=>["Product"]]);
        $conditions = ["Category.category_id"=>0];
        if (AuthComponent::user("role") == "Asesor Externo") {
            $this->loadModel("CategoriesUser");
            $categoriesUser = $this->CategoriesUser->find("list",["fields"=>["category_id","category_id"],"conditions" => ["user_id"=>AuthComponent::user("id")] ]);
            $conditions["Category.id"] = array_values($categoriesUser);
        }

        if (AuthComponent::user("role") != "Asesor Externo") {
            $categoriesCache = Cache::read("Categories");
            $categoriesTotal = $this->Category->find("count",["conditions"=>$conditions]);
            if (is_null($categoriesCache) || (!is_null($categoriesCache) && count($categoriesCache) < $categoriesTotal ) ) {
                $categories = $this->Category->find("all",["fields" => ["id","name","category_id"],"conditions"=>$conditions]);
                Cache::write("Categories",$categories);
            }else{
                $categories = $categoriesCache;
            }
        }else{
            $categories = $this->Category->find("all",["fields" => ["id","name","category_id"],"conditions"=>$conditions]);
        }

        $categories = $this->getAllData($categories,[],"",$br);
        $ppales = array("0" => "Principal sin categoría padre");
        $dataMargen = [];
        foreach ($categories as $key => $value) {
            $ppales[$value["id"]] = $value["name"];
            if($ppalData){
                $dataMargen[$value["id"]] = $this->Category->find("first",["recursive"=>-1,"conditions"=>["Category.id" => $value["id"]]])['Category'];
            }
        }

        if($ppalData){
            $this->set("dataMargen",$dataMargen);
        }
        return $ppales;
    }

    private function getAllData($response, $newData = array(), $name = "",$br = false){
        foreach ($response as $key => $value) {
            $children = $value["SubCategory"];
            unset($value["SubCategory"]);
            unset($value["parent"],$value["Category"]["category_id"]);
            $newData[] = $value["Category"];
            if($children != null){
                foreach ($children as $keyChildren => $valueChildren) {
                    $newData = $this->getOneData($valueChildren["id"],$newData, $value["Category"]["name"],$br);
                }
            }
        }
        return $newData;

    }

    public function getAllIdsCategories($id, $categories = array()){
        $this->loadModel("Category");

        if(empty($categories)){
            $categories = [$id];
        }
        $subCategories = $this->Category->find("all",["fields"=>["id"],"conditions"=>["category_id" => $id], "recursive" => -1 ]);

        if(!empty($subCategories)){
            foreach ($subCategories as $key => $value) {
                $categories[] = $value["Category"]["id"];
                $categories = $this->getAllIdsCategories($value["Category"]["id"], $categories);
            }
        }
        return $categories;
    }

    private function getOneData($id,$newData, $name,$br = false){
        $this->loadModel("Category");
        $this->Category->unbindModel(["belongsTo" => ["FatherCategory"],"hasMany"=>["Product"]]);
        $response = $this->Category->find("first",["fields" => ["id","name","category_id"],"conditions"=>["id"=>$id]]);
        $children = $response["SubCategory"];

        $separator = $br ? " --> " : " -> ";

        $response["Category"]["name"]   = $name.$separator.$response["Category"]["name"];
        unset($response["Category"]["category_id"]);
        if(!empty($children)){
            $newData = $this->getAllData([$response],$newData, $response["Category"]["name"],$br);
        }else{
            unset($response["SubCategory"]);
            $newData[] = $response["Category"];
        }
        return $newData;
    }

    public function validatePassword(){
        if (AuthComponent::user("role") == "Asesor Externo" || AuthComponent::user("role") == "Publicidad") {
            return false;
        }
        if(AuthComponent::user("id") && empty(AuthComponent::user("password_email"))){
            $actions = array("intro","profile");
            if(!$this->request->is("ajax") && !in_array($this->request->action, $actions))
            {
                $this->Session->setFlash('Se debe configurar la contraseña del correo para poder continuar','Flash/error');
                $this->redirect(array("controller"=>"users","action"=>"profile"));
            }
        }
    }

    public function emailClientFlujo($prospective_id){
        $this->loadModel("FlowStage");
        $datosP             = $this->FlowStage->ProspectiveUser->get_data($prospective_id);
        if ($datosP['ProspectiveUser']['contacs_users_id'] > 0) {
            $datosC         = $this->FlowStage->ProspectiveUser->ContacsUser->get_data($datosP['ProspectiveUser']['contacs_users_id']);
            $email          = $datosC['ContacsUser']['email'];
        } else {
            $datosC         = $this->FlowStage->ProspectiveUser->ClientsNatural->get_data($datosP['ProspectiveUser']['clients_natural_id']);
            $email          = $datosC['ClientsNatural']['email'];
        }
        return $email;
    }

    public function getDataCustomer($prospective_id, $total = null){
        $this->loadModel("FlowStage");
        $datosP             = $this->FlowStage->ProspectiveUser->get_data($prospective_id);
        if ($datosP['ProspectiveUser']['contacs_users_id'] > 0) {
            $datosC         = $this->FlowStage->ProspectiveUser->ContacsUser->get_data($datosP['ProspectiveUser']['contacs_users_id']);
            if (!is_null($total) && !empty($datosC["ClientsLegal"]) ) {
                $datosC["ContacsUser"]["name"].=" - ".$datosC["ClientsLegal"]["name"];
                $datosC["ContacsUser"]["identification"] = $datosC["ClientsLegal"]["nit"];
                $datosC["ContacsUser"]["legal"] = $datosC["ClientsLegal"]["name"];
                $datosC["ContacsUser"]["concesion"] = isset($datosC["Concession"]["name"]) && !empty($datosC["Concession"]["name"]) ? $datosC["Concession"]["name"] : '' ;
                $datosC = $datosC["ContacsUser"];
                $datosC["type_customer"] = "legal";
            }else{
                
                if(!empty($datosC)){
                    $datosC["ContacsUser"]["legal"] = $datosC["ClientsLegal"]["name"];
                    $datosC["ContacsUser"]["identification"] = $datosC["ClientsLegal"]["nit"];
                    $datosC = $datosC["ContacsUser"];
                    $datosC["type_customer"] = "legal";
                }
            }
        } else {
            $datosC         = $this->FlowStage->ProspectiveUser->ClientsNatural->get_data($datosP['ProspectiveUser']['clients_natural_id']);
            if(!empty($datosC)){
                $datosC = $datosC["ClientsNatural"];
                $datosC["type_customer"] = "natural";
            }
        }
        return $datosC;
    }


    public function getCagegoryData($category_id = 0, $categories = array(), $init = false){
        $this->loadModel("Category");
        $this->Category->unBindModel(["belongsTo" => ["FatherCategory"], "hasMany" => ["Product"]]);
        $this->Category->recursive = 1;

        if (AuthComponent::user("role") == "Asesor Externo") {
            $this->loadModel("CategoriesUser");
            $categoriesUser = $this->CategoriesUser->find("list",["fields"=>["category_id","category_id"],"conditions" => ["user_id"=>AuthComponent::user("id")] ]);
            $categorias = $this->Category->findAllByCategoryIdAndId($category_id,array_values($categoriesUser));
        }else{
            $categorias = $this->Category->findAllByCategoryId($category_id);
        }


        if($init){
            $categories[0][] = [ "id" => 0, "name" => "Principal sin subcategoría" ];
        }

        foreach ($categorias as $key => $value) {
            $categories[$category_id][] = $value["Category"];
            if(!empty($value["SubCategory"])){
                $categories = $this->getCagegoryData($value["Category"]["id"],$categories);
            }
        }
        return $categories;
    }

    public function getEstructure($parent_id = 0, $categories, $init = false){
        $categorias = array();

        if($init){
            $objStructure        = new Object();
            $objStructure->id    = 0;
            $objStructure->title = "Principal sin Categoría";
            $categorias[] = $objStructure;
        }

        if (!empty($categories[$parent_id])) {
            foreach ($categories[$parent_id] as $key => $value) {
                $objStructure        = new Object();
                $objStructure->id    = $value["id"];
                $objStructure->title = $value["name"];
                if(!empty($categories[$value["id"]])){
                    $objStructure->subs  = $this->getEstructure($value["id"],$categories);
                }
                $categorias[] = $objStructure;
            }
        }
        if(!empty($categorias)){
            return $categorias;
        }
    }

    public function sendEmailInformationQuotationNew($rutaURL,$copias_email = "",$codigoCotizacion, $textoCliente = null,$flowId,$emailClienteData,$name_cliente){
        $this->loadModel("FlowStage");
        $email_defecto              = [];
        $emailCliente               = array();
        $emailCliente[0]            = $emailClienteData;
        if ($copias_email != '') {
            $emails                 = explode(',',$copias_email);
            if (isset($emails[0])) {
                $emailCliente       = array_merge($emails, $emailCliente);
            } else {
                $emailCliente[1]    = $copias_email;
            }
        }
        $datosFlujo                 = $this->FlowStage->ProspectiveUser->get_data($flowId);
        $datos_asesor               = $this->FlowStage->ProspectiveUser->User->get_data($datosFlujo['ProspectiveUser']['user_id']);
        $requerimientoFlujo         = $this->FlowStage->find_reason_prospective($flowId);
        if (file_exists(WWW_ROOT.'/files/quotations/'.$codigoCotizacion.'.pdf')) {
            $options                    = array(
                'to'        => $emailCliente,
                'template'  => 'quote_sent',
                'cc'        => $email_defecto,
                'subject'   => 'Has recibido la cotización '.$codigoCotizacion.' de KEBCO AlmacenDelPintor.com',
                'vars'      => array('codigo' => $codigoCotizacion,'nameClient' => $name_cliente,'nameAsesor' => $datos_asesor['User']['name'],'requerimiento' => $requerimientoFlujo,'ruta'=>$rutaURL,"texto" => $textoCliente),
                'file'      => 'files/quotations/'.$codigoCotizacion.'.pdf'
            );
        } else {
            $options                    = array(
                'to'        => $emailCliente,
                'template'  => 'quote_sent',
                'cc'        => $email_defecto,
                'subject'   => 'Has recibido la cotización '.$codigoCotizacion.' de KEBCO AlmacenDelPintor.com',
                'vars'      => array('codigo' => $codigoCotizacion,'nameClient' => $name_cliente,'nameAsesor' => $datos_asesor['User']['name'],'requerimiento' => $requerimientoFlujo,'ruta'=>$rutaURL,"texto" => $textoCliente)
            );
        }
        if($datos_asesor["User"]["password_email"]){
            $email      = $datos_asesor["User"]["email"] == "jotsuar@gmail.com" ? "pruebascorreojs1@gmail.com" : $datos_asesor["User"]["email"];
            $this->sendMail($options,null, array("username" => $email, "password" => str_replace("@@KEBCO@@", "", base64_decode($datos_asesor["User"]["password_email"]))));
        }else{
            $this->sendMail($options);
        }
    }
    
    public function getCodeBillField($prospectiveData, $salesenvoice_id, $field){
        $this->loadModel("Salesinvoice");
        if($salesenvoice_id == 0){
            return $prospectiveData[$field];
        }else{
            $invoiceData = $this->Salesinvoice->findById($salesenvoice_id);
            if($field == "bill_user"){ $field = "user_id"; }
            return $invoiceData["Salesinvoice"][$field];
        }
    }

    public function configurations(){

        if(is_null(Cache::read("TRM")) || Cache::read("TRM") == false ){
            Cache::write("TRM", 3500);
        }

        if(is_null(Cache::read("FACTORUSA")) || Cache::read("FACTORUSA") == false ){
            Cache::write("FACTORUSA", 1.10);
        }

        if(is_null(Cache::read("FACTORCOP")) || Cache::read("FACTORCOP") == false ){
            Cache::write("FACTORCOP", 1.19);
        }

        $this->loadModel("ProspectiveUser");
        $this->ProspectiveUser->deleteAll(['ProspectiveUser.state_flow' => 0,'ProspectiveUser.user_id' => 0, 'ProspectiveUser.origin' => ''], false);

    }

    public function calculateDays($date_ini, $date_end){
        $date1 = new DateTime($date_ini);
        $date2 = new DateTime($date_end);
        $diff = $date1->diff($date2);
        return $diff->invert == 1 ? 0 : $diff->days;
    }

    public function getComissionPercentaje($days, $commsionData){

        $percentaje = 0;

        if(intval($commsionData["Commision"]["range_one_end"]) >= $days){
            $percentaje = floatval($commsionData["Commision"]["range_one_percentage"]);
        }elseif($commsionData["Commision"]["range_two_end"] >= $days){
            $percentaje = floatval($commsionData["Commision"]["range_two_percentage"]);
        }elseif($commsionData["Commision"]["range_three_end"] >= $days){
            $percentaje = floatval($commsionData["Commision"]["range_three_percentage"]);
        }elseif($commsionData["Commision"]["range_four_end"] >= $days){
            $percentaje = floatval($commsionData["Commision"]["range_four_percentage"]);
        }

        return $percentaje;
    }

    public function validateRolePayments(){
        $rolesPayments = array(Configure::read('variables.roles_usuarios.Contabilidad'),Configure::read('variables.roles_usuarios.Gerente General'),Configure::read("variables.roles_usuarios.Logística") );
        if(!in_array(AuthComponent::user('role'), $rolesPayments)){
            $this->Session->setFlash('Rol no permitido.','Flash/error');
            $return = $this->redirect(array('action' => 'home_adviser',"controller" => "Pages"));
        }
    }

    public function validateRoleAdmin(){
        $rolesPayments = array(Configure::read('variables.roles_usuarios.Contabilidad'),Configure::read('variables.roles_usuarios.Gerente General') );
        if(!in_array(AuthComponent::user('role'), $rolesPayments)){
            $this->Session->setFlash('Rol no permitido.','Flash/error');
            $return = $this->redirect(array('action' => 'home_adviser',"controller" => "Pages"));
        }
    }

    public function validateRoleDispatches(){
        $rolesDispatches = array( Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),
                                Configure::read('variables.roles_usuarios.Gerente General'),
                                Configure::read('variables.roles_usuarios.Logística'),
                                Configure::read('variables.roles_usuarios.Servicio al Cliente') );
        if(!in_array(AuthComponent::user('role'), $rolesDispatches)){
            $this->Session->setFlash('Rol no permitido.','Flash/error');
            $return = $this->redirect(array('action' => 'home_adviser',"controller" => "Pages"));
        }
    }

    public function validateRoleImports(){
        $return = false;
        $rolesImports = array( Configure::read('variables.roles_usuarios.Gerente General'),
                                Configure::read('variables.roles_usuarios.Logística'));
        if(!in_array(AuthComponent::user('role'), $rolesImports)){
            $this->Session->setFlash('Rol no permitido.','Flash/error');
            $return = $this->redirect(array('action' => 'home_adviser',"controller" => "Pages"));
        }
        return $return;
    }

    public function forgeSSL(){
       if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
           header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
           exit;
       }
    }


    public function validateSessionActive(){
        if (!AuthComponent::user('id')) {
            $arrayControllerStyle                   = array('css','js','Quotations','Quizzes','quizzes','quotations','Pqrs','pqrs','clientes');
            if (!in_array($this->request->params['controller'], $arrayControllerStyle)) {
                if (!in_array($this->request->params['action'], ['login','view','respond','register','bot_info'])) {
                    $this->Session->setFlash('La sesión se ha perdido, por favor vuelvete a loguear','Flash/error');
                }
            }
        }elseif(!$this->request->is("ajax") && !in_array(AuthComponent::user("id"),["1376","111"]) && !in_array(AuthComponent::user("role"),["Gerente General","Contabilidad"]) && !in_array(strtolower($this->request->params["controller"]), ['assists','js','css']) ){
            $this->loadModel('Assist');
            $assist = $this->Assist->find("first",["conditions" => ["Assist.user_id"=>AuthComponent::user("id"), "DATE(Assist.created)" => date("Y-m-d") ] ]);
            if(empty($assist)){
                $this->Session->setFlash('Debes registrar el ingreso al sistema','Flash/error');
                $this->redirect(array('action' => 'add',"controller" => "assists"));
            }
        }
    }

    public function validateDatesForReports(){
        $return = null;
        if(empty($this->request->query["ini"]) || empty($this->request->query["end"]) ){
            $this->Session->setFlash('Para acceder a los informes se debe seleccionar una fecha inicio y una fecha fin.','Flash/error');
            $return = $this->redirect(array('action' => 'home_adviser',"controller" => "Pages"));
        }else{
            $ini = $this->request->query["ini"];
            $end = $this->request->query["end"];
            if(!$this->validateDate($ini) || !$this->validateDate($end)){
                $this->Session->setFlash('Por favor verifique las fechas que tengan un formato válido (YYYY-mm-dd)','Flash/error');
                $return = $this->redirect(array('action' => 'home_adviser',"controller" => "Pages"));
            }elseif(strtotime($ini) > strtotime($end)){
                $this->Session->setFlash('Por favor verifique las fechas, la fecha inicio no puede ser mayor a la fin','Flash/error');
                $return = $this->redirect(array('action' => 'home_adviser',"controller" => "Pages"));
            }else{
                $this->set("fechaInicioReporte", $ini);
                $this->set("fechaFinReporte", $end);
            }
        }
        return $return;
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function getName($texto){
        $texto = explode(" ", $texto);
        if (isset($texto[1])) {
            $texto = $texto[0];
        }
        return $texto;
    }

	public function beforeRender(){
        if (AuthComponent::user('id')) {
            $this->loadModel('ManagementNotice');
            $this->loadModel('User');
            $this->loadModel("Config");
            $state_conection                        = $this->User->consult_state_conection();
            $notices_gerencia                       = array();
            if ($state_conection == Configure::read('variables.state_enabled')) {
                $consult_notice_conection           = $this->User->consult_notice_conection();
                if ($consult_notice_conection == Configure::read('variables.state_enabled')) {
                    $notices_gerencia              = $this->ManagementNotice->notes_enabled();
                }
            } else {
                $state_notice_loguin               = $this->User->consult_notice_loguin();
                if ($state_notice_loguin == Configure::read('variables.state_enabled')) {
                    $notices_gerencia              = $this->ManagementNotice->notes_enabled();
                }
                $this->updateStateConectionTrue(AuthComponent::user('id'));
            }
            $this->validateQuiz();

            $usersApprovePermission = $this->User->find("list",["fields" => ["id","id"],"conditions" => ["User.users !=" => NULL] ]);
            $userExtenalValid       = $this->Config->field("users_external",["id"=>1]); 
            if (!empty($userExtenalValid) && !is_null($userExtenalValid)) {
                $userExtenalValid = in_array(AuthComponent::user("id"), explode(",", $userExtenalValid));
                if ($userExtenalValid) {
                    $usersApprovePermission[AuthComponent::user("id")] = AuthComponent::user("id");
                }
            }

            $this->set(compact('notices_gerencia','usersApprovePermission'));

        }
    }

    public function sendEmailInformationQuotation($rutaURL,$copias_email,$codigoCotizacion, $textoCliente = null){

        $this->loadModel("Quotation");
        $this->loadModel("User");
        $this->loadModel("FlowStage");

        try {
            
            $copias_email               = trim($copias_email);
        
            $emailCliente               = array();
            $emailCliente[0]            = $this->emailClientFlujo($this->request->data['FlowStage']['flujo_id']);
            if ($copias_email != '') {
                $emails                 = explode(',',$copias_email);
                if (isset($emails[0])) {
                    $emailCliente       = array_merge($emails, $emailCliente);
                } else {
                    $emailCliente[1]    = $copias_email;
                }
            }
            $rutaNormal = $rutaURL;
            $rutaURL.="?e=".$this->encryptString(implode(",", $emailCliente));
            $acopleUrl = "?e=".$this->encryptString(implode(",", $emailCliente));
            
            $name_cliente               = $this->nameClientFlujo($this->request->data['FlowStage']['flujo_id']);
            $datosFlujo                 = $this->FlowStage->ProspectiveUser->get_data($this->request->data['FlowStage']['flujo_id']);
            $datos_asesor               = $this->FlowStage->ProspectiveUser->User->get_data($datosFlujo['ProspectiveUser']['user_id']);
            $requerimientoFlujo         = $this->FlowStage->find_reason_prospective($this->request->data['FlowStage']['flujo_id']);

            $email_defecto              = [];

            if (!empty($datos_asesor["User"]["copias"])) {
                $copias = $this->User->find("all",["recursive" => -1, "fields" => ["User.email"], "conditions" => ["User.id" => explode(",", $datos_asesor["User"]["copias"]) ] ]);
                if (!empty($copias)) {
                    $copias         = Set::extract($copias,"{n}.User.email");
                    $email_defecto  = $copias;
                }
            }else{
                $email_defecto              = Configure::read('variables.emails_defecto');
                if (in_array($datos_asesor["User"]["email"], ["ventas@kebco.co","gestion@kebco.co"])) {
                    unset($email_defecto[1]);
                }
            }

            $email_defecto[]            = $datos_asesor["User"]["email"];
            $emailCliente               = array_merge($email_defecto, $emailCliente);
            $emailCliente[]             = AuthComponent::user('email');
            if (file_exists(WWW_ROOT.'/files/quotations/'.$codigoCotizacion.'.pdf')) {
                $options                    = array(
                    'to'        => $emailCliente,
                    'template'  => 'quote_sent',
                    'cc'        => $email_defecto,
                    'subject'   => 'Has recibido la cotización '.$codigoCotizacion.' de KEBCO AlmacenDelPintor.com',
                    'vars'      => array('codigo' => $codigoCotizacion,'nameClient' => $name_cliente,'nameAsesor' => $datos_asesor['User']['name'],'requerimiento' => $requerimientoFlujo,'ruta'=>$rutaURL,"texto" => $textoCliente,"quotationID" => $this->Quotation->field("id",["codigo" => $codigoCotizacion ])),
                    //'file'        => 'files/quotations/'.$codigoCotizacion.'.pdf'
                );
            } else {
                $options                    = array(
                    'to'        => $emailCliente,
                    'template'  => 'quote_sent',
                    'cc'        => $email_defecto,
                    'subject'   => 'Has recibido la cotización '.$codigoCotizacion.' de KEBCO AlmacenDelPintor.com',
                    'vars'      => array('codigo' => $codigoCotizacion,'nameClient' => $name_cliente,'nameAsesor' => $datos_asesor['User']['name'],'requerimiento' => $requerimientoFlujo,'ruta'=>$rutaURL,"texto" => $textoCliente,"quotationID" => $this->Quotation->field("id",["codigo" => $codigoCotizacion ]))
                );
            }

            $this->loadModel("Quotation");
            $nameQt         = $this->Quotation->field("name",["codigo" => $codigoCotizacion ]);
            $datosCliente   = $this->getDataCustomer($this->request->data['FlowStage']['flujo_id']);

            try {
                $this->sendQoutationWhatsapp($datosCliente,trim($datos_asesor["User"]["name"]),$options["subject"], $rutaNormal,$nameQt,$acopleUrl, trim($datos_asesor["User"]["cell_phone"]),trim($codigoCotizacion) );
                
            } catch (Exception $e) {
                $this->log($e->getMessage(),"debug");
            }

            if(!empty($datos_asesor["User"]["password_email"])){
                $email      = $datos_asesor["User"]["email"] == "jotsuar@gmail.com" ? "pruebascorreojs1@gmail.com" : $datos_asesor["User"]["email"];
                $password   = str_replace("@@KEBCO@@", "", base64_decode($datos_asesor["User"]["password_email"]) );
                $config     = array("username" => $email, "password" => $password);

                $this->sendMail($options, null, $config);
            }else{
                $this->sendMail($options);
            }

        } catch (Exception $e) {
            
        }

        
        
               
    }

    public function updateStateConectionTrue($user_id){
        $datos['User']['id']                    = $user_id;
        $datos['User']['state_conection']       = 1;
        $this->User->save($datos);
    }

    public function updateStateConectionFalse($user_id){
        $datos['User']['id']                    = $user_id;
        $datos['User']['state_conection']       = 0;
        $this->User->save($datos);
    }

    public function sendMail($options = array(), $brand = null, $config = null){
        $whitelist = array(
            '127.0.0.1',
            '::1'
        );
        if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            $options["to"] = "jotsuar@gmail.com";
            $options["cc"] = "jotsuar@gmail.com";
            $options["cco"] = "jotsuar@gmail.com";
        }

        if(!empty($options["to"])){
            if(is_array($options["to"]) && ( in_array("gerencia@almacendelpintor.com", $options["to"] ) || in_array("ceo@kebco.co", $options["to"] ) ) ){
                $copyTo         = $options["to"];
                $options["to"]  = [];
                foreach ($copyTo as $key => $value) {
                    if($value != 'gerencia@almacendelpintor.com' && $value != 'ceo@kebco.co' ){
                        $options["to"][] = $value;
                    }
                }
            }elseif($options["to"] == "gerencia@almacendelpintor.com" || $options["to"] == "ceo@kebco.co"){
                return false;
            }
        }

        if(!empty($options["cc"])){
            if(is_array($options["cc"]) && (in_array("gerencia@almacendelpintor.com", $options["cc"] ) || in_array("ceo@kebco.co", $options["cc"] ))  ){
                $copyTo         = $options["cc"];
                $options["cc"]  = [];
                foreach ($copyTo as $key => $value) {
                    if($value != 'gerencia@almacendelpintor.com' && $value != 'ceo@kebco.co' ){
                        $options["cc"][] = $value;
                    }
                }
            }elseif($options["cc"] == "gerencia@almacendelpintor.com" || $options["cc"] == "ceo@kebco.co"){
                $options["cc"] = '';
            }
        }

        if(!empty($options["cco"])){
            if(is_array($options["cco"]) && ( in_array("gerencia@almacendelpintor.com", $options["cco"] ) || in_array("ceo@kebco.co", $options["cco"] ) ) ){
                $copyTo         = $options["cco"];
                $options["cco"]  = [];
                foreach ($copyTo as $key => $value) {
                    if($value != 'gerencia@almacendelpintor.com' && $value != 'ceo@kebco.co' ){
                        $options["cco"][] = $value;
                    }
                }
            }elseif($options["cco"] == "gerencia@almacendelpintor.com" || $options["cco"] == "ceo@kebco.co"){
                $options["cco"] = '';
            }
        }
        
        $from = Configure::read('Email.contact_mail');
        if($brand != null){
            $from = Configure::read('Email.brand_mail');
        }else{

            $from = Configure::read('Email.contact_mail');

            if (isset($options["gerencia"])) {
                $from = Configure::read('Email.contact_gerencia');
            }
        }
        try {

            if(!is_null($config)){
                Configure::write("email_qt", $config["username"]);
                Configure::write("password_qt", $config["password"]);
                $from = array($config["username"] => 'Nuevo mensaje');
                $email = new CakeEmail();
            }else{
                Configure::write("email_qt", null);
                Configure::write("password_qt", null);
                $email                      = new CakeEmail();
            }

            if (isset($options['file'])) {
                $email->template($options['template'],isset($options["layout"]) ? $options["layout"] : 'default')
                    ->config('default')
                    ->emailFormat('html')
                    ->subject($options['subject'])
                    ->to($options['to'])
                    ->from($from)
                    ->attachments($options['file'])
                    ->viewVars($options['vars']);
                    if(isset($options["cc"])){
                        $email->cc($options["cc"]);
                    }
                return $email->send();  
            } else {
                $email->template($options['template'], isset($options["layout"]) ? $options["layout"] : 'default' )
                    ->config('default')
                    ->emailFormat('html')
                    ->subject($options['subject'])
                    ->to($options['to'])
                    ->from($from)
                    ->viewVars($options['vars']);
                    if(isset($options["cc"])){
                        $email->cc($options["cc"]);
                    }
                return $email->send();                
            }

            
        } catch(Exception $e){
            if (!is_null($brand) || !is_null($config)) {
                $this->sendMail($options);
            }
            $this->log($e->getMessage(),"debug");
            return false;
        }
        return false;
    }


    public function sendMailSendGridOld($options, $campaign_id){

        $this->loadModel("EmailTracking");

        $dataTrack = array(
            "EmailTracking" => array(
                "campaign_id" => $campaign_id,
                "email" => $options["to"],
                "send" => date("Y-m-d H:i:s"),
            )
         );

        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("sistemas@kebco.co", "Nuevo mensaje KEBCO");
        $email->setSubject($options["subject"]);
        $email->addTo($options["to"]);
        $email->addContent("text/html", $options["content"]);

        $sendgrid = new \SendGrid('');
        
        try {
            $response = $sendgrid->send($email);

            $headers = $response->headers();

            if(!empty($headers)){
                foreach ($headers as $key => $value) {
                    if(trim($value) != ""){
                        $validation = explode(":",$value);
                        if($validation["0"] == "X-Message-Id"){
                            $dataTrack["EmailTracking"]["message_id"] = trim($validation[1]);
                            $dataTrack["EmailTracking"]["response"] = json_encode($headers);
                        }

                    }
                }
            }
            $this->EmailTracking->create();
            $this->EmailTracking->save($dataTrack);
            
        } catch (Exception $e) {
            $this->log($e->getMessage(),"debug");
        }
    }

    public function sendMailSendGrid($options, $campaign_id){

        $this->loadModel("EmailTracking");

        $dataTrack = array(
            "EmailTracking" => array(
                "campaign_id" => $campaign_id,
                "email" => $options["to"],
                "send" => date("Y-m-d H:i:s"),
            )
        );


        $optionsSend = array(
            'to'        => $options["to"],
            'template'  => 'clean_emails',
            'subject'   => $options["subject"],
            'template'  => 'empty',
            'vars'      => array( "content" => $options["content"]  ),
        );

        $this->sendMail($optionsSend);

    }

    public function sendMailSendGridQuotation($options, $datosAsesor){

        $this->loadModel("EmailTracking");
        $this->loadModel("Quotation");
       
        foreach ($options["vars"] as $key => $value) {
            $this->set($key,$value);
        }
        $this->layout = false;
        $emaildata = $this->render("/Emails/html/quote_sent");
        $content   = $emaildata->body();


        $dataTrack = array(
            "EmailTracking" => array(
                "campaign_id"   => 0,
                "email"         => $options["to"],
                "quotation_id"  => $this->Quotation->field("id",["codigo" => $options["vars"]["codigo"]]),
                "send"          => date("Y-m-d H:i:s"),
            )
        );

        $whitelist = array(
            '127.0.0.1',
            '::1'
        ); 

        $emailData = !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? $datosAsesor["User"]["email"] : "mercadeo@almacendelpintor.com";

        $email = new \SendGrid\Mail\Mail();
        $email->setFrom($emailData, "Nuevo mensaje KEBCO");
        $email->setSubject($options["subject"]);
        $email->addTo($options["to"]);
        $email->addContent("text/html", $content);

        $sendgrid = new \SendGrid('');
        
        try {
            $response = $sendgrid->send($email);

            $headers = $response->headers();

            if(!empty($headers)){
                foreach ($headers as $key => $value) {
                    if(trim($value) != ""){
                        $validation = explode(":",$value);
                        if($validation["0"] == "X-Message-Id"){
                            $dataTrack["EmailTracking"]["message_id"] = trim($validation[1]);
                            $dataTrack["EmailTracking"]["response"] = json_encode($headers);
                        }

                    }
                }
            }
            $this->EmailTracking->create();
            $this->EmailTracking->save($dataTrack);
            
        } catch (Exception $e) {
            $this->log($e->getMessage(),"debug");
        }
        die();
    }


    public function generatePdf($options = array()){
        $CakePdf                        = new CakePdf(["encoding" => "UTF-8"]);
        $CakePdf->template($options['template'], 'default');
        $CakePdf->viewVars($options['vars']);
        $CakePdf->write($options['ruta']);
    }

    public function loadDocumentPdf($documento,$carpeta){
        if ($documento['size'] > 0) {
            if ($documento['error'] < 1) {
                $name_file = explode(".",$documento['name']);
                if (mb_strtolower($name_file[count($name_file) - 1]) == 'pdf') {
                    $ruta_img = WWW_ROOT.'files/'.$carpeta.'/';
                    if (!file_exists($ruta_img)) {
                        mkdir($ruta_img, 0777, true);
                    }
                    $nombre_archivo = rand().'.'.$name_file[count($name_file) - 1];
                    $this->Session->write('documentoModelo', $nombre_archivo);
                    if(move_uploaded_file($documento['tmp_name'], $ruta_img.$nombre_archivo)) {
                        return 1;
                    } else{
                        return 5;
                    }
                } else {
                    return 4;
                }
            } else {
                return 2;
            }
        } else {
            return 3;
        }
    }

    public function loadPhoto($imagen,$carpeta){
        if ($imagen['size'] > 0) {
            if ($imagen['error'] < 1) {
                $type_file = explode("/",$imagen['type']);
                if ($type_file['0'] == 'image') {
                    $ruta_img = WWW_ROOT.'img/'.$carpeta.'/';
                    if (!file_exists($ruta_img)) {
                        mkdir($ruta_img, 0777, true);
                    }
                    $nombre_archivo = rand().'.'.$type_file['1'];
                    $this->Session->write('imagenModelo', $nombre_archivo);
                    if(move_uploaded_file($imagen['tmp_name'], $ruta_img.$nombre_archivo)) {
                        return 1;
                    } else{
                        return 5;
                    }
                } else {
                    return 4;
                }
            } else {
                return 2;
            }
        } else {
            return 3;
        }
    }

    public function saveImage64($urlText, $carpeta){
        $ruta_img = WWW_ROOT.'img/'.$carpeta.'/';
        if (!file_exists($ruta_img)) {
            mkdir($ruta_img, 0777, true);
        }
        $nombre_archivo = time().'.png';
        $bin = base64_decode($urlText);

        // Gather information about the image using the GD library
        // $size = getImageSizeFromString($bin);

        $img_file = $ruta_img.$nombre_archivo;

        file_put_contents($img_file, $bin);

        return $nombre_archivo;
    }

    public function validateImageState($imagen,$product = null){
        switch ($imagen) {
            case 3:
                $this->Session->setFlash('La imagen es necesaria','Flash/error');
                break;
             case 4:
                if(is_null($product)){
                    $this->Session->setFlash('El archivo debe de ser una imagen','Flash/error');
                }else{
                    $this->Session->setFlash('Uno de los archivos subidos no es imagen','Flash/error');                    
                }
                break;

            default:
                $this->Session->setFlash('El archivo se encuentra dañado, no se ha podido subir al servidor','Flash/error');
                break;
        }
    }

    public function limpiarTexto($texto) {
        // 1. Elimina espacios en blanco
        $texto = trim($texto);

        // 2. Elimina caracteres especiales al inicio y al final, incluyendo :
        $texto = preg_replace('/^[\s\-\_\,\.\:\!\@\#\$\%\&\/\(\)\[\]\{\}]+|[\s\-\_\,\.\:\!\@\#\$\%\&\/\(\)\[\]\{\}]+$/u', '', $texto);

        return $texto;
    }

    public function deleteImageServer($ruta){
        if (file_exists($ruta)) {
            unlink($ruta);
        }
        return true;
    }

    public function replaceText($texto,$caracterRemplazar,$caracterNuevo){
        return str_replace($caracterRemplazar,$caracterNuevo,$texto);
    }

    public function validateSessionTrue(){
        if (AuthComponent::user('id')) {
            $this->paintValidateMenu(AuthComponent::user('role'));
        }
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

    public function paintValidateMenu($rol){
        switch ($rol) {
            case Configure::read('variables.roles_usuarios.Servicio al Cliente'):
                $this->redirect(array('controller' => 'pages', 'action' => 'home_adviser'));
                break;
            case Configure::read('variables.roles_usuarios.Asesor Comercial'):
                $this->redirect(array('controller' => 'pages', 'action' => 'home_adviser'));
                break;
            case Configure::read('variables.roles_usuarios.Servicio Técnico'):
                $this->redirect(array('controller' => 'pages', 'action' => 'home_adviser'));
                break;
            case Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'):
                $this->redirect(array('controller' => 'pages', 'action' => 'home_adviser'));
                break;
            case Configure::read('variables.roles_usuarios.Gerente General'):
                $this->redirect(array('controller' => 'pages', 'action' => 'home_adviser'));
                break;
            case Configure::read('variables.roles_usuarios.Administración'):
                $this->redirect(array('controller' => 'pages', 'action' => 'home_adviser'));
                break;                
            case Configure::read('variables.roles_usuarios.Contabilidad'):
                $this->redirect(array('controller' => 'pages', 'action' => 'home_adviser'));
                break;
            case Configure::read('variables.roles_usuarios.Logística'):
                $this->redirect(array('controller' => 'pages', 'action' => 'home_adviser'));
                break;
            case Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'):
                $this->redirect(array('controller' => 'pages', 'action' => 'home_adviser'));
                break;
            case Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'):
                $this->redirect(array('controller' => 'pages', 'action' => 'home_adviser'));
                break;
            case "Asesor Externo":
            case "Publicidad":
                $this->redirect(array('controller' => 'pages', 'action' => 'home_adviser'));
                break;

            default:
                $this->redirect(array('controller' => 'users', 'action' => 'role_permissions_false'));
                break;
        }
    }

    public function changeStateModel(){
        $this->autoRender                       = false;
        $controllerFind                         = $this->findModel(mb_strtoupper($this->request->params['controller']));
        $controllerDB                           = $this->findModelDB(mb_strtoupper($this->request->params['controller']));
        $this->loadModel($controllerFind);
        $datos[$controllerFind]['id']           = $this->request->data['model_id'];
        $datos[$controllerFind]['state']        = $this->request->data['state'];
        $this->$controllerFind->save($datos);
        $this->saveDataLogsUser(7,$controllerDB,$this->request->data['model_id']);
        return true;
    }

    public function delete($id){
        $id                 = $this->desencriptarCadena($id);
        $this->autoRender = false;
        $action = $this->uses[0];
        $this->loadModel($action);
        $this->$action->recursive   =   -1;
        $item = $this->$action->findById($id);
        if(empty($item)) {
            $this->Session->setFlash(__('El cambio de estado no fue realizado, el elemento seleccionado no existe.'),'Flash/error');
        } else { 
          $this->__changeState($item, $action, $id);
        }

        if(!empty($this->request->query['controller']) && !empty($this->request->query['action'])) {
           $idRedirect = isset($this->request->query["id"]) && !empty($this->request->query["id"]) ? $this->request->query["id"] : "";
           $this->redirect(array('action' => $this->request->query['action'], "controller" => $this->request->query['controller'], $idRedirect)); 
        } else {
          $this->redirect(array('action' => 'index',"controller" => $this->request->params["controller"])); 
        }
    }

    public function __changeState($item = array(), $action, $id){
        $item[$action]["state"] = $item[$action]["state"] == 1 ? 0 : 1;
        $this->$action->id      = $id;
        if($action == "User") { 
          $email = $item[$action]["email"];
          unset($this->$action->validate['password']);
          unset($this->$action->validate['confirm_password']); 
          unset($this->$action->validate['password_actual']); 
        }
        unset($item[$action]["file"]);
        unset($item[$action]["image"]);
        unset($item[$action]["password"]);
        unset($item[$action]["document"]);
        unset($item[$action]["email"]);
        if($this->$action->save($item, array('validate' => false))) {
            $this->Session->setFlash(__('Cambio de estado realizado correctamente.'),'Flash/success');
        } else {
          $this->Session->setFlash(__('El cambio de estado no fue realizado.'),'Flash/error');
        }
    }

    public function findModel($controller){ //Cambiar nombres
        switch ($controller) {
            case "USERS":
                $controlador        = 'User';
                break;
            case "PROSPECTIVEUSERS":
                $controlador        = 'Quotation';
                break;
            case "TECHNICALSERVICES":
                $controlador        = 'TechnicalService';
                break;
    
            default:
                $controlador        = $controller;
                break;
        }
        return $controlador;
    }

    public function findModelDB($controller){ //Cambiar nombres
        switch ($controller) {
            case "USERS":
                $controlador        = 'User';
                break;
            case "PROSPECTIVEUSERS":
                $controlador        = 'Quotation';
                break;
            case "TECHNICALSERVICES":
                $controlador        = 'TechnicalService';
                break;
    
            default:
                $controlador        = $controller;
                break;
        }
        return $controlador;
    }

    public function calcularHoraFin($hora_inicial) {

        $jornal                     = split(":",$hora_inicial);
        $horas                      = (int) 1+$jornal[0];
        $hora_fin_trabajo           = (int) Configure::read('variables.hora_fin_trabajo');
        $hora_salida                = '';
        switch ($horas) {
            case ($horas < 10):
                $hora_salida = "0".$horas.":".$jornal[1].":00";
                break;
            case ($horas > $hora_fin_trabajo):
                    $hora_salida = ($hora_fin_trabajo+1).":00:00";
                break;
            case ($hora_fin_trabajo):
                    $hora_salida = $horas.":".$jornal[1].":00";
                break;
            case($horas >= 10 && $horas < $hora_fin_trabajo):
                    $hora_salida  = $horas.":".$jornal[1].":00";
                break;
        }
        return $hora_salida;
    }

    private function calculateTimeNew($fechaInicial, $horas,$type = "hour"){
        $newDateTime = strtotime ($fechaInicial. " + $horas $type " ) ; 
        $newDate = date("Y-m-d",$newDateTime);
        $newHour = date("H",$newDateTime);
        $newMin = date("i",$newDateTime);
        $newDay = date("D",$newDateTime);
        return compact(array("newDateTime","newDate","newHour","newMin","newDay"));
    }

    private function validateHoliday($fechaInicial,$tiempoCalculado,$totalhoras){
        if(in_array(date("d-m-Y",strtotime($tiempoCalculado["newDate"])), Configure::read("variables.diasFestivos"))){
            $totalhoras+=24;
            return $this->validateHoliday($fechaInicial,$this->calculateTimeNew($fechaInicial, $totalhoras),$totalhoras); 
        }else{
            return array("horas" => $totalhoras, "tiempoCalculado" => $this->calculateTimeNew($fechaInicial, $totalhoras));
        }
    }

    private function validateHolidayByDay($fechaInicial){
        if(in_array($fechaInicial, Configure::read("variables.diasFestivos"))){
            $fechaInicial = date("Y-m-d",strtotime($fechaInicial. " +1 day"));
            return $this->validateHolidayByDay($fechaInicial); 
        }else{
            return $fechaInicial;;
        }
    }

    public function calculateHoursGest($horas)
    {
        $totalhoras = $horas;
        
        $tiempoCalculado = $this->calculateTimeNew(date("Y-m-d H:i:s"), $totalhoras); 
        $fechaInicial = date("Y-m-d");
        $newDateTime = '10:45:00';
        $hourMorning = false;
        if($tiempoCalculado["newHour"] >= Configure::read("variables.hora_fin_trabajo") && $tiempoCalculado["newMin"] != "00" ){
            $fechaInicial = date("Y-m-d", strtotime($fechaInicial. " +1 day") );
            $fechaInicial = $this->validateHolidayByDay($fechaInicial);
            $fechaInicial .= " ".$newDateTime;
            $hourMorning  = true;
        }else{
            $fechaInicial = date("Y-m-d H:i:s",strtotime("+".$horas." hour"));
        }

        $newHour = date("H",strtotime($fechaInicial));
        $newMin = date("i",strtotime($fechaInicial));
        $newDay = date("D",strtotime($fechaInicial));

        if (intval($newHour) <= 7 ) {
            $fechaInicial = date("Y-m-d",strtotime($fechaInicial))." ".$newDateTime;
            $newHour      = date("H",strtotime($fechaInicial));
            $newMin       = date("i",strtotime($fechaInicial));
        }

        if($newDay == "Sat" && intval($newHour) >= 12){
            $fechaInicial = date("Y-m-d H:i:s", strtotime($fechaInicial. " +48 hour"));
        }

        if($newDay == "Sun" && intval($newHour) >= 12){
            $fechaInicial = date("Y-m-d H:i:s", strtotime($fechaInicial. " +24 hour"));
        }

        $fechaInicial = $this->validateHolidayByDay(date("Y-m-d",strtotime($fechaInicial)));

        if ($hourMorning) {
            $fechaInicial = date("Y-m-d", strtotime($fechaInicial)). " ".$newDateTime;
        }else{
            $fechaInicial =  date("Y-m-d", strtotime($fechaInicial))." ".date("H:i:s",strtotime("+".$horas." hour"));
        }

        $newHour = date("H",strtotime($fechaInicial));
        $newMin = date("i",strtotime($fechaInicial));
        $newDay = date("D",strtotime($fechaInicial));
        $newYear = date("Y",strtotime($fechaInicial));

        if (intval($newHour) <= 7  ) {
            $fechaInicial = date("Y-m-d",strtotime($fechaInicial))." ".$newDateTime;
        }

        if($newYear < date("Y")){
            $fechaInicial = date("Y-m-d")." ".$newDateTime;
        }

        return $fechaInicial;

    }

    public function calcularHoraSalida($fechaInicial,$horas){

        $totalhoras = $horas;

        $tiempoCalculado = $this->calculateTimeNew($fechaInicial, $totalhoras); 

        if($tiempoCalculado["newHour"] >= Configure::read("variables.hora_fin_trabajo") && $tiempoCalculado["newMin"] != "00" ){
            $totalhoras+=15;
        }

        if($totalhoras > $horas){
            $tiempoCalculado = $this->calculateTimeNew($fechaInicial, $totalhoras); 
            $tiempoSinFestivos = $this->validateHoliday($fechaInicial,$tiempoCalculado,$totalhoras);
            $tiempoCalculado = $tiempoSinFestivos["tiempoCalculado"];
            if($tiempoCalculado["newDay"] == "Sat"){
                $totalhoras = $tiempoSinFestivos["horas"]+48;
            }
            if($tiempoCalculado["newDay"] == "Sun"){
                $totalhoras = $tiempoSinFestivos["horas"]+24;
            }  
            $tiempoCalculado =  $this->calculateTimeNew($fechaInicial, $totalhoras);         
        }

        return $tiempoCalculado["newDateTime"];
    }

    public function calcularHoraSalida_($jornal ) {

        $hora_ingreso               = split(":",date("H:i:s")); 
        $jornal                     = split(":",$jornal); 
        $horas                      = (int)$hora_ingreso[0]+(int)$jornal[0];
        $minutos                    = (int)$hora_ingreso[1]+(int)$jornal[1]; 
        $segundos                   = $hora_ingreso[2];
        $horas                      += (int)($minutos/60); 
        $minutos                    = $minutos%60;
        $horas                      = $horas - 1;
        if($minutos < 10){
            $minutos="0".$minutos; 
        }
        if($horas < 10){
            $horas="0".$horas; 
        }
        $hora_salida = $horas.":".$minutos.":".$segundos;
        return $this->validateHoraDiasHabiles($hora_salida);
    }

    public function validateHoraDiasHabiles($hora_salida){
        $array_hora_salida            = split(":",$hora_salida);
        $hora                         = (int) $array_hora_salida[0];
        $minutos                      = (int) $array_hora_salida[1];
        $hora_inicial_trabajo         = (int) Configure::read('variables.hora_inicial_trabajo');
        $hora_fin_trabajo             = (int) Configure::read('variables.hora_fin_trabajo');
        $minutos_fin_trabajo          = (int) Configure::read('variables.minutos_fin_trabajo');
        $dias                         = (int)($hora/24);
        $horas_dias                   = $dias * 24;
        $hora_dias                    = $hora - $horas_dias; 

        if($minutos < 10){
            $minutos="0".$minutos; 
        }
        if ($hora_dias < $hora_inicial_trabajo || $hora_dias >= $hora_fin_trabajo) {
            $dias += 1;
            $minutos        = '00';
            $hora_dias      = '08';
        }
        $hora_fin           = (int) $hora_dias;
         if($hora_fin < 10){
            $hora_fin="0".$hora_fin; 
        }
        $hora_final         = $hora_fin.":".$minutos.":00";
        return $this->calcularFechaSalida($dias."-".$hora_final);
    }

    public function calcularFechaSalida($dias_hora_final){
        $hora_ingreso               = split("-",$dias_hora_final);
        $Segundos                   = 0;
        $dias                       = $hora_ingreso[0];
        $FechaFinal                 = date("Y-m-d");
         for ($i=0; $i<$dias; $i++) {  
            $Segundos = $Segundos + 86400;  
            $caduca = date("D",time()+$Segundos);
            if ($caduca == "Sat"){  
                $i--;  
            } else if ($caduca == "Sun"){  
                $i--;  
            } else {
                $FechaFinal = date("Y-m-d",time()+$Segundos);
            }  
        }
        return $this->validateFestivo($FechaFinal,$hora_ingreso[1]);
    }

    public function validateFestivo($fecha,$hora_final){
        $fecha_entro        = 0;
        $Segundos           = 0;
        $arrayFestivos      = Configure::read('variables.diasFestivos');
        foreach ($arrayFestivos as $val) {
            if ($fecha == $val) {
                $Segundos = $Segundos + 86400;
                $fecha = date("Y-m-d",time()+$Segundos);
            }
        }
        return $fecha."T".$hora_final;
    }

    public function saveManagesUser($description,$horas_habiles,$user_id,$flujo_id,$etapa,$url, $type = 0){
        $this->loadModel('Manage');
        $fechaInicial = date("Y-m-d H:i:s");
        $tiempoFinal                                    = $this->calcularHoraSalida($fechaInicial,$horas_habiles);
        $datosM['Manage']['description']                = $description;
        $datosM['Manage']['date']                       = date("Y-m-d");
        $datosM['Manage']['time']                       = date('H:i:s');
        $datosM['Manage']['time_end']                   = date("H:i:s",$tiempoFinal);
        $datosM['Manage']['url']                        = $url;
        $datosM['Manage']['user_id']                    = $user_id;
        $datosM['Manage']['prospective_users_id']       = $flujo_id;
        $datosM['Manage']['state_flow']                 = $etapa;
        $datosM['Manage']['type']                       = $type;
        // $datosM['Manage']['id']                         = $this->Manage->new_row_model() + 1;
        $this->Manage->create();
        $this->Manage->save($datosM);
        return true;
    }

    public function saveStagesFlow($flujo_id,$description,$reason,$state,$origen = ''){
        $this->loadModel('FlowStage');
        $datosF['FlowStage']['prospective_users_id']        = $flujo_id;
        $datosF['FlowStage']['reason']                      = $reason;
        $datosF['FlowStage']['description']                 = $description;
        $datosF['FlowStage']['state_flow']                  = $state;
        if ($origen != '') {
            $datosF['FlowStage']['origin']                  = $origen;
        }
        // $datosF['FlowStage']['id']                           = $this->FlowStage->new_row_model() + 1;
        $this->FlowStage->create();
        $this->FlowStage->save($datosF);
        return true;
    }

    public function saveAtentionTimeFlujoEtapasLimitTime($flujo_id,$limit_etapa_date,$limit_etapa_time,$horas_habiles){
        $this->loadModel('AtentionTime');
        $flujo_exist        = $this->AtentionTime->get_data_id($flujo_id);
        if ($flujo_exist == 0) {
            // $datos['AtentionTime']['id']                        = $this->AtentionTime->new_row_model() + 1;
            $this->AtentionTime->create();
        } else {
            $datosA['AtentionTime']['id']                       = $flujo_exist;
        }
        $fechaInicial = date("Y-m-d H:i:s");
        $tiempoFinal                                            = $this->calcularHoraSalida($fechaInicial,$horas_habiles);
        $datosA['AtentionTime'][$limit_etapa_date]              = date("Y-m-d", $tiempoFinal);
        $datosA['AtentionTime'][$limit_etapa_time]              = date("H:i:s", $tiempoFinal);
        $datosA['AtentionTime']['prospective_users_id']         = $flujo_id;
        $this->AtentionTime->save($datosA);
        return true;
    }

    public function calculateFechaFinalEntrega($fechaProgramado,$dias = 0){
    
        if ($dias == 0){
            return date("Y-m-d",strtotime($fechaProgramado));
        }

        for ($i=1; $i <= $dias; $i++) { 
            $fecha = date("Y-m-d",strtotime($fechaProgramado."+ 1 days"));
            $fechaProgramado = $this->validateDay($fecha);
        }
        return $fechaProgramado;

    }

    private function validateDay($day){
        $time        = strtotime($day);        
        $dayValidate = date("D",$time);

        if($dayValidate == "Sat"){
            $day = date("Y-m-d",strtotime($day."+ 2 days"));
        }
        if($dayValidate == "Sun"){
            $day = date("Y-m-d",strtotime($day."+ 1 days"));
        }

        return $day;

    }

    public function saveAtentionTimeFlujoEtapas($flujo_id,$etapa_date,$etapa_time,$etapa){
        $this->loadModel('AtentionTime');
        $flujo_exist            = $this->AtentionTime->get_data_id($flujo_id);
        if ($flujo_exist == 0) {
            $this->AtentionTime->create();
            $existe             = false;

        } else {
            $datosA['AtentionTime']['id']                               = $flujo_exist;
            $existe             = true;
            $datos              = $this->AtentionTime->get_data($flujo_exist);
        }
        $datosA['AtentionTime']['prospective_users_id']                 = $flujo_id;
        $datosA['AtentionTime'][$etapa_date]                            = date('Y-m-d');
        $datosA['AtentionTime'][$etapa_time]                            = date('H:i:s');
        if ($etapa == 'contactado' || $etapa == 'cotizado') {
             if ($existe) {
                if ($etapa == 'contactado') {
                    if (strtotime($datos['AtentionTime']['limit_contactado_date'].' '.$datos['AtentionTime']['limit_contactado_time']) > strtotime($datosA['AtentionTime'][$etapa_date].' '.$datosA['AtentionTime'][$etapa_time])) {
                        $datosA['AtentionTime']['demorado_contactado']                   = 0;
                    } else {
                        $datosA['AtentionTime']['demorado_contactado']                   = 1;
                    }
                } else {
                    if (strtotime($datos['AtentionTime']['limit_cotizado_date'].' '.$datos['AtentionTime']['limit_cotizado_time']) > strtotime($datosA['AtentionTime'][$etapa_date].' '.$datosA['AtentionTime'][$etapa_time])) {
                        $datosA['AtentionTime']['demorado_cotizado']                      = 0;
                    } else {
                        $datosA['AtentionTime']['demorado_cotizado']                      = 1;
                    }
                }
            }
        }
        $this->AtentionTime->save($datosA);
        return true;
    }

    public function updateFlowStageState($flowStage_id,$estado){
        $this->loadModel('FlowStage');
        $datos['FlowStage']['id']                       = $flowStage_id;
        $datos['FlowStage']['state']                    = $estado;
        $this->FlowStage->save($datos);
        return true;
    }

    public function updateFlowStagePaymentVerification($flowStage_id,$texto){
        $this->loadModel('FlowStage');
        $datos['FlowStage']['id']                       = $flowStage_id;
        $datos['FlowStage']['payment_verification']     = $texto;
        $this->FlowStage->save($datos);
        return true;
    }

    public function updateStateProspectiveFlow($flujo_id,$state){
        $this->loadModel('ProspectiveUser');
        $datosP['ProspectiveUser']['id']                = $flujo_id;
        $datosP['ProspectiveUser']['state_flow']        = $state;
        $this->ProspectiveUser->save($datosP);
    }

    public function insertProspectiveDesciptionCancel($flujo_id,$description){
        $this->loadModel('ProspectiveUser');
        $datosP['ProspectiveUser']['id']                = $flujo_id;
        $datosP['ProspectiveUser']['description']       = $description;
        $this->ProspectiveUser->save($datosP);
    }

    public function updateStateProspective($flujo_id,$state){
        $this->loadModel('ProspectiveUser');
        $datosP['ProspectiveUser']['id']                = $flujo_id;
        $datosP['ProspectiveUser']['state']             = $state;
        $this->ProspectiveUser->save($datosP);
        return true;
    }

    public function deleteCacheProducts(){
        if ($this->request->is('ajax')) {
            $this->autoRender   = false;
        }
        $this->Session->delete('carritoProductos');
        $this->Session->delete('plantillaProductos');
        $this->Session->delete('plantillaProductos1');
        $this->Session->delete('plantillaProductos2');
    }

    public function deleteCacheProducts1(){
        // $this->Session->delete('carritoProductos');
        // $this->Session->delete('plantillaProductos');
        // $this->Session->delete('plantillaProductos1');
        // $this->Session->delete('plantillaProductos2');
    }

    public function deleteCacheImportaciones($modal = null){
        if(!is_null($modal)){
            $this->layout = false;
        }elseif ($this->request->is('ajax')) {
            $this->autoRender   = false;
        }
        $this->Session->delete('carritoImportaciones');
        return true;
    }

    public function calcuatePromedioResult($total,$cienPorciento){
        $total              = $this->replaceText($total,'.','');
        $cienPorciento      = $this->replaceText($cienPorciento,'.','');
        $promedio               = 0;
        if ($cienPorciento != 0) {
            $totalDivision      = $total * 100;
            if ($totalDivision != 0) {
                $promedio       = $totalDivision / $cienPorciento;
                $promedio       = number_format($promedio, '2', '.', '');
            }
        } else {
            $promedio = '100';
        }
        return $promedio;
    }

    public function updateUserSet($old_user,$user_id,$flujo_id, $reasigna = false){
        $this->loadModel("ProspectiveUser");
        $this->loadModel("Quotation");
        $datosP['ProspectiveUser']['id']                = $flujo_id;
        $datosP['ProspectiveUser']['user_id']           = $user_id;
        $datosUserSession                               = $this->ProspectiveUser->User->get_data($old_user);
        $datosUserAsesor                                = $this->ProspectiveUser->User->get_data($user_id);
        $datosF['FlowStage']['state_flow']              = Configure::read('variables.nombre_flujo.asignado_flujo_proceso');
        $datosF['FlowStage']['prospective_users_id']    = $flujo_id;
        $datosF['FlowStage']['description']             = 'El asesor '.$datosUserSession['User']['name'].' asigno al asesor '.$datosUserAsesor['User']['name'].' para el flujo en proceso';

        if ($reasigna) {
            $datosF['FlowStage']['user_ini']            = $old_user;
            $datosF['FlowStage']['user_to']             = $user_id;
            $datosP['ProspectiveUser']['user_lose']     = $old_user;
            $datosP['ProspectiveUser']['date_lose']     = date("Y-m-d");
        }

        $this->ProspectiveUser->FlowStage->create();
        if ($this->ProspectiveUser->FlowStage->save($datosF)){
            $this->ProspectiveUser->save($datosP);
            $this->saveDataLogsUser(12,'ProspectiveUser',$flujo_id);
            $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.asesor_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosUserAsesor['User']['id'],$flujo_id,$old_user,$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
            $url        = 'prospectiveUsers/adviser?q='.$flujo_id;
            $options = array(
                'to'        => $datosUserAsesor['User']['email'],
                'template'  => 'modify_adviser',
                'subject'   => 'Te han asignado un flujo en proceso',
                'vars'      => array('datosUserAsesor' => $datosUserAsesor, 'datosUserSession' => $datosUserSession,'url' => $url),
            );
            $this->sendMail($options);
        }
    }

    public function saveDataLogsUser($action,$model,$id_colum,$state_etapa = null){
        $this->loadModel('Log');
        $datos['Log']['action']         = $action;
        $datos['Log']['model']          = $model;
        $datos['Log']['id_colum']       = $id_colum;
        $datos['Log']['user_id']        = is_null(AuthComponent::user('id')) ? 1 : AuthComponent::user('id');
        $datos['Log']['date']           = date("Y-m-d");
        if ($state_etapa !=  null) {
            $datos['Log']['state_flujo']   = $state_etapa;
        }
        // $datos['Log']['id']             = $this->Log->new_row_model() + 1;
        $this->Log->create();
        $this->Log->save($datos);
    }

    public function generateIdentificationQuotation($prospective_users_id){
        $this->loadModel('Quotation');
        $countCotizacionesCreadas         = $this->Quotation->count_quotation_flujo($prospective_users_id);
        if ($countCotizacionesCreadas > 0) {
            $version        = $countCotizacionesCreadas + 1; 
        } else {
            $version        = 1;
        }
        $flujo              = (int) $prospective_users_id;
        if ($flujo < 10) {
           $codigo         = Configure::read('variables.code_factura').'000'.$prospective_users_id;
        } else if($flujo > 9 && $flujo < 100){
            $codigo         = Configure::read('variables.code_factura').'00'.$prospective_users_id;
        } else if($flujo > 99 && $flujo < 1000){
            $codigo         = Configure::read('variables.code_factura').'0'.$prospective_users_id;
        } else {
            $codigo         = Configure::read('variables.code_factura').$prospective_users_id;
        }
        return $this->validExistCodigo($codigo,$version);
    }

    public function validExistCodigo($codigo,$version){
        $this->loadModel('Quotation');
        $valid = true;
        while ($valid) {
            if ($version < 10) {
                $texto = $codigo.'-0'.$version;
            } else {
                $texto = $codigo.'-'.$version;
            }
            $count = $this->Quotation->FlowStage->count_cotizaciones_enviadas_client_codigo($texto);
            if ($count > 0) {
                $version++;
            } else {
                $countQuotation = $this->Quotation->count_cotizaciones_enviadas_client_codigo($texto);
                if ($countQuotation > 0) {
                    $version++;
                }else {
                    $valid = false;
                }
            }
        }
        return $texto;
    }

    public function findEmailCliente($contacs_users_id,$clients_natural_id){
        $this->loadModel('ProspectiveUser');
        if ($contacs_users_id > 0) {
            $datosC         = $this->ProspectiveUser->ContacsUser->get_data($contacs_users_id);
            $email          = $datosC['ContacsUser']['email'];
        } else {
            $datosC         = $this->ProspectiveUser->ClientsNatural->get_data($clients_natural_id);
            $email          = $datosC['ClientsNatural']['email'];
        }
        return $email;
    }

    public function findDataCliente($contacs_users_id,$clients_natural_id){
        $this->loadModel('ProspectiveUser');
        if ($contacs_users_id > 0) {
            $datosC         = $this->ProspectiveUser->ContacsUser->get_data($contacs_users_id);
            $datosCliente   = $datosC['ContacsUser'];
        } else {
            $datosC         = $this->ProspectiveUser->ClientsNatural->get_data($clients_natural_id);
            $datosCliente   = $datosC['ClientsNatural'];
        }
        return $datosCliente;
    }

    public function messageUserRoleCotizacion($flujo_id,$tipo_pago,$tienda = null){
        $this->loadModel('ProspectiveUser');
        $datosFlujo             = $this->ProspectiveUser->get_data($flujo_id);
        $datosAsesor            = $this->ProspectiveUser->User->get_data($datosFlujo['ProspectiveUser']['user_id']);
        $users                  = $this->ProspectiveUser->User->role_contablidad_user();
        $arrayUserEmail         = array();
        foreach ($users as $value) {
            $arrayUserEmail[]   = $value['User']['email'];
            if ($tipo_pago == 'total') {
                $type           = 1;
                $datosPago              = $this->ProspectiveUser->FlowStage->data_flow_bussines_latses_pagado($flujo_id);
                $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.verificar_pago'),Configure::read('variables.Gestiones_horas_habiles.verificar_pago'),$value['User']['id'],$flujo_id,Configure::read('variables.nombre_flujo.verificar_pago'),$this->webroot.'prospectiveUsers/verify_payment');
                
            } else {
                $type           = 2;
                $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.verify_pago_abono'),Configure::read('variables.Gestiones_horas_habiles.verificar_pago'),$value['User']['id'],$flujo_id,Configure::read('variables.nombre_flujo.pago_abono'),$this->webroot.'prospectiveUsers/verify_payment');
            }
        }
        if ($type == 1) {
            $options = array(
                'to'        => $arrayUserEmail,
                'template'  => 'verify_payment',
                'subject'   => 'Nueva solicitud de Verificación de Pago, ID flujo'.$flujo_id,
                'vars'      => array('name' => $value['User']['name'],'nombreAsesor' => $datosAsesor['User']['name'],'cantidad' => $datosPago['FlowStage']['valor'],'medioPago' => $datosPago['FlowStage']['payment'], "tienda" => $tienda)
            );
        } else {
            $options = array(
                'to'        => $arrayUserEmail,
                'template'  => 'verify_payment_abono',
                'subject'   => 'Nueva solicitud de Verificación de Pago, ID flujo'.$flujo_id,
                'vars'      => array('name' => $value['User']['name'],'nombreAsesor' => $datosAsesor['User']['name'], "tienda" => $tienda)
            );
        }
        $this->sendMail($options);
    }

    public function messageUserRoleLogistica($flujo_id){
        return true;
        $this->loadModel('ProspectiveUser');
        $users      = $this->ProspectiveUser->User->role_logistica_user();
        foreach ($users as $value) {
            $datosFlujo             = $this->ProspectiveUser->get_data($flujo_id);
            $datosAsesor            = $this->ProspectiveUser->User->get_data($datosFlujo['ProspectiveUser']['user_id']);
            $datosRecibe            = $this->ProspectiveUser->FlowStage->find_data_despachado_flujo($flujo_id);
            $codigoQuotation        = $this->ProspectiveUser->FlowStage->codigoQuotation_latest_regystri_state_cotizado($flujo_id);
            $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.enviar_despacho'),Configure::read('variables.Gestiones_horas_habiles.enviar_despacho'),$value['User']['id'],$flujo_id,Configure::read('variables.nombre_flujo.enviar_despacho'),$this->webroot.'ProspectiveUsers/pending_dispatches');
            $options = array(
                'to'        => $value['User']['email'],
                'template'  => 'new_dispatches',
                'subject'   => 'Nueva orden de despacho',
                'vars'      => array('nameUserLogistica' => $value['User']['name'],'nameasesor' => $datosAsesor['User']['name'],'codigoCotizacion' => $codigoQuotation,'recibeName' => $datosRecibe['FlowStage']['contact'],'recibeCity' => $datosRecibe['FlowStage']['city'],'recibeTelephone' => $datosRecibe['FlowStage']['telephone'],'recibeAdress' => $datosRecibe['FlowStage']['address'],'recibeTypeSale' => $datosRecibe['FlowStage']['flete']),
            );
            $this->sendMail($options);
        }
    }

    public function getDataLegals($term = null, $ids = []){
        $this->loadModel("ProspectiveUser");
        $recursive      = -1;
        $legales        = array();
        $order          = array("name" => "ASC");
        $conditions     = [];

        if (!empty($ids)) {
            $conditions["ClientsLegal.id"] = $ids;
        }


        if (is_null($term)) {
            if (AuthComponent::user("role") == "Asesor Externo") {
                $conditions["ClientsLegal.user_receptor"] = AuthComponent::user("id");
            }
            $clientsLegals  = $this->ProspectiveUser->ContacsUser->ClientsLegal->find('all',compact("recursive","order","conditions"));
        }else{
            $clientsLegals  = $this->ProspectiveUser->ContacsUser->ClientsLegal->find_seeker($term);
        }

        if (!empty($clientsLegals)) {
            foreach ($clientsLegals as $key => $value) {
                $legales[$value["ClientsLegal"]["id"]."_LEGAL"] = trim($value["ClientsLegal"]["nit"])." | ".trim($value["ClientsLegal"]["name"]);
            }
        }
        return $legales;
    }

    public function getDataNaturals($term = null, $ids = []){
        $this->loadModel("ProspectiveUser");
        $recursive      = -1;
        $naturales      = array();
        $order          = array("name" => "ASC");
        $conditions     = [];

        if (!empty($ids)) {
            $conditions["ClientsNatural.id"] = $ids;
        }

        if (is_null($term)) {
            if (AuthComponent::user("role") == "Asesor Externo") {
                $conditions["ClientsNatural.user_receptor"] = AuthComponent::user("id");
            }
            $clientsNaturals    = $this->ProspectiveUser->ClientsNatural->find('all',compact("recursive","order","conditions"));
        }else{
            $clientsNaturals  = $this->ProspectiveUser->ClientsNatural->find_seeker($term);
        }

        if (!empty($clientsNaturals)) {
            foreach ($clientsNaturals as $key => $value) {
                $naturales[$value["ClientsNatural"]["id"]."_NATURAL"] = trim($value["ClientsNatural"]["identification"])." | ".trim($value["ClientsNatural"]["name"])." | ".trim($value["ClientsNatural"]["email"]);
            }
        }
        return $naturales;
    }

    public function searchUser($search,$conditions){
        $this->loadModel('ProspectiveUser');
        $searchNum                          = (int) $search;
        $num                                = false;
        if ($searchNum > 0) {
            $num                            = true;
        } else {
            $search                         = strtolower($search);
        }
        if ($num) {
            $datos_busqueda                 = array('ProspectiveUser.id'  => $searchNum);
        } else {
            $clientNaturales                = $this->ProspectiveUser->ClientsNatural->find_name_buscador_flujo($search);
            $clientJuridicos                = $this->ProspectiveUser->ContacsUser->ClientsLegal->find_name_buscador_flujo($search);
            $contacsClient                  = $this->ProspectiveUser->ContacsUser->id_contacs_user_bussines($clientJuridicos);
            $contacsJuridicos               = $this->ProspectiveUser->ContacsUser->find_name_buscador_flujo($search);
            $contacsJuridicos               = array_merge($contacsClient, $contacsJuridicos);
            $reasonRequerimientoIdFlujo     = $this->ProspectiveUser->FlowStage->find_reason_buscador_flujo($search);
            $datos_busqueda                 = array('OR' => array(
                                                    'ProspectiveUser.clients_natural_id'    => $clientNaturales,
                                                    'ProspectiveUser.contacs_users_id'      => $contacsJuridicos,
                                                    'ProspectiveUser.id'                    => $reasonRequerimientoIdFlujo
                                                )
                                            );
        }
        return array_merge($conditions, $datos_busqueda);
    }

    public function filterUser($filter,$conditions){
        switch ($filter) {
            case 1:
                $conditions1    = array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_asignado'));
                break;
            case 2:
                $conditions1    = array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_contactado'));
                break;
            case 3:
                $conditions1    = array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_cotizado'));
                break;
            case 4:
                $conditions1    = array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_negociado'));
                break;
            case 5:
                $conditions1    = array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_pagado'));
                break;
            case 56:
            case 57:
            case 58:
            case 59:
            case 60:
                $conditions1    = array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_pagado'));
                break;
            case 6:
                $conditions1    = array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_despachado'));
                break;
            case 7:
                $conditions1    = array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_cancelado'));
                break;
            case 8:
                $conditions1    = array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_finalizado'));
                break;
            case 9:
                $conditions1    = array('ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_no_valido'));
                break;

            default:
                $conditions1    = array();
                break;
        }
        if (strtolower($this->request->params["controller"]) == "technicalservices") {
            unset($conditions1["ProspectiveUser.type"]);
        }
        return array_merge($conditions, $conditions1);
    }

    public function filterAsesor($asesor_id, $conditions1 = array()){

        $conditions    = array('ProspectiveUser.user_id' => $asesor_id,'ProspectiveUser.type' => 0);      

        if (strtolower($this->request->params["controller"]) == "technicalservices") {
            unset($conditions["ProspectiveUser.type"]);
        }

        return array_merge($conditions, $conditions1);
    }

    public function exportFileExcel(){
        $this->autoRender                       = false;
        if ($this->request->data['tipo_exportacion'] == 1) {
            header("Content-type:application/vnd.ms-excel; charset=utf-8");
            header("Content-type:application/x-msexcel; charset=utf-8");
            header("Content-Disposition: filename=ficheroExcel.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $this->request->data['datos_a_enviar'];
        }
    }

    public function date_castellano2($fecha){ //Formato de fecha en español
        $fecha = trim($fecha);
        if ($fecha == '0000-00-00' || $fecha == '0000-00-00 00:00:00' || empty($fecha)) {
            $nombre = 'No hay información';
        } else {
            $fechaFinal     = explode(' ', $fecha);
            $fecha          = substr($fechaFinal[0], 0, 10);
            $numeroDia      = date('d', strtotime($fecha));
            $dia            = date('l', strtotime($fecha));
            $mes            = date('F', strtotime($fecha));
            $anio           = date('Y', strtotime($fecha));
            $dias_ES        = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
            $dias_EN        = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
            $nombredia      = str_replace($dias_EN, $dias_ES, $dia);
            $meses_ES       = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
            $meses_EN       = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            $nombreMes      = str_replace($meses_EN, $meses_ES, $mes);
            if (isset($fechaFinal[1])) {
                $nombre         = $nombredia.", ".$numeroDia."/".$nombreMes."/".$anio.' '.$fechaFinal[1];
            } else {
                $nombre         = $nombredia.", ".$numeroDia."/".$nombreMes."/".$anio;
            }
        }
        return $nombre;
    }

    public function encrypt($value){
        return $this->encryptString($value);
    }

    public function encryptString($value=null){
        if(!$value){return false;}
        $texto              = $value;
        $skey               = "$%&/()=?*-+/1jf8";
        $iv_size            = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv                 = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext          = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $texto, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext));
        // return base64_encode($value);
    }

    private  function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    public function decrypt($value = null){
        return $this->desencriptarCadena($value);
    }

    public function desencriptarCadena($value=null){
        if(!$value){return false;}
        $skey                 = "$%&/()=?*-+/1jf8";
        $crypttext            = $this->safe_b64decode($value); 
        $iv_size              = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv                   = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext          = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
        // return base64_decode($value);
    }

   private function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}