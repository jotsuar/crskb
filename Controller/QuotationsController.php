<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
require_once ROOT.'/app/Vendor/CifrasEnLetras.php';


class QuotationsController extends AppController {

    public $components = array('RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view','comment_quotation','resend_quotation','approve_quotation','resend_mail','view_whatsapp','show_image','quiz_data','action_payment_qtprod','payments_ipn','detail_product','action_payment_quotation','notify_chats');
    }

    public function notify_chats(){
        $this->autoRender = false;

        $this->loadModel("Report");
        $fecha = date("Y-m-d",strtotime("-1 day"));

        $reports = $this->Report->findAllByFecha($fecha);

        if(empty($reports)){
            return true;
        }

        $phone = "3127744730";

        $strMsg = '
                {
                   "messaging_product": "whatsapp",
                   "to": "57'.$phone.'",
                   "type": "template",
                   "template": {
                       "name": "notifica_informe",
                       "language": {
                           "code": "es",
                           "policy": "deterministic"
                       },
                       "components": [
                           {
                               "type": "body",
                               "parameters": [
                                   {
                                       "type": "text",
                                       "text": "'.$fecha.'"
                                   },
                                   {
                                       "type": "text",
                                       "text": "https://crm.kebco.co/pages/reports?fecha_consulta=2025-07-03"
                                   }
                               ]
                           },
                           {
                               "type": "button",
                               "sub_type": "url",
                               "index": 0,
                               "parameters": [
                                   {
                                       "type": "text",
                                       "text": "'.$fecha.'"
                                   }
                               ]
                           }
                       ]
                   }
                }
            ';
            try {

                $HttpSocket = new HttpSocket();

                $request = ["header" => [
                    "Content-Type" => 'application/json',
                    "Authorization" => 'Bearer '
                ]];

                $response = $HttpSocket->post('https://graph.facebook.com/v16.0/100586116213138/messages',$strMsg,$request);
                $responseToken = json_decode($response->body);

            } catch (Exception $e) {
                var_dump($e->getMessage());
                $this->log($e->getMessage());
            }
    }

    public function notes_form(){
        $this->layout = false;
        $this->loadModel("Product");
        $this->loadModel("Garantia");

        $id         = $this->request->data["id"];

        $parts      = explode("-", $id);
        $productId  = $parts["1"];
        $brandId    = $this->Product->field("brand_id",["id" => $productId]);
        $garantias  = $this->Garantia->find("list",["conditions" => ["brand_id" => $brandId ] ]);

        $this->set("id",$id);
        $this->set("garantias",$garantias);
    }

    public  function contarElementosEnArray($array1, $array2) {
        $contador = 0;
        
        foreach ($array1 as $elemento) {
            if (in_array($elemento, $array2)) {
                $contador++;
            }
        }
        
        return $contador;
    }

    public function show_details($part_number,$conteo = 1,$total = 1, $conteo_2024 = 1, $total_2024 = 1,$idUsuario = 0){
        $this->layout = false;

        $promedio2023 = $total > 0 && $conteo > 0 ? round($total/$conteo) : 0;
        $anio         = 2023;


        $dateComparaIni = date("$anio-01-01 00:00:00");
        $dateComparaEnd = date("$anio-12-31 23:59:59");


        if($idUsuario == 0){
            $idsUsers = $this->Quotation->User->find("list",["fields"=>["id","id"]]);
        }else{
            $idsUsers = $this->Quotation->User->field("id",["identification"=>$idUsuario]);
        }


        $datosQuotation2023  = $this->Quotation->QuotationsProduct->find("first", ["conditions" => ["QuotationsProduct.created >="=>$dateComparaIni, "QuotationsProduct.created <="=>$dateComparaEnd, "Product.part_number"=>$part_number, "Quotation.user_id" => $idsUsers], "fields" => ["Product.name","Product.img","Product.brand","Product.part_number", "SUM(QuotationsProduct.price * QuotationsProduct.quantity) totalPrice", "count(QuotationsProduct.product_id) as total" ] ] );

        $promedioCotizaciones2023 = round( $datosQuotation2023["0"]["totalPrice"] > 0 && $datosQuotation2023["0"]["total"] > 0 ? $datosQuotation2023["0"]["totalPrice"] / $datosQuotation2023["0"]["total"] : 0 );


        $promedio2024 = $total_2024 > 0 && $conteo_2024 > 0 ? round($total_2024/ $conteo_2024) : 0;
        $anio         = 2024;


        $dateComparaIni = date("$anio-01-01 00:00:00");
        $dateComparaEnd = date("$anio-12-31 23:59:59");


        $datosQuotation2024  = $this->Quotation->QuotationsProduct->find("first", ["conditions" => ["QuotationsProduct.created >="=>$dateComparaIni, "QuotationsProduct.created <="=>$dateComparaEnd, "Product.part_number"=>$part_number, "Quotation.user_id" => $idsUsers], "fields" => ["Product.name","Product.img","Product.brand","Product.part_number", "SUM(QuotationsProduct.price * QuotationsProduct.quantity) totalPrice", "count(QuotationsProduct.product_id) as total" ] ] );

        $promedioCotizaciones2024 = round( $datosQuotation2024["0"]["totalPrice"] > 0 && $datosQuotation2024["0"]["total"] > 0 ? $datosQuotation2024["0"]["totalPrice"] / $datosQuotation2024["0"]["total"] : 0 );

        $this->set(compact('part_number','conteo','total','conteo_2024','total_2024','promedio2023','promedio2024','promedioCotizaciones2023','promedioCotizaciones2024','datosQuotation2023','datosQuotation2024'));

    }

    public function generarReportChat(){
        try {

            $HttpSocket = new HttpSocket(['ssl_allow_self_signed' => false, 'ssl_verify_peer' => false, 'ssl_verify_host' =>false ]);

            $request = ["header" => [
                "Content-Type" => 'application/json'
            ]];

            $response = $HttpSocket->post('https://chat.kebcousa.com/include/api.php?function=generate_report_day&token=57b9ef2591277ab300134d7a4d18dfb5b8a9b242',[],$request);
            $responseData = json_decode($response->body);

            if(isset($responseData->response->cliente_nombre)){
                $this->loadModel("Report");
                $dataReport = $this->object_to_array($responseData->response);

                if(!isset($dataReport["conversacion_valida"]) || (isset($dataReport["conversacion_valida"]) && $dataReport["conversacion_valida"] == 0) ){
                    return;
                }

                if($dataReport["cliente_nombre"] == "" || empty($dataReport["cliente_nombre"])){
                    $dataReport["cliente_nombre"] = "Sin nombre";
                }

                $dataReport["user_id"] = $this->Quotation->User->field("id",["email"=>$dataReport["email"]]);
                unset($dataReport["email"]);

                $dataReport["oportunidades_mejora"]     = json_encode($dataReport["oportunidades_mejora"]);
                $dataReport["fortalezas_detectadas"]    = json_encode($dataReport["fortalezas_detectadas"]);
                $dataReport["resolvio_solicitud"]       = $dataReport ? 1 : 0;
                $dataReport["fecha"]                    = date("Y-m-d",strtotime($dataReport["inicio_conversacion"]));

                $this->Report->create();
                $this->Report->save($dataReport);
            }

        } catch (Exception $e) {
            $this->log($e->getMessage());
        } 
    }

    public function get_quotations(){
        $this->layout = false;
        $quotations              = [];
        $quotationsCliente       = [];
        $idsClientes             = [];
        if (!empty($this->request->data["products"])) {
            $this->loadModel("ProspectiveUser");
            $this->loadModel("QuotationsProduct");

            $conditions = ["OR" => [ ["state_flow" => 2] , "state_flow" => [3,4] ], 'DATE(ProspectiveUser.created) >=' => date("Y-m-d",strtotime("-7 day")),'DATE(ProspectiveUser.created) <=' => date("Y-m-d"), "ProspectiveUser.id !=" => $this->request->data["prospective_users_id"], "ProspectiveUser.user_id" => $this->request->data['user'] ];
             $conditions2 = ["OR" => [ "state_flow" => [5,6] ], 'DATE(ProspectiveUser.created) >=' => date("Y-m-d",strtotime("-60 day")),'DATE(ProspectiveUser.created) <=' => date("Y-m-d"), "ProspectiveUser.id !=" => $this->request->data["prospective_users_id"], "ProspectiveUser.user_id" => $this->request->data['user'] ];

            $idsProspectos       = $this->ProspectiveUser->find("list",["fields"=>["id","id"],"conditions" => $conditions ]);
            
            $idsQt         = $this->Quotation->find("list",["fields" => ["id","id"], "conditions" => ["prospective_users_id" => $idsProspectos]  ]);
            $quotations    = $this->QuotationsProduct->find("all",["limit" => 10, "conditions" => ["quotation_id" => $idsQt, "product_id" => $this->request->data["products"] ], "order" => ["Quotation.created" => "DESC"], "group" => ["QuotationsProduct.quotation_id"]  ]);

            $conditions["user_id !="] = $this->request->data["user"];

            if ($this->request->data["type"] == "natural") {
                $conditions["clients_natural_id"] = $this->request->data["cliente"];
                $conditions2["clients_natural_id"] = $this->request->data["cliente"];
            }else{
                $this->loadModel("ContacsUser");
                $conditions["contacs_users_id"] = $this->ContacsUser->find("list",["fields"=>["id","id"],"conditions"=>["clients_legals_id"=>$this->request->data["cliente"]]]);
                $conditions2["contacs_users_id"] = $this->ContacsUser->find("list",["fields"=>["id","id"],"conditions"=>["clients_legals_id"=>$this->request->data["cliente"]]]);

                $actualFlow = $this->Quotation->ProspectiveUser->findById($this->request->data["prospective_users_id"]);    
                unset($conditions["contacs_users_id"][$actualFlow["ProspectiveUser"]["contacs_users_id"]]);
                unset($conditions2["contacs_users_id"][$actualFlow["ProspectiveUser"]["contacs_users_id"]]);
            }


            $idsProspectosClientes = $this->ProspectiveUser->find("list",["fields"=>["user_id","id"],"conditions" => $conditions ]);
            $idsQtClientes         = $this->Quotation->find("list",["fields" => ["id","id"], "conditions" => ["prospective_users_id" => array_values($idsProspectosClientes) ]  ]);
            $quotationsCliente     = $this->QuotationsProduct->find("all",["limit" => 10, "conditions" => ["quotation_id" => $idsQtClientes, "product_id" => $this->request->data["products"] ], "order" => ["Quotation.created" => "DESC"], "group" => ["QuotationsProduct.quotation_id"]  ]);


            $this->loadModel("ImportProductsDetail");

            



            if (!empty($quotationsCliente)) {
                foreach ($quotationsCliente as $key => $value) {
                    if ($value["Quotation"]["prospective_users_id"] == $this->request->data["prospective_users_id"] || $value["Quotation"]["user_id"] == $this->request->data["user"] || AuthComponent::user("role") == "Gerente General" || AuthComponent::user("email") == 'logistica@kebco.co' ) {
                        unset($quotationsCliente[$key]);
                    }else{
                        echo "COPIA_COTIZACION";
                        die();
                    }
                }
            }

            $idsProspectosImport = $this->ProspectiveUser->find("list",["fields"=>["id","id"],"conditions" => $conditions2 ]);
            $listadoDetails      = $this->ImportProductsDetail->findAllByFlujo($idsProspectosImport);



            if(!empty($listadoDetails) && !empty($idsProspectosImport)){
                foreach ($listadoDetails as $key => $value) {
                    if(in_array($value["Product"]["id"],$this->request->data["products"]) && $value["Import"]["state"] == 1 && $value["Import"]["description"] != "" && $value["Import"]["send_provider"] == 1 )
                    {
                        die ("IMPORTACION_DUPLICADA");
                    }
                }
            }

            if (!empty($quotationsCliente)) {
                $idsClientes = Set::extract($quotationsCliente,"{n}.Quotation.id");
            }
            if (!empty($idsClientes) && !empty($quotations)) {
                foreach ($quotations as $key => $value) {
                    if (in_array($value["Quotation"]["id"], $idsClientes)) {
                        unset($quotations[$key]);
                    }
                }
            }

        }
        $this->set("quotations",$quotations);
        $this->set("quotationsCliente",$quotationsCliente);
    }

    private function accortUrl($url){
        $codigo = "KEBCO-ALMACEN-DEL-PINTOR_".time();
        try {               
            $json = file_get_contents("https://cutt.ly/api/api.php?key=afab6f73f9375c95192d4a83a119d56b&short=$url&name=$codigo");
            $data = json_decode ($json, true);
            if (isset($data["url"]["status"]) && $data["url"]["status"] == 7) {
                $url = $data["url"]["shortLink"];
            }
        } catch (Exception $e) {
            $url = $url;
        }
        return $url;
    }

    public function quiz_data(){
        $this->autoRender = false;

        $this->loadModel("Sender");

        $senders  = $this->Sender->find("all",["conditions" => ["Sender.state" => 0, "DATE(Sender.deadline)"=>date("Y-m-d")],]);

        if (!empty($senders)) {
            $this->loadModel("Config");
            $template = $this->Config->field("textClient",["id"=>1]);
            foreach ($senders as $key => $value) {

                if (date("Y-m-d",strtotime($value["Sender"]["deadline"])) > date("Y-m-d")) {
                    continue;
                }

                $nameAsesor = trim($this->Quotation->User->field("name",["id"=>$value["ProspectiveUser"]["user_id"] ]));
                
                $template   = str_replace("@@ASESOR@@", $nameAsesor , $template);
                $template   = str_replace("@@CODIGO@@", $value["Quotation"]["codigo"] , $template);
                $template   = str_replace("@@FECHA@@",  date("Y-m-d",strtotime($value["Sender"]["send_date"])) , $template);
                $clientName = $this->getDataCustomer($value["ProspectiveUser"]["id"],true);

                $template   = str_replace("@@CORREO@@", $this->encryptString($clientName["email"]) , $template);

                $template   = str_replace("@@CLIENTE@@", $clientName["name"]." ", $template);

                $options = [
                    'layout'    => 'new_emails',
                    "to" => $clientName["email"],
                    "template" => "encuesta",
                    "subject" => "Encuesta de satisfacción KEBCO - EQUIPOS INDUSTRIALES",
                    "vars" => ["content" => $template]
                ];

                $this->sendMail($options);

                $value["Sender"]["state"] = 1;
                $value["Sender"]["modified"] = date("Y-m-d H:i:s");
                $value["Sender"]["send_date"] = date("Y-m-d H:i:s");
                $this->Sender->save($value["Sender"]);

                preg_match('/<a(.*?)<\/a>/s', $template, $match);

                if (isset($match[0])) {
                    $a = new SimpleXMLElement($match[0]);
                    $position = "@attributes";

                    $templateWhatsap = strip_tags(str_replace($match[0], "", $template));

                    $phones = array();

                    $clientName["telephone"] = str_replace([" ","+57","_"], "", $clientName["telephone"]);
                    $clientName["cell_phone"] = str_replace([" ","+57","_"], "", $clientName["cell_phone"]);

                    if(!empty($clientName["telephone"]) && strlen($clientName["telephone"]) == 10){
                        $phones[] = $clientName["telephone"];
                    }

                    if(!empty($clientName["cell_phone"]) && strlen($clientName["cell_phone"]) == 10){
                        $phones[] = $clientName["cell_phone"];
                    }

                    if (count($phones) == 2 && $phones[0] == $phones[1] ) {
                        unset($phones[1]);
                    }
                    unset($phones[1]);

                    foreach ($phones as $key => $phone) {
                        
                        $strMsg = '
                            {
                               "messaging_product": "whatsapp",
                               "to": "57'.$phone.'",
                               "type": "template",
                               "template": {
                                   "name": "encuesta_satisfaccion",
                                   "language": {
                                       "code": "es",
                                       "policy": "deterministic"
                                   },
                                   "components": [
                                       {
                                           "type": "body",
                                           "parameters": [
                                               {
                                                   "type": "text",
                                                   "text": "'.$clientName["name"].'"
                                               },
                                               {
                                                   "type": "text",
                                                   "text": "'.$nameAsesor.'"
                                               }
                                           ]
                                       }
                                   ]
                               }
                            }
                        ';
                        try {

                            $HttpSocket = new HttpSocket();

                            $request = ["header" => [
                                "Content-Type" => 'application/json',
                                "Authorization" => 'Bearer '
                            ]];

                            $responseToken = $HttpSocket->post('https://graph.facebook.com/v15.0/100586116213138/messages',$strMsg,$request);
                            $responseToken = json_decode($responseToken->body);

                        } catch (Exception $e) {
                            $this->log($e->getMessage());
                        }   

                    }
                }

            }
        }

    }

    public function proforma($id) {
        

        $id                 = $this->desencriptarCadena($id);
        $quotation          = $this->Quotation->findById($id);
        $quotationProduts   = $this->Quotation->QuotationsProduct->findAllByQuotationId($id);
        $currency           = $this->request->query["currency"];
        $products           = [];
        $total              = 0;
        $totalIVa           = 0;
        $descuento          = 0;
        $country            = $quotation["ProspectiveUser"]["country"] == "Colombia" ? false : true; 

        $this->pdfConfig            = array(
                        'download'          => false,
                        'paper'             => 'A4',
                        // 'options'           => ['outline' => true,],
                        'filename'          => 'PRO-'.$quotation["Quotation"]["codigo"]."-".$this->request->query["currency"].'.pdf',
                        'orientation'       => 'Potrait',
                        'download'          => true

        );

        foreach ($quotationProduts as $key => $value) {
            if ($value["QuotationsProduct"]["currency"] == $currency) {

                if($quotation["Quotation"]["header_id"] != 3 && $quotation["Quotation"]["show_ship"] == 0 && in_array($value["Product"]["part_number"],['S-003']) ){
                    continue;
                }

                $total += $value["QuotationsProduct"]["price"]* $value["QuotationsProduct"]["quantity"];
                $products[] = $value;

                if ($value["QuotationsProduct"]["iva"] == 1) {
                    $totalIVa += ( $value['QuotationsProduct']['quantity'] *$value['QuotationsProduct']['price'] );
                }

            }
        }

        if ($quotation["Quotation"]["descuento"] > 0) {
            $descuento = $total * ($quotation["Quotation"]["descuento"] / 100) ;
        }

        if (strtolower($quotation["ProspectiveUser"]["country"]) == "colombia") {
            if ($currency == "cop") {
                $letras = CifrasEnLetras::convertirNumeroEnLetras(round(( ($total-$descuento) + ( ($totalIVa - $descuento) * 0.19) ), 2));
            }else{
                $letras = CifrasEnLetras::convertirNumeroEnLetras(round(( ($total-$descuento) + ( ($totalIVa - $descuento) * 0.19) ), 2),-1,'Dolar','Dolares',false,'centavo','centavos');            
            }
        }else{
            if (empty($this->request->query["iva"])) {
                $letras = CifrasEnLetras::convertirNumeroEnLetras(round( ($total-$descuento), 2),-1,'Dolar','Dolares',false,'centavo','centavos');   
            }else{
                $letras = CifrasEnLetras::convertirNumeroEnLetras(round(( ($total-$descuento) *$this->request->query["iva"]), 2),-1,'Dolar','Dolares',false,'centavo','centavos');
            }
        }

        if ($currency == "cop" && is_null($quotation["Quotation"]["proforma"]) ) {
            $quotation["Quotation"]["proforma"] = $this->getNumerMaxProforma();
            $this->Quotation->save($quotation["Quotation"]);
        }elseif ($currency != "cop" && is_null($quotation["Quotation"]["proforma_usd"]) ) {
            $quotation["Quotation"]["proforma_usd"] = $this->getNumerMaxProforma();
            $this->Quotation->save($quotation["Quotation"]);
        }


        $this->set(compact("quotation","products","total","letras","currency","country","descuento"));
    }

    private function getNumerMaxProforma(){
        $proforma = $this->Quotation->field("max(proforma)");
        $proforma_usd = $this->Quotation->field("max(proforma_usd)");

        if(is_null($proforma) && is_null($proforma_usd) ){
            $number = 299;
        }else{

            if (!is_null($proforma) && !is_null($proforma_usd) ) {
                if ($proforma >= $proforma_usd) {
                    $number = $proforma;
                }else{
                    $number = $proforma_usd;
                }
            }elseif (is_null($proforma) && !is_null($proforma_usd)) {
                $number = $proforma_usd;
            }else{
                $number = $proforma;
            }
        }
        return $number+=1;
    }

    public function data_proforma($id){
        $this->layout = false;
        $id = $this->desencriptarCadena($id);

        $quotation = $this->Quotation->findById($id);

        $country   = $quotation["ProspectiveUser"]["country"] == "Colombia" ? false : true; 

        $formaEnvio = ["EX-WORKS MIAMI" => "EX-WORKS MIAMI", "FCA" => "FCA","CIF" => "CIF"];

        $currencys = [];

        $datosCliente = $this->getDataCustomer($quotation['ProspectiveUser']['id'],true); 

        if ($quotation["ProspectiveUser"]["country"] == "Colombia") {
            $formasPago = [
                "Contado 0 días" => "Contado 0 días",
                "Crédito 30 días" => "Crédito 30 días",
            ];
        }else{
            $formasPago = [
                "Debido a la recepción" => "Debido a la recepción",
                "Cash in advance/ contado anticipado" => "Cash in advance/ contado anticipado",
            ];
        }
        foreach ($quotation["QuotationsProduct"] as $key => $value) {
            if(!array_key_exists($value["currency"], $currencys)){
                $currencys[$value["currency"]] = strtoupper($value["currency"]);
            }
        }

        $this->set(compact("currencys","formasPago","datosCliente","id","country","formaEnvio","quotation"));
    }

    public function show_image($id_quotation){
        $this->autoRender = false;
        $id_quotation = $this->desencriptarCadena($id_quotation);
        $this->Quotation->recursive = -1;

        $quotation = $this->Quotation->findById($id_quotation);

        if(is_null($quotation["Quotation"]["date_view"])){
            $quotation["Quotation"]["date_view"]    = date("Y-m-d H:i:s");
        }
        $quotation["Quotation"]["modified"]     = date("Y-m-d");

        $this->Quotation->save($quotation);

        return file_get_contents(WWW_ROOT."img".DS."assets".DS."pdf.jpg");
    }

    public function quoataions_gests(){
        $this->autoRender = false;
        $this->loadModel("Config");

        $this->generarReportChat();

        if (AuthComponent::user("id") == 13) {
            return 0;
        }

        $USER_FIND         = AuthComponent::user("id") == 4 ? [4,13] : AuthComponent::user("id");


        $user_remarketing  = empty( Configuration::get("user_remarketing") ) ? 126 : Configuration::get("user_remarketing");
        
        $flowData         = $this->Quotation->ProspectiveUser->find("first",["recursive" => 1,
            "conditions" => 
            [
                "date_quotation !=" => null,            
                "state_flow" => 3,
                "type" => 0,
                "OR" => [ 
                    ["date_alert <=" => date("Y-m-d"), "alert_one" => 0, "date_final_alert" => null, "alert_two" => 0, "prorroga_one" => 0, "prorroga_two" => 0, 'notified' => 0 ], 
                    ["date_final_alert <=" => date("Y-m-d"), "alert_one" => 1, "date_alert !=" => null, "alert_two" => 0, "prorroga_one" => 0, "prorroga_two" => 0, 'notified' => 0 ], 
                    ["date_prorroga <=" => date("Y-m-d"), "alert_one" => 1,  "date_alert !=" => null, "alert_two" => 1,"date_final_alert !=" => null, "prorroga_one" => 0, "prorroga_two" => 0, 'notified' => 0 ], 
                    ["date_prorroga_final <=" => date("Y-m-d"), "alert_one" => 1, "date_prorroga !="=>null,  "date_alert !=" => null, "alert_two" => 1,"date_final_alert !=" => null, "prorroga_one" => 1, "prorroga_two" => 0, 'notified' => 0 ], 
                ],
                "remarketing" => 0,
                "ProspectiveUser.user_id" => $USER_FIND,
            ],
        ]);


        $flowDataNoGestsNotified = $this->Quotation->ProspectiveUser->find("all",["recursive" => 1, "limit" => 1,
            "conditions" => [
                "date_quotation !=" => null,            
                "state_flow" => 3,
                "type" => 0,
                "deadline_notified <" => date("Y-m-d H:i:s"),
                "OR" => [ 
                    ["date_alert <=" => date("Y-m-d"), "alert_one" => 0, "date_final_alert" => null, "alert_two" => 0, "prorroga_one" => 0, "prorroga_two" => 0, 'notified' => 1 ], 
                    ["date_final_alert <=" => date("Y-m-d"), "alert_one" => 1, "date_alert !=" => null, "alert_two" => 0, "prorroga_one" => 0, "prorroga_two" => 0, 'notified' => 1 ], 
                    ["date_prorroga <=" => date("Y-m-d"), "alert_one" => 1,  "date_alert !=" => null, "alert_two" => 1,"date_final_alert !=" => null, "prorroga_one" => 0, "prorroga_two" => 0, 'notified' => 1 ], 
                    ["date_prorroga_final <=" => date("Y-m-d"), "alert_one" => 1, "date_prorroga !="=>null,  "date_alert !=" => null, "alert_two" => 1,"date_final_alert !=" => null, "prorroga_one" => 1, "prorroga_two" => 0, 'notified' => 1 ], 
                ],
                "remarketing" => 0,
                "ProspectiveUser.user_id" => $USER_FIND
            ]
        ]);

        if (!empty($flowDataNoGestsNotified) && AuthComponent::user("role") != "Gerente General") {
            foreach ($flowDataNoGestsNotified as $key => $value) {
                
                $this->gestMailAuto($value);

                // if ($value["ProspectiveUser"]["user_id"] != 13) {
                    
                //     $this->Quotation->ProspectiveUser->save([
                //         "id" => $value["ProspectiveUser"]["id"], 
                //         // "losed" => 1, 
                //         // "state" => 3,
                //         // "description" => 3,
                //         // "state_flow" => 10,
                //         "remarketing" => 1,
                //         "notified" => 1,
                //         "date_lose" => date("Y-m-d"),
                //     ]);
                // }
            }
        }

        $type = null;

        if (!empty($flowData) && $flowData["User"]["role"] != "Gerente General2") {

            $flowData = $flowData["ProspectiveUser"];

            if ( 
                strtotime($flowData["date_alert"]) <= strtotime(date("Y-m-d")) && $flowData["alert_one"] == 0 && is_null($flowData["date_final_alert"]) && $flowData["alert_two"] == 0 && $flowData["prorroga_one"] == 0 && $flowData["prorroga_two"] == 0 && $flowData['notified'] == 0 
            ) {
                $type = "alert";
            }elseif ( 
                strtotime($flowData["date_final_alert"]) <= strtotime(date("Y-m-d")) && $flowData["alert_one"] == 1 && !is_null($flowData["date_alert"]) && $flowData["alert_two"] == 0 && $flowData["prorroga_one"] == 0 && $flowData["prorroga_two"] == 0 && $flowData['notified'] == 0
            ) {
                $type = "alert_two";
            }elseif ( 
                strtotime($flowData["date_prorroga"]) <= strtotime(date("Y-m-d")) && $flowData["alert_one"] == 1 && !is_null($flowData["date_alert"]) && $flowData["alert_two"] == 1 && !is_null($flowData["date_final_alert"]) && $flowData["prorroga_one"] == 0 && $flowData["prorroga_two"] == 0 && $flowData['notified'] == 0
            ) {
                $type = "prorroga";
            }elseif ( 
                strtotime($flowData["date_prorroga_final"]) <= strtotime(date("Y-m-d")) && $flowData["alert_one"] == 1 && !is_null($flowData["date_prorroga"]) && !is_null($flowData["date_alert"]) && $flowData["alert_two"] == 1 && !is_null($flowData["date_final_alert"]) && $flowData["prorroga_one"] == 1 && $flowData["prorroga_two"] == 0 && $flowData['notified'] == 0
            ) {
                $type = "final_prorroga";
            }

            if ($user_remarketing == 0) {
                $user_remarketing = 126;
            }

            if ($type == "final_prorroga") {

              
                if (AuthComponent::user("role") != "Gerente General2" && $flowData["user_id"] != 13) {

                    $this->Quotation->ProspectiveUser->save([
                        "id" => $flowData["id"], 
                        "losed" => 1, 
                        "prorroga_two" => 1, 
                        "state" => 3,
                        "description" => 3,
                        "remarketing" => 1,
                        "state_flow" => 10,
                        "date_lose" => date("Y-m-d"),
                    ]);                
                }
                return "1";
            }

            if (!is_null($type)) {
                $this->autoRender = true;
                $this->layout = false;
            }

            $dataReturn = [
                 "type" => $type ,"flow" => $flowData["id"], "fecha_cotizado" => $flowData["date_quotation"], "cliente" => $this->getDataCustomer($flowData["id"]),"state_flow" => $flowData["state_flow"] 
            ];

            $dayDeadline = $this->calculateFechaFinalEntrega(date("Y-m-d"),1);

            $this->Quotation->ProspectiveUser->save(["id" => $flowData["id"], "notified" => 1, "deadline_notified" => $dayDeadline." 12:00:00" ]);

            foreach ($dataReturn as $key => $value) {
                $this->set($key,$value);
            }

        }
        return "0";
        

    }

    public function aprovee_number(){
        $this->autoRender = false;
        $this->loadModel("Approve");
        $this->loadModel("User");
        $this->loadModel("Config");

        $users = $this->User->field("users", ["id" => AuthComponent::user("id")] );

        if (!is_null($users)) {
            $users = explode(",", $users);
        }

        $userExtenalValid = $this->Config->field("users_external",["id"=>1]);        

        if (!empty($userExtenalValid) && !is_null($userExtenalValid)) {
            $userExtenalValid = in_array(AuthComponent::user("id"), explode(",", $userExtenalValid));
            $usersExternals = $this->User->find("list", ["fields" => ["id","id"], "conditions" => ["role" => "Asesor Externo","state" => 1] ] );
            if ($userExtenalValid && !empty($usersExternals) ) {
                if (empty($users) || is_null($users)) {
                    $users = $usersExternals;
                }else{
                    $users = array_merge($users,$usersExternals);
                }
            }
        }
        
        

        return $this->Approve->find("count",["conditions" => ["Approve.state" => 0, "Approve.user_id" => $users, "type_aprovee" => 1 ]]);
    }

    public function tracking(){
        $this->loadModel("EmailTracking");
        $q = $this->request->query;

        $this->EmailTracking->unBindModel([
            "belongsTo" => ["Campaign"]
        ]);
        $conditions = ["EmailTracking.quotation_id != " => null ];

        if (!empty($q)) {
            if(!empty($q["txt_flujo"])){
                $conditions["Quotation.prospective_users_id"] = $q["txt_flujo"];
            }
            if(!empty($q["txt_asesor"])){
                $flujosAsesor = $this->Quotation->ProspectiveUser->find("all",["fields"=>["ProspectiveUser.id"],"recursive" => -1, "conditions" => ["ProspectiveUser.user_id" => $q["txt_asesor"], "ProspectiveUser.state_flow >=" => 3, "ProspectiveUser.state_flow <=" => 6 ] ]);
                if(!empty($flujosAsesor)){
                    $flujosAsesor = Set::extract($flujosAsesor,"{n}.ProspectiveUser.id");
                    $conditions["Quotation.prospective_users_id"] = $flujosAsesor;
                }
            }

            if(!empty($q["txt_buscador_fecha"])){
                $conditions["DATE(EmailTracking.created)"] = $q["txt_buscador_fecha"];
            }
            if(!empty($q["txt_cotizacion"])){
                $conditions["OR"] = [
                    "LOWER(Quotation.codigo) LIKE" => '%'.
                    strtolower($q['txt_cotizacion']).'%',
                    "LOWER(Quotation.name) LIKE" => '%'.
                    strtolower($q['txt_cotizacion']).'%',
                ];
            }
            if(!empty($q["txt_estado"])){
                $estado = $q["txt_estado"];
                $conditions["EmailTracking.$estado"] = null;
            }else{
                $conditions["OR"] = [
                    "EmailTracking.read" => null,
                    "EmailTracking.clicked" => null
                ];
            }

        } else {
            $conditions["OR"] = [
                    "EmailTracking.read" => null,
                    "EmailTracking.clicked" => null
            ];
        }
        $fields                     = array('*');
        $order                      = array('EmailTracking.created' => 'desc');

        $this->paginate             = array(
                                        'limit'         => 20,
                                        'conditions'    => $conditions,
                                        'fields'        => $fields,
                                        'order'         => $order
                                    );
        $tracks         = $this->paginate('EmailTracking');

        $this->set(compact('tracks'));

        $usuarios           = $this->Quotation->ProspectiveUser->User->role_asesor_comercial_user_true();

        $this->set("usuarios",$usuarios);


        if(!empty($this->request->query)){
            $this->set("q", $this->request->query);
        }
    }

    public function resend_mail(){
        $this->autoRender = false;
        $this->Quotation->unBindModel(["belongsTo" => ["FlowStage","User","Header"],"hasMany" => ["QuotationsProduct"]]);
        $quotations = $this->Quotation->findAllByFinalAndSendMail(1,NULL);

        if(!empty($quotations)){

            $this->loadModel("Config");
            $this->loadModel("User");

            $config = $this->Config->findById(1);

            $tiempo     = $config["Config"]["days_resend"];
            $txt_resend = $config["Config"]["txt_resend"];
            $msg_redend = $config["Config"]["msg_redend"];

            foreach ($quotations as $key => $value) {
                $fechaQt        = new DateTime($value["Quotation"]["modified"]);
                $fechaValidate  = new DateTime(date("Y-m-d"));

                $diff = $fechaQt->diff($fechaValidate);

                if($diff->days >= intval($tiempo)){
                    $datosCliente               = $this->getDataCustomer($value['ProspectiveUser']['id']);                  
                    $phones                     = array();
                    $datosCliente["telephone"]  = str_replace([" ","+57","_"], "", $datosCliente["telephone"]);
                    $datosCliente["cell_phone"] = str_replace([" ","+57","_"], "", $datosCliente["cell_phone"]);

                    if(!empty($datosCliente["telephone"]) && strlen($datosCliente["telephone"]) == 10){
                        $phones[] = $datosCliente["telephone"];
                    }

                    if(!empty($datosCliente["cell_phone"]) && strlen($datosCliente["cell_phone"]) == 10){
                        $phones[] = $datosCliente["cell_phone"];
                    }

                    if(!empty($phones)){
                        foreach ($phones as $key => $phone) {
                            $this->setTextMsg($phone,$txt_resend);
                        }
                    }
                    $email_defecto = array();
                    $email_defecto = Configure::read('variables.emails_defecto');
                    $email_defecto[] = $this->User->field("email",["id" => $value["ProspectiveUser"]["user_id"] ]);

                    $options = array(
                        'template'  => 'resend_notification',
                        'to'        => $datosCliente["email"],
                        "cc"        => $email_defecto,
                        'subject'   => "Cotizaciones Kebco SAS - AlmacenDelPintor.com",
                        'vars'      => array("msg" =>  $msg_redend),
                    );
                    $this->sendMail($options);

                    $value["Quotation"]["send_mail"] = date("Y-m-d");
                    $this->Quotation->save(["Quotation" => $value["Quotation"]] );

                }

            }
        }

        var_dump($quotations);

    }

    public function getLastCode(){
        $this->loadModel("Order");
        $last = $this->Order->find("first",["fields"=>["Order.code"],"order"=>["Order.code" => "DESC"]]);
        
        if (is_null($last) || $last == 0 || empty($last)) {
            $last = 1;
        }else{
            $last = $last["Order"]["code"]+1;
        }
        return $last;
    }

    public function approve_quotation(){
        $this->autoRender = false;

        $this->request->data["archivoOrden"] = $this->request->form["archivoOrden"];
        $ordenDoc    = null;
        $description = "El cliente / correo aprobó la cotización, con el siguiente comentario: ".$this->request->data["comentarioCotizacion"];
        $this->request->data["comentarioQt"] = $description;

        $this->loadModel("User");
        $this->loadModel("FlowStage");

        if ($this->request->data['archivoOrden']['name'] != '') {

            $idPdf = strpos(strtolower($this->request->data['archivoOrden']['name']), '.pdf');

            if ($idPdf === false) {
                $documento  = $this->loadPhoto($this->request->data['archivoOrden'],'flujo/negociado');
                $datos["FlowStage"]['document'] = $this->Session->read('imagenModelo');
                $ordenDoc   = $this->Session->read('imagenModelo');
            }else{
                $documento  = $this->loadDocumentPdf($this->request->data['archivoOrden'],'flujo/negociado');
                $ordenDoc   = $this->Session->read('documentoModelo');
                $datos["FlowStage"]['document'] = $this->Session->read('documentoModelo');
            }

        } else {
            $documento = 1;
        }

        $datosFlujo = $this->Quotation->ProspectiveUser->findById($this->request->data['flujo']);

        $etapa_id_cotizado      = $this->FlowStage->id_latest_regystri_state_cotizado($this->request->data['flujo']);
        $datosF                 = $this->FlowStage->get_data($etapa_id_cotizado);
        $datosCliente           = $this->getDataCustomer($this->request->data['flujo']);
        
        $datosOrden = [
            "prospective_user_id" => $this->request->data['flujo'],
            "quotation_id" => $datosF['FlowStage']['document'],
            "prefijo" => Configure::read("PrefijoNAC"),
            "code"    => $this->getLastCode(),
            "document" => $ordenDoc,
            "payment_type" => 1,
            "note" => $description,
            "payment_text" => '',
            "deadline" => date("Y-m-d",strtotime("+1 day")),
            "user_id"  => $datosFlujo["ProspectiveUser"]["user_id"],
            "clients_legal_id"    => isset($datosCliente["legal"]) ? $datosCliente["clients_legals_id"] : null ,
            "contacs_user_id"     => isset($datosCliente["legal"]) ? $datosCliente["id"] : null ,
            "clients_natural_id"  => isset($datosCliente["legal"]) ? null : $datosCliente["id"],
        ];

        $datos['FlowStage']['state_flow']               = Configure::read('variables.nombre_flujo.flujo_negociado');
        $datos['FlowStage']['prospective_users_id']     = $this->request->data['flujo'];
        $datos['FlowStage']['description']              = $this->request->data['comentarioCotizacion'];

        $quotation = $this->Quotation->findById($datosF['FlowStage']['document']);
        $this->loadModel("Order");

        $this->Order->create();
        if ($this->Order->save($datosOrden)) {
            $orderId    = $this->Order->id;
            $total = 0;

            $this->loadModel("OrdersProduct");
            foreach ($quotation["QuotationsProduct"] as $productoID => $value) {
                $this->OrdersProduct->create();
                $dataProduct = array(
                    "order_id"   => $orderId,
                    "product_id" => $value["product_id"],
                    "price"      => $value["price"],
                    "quantity"   => $value["quantity"],
                    "iva"        => $value["iva"],
                    "delivery"   => $value["delivery"],
                    "currency"   => $value["currency"],
                    "cost"       => 0,
                    "state"      => 0,
                    "margin"     => $value["margen"]
                );

                $this->OrdersProduct->save($dataProduct);

                $total += $dataProduct["price"]*$dataProduct["quantity"]; 

            }
            $this->Order->updateAll(["Order.total" => floatval($total) ], ["Order.id" => $orderId ]);
            $datos['FlowStage']['cotizacion']               = $orderId;
        }

        if ($documento == 1) {
            $this->FlowStage->create();
            if ($this->FlowStage->save($datos)) {
                $id_inset       = $this->FlowStage->id;
                $this->updateStateProspectiveFlow($datos['FlowStage']['prospective_users_id'],Configure::read('variables.control_flujo.flujo_negociado'));
                $this->saveDataLogsUser(1,'FlowStage',$id_inset,Configure::read('variables.nombre_flujo.flujo_cotizado').' - '.Configure::read('variables.nombre_flujo.flujo_negociado'));
                $this->saveAtentionTimeFlujoEtapas($datos['FlowStage']['prospective_users_id'],'negociado_date','negociado_time','negociado');

                $cotizacion                          = $this->Quotation->findById($this->desencriptarCadena($this->request->data["quotation"]));
                $rutaURL = 'Quotations/view/'.$this->request->data["quotation"];


                $user = $this->User->field("email", ["id" => $cotizacion["ProspectiveUser"]["user_id"] ]);

                $emailGerentes = $this->User->role_gerencia_user();

                if(!empty($emailGerentes) && !empty($user)){
                    $emailGerentes = Set::extract($emailGerentes, "{n}.User.email");
                    $emailGerentes[] = $user;

                    $options = array(
                        'to'        => $emailGerentes,
                        'template'  => 'quote_notification',
                        'subject'   => 'Aprobación cotización '.$cotizacion['Quotation']['codigo'].' de KEBCO AlmacenDelPintor.com',
                        'vars'      => array('ruta'=>$rutaURL,"type" => "aprovee", "correoPrincipal" => $this->request->data["correoPrincipal"], "flujo" => $cotizacion["ProspectiveUser"]["id"], "comentarioCliente" => $this->request->data["comentarioCotizacion"] )
                    ); 
                    $this->sendMail($options);
                }

                $this->comment_quotation(true);
                $this->Session->setFlash('Cotización aprobada','Flash/success');
            }
            return $datos['FlowStage']['prospective_users_id'];
        } else {
            return $documento;
        }

        
    }

    public function resend_quotation(){
        $this->autoRender = false;
        $description = "El cliente / correo reenvio la cotización a la persona: ".$this->request->data["nombrePersona"]. ", al correo: ". $this->request->data["correoPersona"];

        $this->request->data["comentarioQt"] = $description;
        $cotizacion                          = $this->Quotation->findById($this->desencriptarCadena($this->request->data["quotation"]));

        $mensaje = "Hola, \n <br> el usuario: ".$this->request->data["correoPrincipal"]." te ha compartido está cotización.";
        $rutaURL = 'Quotations/view/'.$this->request->data["quotation"];

        $this->loadModel("User");

        $user = $this->User->field("email", ["id" => $cotizacion["ProspectiveUser"]["user_id"] ]);

        $emailGerentes = $this->User->role_gerencia_user();

        if(!empty($emailGerentes) && !empty($user)){
            $emailGerentes = Set::extract($emailGerentes, "{n}.User.email");
            $emailGerentes[] = $user;

            $options = array(
                'to'        => $emailGerentes,
                'template'  => 'quote_notification',
                'subject'   => 'Reenvío de la cotización '.$cotizacion['Quotation']['codigo'].' de KEBCO AlmacenDelPintor.com',
                'vars'      => array('ruta'=>$rutaURL,"type" => "resend", "correoPrincipal" => $this->request->data["correoPrincipal"], "nombrePersona" => $this->request->data["nombrePersona"], "correoPersona" =>  $this->request->data["correoPersona"])
            ); 
            $this->sendMail($options);
        }

        $this->comment_quotation(true);
        $this->sendEmailInformationQuotation($rutaURL,$this->request->data["nombrePersona"],$cotizacion['Quotation']['codigo'],$mensaje, $this->request->data["correoPersona"]);

        


        $this->Session->setFlash('Cotización reenviado correctamente','Flash/success');
    }

    public function sendEmailInformationQuotation($rutaURL,$name_cliente,$codigoCotizacion, $textoCliente = null, $personaEmail = null){

        $this->loadModel("FlowStage");

        $email_defecto              = Configure::read('variables.emails_defecto');
        $emailCliente               = array($personaEmail);
        
        $rutaURL.="?e=".$this->encryptString(implode(",", $emailCliente));

        $datosFlujo                 = $this->FlowStage->ProspectiveUser->get_data($this->request->data['flujo']);
        $datos_asesor               = $this->FlowStage->ProspectiveUser->User->get_data($datosFlujo['ProspectiveUser']['user_id']);
        $requerimientoFlujo         = $this->FlowStage->find_reason_prospective($this->request->data['flujo']);
        if (file_exists(WWW_ROOT.'/files/quotations/'.$codigoCotizacion.'.pdf')) {
            $options                    = array(
                'to'        => $emailCliente,
                'template'  => 'quote_sent',
                'subject'   => 'Has recibido la cotización '.$codigoCotizacion.' de KEBCO AlmacenDelPintor.com',
                'vars'      => array('codigo' => $codigoCotizacion,'nameClient' => $name_cliente,'nameAsesor' => $datos_asesor['User']['name'],'requerimiento' => $requerimientoFlujo,'ruta'=>$rutaURL,"texto" => $textoCliente),
                //'file'        => 'files/quotations/'.$codigoCotizacion.'.pdf'
            );
        } else {
            $options = array(
                'to'        => $emailCliente,
                'template'  => 'quote_sent',
                'subject'   => 'Has recibido la cotización '.$codigoCotizacion.' de KEBCO AlmacenDelPintor.com',
                'vars'      => array('codigo' => $codigoCotizacion,'nameClient' => $name_cliente,'nameAsesor' => $datos_asesor['User']['name'],'requerimiento' => $requerimientoFlujo,'ruta'=>$rutaURL,"texto" => $textoCliente)
            );
        }

        if(!empty($datos_asesor["User"]["password_email"])){
            $email      = $datos_asesor["User"]["email"] == "jotsuar@gmail.com" ? "pruebascorreojs1@gmail.com" : $datos_asesor["User"]["email"];
            $password   = str_replace("@@KEBCO@@", "", base64_decode($datos_asesor["User"]["password_email"]) );
            $config     = array("username" => $email, "password" => $password);

            $this->sendMail($options, null, $config);
        }else{
            $this->sendMail($options);
        }       
    }

    public function comment_quotation($show = null){
        $this->autoRender = false;
        $this->loadModel("ProgresNote");
        $this->loadModel("ProspectiveUser");
        $this->loadModel("Manage");

        $prospecto = $this->ProspectiveUser->findById($this->request->data["flujo"]);

        $datos = array(
            "ProgresNote" => array(
                "id" => null,
                "prospective_users_id" => $this->request->data["flujo"],
                "etapa" => "Cotizado",
                "description" => ! $show ? "Comentario realizado por el cliente / correo: ".$this->request->data["correoPrincipal"]." <br> \n Comentario: ".$this->request->data["comentarioQt"] : $this->request->data["comentarioQt"],
                "user_id" => $prospecto["ProspectiveUser"]["user_id"]
            )
        );

        $datosNotification = array(
            "Manage" => array(
                "description" => $datos["ProgresNote"]["description"],
                "date" => date("Y-m-d"),
                "time" => date("H:i:s"),
                "time_end" => date("H:i:s",strtotime("+120 minute")),
                "url" => $this->webroot."/prospectiveUsers/adviser?q=".$this->request->data["flujo"],
                "user_id" => $prospecto["ProspectiveUser"]["user_id"],
                "prospective_users_id" => $this->request->data["flujo"],
                "state_flow" => "Cotizado",
                "state" => 0,
                "id" => null
            )
        );

        $this->ProgresNote->save($datos);
        $this->Manage->save($datosNotification);

        if(is_null($show)){
            $cotizacion                          = $this->Quotation->findById($this->desencriptarCadena($this->request->data["quotation"]));
            $this->Session->setFlash('Comentario enviado correctamente','Flash/success');

            $this->loadModel("User");

            $user           = $this->User->field("email", ["id" => $cotizacion["ProspectiveUser"]["user_id"] ]);
            $emailGerentes  = $this->User->role_gerencia_user();
            $rutaURL        = 'Quotations/view/'.$this->request->data["quotation"];

            if(!empty($emailGerentes) && !empty($user)){
                $emailGerentes = Set::extract($emailGerentes, "{n}.User.email");
                $emailGerentes[] = $user;

                $options = array(
                    'to'        => $emailGerentes,
                    'template'  => 'quote_notification',
                    'subject'   => 'Comentario de la cotización '.$cotizacion['Quotation']['codigo'].' de KEBCO AlmacenDelPintor.com',
                    'vars'      => array('ruta'=>$rutaURL,"type" => "comment", "correoPrincipal" => $this->request->data["correoPrincipal"], "comentarioQt" => $this->request->data["comentarioQt"], "flujo" => $cotizacion["ProspectiveUser"]["id"])
                ); 
                $this->sendMail($options);
            }

        }
    }

    public function changeTrm(){

        $this->autoRender = false;
        $this->loadModel("User");

        $users = $this->User->role_gerencia_user();

        foreach ($users as $key => $value) {
            $emails[] = $value["User"]["email"];
        }

        $usuario    = AuthComponent::user("name");
        $razon      = $this->request->data["razon"];

        $options = array(
            'to'        => $emails,
            'template'  => 'change_trm',
            'subject'   => "Solicitud de cambio de TRM",
            'vars'      => compact("usuario","razon")
        );
        
        $this->sendMail($options);
    }

    public function changeCost(){
        $this->autoRender = false;
        $this->loadModel("User");
        $this->loadModel("Product");

        $users = $this->User->role_gerencia_user();

        $emails   = array("logistica@kebco.co");
        $emails[] = "ventasbogota@almacendelpintor.com";

        foreach ($users as $key => $value) {
            $emails[] = $value["User"]["email"];
        }

        $usuario    = AuthComponent::user("name");
        $razon      = $this->request->data["razon"];

        $this->Product->recursive = -1;
        $producto   = $this->Product->findById($this->request->data["id"]);
        $product    = $producto["Product"];

        $options = array(
            'to'        => $emails,
            'template'  => 'change_cost',
            'subject'   => "Solicitud de cambio de Costo",
            'vars'      => compact("usuario","razon","product")
        );
        
        $this->sendMail($options);
    }


    public function show_data(){
        $this->layout = false;
        $this->loadModel("Config");

        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        // $factorImport   = $config["Config"]["factorUSA"];

        $currency = $this->request->data["currency"];

        $this->set(compact("trmActual","factorImport","currency"));
        
        foreach ($this->request->data as $key => $value) {
            $this->set($key,$value);
        }
    }

    public function orderArray(){
        $this->autoRender                               = false;
        if ($this->request->is('ajax')) {
            $this->Session->delete('carritoProductos');
            $array_ids                                  = $this->request->data['array_ids'];
            $this->Session->write('carritoProductos',$array_ids);

            if(!isset($this->request->data["noSleep"])){
                sleep(3);
            }
            return true;


        }
    }

    public function create_flow_client($dataCustomer){
        $conversation_id     = $this->request->data["conversation_id"];
        $customer            = $this->getClientByPhone($phone);
        $this->request->data = ["ProspectiveUser" => [] ];

        $this->request->data["ProspectiveUser"]["email_customer"]     = "";

        if (is_null($customer)) {
            $this->request->data["ProspectiveUser"]["phone_customer"] = $phone;
        }else{
            if ($customer["type"] == "legal") {
                $this->request->data["ProspectiveUser"]["contacs_users_id"]     = $customer["id"];
                $this->request->data["ProspectiveUser"]["clients_natural_id"]   = null;
            }else{
                $this->request->data["ProspectiveUser"]["contacs_users_id"]   = 0;
                $this->request->data["ProspectiveUser"]["clients_natural_id"] = $customer["id"];
            }
        }

        $this->request->data["ProspectiveUser"]["user_id"]     = $this->ProspectiveUser->User->field("id",["email"=>$email]);
        $this->request->data["ProspectiveUser"]["description"] = !empty($linea) ? "El cliente está interesado en la línea: ".$linea : "Sin información" ;
        $this->request->data["ProspectiveUser"]["state_flow"]       = 1;
        $this->request->data['ProspectiveUser']['user_receptor']    = $this->request->data["ProspectiveUser"]["user_id"] ;
        $this->request->data['ProspectiveUser']['image']            = null;
        $this->request->data['ProspectiveUser']['origin']           = 'Chat';
        $this->request->data['ProspectiveUser']['page']             = Configure::read("URL_CHAT_CONVERSATION").$conversation_id;
        $this->request->data["ProspectiveUser"]["reason"]           = $this->request->data["ProspectiveUser"]["description"];

        $this->ProspectiveUser->create();
        $this->ProspectiveUser->save($this->request->data);

        $flujoIdData = $this->ProspectiveUser->id;
    }

    public function add($prospective_users_id,$flujo_id,$negociacion = 0) {
        
        $this->loadModel('Template');
        $this->loadModel('Note');
        $this->loadModel('Notice');

        if (!isset($this->request['data']['Quotation']['etapa_id'])) {
            // $this->deleteCacheProducts();
        }
        $headers                            = $this->Quotation->Header->get_headers_list();
        $plantillas                         = $this->Template->get_templates_list();
        $datos                              = $this->Quotation->FlowStage->ProspectiveUser->get_data_model_user($prospective_users_id,1);

        if(!$this->request->is('post')){
            $this->loadModel("DraftInformation");
            $fields   = ["prospective_users_id"=>$prospective_users_id,"flow_stage_id"=>$flujo_id];
            $borrador = $this->DraftInformation->find("first",["conditions"=>$fields]);

            if(empty($borrador)){
                $fields["name"] = "Cotizacion: ".$datos["ProspectiveUser"]["description"];
                $fields["header_id"] = $datos["ProspectiveUser"]["country"] != "Colombia" ? 2 : 3;
                $fields["user_id"]   = $datos["ProspectiveUser"]["user_id"];
                $this->DraftInformation->create();
                $this->DraftInformation->save($fields);
            }
        }

        $validateContact    = $this->validateTimes(true);
        $valid              = true;

        $this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];

        if  (!empty($validateContact["contact"])  || ( !empty($validateContact["quotation"]) && !in_array($prospective_users_id, $validateContact["quotation"] ) )  ) {
            $this->Session->setFlash(__('No tienes permitido cotizar, debes primero gestionar los flujos pendientes. '. "Flujos sin contactar: ".implode(",", $validateContact["contact"]). " | Flujos sin cotizar: ".implode(",",$validateContact["quotation"]) ),'Flash/error');
            $this->redirect(["action"=>"index", "controller"=>"prospective_users"]);
        }

        if (!is_null($datos["ProspectiveUser"]["user_lose"]) && $datos["ProspectiveUser"]["user_lose"] == AuthComponent::user("id") ) {
            $this->Session->setFlash(__('No tienes permitido cotizar un flujo que te fue reasignado.'),'Flash/error');
            $this->redirect(["action"=>"index"]);
        }

        $productClient                      = array();
        if ($datos['ProspectiveUser']['type'] > 0) {
            $productClient                  = $this->Quotation->FlowStage->ProspectiveUser->TechnicalService->ProductTechnical->get_all($datos['ProspectiveUser']['type']);
        }
        if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
            $datosC                         = $this->Quotation->FlowStage->ProspectiveUser->ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
        } else {
            $datosC                         = $this->Quotation->FlowStage->ProspectiveUser->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
        }
        $notas_previas                      = $this->Note->data_notes_previas();
        $notas_descriptivas                 = $this->Note->data_notes_descriptiva();
        $formas_pago                        = $this->Note->data_conditions_negocio();
        $cotizacionesOption                 = $this->Quotation->list_data_prospective($prospective_users_id);
        if ($this->request->is('post')) {
            $arrayIdProducts                                                = $this->Session->read('carritoProductos');
            if (count($arrayIdProducts) > 0) {

                
                $garantiaGeneral                                            = $this->Note->field("description",["type" => 5]);
                $this->request->data['Quotation']['flow_stage_id']          = $flujo_id;
                $this->request->data['Quotation']['prospective_users_id']   = $prospective_users_id;
                $this->request->data['Quotation']['total_visible']          = $this->request->data['radio_option'];
                $this->request->data['Quotation']['show_iva']               = isset($this->request->data['radio_option_iva']) ? $this->request->data['radio_option_iva'] : 0;
                $this->request->data['Quotation']['user_id']                = $this->request->data['Quotation']['user_cotiza'];
                $this->request->data['Quotation']['total']                  = $this->replaceText($this->request->data['Quotation']['total'],",", "");
                $this->request->data['Quotation']['codigo']                 = $this->generateIdentificationQuotation($prospective_users_id);
                $this->request->data['Quotation']['garantia']               = empty($garantiaGeneral) ? "" : $garantiaGeneral;

                if($this->request->data["Quotation"]["show_ship"] == 0 && $this->request->data["Quotation"]["header_id"] != 3){
                    $totalShipping = $this->validateShippingQuotation($arrayIdProducts,$trmActual);
                    if($totalShipping > 0){
                        $total         = floatval($this->request->data["Quotation"]["total"]) - $totalShipping;
                        $this->request->data["Quotation"]["total"] = $total;
                    }
                }

                if (isset($this->request->data["Quotation"]["currency_data"])) {
                    $this->request->data["Quotation"]["currency"] = $this->request->data["Quotation"]["currency_data"];
                }else{
                    $this->request->data["Quotation"]["currency"] = "cop";                  
                }

                // if (floatval($this->request->data["Quotation"]["total"]) > 10000000) {
                //     $htmlOther = "¿Es cliente final?: ".$this->request->data["Quotation"]["es_cliente"]. " <br>";
                //     $htmlOther .= "¿Actualmente se compite con otra empresa?: ".$this->request->data["Quotation"]["competencia"]. " <br>";
                //     $htmlOther .= "¿Ya se ha cotizado antes a este cliente?: ".$this->request->data["Quotation"]["ya_cotizado"]. " <br>";
                //     $htmlOther .= "¿La compra será licitación o compra directa?: ".$this->request->data["Quotation"]["tipo_compra"]. " <br>";
                //     $htmlOther .= "¿Cuando tienen pensado realizar la compra?: ".$this->request->data["Quotation"]["fecha_comptra"]. " <br> Nota: ";

                //     $copyReason = $this->request->data["Quotation"]["reason"];

                //     $this->request->data["Quotation"]["reason"] = $htmlOther.$copyReason;
                // }

                $this->Quotation->create();
                if($this->Quotation->save($this->request->data)){
                    $id_inset                                               = $this->Quotation->id;
                    $this->saveQuotationProduct($arrayIdProducts,$id_inset);
                    if ($negociacion == Configure::read('variables.control_flujo.flujo_negociado')) {
                        $datosQuotation                                     = $this->Quotation->get_data($id_inset);
                        $datosF['FlowStage']['document']                    = $datosQuotation['Quotation']['id'];
                        $datosF['FlowStage']['priceQuotation']              = $datosQuotation['Quotation']['total'];
                        $datosF['FlowStage']['codigoQuotation']             = $datosQuotation['Quotation']['codigo'];
                        $datosF['FlowStage']['state_flow']                  = Configure::read('variables.nombre_flujo.flujo_cotizado');
                        $datosF['FlowStage']['prospective_users_id']        = $prospective_users_id;
                        // $datos['FlowStage']['id']                            = $this->FlowStage->new_row_model() + 1;
                        $this->Quotation->FlowStage->create();
                        $this->Quotation->FlowStage->save($datosF);
                        $this->updateStateProspectiveFlow($prospective_users_id,Configure::read('variables.control_flujo.flujo_cotizado'));
                        $this->Session->setFlash('Cotización creada correctamente, no te preocupes, la cotización no ha sido enviada al cliente', 'Flash/success');

                    } else {
                        $this->deleteInformationBorrador($prospective_users_id);
                        $this->deleteProductBorrador($flujo_id);
                        $this->Session->setFlash('Cotización creada correctamente, recuerda que la debes enviar al cliente', 'Flash/success');
                    }
                    // $this->addPdf($datos,$id_inset,$datosC,$productClient);
                    $this->saveDataLogsUser(2,'Quotation',$id_inset);
                    $this->redirect(array('action'=>'view',$this->encryptString($id_inset)));
                } else {
                    $this->Session->setFlash('Debes seleccionar mínimo un producto para la Cotización','Flash/error');
                }
            }
        }else{
            $this->Session->setFlash('Recuerda que puedes utilizar la opción "Mensaje al cliente" para enviar un mensaje personalizado al cliente.', 'Flash/success');
        }
        $this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $bloqueo        = $config["Config"]["bloqueoMargen"];
        $users_money    = explode(",", $config["Config"]["users_money"]);
        $users_request  = explode(",", $config["Config"]["users_request"]);
        $requestChange  = 0;
        $notices        = $this->Notice->find("list",["conditions"=>["Notice.user_id" => AuthComponent::user("id") ]]);

        if(!empty($users_request) && in_array($datos["ProspectiveUser"]["user_id"], $users_request)){
            $requestChange = 1;
        }

        $categoriesInfoFinal = $this->getCagegoryData();

        $addProduct = $this->validateAddProducts();
        $editProduct = $this->validateEditProducts();

        $this->loadModel("Abrasivo");

        $pricesAbrasivo     = $this->Abrasivo->find("all");
        // $pricesAbrasivo = [];

        // foreach ($sAbrasivos as $key => $value) {
        //     $pricesAbrasivo[$value["Abrasivo"]["type"]][] = $value;
        // }


        $this->set(compact('datos','datosC','plantillas','flujo_id','prospective_users_id','notas_previas','notas_descriptivas','formas_pago','productClient','headers','cotizacionesOption','bloqueo','categoriesSelect','addProduct','editProduct','categoriesInfoFinal','users_money','requestChange','notices','pricesAbrasivo'));
    }
    
    public function changeCountry() {
        $this->autoRender = false;
        $this->loadModel("ProspectiveUser");
        $this->ProspectiveUser->recursive = -1;
        $datos = $this->ProspectiveUser->findById($this->request->data["flujo"]);
        $datos["ProspectiveUser"]["country"] = $this->request->data["country"];
        $this->ProspectiveUser->save($datos);
        $this->Session->setFlash('Cambio de país realizado correctamente.', 'Flash/success');
    }

    public function validateShippingQuotation($arrayIdProducts,$trm){
        $totalCop       = 0;
        $totalShipping  = 0;
        foreach ($arrayIdProducts as $key => $value) {
            $idPrducto = explode("-", $value)[1];
            if(in_array($idPrducto, ["3020"])){
                $totalShipping+=$this->request->data['Precio-'.trim($value)];
            }elseif (strtolower($this->request->data['Moneda-'.trim($value)]) == "cop") {
                $totalCop+=($this->request->data['Precio-'.trim($value)]*$this->request->data['Cantidad-'.trim($value)]);
            }elseif (strtolower($this->request->data['Moneda-'.trim($value)]) == "usd") {
                $totalCop+= round( ($this->request->data['Precio-'.trim($value)]*$this->request->data['Cantidad-'.trim($value)]) * $trm ) ;
            }
        }

        if($totalShipping > 0){

            foreach ($arrayIdProducts as $key => $value) {
                
                $idPrducto = explode("-", $value)[1];
                if(in_array($idPrducto, ["3020"])){
                    continue;
                }

                $precio          = strtolower($this->request->data['Moneda-'.trim($value)]) == "cop" ? ($this->request->data['Precio-'.trim($value)]*$this->request->data['Cantidad-'.trim($value)]) : round( ($this->request->data['Precio-'.trim($value)]*$this->request->data['Cantidad-'.trim($value)]) * $trm );

                $proporcion      = $precio / $totalCop;

                $costo_adicional = $totalShipping * $proporcion / $this->request->data['Cantidad-'.trim($value)];

                if(strtolower($this->request->data['Moneda-'.trim($value)]) == "cop"){
                    $this->request->data['Precio-'.trim($value)] += $costo_adicional;
                }else{
                    $this->request->data['Precio-'.trim($value)] += round($costo_adicional / $trm);                    
                }
            }

        }

        return 0;
    }

    public function saveQuotationProduct($arrayIdProducts,$id_inset){
        foreach ($arrayIdProducts as $value) {
            $datosM = array();
            $idPrducto = explode("-", $value)[1];
            $datosM[explode("-", $value)[0]]['QuotationsProduct']['quotation_id']   = $id_inset;
            $datosM[explode("-", $value)[0]]['QuotationsProduct']['product_id']     = $idPrducto;
            $datosM[explode("-", $value)[0]]['QuotationsProduct']['price']          = $this->request->data['Precio-'.trim($value)];
            $datosM[explode("-", $value)[0]]['QuotationsProduct']['currency']       = strtolower($this->request->data['Moneda-'.trim($value)]);
            // $datosM[explode("-", $value)[0]]['QuotationsProduct']['type']           = strtoupper($this->request->data['Cotiza-'.trim($value)]);
            // $datosM[$value]['QuotationsProduct']['price']            = $this->replaceText($datosM[$value]['QuotationsProduct']['price'],".", "");
            // $datosM[$value]['QuotationsProduct']['price']            = $this->replaceText($datosM[$value]['QuotationsProduct']['price'],",", "");
            $datosM[explode("-", $value)[0]]['QuotationsProduct']['quantity']       = $this->request->data['Cantidad-'.trim($value)];
            $datosM[explode("-", $value)[0]]['QuotationsProduct']['delivery']       = $this->request->data['Entrega-'.trim($value)];
            $datosM[explode("-", $value)[0]]['QuotationsProduct']['iva']       = $this->request->data['IVA-'.trim($value)];
            if (isset($this->request->data['Nota-'.trim($value)]))  {
                $datosM[explode("-", $value)[0]]['QuotationsProduct']['note']       = $this->request->data['Nota-'.trim($value)];                
            }else{
                $datosM[explode("-", $value)[0]]['QuotationsProduct']['note']       = null;
            }

            if($idPrducto == "3020"){
               $datosM[explode("-", $value)[0]]['QuotationsProduct']['margen'] = 1; 
               $datosM[explode("-", $value)[0]]['QuotationsProduct']['price']  = $datosM[explode("-", $value)[0]]['QuotationsProduct']['price'] < 500 ? 0 : $datosM[explode("-", $value)[0]]['QuotationsProduct']['price'];
            }else{
                $datosM[explode("-", $value)[0]]['QuotationsProduct']['margen']         = isset( $this->request->data['Margen_'.trim($idPrducto)."_".strtolower($this->request->data['Moneda-'.trim($value)])] ) ? $this->request->data['Margen_'.trim($idPrducto)."_".strtolower($this->request->data['Moneda-'.trim($value)])] : 0;
            }

            
            // $datosM[$value]['QuotationsProduct']['id']               = $this->Quotation->QuotationsProduct->new_row_model() + 1;
            $this->Quotation->QuotationsProduct->create(); 
            $this->Quotation->QuotationsProduct->saveAll($datosM);
        }
        
        
        $this->Session->delete('carritoProductos');
        return true;
    }

    /**
        * @author Diego Morales <dlmorales096@gmail.com>
        * @date(21-11-2019)
        * @description Metodo para devolver la previsualización de la cotización
        * @param  
        * @return Vista
    */
    public function preview_quotation(){
        $this->layout               = false;
        if ($this->request->is('ajax')) {
            $this->loadModel("Config");
            $this->loadModel("Note");
            $iva                        = $this->Config->field("ivaCol",["id" => 1]);
            $prospective_users_id       = $this->request->data['prospective_users_id'];
            $flujo_id                   = $this->request->data['flujo_id'];
            $header_option              = $this->request->data['header_option'];
            $notes_descriptiva          = $this->request->data['notes_descriptiva'];
            $customer_note              = $this->request->data['customer_note'];
            $notes                      = $this->request->data['notes'];
            $descuento                  = $this->request->data['descuento'];
            $conditions                 = $this->request->data['conditions'];
            $asunto                     = $this->request->data['asunto'];
            $total                      = $this->request->data['total'];
            $radio_option               = $this->request->data['radio_option'];
            $radio_option_iva           = $this->request->data['radio_option_iva'];
            $array_ids                  = $this->request->data['array_ids'];
            $currency                   = $this->request->data['currency'];
            $design                     = $this->request->data['design'];
            $garantiaGeneral            = $this->Note->field("description",["type" => 5]);
            $datos_producto             = json_decode($this->request->data['datos_producto'],true);
            $datosFlowStage             = $this->Quotation->FlowStage->get_data($flujo_id);
            $datos                      = $this->Quotation->FlowStage->ProspectiveUser->get_data($prospective_users_id);
            $codigo                     = $this->generateIdentificationQuotation($prospective_users_id);
            $datosHeaders               = $this->Quotation->Header->get_data($header_option);
            if ($datos['ProspectiveUser']['type'] > 0) {
                $productClient = $this->Quotation->FlowStage->ProspectiveUser->TechnicalService->ProductTechnical->get_all($datos['ProspectiveUser']['type']);
            } else {
                $productClient = array();
            }
            $datosUsuario               = $this->Quotation->User->get_data($this->request->data['user_cotiza']);
            if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
                $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
            } else {
                $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
            }
            $arrayIdProducts                                                = $this->Session->read('carritoProductos');
            $datosProductos                                                 = array();

            if(is_null($arrayIdProducts)){
                $arrayIdProducts = [];
            }
            foreach ($arrayIdProducts as $value) {

                $idPrducto = explode("-", $value)[1];

                $datosProductos[$value]                                     = $this->Quotation->QuotationsProduct->Product->get_data($idPrducto);
                $datosProductos[$value]['QuotationsProduct']['product_id']  = $idPrducto;
                $datosProductos[$value]['QuotationsProduct']['price']       = $datos_producto[0]['Precio-'.trim($value)];
                // $datosProductos[$value]['QuotationsProduct']['price']        = $this->replaceText($datosProductos[$value]['QuotationsProduct']['price'],".", "");
                // $datosProductos[$value]['QuotationsProduct']['price']        = $this->replaceText($datosProductos[$value]['QuotationsProduct']['price'],",", "");
                $datosProductos[$value]['QuotationsProduct']['quantity']    = $datos_producto[0]['Cantidad-'.trim($value)];
                $datosProductos[$value]['QuotationsProduct']['delivery']    = $datos_producto[0]['Entrega-'.trim($value)];
                $datosProductos[$value]['QuotationsProduct']['iva']         = $datos_producto[0]['IVA-'.trim($value)];
                $datosProductos[$value]['QuotationsProduct']['currency']    = $datos_producto[0]['Moneda-'.trim($value)];
                $datosProductos[$value]['QuotationsProduct']['note']        = isset($datos_producto[0]['Nota-'.trim($value)]) ?  $datos_producto[0]['Nota-'.trim($value)] : "";
            }

            $this->set(compact('notes_descriptiva','notes','conditions','datosC','datosUsuario','codigo','datos','asunto','datosHeaders','total','radio_option','datosProductos','currency','iva','productClient','descuento','garantiaGeneral','radio_option_iva'));

            if($design == 2){
                $this->render('preview_quotation_especial');
            }
        }
    }

    public function addPdf($datos,$id_inset,$datosC,$productClient){
        $nombreArchivo                  = $this->request->data['Quotation']['codigo'].'.pdf';
        $datosHeaders                   = $this->Quotation->Header->get_data($this->request->data['Quotation']['header_id']);
        $codigoQuotation                = $this->request->data['Quotation']['codigo'];
        $datosUsuario                   = $this->Quotation->User->get_data($datos['ProspectiveUser']['user_id']);
        $datosQuation                   = $this->Quotation->get_data($id_inset);
        $datosProductos                 = $this->Quotation->QuotationsProduct->get_data_quotation($id_inset);
        $this->loadModel("Config");
        $iva            = $this->Config->field("ivaCol",["id" => 1]);
        $options                        = array(
            'template'  => 'new_quotation',
            'ruta'      => APP . 'webroot/files/quotations/'.$nombreArchivo,
            'vars'      => array('datosHeaders' => $datosHeaders,'codigoQuotation' => $codigoQuotation,'datos' => $datos,'datosC' => $datosC, 'datosUsuario' => $datosUsuario, 'productClient' => $productClient, 'datosQuation' => $datosQuation, 'datosProductos' => $datosProductos,"iva" => $iva),
        );
        $this->generatePdf($options);
        return true;
    }

    public function find_cotizaciones_option_flujo(){
        $this->layout               = false;
        if ($this->request->is('ajax')) {
            $documentosList         = $this->Quotation->all_data_prospective($this->request->data['id_flujo_buscar']);
            $this->set(compact('documentosList'));
        }
    }

    private function validateBase64($data){
        if ( base64_encode(base64_decode($data)) === $data){
          return true;
        } 
        return false;
    }

    public function factura($cotizacion_id){
        $this->autoRender = false;
        $cotizacion_id = $this->desencriptarCadena($cotizacion_id);
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

    public function action_payment_qtprod($qt_product, $quotation_id){
        $this->layout = false;

        $quotation_id      = $this->desencriptarCadena($quotation_id);
        $qt_product        = $this->desencriptarCadena($qt_product);

        $datosQuation      = $this->Quotation->get_data($quotation_id);
        $datosProduct      = $this->Quotation->QuotationsProduct->findById($qt_product);
        $datos             = $this->Quotation->ProspectiveUser->findById($datosQuation["Quotation"]["prospective_users_id"]);

        if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
            $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
        } else {
            $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
        }


        $this->set(compact('datosQuation','datosProduct','qt_product','quotation_id','datosC','datos'));
    }

    public function action_payment_quotation($quotation_id, $abono = null){
        $this->layout = false;

        $quotation_id      = $this->desencriptarCadena($quotation_id);
        $datosQuation      = $this->Quotation->recursive = 2;
        $datosQuation      = $this->Quotation->findById($quotation_id);
        $datos             = $this->Quotation->ProspectiveUser->findById($datosQuation["Quotation"]["prospective_users_id"]);

        if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
            $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
        } else {
            $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
        }

        $productosCotizacion        = $datosQuation["QuotationsProduct"];

        $totalCop         = 0;
        $discount         = 0;
        $totalParaIva     = 0;
        $totalIVa         = 0;
        $totalFinal       = 0;

        $totalUsdOriginal = 0;
        $totalUsdCop      = 0;


        $this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];

        if(!empty($productosCotizacion)){

            foreach ($productosCotizacion as $key => $value) {
                if($value["currency"] == "usd"){
                    $totalUsdOriginal += ($value["price"])*($trmActual*1.05)*$value["quantity"];
                    $value["price"] = ($value["price"])*($trmActual*1.05);
                }else{
                    $totalUsdCop      += ($value["price"]*$value["quantity"]);
                }

                if($datosQuation["Quotation"]["header_id"] != 3 && $datosQuation["Quotation"]["show_ship"] == 0 && in_array($value["Product"]["part_number"],['S-003']) ){
                    continue;
                }

                if($datosQuation["Quotation"]["header_id"] == 3 || $datosQuation["Quotation"]["show_ship"] == 1  || (!in_array($value["Product"]["part_number"],['S-003']) && $datosQuation["Quotation"]["created"] >= '2025-02-15 00:00:00' && $datosQuation["Quotation"]["show_ship"] == 0)){
                        if ($value["iva"] == 1) {
                        $totalParaIva += ($value["price"]*$value["quantity"]);                            
                    }else{
                        $totalCop += ($value["price"]*$value["quantity"]);
                    }
                }

            }

            if($datosQuation["Quotation"]["descuento"] > 0){
                $discount = $datosQuation["Quotation"]["descuento"];
            }

            $this->loadModel("Autorization");

            if($discount > 0){
                $percentDiscount = 100 - $discount;
                $percentDiscount = $percentDiscount <= 0 ? 1 : ($percentDiscount / 100);
                $totalParaIva *= $percentDiscount;
                $totalCop *= $percentDiscount;
                $totalUsdOriginal *= $percentDiscount;
                $totalUsdCop *= $percentDiscount;
            }
        }

        $totalIVa   = round($totalParaIva * 0.19,2);
        $totalFinal = ($totalParaIva*1.19) + $totalCop;

        if(!is_null($abono)){

            $totalFinal = round(($totalUsdCop+($totalUsdOriginal/2))*1.19);
            $totalIVa   = round(($totalUsdCop+($totalUsdOriginal/2))*0.19);
        }

        $this->set(compact('datosQuation','datosProduct','quotation_id','datosC','datos',"totalIVa","totalFinal","abono","totalUsdOriginal","totalUsdCop"));
    }

    public function payments_ipn($referencia)
    {
        $this->autoRender = false;

        $reqData = $this->request->data;

        if(isset($reqData["estado_transaccion"]) && $reqData["estado_transaccion"] == "OK" && isset($reqData["codigo_retorno"]) && $reqData["codigo_retorno"] == "SUCCESS"){
            $partsRef           = explode("Z", $reqData["referencia"]);

            $datosQuation       = [];
            if($partsRef[0] == 'COTCRMZ'){
                $datosProduct   = $this->Quotation->QuotationsProduct->findById($partsRef[0]);
            }

            $datosQuation      = $this->Quotation->get_data($partsRef[1]);

            $datos             = $this->Quotation->ProspectiveUser->findById($datosQuation["Quotation"]["prospective_users_id"]);

            if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
                $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ContacsUser->findById($datos['ProspectiveUser']['contacs_users_id']);
            } else {
                $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
            }

            if($datos['ProspectiveUser']['contacs_users_id'] > 0){
                $email      = $datosC['ContacsUser']['email'];
                $cliente    = $datosC['ContacsUser']['name'] ." - ".$datosC['ClientsLegal']['email'];
            }else{
                $email      = $datosC['ClientsNatural']['email'];
                $cliente    = $datosC['ClientsNatural']['name'];
            }


            $options = array(
                'to'        => $datos["User"]["email"],
                'template'  => 'pago_recibido',
                'cc'        => [
                                'gerencia@almacendelpintor.com',
                                    'sistemas@almacendelpintor.com',
                                    'contabilidad@almacendelpintor.com'
                                ],
                'subject'   => 'Nuevo pago recibido por PSE - '.$reqData["autorizacion"],
                'vars'      => array('cliente' => $cliente,'email' => $email,'reqData' => $reqData,'datosQuation' => $datosQuation,'datosProduct'=>$datosProduct, "name" => $datos["User"]["name"] )
            );

            $this->sendMail($options);

        }

        $this->log($this->request->data,"debug");
        $this->log($this->request->input(),"debug");
    }


    public function detail_product($cotizacion_id,$qt_product_id)
    {
        $this->Quotation->QuotationsProduct->recursive = -1;
        $cotizacion_id        = $this->desencriptarCadena($cotizacion_id);
        $qt_product_id        = $this->desencriptarCadena($qt_product_id);
        $datosQuation         = $this->Quotation->findById($cotizacion_id);
        $dataProductQt        = $this->Quotation->QuotationsProduct->findById($qt_product_id);
        $dataProduct          = $this->Quotation->QuotationsProduct->Product->findById($dataProductQt["QuotationsProduct"]["product_id"]);

        $this->loadModel("Config");
        $this->loadModel("Trm");
        $iva            = $this->Config->field("ivaCol",["id" => 1]);
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $factorImport   = $config["Config"]["factorUSA"];
        $trmDay         = $this->Trm->field("valor",["fecha_inicio >=" => date("Y-m-d",strtotime($datosQuation["Quotation"]["created"])), "fecha_fin <="=> date("Y-m-d",strtotime($datosQuation["Quotation"]["created"])) ]);

        $this->setSuggestedProducts([$dataProductQt],$trmActual);

        $this->set(compact('datosQuation','dataProductQt','dataProduct'));
    }

    public function view($cotizacion_id,$phone = null){

        $dataPhone = $this->desencriptarCadena($phone);
        if(!is_null($phone)){
            throw new NotFoundException('La cotización no existe');
        }

        $extension = isset($this->request->params['ext']) ? $this->request->params['ext'] : '';

        if ($extension === 'pdf') {
            return $this->redirect(['action' => 'view',$cotizacion_id]);
        }

        if(isset($this->request->data["modal"])){
            $this->layout = false;
        }

        $this->loadModel("EmailTracking");


        if(isset($this->request->query["e"])){
            $this->set("edata",$this->request->query["e"]);
        }
        if($this->validateBase64(base64_decode($cotizacion_id))){
            $cotizacion_id = base64_decode($cotizacion_id);
        }elseif(!is_numeric($cotizacion_id)){
            $cotizacion_id        = $this->desencriptarCadena($cotizacion_id);
        }
        if (!$this->Quotation->exists($cotizacion_id)) {
            throw new NotFoundException('La cotización no existe');
        }

        $datosQuation               = $this->Quotation->get_data($cotizacion_id);
        $this->Quotation->ProspectiveUser->recursive = -1;
        $qtData                     = $this->Quotation->ProspectiveUser->findById($datosQuation['Quotation']['prospective_users_id']);
        $notPermitido               = false;

        if(is_null($datosQuation['Quotation']['deadline'])){
            if ($qtData["ProspectiveUser"]["state_flow"] < 3) {
                $notPermitido = true;
            }
            $emailUser = $this->emailClientFlujo($datosQuation['Quotation']['prospective_users_id']);
            $datosTrack = $this->EmailTracking->findByQuotationIdAndEmail($cotizacion_id,$emailUser);

            if(!empty($datosTrack)){
                $this->set("datosTrack",$datosTrack);
            }

            if ($notPermitido && isset($this->request->params["ext"]) && $this->request->params["ext"] == "pdf") {
                die("Acción no permitida");
            }
            $datosFlowStage             = $this->Quotation->FlowStage->get_data($datosQuation['Quotation']['flow_stage_id']);
        }else{
            $datosFlowStage             = $this->Quotation->FlowStage->get_data($datosQuation['Quotation']['flow_stage_id']);
            
        }


        

        $this->pdfConfig            = array(
                        'download'          => false,
                        'filename'          => 'cotizacion-'.$this->encryptString($datosQuation['Quotation']['codigo']).'.pdf',
                        'orientation'       => 'Potrait',
                        'download'          => false

        );
        if ($datosQuation['Quotation']['notes'] == '<br>') {
            $datosQuation['Quotation']['notes'] = '';
        }
        $datosHeaders               = $this->Quotation->Header->get_data($datosQuation['Quotation']['header_id']);

        try {
            $otherData                  = $this->Quotation->FlowStage->find("first",["recursive"=>-1,"conditions" => ["id" => $this->Quotation->FlowStage->id_latest_regystri_state_cotizado($datosQuation['Quotation']['prospective_users_id']) ]]);
            if(!empty($otherData) && $otherData["FlowStage"]["document"] == $cotizacion_id && AuthComponent::user("id")){
                $this->set("reenviar", true);
                $this->set("flowData", $otherData["FlowStage"]["id"]);
                $this->set("flujoDataId", $datosQuation['Quotation']['prospective_users_id']);
            }
            
        } catch (Exception $e) {
            
        }


        $datos                      = $this->Quotation->FlowStage->ProspectiveUser->get_data($datosQuation['Quotation']['prospective_users_id']);
        if ($datosQuation['Quotation']['prospective_users_id'] > 0 && $datos['ProspectiveUser']['type'] > 0) {
            $productClient = $this->Quotation->FlowStage->ProspectiveUser->TechnicalService->ProductTechnical->get_all($datos['ProspectiveUser']['type']);
            $datosUsuario               = $this->Quotation->User->field("role",["id" => $qtData['ProspectiveUser']['user_id'] ]) == "Gerente General" ? $this->Quotation->User->get_data($qtData['ProspectiveUser']['user_id']) : $this->Quotation->User->get_data($datosQuation['Quotation']['user_id']);
        } else {
            $productClient = array();
            $datosUsuario               = $this->Quotation->User->get_data($datosQuation['Quotation']['user_id']);
        }

        if($datosQuation['Quotation']['prospective_users_id'] > 0 ){


            if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
                $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
            } else {
                $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
            }
        }else{
            if($datosQuation["Quotation"]["clients_natural_id"] != null){
                $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ClientsNatural->get_data($datosQuation["Quotation"]["clients_natural_id"]);
            }elseif($datosQuation["Quotation"]["contacs_user_id"] != null){
                $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ContacsUser->get_data_modelos($datosQuation["Quotation"]["contacs_user_id"]);
            }
        }


        $datosProductos             = $this->Quotation->QuotationsProduct->get_data_quotation($cotizacion_id,true);
        $mostrarTodos               = false;

        $this->loadModel("Config");
        $this->loadModel("Trm");
        $iva            = $this->Config->field("ivaCol",["id" => 1]);
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $factorImport   = $config["Config"]["factorUSA"];
        $trmDay         = $this->Trm->field("valor",["fecha_inicio >=" => date("Y-m-d",strtotime($datosQuation["Quotation"]["created"])), "fecha_fin <="=> date("Y-m-d",strtotime($datosQuation["Quotation"]["created"])) ]);

        if (isset($this->request->data["biiled"])) {
            $totalProducts = count($datosProductos);
            $totalFactured = 0;
            foreach ($datosProductos as $key => $value) {
                if ($value["QuotationsProduct"]["biiled"] != 1) {
                    $totalFactured++;
                }
            }
            if ($totalProducts == $totalFactured) {
                $mostrarTodos = 0;
            }
        }else{
            if(!empty($datosProductos)){
                $produtosCotizacion   = $datosProductos;
                $totalUsdOriginal = 0;
                $totalUsdCop      = 0;
                $discount         = 0;
                $valor_envio      = 0;
                $usdData          = 0;
                $usdData          = [];
                $usdData          = [];

                $quotationData = end($produtosCotizacion);
                foreach ($produtosCotizacion as $key => $value) {


                    if($quotationData["Quotation"]["header_id"] == 3 || $quotationData["Quotation"]["show_ship"] == 1  || (!in_array($value["Product"]["part_number"],['S-003']) && $quotationData["Quotation"]["show_ship"] == 0) ){

                        $totalCop += ($value["QuotationsProduct"]["price"]*$value["QuotationsProduct"]["quantity"]); 
                        if ($value["QuotationsProduct"]["change"] == 1 || $value["QuotationsProduct"]["currency"] == "usd") {
                            $totalUsdOriginal += ($value["QuotationsProduct"]["price"]*$value["QuotationsProduct"]["quantity"])*($trmActual*1.05);
                        }else{
                            $totalUsdCop      += ($value["QuotationsProduct"]["price"]*$value["QuotationsProduct"]["quantity"]);
                        }      

                    }               
                    

                    if(in_array($value["Product"]["part_number"],['S-003']) && $quotationData["Quotation"]["show_ship"] == 0){
                        $valor_envio = $value["QuotationsProduct"]["price"];
                    }

                    if($value["Quotation"]["descuento"] > 0){
                        $discount = $value["Quotation"]["descuento"];
                    }
                }

                $this->set(compact("totalUsdCop","totalUsdOriginal"));

            }
        }

        if(isset($this->request->data["modal"])){
            $datosQuation["Quotation"]["show"] = 1;        
            $datosQuation["Quotation"]["total_visible"] = 1;        
        }


        $this->set(compact('datosQuation','datos','datosC','datosProductos','datosUsuario','productClient','datosHeaders','datosFlowStage','mostrarTodos'));

        

        $this->set(compact("trmActual","factorImport","iva","notPermitido","trmDay"));

        $this->setSuggestedProducts($datosProductos,$trmActual);


        if($datosQuation["Quotation"]["design"] == 2){
            $this->render('view_especial');
        }elseif($datosQuation["Quotation"]["type_quotation"] == 3){
            $this->render("quicky");
        }else{



            $this->render('view');
        }

    }

    public function setSuggestedProducts($productos,$trmActual){

        $this->loadModel("SuggestedProduct");

        $exists_products = Set::extract($productos,"{n}.QuotationsProduct.product_id");

        $suggested       = $this->SuggestedProduct->findAllByProductPpal($exists_products);

        $finalSuggested  = [];

        $currencys       = [];

        $aditionals      = [];

        foreach ($productos as $key => $value) {
            $currencys[ $value["QuotationsProduct"]["product_id"] ] = $value["QuotationsProduct"]["currency"];
        }

        if (!empty($suggested)) {
            foreach ($suggested as $key => $value) {
                $value["Principal"]["suggested"] = [];
                $value["Principal"]["aditional"] = [];
                $finalSuggested[ $value["SuggestedProduct"]["product_ppal"] ] = $value["Principal"];

                if(!is_null($value["SuggestedProduct"]["product_aditional"])){
                    $aditionals[] = $value["SuggestedProduct"]["product_aditional"];
                    $finalSuggested[ $value["SuggestedProduct"]["product_ppal"] ]["aditional"] = $value["Segundario"];
                }
            }

            foreach ($suggested as $key => $value) {
                if (!in_array($value["SuggestedProduct"]["product_id"], $exists_products) && !in_array($value["SuggestedProduct"]["product_ppal"],$aditionals) ) {
                    unset($value["Principal"]);
                    $finalSuggested[ $value["SuggestedProduct"]["product_ppal"] ]["suggested"][] = $value;
                }
            }
            foreach ($finalSuggested as $key => $value) {
                if (empty($value["suggested"])) {
                    unset($finalSuggested[$key]);
                }
            }
        }

        $this->set("suggested",$finalSuggested);
        $this->set("currencys",$currencys);
        $this->set("trm_suggest",$trmActual);
      
    }

    public function view_whatsapp($cotizacion_id,$phone = null){
        $dataPhone = $this->desencriptarCadena($phone);
        $dataPhone = substr($dataPhone, -4);
        if(is_null($dataPhone) || !is_numeric($dataPhone)){
            throw new NotFoundException('La cotización no existe');
        }

        $phone = $this->encryptString($phone);
        if(isset($this->request->data["modal"])){
            $this->layout = false;
        }

        $this->loadModel("EmailTracking");


        if(isset($this->request->query["e"])){
            $this->set("edata",$this->request->query["e"]);
        }
        if($this->validateBase64(base64_decode($cotizacion_id))){
            $cotizacion_id = base64_decode($cotizacion_id);
        }elseif(!is_numeric($cotizacion_id)){
            $cotizacion_id        = $this->desencriptarCadena($cotizacion_id);
        }
        if (!$this->Quotation->exists($cotizacion_id)) {
            throw new NotFoundException('La cotización no existe');
        }

        $datosQuation               = $this->Quotation->get_data($cotizacion_id);

        $emailUser = $this->emailClientFlujo($datosQuation['Quotation']['prospective_users_id']);

        $datosTrack = $this->EmailTracking->findByQuotationIdAndEmail($cotizacion_id,$emailUser);
        if(!empty($datosTrack)){
            $this->set("datosTrack",$datosTrack);
        }


        $this->pdfConfig            = array(
                        'download'          => false,
                        'filename'          => 'cotizacion-'.$this->encryptString($datosQuation['Quotation']['codigo']).'.pdf',
                        'orientation'       => 'Potrait',
                        'download'          => true

        );
        if ($datosQuation['Quotation']['notes'] == '<br>') {
            $datosQuation['Quotation']['notes'] = '';
        }
        $datosHeaders               = $this->Quotation->Header->get_data($datosQuation['Quotation']['header_id']);
        $datosFlowStage             = $this->Quotation->FlowStage->get_data($datosQuation['Quotation']['flow_stage_id']);
        $datos                      = $this->Quotation->FlowStage->ProspectiveUser->get_data($datosFlowStage['FlowStage']['prospective_users_id']);
        if ($datos['ProspectiveUser']['type'] > 0) {
            $productClient = $this->Quotation->FlowStage->ProspectiveUser->TechnicalService->ProductTechnical->get_all($datos['ProspectiveUser']['type']);
        } else {
            $productClient = array();
        }
        $datosUsuario               = $this->Quotation->User->get_data($datos['ProspectiveUser']['user_id']);
        if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
            $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
        } else {
            $datosC                 = $this->Quotation->FlowStage->ProspectiveUser->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
        }
        $datosProductos             = $this->Quotation->QuotationsProduct->get_data_quotation($cotizacion_id,true);
        $this->set(compact('datosQuation','datos','datosC','datosProductos','datosUsuario','productClient','datosHeaders'));

        $this->loadModel("Config");
        $iva            = $this->Config->field("ivaCol",["id" => 1]);
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $factorImport   = $config["Config"]["factorUSA"];

        $this->set(compact("trmActual","factorImport","iva","dataPhone"));
    }

    public function find_products_cotizacion(){
        $this->layout                               = false;
        if ($this->request->is('ajax')) {
            $entrega                                = Configure::read('variables.entregaProduct');
            $this->deleteCacheProducts1();
            $cotizacion_id                          = $this->request->data['cotizacion_id'];
            $datosListTP                            = $this->Quotation->QuotationsProduct->get_data_quotation_list($cotizacion_id);
            $this->Session->write('carritoProductos', $datosListTP);
            $productQuotation                       = $this->Quotation->QuotationsProduct->get_data_quotation($cotizacion_id,true);
            $quotation                              = $this->Quotation->find("first",["recursive"=> -1, "conditions" => ["Quotation.id" => $cotizacion_id] ]);
            $type                                   = isset($this->request->data["type"]) ? $this->request->data["type"] : 1;

            if (!empty($productQuotation)) {
                foreach ($productQuotation as $key => $value) {
                    $this->updateCostCol($value["Product"]["id"]);
                }
            }

            $productQuotation                       = $this->Quotation->QuotationsProduct->get_data_quotation($cotizacion_id,true);
            if (!empty($productQuotation)) {
                foreach ($productQuotation as $key => $value) {
                    $users = explode(",", $value["Product"]["users"]);
                    if (($value["Product"]["Category"]["grupo"] == 1 && $this->validateGarantia($value["Product"]["brand_id"])) || (!in_array(AuthComponent::user("role"),["Gerente General","Logística"]) && $users["0"] != 'all' && !in_array(AuthComponent::user("id"), $users)) ) {
                        unset($productQuotation[$key]);
                    }
                }
            }
            $inventioWo                             = $this->getValuesProductsWo($productQuotation);
            $costos                                 = $this->getCosts($inventioWo);

            if (!empty($productQuotation)) {
                foreach ($productQuotation as $key => $value) {
                    $productQuotation[$key]["productsSugestions"] =  $this->sugestions($value["Product"]["id"]);
                }
            }

            $this->set(compact('productQuotation','entrega','inventioWo'));
            $this->loadModel("Config");
            $config         = $this->Config->findById(1);
            $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
            $factorImport   = $config["Config"]["factorUSA"];

            $currency = isset($this->request->data["currency"]) ? $this->request->data["currency"] : null ;
            $header = isset($this->request->data["header"]) ? $this->request->data["header"] : null ;

            $editProducts = $this->validateEditProducts();

            $this->loadModel("Abrasivo");
            $abrasivos = $this->Abrasivo->find("list",["fields" => ["quantity",'unit_price' ] ]);

            $this->set(compact("trmActual","factorImport","currency","editProducts","header","costos",'abrasivos','type','quotation'));
        }
    }

     public function find_products_cotizacion_order(){
        $this->layout                               = false;
        if ($this->request->is('ajax')) {
            $entrega                                = Configure::read('variables.entregaProduct');
            $this->deleteCacheProducts1();
            $cotizacion_id                          = $this->request->data['cotizacion_id'];
            $datosListTP                            = $this->Quotation->QuotationsProduct->get_data_quotation_list($cotizacion_id);
            $this->Session->write('carritoProductos', $datosListTP);
            $productQuotation                       = $this->Quotation->QuotationsProduct->get_data_quotation($cotizacion_id,true);

            $inventioWo                             = $this->getValuesProductsWo($productQuotation);
            $this->set(compact('productQuotation','entrega','inventioWo'));
            $this->loadModel("Config");
            $config         = $this->Config->findById(1);
            $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
            $factorImport   = $config["Config"]["factorUSA"];

            $currency = isset($this->request->data["currency"]) ? $this->request->data["currency"] : null ;
            $header = isset($this->request->data["header"]) ? $this->request->data["header"] : null ;

            $editProducts = $this->validateEditProducts();
            $this->set(compact("trmActual","factorImport","currency","editProducts","header"));
        }
    }


    public function getInformationQuotation(){
        $this->autoRender                           = false;
        if ($this->request->is('ajax')) {
            $cotizacion_id                          = $this->request->data['cotizacion_id'];
            $datosCotizacion                        = $this->Quotation->get_data($cotizacion_id);
            return json_encode($datosCotizacion);
        }
    }

    public function find_products_borrador(){
        $this->layout                               = false;
        if ($this->request->is('ajax')) {
            $entrega                                = Configure::read('variables.entregaProduct');
            $this->deleteCacheProducts1();
            $etapa_id                               = $this->request->data['etapa_id'];
            $datosListB                             = $this->Quotation->FlowStage->FlowStagesProduct->get_data_borrador_list($etapa_id);
            if (count($datosListB) > 0) {
                $this->Session->write('carritoProductos', $datosListB);
                $productsBorrador                   =  $this->Quotation->FlowStage->FlowStagesProduct->get_data_borrador($etapa_id);

                if (!empty($productsBorrador)) {
                    foreach ($productsBorrador as $key => $value) {
                        $this->updateCostCol($value["Product"]["id"]);
                    }
                }

                $productsBorrador                   =  $this->Quotation->FlowStage->FlowStagesProduct->get_data_borrador($etapa_id);

                // if (!empty($productsBorrador)) {
                //     foreach ($productsBorrador as $key => $value) {

                //         if ($value["Product"]["Category"]["grupo"] == 1 && $this->validateGarantia($value["Product"]["brand_id"])) {
                //             unset($productsBorrador[$key]);
                //         }else{
                //             $productsBorrador[$key]["productsSugestions"] =  $this->sugestions($value["Product"]["id"]);
                //         }

                //     }
                // }

                $this->set(compact('productsBorrador','entrega'));
                $this->loadModel("Config");
                $config         = $this->Config->findById(1);
                $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
                $factorImport   = $config["Config"]["factorUSA"];

                $currency = isset($this->request->data["currency"]) ? $this->request->data["currency"] : "cop" ;
                $header = isset($this->request->data["header"]) ? $this->request->data["header"] : "1" ;
                $editProducts = $this->validateEditProducts();
                $inventioWo                             = $this->getValuesProductsWo($productsBorrador);
                $costos                                 = $this->getCosts($inventioWo);
                $this->set(compact("trmActual","factorImport","currency","editProducts","header","inventioWo","costos"));
            } else {
                $this->autoRender                   = false;
                return 0;
            }
        }
    }

    public function getDataBorrador(){
        $this->autoRender                                           = false;
        if ($this->request->is('ajax')) {
            $datos = $this->Quotation->FlowStage->FlowStagesProduct->DraftInformation->get_data($this->request->data['flujo_id']);
            if (isset($datos['DraftInformation'])) {
                return json_encode($datos);
            } else {
                return false;
            }
        }
    }

    public function saveBorradorQuotation(){
        $this->autoRender                                           = false;
        if ($this->request->is('ajax')) {

            $etapa_id                                               = $this->request->data['Quotation']['etapa_id'];
            $flujo_id                                               = $this->request->data['Quotation']['prospective_users_id'];
            $datosB['DraftInformation']['name']                     = $this->request->data['Quotation']['name'];
            $datosB['DraftInformation']['notes']                    = $this->request->data['Quotation']['notes'];
            $datosB['DraftInformation']['notes_description']        = $this->request->data['Quotation']['notes_description'];
            $datosB['DraftInformation']['conditions']               = $this->request->data['Quotation']['conditions'];
            $datosB['DraftInformation']['header_id']                = $this->request->data['Quotation']['header_id'];
            $datosB['DraftInformation']['customer_note']            = $this->request->data['Quotation']['customer_note'];
            $datosB['DraftInformation']['show_ship']                = $this->request->data['Quotation']['show_ship'];
            $datosB['DraftInformation']['show']                     = $this->request->data['Quotation']['show'];
            $datosB['DraftInformation']['show_iva']                 = isset($this->request->data['radio_option_iva']) ? $this->request->data['radio_option_iva'] : 0;
            $datosB['DraftInformation']['prospective_users_id']     = $flujo_id;
            $datosB['DraftInformation']['user_id']                  = $this->request->data['Quotation']['user_cotiza'];
            $datosB['DraftInformation']['total_visible']            = $this->request->data['radio_option'];
            $datosB['DraftInformation']['flow_stage_id']            = $etapa_id;

            if (isset($this->request->data["Quotation"]["currency_data"])) {
                $datosB["DraftInformation"]["currency"] = $this->request->data["Quotation"]["currency_data"];
            }else{
                $datosB["DraftInformation"]["currency"] = "";                  
            }
            $arrayIdProducts                                        = $this->Session->read('carritoProductos');

            $exists = $this->Quotation->FlowStage->FlowStagesProduct->DraftInformation->field("id",["prospective_users_id",$flujo_id]);

            if(!$exists){
                $this->Quotation->FlowStage->FlowStagesProduct->DraftInformation->create();
            }else{
                $datosB['DraftInformation']['id']                   = $exists;
            }
            
            // $this->deleteInformationBorrador($flujo_id);
            // 

            if ($this->Quotation->FlowStage->FlowStagesProduct->DraftInformation->save($datosB)) {
                $this->deleteProductBorrador($etapa_id);
                if (count($arrayIdProducts) > 0) {
                    $idInformation          = $this->Quotation->FlowStage->FlowStagesProduct->DraftInformation->id;

                    foreach ($arrayIdProducts as $value) {
                        $datosM = array();
                        $idPrducto = explode("-", $value)[1];
                        $datosM[explode("-", $value)[0]]['FlowStagesProduct']['draft_information_id']   = $idInformation;
                        $datosM[explode("-", $value)[0]]['FlowStagesProduct']['prospective_users_id']   = $flujo_id;
                        $datosM[explode("-", $value)[0]]['FlowStagesProduct']['product_id']             = $idPrducto;
                        $datosM[explode("-", $value)[0]]['FlowStagesProduct']['flow_stage_id']          = $etapa_id;
                        $datosM[explode("-", $value)[0]]['FlowStagesProduct']['price']          = $this->request->data['Precio-'.trim($value)];
                        $datosM[explode("-", $value)[0]]['FlowStagesProduct']['currency']       = strtolower($this->request->data['Moneda-'.trim($value)]);
                        $datosM[explode("-", $value)[0]]['FlowStagesProduct']['quantity']       = $this->request->data['Cantidad-'.trim($value)];
                        $datosM[explode("-", $value)[0]]['FlowStagesProduct']['delivery']       = $this->request->data['Entrega-'.trim($value)];
                        $datosM[explode("-", $value)[0]]['FlowStagesProduct']['iva']            = $this->request->data['IVA-'.trim($value)];

                        $this->Quotation->FlowStage->FlowStagesProduct->create();
                        $this->Quotation->FlowStage->FlowStagesProduct->saveAll($datosM);
                    }


                    // $this->saveQuotationProductBorrador($arrayIdProducts,$idInformation,$etapa_id,$flujo_id);
                }
                // $this->saveDataLogsUser(8,'Quotation',0);
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function saveQuotationProductBorrador($arrayIdProducts,$id_inset,$etapa_id,$flujo_id){
        $datosProductos      = array();
        $arrayIdProductsData = array();
        $datosT              = array();
        $datosNuevos         = $this->request->data;

   

        foreach ($datosNuevos as $key => $value) {

            $pos = strpos($key, "Margen");
            $pos2 = strpos($key, "IVA");
            $pos3 = strpos($key, "Nota");
            if($pos === false && $pos2 === false && $pos3 === false){
                $explode = explode("-", $key);
                $datosProductos[$explode[0]."-".$explode[2]] = $value;
                if(!in_array($explode[2], $arrayIdProductsData)){
                    $arrayIdProductsData[] = $explode[2]; 
                }       
            }else{
                continue;
            }
        }



        foreach ($arrayIdProductsData as $value) {
            $idPrducto = explode("-", $value);
            if(count($idPrducto) == 1){
                $idPrducto = $idPrducto[0];
            }else{
                $idPrducto = $idPrducto[1];
            }
            $datosT[explode("-", $value)[0]]['FlowStagesProduct']['flow_stage_id']          = $etapa_id;
            $datosT[explode("-", $value)[0]]['FlowStagesProduct']['prospective_users_id']   = $flujo_id;
            $datosT[explode("-", $value)[0]]['FlowStagesProduct']['product_id']             = $idPrducto;
            $datosT[explode("-", $value)[0]]['FlowStagesProduct']['draft_information_id']   = $id_inset;
            
            $datosT[explode("-", $value)[0]]['FlowStagesProduct']['price']                  = $datosProductos['Precio-'.$idPrducto];
            $datosT[explode("-", $value)[0]]['FlowStagesProduct']['price']                  = $this->replaceText($datosT[explode("-", $value)[0]]['FlowStagesProduct']['price'],".", "");
            $datosT[explode("-", $value)[0]]['FlowStagesProduct']['price']                  = $this->replaceText($datosT[explode("-", $value)[0]]['FlowStagesProduct']['price'],",", "");
            $datosT[explode("-", $value)[0]]['FlowStagesProduct']['quantity']               = $datosProductos['Cantidad-'.$idPrducto];
            $datosT[explode("-", $value)[0]]['FlowStagesProduct']['delivery']               = $datosProductos['Entrega-'.$idPrducto];
        }

        $this->Quotation->FlowStage->FlowStagesProduct->create();
        $this->Quotation->FlowStage->FlowStagesProduct->saveAll($datosT);
        $this->Session->delete('carritoProductos');

        return true;
    }

    public function deleteInformationBorrador($etapa_id){
        $this->Quotation->FlowStage->FlowStagesProduct->DraftInformation->deleteAll([
                'DraftInformation.prospective_users_id' => $etapa_id
            ],false
        );
    }

    public function deleteProductBorrador($etapa_id){
        $this->Quotation->FlowStage->FlowStagesProduct->deleteAll([
                'FlowStagesProduct.flow_stage_id' => $etapa_id
            ],false
        );
    }


}