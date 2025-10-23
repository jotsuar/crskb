<?php

require_once '../Vendor/spreadsheet/vendor/autoload.php';
require_once ROOT.'/app/Vendor/CifrasEnLetras.php';
App::uses('AppController', 'Controller');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class ProspectiveUsersController extends AppController {

	public $components = array('Paginator','RequestHandler');

    private $flowsComisions = [];

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('timeManagementFlujosAttended','request_import_brands_email','informe_diario_clientes','flujos_sin_gestionar','form_landig','form_chat_wp','send_mail_finish','flujo_especial_api','sendTotalQuotationBlock','chat_point','informe_gerencia','update_flujos_no_gest','informe_sinterminar','validate_shipping','flujo_cliente','updateuserAsignado','add_prospective_chat','flujo_especial_chatapi','get_last_asesor','create_temp','transfer_flows','updateStateCanceladoProspective','diploma');
    }

    public function diploma($imagename = null){

        $nombre         = isset($this->request->query["nombre"]) && $this->request->query["nombre"] != '' ?  $this->request->query["nombre"] : ''; 
        $identification = isset($this->request->query["identification"]) && $this->request->query["identification"] != '' ?  $this->request->query["identification"] : ''; 
        $course         = isset($this->request->query["course"]) && $this->request->query["course"] != '' ?  $this->request->query["course"] : ''; 
        $city_date      = isset($this->request->query["city_date"]) && $this->request->query["city_date"] != '' ?  $this->request->query["city_date"] : ''; 

        $image = $this->get_image_certificate($nombre, $identification, $course, $city_date);
            

        if( is_null($imagename) ){

            $this->autoRender = false;
            header('Content-Type: image/png');
            imagepng($image);
        }else{

            $this->pdfConfig = array(
                'download'          => false,
                'filename'          => 'diploma_'.$imagename.'.pdf',
                'orientation'       => 'landscape',
                'download'          => false
            );
            $this->set('filename_img',$imagename.'.png');
        }
    }


    public function transfer_flows(){
        $this->autoRender = false;

        header('Access-Control-Allow-Origin: *');
        $this->response->header('Access-Control-Allow-Origin', '*');

        $email =  $this->request->data["email"];
        $flows =  $this->request->data["flows"];
        $user  =  $this->ProspectiveUser->User->findByEmail($email);

        if(!empty($flows) && !empty($user)){
            foreach ($flows as $key => $value) {
                $this->ProspectiveUser->recursive = -1;
                $flowData = $this->ProspectiveUser->findById($value);

                $this->request->data["user_id"]     = $flowData["ProspectiveUser"]["id"];
                $this->request->data['flujo_id']    = $value;
                $this->request->data['asesor']      = $user["User"]["id"];
                $this->updateuserAsignado(true);
            }
        }
    }

    

    public function panel_gest() {
         $this->loadModel("User");

        if(AuthComponent::user("role") != "Gerente General"){
            $this->redirect(array("controller"=>"prospective_users","action" => "index"));
        }

        $cotizaciones  = $this->ProspectiveUser->find("all",["conditions" => ["ProspectiveUser.state" => 3, "ProspectiveUser.losed" => 1, "ProspectiveUser.state_flow" => 10 ] ]);

        $users = [];

        $cotizaciones_enviadas = [];

        foreach ($cotizaciones as $key => $value) {           
            if(!in_array($value["ProspectiveUser"]["user_id"], $users)){
                $users[] = $value["ProspectiveUser"]["user_id"];
            }
            $cotizaciones_enviadas[$value["ProspectiveUser"]["user_id"]][] = $value;
        }

        $usuarios           = $this->ProspectiveUser->User->role_asesor_comercial_user_true(true);

        if (!empty($usuarios) && isset($usuarios[AuthComponent::user("id")])) {
            unset($usuarios[AuthComponent::user("id")]);
        }


        $this->set(compact('cotizaciones_enviadas','users',"usuarios"));
    }

    public function reject_flow($id){

        $prospectiveUser = $this->ProspectiveUser->findById($id);

        if ($this->request->is(['post','put'])) {
            $this->request->data["ProspectiveUser"]["valid"] = 1;
            $imagen = 0;
            if ($this->request->data['ProspectiveUser']['reject_image']['name'] != '') {
                $imagen                                             = $this->loadPhoto($this->request->data['ProspectiveUser']['reject_image'],'chat_images');
                $this->request->data['ProspectiveUser']['reject_image']              = $this->Session->read('imagenModelo');
            } else {
                unset($this->request->data['ProspectiveUser']['reject_image']);
            }

            if ($this->ProspectiveUser->save($this->request->data)) {
                $this->Session->setFlash(__('Flujo enviado a validación correctamente'),'Flash/success');
            }else{
                $this->Session->setFlash(__('El flujo no fue enviado, consulte con soporte'),'Flash/error');
            }

            $this->redirect(["action"=>"index","?" => ["q" => $id ] ]);

        }

        $this->request->data = $prospectiveUser;
        $this->layout = false;
        $this->render('modals/reject_flow');
    }

    public function aprobar_rechazos() {
        $prospectiveUsers = $this->ProspectiveUser->find("all",["conditions"=>["ProspectiveUser.valid"=>1,"ProspectiveUser.reject"=>2]]);
        $this->set(compact('prospectiveUsers'));
    }

    public function aprove_reject_flow(){
        $this->autoRender = false;

        $reject         = $this->request->data["type"];
        $id             = $this->request->data["id"];
        $reject_note    = $this->request->data["note"];

        $data = ["id"=>$id,"reject"=>$reject,"reject_note"=>$reject_note,"valid"=>0];

        $prospective = $this->ProspectiveUser->findById($id);

        if($reject == 1){
            $options = [
                "to" => "lmazop2020@gmail.com",
                "cc" => $prospective["User"]["email"],
                "template" => "aprove_reject_flow",
                "subject" => "CHAT INICIADO RECHAZADO POR ASESOR",
                "vars" => ["flujo" => $id, "asesor" => $prospective["User"]["name"], "correoPrincipal" => $prospective["User"]["email"], "razon" => $prospective["ProspectiveUser"]["reject_reason"], "reject" =>$reject, "ruta" => $prospective["ProspectiveUser"]["reject_image"], "urlPage" => $prospective["ProspectiveUser"]["page"] ]
            ];

        }else{
            $options = [
                "to" => $prospective["User"]["email"],
                "template" => "aprove_reject_flow",
                "subject" => "NO APROBACIÓN DE FLUJO RECHAZADO",
                "vars" => ["flujo" => $id, "razon" => $reject_note, "reject" =>$reject]
            ];
        }
        $this->sendMail($options);

        $this->ProspectiveUser->save($data);

        $this->Session->setFlash(__('Cambio realizado correctamente'),'Flash/success');

        if($reject == 1){

            $this->request->data["user_id"]     = $prospective["User"]["id"];
            $this->request->data['flujo_id']    = $id;
            $this->request->data['asesor']      = 129;
            $this->updateuserAsignado(true);
        }

    }

    public function edit_envoice($id,$type){
        $this->layout = false;
        $this->loadModel("Salesinvoice");
        $typeModel = $type == 0 ? "ProspectiveUser" : "Salesinvoice";

        if ($this->request->is(['post','put'])) {

            if ($this->$typeModel->save($this->request->data)) {
                $this->Session->setFlash(__('Factura editada correctamente'),'Flash/success');
            }else{
                $this->Session->setFlash(__('La Factura no fue editada correctamente'),'Flash/error');
            }

            $this->redirect(["action"=>"index","?" => ["q" => $id ] ]);
            
        }else{
            $this->request->data = $this->$typeModel->findById( $type == 0 ? $id : $type );
        }

        $usuariosAsesores           = $this->ProspectiveUser->User->role_asesor_comercial_user_true(true);

        $this->set(compact("typeModel",'usuariosAsesores'));
    }


    public function delete_envoice(){
        
        $this->autoRender = false;

        if ($this->request->data["type"] == 0) {
            $prospecto = $this->ProspectiveUser->findById($this->request->data["id"]);
            $prospecto["ProspectiveUser"]["bill_value"]     = null;
            $prospecto["ProspectiveUser"]["bill_value_iva"] = null;
            $prospecto["ProspectiveUser"]["bill_code"]      = null;
            $prospecto["ProspectiveUser"]["bill_user"]      = null;
            $prospecto["ProspectiveUser"]["bill_date"]      = null;
            $prospecto["ProspectiveUser"]["bill_file"]      = null;
            $prospecto["ProspectiveUser"]["bill_text"]      = null;
            $prospecto["ProspectiveUser"]["locked"]         = 0;
            $this->ProspectiveUser->save($prospecto["ProspectiveUser"]);
        }else{
            $this->loadModel("Salesinvoice");
            $this->Salesinvoice->delete($this->request->data["type"]);
        }
        $this->Session->setFlash(__('Factura eliminada correctamente'),'Flash/success');
    }


    public function gestionar_flujo()
    {
        $this->autoRender = false;
        $flujo_id = $this->desencriptarCadena($this->request->data["flujo_id"]);
        $flowData = $this->ProspectiveUser->findById($flujo_id);

        $continue = false;

        $validateContact    = $this->validateTimes(true);
        $valid              = true;

        if ((!empty($validateContact["contact"])) || !empty($validateContact["quotation"]) ) {
            $valid = false;
            echo "<h1> Tienes flujos represados en estado asignado y contactado </h1>";
            return;
        }

        if ( 
            $flowData["ProspectiveUser"]["user_id"] == AuthComponent::user("id") || 
            (AuthComponent::user("email") == "ventas@kebco.co" && $flowData["ProspectiveUser"]["user_id"] == 13 ) || 
            AuthComponent::user("email") == "jotsuar@gmail.com") {
            $continue = true;
        }

        if (!$continue) {
            echo "<h1> Este flujo ya fue reasignado </h1>";
            return;
        }

        $flowData = $flowData["ProspectiveUser"];

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
       

        $dataReturn = [
             "type" => $type ,"flow" => $flowData["id"], "fecha_cotizado" => $flowData["date_quotation"], "cliente" => $this->getDataCustomer($flowData["id"]),"state_flow" => $flowData["state_flow"] 
        ];

        foreach ($dataReturn as $key => $value) {
            $this->set($key,$value);
        }

        if (!is_null($type)) {
            $this->autoRender = true;
            $this->layout = false;
            $this->render('/Quotations/quoataions_gests');
        }

    }

    public function gest_wpp($quotation,$id) {
        $this->autoRender = false;
        $id         = $this->desencriptarCadena($id);
        $quotation  = $this->desencriptarCadena($quotation);

        $this->loadModel("Quotation");
        $flowData       = $this->ProspectiveUser->findById($id);
        $quotationData  = $this->Quotation->findById($quotation);
        $customer_data  = $this->getDataCustomer($id);

        $phones = array();
        $customer_data["telephone"] = str_replace([" ","+57","_"], "", $customer_data["telephone"]);
        $customer_data["cell_phone"] = str_replace([" ","+57","_"], "", $customer_data["cell_phone"]);

        if(!empty($customer_data["telephone"]) && strlen($customer_data["telephone"]) == 10){
            $phones[] = $customer_data["telephone"];
        }

        if(!empty($customer_data["cell_phone"]) && strlen($customer_data["cell_phone"]) == 10){
            $phones[] = $customer_data["cell_phone"];
        }

        if (count($phones) == 2 && $phones[0] == $phones[1] ) {
            unset($phones[1]);
        }

        if(!empty($phones)){

            foreach ($phones as $key => $phone) {
                $strMsg = '
                    {
                       "messaging_product": "whatsapp",
                       "to": "57'.$phone.'",
                       "type": "template",
                       "template": {
                           "name": "gestion_cotizacion",
                           "language": {
                               "code": "es",
                               "policy": "deterministic"
                           },
                           "components": [
                               {
                                   "type": "header",
                                   "parameters": [
                                       {
                                           "type": "text",
                                           "text": "'.$quotationData["Quotation"]["codigo"].'"
                                       }
                                   ]
                               },
                               {
                                   "type": "body",
                                   "parameters": [
                                       {
                                           "type": "text",
                                           "text": "'.$customer_data["name"].'"
                                       },
                                       {
                                           "type": "text",
                                           "text": "'. $this->date_castellano2(date("Y-m-d", strtotime($flowData["ProspectiveUser"]["date_quotation"]))).'"
                                       },
                                       {
                                           "type": "text",
                                           "text": "'.$quotationData["Quotation"]["name"].'"
                                       },
                                       {
                                           "type": "text",
                                           "text": "'.$quotationData["Quotation"]["codigo"].'"
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
                                           "text": "'.$this->encrypt($quotation).'"
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

                    $responseToken = $HttpSocket->post('https://graph.facebook.com/v13.0/100586116213138/messages',$strMsg,$request);
                    $responseToken = json_decode($responseToken->body);


                } catch (Exception $e) {
                    $this->log($e->getMessage());
                }   
            }


            $infoFlow = $flowData;
            $flowData = $flowData["ProspectiveUser"];

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

            $datosNota['ProgresNote']['description']                = "Gestión realizada vía Whatsapp el día: ".date("Y-m-d H:i:s");
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

            $this->Session->setFlash('Gestión realizada correctamente', 'Flash/success');

        }
        $this->redirect(["action"=>"pendientes_gestion"]);
    }


    public function gest_mail(){
        $this->layout = false;

        $this->loadModel("Quotation");
        $id         = $this->desencriptarCadena($this->request->query["id"]);
        $quotation  = $this->desencriptarCadena($this->request->query["quotation"]);

        $flowData       = $this->ProspectiveUser->findById($id);
        $quotationData  = $this->Quotation->findById($quotation);
        $quotationProducts  = $this->Quotation->QuotationsProduct->findAllByQuotationId($quotation);
        $customer_data  = $this->getDataCustomer($id);

        if ($this->request->is(["post","put"])) {
            $infoFlow = $flowData;
            $flowData = $flowData["ProspectiveUser"];

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

            $datosNota['ProgresNote']['description']                = "Gestión realizada vía correo electrónico el día: ".date("Y-m-d H:i:s");
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

            $rutaURL            = 'Quotations/view/'.$this->encryptString($quotationData['Quotation']['id']);
            
            $options  = array(
                'to'        => $customer_data["email"],
                'template'  => 'quote_sent_gest',
                'cc'        => AuthComponent::user("email"),
                'subject'   => strip_tags($this->request->data["ProspectiveUser"]["subject"]).' | KEBCO AlmacenDelPintor.com',
                'vars'      => array('contenido' => $this->request->data["ProspectiveUser"]["contenido"],'ruta'=>$rutaURL,"quotationID" =>$quotationData['Quotation']['id'] )
            );

            $datos_asesor = $this->ProspectiveUser->User->findById($infoFlow["ProspectiveUser"]["user_id"]);
            if(!empty($datos_asesor["User"]["password_email"])){
                $email      = $datos_asesor["User"]["email"] == "jotsuar@gmail.com" ? "sistemas@almacendelpintor.com" : $datos_asesor["User"]["email"];
                $password   = str_replace("@@KEBCO@@", "", base64_decode($datos_asesor["User"]["password_email"]) );
                $config     = array("username" => $email, "password" => $password);

                $this->sendMail($options, null, $config);
            }else{
                $this->sendMail($options);
            }

            $this->Session->setFlash('Gestión realizada correctamente', 'Flash/success');
            $this->redirect(["action"=>"pendientes_gestion"]);
        }

        $flowData       = $this->ProspectiveUser->findById($id);

        $datos_asesor = $this->ProspectiveUser->User->findById($flowData["ProspectiveUser"]["user_id"]);

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

        $res_ai = null;

        $respuestaIntencion = $this->callOpenAI($msgToSend);

        // var_dump($respuestaIntencion); die();

        //     // Extraer la intención del modelo
        // $res_ai = isset($respuestaIntencion['choices'][0]['message']['content']) ? $respuestaIntencion['choices'][0]['message']['content'] : null ;

        $res_ai = count($respuestaIntencion["output"]) == 2 ? $respuestaIntencion['output'][1]['content'][0]["text"] : $respuestaIntencion['output'][0]['content'][0]["text"];

        $this->set(compact("flowData","quotationData","customer_data","res_ai"));

    }

    public function pendientes_gestion() {
        $conditions  = [
            "date_quotation !=" => null,            
            "state_flow" => 3,
            "deadline_notified >=" => date("Y-m-d H:i:s"), 
            "OR" => [ 
                ["date_alert <=" => date("Y-m-d"), "alert_one" => 0, "date_final_alert" => null, "alert_two" => 0, "prorroga_one" => 0, "prorroga_two" => 0, 'notified' => 1 ], 
                ["date_final_alert <=" => date("Y-m-d"), "alert_one" => 1, "date_alert !=" => null, "alert_two" => 0, "prorroga_one" => 0, "prorroga_two" => 0, 'notified' => 1 ], 
                ["date_prorroga <=" => date("Y-m-d"), "alert_one" => 1,  "date_alert !=" => null, "alert_two" => 1,"date_final_alert !=" => null, "prorroga_one" => 0, "prorroga_two" => 0, 'notified' => 1 ], 
                ["date_prorroga_final <=" => date("Y-m-d"), "alert_one" => 1, "date_prorroga !="=>null,  "date_alert !=" => null, "alert_two" => 1,"date_final_alert !=" => null, "prorroga_one" => 1, "prorroga_two" => 0, 'notified' => 1 ], 
            ],
            "remarketing" => 0,
        ];

        if (AuthComponent::user("email") != "jotsuar@gmail.com" ) {
            $conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
        }

        if ( 
            AuthComponent::user("id") == 4 || 
            ( AuthComponent::user("role") == "Gerente General" && AuthComponent::user("email") != "jotsuar@gmail.com")  
        ) {
            $conditions["ProspectiveUser.user_id"] = 13;
        }

        $listPendind = $this->ProspectiveUser->find("all",["limit" => 100,
            "conditions" => $conditions
        ]);


        $this->set(compact("listPendind"));
    }

    public function download_csv($import_id) {
        $this->autoRender = false;
        $fileName = $import_id.".csv";

        $import_id = $this->decrypt($import_id);
        $this->loadModel("Import");

        $import = $this->Import->ImportProduct->findAllByImportId($import_id);

        $ruta_img = WWW_ROOT.'files'.DS.'graco';
        if (!file_exists($ruta_img)) {
            mkdir($ruta_img, 0777, true);
        }

        $newFileObj = fopen(WWW_ROOT.'files'.DS.'graco'.DS.$fileName, 'w');


        if (!empty($import) &&  ($open = fopen(WWW_ROOT.'files'.DS.'graco'.DS.$fileName, "r")) !== FALSE) {
            foreach ($import as $key => $value) {
                $fileInfo = [$value["Product"]["part_number"], $value["ImportProduct"]["quantity"] ];
                fputcsv($newFileObj, $fileInfo );
            }
            fclose($open);
        }

        fclose($newFileObj);
        $this->redirect(Router::url("/",true).'files'.DS.'graco'.DS.$fileName);

    }


    public function update_orden_import(){
        $this->autoRender = false;
        $this->loadModel("Import");
        $this->Import->save($this->request->data);
    }

    public function flujos_import() {
        $this->loadModel("ImportRequest");
        $conditions = ["ImportRequestsDetail.deadline !="=>null];
        $conditions = $this->getImportsExternas($conditions);

        $datos = $this->ImportRequest->ImportRequestsDetail->find("all",[
            "recursive" => -1,
            "joins" => [ 
                array(
                    'table' => 'import_requests',
                    'alias' => 'ImportRequest',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ImportRequest.id = ImportRequestsDetail.import_request_id AND ImportRequest.import_id is not null'
                    )
                ),
                array(
                    'table' => 'prospective_users',
                    'alias' => 'ProspectiveUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProspectiveUser.id = ImportRequestsDetail.prospective_user_id'
                    )
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProspectiveUser.user_id = User.id'
                    )
                ),
                array(
                    'table' => 'imports',
                    'alias' => 'Import',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ImportRequest.import_id = Import.id AND Import.state in (1,3)'
                    )
                ),
                array(
                    'table' => 'import_products_details',
                    'alias' => 'ImportProductsDetail',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ImportProductsDetail.import_id = Import.id AND ImportRequestsDetail.id = ImportProductsDetail.import_requests_detail_id AND ImportProductsDetail.quantity_final < ImportProductsDetail.quantity'
                    )
                ),
            ],
            "conditions" => $conditions,
            "fields" => ["ProspectiveUser.id","ProspectiveUser.clients_natural_id","ProspectiveUser.contacs_users_id","User.*","ImportRequestsDetail.*","Import.*"],
            "group" => ["ProspectiveUser.id","Import.id"]
        ]);
        $this->set(compact("datos"));
    }

    public function solicitudes_internas(){
        // $this->autoRender = false;
        $this->validateRoleImports();
        $this->loadModel("ImportRequest");
        $products = [];

        $solicitudesActivas = $this->ImportRequest->ImportRequestsDetail->find("all",["conditions" => ["ImportRequest.state" => 1,"ImportRequestsDetail.state"=>2,"ImportRequestsDetail.type_request" =>2 ] ]);

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
        $inventioWo         = $this->getValuesProductsWo($products);
        $this->loadModel("Config");

        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $factorImport   = $config["Config"]["factorUSA"];

        $this->set(compact("solicitudesActivas","inventioWo","trmActual","factorImport"));

    }

    public function get_documents(){
        $this->autoRender = false;
    }

    public function sendMessageBoss(){
        $this->autoRender = false;
        $fecha = date("Y-m-d H:i:A");
        $name  = AuthComponent::user("name");
        $subject = Configure::read("Subjects.".$this->request->data["subject"]);
        $bodyMessaje = $this->request->data["message"];

        $options = array(
            'to'        => ["gerencia@almacendelpintor.com"],
            'template'  => 'mensaje_externo',
            'subject'   => 'Mensaje asesor externo - '.$fecha.' -  KEBCO AlmacenDelPintor.com',
            'vars'      => compact("name","subject","bodyMessaje"),
            "gerencia"  => true,
        );

        $descNotification = "El asesor externo <b>".$name."</b> te ha enviado el siguiente mensaje a continuación con el siguiente asunto: <b> ".$subject."</b> <br>".$bodyMessaje;

        $this->saveManagesUser($descNotification,24,13,null,null,"/",1);

        $this->sendMail($options);

    }

    public function informe_gerencia(){
        $this->autoRender = false;
        $fecha  = date("Y-m-d");
        $this->loadModel("Informe");
        $informes = $this->Informe->find("count",["conditions" => ["Informe.fecha" => $fecha] ]);
        die();
        if ($informes > 0) {

            $options = array(
                'to'        => ["gerencia@almacendelpintor.com"],
                'template'  => 'reporte_gerencia',
                'subject'   => 'Reporte Gerencia - '.$fecha.' -  KEBCO AlmacenDelPintor.com',
                'vars'      => compact("fecha"),
                "gerencia"  => true,
             );


            $this->sendMail($options);     
        }
    }

    public function validate_shipping(){
        $this->autoRender = false;
        $this->loadModel("Config");
        $this->loadModel("ImportsProspectiveUser");

        $this->ProspectiveUser->recursive = -1;
        $flujosPagados = $this->ProspectiveUser->find("all",["conditions" => ["state_flow" => 5, "state !=" => 3, "status" => 1] ]);
        $flujosDespachados = $this->ProspectiveUser->find("all",["conditions" => ["state_flow" => 6, "state !=" => 3, "status_bill" => 1, "bill_code" => null ] ]);
        $config = $this->Config->findById(1);

        if(!empty($flujosPagados)){
            foreach ($flujosPagados as $key => $value) {
                $flowPagados = $this->ProspectiveUser->FlowStage->find("first",["recursive" => -1, "fields" => ["max(date_verification) fecha"],"conditions" => ["prospective_users_id" => $value["ProspectiveUser"]["id"], "state_flow" => "Pagado", "payment_verification" => 1] ]);

                if(!is_null($flowPagados["0"]["fecha"])){
                    $dias = $this->calculateDays($flowPagados["0"]["fecha"],date("Y-m-d"));
                    $import = $this->ImportsProspectiveUser->find("count",["conditions" => ["ProspectiveUser.id" => $value["ProspectiveUser"]["id"], "Import.state" => 1,2,3 ] ]);

                    $change = false;

                    if($import > 0 && $dias > $config["Config"]["days_shipping_import"] ){
                        $change = true;
                    }elseif($dias > $config["Config"]["days_shipping_normal"] ){
                        $change = true;
                    }

                    if ($change) {
                        $value["ProspectiveUser"]["status"] = 0;
                        $value["ProspectiveUser"]["updated"] = date("Y-m-d H:i:s");
                        $this->ProspectiveUser->save($value);
                    }

                }
            }
        }

         if(!empty($flujosDespachados)){
            foreach ($flujosDespachados as $key => $value) {
                $flowPagados = $this->ProspectiveUser->FlowStage->find("first",["recursive" => -1, "fields" => ["max(DATE(created)) fecha"],"conditions" => ["prospective_users_id" => $value["ProspectiveUser"]["id"], "state_flow" => "Despachado",] ]);

                if(!is_null($flowPagados["0"]["fecha"])){
                    $dias = $this->calculateDays($flowPagados["0"]["fecha"],date("Y-m-d"));
                    $import = $this->ImportsProspectiveUser->find("count",["conditions" => ["ProspectiveUser.id" => $value["ProspectiveUser"]["id"], "Import.state" => 1,2,3 ] ]);

                    $change = false;

                    if($import > 0 && $dias > $config["Config"]["days_billing_import"] ){
                        $change = true;
                    }elseif($dias > $config["Config"]["days_billing_normal"] ){
                        $change = true;
                    }

                    if ($change) {
                        $value["ProspectiveUser"]["status_bill"] = 1;
                        $value["ProspectiveUser"]["updated"] = date("Y-m-d H:i:s");
                        $this->ProspectiveUser->save($value);
                    }

                }
            }
        }
    }

    public function report_provees(){
        $this->validateDatesForReports();
    }

    public function informe_general(){
        $fecha = isset($this->request->query["date"]) ? $this->request->query["date"] : date("Y-m-d");
        $this->loadModel("Informe");

        $informes = $this->Informe->findAllByFecha($fecha);

        $this->set("informes",$informes);
        $this->set("fecha",$fecha);

    }

    public function informe_sinterminar($reporteGeneral = null){
        $this->loadModel("QuotationsProduct");

        $conditions = ["QuotationsProduct.biiled" => 0, "ProspectiveUser.state_flow" => [6],"Quotation.final" => 1 ];

        if (!empty($this->request->query["q"])) {
            $conditions["ProspectiveUser.id"] = $this->request->query["q"];
        }

        $idsMed = $this->getUsersByCity("med");
        $idsBog = $this->getUsersByCity("bog");

        $medellin = [];
        $bogota   = [];

        $datos = $this->QuotationsProduct->find("all",["limit" => 100,
            "joins" => [
                array(
                    'table' => 'quotations',
                    'alias' => 'Quotation',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Quotation.id = QuotationsProduct.quotation_id'
                    )
                ),
                array(
                    'table' => 'prospective_users',
                    'alias' => 'ProspectiveUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProspectiveUser.id = Quotation.prospective_users_id'
                    )
                )
            ],
            "recursive" => -1,
            "fields" => ["COUNT(QuotationsProduct.id) as total","Quotation.codigo","Quotation.id", "Quotation.prospective_users_id","ProspectiveUser.*"],
            "conditions" => $conditions,
            "group" => ["Quotation.prospective_users_id"]
        ]);

        if(!empty($datos)){
            $this->loadModel("FlowStage");
            foreach ($datos as $key => $value) {            
                $id_etapa_cotizado          = $this->FlowStage->id_latest_regystri_state_cotizado($value["ProspectiveUser"]["id"]);
                $datosFlowstage             = $this->FlowStage->get_data($id_etapa_cotizado);
                if (is_numeric($datosFlowstage['FlowStage']['document']) && $datosFlowstage['FlowStage']['document'] != $value["Quotation"]["id"] ){
                    unset($datos[$key]);
                }else{
                    if(in_array($value["ProspectiveUser"]["user_id"], $idsBog)){
                        $bogota[] = $value;
                    }else{
                        $medellin[] = $value;
                    }
                }
            }
        }

        $datos = ["bogota" => $bogota, "medellin" => $medellin];

        if (!is_null($reporteGeneral) && !empty($datos)) {
            $this->autoRender = false;
            $this->loadModel("Informe");

            $dataInforme = ["Informe" => [ "type" => "informe_sinterminar","datos" => json_encode($datos), "total" => (count($datos["bogota"])+count($datos["medellin"])), "fecha" => date("Y-m-d") ] ];

            $this->Informe->create();
            $this->Informe->save($dataInforme);
        }

        $this->set(compact("datos"));

    }

    public function getInfoAproves(){
        $this->autoRender = false;
        $this->loadModel("Approve");

        
        $dataByUser     = array();
        $totalCotizaciones = 0;

        if(isset($this->request->data["qt"])){
            $quotations        = $this->Approve->getDataByUser($this->request->data["fecha_ini"],$this->request->data["fecha_end"],$this->request->data["state"],true);

            $dataTable         = [];

            foreach ($quotations as $key => $value) {
                $dataTable[] = [
                    "nro"               => $value['FlowStage']['id'],
                    "nombre"            => substr($value['Quotation']['name'], 0,40),
                    "valor"             => number_format(doubleval($value["Quotation"]["total"]),2,",","."),
                    "código"            => $value["Quotation"]["codigo"],
                    "flujo"            => $value["Quotation"]["prospective_users_id"],
                    "cliente"           => trim($this->name_prospective_contact($value["ProspectiveUser"])),
                    "vendedor"          => $this->ProspectiveUser->User->field("name",["id" => $value["ProspectiveUser"]["user_id"]]),
                    "fecha_envio"           => $value["FlowStage"]["created"],
                    "estado_cotización"     => $this->find_stateFlow_quotation($value["Quotation"]["prospective_users_id"], $value["Quotation"]["id"])
                ];
            }
            return json_encode($dataTable);
        }

        $usuarios          = $this->Approve->getDataByUser($this->request->data["fecha_ini"],$this->request->data["fecha_end"],$this->request->data["state"]);

        foreach ($usuarios as $key => $value) {

            $datos = new StdClass();
            $datos->name = $this->ProspectiveUser->User->field("name",["id" => $key]);
            $datos->y = intval($value);
            $dataByUser[] = $datos;
            $totalCotizaciones+= $value;
        }

        return json_encode(["total" => $totalCotizaciones, "datos" => $dataByUser]);
    }

    public function name_prospective_contact($prospective){
        $nombre = "Nombre sin gestión";
        $this->loadModel("ContacsUser");
        $this->loadModel("ClientsNatural");

        if ($prospective['contacs_users_id'] > 0) {
            $datosC         = $this->ContacsUser->get_data_modelos($prospective['contacs_users_id']);
            $nombre         = isset($datosC['ClientsLegal']['name']) ? $datosC['ClientsLegal']['name'].' - '.$datosC['ContacsUser']['name'] : "";
            // $nombre      = $datosC['ClientsLegal']['name'];

        } elseif(!is_null($prospective['clients_natural_id'])) {
            $datosC         = $this->ClientsNatural->get_data($prospective['clients_natural_id']);
            $nombre         = $datosC['ClientsNatural']['name'];
        }
        
        return $nombre;
    }

    public function find_stateFlow_quotation($flujo_id,$quotation_id){
        $this->loadModel("FlowStage");
        $existeEtapa            = $this->FlowStage->exist_state_cotizado_prospective($flujo_id);
        if ($existeEtapa > 0) {
            $estado                 = $this->ProspectiveUser->get_stateFlow_flujo($flujo_id);
            $etapa_id_cotizado      = $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
            $datosF                 = $this->FlowStage->get_data($etapa_id_cotizado);
            if ($datosF['FlowStage']['document'] == $quotation_id) {
                switch ($estado) {
                    case 7:
                        $nombre         = 'No vendida';
                    break;
                    case 8:
                        $nombre         = 'Vendida y entregada';
                    break;

                    default:
                        $nombre         = 'En proceso';
                        break;
                }
            } else {
                $nombre                 = 'Se generó una nueva';
            }
        } else {
            $nombre                     = 'Por enviar';
        }
        return $nombre;
    }

    public function sendTotalQuotationBlock(){
        $this->autoRender = false;
        $this->loadModel("User");
        $this->loadModel("Approve");
        $aprovar = $this->Approve->findAllByState(0);

        if(!empty($aprovar)){
            $this->User->recursive = -1;
            $users = $this->User->findAllByRoleAndState(["Gerente General","Asesor Comercial"],1);
            if(!empty($users)){
                $users = Set::extract($users, "{n}.User.email");

                foreach ($users as $key => $value) {
                    if ($value == "jotsuar@gmail.com") {
                        unset($users[$key]);
                    }
                }
            }

            $options = array(
                'to'        => $users,
                'template'  => 'approve_qts',
                'subject'   => 'Reporte de cotizaciones por aprobar - KEBCO AlmacenDelPintor.com',
                'vars'      => compact("aprovar")
            );


            $this->sendMail($options, true);     
        }
    }

    public function chat_point(){
        $this->autoRender = false;

        header('Access-Control-Allow-Origin: *');
        $this->response->header('Access-Control-Allow-Origin', '*');

        $dia    = date("D");
        $hora   = date("H");



        if($dia == "Sun" || ( $dia == "Sat" && $hora >= 12) || ( !in_array($dia, ["Sat","Sun"]) && $hora >= 18 ) ){
            $datos  = $this->request->input('json_decode');
            if(isset($datos->message->text)){

                $this->loadModel("ProspectiveUser");

                $phone      = str_replace("Teléfono : ", '', $datos->message->text);

                if (is_null($phone) || empty($phone)) {
                    return false;
                }

                $asesores   = [5,11,7,3,2];

                $this->ProspectiveUser->recursive  = -1;
                $totales = $this->ProspectiveUser->find("all",["fields" => ["COUNT(user_id) total", "user_id"], "conditions" => ["state_flow" => 1, "user_id" => $asesores], "group" => ["user_id"], "order" => ["total" => "ASC"] ]);

                $data = [ "ProspectiveUser" => [
                    "origin"        => "Chat", "type" => 0,
                    "state_flow"    => 1,
                    "user_id"       => 2,
                    "user_receptor" => 2,
                    "description"   => "Solicitud en chat fuera de tiempo laboral - ".date("Y-m-d H:i:s"),
                    "reason"        => "Solicitud en chat fuera de tiempo laboral - ".date("Y-m-d H:i:s"),
                    "country"       => "Colombia",
                    "phone_customer" => $phone
                ] ];

                $existCustomer = $this->validateCustomerPhones($phone);

                if(!is_null($existCustomer)){
                    if($existCustomer["type"] == "natural"){
                        $data["ProspectiveUser"]["clients_natural_id"] = $existCustomer["id"];
                        $data["ProspectiveUser"]["contacs_users_id"]   = 0; 
                    }else{
                        $data["ProspectiveUser"]["contacs_users_id"] = $existCustomer["id"];
                        $data["ProspectiveUser"]["clients_natural_id"]   = null;    
                    }
                }else{
                    $data["ProspectiveUser"]["contacs_users_id"]     = 0;
                    $data["ProspectiveUser"]["clients_natural_id"]   = null;    
                }

                $this->ProspectiveUser->create();
                $this->ProspectiveUser->save($data);

                $flujoIdData = $this->ProspectiveUser->id;

                $datosUsu    = $this->ProspectiveUser->User->get_data($data['ProspectiveUser']['user_id']);
                $datosProspecto = $data;

                if(!is_null($data["ProspectiveUser"]["clients_natural_id"])){
                    $datosContacto = $this->ProspectiveUser->ClientsNatural->get_data($datosProspecto["ProspectiveUser"]["clients_natural_id"]);
                    $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'),$datosProspecto["ProspectiveUser"]["origin"]);
                    $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                    $this->addProspectiveAtentionTime($flujoIdData);
                    $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                    $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$datosContacto['ClientsNatural']['name'],$datosContacto['ClientsNatural']['telephone'],$datosContacto['ClientsNatural']['cell_phone'],$datosContacto['ClientsNatural']['email'],$datosContacto['ClientsNatural']['city'],'prospectiveUsers/adviser?q='.$flujoIdData);  
                }else if($data["ProspectiveUser"]["contacs_users_id"] != 0){
                    $datosContacto                              = $this->ProspectiveUser->ContacsUser->get_data($datosProspecto["ProspectiveUser"]["contacs_users_id"]);
                    $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'));
                    $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                    $this->addProspectiveAtentionTime($flujoIdData);
                    $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                    $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$datosContacto['ContacsUser']['name'],$datosContacto['ContacsUser']['telephone'],$datosContacto['ContacsUser']['cell_phone'],$datosContacto['ContacsUser']['email'],$datosContacto['ContacsUser']['city'],'prospectiveUsers/adviser?q='.$flujoIdData);    
                }else{
                    $nombre = "Sin nombre, por gestionar";
                    $phone = empty($datosProspecto["ProspectiveUser"]["phone_customer"]) ? "Sin teléfono, por gestionar" : $datosProspecto["ProspectiveUser"]["phone_customer"];
                    $cell_phone = empty($datosProspecto["ProspectiveUser"]["phone_customer"]) ? "Sin celular, por gestionar" : $datosProspecto["ProspectiveUser"]["phone_customer"];
                    $email = empty($datosProspecto["ProspectiveUser"]["email_customer"]) ? "Sin email, por gestionar" : $datosProspecto["ProspectiveUser"]["email_customer"];
                    $city = "Sin ciudad, por gestionar";

                    $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'),$datosProspecto["ProspectiveUser"]["origin"]);
                    $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                    $this->addProspectiveAtentionTime($flujoIdData);
                    $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                    $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$nombre,$cell_phone,$phone, $email,$city,'prospectiveUsers/adviser?q='.$flujoIdData);  
                }
            }
        }
        return true;
        //$this->setMessagesFirebase();
    }

    public function validateCustomerPhones($phone){
        $this->loadModel("ClientsNatural");
        $this->loadModel("ContacsUser");

        $datos      = null;

        $natural    = $this->ClientsNatural->findByTelephoneOrCellPhone($phone,$phone);
        $contacto   = $this->ContacsUser->findByTelephoneOrCellPhone($phone,$phone);

        if(!empty($natural)){
            $datos = array(
                "type" => "natural",
                "id" => $natural["ClientsNatural"]["id"]
            );
        }else if(!empty($contacto)){
            $datos = array(
                "type" => "legal",
                "id" => $contacto["ContacsUser"]["id"],
            );
        }
        return $datos;
    }

    public function flujo_cliente(){
        $this->autoRender = false;

        if($this->request->is("post")){
            $this->response->header('Access-Control-Allow-Origin', '*');
            $data        = $this->request->data;

            $idCliente   = $this->decrypt($data["cliente"]);
            $typeCliente = $this->decrypt($data["cliente_type"]);

            $client_information = ["id" => $idCliente, "type" => $typeCliente];

            if(!empty($client_information) ){                

                $asesores   = [5,11,7,3,2];
                $config     = array(
                    "Config" => array(
                        "reason" => isset($data["flujo"]) ? "El cliente solicita contacto ya que está interesado en volver a pedir algo del flujo: ".$data["flujo"]." y la orden: ".$data["order"] : "El cliente desea ser contactado" ,
                        "page" => $this->request->referer(),
                        "country" => "Colombia",
                        "origin" => "Referido",
                    )
                );
                $asesor             = isset($data["flujo"]) ? $this->ProspectiveUser->field("user_id",["id" => $data["flujo"]]) : 2;
                $flujoId            = $this->createProspective($client_information,$config,  $asesor); 
                return json_encode(array("status" => true, "message" => "Created successfully", "prospective" => $flujoId));
                
            }else{
                return json_encode(["status" => false, "message" => "All fields are required"]);
            }
        }

    }

    private function getOrCreateClientRetoma($data){
        $this->loadModel("ClientsNatural");
        $this->loadModel("ContacsUser");

        $conditionsNatural = ["OR" => [] ];
        $conditionsContact = ["OR" => [] ];

        if (!empty($data["EMail"])) {
            $conditionsNatural["OR"]["ClientsNatural.email"]  = $data["EMail"];
            $conditionsContact["OR"]["ContacsUser.email"]     = $data["EMail"];
        }

        if (!empty($data["Telefonos"])) {
            $conditionsNatural["OR"]["ClientsNatural.telephone"]  = $data["Telefonos"];
            $conditionsNatural["OR"]["ClientsNatural.cell_phone"] = $data["Telefonos"];
            $conditionsContact["OR"]["ContacsUser.telephone"]     = $data["Telefonos"];
            $conditionsContact["OR"]["ContacsUser.cell_phone"]    = $data["Telefonos"];
        }

        if ($data["IdTipoIdentificacion"] == 13) {
            $conditionsContact["OR"]["ClientsLegal.nit LIKE"]   = '%'.$data["Identificacion"].'%';
            $conditionsContact["OR"]["ClientsLegal.name"]       = '%'.trim($data["Nombre"]).'%';
        }else{
            $conditionsNatural["OR"]["ClientsNatural.identification LIKE"]   = '%'.$data["Identificacion"].'%';
            $conditionsNatural["OR"]["ClientsNatural.name"]                  = '%'.trim($data["Nombre"]." ".$data["Apellidos"]).'%';
        }

        $natural  = $this->ClientsNatural->find("first",["conditions"=>$conditionsNatural]);
        $contacto = $this->ContacsUser->find("first",["conditions"=>$conditionsContact]);

        if(!empty($natural)){
            return array("type" => "natural", "id" => $natural["ClientsNatural"]["id"]);
        }
        if(!empty($contacto)){
            return array("type" => "legal", "id" => $contacto["ContacsUser"]["id"]);
        }

        if(empty($natural) && empty($contacto)){
            $datos = array(
                "ClientsNatural" => array(
                    "name"          => trim($data["Nombre"]." ".$data["Apellidos"]),
                    "telephone"     => "",
                    "cell_phone"    => $data["Telefonos"],
                    "city"          => empty($data["Ciudad"])? null: $data["Ciudad"],
                    "email"         => $data["EMail"],
                    "user_receptor" => is_null(AuthComponent::user("id")) ? 1 : AuthComponent::user("id"),
                )
            );

            $this->ClientsNatural->create();
            $this->ClientsNatural->save($datos);
            return array("type" => "natural", "id" => $this->ClientsNatural->id); 
        }
        return null;
    }

    public function create_flow_retoma(){
        $this->autoRender = false;
        $infoClientes = $this->datos_clientes(true) ;
        $user_id      = $this->request->data["user_id"];
        $anios        = $this->request->data["anio"];
        $anios_arr    = explode("-", $this->request->data["anio"]);

        $flujos       = [];

        foreach ($this->request->data["clientes"] as $key => $value) {
            $cliente = $this->getOrCreateClientRetoma($value);
            $config     = array(
                "Config" => array(
                    "reason" => "Cliente asignado para retoma por compra uníca en el/los año(s) ".$anios,
                    "page" => Router::url("/",true)."pages/detail_clientes_anio/".$anios,
                    "country" => "Colombia",
                    "origin" => "Remarketing",
                )
            );

            $ventas = [];

            foreach ($anios_arr as $key => $anio) {
                $ventasAnio   = $infoClientes["clientesByAnioDetail"][$anio][$value["Identificacion"]];
                $ventas       = array_merge($ventasAnio,$ventas);
            }
            $flujos[]         = $this->createProspective($cliente,$config, $user_id,$ventas, $value["Identificacion"]."|".$anios );
        }
        echo empty($flujos) ? 1 : implode(",", $flujos);
        die;
    }

    public function form_chat_wp(){
        $this->autoRender = false;
        $data = $this->request->data;

        if (isset($data["id"]) && isset($data["type"]) ) {
            $client_information = ["id" => $data["id"], $data["type"]];
        }else{
            $phone = $data["phone"];
            if (strlen($phone) == 12 || strlen($phone) == 13) {
                $phone = str_replace([" ","+"], "", $phone);
                $phone = substr($phone, 2);
            }
            $clientDataValidation = ["A" => $data["name"], "B" => $data["email"],"C" => $phone, "D" => $data["city"]];
            $client_information   = $this->getOrCreateClient($clientDataValidation);
        }

        $config     = array(
            "Config" => array(
                "reason" => "Línea: ".$data["linea"].", requerimiento: ".$data["request"],
                "page" => $this->request->referer(),
                "country" => "Colombia",
                "origin" => "Whatsapp",
            )
        );

        $id_asesor = $this->ProspectiveUser->User->field("id",["email"=>$data["email_agent"]]);

        try {
            $flujoId            = $this->createProspective($client_information,$config, is_null($id_asesor) ? 2 : $id_asesor );
            return json_encode(array("status" => true, "message" => "Created successfully", "prospective" => $flujoId));
        } catch (Exception $e) {
             return json_encode(["status" => false, "message" => "All fields are required"]);
        }
    }

    public function form_landig(){
        $this->autoRender = false;

        if($this->request->is("post")){
            $this->response->header('Access-Control-Allow-Origin', '*');
            $data = $this->request->data;

            if(!empty($data["nombre"]) && ( !empty($data["correo"]) || !empty($data["celular"]) ) && !empty($data["ciudad"]) && !empty($data["comentario"]) ){                

                $asesores   = [5,11,7,3,2];
                $config     = array(
                    "Config" => array(
                        "reason" => strip_tags($this->request->data["comentario"]),
                        "page" => $this->request->referer(),
                        "country" => isset($this->request->data["country"]) ? $this->request->data["country"] : "Colombia",
                        "origin" => isset($this->request->data["origin"]) ? $this->request->data["origin"] : "Redes Sociales",
                    )
                );
                $clientDataValidation = ["A" => $this->request->data["nombre"], "B" => $this->request->data["correo"],"C" => $this->request->data["celular"], "D" => $this->request->data["ciudad"]];
                $client_information = $this->getOrCreateClient($clientDataValidation);
                $flujoId            = $this->createProspective($client_information,$config, 2);
                return json_encode(array("status" => true, "message" => "Created successfully", "prospective" => $flujoId));
                
            }else{
                return json_encode(["status" => false, "message" => "All fields are required"]);
            }
        }

    }

    public function despachos($new = null){

        $this->ProspectiveUser->recursive = -1;
        $conditions = array("ProspectiveUser.state_flow" => [6,8]);
        

        if(!empty($this->request->query)){
            $q = $this->request->query;
            if(!empty($q["txt_buscador_flujo"])){
                $conditions["ProspectiveUser.id"] = $q["txt_buscador_flujo"];
            }
            if(!empty($q["state_flow"])){
                $conditions["ProspectiveUser.state_flow"] = $q["state_flow"];
            }

            if(!empty($q["txt_buscador_cliente"])){
                $pos = strpos($q["txt_buscador_cliente"],"LEGAL");
                if($pos === false){
                    $conditions["ProspectiveUser.clients_natural_id"] = str_replace("NATURAL", "", $q["txt_buscador_cliente"]);
                }else{
                    $id_legal = str_replace("LEGAL", "", $q["txt_buscador_cliente"]);
                    $this->loadModel("ContacsUser");
                    $this->ContacsUser->recursive = -1;
                    $contactos = $this->ContacsUser->findAllByClientsLegalsId($id_legal);
                    if(!empty($id_legal)){
                        $contactos  = Set::extract($contactos, "{n}.ContacsUser.id");
                        $conditions["ProspectiveUser.contacs_users_id"] = $contactos;
                    }
                }
            }

            if(!empty($q["txt_buscador_transportadora"]) || !empty($q["txt_buscador_contacto"]) || !empty($q["txt_buscador_guia"] ) || !empty($q["txt_buscador_fecha"]) ){
                $conditions = $this->createConditions($conditions,$q);
            }

        }

        if (AuthComponent::user("role") == "Asesor Externo") {
            $conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
        }
        $this->paginate         = array(
                                        'limit'         => 10,
                                        'order'         => array("ProspectiveUser.modified" => "DESC"),
                                        'conditions'    => $conditions,
                                        'recursive'     => -1
                                    );

        try {
            $despachos              = $this->paginate('ProspectiveUser');
            if(!empty($despachos)){
                foreach ($despachos as $key => $value) {

                    $dataDespachos = $this->ProspectiveUser->FlowStage->find("all",
                        [
                            "conditions" => ["FlowStage.prospective_users_id" => $value["ProspectiveUser"]["id"], "FlowStage.state_flow" => ['Despachado', 'Despacho'] ]
                        , "recursive" => -1, "order" => ["FlowStage.state_flow = 'Despachado' " => "DESC"]
                    ]);

                    $despachos[$key]["ProspectiveUser"]["despachos"] = $dataDespachos;
                }
            }
            
        } catch (Exception $e) {
            $despachos = array();   
        }

        $clientsLegals      = $this->getClientsLegals();
        $clientsNaturals    = $this->getClientsNaturals();

        $this->loadModel("Conveyor");
        $conveyors = $this->Conveyor->find("list",["fields" => "name", "name"]);
        $conveyors = array_combine($conveyors, $conveyors);

        $medios         = Configure::read('variables.mediosPago');

        $this->set("despachos",$despachos);
        $this->set("clientsLegals",$clientsLegals);
        $this->set("clientsNaturals",$clientsNaturals);
        $this->set("conveyors",$conveyors);
        $this->set("medios",$medios);

        $this->set("new",$new);
        if(!empty($this->request->query)){
            $this->set("q", $this->request->query);
        }

    }  

    private function createConditions($conditions, $q){

        $conditionsFinal = $conditions;
        $conditions = array("FlowStage.state_flow" => ['Despachado', 'Despacho']);
        if (!empty($q["txt_buscador_guia"] )) {
            $conditions['LOWER(FlowStage.number) LIKE'] = '%'.mb_strtolower($q['txt_buscador_guia']).'%';
        }

        if (!empty($q["txt_buscador_transportadora"] )) {
            $conditions['LOWER(FlowStage.conveyor) LIKE'] = '%'.mb_strtolower($q['txt_buscador_transportadora']).'%';
        }

        if (!empty($q["txt_buscador_contacto"] )) {
            $conditions['LOWER(FlowStage.contact) LIKE'] = '%'.mb_strtolower($q['txt_buscador_contacto']).'%';
        }

        if (!empty($q["txt_buscador_fecha"] )) {
            $conditions['DATE(FlowStage.created) LIKE'] = $q['txt_buscador_fecha'];
        }
        

        $flows = $this->ProspectiveUser->FlowStage->find("all",["recursive" => -1, "fields" => ["prospective_users_id"],"conditions" => $conditions]);

        if(empty($flows)){
            $flows = [0];
        }

        if(!empty($flows)){
            $conditionsFinal["ProspectiveUser.id"] = Set::extract($flows,"{n}.FlowStage.prospective_users_id");
        }
        return $conditionsFinal;

    } 

    public function change_trm_quotation(){
        $this->autoRender = false;
        if($this->request->is("ajax")){           
            $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->recursive = -1;
            $productosCotizacion     = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->findAllByQuotationId($this->request->data["quotation"]);            
            $total                   = 0;
            $quotation               = $this->ProspectiveUser->FlowStage->Quotation->findById($this->request->data["quotation"]);
            foreach ($productosCotizacion as $key => $value) {
                if( isset($this->request->data["new_calculate"]) ){
                    $this->request->data["calculate"] = 2;
                } 
                if($this->request->data["calculate"] == 2){
                    if($value["QuotationsProduct"]["change"] == "1"){

                        $value["QuotationsProduct"]["original_price"]   = ($value["QuotationsProduct"]["price"] / $value["QuotationsProduct"]["trm_change"]);
                        
                        $value["QuotationsProduct"]["currency"]     = "cop";
                        $value["QuotationsProduct"]["change"]       = 1;
                        $value["QuotationsProduct"]["price"]        = ($value["QuotationsProduct"]["price"] / $value["QuotationsProduct"]["trm_change"]) * floatval($this->request->data["trm"]);
                        $value["QuotationsProduct"]["trm_change"]   = floatval($this->request->data["trm"]);

                                          
                    }
                }else{
                    if($value["QuotationsProduct"]["currency"] == "usd"){

                        $value["QuotationsProduct"]["original_price"]   = $value["QuotationsProduct"]["price"];
                        $value["QuotationsProduct"]["currency"]     = "cop";
                        $value["QuotationsProduct"]["change"]       = 1;
                        $value["QuotationsProduct"]["trm_change"]   = floatval($this->request->data["trm"]);
                        $value["QuotationsProduct"]["price"]        = ($value["QuotationsProduct"]["price"] * $this->request->data["trm"]);

                        if($this->request->data["calculate"] != 1){
                            $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->save($value);
                        }                        
                    }
                }
                if( isset($this->request->data["new_calculate"]) && !isset($this->request->data["noSave"]) ){
                    if (isset($this->request->data["warehose"])) {
                        $value["QuotationsProduct"]["warehouse"] = 2;
                    }
                    $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->save($value);
                    $this->request->data["calculate"] = 0;
                }     

                if($quotation["Quotation"]["show_ship"] == 0 && $quotation["Quotation"]["header_id"] != 3 && $value["QuotationsProduct"]["product_id"] == 3020 ){
                    continue;
                }
                
                $total+= ( $value["QuotationsProduct"]["quantity"] * $value["QuotationsProduct"]["price"] );
            }
            if($this->request->data["calculate"] == "1" || $this->request->data["calculate"] == "2" || isset($this->request->data["noSave"]) ){
                return number_format($total, 2, ",",".");
            }else{
                $this->ProspectiveUser->FlowStage->Quotation->recursive = -1;
                $data = $this->ProspectiveUser->FlowStage->Quotation->findById($this->request->data["quotation"]);
                $data["Quotation"]["total"] = $total;
                $this->ProspectiveUser->FlowStage->Quotation->save($data);

                $idFlowstage    = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($this->request->data["flujo"]);
                $datosFlowstage = $this->ProspectiveUser->FlowStage->get_data($idFlowstage);

                $datosFlowstage["FlowStage"]["priceQuotation"] = $total;
                $this->ProspectiveUser->FlowStage->save($datosFlowstage);

                $this->Session->setFlash(__('Cotización cambiada correctamente'),'Flash/success');

            }
        }
    }

    public function flujo_especial(){
        if($this->request->is("post")){
            $this->request->data["ProspectiveUser"]["description"] = $this->request->data["ProspectiveUser"]["reason"]; 

            $this->request->data["ProspectiveUser"]["contacs_users_id"] = empty($this->request->data["ProspectiveUser"]["contacs_users_id"]) ? 0 : $this->request->data["ProspectiveUser"]["contacs_users_id"];

            $this->request->data["ProspectiveUser"]["clients_natural_id"] = empty($this->request->data["ProspectiveUser"]["clients_natural_id"]) ? NULL : $this->request->data["ProspectiveUser"]["clients_natural_id"];

             $this->request->data["ProspectiveUser"]["state_flow"] = 1;

            if(!empty($this->request->data["ProspectiveUser"]["image"]["name"])){
                $documento                                  = $this->loadPhoto($this->request->data['ProspectiveUser']['image'],'flujo/imagenes');
                $this->request->data['ProspectiveUser']['image']          = $this->Session->read('imagenModelo');
            }elseif (!empty($this->request->data["ProspectiveUser"]["image_paste"])) {
                $this->request->data["ProspectiveUser"]["image_paste"]    = str_replace("data:image/png;base64,", "", $this->request->data["ProspectiveUser"]["image_paste"]);
                $this->request->data['ProspectiveUser']['image']          = $this->saveImage64($this->request->data["ProspectiveUser"]["image_paste"],'flujo/imagenes');
            }else{
                $this->request->data['ProspectiveUser']['image']          = null;
            }

            $this->request->data["ProspectiveUser"]["time_contact"]       = $this->calculateHoursGest( Configuration::get_flow("hours_contact") );

            $this->ProspectiveUser->create();
            $this->ProspectiveUser->save($this->request->data);

            $flujoIdData = $this->ProspectiveUser->id;

            $datosUsu    = $this->ProspectiveUser->User->get_data($this->request->data['ProspectiveUser']['user_id']);
            $datosProspecto = $this->request->data;

            if(!is_null($this->request->data["ProspectiveUser"]["clients_natural_id"])){
                $datosContacto = $this->ProspectiveUser->ClientsNatural->get_data($datosProspecto["ProspectiveUser"]["clients_natural_id"]);
                $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'),$datosProspecto["ProspectiveUser"]["origin"]);
                $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                $this->addProspectiveAtentionTime($flujoIdData);
                $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$datosContacto['ClientsNatural']['name'],$datosContacto['ClientsNatural']['telephone'],$datosContacto['ClientsNatural']['cell_phone'],$datosContacto['ClientsNatural']['email'],$datosContacto['ClientsNatural']['city'],'prospectiveUsers/adviser?q='.$flujoIdData);  
            }else if($this->request->data["ProspectiveUser"]["contacs_users_id"] != 0){
                $datosContacto                              = $this->ProspectiveUser->ContacsUser->get_data($datosProspecto["ProspectiveUser"]["contacs_users_id"]);
                $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'));
                $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                $this->addProspectiveAtentionTime($flujoIdData);
                $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$datosContacto['ContacsUser']['name'],$datosContacto['ContacsUser']['telephone'],$datosContacto['ContacsUser']['cell_phone'],$datosContacto['ContacsUser']['email'],$datosContacto['ContacsUser']['city'],'prospectiveUsers/adviser?q='.$flujoIdData);    
            }else{
                $nombre = "Sin nombre, por gestionar";
                $phone = empty($datosProspecto["ProspectiveUser"]["phone_customer"]) ? "Sin teléfono, por gestionar" : $datosProspecto["ProspectiveUser"]["phone_customer"];
                $cell_phone = empty($datosProspecto["ProspectiveUser"]["phone_customer"]) ? "Sin celular, por gestionar" : $datosProspecto["ProspectiveUser"]["phone_customer"];
                $email = empty($datosProspecto["ProspectiveUser"]["email_customer"]) ? "Sin email, por gestionar" : $datosProspecto["ProspectiveUser"]["email_customer"];
                $city = "Sin ciudad, por gestionar";

                $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'),$datosProspecto["ProspectiveUser"]["origin"]);
                $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                $this->addProspectiveAtentionTime($flujoIdData);
                $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$nombre,$cell_phone,$phone, $email,$city,'prospectiveUsers/adviser?q='.$flujoIdData);  
            }
            $this->Session->setFlash(__('Flujo creado con exíto'),'Flash/success');
            $this->redirect(array("controller"=> "ProspectiveUsers", "action" => "index", "?" => array("q"=> $flujoIdData)));
            
        }
        $clientsLegals      = $this->getClientsLegals();
        $clientsNaturals    = $this->getClientsNaturals();
        $usuarios           = $this->ProspectiveUser->User->role_asesor_comercial_user_true();
        $this->set(compact("clientsLegals","clientsNaturals",'usuarios'));
    }

    public function flujo_especial_api(){
        $this->autoRender = false;
        header('Access-Control-Allow-Origin: *');
        $this->response->header('Access-Control-Allow-Origin', '*');
        $data = $this->request->data;
        if($this->request->is("post")){

            $data = $this->request->data;

            $this->request->data = [];

            $this->request->data["ProspectiveUser"] = $data;

            $this->request->data["ProspectiveUser"]["description"] = $this->request->data["ProspectiveUser"]["reason"]; 

            $this->request->data["ProspectiveUser"]["contacs_users_id"] = empty($this->request->data["ProspectiveUser"]["contacs_users_id"]) ? 0 : $this->request->data["ProspectiveUser"]["contacs_users_id"];

            $this->request->data["ProspectiveUser"]["clients_natural_id"] = empty($this->request->data["ProspectiveUser"]["clients_natural_id"]) ? NULL : $this->request->data["ProspectiveUser"]["clients_natural_id"];

             $this->request->data["ProspectiveUser"]["state_flow"] = 1;

            if(!empty($this->request->data["ProspectiveUser"]["image"]["name"])){
                $documento                                  = $this->loadPhoto($this->request->data['ProspectiveUser']['image'],'flujo/imagenes');
                $this->request->data['ProspectiveUser']['image']          = $this->Session->read('imagenModelo');
            }elseif (!empty($this->request->data["ProspectiveUser"]["image_paste"])) {
                $this->request->data["ProspectiveUser"]["image_paste"]    = str_replace("data:image/png;base64,", "", $this->request->data["ProspectiveUser"]["image_paste"]);
                $this->request->data['ProspectiveUser']['image']          = $this->saveImage64($this->request->data["ProspectiveUser"]["image_paste"],'flujo/imagenes');
            }else{
                $this->request->data['ProspectiveUser']['image']          = null;
            }

            $this->ProspectiveUser->create();
            $this->ProspectiveUser->save($this->request->data);

            $flujoIdData = $this->ProspectiveUser->id;

            $datosUsu    = $this->ProspectiveUser->User->get_data($this->request->data['ProspectiveUser']['user_id']);
            $datosProspecto = $this->request->data;

            if(!is_null($this->request->data["ProspectiveUser"]["clients_natural_id"])){
                $datosContacto = $this->ProspectiveUser->ClientsNatural->get_data($datosProspecto["ProspectiveUser"]["clients_natural_id"]);
                $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'),$datosProspecto["ProspectiveUser"]["origin"]);
                $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                $this->addProspectiveAtentionTime($flujoIdData);
                $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$datosContacto['ClientsNatural']['name'],$datosContacto['ClientsNatural']['telephone'],$datosContacto['ClientsNatural']['cell_phone'],$datosContacto['ClientsNatural']['email'],$datosContacto['ClientsNatural']['city'],'prospectiveUsers/adviser?q='.$flujoIdData);  
            }else if($this->request->data["ProspectiveUser"]["contacs_users_id"] != 0){
                $datosContacto                              = $this->ProspectiveUser->ContacsUser->get_data($datosProspecto["ProspectiveUser"]["contacs_users_id"]);
                $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'));
                $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                $this->addProspectiveAtentionTime($flujoIdData);
                $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$datosContacto['ContacsUser']['name'],$datosContacto['ContacsUser']['telephone'],$datosContacto['ContacsUser']['cell_phone'],$datosContacto['ContacsUser']['email'],$datosContacto['ContacsUser']['city'],'prospectiveUsers/adviser?q='.$flujoIdData);    
            }else{
                $nombre = "Sin nombre, por gestionar";
                $phone = empty($datosProspecto["ProspectiveUser"]["phone_customer"]) ? "Sin teléfono, por gestionar" : $datosProspecto["ProspectiveUser"]["phone_customer"];
                $cell_phone = empty($datosProspecto["ProspectiveUser"]["phone_customer"]) ? "Sin celular, por gestionar" : $datosProspecto["ProspectiveUser"]["phone_customer"];
                $email = empty($datosProspecto["ProspectiveUser"]["email_customer"]) ? "Sin email, por gestionar" : $datosProspecto["ProspectiveUser"]["email_customer"];
                $city = "Sin ciudad, por gestionar";

                $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'),$datosProspecto["ProspectiveUser"]["origin"]);
                $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                $this->addProspectiveAtentionTime($flujoIdData);
                $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$nombre,$cell_phone,$phone, $email,$city,'prospectiveUsers/adviser?q='.$flujoIdData);  
            }
            return json_encode(array("mssage" => "Correcto", "id" => $flujoIdData));
            
        }
    }


    public function get_last_asesor(){
        $this->autoRender = false;
        header('Access-Control-Allow-Origin: *');
        $this->response->header('Access-Control-Allow-Origin', '*');
        $data = $this->request->data;
        if($this->request->is("post")){
            $phone      = $this->request->data["phone"];
            $customer   = $this->getClientByPhone($phone);
            $email = 0;
            if (!is_null($customer)){
                if ($customer["type"] == "legal") {

                    if($customer["clients_legal_id"] == 1917 || $customer["clients_legal_id"] == 10629){
                        return json_encode(["mssage" => "Correcto", "email" => 'logistica@kebco.co' ]);
                    }

                    $lastFlow = $this->ProspectiveUser->find("first",["order"=>["ProspectiveUser.created" => "DESC" ], "conditions" => ["ProspectiveUser.contacs_users_id" => $customer["id"], "ProspectiveUser.created >=" => date("Y-m-d H:i:s",strtotime("-6 month")) , "bill_text !=" => null ] ]);
                }else{
                    $lastFlow = $this->ProspectiveUser->find("first",["order"=>["ProspectiveUser.created" => "DESC" ], "conditions" => ["ProspectiveUser.clients_natural_id" => $customer["id"], "ProspectiveUser.created >=" => date("Y-m-d H:i:s",strtotime("-6 month")) , "bill_text !=" => null  ] ]);
                }

                if (!empty($lastFlow)) {
                    $email = $lastFlow["User"]["email"];
                }
            }
            return json_encode(array("mssage" => "Correcto", "email" => $email));
        }
    }

    public function flujo_especial_chatapi(){
        $this->autoRender = false;
        header('Access-Control-Allow-Origin: *');
        $this->response->header('Access-Control-Allow-Origin', '*');
        $data = $this->request->data;
        if($this->request->is("post")){

            $email               = $this->request->data["email"];
            $phone               = $this->request->data["phone"];
            $linea               = $this->request->data["linea"];

            if (empty($email) || $email == null || $email == "") {
                return json_encode(array("mssage" => "incorrecto", "id" => null));
            }

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

            $datosUsu    = $this->ProspectiveUser->User->get_data($this->request->data['ProspectiveUser']['user_id']);
            $datosProspecto = $this->request->data;

            if(!is_null($this->request->data["ProspectiveUser"]["clients_natural_id"])){
                $datosContacto = $this->ProspectiveUser->ClientsNatural->get_data($datosProspecto["ProspectiveUser"]["clients_natural_id"]);
                $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'),$datosProspecto["ProspectiveUser"]["origin"]);
                $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                $this->addProspectiveAtentionTime($flujoIdData);
                $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$datosContacto['ClientsNatural']['name'],$datosContacto['ClientsNatural']['telephone'],$datosContacto['ClientsNatural']['cell_phone'],$datosContacto['ClientsNatural']['email'],$datosContacto['ClientsNatural']['city'],'prospectiveUsers/adviser?q='.$flujoIdData);  
            }else if($this->request->data["ProspectiveUser"]["contacs_users_id"] != 0){
                $datosContacto                              = $this->ProspectiveUser->ContacsUser->get_data($datosProspecto["ProspectiveUser"]["contacs_users_id"]);
                $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'));
                $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                $this->addProspectiveAtentionTime($flujoIdData);
                $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$datosContacto['ContacsUser']['name'],$datosContacto['ContacsUser']['telephone'],$datosContacto['ContacsUser']['cell_phone'],$datosContacto['ContacsUser']['email'],$datosContacto['ContacsUser']['city'],'prospectiveUsers/adviser?q='.$flujoIdData);    
            }else{
                $nombre = "Sin nombre, por gestionar";
                $phone = empty($datosProspecto["ProspectiveUser"]["phone_customer"]) ? "Sin teléfono, por gestionar" : $datosProspecto["ProspectiveUser"]["phone_customer"];
                $cell_phone = empty($datosProspecto["ProspectiveUser"]["phone_customer"]) ? "Sin celular, por gestionar" : $datosProspecto["ProspectiveUser"]["phone_customer"];
                $email = empty($datosProspecto["ProspectiveUser"]["email_customer"]) ? "Sin email, por gestionar" : $datosProspecto["ProspectiveUser"]["email_customer"];
                $city = "Sin ciudad, por gestionar";

                $this->saveStagesFlow($flujoIdData,$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'),$datosProspecto["ProspectiveUser"]["origin"]);
                $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
                $this->addProspectiveAtentionTime($flujoIdData);
                $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$datosProspecto["ProspectiveUser"]["reason"],$datosProspecto["ProspectiveUser"]["reason"],$nombre,$cell_phone,$phone, $email,$city,'prospectiveUsers/adviser?q='.$flujoIdData);  
            }
            return json_encode(array("mssage" => "Correcto", "id" => $flujoIdData));
            
        }
    }

    public function validate_customer_especial(){
        $this->autoRender = false;
        $this->loadModel("ClientsNatural");
        $this->loadModel("ContacsUser");

        $natural    = array();
        $contacto   = array();
        $datos      = array();

        if(!empty($this->request->data["emailCliente"]) && !empty($this->request->data["phoneCustomer"])){
            $natural    = $this->ClientsNatural->findByTelephoneOrCellPhoneOrEmail($this->request->data["phoneCustomer"],$this->request->data["phoneCustomer"],$this->request->data["emailCliente"]);
            $contacto   = $this->ContacsUser->findByTelephoneOrCellPhoneOrEmail($this->request->data["phoneCustomer"],$this->request->data["phoneCustomer"],$this->request->data["emailCliente"]);
        }elseif(!empty($this->request->data["emailCliente"])){
            $natural    = $this->ClientsNatural->findByEmail($this->request->data["emailCliente"]);
            $contacto   = $this->ContacsUser->findByEmail($this->request->data["emailCliente"]);
        }elseif(!empty($this->request->data["phoneCustomer"])){
            $natural    = $this->ClientsNatural->findByTelephoneOrCellPhone($this->request->data["phoneCustomer"],$this->request->data["phoneCustomer"]);
            $contacto   = $this->ContacsUser->findByTelephoneOrCellPhone($this->request->data["phoneCustomer"],$this->request->data["phoneCustomer"]);
        }

        if(!empty($natural)){
            $datos = array(
                "type" => "natural",
                "id" => $natural["ClientsNatural"]["id"],
                "name" => $natural["ClientsNatural"]["name"]." - ".$natural["ClientsNatural"]["email"]
            );
        }else if(!empty($contacto)){
            $datos = array(
                "type" => "legal",
                "id" => $contacto["ContacsUser"]["id"],
                "name" => $contacto["ClientsLegal"]["name"],
                "name_contact" => $contacto["ContacsUser"]["name"]." - ".$contacto["ContacsUser"]["email"]
            );
        }
        return json_encode($datos);
    }

    public function validarNumeroTelefono($numero) {
    // Definir los códigos de país para América
        $paises = [
            '57' => 'Colombia',
            '1' => 'Estados Unidos',
            '52' => 'México',
            '503' => 'El Salvador',
            '504' => 'Honduras',
            '505' => 'Nicaragua',
            '506' => 'Costa Rica',
            '507' => 'Panamá',
            '51' => 'Perú',
            '54' => 'Argentina',
            '55' => 'Brasil',
            '56' => 'Chile',
            '58' => 'Venezuela',
            '59' => 'Paraguay',
            '591' => 'Bolivia',
            '592' => 'Guyana',
            '593' => 'Ecuador',
            '595' => 'Paraguay',
            '596' => 'Martinica',
            '597' => 'Surinam',
            '598' => 'Uruguay',
            '599' => 'Curazao'
        ];

        // Quitar cualquier carácter no numérico del número de teléfono
        $numero = preg_replace('/[^0-9+]/', '', $numero);

        // Remover el signo de más si está presente
        if (substr($numero, 0, 1) === '+') {
            $numero = substr($numero, 1);
        }

        // Verificar si el número de teléfono coincide con algún código de país
        foreach ($paises as $codigo => $pais) {
            if (strpos($numero, $codigo) === 0) {
                return $pais;
            }
        }

        return 'Colombia';
    }

    public function create_temp(){
        $this->autoRender = false;

        $this->loadModel("Product");
        $this->loadModel("Note");
        $this->loadModel("Quotation");

        if(empty($this->request->data)){
            $this->request->data = $this->object_to_array(json_decode($this->request->input()));
        }

        $pais                = $this->validarNumeroTelefono($this->request->data["phone"]);

        $conversation_id     = $this->request->data["conversation_id"];
        $phone               = $this->request->data["phone"];
        $products            = $this->request->data["products"];
        $quotation_id        = isset($this->request->data["quotation_id"]) ? $this->request->data["quotation_id"] : null;
        $posNacional         = strpos($phone, "+57");
        $phone               = str_replace("+57", "", $phone);

        $customer = $this->getOrCreateClient(["A"=>$this->request->data["response_crm"]["name"],"B"=>$this->request->data["response_crm"]["email"], "C" => $phone, "D" => $pais ]);

        $config    = array(
            "Config" => array(
                "reason" => "Cotización de productos CODY IA",
                "page" => $this->request->referer(),
                "country" => "Colombia",
                "origin" => "Robot",
                "from_bot" => "1",
                "status" => $conversation_id
            )
        );

        $exixstFlowActually = $this->ProspectiveUser->find("first",["conditions"=> ["from_bot" => 1, "status" => $conversation_id ], "recursive" => -1 ]);

        if(!empty($exixstFlowActually)){
            $flujoId = $exixstFlowActually["ProspectiveUser"]["id"];
        }else{
            $flujoId        = $this->createProspective($customer,$config, 112);
        }


        $datos              = $this->ProspectiveUser->findById($flujoId);

        if(empty($exixstFlowActually)){            

            $datosFlowstage = array(
                "FlowStage" => array(
                    "contact" => $customer["id"],
                    "reason" => 'Cotización de productos CODY IA',
                    "origin" => 'Cotización de productos CODY IA',
                    "description" => "Cotización de productos CODY IA",
                    "state_flow" => Configure::read('variables.nombre_flujo.flujo_contactado'),
                    "cotizacion" => "1",
                    "prospective_users_id" => $flujoId
                )
            );


            $this->ProspectiveUser->FlowStage->create();
            $this->ProspectiveUser->FlowStage->save($datosFlowstage);
            $this->updateStateProspectiveFlow($flujoId,Configure::read('variables.control_flujo.flujo_contactado'));
            $flowContactado = $this->ProspectiveUser->FlowStage->id;

        }else{
            $flowContactado = $this->ProspectiveUser->FlowStage->find("first",["conditions"=>["FlowStage.prospective_users_id" => $flujoId, "state_flow" => Configure::read('variables.nombre_flujo.flujo_contactado')],"recursive" => -1 ]); 

            if(!empty($flowContactado)){
                $flowContactado = $flowContactado["FlowStage"]["id"];
            }
        }

        $this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $trmActual      = ($config["Config"]["trm"]+$config["Config"]["ajusteTrm"])*1.05;


        if(is_null($quotation_id)){
            $this->Product->hasMany = [];

            foreach ($products as $keyProd => $valProd) {
                // $products[$keyProd] = [ "Product.part_number LIKE" => "%".$this->limpiarTexto($valProd)."%" ] ;
                $products[$keyProd] = [ "Product.part_number" => $this->limpiarTexto($valProd) ] ;
            }

            $products_quotation  = $this->Product->find("all",["conditions"=>[ "OR" => $products ] ]);

            $partsData              = [];
            $costos                 = [];
            $inventory              = [];

            if(!empty($products_quotation)){
                $partsData              = $this->getValuesProductsWo($products_quotation);
                $inventory              = $this->getTotalesStock($partsData);
                $costos                 = $this->getCosts($partsData);
            }

            

            $dataQuotation  = ["Quotation" => [
                "name" => "Cotización de productos",
                "prospective_users_id" => $flujoId,            
                "codigo" => $this->generateIdentificationQuotation($flujoId),
                "user_id" => 13,   
                "conditions" => $this->Note->field("description",["id"=> 10]),
                "header_id" => $posNacional === false ? 3 : 1,
                "total_visible" => 1,
                "flow_stage_id" => 0,
                "state" => 1,
                "notes" => "",
                "notes_description" => "",
                "show" => 1,
                "show_iva" => $pais == 'Colombia' ? 1 : 0, 
                "total" => 0,
                "conversation_id" => $conversation_id,
            ]];
            if(!empty($customer)){
                if($customer["type"] == "natural"){
                    $dataQuotation["Quotation"]["clients_natural_id"] = $customer["id"];
                }else{
                   $dataQuotation["Quotation"]["contacs_user_id"] = $customer["id"];
                }
            }

            $products_add = [];
            $datosProducto = [];

            foreach ($products_quotation as $key => $product) {

                if(!in_array($product["Product"]["id"], $products_add)){

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
                            $precioVenta    = $precio * 1.02 / ( 1 - ($product["Category"]["margen_wo"]/100) );
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

                    if($product["Product"]["fixed_price"] > 0 && $typeCost == 'WO'){
                        $precioVenta = $product["Product"]["fixed_price"] ;
                    }elseif($product["Product"]["fixed_cop"] > 0 && $typeCost == 'CRM'){
                        $precioVenta = $product["Product"]["fixed_cop"] ;
                    }elseif($product["Product"]["fixed_usd"] > 0 && $typeCost == 'REPOSICION' && $currency == 'usd'){
                        $precioVenta = $product["Product"]["fixed_usd"] ;
                    }elseif($product["Product"]["fixed_cop"] > 0 && $typeCost == 'REPOSICION' && $currency == 'cop'){
                        $precioVenta = $product["Product"]["fixed_cop"] ;
                    }                

                    
                    $dataQuotation["Quotation"]["total"]  += $precioVenta;
                    $datosProducto[] = [
                        "QuotationsProduct" =>
                        [
                            "product_id" => $product["Product"]["id"],
                            "price" => $precioVenta,
                            "type" => (isset($costos[$product["Product"]["part_number"]]) && ($costos[$product["Product"]["part_number"]] * 1) >=  $costoRep ? "REPOSICION" : "WO"),
                            "currency" => $currency,
                            "quantity" => "1",
                            "delivery" => isset( $inventory[ $product["Product"]["part_number"] ]) && $inventory[ $product["Product"]["part_number"] ] ? 'Inmediato' : 'Consultar Disponibilidad'
                        ]
                    ];

                    $products_add[] = $product["Product"]["id"];
                } 
            }

            $this->Quotation->create();
            $this->Quotation->save($dataQuotation);

            foreach ($datosProducto as $key => $value) {
                $value["QuotationsProduct"]["quotation_id"] = $this->Quotation->id;
                $this->Quotation->QuotationsProduct->create();
                $this->Quotation->QuotationsProduct->save($value);
            }
        }else{
            $this->loadModel("Config");
            $this->loadModel("QuotationsMarketing");
            $config     = $this->Config->findById(1);
            $cotizacion = $this->QuotationsMarketing->findById($quotation_id);

            $totalData = 0;

            if (!empty($cotizacion) && !empty($cotizacion["QuotationsMarketing"]["products"])) {
                $products  = $this->object_to_array(json_decode($cotizacion["QuotationsMarketing"]["products"]));
                $productos = [];
                foreach ($products as $key => $value) {
                   $productos[] = $value["Producto"];
                   $totalData+= ($value["Producto"]["price"]*$value["Producto"]["quantity"]);
                }
                $productos = Set::sort($productos, '{n}.number', 'asc');

            }else{
                return "ERR_OR";
            }

            if (!empty($cotizacion["QuotationsMarketing"]["user_id"]) && !is_null($cotizacion["QuotationsMarketing"]["user_id"]) ) {
                $asesores = [$cotizacion["QuotationsMarketing"]["user_id"]];
            }else{
                $asesores = [13];
            }




            $num = 0;
            $config["Config"]["origin"] = $cotizacion["QuotationsMarketing"]["origin"];
            $config["Config"]["reason"] = $cotizacion["QuotationsMarketing"]["name"];

            $this->loadModel("Quotation");
            $this->loadModel("Note");

            $dataQuotation = array(
                "Quotation" => array(
                    "prospective_users_id" => $flujoId,
                    "codigo" => "KEB".$flujoId."-01",
                    "name" => "Cotización: ".$cotizacion["QuotationsMarketing"]["name"],
                    "customer_note" => $cotizacion["QuotationsMarketing"]["customer_note"],
                    "total" => $totalData,
                    "header_id" => $posNacional === false ? 3 : $cotizacion["QuotationsMarketing"]["header_id"],
                    "total_visible" => $cotizacion["QuotationsMarketing"]["total_visible"],
                    "notes" => $this->Note->field("description",["id"=> $cotizacion["QuotationsMarketing"]["notes"] ]),
                    "notes_description" => $this->Note->field("description",["id"=> $cotizacion["QuotationsMarketing"]["notes_description"] ]),
                    "conditions" => $this->Note->field("description",["id"=> $cotizacion["QuotationsMarketing"]["conditions"] ]),
                    "currency" => $posNacional === false  ? "usd" : "cop",
                    "flow_stage_id" => $flowContactado,
                    "show_iva" => $pais == 'Colombia' ? 1 : 0, 
                    "user_id" => $asesores[$num]
                )
            );

            $this->Quotation->create();
            $this->Quotation->save($dataQuotation);
            $idQuotation = $this->Quotation->id;


            foreach ($productos as $key => $value) {
                $this->Quotation->QuotationsProduct->create();

                $this->Product->hasMany = [];
                $product     = $this->Product->find("first",["conditions"=> ["Product.id" => $value["id"] ] ]);


                $inventory   = [];
                $partsData   = [];
                if(!empty($product)){
                    $partsData              = $this->getValuesProductsWo([$product]);
                    $inventory              = $this->getTotalesStock($partsData);
                    $costos                 = $this->getCosts($partsData);
                }

                if(!is_null($product["Product"]["margen_wo"]) && $product["Product"]["margen_wo"] > 0 ){
                    $product["Category"]["margen_wo"] = $product["Product"]["margen_wo"];
                }

                if(!is_null($product["Product"]["margen_usd"]) && $product["Product"]["margen_usd"] > 0 ){
                    $product["Category"]["margen"] = $product["Product"]["margen_usd"];
                }

                if(!is_null($product["Product"]["factor"]) && $product["Product"]["factor"] > 0 ){
                    $product["Category"]["factor"] = $product["Product"]["factor"];
                }

                // if($posNacional === false ){
                //     $precio      = $product["Product"]["purchase_price_usd"] + $product["Product"]["aditional_usd"];
                //     $precioVenta = $precio / 0.8;
                //     $precioVenta = round(($precioVenta *1.01),2);
                //     $currency    = "usd";
                // }else{
                //     $currency    = $value["currency"];
                //     $precioVenta = $value["price"];
                // }


                $costo    = $product["Product"]["purchase_price_wo"];
                $costoRep = round($product["Category"]["factor"] * ($product["Product"]["purchase_price_usd"] * $trmActual),2);

                $typeCost = "CRM";

                if($posNacional === false ){
                    $precio      = $product["Product"]["purchase_price_usd"] + $product["Product"]["aditional_usd"];
                    $precioVenta = $precio / 0.8;
                    $precioVenta = round(($precioVenta ),2);
                    $currency    = "usd";
                    $typeCost    = "REPOSICION";
                }else{
                    $currency    = "cop";
                    if($product["Product"]["type"] == 0){
                        $precio         = $product["Product"]["purchase_price_cop"] + $product["Product"]["aditional_cop"];
                        $precioVenta    = $precio * 1.02 / ( 1 - ($product["Category"]["margen_wo"]/100) );
                        $typeCost       = "CRM";
                    }else{
                        if ( isset( $inventory[ $product["Product"]["part_number"] ]) && $inventory[ $product["Product"]["part_number"] ] > 0 && isset($costos[$product["Product"]["part_number"]]) && ($costos[$product["Product"]["part_number"]] * 1) >=  $costoRep ) {
                            $precio = $costos[$product["Product"]["part_number"]]*1;
                            $typeCost    = "WO";
                        }else{
                            $precio = $costoRep;
                            $typeCost = "REPOSICION";
                        }
                        $precioVenta    = round($precio / ( 1 - ($product["Category"]["margen_wo"]/100) ) ) * 1 ;

                    }                    
                } 

                if($product["Product"]["fixed_price"] > 0 && $typeCost == 'WO'){
                    $precioVenta = $product["Product"]["fixed_price"] ;
                }elseif($product["Product"]["fixed_cop"] > 0 && $typeCost == 'CRM'){
                    $precioVenta = $product["Product"]["fixed_cop"] ;
                }elseif($product["Product"]["fixed_usd"] > 0 && $typeCost == 'REPOSICION' && $currency == 'usd'){
                    $precioVenta = $product["Product"]["fixed_usd"] ;
                }elseif($product["Product"]["fixed_cop"] > 0 && $typeCost == 'REPOSICION' && $currency == 'cop'){
                    $precioVenta = $product["Product"]["fixed_cop"] ;
                }

                $dataProduct = array(
                   "QuotationsProduct" => [
                        "quotation_id"  => $idQuotation,
                        "product_id"    => $value["id"],
                        "price"         => $precioVenta,
                        "quantity"      => $value["quantity"],
                        "delivery"      => isset( $inventory[ $product["Product"]["part_number"] ]) && $inventory[ $product["Product"]["part_number"] ] ? 'Inmediato' : 'Consultar Disponibilidad',
                        "state"         => 0,
                        "currency"      => $currency
                   ]
                );

                $this->Quotation->QuotationsProduct->save($dataProduct);
            }

        }

        

        $datosFlowsCotizado = array(
            "FlowStage" => array(
                "document" => $this->Quotation->id,
                "priceQuotation" => $dataQuotation["Quotation"]["total"],
                "codigoQuotation" => $dataQuotation["Quotation"]["codigo"],
                "state_flow" => Configure::read('variables.nombre_flujo.flujo_cotizado'),
                "prospective_users_id" => $flujoId,
                "copias_email" => "",                        
            )
        );

        $this->ProspectiveUser->FlowStage->create();
        $this->ProspectiveUser->FlowStage->save($datosFlowsCotizado);

        $this->Quotation->save(["Quotation" => [
            "id" => $this->Quotation->id,
            "flow_stage_id" => $this->ProspectiveUser->FlowStage->id
        ] ]);


        $this->ProspectiveUser->save(
            ["ProspectiveUser"=>[
                "id"=>$flujoId,
                "from_bot"=>1,
                "notified" => 0,
                "alert_one" => 0,
                "returned" => 0,
                "alert_two" => 0,
                "deadline_notified" => null,
                "user_lose" => null,
                "country" => $pais,
                "date_final_alert" => null,
                "date_quotation"   => date("Y-m-d H:i:s"),
                "date_alert"=> date("Y-m-d H:i:s", strtotime("+".Configure::read("DIAS_NOTIFY_ONE"). " day")),
            ]
        ]);

        $this->updateStateProspectiveFlow($flujoId,Configure::read('variables.control_flujo.flujo_cotizado'));

        

        try {

            $rutaURL            = 'Quotations/view/'.$this->encryptString($this->Quotation->id);

            $prospectoFinal     = $this->ProspectiveUser->findById($flujoId);

            $options = array(
                'to'        => $prospectoFinal['User']['email'],
                'template'  => 'quote_sent',
                'cc'        => [],
                'subject'   => 'Has recibido la cotización '.$dataQuotation["Quotation"]["codigo"].' desde el <b>ROBOT</b> de KEBCO AlmacenDelPintor.com',
                'vars'      => array('codigo' => $dataQuotation["Quotation"]["codigo"],'nameClient' => $prospectoFinal['User']['name'],'nameAsesor' => 'SISTEMA','requerimiento' => $dataQuotation["Quotation"]["name"],'ruta'=>$rutaURL,"texto" => '',"quotationID" => $this->Quotation->id )
            );
            $this->sendMail($options);
        } catch (Exception $e) {
                
        }

        return json_encode(array("url" => Router::url("/",true)."quotations/view/".$this->encrypt($this->Quotation->id), "id" => $flujoId));
    }

    private function createProspective($clientData, $config, $user_id, $ventas = null, $data_client = null ){

        $datosProspecto = array(
            "ProspectiveUser" => array(
                "type"          => 0,
                "origin"        => $config["Config"]["origin"],
                "page"          => isset($config["Config"]["page"]) ? $config["Config"]["page"] : null,
                "state_flow"    => 1,
                "user_receptor" => is_null(AuthComponent::user("id")) ? $user_id : AuthComponent::user("id"),
                "clients_natural_id" => $clientData["type"] == "natural" ? $clientData["id"] : NULL,
                "contacs_users_id"   => $clientData["type"] == "legal" ? $clientData["id"] : 0,
                "state"              => 1,
                "description"        => $config["Config"]["reason"],
                "country"            => isset($config["Config"]["country"]) ? $config["Config"]["country"] : "Colombia",
                "user_id"            => $user_id,
                "remarketing_ventas" => is_null($ventas) ? null : json_encode($ventas),
                "bill_file"          => is_null($data_client) ? null : ($data_client),
                "remarketing"        => is_null($ventas) ? 0 : 1,
                "from_bot"           => isset($config["Config"]["from_bot"]) ? $config["Config"]["from_bot"] : 0,
                "status"             => isset($config["Config"]["status"]) ? $config["Config"]["status"] : 1,
                "time_contact"       => $this->calculateHoursGest( Configuration::get_flow("hours_contact")),

            )
        );

        $this->ProspectiveUser->create();
        $this->ProspectiveUser->save($datosProspecto);

        $datosProspecto["ProspectiveUser"]["time_contact"]       = $this->calculateHoursGest( Configuration::get_flow("hours_contact") );

        $flujoIdData = $this->ProspectiveUser->id;

        $datosUsu    = $this->ProspectiveUser->User->get_data($datosProspecto['ProspectiveUser']['user_id']);
        if($clientData["type"] == "natural"){
            $datosContacto = $this->ProspectiveUser->ClientsNatural->get_data($datosProspecto["ProspectiveUser"]["clients_natural_id"]);
            $this->saveStagesFlow($flujoIdData,$config["Config"]["reason"],$config["Config"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'),$config["Config"]["origin"]);
            $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
            $this->addProspectiveAtentionTime($flujoIdData);
            $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
            $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$config["Config"]["reason"],$config["Config"]["reason"],$datosContacto['ClientsNatural']['name'],$datosContacto['ClientsNatural']['telephone'],$datosContacto['ClientsNatural']['cell_phone'],$datosContacto['ClientsNatural']['email'],$datosContacto['ClientsNatural']['city'],'prospectiveUsers/adviser?q='.$flujoIdData);  
        }else{
            $reason = $config["Config"]["reason"];
            $datosContacto  = $this->ProspectiveUser->ContacsUser->get_data($datosProspecto["ProspectiveUser"]["contacs_users_id"]);
            $this->saveStagesFlow($flujoIdData,$config["Config"]["reason"],$config["Config"]["reason"],Configure::read('variables.nombre_flujo.flujo_asignado'));
            $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosProspecto['ProspectiveUser']['user_id'],$flujoIdData,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujoIdData);
            $this->addProspectiveAtentionTime($flujoIdData);
            $this->saveAtentionTimeFlujoEtapasLimitTime($flujoIdData,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
            $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$reason,$config["Config"]["reason"],$datosContacto['ContacsUser']['name'],$datosContacto['ContacsUser']['telephone'],$datosContacto['ContacsUser']['cell_phone'],$datosContacto['ContacsUser']['email'],$datosContacto['ContacsUser']['city'],'prospectiveUsers/adviser?q='.$flujoIdData);    
        }

        return $flujoIdData;
    }

    public function flujo_masivo(){
        if($this->request->is("post")){
            $file_mimes = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if(isset($this->request->data["ProspectiveUser"]["file"]["name"]) && in_array($this->request->data["ProspectiveUser"]["file"]["type"], $file_mimes)){
                try {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $spreadsheet = $reader->load($this->request->data["ProspectiveUser"]["file"]['tmp_name']);
                    $xls_data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                     unset($xls_data[1]);
                } catch (Exception $e) {
                    $xls_data = array();
                }
                 

                if (!empty($xls_data)) {

                    $this->loadModel("Config");
                    $this->loadModel("QuotationsMarketing");
                    $config     = $this->Config->findById(1);
                    $cotizacion = $this->QuotationsMarketing->findById($this->request->data["ProspectiveUser"]["cotizacion"]);

                    $totalData = 0;

                    if (!empty($cotizacion) && !empty($cotizacion["QuotationsMarketing"]["products"])) {
                        $products  = $this->object_to_array(json_decode($cotizacion["QuotationsMarketing"]["products"]));
                        $productos = [];
                        foreach ($products as $key => $value) {
                           $productos[] = $value["Producto"];
                           $totalData+= ($value["Producto"]["price"]*$value["Producto"]["quantity"]);
                        }
                        $productos = Set::sort($productos, '{n}.number', 'asc');

                    }else{
                        $this->Session->setFlash(__('No hay productos configurados para enviar cotizaciones masivas.'),'Flash/error');
                        $this->redirect(["action"=>"flujo_masivo"]);
                    }

                    if (!empty($cotizacion["QuotationsMarketing"]["user_id"]) && !is_null($cotizacion["QuotationsMarketing"]["user_id"]) ) {
                        $asesores = [$cotizacion["QuotationsMarketing"]["user_id"]];
                    }else{
                        $asesores = [5,11,7,3,2];
                    }

                    $num = 0;
                    $config["Config"]["origin"] = $cotizacion["QuotationsMarketing"]["origin"];
                    $config["Config"]["reason"] = $cotizacion["QuotationsMarketing"]["name"];

                    foreach ($xls_data as $key => $value) {
                        $datosFila = $value;
                        $client_information = $this->getOrCreateClient($value);
                        $flujoId            = $this->createProspective($client_information,$config, $asesores[$num]);
                        $datosFlowstage = array(
                            "FlowStage" => array(
                                "contact" => $client_information["id"],
                                "reason" => $cotizacion["QuotationsMarketing"]["name"],
                                "origin" => $cotizacion["QuotationsMarketing"]["origin"],
                                "description" => $cotizacion["QuotationsMarketing"]["name"],
                                "state_flow" => Configure::read('variables.nombre_flujo.flujo_contactado'),
                                "cotizacion" => "1",
                                "prospective_users_id" => $flujoId
                            )
                        );

                        $this->ProspectiveUser->FlowStage->create();
                        $this->ProspectiveUser->FlowStage->save($datosFlowstage);
                         
                        $this->updateStateProspectiveFlow($flujoId,Configure::read('variables.control_flujo.flujo_contactado'));

                        $flowContactado = $this->ProspectiveUser->FlowStage->id;

                        $this->loadModel("Quotation");
                        $this->loadModel("Note");

                        $datosQuotation = array(
                            "Quotation" => array(
                                "prospective_users_id" => $flujoId,
                                "codigo" => "KEB".$flujoId."-01",
                                "name" => "Venta: ".$cotizacion["QuotationsMarketing"]["name"],
                                "customer_note" => $cotizacion["QuotationsMarketing"]["customer_note"],
                                "total" => $totalData,
                                "header_id" => $cotizacion["QuotationsMarketing"]["header_id"],
                                "total_visible" => $cotizacion["QuotationsMarketing"]["total_visible"],
                                "notes" => $this->Note->field("description",["id"=> $cotizacion["QuotationsMarketing"]["notes"] ]),
                                "notes_description" => $this->Note->field("description",["id"=> $cotizacion["QuotationsMarketing"]["notes_description"] ]),
                                "conditions" => $this->Note->field("description",["id"=> $cotizacion["QuotationsMarketing"]["conditions"] ]),
                                "currency" => "cop",
                                "flow_stage_id" => $flowContactado,
                                "user_id" => $asesores[$num]
                            )
                        );

                        $this->Quotation->create();
                        $this->Quotation->save($datosQuotation);
                        $idQuotation = $this->Quotation->id;


                        foreach ($productos as $key => $value) {
                            $this->Quotation->QuotationsProduct->create();
                            $dataProduct = array(
                               "QuotationsProduct" => [
                                    "quotation_id"  => $idQuotation,
                                    "product_id"    => $value["id"],
                                    "price"         => $value["price"],
                                    "quantity"      => $value["quantity"],
                                    "delivery"      => $value["delivery"],
                                    "state"         => 0,
                                    "currency"      => $value["currency"]
                               ]
                            );
                            $this->Quotation->QuotationsProduct->save($dataProduct);
                        }

                        $datosFlowsCotizado = array(
                            "FlowStage" => array(
                                "document" => $idQuotation,
                                "priceQuotation" => $datosQuotation["Quotation"]["total"],
                                "codigoQuotation" => $datosQuotation["Quotation"]["codigo"],
                                "state_flow" => Configure::read('variables.nombre_flujo.flujo_cotizado'),
                                "prospective_users_id" => $flujoId,
                                "copias_email" => "",            
                                "cotizacion" => 2,            
                            )
                        );

                        $this->ProspectiveUser->FlowStage->create();
                        $this->ProspectiveUser->FlowStage->save($datosFlowsCotizado);

                        $this->updateStateProspectiveFlow($flujoId,Configure::read('variables.control_flujo.flujo_cotizado'));

                        $nombreArchivo                  = $datosQuotation['Quotation']['codigo'].'.pdf';
                        $datosHeaders                   = $this->Quotation->Header->get_data($datosQuotation['Quotation']['header_id']);
                        $codigoQuotation                = $datosQuotation['Quotation']['codigo'];
                        $datosUsuario                   = $this->Quotation->User->get_data($asesores[$num]);
                        $datosQuationF                  = $this->Quotation->get_data($idQuotation);
                        $datosProductos                 = $this->Quotation->QuotationsProduct->get_data_quotation($idQuotation);

                        if ($client_information["type"] == "legal") {
                            $datosC                         = $this->Quotation->FlowStage->ProspectiveUser->ContacsUser->get_data_modelos($client_information["id"]);
                        } else {
                            $datosC                         = $this->Quotation->FlowStage->ProspectiveUser->ClientsNatural->get_data($client_information["id"]);
                        }

                        $datosProspective                   = $this->Quotation->FlowStage->ProspectiveUser->get_data_model_user($flujoId);
                        $productClient                      = array();

                        $this->loadModel("Config");
                        $iva            = $this->Config->field("ivaCol",["id" => 1]);

                        $options                        = array(
                            'template'  => 'new_quotation',
                            'ruta'      => APP . 'webroot/files/quotations/'.$nombreArchivo,
                            'vars'      => array('datosHeaders' => $datosHeaders,'codigoQuotation' => $codigoQuotation,'datos' => $datosProspective,'datosC' => $datosC, 'datosUsuario' => $datosUsuario, 'productClient' => $productClient, 'datosQuation' => $datosQuationF, 'datosProductos' => $datosProductos, "iva" => $iva),
                        );
                        $this->generatePdf($options);

                        $rutaURL                            = 'Quotations/view/'.$this->encryptString($idQuotation);

                        $this->sendEmailInformationQuotationNew($rutaURL,'',$datosQuotation['Quotation']['codigo'],$datosQuotation["Quotation"]["customer_note"],$flujoId,$datosFila["B"],$datosFila["A"]);

                        if (count($asesores) == 1) {
                            $num = 0;
                        }else{
                            if($num == 4){
                                $num = 0;
                            }else{
                                $num++;
                            }
                        }
                        
                    }
                    $this->Session->setFlash(__('Importación finalizada con éxito'),'Flash/success');
                }else{
                    $this->Session->setFlash(__('Las filas no són válidas'),'Flash/error');
                }                
                
            }else{
                $this->Session->setFlash(__('El archivo no es un excel'),'Flash/error');
            }
        }
        $this->loadModel("QuotationsMarketing");
        $cotizaciones = $this->QuotationsMarketing->find("list");
        $this->set("cotizaciones",$cotizaciones);
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

    public function flujo_tienda(){
        // $clientsLegals      = $this->getClientsLegals();
        // $clientsNaturals    = $this->getClientsNaturals();
        $usuarios           = $this->ProspectiveUser->User->role_asesor_comercial_user_true(true);
        if($this->request->is("post")){
            $datos                                   = $this->request->data["ProspectiveUser"];
            $datos['user_receptor']                  = AuthComponent::user('id');
            $keys                                    = array_keys($this->request->data);
            $existe                                  = false;

            foreach ($keys as $key => $value) {
                $parts = explode("-", $value);
                if ($parts["0"]=="Precio" && count($parts) == 3) {
                    $existe = true;
                    break;
                }
            }

            if (!$existe) {
                $this->Session->setFlash(__('Se debe agregar mínimo un producto'),'Flash/error');
            }else{

                $origen                                                     = "Tienda";
                if ($this->request->data["ProspectiveUser"]['flujo_no_valido'] == 1) {
                    $datos['state_flow']                 = Configure::read('variables.control_flujo.flujo_no_valido');               
                } else {
                    $datos['state_flow']                 = Configure::read('variables.control_flujo.flujo_asignado');
                }
                $reason                                                     = $this->request->data['ProspectiveUser']['reason'];

                if(strpos($this->request->data["ProspectiveUser"]["clients_natural_id"], "_LEGAL") === false){
                    $datos["clients_natural_id"]     = str_replace("_NATURAL", "", $datos["clients_natural_id"]);
                    $datos["contacs_users_id"]       = null;
                    $type = "1";
                }else{
                    $datos["contacs_users_id"]       = $this->request->data["contac_id"];
                    $datos["clients_natural_id"]     = null;
                    $type = "2";
                }
                
                $datos["state"]                  = 5;
                $datos["origin"] = "Tienda";

                $datos["type"] = 0;


                $this->ProspectiveUser->create();
                if ($this->ProspectiveUser->save($datos)) {
                    $flujo_id  = $this->ProspectiveUser->id;
                    $this->saveStagesFlow($flujo_id,$reason,$reason,Configure::read('variables.nombre_flujo.flujo_asignado'),$origen);
                    $datos["id"] = $flujo_id;
                    $datosFlowstage = array(
                        "FlowStage" => array(
                            "contact" => $type == "2" ? $this->request->data["contac_id"] : $datos["clients_natural_id"],
                            "reason" => $datos["reason"],
                            "origin" => $datos["origin"],
                            "description" => $datos["reason"],
                            "state_flow" => Configure::read('variables.nombre_flujo.flujo_contactado'),
                            "cotizacion" => "1",
                            "prospective_users_id" => $flujo_id
                        )
                    );

                    $this->ProspectiveUser->FlowStage->create();
                    $this->ProspectiveUser->FlowStage->save($datosFlowstage);
                     
                    $this->updateStateProspectiveFlow($flujo_id,Configure::read('variables.control_flujo.flujo_contactado'));

                    $flowContactado = $this->ProspectiveUser->FlowStage->id;

                    $this->loadModel("Quotation");

                    $datosQuotation = array(
                        "Quotation" => array(
                            "prospective_users_id" => $flujo_id,
                            "codigo" => "KEB".$flujo_id."-01",
                            "name" => "Venta: ".$datos["reason"],
                            "customer_note" => "",
                            "total" => str_replace(",", "", $datos["total"]),
                            "header_id" => 1,
                            "total_visible" => 1,
                            "notes" => "",
                            "notes_description" => "",
                            "conditions" => "",
                            "currency" => "cop",
                            "flow_stage_id" => $flowContactado,
                            "user_id" => $datos["user_id"]
                        )
                    );

                    $this->Quotation->create();
                    $this->Quotation->save($datosQuotation);
                    $idQuotation = $this->Quotation->id;

                    $otrosDatos = $this->request->data;

                    unset($otrosDatos["ProspectiveUser"]);
                    unset($otrosDatos["contact_id"]);

                    $idProductos = array();
                    $itTrs       = array();

                    foreach ($otrosDatos as $key => $value) {
                        if(strpos($key, "Cantidad-") !== false){
                            $parts = explode("-", $key);
                            $idProductos[] = count($parts) == 3 ? $parts[2] : $parts[1];
                            $itTrs[count($parts) == 3 ? $parts[2] : $parts[1]] = count($parts) == 3 ? $parts[1] : $parts[0];
                        }
                    }

                    $dataProductOrder = [];

                    foreach ($idProductos as $productoID => $value) {
                        $this->Quotation->QuotationsProduct->create();
                        $dataProduct = array(
                            "quotation_id" => $idQuotation,
                            "product_id" => $value,
                            "iva" => 1,
                            "price" => $otrosDatos["Precio-".$itTrs[$value]."-".$value],
                            "quantity" => isset($otrosDatos["Cantidad-".$itTrs[$value]."-".$value]) ? $otrosDatos["Cantidad-".$itTrs[$value]."-".$value] : 1,
                            "delivery" => $otrosDatos["Entrega-".$itTrs[$value]."-".$value],
                            "state" => 1
                        );
                        $this->Quotation->QuotationsProduct->save($dataProduct);

                        $dataProductOrder[] = array(
                            "order_id"   => null,
                            "product_id" => $value,
                            "price"      => $dataProduct["price"],
                            "quantity"   => $dataProduct["quantity"],
                            "iva"        => $dataProduct["iva"],
                            "delivery"   => $dataProduct["delivery"],
                            "currency"   => 'cost',
                            "cost"       => 0,
                            "state"      => 0,
                            "margin"     => 1
                        );
                    }

                    $datosFlowsCotizado = array(
                        "FlowStage" => array(
                            "document" => $idQuotation,
                            "priceQuotation" => $datosQuotation["Quotation"]["total"],
                            "codigoQuotation" => $datosQuotation["Quotation"]["codigo"],
                            "state_flow" => Configure::read('variables.nombre_flujo.flujo_cotizado'),
                            "prospective_users_id" => $flujo_id,
                            "copias_email" => "",                        
                        )
                    );

                    $this->ProspectiveUser->FlowStage->create();
                    $this->ProspectiveUser->FlowStage->save($datosFlowsCotizado);

                    $this->updateStateProspectiveFlow($flujo_id,Configure::read('variables.control_flujo.flujo_cotizado'));

                    $this->loadModel("Order");

                    $orderData = [
                        "Order" => [
                            "prospective_user_id" => $flujo_id,
                            "quotation_id"        => $idQuotation,
                            "payment_type"        => 1,
                            "payment_text"        => "Compra en tienda",
                            "note"                => "Compra en tienda",
                            "prefijo"             => Configure::read("PrefijoNAC"),
                            "code"                => $this->getLastCode(),
                            "deadline"            => date("Y-m-d"),
                            "user_id"             => $datos["user_id"],
                            "contacs_users_id"    => $datos["contacs_users_id"],
                            "clients_natural_id"  => $datos["clients_natural_id"],
                    ] ];

                    $this->Order->create();
                    $this->Order->save($orderData);
                    $orderID = $this->Order->id;

                    $this->loadModel("OrdersProduct");

                    foreach ($dataProductOrder as $key => $value) {
                        $value["order_id"] = $orderID;
                        $this->OrdersProduct->create();
                        $this->OrdersProduct->save($value);
                    }

                    $datosFlowNegociado = array(
                        "FlowStage" => array(
                            "state_flow" => Configure::read('variables.nombre_flujo.flujo_negociado'),
                            "prospective_users_id" => $flujo_id,
                            "description" => "Comprado en tienda",       
                            "cotizacion" => $orderID,                 
                        )
                    );

                    $this->ProspectiveUser->FlowStage->create();
                    $this->ProspectiveUser->FlowStage->save($datosFlowNegociado);

                    $this->updateStateProspectiveFlow($flujo_id,Configure::read('variables.control_flujo.flujo_negociado'));

                    // $datosFlowPagado = array(
                    //     "FlowStage" => array(
                    //         "state_flow" => Configure::read('variables.nombre_flujo.flujo_pagado'),
                    //         "prospective_users_id" => $flujo_id,
                    //         "payment" => "Efectivo",   
                    //         "valor" => ($datosQuotation["Quotation"]["total"] * 1.19),
                    //         "payment_day" => 0,
                    //         "payment_verification" => 1,
                    //         "date_verification" => date("Y-m-d"),
                    //         "state" => 1                     
                    //     )
                    // );

                    // $this->ProspectiveUser->FlowStage->create();
                    // $this->ProspectiveUser->FlowStage->save($datosFlowPagado);

                    // $this->updateStateProspectiveFlow($flujo_id,Configure::read('variables.control_flujo.flujo_pagado'));

                    $address = array();
                    $datosAddress = array(
                        "FlowStage" => array(
                            "prospective_users_id"  => $flujo_id,
                            "copias_email"          => "",
                            "flete"                 => "Tienda",
                        )
                    );
                    $datosAddress['FlowStage']['city']                       = empty($address) ? "Tienda" : $address["Adress"]["city"];
                    $datosAddress['FlowStage']['contact']                   = empty($address) ? "N/A" : $address["Adress"]["name"];
                    $datosAddress['FlowStage']['address']                   = empty($address) ? "N/A" : $address["Adress"]["address"];
                    $datosAddress['FlowStage']['additional_information']    = empty($address) ? "N/A" : $address["Adress"]["address_detail"];
                    $datosAddress['FlowStage']['telephone']                 = empty($address) ? "N/A" : $address["Adress"]["phone"].$telefono;
                    $datosAddress['FlowStage']['state_flow']                = Configure::read('variables.nombre_flujo.datos_despacho');
                    $datosAddress['FlowStage']['state']                     = 2;

                    $this->ProspectiveUser->FlowStage->create();
                    $this->ProspectiveUser->FlowStage->save($datosAddress);

                    $datosDespachoFinal['FlowStage']['document']                 = '';
                    $datosDespachoFinal['FlowStage']['number']                   = "0";
                    $datosDespachoFinal['FlowStage']['conveyor']                 = "Entrega en Oficina";
                    $datosDespachoFinal['FlowStage']['prospective_users_id']     = $flujo_id;
                    $datosDespachoFinal['FlowStage']['state_flow']               = Configure::read('variables.nombre_flujo.flujo_despachado');
                    $datosDespachoFinal['FlowStage']['products_send']            = count($idProductos);
                    $datosDespachoFinal['FlowStage']['state']                    = 6;

                    $this->ProspectiveUser->FlowStage->create();
                    $this->ProspectiveUser->FlowStage->save($datosDespachoFinal);

                    $this->updateStateProspectiveFlow($flujo_id,8);

                    $this->sendEmailClientOrderDelivred($datos['contacs_users_id'],$datos['clients_natural_id'],$datos['id']);

                    $this->updateStateProspective($flujo_id,5);

                    $this->Session->setFlash(__('Flujo en tienda creado correctamente'),'Flash/success');

                    $arryBusqueda = "txt_buscador_flujo=36436&state_flow=&txt_buscador_cliente=&txt_buscador_transportadora=&txt_buscador_contacto=&txt_buscador_guia=&txt_buscador_fecha=";

                    $arryBusqueda = parse_str($arryBusqueda,$txtSal);

                    $txtSal["txt_buscador_flujo"] = $flujo_id;

                    $this->redirect(array("controller" => "ProspectiveUsers", "action" => "despachos","?"=> $txtSal ));

                }
                
            }
                
        }
        $this->set(compact("clientsLegals","clientsNaturals",'usuarios'));
    }

    public function generate_document($id){
        $datosFlujo  = $this->ProspectiveUser->findById($id);
        $cliente     = $this->getDataCustomer($id);
        $total       = 0;
        $totalIVa    = 0;
        $descuento   = 0;

        $id_etapa_cotizado          = $this->FlowStage->id_latest_regystri_state_cotizado($id);
        $datosFlowstage             = $this->FlowStage->get_data($id_etapa_cotizado);
        if (is_numeric($datosFlowstage['FlowStage']['document'])) {
            $produtosCotizacion     = $this->FlowStage->Quotation->QuotationsProduct->findAllByQuotationId($datosFlowstage['FlowStage']['document']);
        } else {
            $produtosCotizacion     = array();
        }

        $quotation = end($produtosCotizacion);

        foreach ($produtosCotizacion as $key => $value) {
            $total += $value["QuotationsProduct"]["price"]* $value["QuotationsProduct"]["quantity"];
            $products[] = $value;

            if ($value["QuotationsProduct"]["iva"] == 1) {
                $totalIVa += ( $value['QuotationsProduct']['quantity'] *$value['QuotationsProduct']['price'] );
            }

        }

        if ($quotation["Quotation"]["descuento"] > 0) {
            $descuento = $total * ($quotation["Quotation"]["descuento"] / 100) ;
        }

        $letras = CifrasEnLetras::convertirNumeroEnLetras(round(( ($total-$descuento) + ( ($totalIVa - $descuento) * 0.19) ), 2));

        $this->set(compact("datosFlujo","cliente","produtosCotizacion","letras","total","descuento"));

    }

    public function savePaymentTienda(){
        $this->autoRender = false;
        if($this->request->is("post")){
            $documento = $this->loadPhoto($this->request->data['ProspectiveUser']['img'],'flujo/pagado');

            if($documento == 1){

                $datos['FlowStage']['document']             = $this->Session->read('imagenModelo');
                $datosFlowPagado = array(
                    "FlowStage" => array(
                        "state_flow" => Configure::read('variables.nombre_flujo.flujo_pagado'),
                        "prospective_users_id" => $this->request->data["ProspectiveUser"]["flujo_id"],
                        "payment" => $this->request->data["ProspectiveUser"]["payment"],   
                        "document" => $this->Session->read('imagenModelo'),
                        "valor" => $this->request->data["ProspectiveUser"]["value"],
                        "identificator" => $this->request->data["ProspectiveUser"]["identificator"],
                        "payment_day" => 0,
                        "payment_verification" => 0,
                        "state" => 2                     
                    )
                );

                $this->ProspectiveUser->FlowStage->create();
                $this->ProspectiveUser->FlowStage->save($datosFlowPagado);
                $this->messageUserRoleCotizacion($this->request->data['ProspectiveUser']['flujo_id'],'total',true);
                $this->updateStateProspective($this->request->data['ProspectiveUser']['flujo_id'],6);
                $this->Session->setFlash(__('Pago guardado correctamente, ahora deberá ser autorizado por tesorería'),'Flash/success');
            }else{
                $this->Session->setFlash(__('El pago no se ha guardado el archivo es incorrecto.'),'Flash/success');
            }

            $this->redirect(array("controller" => "ProspectiveUsers", "action" => "status_dispatches_finish","?"=>array("q"=>$this->request->data['ProspectiveUser']['flujo_id'])));
        }
        $this->redirect(array("controller" => "ProspectiveUsers", "action" => "status_dispatches_finish"));
    }

    public function update_flujos_no_gest(){
        $this->autoRender = false;
        $conditions = array(
            "ProspectiveUser.state_flow" => 5,
            "FlowStage.state" => 7,
            "FlowStage.state_flow" => "Pagado",
        );

        $this->loadModel("FlowStage");
        $this->loadModel("QuotationsProduct");

        $order = array("ProspectiveUser.created" => "DESC");

        $fields = array("ProspectiveUser.*","FlowStage.*");

        $recursive = -1;

        $flujos = $this->ProspectiveUser->FlowStage->find("all",compact("conditions","order","fields"));

        if (empty($flujos)) {
            return false;
        }

        foreach ($flujos as $key => $value) {
            $codigoCotizacionFlowstageState     = $this->FlowStage->id_latest_regystri_state_cotizado($value["FlowStage"]["prospective_users_id"]);
            $quotation_id                       = $this->FlowStage->field("document",["id" => $codigoCotizacionFlowstageState]);
            
            $allProducts                        = $this->QuotationsProduct->find("count",["conditions"=>["QuotationsProduct.quotation_id" => $quotation_id ]]);
            $allProductsGest                    = $this->QuotationsProduct->find("count",["conditions"=>["QuotationsProduct.quotation_id" => $quotation_id,"QuotationsProduct.state != " => 0 ]]);

            if ($allProducts == $allProductsGest) {
                $value["FlowStage"]["state"] = 3;
                $this->FlowStage->save($value["FlowStage"]);
            }
        }
    }

    public function flujos_sin_gestionar(){
        $this->autoRender = false;

        $conditions = array(
            "ProspectiveUser.state_flow" => 5,
            "FlowStage.state" => 7,
            "FlowStage.state_flow" => "Pagado",
        );

        $order = array("ProspectiveUser.created" => "DESC");

        $fields = array("ProspectiveUser.*","FlowStage.*");

        $flujos = $this->ProspectiveUser->FlowStage->find("all",compact("conditions","order","fields"));

        if (empty($flujos)) {
            return false;
        }

        $this->loadModel("Informe");
        $dataInforme = ["Informe" => [ "type" => "flujos_sin_gestionar","datos" => json_encode($flujos), "total" => count($flujos), "fecha" => date("Y-m-d") ] ];

        $this->Informe->create();
        $this->Informe->save($dataInforme);

        return true;

        $emails = array("gerencia@almacendelpintor.com","contabilidad@almacendelpintor.com");

        $options = array(
            'to'        => $emails,
            'template'  => 'report_no_continue',
            'subject'   => 'Reporte de flujos sin gestionar en estado PAGADO - KEBCO AlmacenDelPintor.com',
            'vars'      => compact("flujos")
        );


        $this->sendMail($options, true);
    }

    public function add_prospective(){
    	$this->layout		= false;

        $validateContact    = $this->validateTimes(true);
        // $flowsNoShipping    = $this->validateShipping(true);
        $valid              = true;

        if ((!empty($validateContact["contact"])) || !empty($validateContact["quotation"]) ) {
            echo "Tienes flujos represados en asignado y contactado";
            die();
        }else if(!empty($flowsNoShipping)){
            echo 'Tienes pendientes '.count($flowsNoShipping). ' solicitudes de facturación y/o despacho por gestionar pertenecientes a flujos';
            die();
        }

        //   	$clientsLegals 		= $this->getClientsLegals();
		// $clientsNaturals	= $this->getClientsNaturals();
		$usuarios 			= $this->ProspectiveUser->User->role_asesor_comercial_user_true(true);
		$this->set(compact("clientsLegals","clientsNaturals",'usuarios'));
    }

    public function add_prospective_chat($email,$conversation_id){
        $this->layout       = false;
        $email              = base64_decode($email);

        $user_id            = $this->ProspectiveUser->User->field("id",["email" => $email ]);

        // $validateContact    = $this->validateTimes(true);
        // $flowsNoShipping    = $this->validateShipping(true);

        if ($this->request->is(["post","put"])) {
            $this->autoRender = false;
            $datosClientePost = json_decode($this->request->data["ProspectiveUser"]["client_data"]);

            $emailUser        = isset($datosClientePost->details->response_crm->email) ? $datosClientePost->details->response_crm->email : null;
            $phoneUser        = isset($datosClientePost->extra->phone->value) ? $datosClientePost->extra->phone->value : null;
            $nameUser         = isset($datosClientePost->details->response_crm->name) ? $datosClientePost->details->response_crm->name : $datosClientePost->details->first_name;
            $cityUser         = isset($datosClientePost->details->response_crm->city) ? $datosClientePost->details->response_crm->city : 'Medellin';

            $conversation_id  = isset($datosClientePost->conversation) ? $datosClientePost->conversation : 1;

            $client_information = $this->getOrCreateClient(["A"=>$nameUser,"B"=>$emailUser, "C" => $phoneUser, "D" => $cityUser ]);
            $asesores           = [5,11,7,3,2];

            $config     = array(
                "Config" => array(
                    "reason" => strip_tags($this->request->data["ProspectiveUser"]["reason"]." | ".$this->request->data["ProspectiveUser"]["description"]),
                    "page" => $this->request->referer(),
                    "country" => isset($this->request->data["ProspectiveUser"]["country"]) ? $this->request->data["ProspectiveUser"]["country"] : "Colombia",
                    "origin" => isset($this->request->data["ProspectiveUser"]["origin"]) ? $this->request->data["ProspectiveUser"]["origin"] : "Chat",
                    "status" => $conversation_id
                )
            );
            try {
                $flujoId            = $this->createProspective($client_information,$config, $this->request->data["ProspectiveUser"]["user_id"]);
                return $flujoId;
            } catch (Exception $e) {
                return 0;
            }
        }

        $usuarios           = $this->ProspectiveUser->User->role_asesor_comercial_user_true(true);
        $this->set(compact("clientsLegals","clientsNaturals",'usuarios','user_id'));
    }

    public function duplicate($flujoId, $quotation, $flow){
        $this->layout = false;
        if (AuthComponent::user("role") == "Asesor Externo") {
            $usuarios       = [AuthComponent::user("id") => AuthComponent::user("name")];
        }else{
            $usuarios           = $this->ProspectiveUser->User->role_asesor_comercial_user_true();
        }

        $this->ProspectiveUser->recursive = -1;
        $datosProspecto = $this->ProspectiveUser->findById($flujoId);

        if ($this->request->is("post")) {

            $datos                                                      = $this->request->data;


            $datos['ProspectiveUser']['user_receptor']                  = AuthComponent::user('id');

            $origen                                                     = $this->request->data["ProspectiveUser"]['origin'];
            if ($this->request->data["ProspectiveUser"]['flujo_no_valido'] == 1) {
                $datos['ProspectiveUser']['state_flow']                 = Configure::read('variables.control_flujo.flujo_no_valido');               
            } else {
                $datos['ProspectiveUser']['state_flow']                 = Configure::read('variables.control_flujo.flujo_asignado');
            }
            $description                                                = $this->request->data['ProspectiveUser']['description'];
            $reason                                                     = $this->request->data['ProspectiveUser']['reason'];

            if(strpos($this->request->data["ProspectiveUser"]["clients_natural_id"], "_LEGAL") === false){
                $datos["ProspectiveUser"]["clients_natural_id"] = str_replace("_NATURAL", "", $datos["ProspectiveUser"]["clients_natural_id"]);
                $type = "1";
            }else{
                $datos["ProspectiveUser"]["contacs_users_id"]       = $this->request->data["contac_id"];
                $datos["ProspectiveUser"]["clients_natural_id"]     = null;
                $type = "2";
            }

            $datos["ProspectiveUser"]["type"] = 0;

            if(!empty($datos["ProspectiveUser"]["image"]["name"])){
                $documento                                  = $this->loadPhoto($this->request->data['ProspectiveUser']['image'],'flujo/imagenes');
                $datos['ProspectiveUser']['image']          = $this->Session->read('imagenModelo');
            }elseif (!empty($datos["ProspectiveUser"]["image_paste"])) {
                $datos["ProspectiveUser"]["image_paste"]    = str_replace("data:image/png;base64,", "", $datos["ProspectiveUser"]["image_paste"]);
                $datos['ProspectiveUser']['image']          = $this->saveImage64($datos["ProspectiveUser"]["image_paste"],'flujo/imagenes');
            }else{
                $datos['ProspectiveUser']['image']          = null;
            }


            $this->ProspectiveUser->create();
            if ($this->ProspectiveUser->save($datos)) {
                $flujo_id                                               = $this->ProspectiveUser->id;
                $this->saveDataLogsUser(2,'ProspectiveUser',$flujo_id);
                if ($this->request->data["ProspectiveUser"]['flujo_no_valido'] == 0) {
                    $datosUsu                                   = $this->ProspectiveUser->User->get_data($datos['ProspectiveUser']['user_id']);
                    if($type == 1){
                        $datosContacto                              = $this->ProspectiveUser->ClientsNatural->get_data($datos["ProspectiveUser"]["clients_natural_id"]);
                        $this->saveStagesFlow($flujo_id,$description,$reason,Configure::read('variables.nombre_flujo.flujo_asignado'),$origen);
                        $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datos['ProspectiveUser']['user_id'],$flujo_id,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
                        $this->addProspectiveAtentionTime($flujo_id);
                        $this->saveAtentionTimeFlujoEtapasLimitTime($flujo_id,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                        $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$reason,$description,$datosContacto['ClientsNatural']['name'],$datosContacto['ClientsNatural']['telephone'],$datosContacto['ClientsNatural']['cell_phone'],$datosContacto['ClientsNatural']['email'],$datosContacto['ClientsNatural']['city'],'prospectiveUsers/adviser?q='.$flujo_id);  
                    }else{
                        $datosContacto                              = $this->ProspectiveUser->ContacsUser->get_data($datos["ProspectiveUser"]["contacs_users_id"]);
                        $this->saveStagesFlow($flujo_id,$description,$reason,Configure::read('variables.nombre_flujo.flujo_asignado'));
                        $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datos['ProspectiveUser']['user_id'],$flujo_id,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
                        $this->addProspectiveAtentionTime($flujo_id);
                        $this->saveAtentionTimeFlujoEtapasLimitTime($flujo_id,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
                        $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$reason,$description,$datosContacto['ContacsUser']['name'],$datosContacto['ContacsUser']['telephone'],$datosContacto['ContacsUser']['cell_phone'],$datosContacto['ContacsUser']['email'],$datosContacto['ContacsUser']['city'],'prospectiveUsers/adviser?q='.$flujo_id);    
                    }

                    $datosFlowstage = array(
                        "FlowStage" => array(
                            "contact" => $type == 1 ? $datos["ProspectiveUser"]["clients_natural_id"] : $datos["ProspectiveUser"]["contacs_users_id"] ,
                            "reason" => $datos["ProspectiveUser"]["reason"],
                            "origin" => $datos["ProspectiveUser"]["origin"],
                            "description" => $datos["ProspectiveUser"]["reason"],
                            "state_flow" => Configure::read('variables.nombre_flujo.flujo_contactado'),
                            "cotizacion" => "1",
                            "prospective_users_id" => $flujo_id
                        )
                    );

                    $this->ProspectiveUser->FlowStage->create();
                    $this->ProspectiveUser->FlowStage->save($datosFlowstage);
                     
                    $this->updateStateProspectiveFlow($this->ProspectiveUser->id,Configure::read('variables.control_flujo.flujo_contactado'));

                    $flowContactado = $this->ProspectiveUser->FlowStage->id;


                    $this->loadModel("Quotation");
                    $quotation = $this->Quotation->findById($quotation);
                    $etapa_id  = $flowContactado;

                    $datosB['DraftInformation']['name']                     = $quotation['Quotation']['name'];
                    $datosB['DraftInformation']['notes']                    = $quotation['Quotation']['notes'];
                    $datosB['DraftInformation']['notes_description']        = $quotation['Quotation']['notes_description'];
                    $datosB['DraftInformation']['conditions']               = $quotation['Quotation']['conditions'];
                    $datosB['DraftInformation']['header_id']                = $quotation['Quotation']['header_id'];
                    $datosB['DraftInformation']['customer_note']            = $quotation['Quotation']['customer_note'];
                    $datosB['DraftInformation']['prospective_users_id']     = $flujo_id;
                    $datosB['DraftInformation']['user_id']                  = $quotation["Quotation"]["user_id"];

                    $this->Quotation->FlowStage->FlowStagesProduct->DraftInformation->create();

                    if ($this->Quotation->FlowStage->FlowStagesProduct->DraftInformation->save($datosB)) {
                        if (count($quotation["QuotationsProduct"]) > 0) {
                            $idInformation          = $this->Quotation->FlowStage->FlowStagesProduct->DraftInformation->id;
                            $this->saveQuotationProductBorrador($quotation["QuotationsProduct"],$idInformation,$etapa_id,$flujo_id);
                            $this->saveDataLogsUser(8,'Quotation',0);
                            $this->redirect(["controller" => "quotations","action" => "add",$flujo_id,$flowContactado]);
                        }
                        
                    } 
                } else {
                    $this->saveStagesFlow($flujo_id,$description,$reason,Configure::read('variables.nombre_flujo.flujo_no_valido'),$origen);
                    return false;
                }
            }
            var_dump($datos);
            die;
        }

        $this->set(compact('usuarios','datosProspecto'));
    }


    public function saveQuotationProductBorrador($products,$id_inset,$etapa_id,$flujo_id){
        $this->loadModel("Quotation");
        $datosT              = array();
        $i = 0;
        foreach ($products as $value) {

            $datosT[$i]['FlowStagesProduct']['flow_stage_id']          = $etapa_id;
            $datosT[$i]['FlowStagesProduct']['prospective_users_id']   = $flujo_id;
            $datosT[$i]['FlowStagesProduct']['product_id']             = $value["product_id"];
            $datosT[$i]['FlowStagesProduct']['draft_information_id']   = $id_inset;
            
            $datosT[$i]['FlowStagesProduct']['price']                  = $value["price"];
            $datosT[$i]['FlowStagesProduct']['quantity']               = $value["quantity"];
            $datosT[$i]['FlowStagesProduct']['delivery']               = $value["delivery"];
            $i++;
        }
        $this->Quotation->FlowStage->FlowStagesProduct->create();
        $this->Quotation->FlowStage->FlowStagesProduct->saveAll($datosT);
        return true;
    }

    public function reporte_tecnico(){
        $ini        = date("Y-m-d");
        $end        = date("Y-m-d");
        $filter     = false;
        $total      = null;
        $users      = [];
        $totalByUser = [];

        $sales      = array();

        if($this->request->is("post")){
            $total = 0;
            $filter     = true;
            $ini        = $this->request->data["fechaIni"];
            $end        = $this->request->data["fechaEnd"];
            $sales      = $this->ProspectiveUser->getSalesListTecnical($ini, $end);
            $this->loadModel("Salesinvoice");

            foreach ($sales as $key => $value) {
                $this->Salesinvoice->recursive = -1;

                $users[$value["ProspectiveUser"]["bill_user"]] = $value["User"]["name"];
                $total+= $value["ProspectiveUser"]["bill_value"];

                if(!array_key_exists($value["ProspectiveUser"]["bill_user"], $totalByUser)){
                    $totalByUser[$value["ProspectiveUser"]["bill_user"]] = $value["ProspectiveUser"]["bill_value"];
                }else{
                    $totalByUser[$value["ProspectiveUser"]["bill_user"]] += $value["ProspectiveUser"]["bill_value"];
                }

                $sales[$key]["facturas"] = $this->Salesinvoice->find("all",["conditions"=>["Salesinvoice.prospective_user_id"=>$value["ProspectiveUser"]["id"], "Salesinvoice.bill_code !=" => $value["ProspectiveUser"]["bill_code"], "locked" => 0 ]]);

                if(!empty($sales[$key]["facturas"])){
                    foreach ($sales[$key]["facturas"] as $key => $valueInvo) {
                        $total+=$valueInvo["Salesinvoice"]["bill_value"];
                        $totalByUser[$value["ProspectiveUser"]["bill_user"]] += $valueInvo["Salesinvoice"]["bill_value"];
                    }
                }
            }

        }

        $this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
        $this->set("filter", $filter);
        $this->set("sales", $sales);
        $this->set("users", $users);
        $this->set("totalByUser", $totalByUser);
        $this->set("totalVentasServicio", $total);
    }

    public function add_flujo_post(){
    	$this->autoRender = false;

    	$datos 														= $this->request->data;
    	$datos['ProspectiveUser']['user_receptor']               	= AuthComponent::user('id');

    	$origen							                 			= $this->request->data["ProspectiveUser"]['origin'];
        if ($this->request->data["ProspectiveUser"]['flujo_no_valido'] == 1) {
        	$datos['ProspectiveUser']['state_flow'] 				= Configure::read('variables.control_flujo.flujo_no_valido');           	
        } else {
        	$datos['ProspectiveUser']['state_flow']             	= Configure::read('variables.control_flujo.flujo_asignado');
        }
        $description                                        		= $this->request->data['ProspectiveUser']['description'];
        $reason                                             		= $this->request->data['ProspectiveUser']['reason'];

        if(strpos($this->request->data["ProspectiveUser"]["clients_natural_id"], "_LEGAL") === false){
            $datos["ProspectiveUser"]["clients_natural_id"] = str_replace("_NATURAL", "", $datos["ProspectiveUser"]["clients_natural_id"]);
            $type = "1";
        }else{
            $datos["ProspectiveUser"]["contacs_users_id"]       = $this->request->data["contac_id"];
            $datos["ProspectiveUser"]["clients_natural_id"]     = null;
            $type = "2";
        }

        $datos["ProspectiveUser"]["type"] = 0;

        if(!empty($datos["ProspectiveUser"]["image"]["name"])){
            $documento                                  = $this->loadPhoto($this->request->data['ProspectiveUser']['image'],'flujo/imagenes');
            $datos['ProspectiveUser']['image']          = $this->Session->read('imagenModelo');
        }elseif (!empty($datos["ProspectiveUser"]["image_paste"])) {
            $datos["ProspectiveUser"]["image_paste"]    = str_replace("data:image/png;base64,", "", $datos["ProspectiveUser"]["image_paste"]);
            $datos['ProspectiveUser']['image']          = $this->saveImage64($datos["ProspectiveUser"]["image_paste"],'flujo/imagenes');
        }else{
            $datos['ProspectiveUser']['image']          = null;
        }

        $datos["ProspectiveUser"]["time_contact"]       = $this->calculateHoursGest( Configuration::get_flow("hours_contact") );

        $this->ProspectiveUser->create();
        if ($this->ProspectiveUser->save($datos)) {
            $flujo_id                                       		= $this->ProspectiveUser->id;
            $this->saveDataLogsUser(2,'ProspectiveUser',$flujo_id);
            if ($this->request->data["ProspectiveUser"]['flujo_no_valido'] == 0) {
            	$datosUsu 									= $this->ProspectiveUser->User->get_data($datos['ProspectiveUser']['user_id']);
            	if($type == 1){
            		$datosContacto 								= $this->ProspectiveUser->ClientsNatural->get_data($datos["ProspectiveUser"]["clients_natural_id"]);
	            	$this->saveStagesFlow($flujo_id,$description,$reason,Configure::read('variables.nombre_flujo.flujo_asignado'),$origen);
	            	$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datos['ProspectiveUser']['user_id'],$flujo_id,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
	            	$this->addProspectiveAtentionTime($flujo_id);
	                $this->saveAtentionTimeFlujoEtapasLimitTime($flujo_id,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
	                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$reason,$description,$datosContacto['ClientsNatural']['name'],$datosContacto['ClientsNatural']['telephone'],$datosContacto['ClientsNatural']['cell_phone'],$datosContacto['ClientsNatural']['email'],$datosContacto['ClientsNatural']['city'],'prospectiveUsers/adviser?q='.$flujo_id);	
            	}else{
            		$datosContacto 								= $this->ProspectiveUser->ContacsUser->get_data($datos["ProspectiveUser"]["contacs_users_id"]);
	            	$this->saveStagesFlow($flujo_id,$description,$reason,Configure::read('variables.nombre_flujo.flujo_asignado'));
	            	$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datos['ProspectiveUser']['user_id'],$flujo_id,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
	            	$this->addProspectiveAtentionTime($flujo_id);
	                $this->saveAtentionTimeFlujoEtapasLimitTime($flujo_id,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
	                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$reason,$description,$datosContacto['ContacsUser']['name'],$datosContacto['ContacsUser']['telephone'],$datosContacto['ContacsUser']['cell_phone'],$datosContacto['ContacsUser']['email'],$datosContacto['ContacsUser']['city'],'prospectiveUsers/adviser?q='.$flujo_id);	   
            	}
            	
                return $flujo_id;
            } else {
            	$this->saveStagesFlow($flujo_id,$description,$reason,Configure::read('variables.nombre_flujo.flujo_no_valido'),$origen);
            	return false;
            }
        }

    	var_dump($this->request->data);
    	die();
    }

    public function informe_diario_clientes(){
    	$this->autoRender = false;

    	$day = date("Y-m-d", strtotime("-1 day"));

    	$conditionsClientesNaturalesNuevos = array(
    		"ClientsNatural.created IS NOT NULL",
    		"ClientsNatural.created !=" => "0000-00-00",
    		"ClientsNatural.created =" => $day,
    		"DATE(ProspectiveUser.created) =" => $day,
    	);

    	$conditionsClientesNaturalesViejos = array(
    		"ClientsNatural.created IS NOT NULL",
    		"ClientsNatural.created !=" => "0000-00-00",
    		"ClientsNatural.created !=" => $day,
    		"DATE(ProspectiveUser.created) =" => $day,
    	);

    	$conditionsClientesLegalesNuevos = array(
    		"ClientsLegal.created IS NOT NULL",
    		"ClientsLegal.created !=" => "0000-00-00",
    		"ClientsLegal.created =" => $day,
    		"DATE(ProspectiveUser.created) =" => $day,
    	);

    	$conditionsClientesLegalesViejos = array(
    		"ClientsLegal.created IS NOT NULL",
    		"ClientsLegal.created !=" => "0000-00-00",
    		"ClientsLegal.created !=" => $day,
    		"ContacsUser.created =" => $day,
    		"DATE(ProspectiveUser.created) =" => $day,
    	);

    	$joins = array(
	        array(
	            'table' => 'clients_legals',
	            'alias' => 'ClientsLegal',
	            'type' => 'INNER',
	            'conditions' => array(
	                'ContacsUser.clients_legals_id = ClientsLegal.id'
	            )
	        )
		);

    	$this->ProspectiveUser->unBindModel(array(
    		"belongsTo" => array( "Adress", "Import" ),
    		"hasAndBelongsToMany" => array("Imports"),
    		"hasMany" => array("FlowStage","FlowStagesProduct","AtentionTime","Payment","ProgresNote","Receipt","TechnicalService")
    	));

    	$clientesNaturalesNuevosConFlujo = $this->ProspectiveUser->find("all",array("fields"=>array("ProspectiveUser.*","ClientsNatural.name","User.name"),"conditions" => $conditionsClientesNaturalesNuevos));

    	
    	$this->ProspectiveUser->unBindModel(array(
    		"belongsTo" => array( "Adress", "Import" ),
    		"hasAndBelongsToMany" => array("Imports"),
    		"hasMany" => array("FlowStage","FlowStagesProduct","AtentionTime","Payment","ProgresNote","Receipt","TechnicalService")
    	));

    	$clientesNaturalesViejosConFlujo = $this->ProspectiveUser->find("all",array("fields"=>array("ProspectiveUser.*","ClientsNatural.name","User.name"),"conditions" => $conditionsClientesNaturalesViejos));

    	$this->ProspectiveUser->unBindModel(array(
    		"belongsTo" => array( "Adress", "Import" ),
    		"hasAndBelongsToMany" => array("Imports"),
    		"hasMany" => array("FlowStage","FlowStagesProduct","AtentionTime","Payment","ProgresNote","Receipt","TechnicalService")
    	));

    	$clientesLegalesNuevosConFlujo = $this->ProspectiveUser->find("all",array("fields"=>array("ProspectiveUser.*","ContacsUser.name","User.name","ClientsLegal.name"),"conditions" => $conditionsClientesLegalesNuevos, "joins" => $joins));

    	$this->ProspectiveUser->unBindModel(array(
    		"belongsTo" => array( "Adress", "Import" ),
    		"hasAndBelongsToMany" => array("Imports"),
    		"hasMany" => array("FlowStage","FlowStagesProduct","AtentionTime","Payment","ProgresNote","Receipt","TechnicalService")
    	));

    	$clientesLegalesViejosConFlujo = $this->ProspectiveUser->find("all",array("fields"=>array("ProspectiveUser.*","ContacsUser.name","User.name","ClientsLegal.name"),"conditions" => $conditionsClientesLegalesViejos, "joins" => $joins));

      //   	$emails = array("gerencia@almacendelpintor.com");

		// $options = array(
		// 	'to'		=> $emails,
		// 	'template'	=> 'report_customers',
		// 	'subject'	=> 'Reporte de clientes con flujos nuevos ' .$day. ' KEBCO AlmacenDelPintor.com',
		// 	'vars'		=> compact("day", "clientesNaturalesNuevosConFlujo", "clientesNaturalesViejosConFlujo", "clientesLegalesNuevosConFlujo", "clientesLegalesViejosConFlujo")
		// );


		// $this->sendMail($options, true);


        $totalData = count($clientesNaturalesNuevosConFlujo)+count($clientesNaturalesViejosConFlujo)+count($clientesLegalesNuevosConFlujo)+count($clientesLegalesViejosConFlujo);

        if ($totalData == 0) {
            return false;
        }

        $this->loadModel("Informe");
        $dataInforme = ["Informe" => [ "type" => "informe_diario_clientes","datos" => json_encode(compact("day", "clientesNaturalesNuevosConFlujo", "clientesNaturalesViejosConFlujo", "clientesLegalesNuevosConFlujo", "clientesLegalesViejosConFlujo")), "total" => $totalData, "fecha" => date("Y-m-d") ] ];

        $this->Informe->create();
        $this->Informe->save($dataInforme);

    	var_dump($clientesNaturalesNuevosConFlujo);
    	die();

    }

    public function informe_ventas(){
    	$usuarios 	= $this->ProspectiveUser->User->role_asesor_comercial_user_true();
    	$filter 	= false;
    	$ini 		= date("Y-m-d");
    	$end 		= date("Y-m-d");
    	$sales 		= array();

    	if($this->request->is("post")){
    		$filter 	= true;
    		$ini  		= $this->request->data["fechaIni"];
    		$end 		= $this->request->data["fechaEnd"];
    		$user_id 	= $this->request->data["ProspectiveUser"]["user"];
    		$sales  	= $this->ProspectiveUser->getSalesList($ini, $end, $user_id);
            $this->loadModel("Salesinvoice");

            foreach ($sales as $key => $value) {
                $this->Salesinvoice->recursive = -1;
                $sales[$key]["facturas"] = $this->Salesinvoice->findAllByProspectiveUserId($value["ProspectiveUser"]["id"]);
            }
            $salesinvoiceData = $this->request->data["ProspectiveUser"];
    	}


    	$this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
		$this->set(compact('usuarios','sales','filter'));
    }

    public function informe_ventas_externos() {
        $usuarios   = $this->ProspectiveUser->User->find("list",["conditions" => ["User.role" => "Asesor Externo" ] ]);
        $filter     = false;
        $ini        = date("Y-m-d");
        $end        = date("Y-m-d");
        $sales      = array();

        if($this->request->is("post")){
            $filter     = true;
            $ini        = $this->request->data["fechaIni"];
            $end        = $this->request->data["fechaEnd"];
            $user_id    = $this->request->data["ProspectiveUser"]["user"];
            $sales      = $this->ProspectiveUser->getSalesList($ini, $end, $user_id);

            $this->loadModel("Salesinvoice");

            foreach ($sales as $key => $value) {
                $this->Salesinvoice->recursive = -1;
                $sales[$key]["facturas"] = $this->Salesinvoice->findAllByProspectiveUserId($value["ProspectiveUser"]["id"]);
            }

            $salesinvoiceData = $this->request->data["ProspectiveUser"];
        }


        $this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
        $this->set(compact('usuarios','sales','filter'));
    }

    public function informe_ventas_tienda(){
        $filter     = false;
        $ini        = date("Y-m-d");
        $end        = date("Y-m-d");
        $sales      = array();
        $total      = 0;

        if($this->request->is("post")){
            $filter     = true;
            $ini        = $this->request->data["fechaIni"];
            $end        = $this->request->data["fechaEnd"];
            $sales      = $this->ProspectiveUser->getSalesList($ini, $end, null, true);
            $this->loadModel("Salesinvoice");

            foreach ($sales as $key => $value) {
                $this->Salesinvoice->recursive = -1;
                $total+= $value["ProspectiveUser"]["bill_value"];
                $sales[$key]["facturas"] = $this->Salesinvoice->findAllByProspectiveUserId($value["ProspectiveUser"]["id"]);
                foreach ($sales[$key]["facturas"] as $keyFact => $valueFact) {
                    $total+= $valueFact["Salesinvoice"]["bill_value"];
                }
            }
        }


        $this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
        $this->set(compact('sales','filter','total'));
    }

    public function informe_comisiones_externas() {
        $this->loadModel("Commision");
        $usuarios           = $this->ProspectiveUser->User->find("list");
        $usersidsValids     = $this->Commision->find("all",array("conditions" => ["User.role" => "Asesor Externo" ],"fields"=> array("user_id"), "recursive" => 1));
        $userIds            = Set::extract($usersidsValids, "{n}.Commision.user_id");
        $comision           = null;

        if(!empty($userIds)  && !is_null($userIds)){

            foreach ($usuarios as $key => $value) {
                if(!in_array($key, $userIds)){
                    unset($usuarios[$key]);
                }
            }
        }else{
            $usuarios = array();
        }

        $filter     = false;
        $ini        = date("Y-m-d");
        $end        = date("Y-m-d");
        $sales      = array();

        if($this->request->is("post")){
            $ini        = $this->request->data["fechaIni"];
            $end        = $this->request->data["fechaEnd"];
            $user_id    = $this->request->data["ProspectiveUser"]["user"];
            $sales      = $this->ProspectiveUser->getSalesListCodes($ini, $end, $user_id);
            $sales      = $this->calculateUtilidadForBill($sales);

            $comision   = $this->Commision->findByUserId($user_id);
            $filter     = true;
            if($this->request->data["ProspectiveUser"]["excel"] == 1){
                $this->export_comissions($sales,$comision,$ini,$end);
            }
        }

        $this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
        $this->set(compact('usuarios','sales','filter','userIds','comision'));
    }

    public function comisiones(){
        $this->layout = false;
        $ini        = date("Y-m-d");
        $end        = date("Y-m-d");
        $sales      = array();
        $porcentajes_comisiones      = array();
        $efetivities_comisiones      = array();
        $meta       = 0;
        $metaVentas = 0;
        $totalVentas = 0;
        $saldos             = 0;
        $datosComision      = 0;
        $totalMargen        = 0;
        $dataEfectivity = null;
        $percentajeDb   = null;
        $costoFacturas  = 0;

        if($this->request->is("post")){
            $ini        = $this->request->data["fechaIni"];
            $end        = $this->request->data["fechaEnd"];
            $user_id    = $this->request->data["user"];


            $ventas = $this->postWoApi(["ini" => $ini, "end" => $end, "vendedor" => $comision["User"]["identification"]  ],"documents");
            if (isset($ventas->listado)) {
                foreach ($ventas->listado as $key => $value) {
                    $totalVentas += intval($value->Total_Venta - intval($value->Total_Descuentos));
                    $costoFacturas = $value->Total_Descuentos > 0 ? $costoFacturas-$value->CostoFactura : $costoFacturas+$value->CostoFactura;
                }
            }

            $totalUtilidadVentas = $totalVentas-$costoFacturas;
            $totalMargen         = $totalUtilidadVentas/$totalVentas * 100;

            $sales      = $this->ProspectiveUser->getSalesListCodes($ini, $end, $user_id,false);
            $sales      = $this->calculateUtilidadForBill($sales, $user_id,$totalMargen);
            $this->loadModel("Commision");
            $comision   = $this->Commision->findByUserId($user_id);
            $filter     = true;

            $saldos     = Set::extract($sales,"{n}.Receipt.total");
            $saldos     = array_sum($saldos);

            $this->loadModel("CollectionGoal");
            $this->loadModel("Goal");
            $this->loadModel("Percentage");
            $this->loadModel("Effectivity");

            $monthIni = date("m",strtotime($ini));
            $monthEnd = date("m",strtotime($end));

            if ($monthIni == $monthEnd) {
                $meta+= $this->CollectionGoal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
            }else{
                $meta+= $this->CollectionGoal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $meta+= $this->CollectionGoal->field($monthEnd,["user_id"=>$user_id, "year" => date("Y",strtotime($end)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($end)) ]);
            }

            $porcentajes_comisiones = $this->Percentage->findAllByUserId($user_id);

            $efetivities_comisiones = $this->Effectivity->find("all",["conditions"=>["Effectivity.user_id" => $user_id], "recursive" => -1]);

            $dataEfectivity = $this->getEfetivityByUser($ini,$end,$user_id);

            $this->loadModel("Commision");
            $this->Commision->recursive = -1;
            $datosComision = $this->Commision->findByUserId($user_id);

            if (!is_null($dataEfectivity) && isset($dataEfectivity["efectividad"])) {
            $this->loadModel("Effectivity");
                $percentajeDb = $this->Effectivity->find("first",["conditions"=>["Effectivity.min <=" => $dataEfectivity["efectividad"], "Effectivity.max >=" => $dataEfectivity["efectividad"],"Effectivity.user_id" => $comision["User"]["id"] ], "recursive" => -1]);
            }
            


        }
        $this->set(compact('usuarios','sales','filter','userIds','comision','dataEfectivity','meta','metaVentas','percentajeDb','totalVentas','saldos','porcentajes_comisiones','efetivities_comisiones','datosComision','totalMargen'));
    }

    public function get_data_services() {
        $this->autoRender = false;
        $end = $this->request->query["end"];

        $this->loadModel("TechnicalService");
        $servicios        = $this->TechnicalService->find("all",[ "fields" => ["TechnicalService.*","ProspectiveUser.*","DATEDIFF(NOW(),TechnicalService.date_end) dias","User.*"] ,"conditions" => ["DATE(TechnicalService.created) <= "=>$end,'TechnicalService.state'=> 1, "ProspectiveUser.state_flow" => [1,2,3,4,5], "DATEDIFF(NOW(),TechnicalService.date_end) > 90" ] ]);

        $data = [];

        if (!empty($servicios)) {
            foreach ($servicios as $key => $value) {
                $data[] = [ $value["User"]["name"],$value['TechnicalService']['code'], $this->name_prospective_contact($value["ProspectiveUser"]),$value['TechnicalService']['created'],$value['TechnicalService']['date_end'], $value["ProspectiveUser"]["id"], $this->finde_state_flujo($value["ProspectiveUser"]["state_flow"])  ];
            }
        }

        return json_encode(["data" => $data]);
    }

    public function getMargenGeneral($dataFactura){
        $info = $this->getValueFactInfo($dataFactura);
        return $info;
    }

    public function informe_director() {
        $filter      = false;
        $ini         = date("Y-m-d");
        $end         = date("Y-m-d");
        
        $baseVentas     = 0;
        $baseCartera    = 0;
        $baseServicio   = 0;
        $baseMargen     = 0;
        $baseNuevos     = 0;
        $baseContactado = 0;
        $baseCotizado   = 0;
        
        $totalXventas       = 0;
        $totalXcartera      = 0;
        $totalXservicio     = 0;
        $totalXmargen       = 0;
        $totalXnuevos       = 0;
        $totalXContactado   = 0;
        $totalXCotizado     = 0;

        $pocertajeVenta        = 0;
        $pocertajeCartera      = 0;
        $pocertajeServicio     = 0;
        $porcentajeMargen      = 0;
        $porcentajeNuevos      = 0;
        $porcentajeContactado  = 0;
        $porcentajeCotizado    = 0;

        $total_ventas_empresa = 0;
        $total_cartera        = 0;
        $total_servicios      = 0;
        $total_margen         = 0;
        $total_nuevos         = 0;
        $total_contactado     = 0;
        $total_cotizado       = 0;

        $facturas             = [];
        $carteras             = [];
        $servicios            = [];
        $nuevos               = [];
        $postConsulta         = false;
        $muestraInfo          = true;

        $margenes             = [];
        $totalMargenPromedio  = 0;

        if($this->request->is("post")){
            $ini        = $this->request->data["fechaIni"];
            $end        = $this->request->data["fechaEnd"];
            $muestraInfo = $this->request->data["ProspectiveUser"]["excel"] == 1 ? true : false;

            $this->loadModel("TechnicalService");

            $servicios        = $this->TechnicalService->find("count",[ "fields" => ["TechnicalService.*","ProspectiveUser.*","DATEDIFF(NOW(),TechnicalService.date_end) dias","User.*"] ,"conditions" => ["DATE(TechnicalService.created) <= "=>$end,'TechnicalService.state'=> 1, "ProspectiveUser.state_flow" => [1,2,3,4,5], "DATEDIFF(NOW(),TechnicalService.date_end) > 90" ] ]);

            $total_servicios = ($servicios);

            $totalMes   = $this->postWoApi(["ini" => $ini, "end" => $end, "details" => 1 ],"documents");


            $carteraMes = $this->postWoApi(["end" => $end ],"debtsdirector");
            

            $datosCompara         = Configure::read("DATOS_DIRECTOR");

            $baseVentas           = $datosCompara["VENTAS"]["BASE"];
            $baseCartera          = $datosCompara["CARTERA"]["BASE"];
            $baseServicio         = $datosCompara["SERVICIO"]["BASE"];
            $baseMargen           = $datosCompara["MARGEN"]["BASE"];
            $baseNuevos           = $datosCompara["NUEVOS"]["BASE"];
            $baseContactado       = $datosCompara["CONTACTADO"]["BASE"];
            $baseCotizado        = $datosCompara["COTIZADO"]["BASE"];

            if (!empty($totalMes) && isset($totalMes->valores)) {
                foreach ($totalMes->listado as $key => $value) {
                    $total_ventas_empresa += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);  
                    if ($value->Valores->datos_factura->prefijo != "DMC") {
                        $valorMargen = $this->getMargenGeneral($value->Valores);
                        $margenes[] = $valorMargen["utilidad_porcentual"];     

                        if (!empty($valorMargen["productosNuevos"])) {
                            foreach ($valorMargen["productosNuevos"] as $key => $value) {
                                $total_nuevos+= $value["precio"];
                                $nuevos[] = $value;
                            }
                        }

                    }else{
                        $margenes[] = 0;
                    }

                    $totalMes->listado[$key]->Margen = $valorMargen["utilidad_porcentual"];
                }
                $facturas = $this->object_to_array($totalMes->listado);
            }

            $totalMargenPromedio = array_sum($margenes) / count($margenes);
            $total_margen        = round($totalMargenPromedio,2);

            foreach ($datosCompara["NUEVOS"]["RANGOS"] as $key => $value) {
                if ($value["min"] <= $total_nuevos && $value["max"] >= $total_nuevos ) {
                    $totalXnuevos       = $baseNuevos * ($value["percent"]/100);
                    $porcentajeNuevos   = $value["percent"];
                    break;
                }
            }

            foreach ($datosCompara["MARGEN"]["RANGOS"] as $key => $value) {
                if ($value["min"] <= $totalMargenPromedio && $value["max"] >= $totalMargenPromedio ) {
                    $totalXmargen       = $baseMargen * ($value["percent"]/100);
                    $porcentajeMargen   = $value["percent"];
                    break;
                }
            }


            if (!empty($carteraMes)) {
                $carteras = $this->object_to_array($carteraMes);
                foreach ($carteraMes as $key => $value) {
                    $total_cartera += floatval(is_null($value->Saldo) < 0 ? 0 : $value->Saldo );  
                }
            }

            foreach ($datosCompara["VENTAS"]["RANGOS"] as $key => $value) {
                if ($value["min"] <= $total_ventas_empresa && $value["max"] >= $total_ventas_empresa ) {
                    $totalXventas   = $baseVentas * ($value["percent"]/100);
                    $pocertajeVenta = $value["percent"];
                    break;
                }
            }

            foreach ($datosCompara["CARTERA"]["RANGOS"] as $key => $value) {
                if ($value["min"] <= $total_cartera && $value["max"] >= $total_cartera ) {
                    $totalXcartera    = $baseCartera * ($value["percent"]/100);
                    $pocertajeCartera = $value["percent"];
                    break;
                }
            }

            foreach ($datosCompara["SERVICIO"]["RANGOS"] as $key => $value) {
                if ($value["min"] <= $total_servicios && $value["max"] >= $total_servicios ) {
                    $totalXservicio      = $baseServicio * ($value["percent"]/100);
                    $pocertajeServicio   = $value["percent"];
                    break;
                }
            }

            $postConsulta = true;


            $conditionsContacado     = $this->filterUser(2,$conditions1 = array());
            $conditionsContacado["DATE(ProspectiveUser.created) <="] = $end;

            $totalContactado         = $this->ProspectiveUser->find("count",["conditions" => $conditionsContacado]);
            $total_contactado        = $totalContactado;

            foreach ($datosCompara["CONTACTADO"]["RANGOS"] as $key => $value) {
                if ($value["min"] <= $totalContactado && $value["max"] >= $totalContactado ) {
                    $totalXContactado      = $baseContactado * ($value["percent"]/100);
                    $porcentajeContactado   = $value["percent"];
                    break;
                }
            }

            $conditionsContizado     = $this->filterUser(3,$conditions1 = array());
            $conditionsContizado["DATE(ProspectiveUser.created) <="] = $end;
            $conditionsContizado["DATE(ProspectiveUser.created) <="] = date("Y-m-d", strtotime($end." -90 day"));


            $totalCotizado         = $this->ProspectiveUser->find("count",["conditions" => $conditionsContizado]);
            $total_cotizado        = $totalCotizado;

            foreach ($datosCompara["COTIZADO"]["RANGOS"] as $key => $value) {
                if ($value["min"] <= $totalCotizado && $value["max"] >= $totalCotizado ) {
                    $totalXContizado      = $baseContactado * ($value["percent"]/100);
                    $porcentajeCotizado   = $value["percent"];
                    break;
                }
            }

            // var_dump($totalContactado);
            // die;


            // var_dump($totalXventas);
            // var_dump($totalXcartera);
            // var_dump($baseVentas);
            // var_dump($baseCartera);

        }


        $this->set("fechaFinReporte", $end);
        $this->set("fechaInicioReporte", $ini);
        $this->set("muestraInfo", $muestraInfo);
        $this->set(compact('usuarios','sales','filter','userIds','comision','state','totalXventas','totalXcartera','baseVentas','baseCartera','pocertajeCartera','pocertajeVenta','total_ventas_empresa','total_cartera','total_servicios','totalXservicio','pocertajeServicio','total_servicios','baseServicio','servicios','facturas','carteras','postConsulta','baseMargen','totalXmargen','porcentajeMargen','total_margen','porcentajeNuevos','totalXnuevos','total_nuevos','nuevos','baseNuevos'));
        $this->set(compact('totalXContactado','porcentajeContactado','total_contactado','baseContactado'));
        $this->set(compact('totalXCotizado','porcentajeCotizado','total_cotizado','baseCotizado'));
    }

    public function view_liquidation($liquidation_id) {
        $liquidation_id = $this->desencriptarCadena($liquidation_id);
        $this->loadModel('Liquidation');
        $this->loadModel('Commision');

        $liquidation = $this->Liquidation->findById($liquidation_id);


        $filter     = false;
        $ini        = $liquidation["Liquidation"]["date_ini"];
        $end        = $liquidation["Liquidation"]["date_end"];
        $state      = "0";
        $sales      = array();
        $saldos     = [];
        $meta       = 0;
        $metaVentas = 0;

        $totalPagar      = 0;
        $totalPagarFinal = 0;
        $percentajeDb    = null;
        $totalVentas     = 0;
        $totalServicios  = 0;
        $pagarByPercent  = 0;
        $totalAdicionalFinal  = 0;
        $totalFinalMargen  = 0;
        $totalFinalEfectivity  = 0;
        $totalExtra  = 0;
        $totalAdescontar = 0;

        $dataEfectivity = [];

        $ventas;


            $receipts_ids = Set::extract($liquidation["Receipt"],"{n}.id");


            $user_id    = $liquidation["Liquidation"]["user_id"];
            $state      = 1;
            $sales      = $this->ProspectiveUser->getSalesListCodesAllInformation($ini, $end, $user_id,true,$state);
            $sales      = $this->calculateUtilidadForBill($sales,$user_id);
            $comision   = $this->Commision->findByUserId($user_id);
            $filter     = true;
            $saldos     = Set::extract($sales,"{n}.Receipt.total");
            $saldos     = array_sum($saldos);

            $this->loadModel("CollectionGoal");
            $this->loadModel("Goal");

            $monthIni = date("m",strtotime($ini));
            $monthEnd = date("m",strtotime($end));

            if ($monthIni == $monthEnd) {
                $meta+= $this->CollectionGoal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
            }else{
                $meta+= $this->CollectionGoal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $meta+= $this->CollectionGoal->field($monthEnd,["user_id"=>$user_id, "year" => date("Y",strtotime($end)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($end)) ]);
            }

            $dataEfectivity = $this->getEfetivityByUser($ini,$end,$user_id);

            if (!is_null($dataEfectivity) && isset($dataEfectivity["efectividad"])) {
                $this->loadModel("Effectivity");
                $percentajeDb = $this->Effectivity->find("first",["conditions"=>["Effectivity.min <=" => $dataEfectivity["efectividad"], "Effectivity.max >=" => $dataEfectivity["efectividad"],"Effectivity.user_id" => $comision["User"]["id"] ], "recursive" => -1]);
            }

            if (!empty($sales)) {
                foreach ($sales as $key => $value) {

                    if(!in_array($value["Receipt"]["id"], $receipts_ids)){
                        unset($sales[$key]);
                        continue;
                    }

                    $dias           =       $this->calculateDays( $this->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date") ,$value["Receipt"]["date_receipt"]);
                    $percentaje     =       !is_null($value['Receipt']['percent_value']) ? $value['Receipt']['percent_value'] :  $this->getComissionPercentaje($dias,$comision);

                    if ($value["Valores"]["totalProductos"] == 1 && $value["Valores"]["totalSt"] > 0) {
                        $value["Receipt"]["total_iva"] = 0;
                    }

                    $multiplica     =       ( !is_null($value["Salesinvoice"]["bill_date"]) && $value["Salesinvoice"]["bill_date"] >= '2024-08-14' && $value["Salesinvoice"]["bill_date"] <= '2024-08-31' ) ||                    ( $value["ProspectiveUser"]["bill_date"] >= '2024-08-14' && $value["ProspectiveUser"]["bill_date"] <= '2024-08-31' ) ? 2 : 1;

                    $pagar          =       ($percentaje / 100) * floatval($value["Receipt"]["total_iva"] - $value["Valores"]["valorSt"]); 

                    if($multiplica == 2){
                        $pagar *=   2;
                    }
                    

                    if($pagar < 0){ $pagar = 0; }

                    $totalPagar     +=      $pagar;
                    $totalAdescontar = floatval($value["ProspectiveUser"]["discount_datafono"]);
                    

                    // $percentUtility = $this->calculatePercent($value["Valores"]["utilidad_porcentual"], $comision["Commision"]["user_id"]);
                    $percentUtility = !is_null($value["ProspectiveUser"]["comision"]) && !empty($value["ProspectiveUser"]["comision"]) ? $value["ProspectiveUser"]["comision"] : $this->calculatePercent($sales[$key]["Valores"]["utilidad_porcentual"],$comision["Commision"]["user_id"]);

                    $pagarByPercent = $pagar * ($percentUtility / 100);
                    $totalPagarFinal += $pagarByPercent;

                    $sales[$key]["Finals"]["dias"] = $dias;
                    $sales[$key]["Finals"]["percentaje"] = $percentaje;
                    $sales[$key]["Finals"]["pagar"] = $pagar;
                    $sales[$key]["Finals"]["percentUtility"] = $percentUtility;
                    $sales[$key]["Finals"]["pagarByPercent"] = $pagarByPercent;

                    $nota = "";
                    if ($value["Valores"]["totalSt"] > 0) {
                        $nota = $value["Valores"]["totalSt"]." servicio(s) técnico(s), para un total de: ".number_format($value["Valores"]["valorBySt"]);
                    }

                    $sales[$key]["Finals"]["nota"] = $nota;

                    $totalServicios+=$value["Valores"]["valorBySt"];

                    $receipts_ids[] = $value["Receipt"]["id"];

                }


                $totalFinalMargen = intval($totalPagarFinal+$totalAdicionalFinal);
                $totalEfectivity = $totalFinalMargen;

                $totalEfectivity = $totalFinalMargen;
                if (!is_null($percentajeDb) && !empty($percentajeDb) ) {
                    $totalEfectivity = $percentajeDb["Effectivity"]["value"] == 0 ? 0 : $totalFinalMargen * ($percentajeDb["Effectivity"]["value"] / 100 ); 
                    $totalFinalEfectivity = $totalEfectivity; 
                }

                if($metaVentas > 0){
                    $ventas = $this->postWoApi(["ini" => $ini, "end" => $end, "vendedor" => $comision["User"]["identification"]  ],"documents");
                

                    if (isset($ventas->listado)) {
                        foreach ($ventas->listado as $key => $value) {
                            $totalVentas += intval($value->Total_Venta - intval($value->Total_Descuentos));
                        }

                        $totalExtra = $totalVentas >= $metaVentas ? $totalPagar*0.3 : 0;
                        if ($totalVentas >= $metaVentas) {
                            $totalEfectivity += $totalExtra;
                        }

                    }
                }

                         

            }




        $this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
        $this->set(compact('usuarios','sales','filter','userIds','comision','state','saldos','meta','dataEfectivity','metaVentas','totalVentas','totalExtra','totalEfectivity','totalServicios','totalPagar','totalPagarFinal','percentajeDb','totalFinalMargen','totalFinalEfectivity','liquidation','totalAdescontar'));

    }

    public function liquidation(){

        $facturas_bloqueadas = $this->facturas_bloqueadas(true);

        // if(!empty($facturas_bloqueadas)){
        //     $this->Session->setFlash('Debes revisar las facturas bloqueadas antes de liquidar comisiones', 'Flash/error');
        //     return $this->redirect(array('action' => 'facturas_bloqueadas'));
        // }

        $this->loadModel("Commision");
        $this->loadModel("CommisionsLocal");
        $usuarios           = $this->ProspectiveUser->User->find("list");
        $usersidsValids     = $this->Commision->find("all",[ "conditions" => ["User.role !=" => "Asesor Externo" ], "fields"=> array("user_id"), "recursive" => 1 ]);
        $userIds            = Set::extract($usersidsValids, "{n}.Commision.user_id");
        $comision           = null;
        if(!empty($userIds)  && !is_null($userIds)){

            foreach ($usuarios as $key => $value) {
                if(!in_array($key, $userIds)){
                    unset($usuarios[$key]);
                }
            }
        }else{
            $usuarios = array();
        }

        $filter     = false;
        $ini        = date("Y-m-d");
        $end        = date("Y-m-d");
        $state      = "0";
        $sales      = array();
        $saldos     = [];
        $meta       = 0;
        $metaVentas = 0;

        $totalPagar      = 0;
        $totalPagarFinal = 0;
        $percentajeDb    = null;
        $totalVentas     = 0;
        $totalServicios  = 0;
        $pagarByPercent  = 0;
        $totalAdicionalFinal  = 0;
        $totalFinalMargen  = 0;
        $totalFinalEfectivity  = 0;
        $totalExtra  = 0;
        $totalAdescontar = 0;

        $dataEfectivity = [];

        $demora = ["dias"=>0,"minutos"=>0,"horas"=>0];

        $costoFacturas  = 0;

        $ventas;

        if($this->request->is("post")){

            $receipts_ids = [];

            $ini        = $this->request->data["fechaIni"];
            $end        = $this->request->data["fechaEnd"];
            $user_id    = $this->request->data["ProspectiveUser"]["user"];
            $state      = $this->request->data["ProspectiveUser"]["state"];
            $sales      = $this->ProspectiveUser->getSalesListCodesAllInformation($ini, $end, $user_id,true,$state);
            $sales      = $this->calculateUtilidadForBill($sales,$user_id);
            $comision   = $this->Commision->findByUserId($user_id);
            $filter     = true;
            $saldos     = Set::extract($sales,"{n}.Receipt.total");
            $saldos     = array_sum($saldos);

            $demora     = $this->calcularRetraso($user_id, $ini, $end);

            $this->loadModel("CollectionGoal");
            $this->loadModel("Goal");

            $monthIni = date("m",strtotime($ini));
            $monthEnd = date("m",strtotime($end));

            if ($monthIni == $monthEnd) {
                $meta+= $this->CollectionGoal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
            }else{
                $meta+= $this->CollectionGoal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $meta+= $this->CollectionGoal->field($monthEnd,["user_id"=>$user_id, "year" => date("Y",strtotime($end)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($end)) ]);
            }

            $dataEfectivity = $this->getEfetivityByUser($ini,$end,$user_id);

            if($this->request->data["ProspectiveUser"]["excel"] == 1){
                $this->export_comissions($sales,$comision,$ini,$end);
            }

            if (!is_null($dataEfectivity) && isset($dataEfectivity["efectividad"])) {
                $this->loadModel("Effectivity");
                $percentajeDb = $this->Effectivity->find("first",["conditions"=>["Effectivity.min <=" => $dataEfectivity["efectividad"], "Effectivity.max >=" => $dataEfectivity["efectividad"],"Effectivity.user_id" => $comision["User"]["id"] ], "recursive" => -1]);
            }

            if (!empty($sales)) {
                $sales = Set::sort($sales, '{n}.Valores.valor_factura', 'desc');

                $ventas = $this->postWoApi(["ini" => $ini, "end" => $end, "vendedor" => $comision["User"]["identification"]  ],"documents");
                
                if (isset($ventas->listado)) {
                    foreach ($ventas->listado as $key => $value) {
                        $totalVentas += intval($value->Total_Venta - intval($value->Total_Descuentos));
                        $costoFacturas = $value->Total_Descuentos > 0 ? $costoFacturas-$value->CostoFactura : $costoFacturas+$value->CostoFactura;
                    }
                }

                $totalUtilidadVentas = $totalVentas-$costoFacturas;
                $totalMargen         = $totalUtilidadVentas/$totalVentas * 100;

                foreach ($sales as $key => $value) {
                    $dias           =       $this->calculateDays( $this->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date") ,$value["Receipt"]["date_receipt"]);
                    $percentaje     =    !is_null($value['Receipt']['percent_value']) ? $value['Receipt']['percent_value'] :  $this->getComissionPercentaje($dias,$comision);

                    if ($value["Valores"]["totalProductos"] == 1 && $value["Valores"]["totalSt"] > 0) {
                        $value["Receipt"]["total_iva"] = 0;
                    }

                    $multiplica     =       ( !is_null($value["Salesinvoice"]["bill_date"]) && $value["Salesinvoice"]["bill_date"] >= '2024-08-14' && $value["Salesinvoice"]["bill_date"] <= '2024-08-31' ) ||                    ( $value["ProspectiveUser"]["bill_date"] >= '2024-08-14' && $value["ProspectiveUser"]["bill_date"] <= '2024-08-31' ) ? 2 : 1;

                    $pagar          =       ($percentaje / 100) * floatval($value["Receipt"]["total_iva"] - $value["Valores"]["valorSt"]); 

                    if($multiplica == 2){
                        $pagar *=   2;
                    }

                    if($pagar < 0){ $pagar = 0; }

                    $totalPagar     +=      $pagar;

                    $totalAdescontar += floatval($value["ProspectiveUser"]["discount_datafono"]);

                    // $percentUtility = $this->calculatePercent($value["Valores"]["utilidad_porcentual"], $comision["Commision"]["user_id"]);

                    $percentUtility = !is_null($value["ProspectiveUser"]["comision"]) && !empty($value["ProspectiveUser"]["comision"]) ? $value["ProspectiveUser"]["comision"] : $this->calculatePercent($totalMargen,$comision["Commision"]["user_id"],0);


                    $pagarByPercent = $pagar * ($percentUtility / 100);
                    $totalPagarFinal += $pagarByPercent;

                    $sales[$key]["Finals"]["dias"] = $dias;
                    $sales[$key]["Finals"]["percentaje"] = $percentaje;
                    $sales[$key]["Finals"]["pagar"] = $pagar;
                    $sales[$key]["Finals"]["percentUtility"] = $percentUtility;
                    $sales[$key]["Finals"]["pagarByPercent"] = $pagarByPercent;

                    $nota = "";
                    if ($value["Valores"]["totalSt"] > 0) {
                        $nota = $value["Valores"]["totalSt"]." servicio(s) técnico(s), para un total de: ".number_format($value["Valores"]["valorBySt"]);
                    }

                    $sales[$key]["Finals"]["nota"] = $nota;

                    $totalServicios+=$value["Valores"]["valorBySt"];

                    $receipts_ids[] = $value["Receipt"]["id"];

                }


                $totalFinalMargen = intval($totalPagarFinal+$totalAdicionalFinal);
                $totalEfectivity = $totalFinalMargen;

                $totalEfectivity = $totalFinalMargen;
                if (!is_null($percentajeDb) && !empty($percentajeDb) ) {
                    $totalEfectivity = $percentajeDb["Effectivity"]["value"] == 0 ? 0 : $totalFinalMargen * ($percentajeDb["Effectivity"]["value"] / 100 ); 
                    $totalFinalEfectivity = $totalEfectivity; 
                }



                if($metaVentas > 0){                    

                    $totalExtra = $totalVentas >= $metaVentas ? $totalPagar*0.3 : 0;
                    if ($totalVentas >= $metaVentas) {
                        $totalEfectivity += $totalExtra;
                    }

                    
                }


                

                if(isset($this->request->data["guardar_liquidación"])){

                    $this->loadModel("Liquidation");
                    $liquidation_data = ["Liquidation" => [
                        "valor_recaudo" => $meta,
                        "valor_tiempo" => $totalPagar,
                        "valor_servicios" => $totalServicios,
                        "valor_efectividad" => $totalFinalEfectivity,
                        "valor_bono" => $totalExtra,
                        "valor_bono_gerencia" => intval($this->request->data["total_bono_gerencia"]),
                        "valor_a_pagar" => ($totalEfectivity) + $totalServicios + intval($this->request->data["total_bono_gerencia"]) - $totalAdescontar,
                        "total_recaudado" => $saldos,
                        "efectividad" => $percentajeDb["Effectivity"]["value"],
                        "total_ventas" => $totalVentas,
                        "date_ini" => $ini,
                        "date_end" => $end,
                        "user_id" => $user_id,

                    ]];

                    $this->Liquidation->create();
                    $this->Liquidation->save($liquidation_data);

                    $this->loadModel("Receipt");
                    $this->Receipt->updateAll(
                        ["liquidation_id" => $this->Liquidation->id, "user_liquidated" => AuthComponent::user("id"), "state" => 1 ],
                        ["Receipt.id" => $receipts_ids]
                    );

                    $this->loadModel("Manage");

                    $liquidation = $this->Liquidation->findById($this->Liquidation->id);

                    $subject = 'Nueva liquidación de comisiones para el asesor: '.$liquidation["User"]["name"]; 

                    // $this->Manage->sendNotificationLiquidation($subject,$this->Liquidation->id,6);

                    $this->Session->setFlash('La nota se ha guardado satisfactoriamente', 'Flash/success');
                    return $this->redirect(array('action' => 'index','controller'=>"liquidations"));
                }
                         

            }

        }

        

        $this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
        $this->set(compact('usuarios','sales','filter','userIds','comision','state','saldos','meta','dataEfectivity','metaVentas','totalVentas','totalExtra','totalEfectivity','totalServicios','totalPagar','totalPagarFinal','percentajeDb','totalFinalMargen','totalFinalEfectivity','totalAdescontar','totalUtilidadVentas','costoFacturas','costoFacturas','totalMargen','demora'));
    }

    public function informe_comisiones(){
        $this->loadModel("Commision");
        $usuarios           = $this->ProspectiveUser->User->find("list");
        $usersidsValids     = $this->Commision->find("all",[ "conditions" => ["User.role !=" => "Asesor Externo" ], "fields"=> array("user_id"), "recursive" => 1 ]);
        $userIds            = Set::extract($usersidsValids, "{n}.Commision.user_id");
        $comision           = null;
        if(!empty($userIds)  && !is_null($userIds)){

            foreach ($usuarios as $key => $value) {
                if(!in_array($key, $userIds)){
                    unset($usuarios[$key]);
                }
            }
        }else{
            $usuarios = array();
        }

        $filter     = false;
        $ini        = date("Y-m-d");
        $end        = date("Y-m-d");
        $state      = "";
        $sales      = array();
        $saldos     = [];
        $meta       = 0;
        $metaVentas = 0;

        $dataEfectivity = [];

        if($this->request->is("post")){
            $ini        = $this->request->data["fechaIni"];
            $end        = $this->request->data["fechaEnd"];
            $user_id    = $this->request->data["ProspectiveUser"]["user"];
            $state      = $this->request->data["ProspectiveUser"]["state"];
            $sales      = $this->ProspectiveUser->getSalesListCodesAllInformation($ini, $end, $user_id,true,$state);
            $sales      = $this->calculateUtilidadForBill($sales,$user_id);
            $comision   = $this->Commision->findByUserId($user_id);
            $filter     = true;
            $saldos     = Set::extract($sales,"{n}.Receipt.total");
            $saldos     = array_sum($saldos);

            $this->loadModel("CollectionGoal");
            $this->loadModel("Goal");

            $monthIni = date("m",strtotime($ini));
            $monthEnd = date("m",strtotime($end));

            if ($monthIni == $monthEnd) {
                $meta+= $this->CollectionGoal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
            }else{
                $meta+= $this->CollectionGoal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $meta+= $this->CollectionGoal->field($monthEnd,["user_id"=>$user_id, "year" => date("Y",strtotime($end)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($ini)) ]);
                $metaVentas+= $this->Goal->field($monthIni,["user_id"=>$user_id, "year" => date("Y",strtotime($end)) ]);
            }

            $dataEfectivity = $this->getEfetivityByUser($ini,$end,$user_id);


            // var_dump($sales); die();

            if($this->request->data["ProspectiveUser"]["excel"] == 1){
                $this->export_comissions($sales,$comision,$ini,$end,$saldos,$meta,$metaVentas,$dataEfectivity);
            }
        }


        $this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
        $this->set(compact('usuarios','sales','filter','userIds','comision','state','saldos','meta','dataEfectivity','metaVentas'));
    }


    public function change_percentaje(){
        $this->autoRender = false;
        $this->loadModel("Receipt");
        $id = $this->request->data["id"];
        $percent_value = $this->request->data["value"];

        $this->Receipt->save(["id" => $id, "percent_value" => $percent_value ]);
    }

    public function change_comision(){
        $this->autoRender   = false;
        $id                 = $this->request->data["id"];
        $percent_value      = $this->request->data["value"];
        $this->ProspectiveUser->save(["id" => $id, "comision" => $percent_value ]);
    }

    private function export_comissions($sales, $commsionData,$ini,$end,$saldos = 0, $minimo = 0,$metaVentas = 0,$dataEfectivity = null){

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $spreadsheet->getProperties()->setCreator('Kebco SAS')
                    ->setLastModifiedBy('Kebco SAS')
                    ->setTitle('Comisiones por vendedor')
                    ->setSubject('Comisiones por vendedor')
                    ->setDescription('Comisiones por vendedor')
                    ->setKeywords('Comisiones CRM Productos')
                    ->setCategory('Comisiones');

        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'FLUJO')
                    ->setCellValue('B1', 'CLIENTE')
                    ->setCellValue('C1', 'FACTURA')
                    ->setCellValue('D1', 'FECHA FACTURA')
                    ->setCellValue('E1', 'RECIBO')
                    ->setCellValue('F1', 'FECHA RECIBO')
                    ->setCellValue('G1', 'VALOR VENTA')
                    ->setCellValue('H1', 'VALOR FACTURA')
                    ->setCellValue('I1', 'COSTO FACTURA')
                    ->setCellValue('J1', 'UTILIDAD EN PESOS')
                    ->setCellValue('K1', 'MARGEN SOBRE LA VENTA')
                    ->setCellValue('L1', 'VALOR PAGO')
                    ->setCellValue('M1', 'BASE COMISIÓN')
                    ->setCellValue('N1', 'DÍAS PAGO')
                    ->setCellValue('O1', 'PORCENTAJE COMISIÓN')
                    ->setCellValue('P1', 'VALOR COMISIÓN')
                    ->setCellValue('Q1', 'NOTA')
                    ->setCellValue('R1', '% COMISION SEGÚN UTILIDAD')
                    ->setCellValue('S1', 'VALOR COMISIÓN SEGUN % UTILIDAD')
                    ->setCellValue('T1', 'VALOR COMISIÓN SERVICIO TECNICO');

        $i               = 2;
        $totalPagar      = 0;
        $totalPagarFinal = 0;
        $percentajeDb    = null;
        $totalVentas     = 0;
        $totalServicios  = 0;

        if (!is_null($dataEfectivity) && isset($dataEfectivity["efectividad"])) {
            $this->loadModel("Effectivity");
            $percentajeDb = $this->Effectivity->find("first",["conditions"=>["Effectivity.min <=" => $dataEfectivity["efectividad"], "Effectivity.max >=" => $dataEfectivity["efectividad"],"Effectivity.user_id" => $commsionData["User"]["id"] ], "recursive" => -1]);
        }


        foreach ($sales as $key => $value) {

            $cliente        =       !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"];
            $dias           =       $this->calculateDays( $this->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date") ,$value["Receipt"]["date_receipt"]);
            $percentaje     =       !is_null($value['Receipt']['percent_value']) ? $value['Receipt']['percent_value'] : $this->getComissionPercentaje($dias,$commsionData);


            if ($value["Valores"]["totalProductos"] == 1 && $value["Valores"]["totalSt"] > 0) {
                $value["Receipt"]["total_iva"] = 0;
            }

            $pagar          =       ($percentaje / 100) * floatval($value["Receipt"]["total_iva"] - $value["Valores"]["valorSt"]); 
            $totalPagar     +=      $pagar;

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $value["ProspectiveUser"]["id"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $cliente);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, strtoupper( $this->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_code") ) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i,  $this->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date") );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, strtoupper($value["Receipt"]["code"]));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $value["Receipt"]["date_receipt"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $this->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_value"));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $value["Valores"]["valor_factura"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $value["Valores"]["costo_factura"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, $value["Valores"]["utilidad_factura"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $value["Valores"]["utilidad_porcentual"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, $value["Receipt"]["total"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$i, $value["Receipt"]["total_iva"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$i, $dias);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, $percentaje);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $pagar);

            $percentUtility = $this->calculatePercent($value["Valores"]["utilidad_porcentual"], $commsionData["Commision"]["user_id"],$value["Valores"]["valores"]);
            $pagarByPercent = $pagar * ($percentUtility / 100);
            $totalPagarFinal += $pagarByPercent;

            $nota = "";
            if ($value["Valores"]["totalSt"] > 0) {
                $nota = $value["Valores"]["totalSt"]." servicio(s) técnico(s), para un total de: ".number_format($value["Valores"]["valorBySt"]);
            }

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$i, $nota);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('R'.$i, $percentUtility);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('S'.$i, $pagarByPercent);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('T'.$i, $value["Valores"]["valorBySt"]);

            $totalServicios+=$value["Valores"]["valorBySt"];
            $i++;

        }

        $label = "";

        $totalAdicional = 0;
        $totalAdicionalFinal = 0;

        $usersApply = [];
        $usersApply = $commsionData["User"]["users_money"] == "" ? [] : json_decode($commsionData["User"]["users_money"]);
        if (!empty($usersApply)) {
            $endDate = $end;
            if ($commsionData["User"]["id"] == 5 && strtotime($ini) == strtotime("2022-02-01") && strtotime($end) == strtotime("2022-02-28") ) {
                $endDate = "2022-02-25";
            }
            foreach ($usersApply as $key => $value) {
                $datosUsuario = $this->calculateFunctionByUserInterno($value,$ini,$endDate);   
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $datosUsuario["usuario"]);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, "Base recaudada");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$i, floatval($datosUsuario["totalPagar"]));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$i, "Comisión a ganar por este usuario:");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, floatval($datosUsuario["totalPagar"]*0.004));
                $totalAdicional+=($datosUsuario["totalPagar"]*0.004);
                $totalAdicionalFinal+=($datosUsuario["totalPagar"]*0.004);
                $i++;
            }
        }

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, "");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, "");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, "");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, "");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, "");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, "");

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, "");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, "");

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, "");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, "");


        
        

        if ($minimo != 0) {



            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.($i), "Meta de recaudo");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.($i), $minimo);

            $i++;

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.($i), "Valor recaudado");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.($i), $saldos);
            $i++;

            if (!is_null($dataEfectivity)) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "% de efectividad");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, isset($dataEfectivity["efectividad"]) ? $dataEfectivity["efectividad"] : 0 );
                $i++;
            }

            if ($metaVentas > 0) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "Meta de ventas");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $metaVentas);

                $ventas = $this->postWoApi(["ini" => $ini, "end" => $end, "vendedor" => $commsionData["User"]["identification"]  ],"documents");
                

                if (isset($ventas->listado)) {
                    foreach ($ventas->listado as $key => $value) {
                        $totalVentas += intval($value->Total_Venta - intval($value->Total_Descuentos));
                    }
                }
                $i++;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "Total ventas");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, number_format($totalVentas));
                $i++;
            }
            $i++;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.($i), "Total comisiones por recaudo");

            if ($minimo > $saldos) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.($i), "No cumple la meta mínima de recaudo");
            }else{
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.($i), $totalPagar+$totalAdicional);
            }
            $i++;

            

            

        }else{
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, "");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, "");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$i, "");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$i, "");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "Total a pagar");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i,  $totalPagar+$totalAdicional);
        }


        
        if (!empty($usersApply)) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$i,"Ganado por el usuario:" .$totalPagar. " + ganancia 0.004 de usuarios: " .number_format($totalAdicional));            
        }else{
            //$spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$i, "Ganado por el usuario:" .$totalPagar);                        
        }
        
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "Total a pagar según % de utilidad");
        if ($minimo != 0) {
            if ($minimo > $saldos) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, "No cumple la meta mínima de recaudo");
            }else{
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $totalPagarFinal+$totalAdicionalFinal);
            }
        }
        
        if (!empty($usersApply)) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$i, "Ganado por el usuario: " .$totalPagarFinal. " + ganancia según % de utilidad de 0.004 de usuarios: " .number_format($totalAdicional));
        }else{
            //$spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$i, "Ganado por el usuario:" .$totalPagarFinal);
        }

        $i++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "Diferencia comisiones por recaudo y margen");

        if ($minimo != 0) {
            if ($minimo > $saldos) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, "No cumple la meta mínima de recaudo");
            }else{
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, abs(($totalPagar+$totalAdicional)-($totalPagarFinal+$totalAdicionalFinal)));
            }
        }
        $i++;
        $i++;

        $totalFinalMargen = intval($totalPagarFinal+$totalAdicionalFinal);

        $totalEfectivity = $totalFinalMargen;
        if (!is_null($percentajeDb) && !empty($percentajeDb) ) {

            $totalEfectivity = $percentajeDb["Effectivity"]["value"] == 0 ? 0 : $totalFinalMargen * ($percentajeDb["Effectivity"]["value"] / 100 ); 
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "% a pagar según efectividad");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $minimo <= $saldos ? $percentajeDb["Effectivity"]["value"] : "No cumple la meta mínima de recaudo" );
            $i++;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "Total comisiones según efectividad");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $minimo <= $saldos ? $totalEfectivity : 'No cumple la meta mínima de recaudo' );
            $i++;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "Diferencia comisiones margen y efectividad");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $minimo <= $saldos ? abs($totalFinalMargen-$totalEfectivity) : 'No cumple la meta mínima de recaudo');
            $i++;
        }

        $i++;

        if ($metaVentas > 0  ) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "Total adicional por cumplir meta de ventas");
            if ($minimo <= $saldos) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $totalVentas >= $metaVentas ? $totalEfectivity*0.3 : 'No cumplió la meta de ventas' );
            }else{
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, 'No cumple la meta de recaudo' );
            }
            
            $i++;
            if ($totalVentas >= $metaVentas) {
                $totalEfectivity*=1.3;
            }
        }

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "Total comisiones final");
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $minimo <= $saldos ? $totalEfectivity : 'No cumple meta mínima de recaudo');

        if ($totalServicios > 0) {
            
            $i++;
            $i++;

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "Total servicios técnicos");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, $totalServicios );

            $i++;

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$i, "Total comiciones + servicios técnicos");
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$i, ($minimo <= $saldos ? $totalEfectivity  : 0) + $totalServicios );

        }
        
        // die;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->setTitle($commsionData["User"]["name"]);
        $spreadsheet->getActiveSheet()->getStyle('A1:T1')->getFont()->setBold(true);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="comisiones_kebco_'.time().'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 2025 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

    }

    public function cuenta_cobro($solicita = null){
        if (is_null($solicita)) {
            $this->layout = false;            
        }else{
            $this->autoRender = false;
        }
        $this->loadModel("Commision");
        $usuarios           = $this->ProspectiveUser->User->find("list");
        $usersidsValids     = $this->Commision->find("all",array("fields"=> array("user_id"), "recursive" => -1));
        $userIds            = Set::extract($usersidsValids, "{n}.Commision.user_id");
        $comision           = null;

        if(!empty($userIds)  && !is_null($userIds)){

            foreach ($usuarios as $key => $value) {
                if(!in_array($key, $userIds)){
                    unset($usuarios[$key]);
                }
            }
        }else{
            $usuarios = array();
        }

        $filter     = false;

        $sales      = $this->ProspectiveUser->getSalesListCodesExt(AuthComponent::user("id"));
        $sales      = $this->calculateUtilidadForBill($sales);

        $comision   = $this->Commision->findByUserId(AuthComponent::user("id"));

        if (!is_null($solicita)) {
            $this->loadModel("Account");
            $this->loadModel("Receipt");

            $totalAccount = 0;
            $recibosID    = [];


            foreach ($sales as $key => $value) {
                if ($value["Valores"]["totalProductos"] == 1 && $value["Valores"]["totalSt"] > 0) {
                    $value["Receipt"]["total_iva"] = $value["Valores"]["valorSt"];
                }
                $dias           =       $this->calculateDays( $this->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date") ,$value["Receipt"]["date_receipt"]);
                $percentaje     =       $this->getComissionPercentaje($dias,$comision);
                $pagar          = ($percentaje / 100) * floatval($value["Receipt"]["total_iva"] - $value["Valores"]["valorSt"]) + ($value["Valores"]["valorBySt"]) ; 

                $percentUtility = $this->calculatePercent($value["Valores"]["utilidad_porcentual"], $comision["Commision"]["user_id"]);
                $pagarByPercent = $pagar * ($percentUtility / 100);
                $totalAccount   += $pagarByPercent;
                $recibosID[]    = $value["Receipt"]["id"];
            }

            $cuentaData = [ "Account" => ["date_send" => date("Y-m-d H:i:s"),"initial_value" => $totalAccount, "user_id" => AuthComponent::user("id") ] ];
            $this->Account->create();
            if ($this->Account->save($cuentaData)) {
                $accountId = $this->Account->id;
                $this->Receipt->updateAll(["Receipt.account_id" => $accountId, "state" => 1 ], ["Receipt.id" => $recibosID ] );
                $account = $this->Account->findById($accountId);
                $options = array(
                    'to'        => ["contabilidad@yopmail.com","gerencia@yopmail.com"],
                    'template'  => 'account_cambio',
                    'subject'   => 'Se generó la cuenta de cobro CBKEB #'.$account['Account']['id'],
                    'vars'      => array('account' => $account, "account_review" => true )
                );

                $this->sendMail($options);

                $this->Session->setFlash('Información guardada satisfactoriamente, recuerda que esto se envió a proceso de gestión y aprobación de tesorería. Debes estar pendiente de la aplicación y el correo electrónico para información de cambios', 'Flash/success');
                $this->redirect(["action" => "informe_comisiones_externals_view", $this->encryptString($accountId)] );
            }else{
                $this->Session->setFlash('Información no guardada satisfactoriamente', 'Flash/error');
                $this->redirect(["action" => "informe_comisiones_externals",]);
            }


        }

        $this->set(compact('usuarios','sales','filter','userIds','comision'));
    }

    public function informe_comisiones_externals_view($accountID = null) {
        $this->loadModel("Account");
        $id = $this->desencriptarCadena($accountID);
        if (!$this->Account->exists($id)) {
            throw new NotFoundException(__('Invalid import request'));
        }
        $account    = $this->Account->findById($id);

        $this->loadModel("Commision");
        $usuarios           = $this->ProspectiveUser->User->find("list");
        $usersidsValids     = $this->Commision->find("all",array("fields"=> array("user_id"), "recursive" => -1));
        $userIds            = Set::extract($usersidsValids, "{n}.Commision.user_id");
        $comision           = null;

        $filter     = false;

        $sales      = $this->ProspectiveUser->getSalesListCodesExt($account["Account"]["user_id"],$id);
        $sales      = $this->calculateUtilidadForBill($sales);

        $comision   = $this->Commision->findByUserId($account["Account"]["user_id"]);

        $this->set(compact('usuarios','sales','filter','userIds','comision','account'));
    }

    public function informe_comisiones_externals(){
        $this->loadModel("Commision");
        $usuarios           = $this->ProspectiveUser->User->find("list");
        $usersidsValids     = $this->Commision->find("all",array("fields"=> array("user_id"), "recursive" => -1));
        $userIds            = Set::extract($usersidsValids, "{n}.Commision.user_id");
        $comision           = null;

        if(!empty($userIds)  && !is_null($userIds)){

            foreach ($usuarios as $key => $value) {
                if(!in_array($key, $userIds)){
                    unset($usuarios[$key]);
                }
            }
        }else{
            $usuarios = array();
        }

        $filter     = false;

        $sales      = $this->ProspectiveUser->getSalesListCodesExt(AuthComponent::user("id"));
        $sales      = $this->calculateUtilidadForBill($sales);

        $comision   = $this->Commision->findByUserId(AuthComponent::user("id"));
        $filter     = true;
        if($this->request->is("post") && $this->request->data["ProspectiveUser"]["excel"] == 1){
            $this->export_comissions($sales,$comision,$ini,$end);
        }

        $this->set("fechaInicioReporte", date("Y-m-d"));
        $this->set("fechaFinReporte", date("Y-m-d"));
        $this->set(compact('usuarios','sales','filter','userIds','comision'));
    }

    public function informe_comisiones_externals_gest() {
        $this->loadModel("Account");
        if (!in_array(AuthComponent::user("role"),["Contabilidad","Gerente General","Asesor Externo"])) {
            $this->redirect(["controller"=>"pages","action"=>"home_adviser"]);
        }

        $ini        = date("Y-m-d",strtotime("-30 day"));
        $end        = date("Y-m-d");
        $user_id    = "";

        $idsUsers   = $this->Account->find("list",["fields" => ["user_id","user_id"], "conditions" ]);
        $users      = $this->Account->User->find("list",["conditions"=>["User.id" => $idsUsers]]);

        if (!empty($this->request->query)) {
            $ini = $this->request->query["ini"];
            $end = $this->request->query["end"];
        }

       

        $conditions = [];

        if (AuthComponent::user("role") == "Asesor Externo") {
            $conditions["Account.user_id"] = AuthComponent::user("id");
        }

        $this->paginate             = array(
                                        'order'         => ["Account.date_send" => "ASC"],
                                        'limit'         => 20,
                                        'conditions'    => $conditions,
                                    );
        
        $this->set("fechaInicioReporte", $ini);
        $this->set("fechaFinReporte", $end);
        $this->set("users", $users);
        $this->set("user_id", $user_id);

        $this->set('accounts', $this->Paginator->paginate("Account"));
    }

    private function calculateUtilidadForBill($sales,$user_id = null,$margen = null){
        $this->loadModel("Salesinvoice");

        foreach ($sales as $key => $value) {
            $billData = empty($value["Receipt"]["salesinvoice_id"]) ? $value["ProspectiveUser"]["bill_text"] : $this->Salesinvoice->field("bill_text",["id" => $value["Receipt"]["salesinvoice_id"] ]);
            $flowID   = empty($value["Receipt"]["salesinvoice_id"]) ? $value["ProspectiveUser"]["id"] : $this->Salesinvoice->field("prospective_user_id",["id" => $value["Receipt"]["salesinvoice_id"] ]);
            if (!empty($billData)) {
                $dataFact = (array) json_decode($billData);
                if (!empty($dataFact)) {
                    if (!isset($this->flowsComisions[ $flowID ])) {
                        $this->flowsComisions[ $flowID ] = ["fact" => $dataFact["datos_factura"]->prefijo.$dataFact["datos_factura"]->Id, "recorre" => 0 ];
                    }else{
                        if ($this->flowsComisions[ $flowID ]["fact"] != $dataFact["datos_factura"]->prefijo.$dataFact["datos_factura"]->Id ) {
                            $this->flowsComisions[ $flowID ] = ["fact" => $dataFact["datos_factura"]->prefijo.$dataFact["datos_factura"]->Id, "recorre" => 0 ];
                        }
                    }
                }
                $sales[$key]["Valores"] = $this->getValueFact($dataFact,$flowID);
                $percentUtility = null;
                if (!is_null($user_id)) {
                    $percentUtility = !is_null($value["ProspectiveUser"]["comision"]) && !empty($value["ProspectiveUser"]["comision"]) ? $value["ProspectiveUser"]["comision"] : $this->calculatePercent(!is_null($margen) ? $margen : $sales[$key]["Valores"]["utilidad_porcentual"], $user_id,$sales[$key]["Valores"]["local"]);



                    // $sales[$key]["Valores"]["utilidad_porcentual"] = !is_null($value["ProspectiveUser"]["comision"]) && !empty($value["ProspectiveUser"]["comision"]) ? $value["ProspectiveUser"]["comision"] : $sales[$key]["Valores"]["utilidad_porcentual"];

                    $minus_value = $this->ProspectiveUser->field("value_minus",["id"=>$value["ProspectiveUser"]["id"]]);

                    if($minus_value > 0){
                        $percentUtility-=$minus_value;
                    }
                }

                $sales[$key]["Valores"]["percentFinalData"] = $percentUtility;
                // echo "<pre>";
                //     var_dump($sales[$key]["Valores"]);
                //     die();

            }else{
                
            }
        }
        return $sales;
    }

    public function verify_wo(){
        $this->layout = false;
        $flujo = $this->request->data["flujo"];
        $flow = $this->request->data["flow"];
        $user = $this->request->data["user"];
        $is_validate_adm = isset($this->request->data["is_validate_adm"]) ? $this->request->data["is_validate_adm"] : null;
        $data = $this->postWoApi(["dni"=>$this->request->data["dni"]],"customers");
        $this->set("data",$data);
        $this->set("flujo",$flujo);
        $this->set("flow",$flow);
        $this->set("user",$user);
        $this->set("is_validate_adm",$is_validate_adm);
    }

    private function getValueFactInfo($dataFact){

        $valor_factura       = 0;
        $costo_factura       = 0;
        $utilidad_factura    = 0;
        $utilidad_porcentual = 0;
        $totalSt             = 0;
        $totalProductos      = 0;
        $valorSt             = 0;
        $valorBySt           = 0;
        $productosNuevos     = [];

        $this->loadModel("Config");
        $this->loadModel("Product");
        $this->loadModel("Category");
        $this->loadModel("Cost");

        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $this->loadModel("Trm");
        $trmDay         = $this->Trm->field("valor",["fecha_inicio >=" => date("Y-m-d",strtotime($dataFact->datos_factura->Fecha) ), "fecha_fin <="=> date("Y-m-d",strtotime($dataFact->datos_factura->Fecha)) ]);

        $fechaData      = date("Y-m-d",strtotime($dataFact->datos_factura->Fecha));

        $totalSilicato  = 0;

        $referencesAbrasivo = ['M-8','M-25','M-60'];


        foreach ($dataFact->productos_factura as $key => $value) {
            $cantidad = is_null($value->Cantidad) ? 1 : $value->Cantidad;
            $totalProductos++;
            $datosProducto = $this->Product->find("first",["fields" => ["id","purchase_price_usd","category_id","created"], "conditions" => ["part_number"=>$value->CódigoInventario, "deleted" => 0], "recursive" => -1 ]);
            
            if ($value->CódigoInventario == 538 || empty($datosProducto) ) {

                if (empty($datosProducto)) {
                    continue;
                }

                $totalSt += $cantidad;
                $valorBySt += ($value->Precio * Configure::read("VALOR_ST")) * $cantidad;
                $valorSt += $value->Precio * $cantidad;
                continue;
            }

            
            if(in_array($value->CódigoInventario, $referencesAbrasivo)){
                $totalSilicato += floatval($value->Precio) * $value->Cantidad;
                if (isset($value->costo)) {
                    $costoU   = round(floatval($value->costo),2);
                }else{
                    $valorWO = $this->postWoApi(["part_number" => $value->CódigoInventario],"get_cost");
                    $costoU   = !empty($valorWO) ? round(floatval($valorWO->Costo),2) : 0; 
                }

                $value->Precio = $custoU / 0.65;
            }

            $valor_factura += floatval($value->Precio) * $value->Cantidad;
            $totalSilicato += floatval($value->Precio) * $value->Cantidad;

            if (isset($value->costo)) {
                $total   = round(floatval($value->costo),2);
            }else{
                $valorWO = $this->postWoApi(["part_number" => $value->CódigoInventario],"get_cost");
                $total   = !empty($valorWO) ? round(floatval($valorWO->Costo),2) : 0; 
            }


           
            $idProduct   = $datosProducto["Product"]["id"];
            $precioUsd   = $datosProducto["Product"]["purchase_price_usd"];
            $category_id = $datosProducto["Product"]["category_id"];
            $created     = $datosProducto["Product"]["created"];
            $fechaCompara = date("Y-m-d H:i:s",strtotime("-12 month"));
            


            if (strtotime($created) >= strtotime($fechaCompara) ) {
                $productosNuevos[] = [ "id" => $idProduct, "part_number" => $value->CódigoInventario, "precio" => floatval($value->Precio) * $value->Cantidad, "fecha" => $created, "factura" => $dataFact->datos_factura->prefijo." ".$dataFact->datos_factura->Id  ];
            }
            

            $factor      = $this->Category->field("factor",["id" => $category_id]);
            $precioUsd   = round(floatval($precioUsd),2);
            $otroCosto   = $this->Cost->field("pre_purchase_price_usd",["product_id"=> $idProduct, "pre_purchase_price_usd" => $precioUsd, "DATE(created) >=" => $fechaData ]);

            if ($otroCosto != false) {
                $precioUsd = round(floatval($otroCosto),2);
            }

            // $costFob   = $precioUsd*$factor*$trmActual;
            $costFob   = $precioUsd*$factor*$trmDay;

            if ($total < $costFob) {
                $total = $costFob;
            }

            $costo_factura += ($total * intval($value->Cantidad));
        }

        $utilidad_factura       = $valor_factura - $costo_factura;
        $utilidad_porcentual    = $costo_factura < 1000 ? 35 : round( ( ($utilidad_factura / $valor_factura) * 100 ),2 );   

        $utilidad_porcentual    = $utilidad_porcentual < 0 ? 0 : $utilidad_porcentual; 

        if($valor_factura < $totalSilicato){
            $valor_factura = $totalSilicato;
        }

        return compact("valor_factura","costo_factura","utilidad_factura","utilidad_porcentual","totalSt","valorSt","totalProductos","valorBySt",'productosNuevos');

    }

    private function getValueFact($dataFact,$flowID){

        $valor_factura       = 0;
        $costo_factura       = 0;
        $utilidad_factura    = 0;
        $utilidad_porcentual = 0;
        $totalSt             = 0;
        $totalProductos      = 0;
        $valorSt             = 0;
        $valorBySt           = 0;
        $valorReal           = 0;
        $costoReal           = 0;
        $local               = 1;

        $this->loadModel("Config");
        $this->loadModel("Product");
        $this->loadModel("Category");
        $this->loadModel("Cost");

        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $this->loadModel("Trm");
        $trmDay         = $this->Trm->field("valor",["fecha_inicio >=" => date("Y-m-d",strtotime($dataFact["datos_factura"]->Fecha) ), "fecha_fin <="=> date("Y-m-d",strtotime($dataFact["datos_factura"]->Fecha)) ]);

        $fechaData      = date("Y-m-d",strtotime($dataFact["datos_factura"]->Fecha));
        $totalAbrasivo  = 0;

        // foreach ($dataFact["valores_factura"] as $key => $value) {
        //     if (!is_null($value->IdClasificación)){
        //         $valor_factura = round($value->Crédito,2);
        //     }
        // }

        foreach ($dataFact["productos_factura"] as $key => $value) {
            $cantidad = is_null($value->Cantidad) ? 1 : $value->Cantidad;
            $totalProductos++;
            if ($value->CódigoInventario == 538 ) {

                if (isset($this->flowsComisions[ $flowID ]["fact"]) && $this->flowsComisions[ $flowID ]["fact"] == $dataFact["datos_factura"]->prefijo.$dataFact["datos_factura"]->Id && $this->flowsComisions[ $flowID ]["recorre"] == 0 ) {
                    $totalSt += $cantidad;
                    $valorBySt += ($value->Precio * Configure::read("VALOR_ST")) * $cantidad;
                    $this->flowsComisions[ $flowID ]["recorre"] = 1;
                    $valorSt += $value->Precio * $cantidad;
                    // $value->costo = round($valorSt/1.55);
                }
            }


            $dataAbrasivo = $this->get_data_abrasivo($value->CódigoInventario);

            if(!is_null($dataAbrasivo["precio"])){
                $total = $dataAbrasivo["precio"];
                $totalAbrasivo++;
            }else{
                if (isset($value->costo)) {
                    $total   = round(floatval($value->costo),2);
                }else{
                    $valorWO = $this->postWoApi(["part_number" => $value->CódigoInventario],"get_cost");
                    $total   = !empty($valorWO) ? round(floatval($valorWO->Costo),2) : 0; 
                }
            }

            $idProduct   = $this->Product->field("id",["part_number"=>$value->CódigoInventario, "deleted" => 0]);
            $precioUsd   = $this->Product->field("purchase_price_usd",["part_number"=>$value->CódigoInventario, "deleted" => 0]);
            $category_id = $this->Product->field("category_id",["part_number"=>$value->CódigoInventario, "deleted" => 0]);
            $type        = $this->Product->field("type",["part_number"=>$value->CódigoInventario, "deleted" => 0]);
            $factor      = $this->Category->field("factor",["id" => $category_id]);
            $precioUsd   = round(floatval($precioUsd),2);
            $otroCosto   = $this->Cost->field("pre_purchase_price_usd",["product_id"=> $idProduct, "purchase_price_usd" => $precioUsd, "DATE(created) >=" => $fechaData ]);

            if ($otroCosto != false) {
                $precioUsd = round(floatval($otroCosto),2);
            }

            if($type == 1){
                $local = 0;
            }            


            // $costFob   = $precioUsd*$factor*$trmActual;
            $costFob   = $precioUsd*$factor*$trmDay;
            if ($total < $costFob) {
                $total = $costFob;
            }

            if ($value->CódigoInventario != 538) {
                $costoReal += ($total * intval($value->Cantidad));
                $valorReal += floatval($value->Precio)* $value->Cantidad;
            }

            $costo_factura += ($total * intval($value->Cantidad));
            $valor_factura += floatval($value->Precio) * $value->Cantidad;
        }

        // if ($valor_factura == 0) {
        //     foreach ($dataFact["productos_factura"] as $key => $value) {
        //     }
        // }
       

        $utilidad_factura       = $valor_factura - $costo_factura;

        if ($valor_factura > $valorReal && $valorReal != 0) {
            $utilidad_factura       = $valorReal - $costoReal;

        }

        if($totalAbrasivo > 0 && $totalAbrasivo == count($dataFact["productos_factura"])){
            $utilidad_porcentual = 35;
        }else{
            $utilidad_porcentual    = $costo_factura < 1000 ? 35 : round( ( ($utilidad_factura / ( ($valor_factura > $valorReal && $valorReal != 0) ? $valorReal : $valor_factura ) ) * 100 ),2 );    
        }


        return compact("valor_factura","costo_factura","utilidad_factura","utilidad_porcentual","totalSt","valorSt","totalProductos","valorBySt","costoReal","valorReal","local");

    }

    private function calculateFunctionByUserInterno($user_id,$ini,$end){
        $this->loadModel("Commision");
        $commsionData   = $this->Commision->findByUserId($user_id);
        $sales          = $this->ProspectiveUser->getSalesListCodes($ini, $end, $user_id);
        $sales          = $this->calculateUtilidadForBill($sales);

        $totalPagar     = 0;
        $totalPagarFinal = 0;

        foreach ($sales as $key => $value) {
            $dias           =       $this->calculateDays( $this->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date") ,$value["Receipt"]["date_receipt"]);
            $percentaje     =       $this->getComissionPercentaje($dias,$commsionData);


            if (isset($value["Valores"]) && $value["Valores"]["totalProductos"] == 1 && $value["Valores"]["totalSt"] > 0) {
                $value["Receipt"]["total_iva"] = $value["Valores"]["valorSt"];
            }
            $value["Valores"]["valorSt"] = isset($value["Valores"]["valorSt"]) ? $value["Valores"]["valorSt"] : 0;
            // $pagar          =       floatval($value["Receipt"]["total_iva"] - $value["Valores"]["valorSt"]); 
            $pagar          =       ($percentaje / 100) * floatval($value["Receipt"]["total_iva"] - $value["Valores"]["valorSt"]) + $value["Valores"]["valorBySt"]; 
            if ($pagar < 0) {
                $pagar = 0;
            }
            $totalPagar     +=      $pagar;

            $percentUtility = $this->calculatePercent($value["Valores"]["utilidad_porcentual"], $commsionData["Commision"]["user_id"],$value["Valores"]["local"]);
            $pagarByPercent = $pagar * ($percentUtility / 100);
            $totalPagarFinal += $pagarByPercent;
        }

        $usuario = $commsionData["User"]["name"];

        $usuarioName = explode(" ", $usuario);

        $usuario = count($usuarioName) > 1 ? $usuarioName[0]. " ".$usuarioName[1] : $usuario;

        return compact("usuario","totalPagar","totalPagarFinal");

    }

    private function calculatePercent($percent, $user_id, $type = 0){
        $percentFinal = 0;
        $dbPercent = 0;

        $this->loadModel("Percentage");
        $this->loadModel("PercentagesLocal");

        if($type == 0){
            $percentajeDb = $this->Percentage->find("first",["conditions"=>["Percentage.min <=" => $percent, "Percentage.max >" => $percent,"Percentage.user_id" => $user_id ], "recursive" => -1]);
        }else{
            $percentajeDb = $this->PercentagesLocal->find("first",["conditions"=>["PercentagesLocal.min <=" => $percent, "PercentagesLocal.max >" => $percent,"PercentagesLocal.user_id" => $user_id ], "recursive" => -1]);            
        }


        if ($user_id == 5) {
            if($percent    >=  40  &&  $percent    <=  200 ){
                $percentFinal =   125;
            }elseif($percent    >=  39  &&  $percent    <=  40  ){
                $percentFinal =   120;
            }elseif($percent    >=  38  &&  $percent    <=  39  ){
                $percentFinal =   115;
            }elseif($percent    >=  37  &&  $percent    <=  38  ){
                $percentFinal =   110;
            }elseif($percent    >=  36  &&  $percent    <=  37  ){
                $percentFinal =   105;
            }elseif($percent    >=  35  &&  $percent    <=  36  ){
                $percentFinal =   100;
            }elseif($percent    >=  34  &&  $percent    <=  35  ){
                $percentFinal =   98;
            }elseif($percent    >=  33  &&  $percent    <=  34  ){
                $percentFinal =   96;
            }elseif($percent    >=  32  &&  $percent    <=  33  ){
                $percentFinal =   94;
            }elseif($percent    >=  31  &&  $percent    <=  32  ){
                $percentFinal =   92;
            }elseif($percent    >=  30  &&  $percent    <=  31  ){
                $percentFinal =   90;
            }elseif($percent    >=  29  &&  $percent    <=  30  ){
                $percentFinal =   85;
            }elseif($percent    >=  28  &&  $percent    <=  29  ){
                $percentFinal =   80;
            }elseif($percent    >=  27  &&  $percent    <=  28  ){
                $percentFinal =   75;
            }elseif($percent    >=  26  &&  $percent    <=  27  ){
                $percentFinal =   70;
            }elseif($percent    >=  25  &&  $percent    <=  26  ){
                $percentFinal =   60;
            }elseif($percent    >=  24  &&  $percent    <=  25  ){
                $percentFinal =   55;
            }elseif($percent    >=  23  &&  $percent    <=  24  ){
                $percentFinal =   50;
            }elseif($percent    >=  22  &&  $percent    <=  23  ){
                $percentFinal =   48;
            }elseif($percent    >=  21  &&  $percent    <=  22  ){
                $percentFinal =   46;
            }elseif($percent    >=  20  &&  $percent    <=  21  ){
                $percentFinal =   40;
            }elseif($percent    >=  19  &&  $percent    <=  20  ){
                $percentFinal =   35;
            }elseif($percent    >=  18  &&  $percent    <=  19  ){
                $percentFinal =   30;
            }elseif($percent    >=  17  &&  $percent    <=  18  ){
                $percentFinal =   25;
            }elseif($percent    >=  16  &&  $percent    <=  17  ){
                $percentFinal =   20;
            }elseif($percent    >=  15  &&  $percent    <=  16  ){
                $percentFinal =   15;
            }elseif($percent    >=  -100    &&  $percent    <  15  ){
                $percentFinal =   0;        
            }
        }else{
            if($percent    >=  40  &&  $percent    <=  200 ){
                $percentFinal =   125;
            }elseif($percent    >=  39  &&  $percent    <=  40  ){
                $percentFinal =   120;
            }elseif($percent    >=  38  &&  $percent    <=  39  ){
                $percentFinal =   115;
            }elseif($percent    >=  37  &&  $percent    <=  38  ){
                $percentFinal =   110;
            }elseif($percent    >=  36  &&  $percent    <=  37  ){
                $percentFinal =   105;
            }elseif($percent    >=  35  &&  $percent    <=  36  ){
                $percentFinal =   100;
            }elseif($percent    >=  34  &&  $percent    <=  35  ){
                $percentFinal =   95;
            }elseif($percent    >=  33  &&  $percent    <=  34  ){
                $percentFinal =   90;
            }elseif($percent    >=  32  &&  $percent    <=  33  ){
                $percentFinal =   85;
            }elseif($percent    >=  31  &&  $percent    <=  32  ){
                $percentFinal =   80;
            }elseif($percent    >=  30  &&  $percent    <=  31  ){
                $percentFinal =   75;
            }elseif($percent    >=  29  &&  $percent    <=  30  ){
                $percentFinal =   70;
            }elseif($percent    >=  28  &&  $percent    <=  29  ){
                $percentFinal =   65;
            }elseif($percent    >=  27  &&  $percent    <=  28  ){
                $percentFinal =   60;
            }elseif($percent    >=  26  &&  $percent    <=  27  ){
                $percentFinal =   55;
            }elseif($percent    >=  25  &&  $percent    <=  26  ){
                $percentFinal =   50;
            }elseif($percent    >=  24  &&  $percent    <=  25  ){
                $percentFinal =   45;
            }elseif($percent    >=  23  &&  $percent    <=  24  ){
                $percentFinal =   40;
            }elseif($percent    >=  22  &&  $percent    <=  23  ){
                $percentFinal =   35;
            }elseif($percent    >=  21  &&  $percent    <=  22  ){
                $percentFinal =   30;
            }elseif($percent    >=  20  &&  $percent    <=  21  ){
                $percentFinal =   25;
            }elseif($percent    >=  19  &&  $percent    <=  20  ){
                $percentFinal =   20;
            }elseif($percent    >=  18  &&  $percent    <=  19  ){
                $percentFinal =   15;
            }elseif($percent    >=  17  &&  $percent    <=  18  ){
                $percentFinal =   10;
            }elseif($percent    >=  16  &&  $percent    <=  17  ){
                $percentFinal =   5;
            }elseif($percent    >=  -100    &&  $percent    <=  15  ){
                $percentFinal =   0;        
            }
        }

        if (!empty($percentajeDb)) {
            $dbPercent = $percentajeDb["Percentage"]["value"];
            $percentFinal = $dbPercent;
        }
        
        return $percentFinal;
    }

    public function index_externals(){
        if (AuthComponent::user("role") == "Asesor Externo") {
            $this->redirect(["action"=>"adviser"]);
        }
        if (!in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad","Servicio al Cliente"])) {
            $this->redirect(["action"=>"index"]);
        }
        $get                        = $this->request->query;
        if ($this->request->is("post")) {
            $this->loadModel("FlowStage");
            $datosPros                                      =   $this->FlowStage->ProspectiveUser->get_data($this->request->data['ProspectiveUser']["id"]);
            $datosPros["ProspectiveUser"]["description"]    =   $this->request->data["ProspectiveUser"]["description"];
            $save = $this->FlowStage->ProspectiveUser->save($datosPros["ProspectiveUser"]);
            $conditions             = array('FlowStage.prospective_users_id' => $this->request->data['ProspectiveUser']["id"],'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'));

            $asginedFlow = $this->FlowStage->find("first",["recursive" => -1, "conditions" => $conditions ]);
            $asginedFlow["FlowStage"]["reason"] = $this->request->data["ProspectiveUser"]["description"];
            $this->FlowStage->save($asginedFlow);
            $this->Session->setFlash('Información guardada satisfactoriamente', 'Flash/success');
            $this->redirect(["action" => "index", "?" => ["q" => $this->request->data['ProspectiveUser']["id"]] ]);
        }
        if (!empty($get)) {

            if(in_array(AuthComponent::user("role"), array(Configure::read('variables.roles_usuarios.Gerente General'), Configure::read('variables.roles_usuarios.Logística') ) ) && isset($get['q'])){
                $conditions = array();
            }else{
                 $conditions            = array('ProspectiveUser.type' => 0);
            }

            if (isset($get['q'])) {
                $conditions         = $this->searchUser($get['q'],$conditions); 
            } else {
                if (isset($get['filterEtapa'])) {
                    $conditions     = $this->filterUser($get['filterEtapa'],$conditions1 = array());
                }
                if (isset($get['filterAsesores'])) {
                    $conditions     = $this->filterAsesor($get['filterAsesores'], $conditions);
                }

                if(isset($get["filterEtapa"]) && $get["filterEtapa"] == 56){
                    $this->ProspectiveUser->getNoSend($conditions,$get);
                }
            }

            if(isset($get["fechaInicioReporte"]) && isset($get["fechaFinReporte"])){
                $conditions["DATE(ProspectiveUser.created) >="] = $get["fechaInicioReporte"];
                $conditions["DATE(ProspectiveUser.created) <="] = $get["fechaFinReporte"];
                $this->set("fechaInicioReporte",$get["fechaInicioReporte"]);
                $this->set("fechaFinReporte",$get["fechaFinReporte"]);
            }

            $extUsers = $this->ProspectiveUser->User->find("list",["fields"=>["id","id"],"conditions" =>["User.role" => "Asesor Externo","User.state" => 1] ]);

        } else {
            $extUsers = $this->ProspectiveUser->User->find("list",["fields"=>["id","id"],"conditions" =>["User.role" => "Asesor Externo","User.state" => 1] ]);
            $conditions         = array('ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'),'ProspectiveUser.type' => 0,"ProspectiveUser.user_id"=>$extUsers);
        }

        $noPayment = $this->ProspectiveUser->getNoSend($conditions,$get);
        if(isset($get["filterEtapa"]) && $get["filterEtapa"] == 56 && !empty($noPayment)){
            $conditions["ProspectiveUser.id"] = $noPayment;
        }

        $order                  = array('ProspectiveUser.id' => 'desc');
        $this->paginate         = array(
                                        'limit'         => 6,
                                        'order'         => $order,
                                        'conditions'    => $conditions
                                    );
        $this->ProspectiveUser->unBindModel(array("belongsTo" => array("User","ContacsUser","ClientsNatural"),"hasMany" => array("FlowStage","FlowStagesProduct","AtentionTime")));
        try {
            $prospectiveUsers       = $this->paginate('ProspectiveUser');            
        } catch (Exception $e) {
            $prospectiveUsers       = array();        
        }

        $coditionsC = ["ProspectiveUser.user_id"=>$extUsers];
        
        $count_asignado             = $this->ProspectiveUser->count_flow_asignado($coditionsC);
        $count_contactado           = $this->ProspectiveUser->count_flow_contactado($coditionsC);
        $count_cotizado             = $this->ProspectiveUser->count_flow_cotizado($coditionsC);
        $count_negociado            = $this->ProspectiveUser->count_flow_negociado($coditionsC);
        $count_pagado               = $this->ProspectiveUser->count_flow_pagado($coditionsC);
        $count_pagadoNoDes          = count($noPayment);
        $count_despachado           = $this->ProspectiveUser->count_flow_despachado($coditionsC);
        $count_cancelado            = $this->ProspectiveUser->count_flow_cancelado($coditionsC);
        $count_todo_habilitado      = $count_asignado + $count_contactado + $count_cotizado + $count_negociado + $count_pagado + $count_despachado;
        $count_terminados           = $this->ProspectiveUser->count_flow_terminado($coditionsC);
        $usuariosAsesores           = $this->ProspectiveUser->User->find("all",["fields"=>["id","name"],"conditions" =>["User.role" => "Asesor Externo","User.state" => 1] ]);
        $usuarios_asesores          = $this->ProspectiveUser->User->find("list",["fields"=>["id","name"],"conditions" =>["User.role" => "Asesor Externo","User.state" => 1] ]);;
        $this->set(compact('prospectiveUsers','count_asignado','count_contactado','count_cotizado','count_negociado','count_pagado','count_despachado','count_cancelado','count_todo_habilitado','count_terminados','usuariosAsesores','usuarios_asesores','count_pagadoNoDes'));
    }

    public function index_remarketing() {
        $get                        = $this->request->query;
        if ($this->request->is("post")) {
            $this->loadModel("FlowStage");
            $datosPros                                      =   $this->FlowStage->ProspectiveUser->get_data($this->request->data['ProspectiveUser']["id"]);
            $datosPros["ProspectiveUser"]["description"]    =   $this->request->data["ProspectiveUser"]["description"];
            $save = $this->FlowStage->ProspectiveUser->save($datosPros["ProspectiveUser"]);
            $conditions             = array('FlowStage.prospective_users_id' => $this->request->data['ProspectiveUser']["id"],'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'));

            $asginedFlow = $this->FlowStage->find("first",["recursive" => -1, "conditions" => $conditions ]);
            $asginedFlow["FlowStage"]["reason"] = $this->request->data["ProspectiveUser"]["description"];
            $this->FlowStage->save($asginedFlow);
            $this->Session->setFlash('Información guardada satisfactoriamente', 'Flash/success');
            $this->redirect(["action" => "index", "?" => ["q" => $this->request->data['ProspectiveUser']["id"]] ]);
        }
        if (!empty($get)) {

            if(in_array(AuthComponent::user("role"), array(Configure::read('variables.roles_usuarios.Gerente General'), Configure::read('variables.roles_usuarios.Logística') ) ) && isset($get['q'])){
                $conditions = array();
            }else{
                 $conditions            = array('ProspectiveUser.type' => 0);
            }

            if (isset($get['q'])) {
                $conditions         = $this->searchUser($get['q'],$conditions); 
            } else {
                if (isset($get['filterEtapa'])) {
                    $conditions     = $this->filterUser($get['filterEtapa'],$conditions1 = array());
                }
                if (isset($get['filterAsesores'])) {
                    $conditions     = $this->filterAsesor($get['filterAsesores'], $conditions);
                }

                if(isset($get["filterEtapa"]) && $get["filterEtapa"] == 56){
                    $this->ProspectiveUser->getNoSend($conditions,$get);
                }
            }

            if(isset($get["fechaInicioReporte"]) && isset($get["fechaFinReporte"])){
                $conditions["DATE(ProspectiveUser.created) >="] = $get["fechaInicioReporte"];
                $conditions["DATE(ProspectiveUser.created) <="] = $get["fechaFinReporte"];
                $this->set("fechaInicioReporte",$get["fechaInicioReporte"]);
                $this->set("fechaFinReporte",$get["fechaFinReporte"]);
            }



        } else {
            $conditions         = array('ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'),'ProspectiveUser.type' => 0);
        }

        $conditions["ProspectiveUser.remarketing"] = 1;


        $noPayment  = $this->ProspectiveUser->getNoSend($conditions,$get);
        $withImport = $this->ProspectiveUser->withImport($conditions,$get);
        if(isset($get["filterEtapa"]) && $get["filterEtapa"] == 56 && !empty($noPayment)){
            $conditions["ProspectiveUser.id"] = $noPayment;
        }

         if(isset($get["filterEtapa"]) && $get["filterEtapa"] == 57 && !empty($noPayment)){
            $conditions["ProspectiveUser.id"] = $noPayment;
        }

        $extUsers = $this->ProspectiveUser->User->find("list",["fields"=>["id","id"],"conditions" =>["User.role" => "Asesor Externo","User.state" => 1] ]);

        if (!empty($extUsers) ) {

            if (!in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad","Servicio al Cliente"])) {
                $conditions["OR"]["ProspectiveUser.user_id !="] = $extUsers;                            
            }

        }

        
        $order                  = array('ProspectiveUser.id' => 'desc');
        $this->paginate         = array(
                                        'limit'         => 6,
                                        'order'         => $order,
                                        'conditions'    => $conditions
                                    );
        $this->ProspectiveUser->unBindModel(array("belongsTo" => array("ContacsUser","ClientsNatural"),"hasMany" => array("FlowStage","FlowStagesProduct","AtentionTime")));
        try {
            $prospectiveUsers       = $this->paginate('ProspectiveUser');            
        } catch (Exception $e) {
            $prospectiveUsers       = array();        
        }

        // if (count($prospectiveUsers) < 1) {
        //  $this->redirect(array('action' => 'filtro_null_index'));
        // }
        // 
        $coditionsC                 = ["ProspectiveUser.remarketing" => 1];
        $count_asignado             = $this->ProspectiveUser->count_flow_asignado($coditionsC);
        $count_contactado           = $this->ProspectiveUser->count_flow_contactado($coditionsC);
        $count_cotizado             = $this->ProspectiveUser->count_flow_cotizado($coditionsC);
        $count_negociado            = $this->ProspectiveUser->count_flow_negociado($coditionsC);
        $count_pagado               = $this->ProspectiveUser->count_flow_pagado($coditionsC);
        $count_pagadoNoDes          = count($noPayment);
        $count_despachado           = $this->ProspectiveUser->count_flow_despachado($coditionsC);
        $count_cancelado            = $this->ProspectiveUser->count_flow_cancelado($coditionsC);
        $count_todo_habilitado      = $count_asignado + $count_contactado + $count_cotizado + $count_negociado + $count_pagado + $count_despachado;
        $count_terminados           = $this->ProspectiveUser->count_flow_terminado($coditionsC);
        $usuariosAsesores           = $this->ProspectiveUser->User->role_asesor_comercial_users_all_true(true);
        $usuarios_asesores          = $this->ProspectiveUser->User->role_asesor_comercial_user_true(true);

        $this->set(compact('prospectiveUsers','count_asignado','count_contactado','count_cotizado','count_negociado','count_pagado','count_despachado','count_cancelado','count_todo_habilitado','count_terminados','usuariosAsesores','usuarios_asesores','count_pagadoNoDes'));
    }

    
	public function index() {
        $this->validateTimes(true);
        if (AuthComponent::user("role") == "Asesor Externo") {
            $redirect = ["action"=>"adviser"];
            if (isset($this->request->query["q"]) && !empty($this->request->query["q"])) {
                $redirect["?"] = ["q"=>$this->request->query["q"]];
            }
            $this->redirect($redirect);
        }
		$get 						= $this->request->query;
        if ($this->request->is("post")) {
            $this->loadModel("FlowStage");
            $datosPros                                      =   $this->FlowStage->ProspectiveUser->get_data($this->request->data['ProspectiveUser']["id"]);
            $datosPros["ProspectiveUser"]["description"]    =   $this->request->data["ProspectiveUser"]["description"];
            $save = $this->FlowStage->ProspectiveUser->save($datosPros["ProspectiveUser"]);
            $conditions             = array('FlowStage.prospective_users_id' => $this->request->data['ProspectiveUser']["id"],'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_asignado'));

            $asginedFlow = $this->FlowStage->find("first",["recursive" => -1, "conditions" => $conditions ]);
            $asginedFlow["FlowStage"]["reason"] = $this->request->data["ProspectiveUser"]["description"];
            $this->FlowStage->save($asginedFlow);
            $this->Session->setFlash('Información guardada satisfactoriamente', 'Flash/success');
            $this->redirect(["action" => "index", "?" => ["q" => $this->request->data['ProspectiveUser']["id"]] ]);
        }
		if (!empty($get)) {

            if(in_array(AuthComponent::user("role"), array('Gerente General', 'Logística' ) ) && !empty($get)){
                $conditions = array();
            }else{
			     $conditions 			= array('ProspectiveUser.type' => 0);
            }

			if (isset($get['q'])) {
				$conditions 		= $this->searchUser($get['q'],$conditions); 
			} else {
				if (isset($get['filterEtapa'])) {
					$conditions 	= $this->filterUser($get['filterEtapa'],$conditions1 = array());
				}
				if (isset($get['filterAsesores'])) {
					$conditions 	= $this->filterAsesor($get['filterAsesores'], $conditions);
				}
			}

            if(isset($get["fechaInicioReporte"]) && isset($get["fechaFinReporte"])){
                $conditions["DATE(ProspectiveUser.created) >="] = $get["fechaInicioReporte"];
                $conditions["DATE(ProspectiveUser.created) <="] = $get["fechaFinReporte"];
                $this->set("fechaInicioReporte",$get["fechaInicioReporte"]);
                $this->set("fechaFinReporte",$get["fechaFinReporte"]);
            }



		} else {
			$conditions 		= array('ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'),'ProspectiveUser.type' => 0);
		}

        $noPayment  = $this->ProspectiveUser->getNoSend($conditions,$get);
        $withImport = $this->ProspectiveUser->withImport($conditions,$get);
        $withoutShipping = $this->ProspectiveUser->withoutShipping($conditions,$get);
        $middleShipping = $this->ProspectiveUser->middleShipping($conditions,$get);

        if(isset($get["filterEtapa"]) && $get["filterEtapa"] == 56 && !empty($noPayment)){
            $conditions["ProspectiveUser.id"] = $noPayment;
            $conditions["ProspectiveUser.bill_code"] = null;
        }

        if(isset($get["filterEtapa"]) && $get["filterEtapa"] == 57 && !empty($withImport)){
            $conditions["ProspectiveUser.id"] = $withImport;
        }

        if(isset($get["filterEtapa"]) && $get["filterEtapa"] == 58 && !empty($withoutShipping)){
            $conditions["ProspectiveUser.id"] = $withoutShipping;
        }

        if(isset($get["filterEtapa"]) && $get["filterEtapa"] == 59 && !empty($middleShipping)){
            $conditions["ProspectiveUser.id"] = $middleShipping;
        }

        $extUsers = $this->ProspectiveUser->User->find("list",["fields"=>["id","id"],"conditions" =>["User.role" => "Asesor Externo","User.state" => 1] ]);

        if (!empty($extUsers) ) {

            if (!in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad","Servicio al Cliente"])) {
                $conditions["OR"]["ProspectiveUser.user_id !="] = $extUsers;                            
            }

        }

		$order					= array('ProspectiveUser.id' => 'desc');
		$this->paginate 		= array(
								        'limit' 		=> 6,
								        'order' 		=> $order,
								        'conditions'	=> $conditions
								    );
		$this->ProspectiveUser->unBindModel(array("belongsTo" => array("ContacsUser","ClientsNatural","Adress"),"hasMany" => array("FlowStage","FlowStagesProduct","AtentionTime","TechnicalService","Payment","BillProduct")));
        try {
	        $prospectiveUsers 		= $this->paginate('ProspectiveUser');            
        } catch (Exception $e) {
            $prospectiveUsers       = array();        
        }

	    // if (count($prospectiveUsers) < 1) {
	    // 	$this->redirect(array('action' => 'filtro_null_index'));
	    // }
	    $count_asignado 			= $this->ProspectiveUser->count_flow_asignado($coditionsC = array());
	    $count_contactado 			= $this->ProspectiveUser->count_flow_contactado($coditionsC = array());
	    $count_cotizado 			= $this->ProspectiveUser->count_flow_cotizado($coditionsC = array());
	    $count_negociado 			= $this->ProspectiveUser->count_flow_negociado($coditionsC = array());
        $count_pagado               = $this->ProspectiveUser->count_flow_pagado($coditionsC = array());
	    $count_pagadoNoDes     		= count($noPayment);
        $count_withImport           = count($withImport);
        $count_without              = count($withoutShipping);
        $count_parcial              = count($middleShipping);
	    $count_despachado 			= $this->ProspectiveUser->count_flow_despachado($coditionsC = array());
	    $count_cancelado 			= $this->ProspectiveUser->count_flow_cancelado($coditionsC = array());
	    $count_todo_habilitado 		= $count_asignado + $count_contactado + $count_cotizado + $count_negociado + $count_pagado + $count_despachado;
	    $count_terminados 			= $this->ProspectiveUser->count_flow_terminado($coditionsC = array());
	    $usuariosAsesores 		 	= $this->ProspectiveUser->User->role_asesor_comercial_users_all_true(true);
	    $usuarios_asesores 		 	= $this->ProspectiveUser->User->role_asesor_comercial_user_true(true);
        // $usuarios           = $this->ProspectiveUser->User->role_asesor_comercial_user_true(true);

	    $this->set(compact('prospectiveUsers','count_asignado','count_contactado','count_cotizado','count_negociado','count_pagado','count_despachado','count_cancelado','count_todo_habilitado','count_terminados','usuariosAsesores','usuarios_asesores','count_pagadoNoDes','count_withImport','count_parcial','count_without'));
	}

    public function get_flow_data(){
        $this->autoRender = false;
        $this->ProspectiveUser->recursive = 2;
        $dataFlow = $this->ProspectiveUser->findById($this->request->query["q"]);

        if ($dataFlow["ProspectiveUser"]["contacs_users_id"] > 0) {
            $cliente = $dataFlow["ContacsUser"]["ClientsLegal"]["name"]." - ".$dataFlow["ContacsUser"]["name"];
        }else{
            $cliente = $dataFlow["ClientsNatural"]["name"];
        }

        $result   = [ ["id" => $this->request->query["q"], "text" => "Flujo: ".$this->request->query["q"]." | Cliente: ".$cliente." | Asesor: ".$dataFlow["User"]["name"] ] ];
        return empty($result) ? json_encode([]) :  json_encode(["items" => $result ]);
    }

    public function get_user(){
        $this->autoRender = false;

        $this->request->query = isset($this->request->query["q"]) ? ["q" => $this->request->query["q"] ] : ["q" => "" ];

        $clientsLegals      = $this->getClientsLegals($this->request->query["q"]);
        $clientsNaturals    = $this->getClientsNaturals($this->request->query["q"]);

        $clientes           = array_merge($clientsLegals,$clientsNaturals);

        $result             = [];

        if (!empty($clientes)) {            
            foreach ($clientes as $key => $value) {
                $result[] = ["id" => $key, "text" => $value ];
            }
        }

        return empty($result) ? json_encode([]) :  json_encode(["items" => $result ]);
    }

    public function get_flujo($encrypt = null){
        $this->autoRender = false;

        $this->request->query = isset($this->request->query["q"]) ? ["q" => $this->request->query["q"] ] : ["q" => "" ];

        $this->ProspectiveUser->hasAndBelongsToMany = [];
        $this->ProspectiveUser->hasMany = [];
        $this->loadModel("FlowStage");

        $datos              = $this->ProspectiveUser->find("all",["fields"=>["ProspectiveUser.id","User.name", "ProspectiveUser.state_flow"],"conditions" => ["ProspectiveUser.state_flow" => [5,6,8], "ProspectiveUser.id LIKE "=> '%'.$this->request->query["q"].'%' ], "limit" => 20, "order" => ["ProspectiveUser.id" => "DESC"] ]);
        $result             = [];

        if (!empty($datos)) {            
            foreach ($datos as $key => $value) {
                $flujosVerify = $this->FlowStage->flujos_verify($value["ProspectiveUser"]["id"]);


                if ($value["ProspectiveUser"]["state_flow"] == 5 && !empty($flujosVerify) ) {
                    continue;
                }else{
                    $result[] = ["id" => !is_null($encrypt) ? $this->encryptString($value["ProspectiveUser"]["id"]) : $value["ProspectiveUser"]["id"], "text" => $value["ProspectiveUser"]["id"]. " - ". $value["User"]["name"] ];
                    $result[] = ["id" => !is_null($encrypt) ? $this->encryptString($value["ProspectiveUser"]["id"]) : $value["ProspectiveUser"]["id"], "text" => $value["ProspectiveUser"]["id"]. " - ". $value["User"]["name"] ];
                }
            }
        }

        return empty($result) ? json_encode([]) :  json_encode(["items" => $result ]);
    }

    public function new_customer(){
        $this->layout       = false;
        $this->loadModel("ClientsNatural");
        $this->loadModel("ClientsLegal");
        // $clientsLegals      = $this->getClientsLegals();
        // $clientsNaturals    = $this->getClientsNaturals();
        // unset($this->ProspectiveUser->belongsTo->User);
        $datosP             = $this->ProspectiveUser->findById($this->request->data["flujo"]);
        $actual             = is_null($datosP["ProspectiveUser"]["clients_natural_id"]) ? $datosP["ContacsUser"]["clients_legals_id"]."_LEGAL" : $datosP["ProspectiveUser"]["clients_natural_id"]."_NATURAL";
        $options            = [];

        if (is_null($datosP["ProspectiveUser"]["clients_natural_id"]) || $datosP["ProspectiveUser"]["clients_natural_id"] == 0 ) {
            $customer = $this->ClientsLegal->findById($datosP["ContacsUser"]["clients_legals_id"]);
            $options[$customer["ClientsLegal"]["id"]."_LEGAL"] = trim($customer["ClientsLegal"]["nit"])." | ".trim($customer["ClientsLegal"]["name"]);
        }else{
            $customer = $this->ClientsNatural->findById($datosP["ProspectiveUser"]["clients_natural_id"]);
            $options[$customer["ClientsNatural"]["id"]."_NATURAL"] = trim($customer["ClientsNatural"]["identification"])." | ".trim($customer["ClientsNatural"]["name"])." | ".trim($customer["ClientsNatural"]["email"]);
        }

        $this->set(compact("clientsLegals","clientsNaturals","datosP","actual","options"));
    }

    public function change_customer(){
        $this->layout       = false;
        $clientsNaturals    = $this->ProspectiveUser->ClientsNatural->findById($this->request->data["cliente"]);
        $flujo_id           = $this->request->data["flujo"];
        $this->set(compact("clientsNaturals","flujo_id"));
    }

    public function change_post_customer(){
        $this->autoRender = false;

        $this->loadModel("ContacsUser");

        $datosLegal = [ "ClientsLegal" => [
            "name" => $this->request->data["ClientsNatural"]["name_emp"],
            "nit" => $this->request->data["ClientsNatural"]["nit"],
            "nacional" => $this->request->data["ClientsNatural"]["nacional"],
            "user_receptor" => AuthComponent::user("id"),
        ] ];

        $idActual   = $this->request->data["ClientsNatural"]["id"];
        $flujoID    = $this->request->data["ClientsNatural"]["flujo_id"];
        $type       = $this->request->data["ClientsNatural"]["type"];

        unset($this->request->data["ClientsNatural"]["name_emp"]);
        unset($this->request->data["ClientsNatural"]["nit"]);
        unset($this->request->data["ClientsNatural"]["nacional"]);
        unset($this->request->data["ClientsNatural"]["id"]);
        unset($this->request->data["ClientsNatural"]["flujo_id"]);
        unset($this->request->data["ClientsNatural"]["type"]);

        $this->ProspectiveUser->recursive = -1;
        $flujo = $this->ProspectiveUser->findById($flujoID);

        $this->request->data["ClientsNatural"]["user_receptor"] = AuthComponent::user("id");

        $datosContacto = ["ContacsUser" => $this->request->data["ClientsNatural"] ];


        $conditions    = array('OR' => array(
                                        'LOWER(ContacsUser.telephone) LIKE'     => '%'.$datosContacto['ContacsUser']['telephone'].'%',
                                        'LOWER(ContacsUser.cell_phone) LIKE'    => '%'.$datosContacto['ContacsUser']['telephone'].'%',
                                        'LOWER(ContacsUser.telephone) LIKE'     => '%'.$datosContacto['ContacsUser']['cell_phone'].'%',
                                        'LOWER(ContacsUser.cell_phone) LIKE'    => '%'.$datosContacto['ContacsUser']['cell_phone'].'%',
                                        'LOWER(ContacsUser.email) LIKE'         => '%'.$datosContacto['ContacsUser']['email'].'%'
                                    ),
                                "ContacsUser.id !=" => $idActual  
                                );

        $existe             = $this->ContacsUser->find("count",["conditions"=>$conditions]);

        $this->ProspectiveUser->ContacsUser->ClientsLegal->create();
        if ($existe == 0 && $this->ProspectiveUser->ContacsUser->ClientsLegal->save($datosLegal)) {
            $clienteLegalID = $this->ProspectiveUser->ContacsUser->ClientsLegal->id;
            $datosContacto["ContacsUser"]["clients_legals_id"] = $clienteLegalID;

            $this->ProspectiveUser->ContacsUser->create();
            if ($this->ProspectiveUser->ContacsUser->save($datosContacto)) {
                $contactoId = $this->ProspectiveUser->ContacsUser->id;
                $flujo["ProspectiveUser"]["clients_natural_id"] = null;
                $flujo["ProspectiveUser"]["contacs_users_id"]   = $contactoId;
                $this->ProspectiveUser->save($flujo);

                $this->ProspectiveUser->recursive = -1;
                $flujos = $this->ProspectiveUser->findAllByClientsNaturalId($idActual);
                foreach ($flujos as $key => $value) {
                    $value["ProspectiveUser"]["clients_natural_id"] = null;
                    $value["ProspectiveUser"]["contacs_users_id"]   = $contactoId;
                    $this->ProspectiveUser->save($value);
                }

                
                
                try {

                    $this->loadModel("TechnicalService");
                    $this->TechnicalService->recursive = -1;
                    $tecnicos = $this->TechnicalService->findAllByClientsNaturalId($idActual);
                    if(!empty($tecnicos)){
                        foreach ($tecnicos as $key => $value) {
                            $value["TechnicalService"]["clients_natural_id"] = 0;
                            $value["TechnicalService"]["clients_legal_id"]   = $clienteLegalID;
                            $value["TechnicalService"]["contacs_users_id"]   = $contactoId;
                            $this->TechnicalService->save($value);
                        }
                    }
                    
                } catch (Exception $e) {
                    
                }

                if ($type == "1") {
                    $this->ProspectiveUser->ClientsNatural->recursive = -1;
                    $clienteActual = $this->ProspectiveUser->ClientsNatural->findById($idActual);
                    $this->ProspectiveUser->ClientsNatural->delete($idActual);
                }
                $this->Session->setFlash(__('El cambio fue realizado correctamente correctamente'),'Flash/success');
                return $flujoID;
            }else{
                $this->Session->setFlash(__('El cambio no fue realizado correctamente correctamente'),'Flash/error');
                return false;
            }
        }else{
            $this->Session->setFlash(__('El cambio no fue realizado correctamente correctamente'),'Flash/error');
            return false;
        }
    }

    public function facturas(){

        $conditions = array(
            "ProspectiveUser.bill_code IS NOT NULL" 
        );

        if(!empty($this->request->query) && isset($this->request->query["q"]) && !empty($this->request->query["q"])){
            $conditions["ProspectiveUser.id"] = $this->request->query["q"];
        }

        if(!empty($this->request->query) && isset($this->request->query["dateBill"]) && !empty($this->request->query["dateBill"])){
            $conditions["ProspectiveUser.bill_date"] = $this->request->query["dateBill"];
        }

        $order                  = array('ProspectiveUser.bill_date' => 'desc');
        $this->paginate         = array(
                                        'limit'         => 10,
                                        'order'         => $order,
                                        'conditions'    => $conditions,
                                        'recursive'     => 2,
                                    );
        $this->ProspectiveUser->unBindModel(array("belongsTo" => array("User"),"hasMany" => array("FlowStage","FlowStagesProduct","AtentionTime")));
        $this->loadModel("Salesinvoice");
        $prospectiveUsers       = $this->paginate('ProspectiveUser');

        foreach ($prospectiveUsers as $key => $value) {
            $this->Salesinvoice->recursive = -1;
            $prospectiveUsers[$key]["facturas"] = $this->Salesinvoice->findAllByProspectiveUserId($value["ProspectiveUser"]["id"]);
        }
        $this->set("prospectiveUsers",$prospectiveUsers);
    }

	public function bill_information(){
        $this->layout       = false;
        $usuarios_asesores  = ["Asesores KEBCO" => $this->ProspectiveUser->User->role_asesor_comercial_user(), "Asesores Externos" => $this->ProspectiveUser->User->find("list",["conditions" => ["User.role" => "Asesor Externo","User.state" => 1 ] ]) ];
        $disabled           = false;
        $cotizacion         = array();
        $prospective        = $this->request->data["id"];
        $bill               = $this->request->data["id"];
        $produtosCotizacion = array();
        $imports            = array();

        $validateTypeCotizacion = '';
        $billed                 = 0;

        if(!empty($this->request->data["view"])){
            $disabled = true;
            if (in_array( AuthComponent::user("role"),array( Configure::read('variables.roles_usuarios.Gerente General'),
                                Configure::read('variables.roles_usuarios.Servicio al Cliente'), Configure::read('variables.roles_usuarios.Asesor Logístico Comercial') ) )){
                $disabled = false;
            }
        }
        $this->loadModel("Quotation");
        $this->set("usuariosAsesoresData", $usuarios_asesores);
        $this->set("disabled", $disabled);
        $this->set("datosFlujo", $this->ProspectiveUser->findById($prospective));
        $this->ProspectiveUser->recursive = -1;
        $this->request->data = !empty($this->request->data["bill"]) && !is_null($this->request->data["bill"]) ? array() : $this->ProspectiveUser->findById($this->request->data["id"]);

        $id_etapa_cotizado          = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($prospective);
        $datosFlowstage             = $this->ProspectiveUser->FlowStage->get_data($id_etapa_cotizado);
        if (is_numeric($datosFlowstage['FlowStage']['document'])) {
            $produtosCotizacion     = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datosFlowstage['FlowStage']['document']);
            $cotizacion             = $this->Quotation->find("first",["recursive" => -1, "conditions" => ["id" => $datosFlowstage["FlowStage"]["document"]],"fields" => ["total","header_id","id"]]);
        }   

        $productosFinal = array();

        foreach ($produtosCotizacion as $key => $value) {
            if($value["QuotationsProduct"]["biiled"] == 1){
                $billed++;
            }
            if($value["QuotationsProduct"]["trm_change"] != 0 && $value["QuotationsProduct"]["change"] == 1){
                $trm = $value["QuotationsProduct"]["trm_change"];
            }
            if(!array_key_exists($value["QuotationsProduct"]["product_id"], $productosFinal)){
                $productosFinal[$value["QuotationsProduct"]["product_id"]] = $value;
            }else{
                if($value['QuotationsProduct']['state'] == $productosFinal[$value["QuotationsProduct"]["product_id"]]["QuotationsProduct"]["state"]){
                    $productosFinal[$value["QuotationsProduct"]["product_id"]]["QuotationsProduct"]["quantity"]+=$value["QuotationsProduct"]["quantity"];
                }else{
                    $productosFinal[(rand(1000000,500000) + $value["QuotationsProduct"]["product_id"])] = $value;
                }                
            }
        }

        $this->loadModel("Config");
        $config                 = $this->Config->findById(1);
        $trmActual              = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $trm                    = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $this->set("trmActual",$trmActual);
        $this->set("ajuste",$config["Config"]["ajusteTrm"]);

        $this->set("trm",$trm);

        $this->set("produtosCotizacion", $productosFinal);
        $this->set("totalProductos", count($produtosCotizacion));
        $this->set("validateTypeCotizacion", $validateTypeCotizacion);
        $this->set("prospective", $prospective);
        $this->set("cotizacion", $cotizacion);
        $this->set("billed", $billed);

        $this->loadModel("ImportsProspectiveUser");

        $flujos   = $this->ImportsProspectiveUser->find("all",array("fields" => array("import_id",), "conditions" =>array("prospective_user_id" => $prospective), "recursive" => -1,"group" => array("import_id","prospective_user_id")));

        foreach ($flujos as $key => $value) {
            $imports[]["id"] = $value["ImportsProspectiveUser"]["import_id"];
        }
        $this->set("imports", $imports);

        if(!empty($bill)){
            $this->request->data["ProspectiveUser"]["id"] = $prospective;
        }

    }

    public function bill_qt($id,$flow_qt){

        $this->layout = false;

        if ($this->request->is("post")) {
            $this->loadModel("FlowStage");
            $this->loadModel("Quotation");
            $this->loadModel("Product");
            $this->loadModel("Order");
            $dataFlow       = $this->FlowStage->findById($flow_qt);
            $dataQtId       = null;
            $idQuotation    = null;

            $params["number"]   = $this->request->data["ProspectiveUser"]["bill_code"];
            $params["prefijo"]  = $this->request->data["ProspectiveUser"]["bill_prefijo"];
            $datos              = (array) $this->getDataDocument($params);

            $order              = $this->Order->find("first",["conditions"=>["Order.prospective_user_id"=>$id],"order"=>["Order.id"=>"desc"]]);

            if(!empty($order) && !empty($datos)){
                $products_for_bill = [];

                $referencesValidate = [];

                foreach ($datos["productos_factura"] as $key => $value) {
                    $referencesValidate[] = $value->CódigoInventario;
                }

                foreach ($order["Product"] as $key => $value) {
                    if(in_array($value["part_number"], $referencesValidate)){
                        $value["OrdersProduct"]["biiled"] = 2;
                        $products_for_bill[] = $value;
                        $this->Order->OrdersProduct->save($value["OrdersProduct"]);
                    }
                }
                if(count($products_for_bill) > 0 ){
                    $this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente'),'Flash/success'); 
                }else{
                    $this->Session->setFlash('La información de la factura no esta completa','Flash/error');
                }
            }else{
                $this->Session->setFlash('La información de la factura no esta completa','Flash/error');
            }

            $this->redirect(["action"=>"index","?"=>["q"=>$id]]);
        }

        $this->set(compact("id","flow_qt"));
    }

    public function carga_factura() {

        if ($this->request->is("post")) {
            $params["number"]   = $this->request->data["ProspectiveUser"]["bill_code"];
            $params["prefijo"]  = $this->request->data["ProspectiveUser"]["bill_prefijo"];
            $datos              = (array) $this->getDataDocument($params);
            $flujos             = $this->request->data["ProspectiveUser"]["flows"];
            $productsData       = [];

            if (!empty($this->request->data["ProspectiveUser"]["products_data"])) {
                $this->request->data["ProspectiveUser"]["products_data"] = json_decode($this->request->data["ProspectiveUser"]["products_data"]);
                $productsData = $this->request->data["ProspectiveUser"]["products_data"];
            }

            unset($this->request->data["ProspectiveUser"]["flows"]);

            foreach ($flujos as $posFlujo => $flujo_id) {
                $newData   = $this->request->data;
                $newData["ProspectiveUser"]["id"] = $flujo_id;

                $bill_code = $newData["ProspectiveUser"]["bill_prefijo"].= " ".$newData["ProspectiveUser"]["bill_code"];
                $newData["ProspectiveUser"]["bill_prefijo"]      = $newData["ProspectiveUser"]["bill_code"];
                $newData["ProspectiveUser"]["bill_code"]         = $bill_code;
                $newData["ProspectiveUser"]["bill_file"]         = null;
                $newData["ProspectiveUser"]["bill_text"]         = json_encode($datos);

                if (!empty($datos) && isset($datos["valores_factura"]) && !empty($datos["valores_factura"])) {
                    foreach ($datos["valores_factura"] as $key => $value) {
                        if (!is_null($value->IdClasificación)) {
                            $newData["ProspectiveUser"]["bill_value"] = floatval($value->Crédito);
                        }
                        if (is_null($value->IdClasificación)) {
                            $newData["ProspectiveUser"]["bill_value_iva"] = floatval($value->Débito);
                        }
                    }
                }else{
                    $this->Session->setFlash('La información de la factura no esta completa','Flash/error');
                    return $this->redirect(["action"=>"carga_factura"]);
                }

                if (!empty($datos) && isset($datos["datos_factura"])) {
                    $newData["ProspectiveUser"]["bill_date"] = date("Y-m-d",strtotime($datos["datos_factura"]->Fecha));
                }

                $prospective = $this->ProspectiveUser->findById($newData['ProspectiveUser']['id']);

                $locked      = count($flujos) > 1 || count($productsData) > 1 ? 1 : $this->validateProducts($datos,$newData);
                $newData["ProspectiveUser"]["locked"] = $locked;
                if ($locked == 1) {
                    $newData["ProspectiveUser"]["date_locked"] = date("Y-m-d H:i:s");
                }

                $this->loadModel("ProductsLock");

                try {
                    $this->ProductsLock->updateAll( ["ProductsLock.state" => 2], ["ProductsLock.prospective_user_id" => $prospective["ProspectiveUser"]["id"]  ] );
                } catch (Exception $e) {
                    
                }

                if(!empty($prospective["ProspectiveUser"]["bill_code"])){
                    $this->loadModel("Salesinvoice");
                    $salesinvoiceData = $newData["ProspectiveUser"];
                    $salesinvoiceData["prospective_user_id"] = $salesinvoiceData["id"];
                    $salesinvoiceData["user_id"] = $salesinvoiceData["bill_user"];
                    unset($salesinvoiceData["id"]);
                    $this->Salesinvoice->create();
                    $this->Salesinvoice->save($salesinvoiceData);
                    if (!empty($datos) && isset($datos["productos_factura"]) && !empty($datos["productos_factura"])) {
                        $this->loadModel("QuotationsProduct");
                        foreach ($productsData as $keyProd => $dataValueProd) {
                            $this->QuotationsProduct->updateAll(["QuotationsProduct.biiled"=> $locked == 1 ? 1 : 2 ],["QuotationsProduct.id" => $dataValueProd->productos ]);
                        }
                    }

                }else{
                    $prospective["ProspectiveUser"]["bill_value"]       = floatval($newData["ProspectiveUser"]["bill_value"]);
                    $prospective["ProspectiveUser"]["bill_value_iva"]   = floatval($newData["ProspectiveUser"]["bill_value_iva"]);
                    $prospective["ProspectiveUser"]["bill_code"]        = $newData["ProspectiveUser"]["bill_code"];
                    $prospective["ProspectiveUser"]["bill_user"]        = $newData["ProspectiveUser"]["bill_user"];
                    $prospective["ProspectiveUser"]["bill_date"]        = $newData["ProspectiveUser"]["bill_date"];
                    $prospective["ProspectiveUser"]["bill_file"]        = $newData["ProspectiveUser"]["bill_file"];
                    $prospective["ProspectiveUser"]["bill_text"]        = $newData["ProspectiveUser"]["bill_text"];
                    $prospective["ProspectiveUser"]["status_bill"]      = 0;
                    $prospective["ProspectiveUser"]["locked"]           = $locked;
                    if ($locked == 1) {
                        $prospective["ProspectiveUser"]["date_locked"]  = date("Y-m-d H:i:s");
                    }
                    $prospective["ProspectiveUser"]["updated"]          = date("Y-m-d H:i:s");
                    if ($this->ProspectiveUser->save($prospective)) {
                        if (!empty($datos) && isset($datos["productos_factura"]) && !empty($datos["productos_factura"])) {
                            $this->loadModel("QuotationsProduct");
                            foreach ($productsData as $keyProd => $dataValueProd) {
                                $this->QuotationsProduct->updateAll(["QuotationsProduct.biiled"=> $locked == 1 ? 1 : 2 ],["QuotationsProduct.id" => $dataValueProd->productos ]);
                            }
                        }
                    }

                    $this->loadModel("Sender");
                    $this->loadModel("Config");
                    $this->Sender->create();
                    $tiempo = $this->Config->field("time_factura",["id" => 1]);
                    $fecha  = date("Y-m-d", strtotime("+".$tiempo." day"));
                    $this->Sender->save(["Sender"=>["prospective_user_id"=> $prospective["ProspectiveUser"]["id"], "quotation_id" => $prospective["ProspectiveUser"]["id"], "quiz_id" => 1, "deadline" => $fecha ]]);

                }

                if($locked == 1){

                    $this->loadModel("User");
                    $users = $this->User->role_gerencia_user();

                    foreach ($users as $key => $value) {
                        $emails[] = $value["User"]["email"];
                    }

                    $emails  = ["logistica@kebco.co"]; 

                    $subject = "Bloqueo de factura ".$bill_code;

                    $options = array(
                        'to'        => $emails,
                        'template'  => 'diference',
                        'subject'   => $subject,
                        'vars'      => array(
                            "flujo"  => $prospective["ProspectiveUser"]["id"]
                        )
                    );
                    
                    $this->sendMail($options);

                    $this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente, pero deberá ser revisada por generencía ya que se encontró diferencias en precios y/o productos cotizados'),'Flash/success'); 


                }else{
                    $this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente.'),'Flash/success');
                }
                $this->validateBillFinal($newData['ProspectiveUser']['id'],$newData["ProspectiveUser"]["bill_value"]);
            }
            return $this->redirect(["action"=>"carga_factura"]);
        }

        $usuarios_asesores  = ["Asesores KEBCO" => $this->ProspectiveUser->User->role_asesor_comercial_user(), "Asesores Externos" => $this->ProspectiveUser->User->find("list",["conditions" => ["User.role" => "Asesor Externo","User.state" => 1 ] ]) ];
        $this->set("usuarios_asesores",$usuarios_asesores);
    }

    public function get_document(){
        #2904 AND prefijo = 'KE';
        $this->layout = false;
        if (isset($this->request->data["prefijo"])) {
            $this->request->data["prefijo"] = strtoupper($this->request->data["prefijo"]);
        }
        $datos = $this->getDataDocument($this->request->data);
        if (!empty($datos)) {
            $datos = (array) $datos;
        }
        $this->set("datos",$datos);
    }

    public function get_document_new(){
        #2904 AND prefijo = 'KE';
        $this->layout = false;
        if (isset($this->request->data["prefijo"])) {
            $this->request->data["prefijo"] = strtoupper($this->request->data["prefijo"]);
        }
        $datos = $this->getDataDocument($this->request->data);
        if (!empty($datos)) {
            $datos = (array) $datos;
        }
        $this->set("datos",$datos);
    }

    public function save_document(){
        $this->autoRender = false;

        $params["number"]   = $this->request->data["ProspectiveUser"]["bill_code"];
        $params["prefijo"]  = $this->request->data["ProspectiveUser"]["bill_prefijo"];
        $datos              = (array) $this->getDataDocument($params);

        $bill_code = $this->request->data["ProspectiveUser"]["bill_prefijo"].= " ".$this->request->data["ProspectiveUser"]["bill_code"];

        $this->request->data["ProspectiveUser"]["bill_prefijo"].= " ".$this->request->data["ProspectiveUser"]["bill_code"];
        $this->request->data["ProspectiveUser"]["bill_code"]         = $bill_code;
        $this->request->data["ProspectiveUser"]["bill_file"]         = null;
        $this->request->data["ProspectiveUser"]["bill_text"]         = json_encode($datos);

        if (!empty($datos) && isset($datos["valores_factura"]) && !empty($datos["valores_factura"])) {
            foreach ($datos["valores_factura"] as $key => $value) {
                if (!is_null($value->IdClasificación)) {
                    $this->request->data["ProspectiveUser"]["bill_value"] = floatval($value->Crédito);
                }
                if (is_null($value->IdClasificación)) {
                    $this->request->data["ProspectiveUser"]["bill_value_iva"] = floatval($value->Débito);
                }
            }
        }else{
            $this->Session->setFlash('La información de la factura no esta completa','Flash/error');
            return $this->request->data['ProspectiveUser']['id'];
        }


        if (!empty($datos) && isset($datos["datos_factura"])) {
            $this->request->data["ProspectiveUser"]["bill_date"] = date("Y-m-d",strtotime($datos["datos_factura"]->Fecha));
        }

        $prospective = $this->ProspectiveUser->findById($this->request->data['ProspectiveUser']['id']);

        $locked      = $this->validateProducts($datos,$this->request->data);
        $this->request->data["ProspectiveUser"]["locked"] = $locked;
        if ($locked == 1) {
            $this->request->data["ProspectiveUser"]["date_locked"] = date("Y-m-d H:i:s");
        }

        if(!empty($prospective["ProspectiveUser"]["bill_code"])){
            $this->loadModel("Salesinvoice");
            $salesinvoiceData = $this->request->data["ProspectiveUser"];
            $salesinvoiceData["prospective_user_id"] = $salesinvoiceData["id"];
            $salesinvoiceData["user_id"] = $salesinvoiceData["bill_user"];
            unset($salesinvoiceData["id"]);
            $this->Salesinvoice->create();
            $this->Salesinvoice->save($salesinvoiceData);
            if (!empty($datos) && isset($datos["productos_factura"]) && !empty($datos["productos_factura"])) {
                $this->discountInventoryFromWo($datos["productos_factura"],$this->request->data["ProspectiveUser"]["id"]);
            }
        }else{
            $prospective["ProspectiveUser"]["bill_value"]       = floatval($this->request->data["ProspectiveUser"]["bill_value"]);
            $prospective["ProspectiveUser"]["bill_value_iva"]   = floatval($this->request->data["ProspectiveUser"]["bill_value_iva"]);
            $prospective["ProspectiveUser"]["bill_code"]        = $this->request->data["ProspectiveUser"]["bill_code"];
            $prospective["ProspectiveUser"]["bill_user"]        = $this->request->data["ProspectiveUser"]["bill_user"];
            $prospective["ProspectiveUser"]["bill_date"]        = $this->request->data["ProspectiveUser"]["bill_date"];
            $prospective["ProspectiveUser"]["bill_file"]        = $this->request->data["ProspectiveUser"]["bill_file"];
            $prospective["ProspectiveUser"]["bill_text"]        = $this->request->data["ProspectiveUser"]["bill_text"];
            $prospective["ProspectiveUser"]["status_bill"]      = 0;
            $prospective["ProspectiveUser"]["locked"]           = $locked;
            if ($locked == 1) {
                $prospective["ProspectiveUser"]["date_locked"]  = date("Y-m-d H:i:s");
            }
            $prospective["ProspectiveUser"]["updated"]          = date("Y-m-d H:i:s");
            if ($this->ProspectiveUser->save($prospective)) {
                if (!empty($datos) && isset($datos["productos_factura"]) && !empty($datos["productos_factura"])) {
                    $this->discountInventoryFromWo($datos["productos_factura"],$this->request->data["ProspectiveUser"]["id"]);
                }
            }
        }


        if($locked == 1){

            $this->loadModel("User");
            $users = $this->User->role_gerencia_user();

            foreach ($users as $key => $value) {
                $emails[] = $value["User"]["email"];
            }

            $subject = "Bloqueo de factura ".$bill_code;

            $options = array(
                'to'        => $emails,
                'template'  => 'diference',
                'subject'   => $subject,
                'vars'      => array(
                    "flujo"  => $prospective["ProspectiveUser"]["id"]
                )
            );
            
            $this->sendMail($options);

            $this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente, pero deberá ser revisada por generencía ya que se encontró diferencias en precios y/o productos cotizados'),'Flash/success'); 


        }else{
            $this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente.'),'Flash/success');
        }
        $this->validateBillFinal($this->request->data['ProspectiveUser']['id'],$this->request->data["ProspectiveUser"]["bill_value"]);
        return $this->request->data['ProspectiveUser']['id'];
    }

    public function action_bill_gr(){
        $this->autoRender = false;

        $flujos     = empty($this->request->data["id"]) ? [] : explode(",", $this->request->data["id"]);
        $invoices   = empty($this->request->data["invoice"]) ? [] : explode(",", $this->request->data["invoice"]);

        if (!empty($flujos)) {
            foreach ($flujos as $key => $value) {
                $datos = $this->ProspectiveUser->findById($value);
                $datos["ProspectiveUser"]["locked"] = $this->request->data["type"] == 3 ? -1 : 0;
                $this->ProspectiveUser->save($datos["ProspectiveUser"]);
                $user    = $datos["User"];
                $bill    = $datos["ProspectiveUser"]["id"];
                $factura = $datos["ProspectiveUser"]["bill_code"];


                $subject = "Cambio en la factura ".$factura;

                try {
                    $options = array(
                        'to'        => $user["email"],
                        'template'  => 'change_document',
                        'subject'   => $subject,
                        'vars'      => array(
                            "flujo"   => $bill,
                            "factura" => $factura,
                            "estado"  => $this->request->data["type"],
                            "mensaje" => $this->request->data["message"],
                        )
                    );
                    
                    $this->sendMail($options);
                } catch (Exception $e) {
                    
                }

                $estado     = $this->request->data["type"];
                $mensaje    = $this->request->data["message"];

                $this->loadModel("FlowStage");
                $this->loadModel("QuotationsProduct");

                $id_etapa_cotizado          = $this->FlowStage->id_latest_regystri_state_cotizado($value);
                $datosFlowstage             = $this->FlowStage->get_data($id_etapa_cotizado);

                if ($estado == 1) {
                    $concepto = "Aprobada";
                    $this->QuotationsProduct->updateAll(["QuotationsProduct.biiled" => 2], ["QuotationsProduct.biiled" => 1, "QuotationsProduct.quotation_id" => $datosFlowstage['FlowStage']['document'] ]);
                }elseif ($estado == 2) {
                    $concepto = "Aprobada con mensaje previo";
                    $this->QuotationsProduct->updateAll(["QuotationsProduct.biiled" => 2], ["QuotationsProduct.biiled" => 1, "QuotationsProduct.quotation_id" => $datosFlowstage['FlowStage']['document'] ]);
                }else{
                    $concepto = "Rechazada";
                    $this->QuotationsProduct->updateAll(["QuotationsProduct.biiled" => 0], ["QuotationsProduct.biiled" => 1, "QuotationsProduct.quotation_id" => $datosFlowstage['FlowStage']['document'] ]);
                }

                try {
                    $descNotification = "Cambio en la factura ".$factura."<br> <p>Se ha hecho un cambio en la factura <b>".$factura."</b> por el concepto de: <b>".$concepto."</b>.</p>";
                    if (in_array($estado, [2,3])) {
                        $descNotification.= " <p><br>Se ha enviado el siguiente mensaje por parte de gerencía.</p><p style='text-align: center'><b>".$mensaje."</b></p>"; 
                    }
                    $this->saveManagesUser($descNotification,24,$user["id"],null,null,Router::url("/",true)."prospectiveUsers/index/?q=".$bill,1);
                } catch (Exception $e) {
                    
                }
            }
        }

        if (!empty($invoices)) {
            foreach ($invoices as $key => $value) {
                $this->loadModel("Salesinvoice");
                $datos = $this->Salesinvoice->findById($value);
                $datos["Salesinvoice"]["locked"] = $this->request->data["type"] == 3 ? -1 : 0;
                $this->Salesinvoice->save($datos["Salesinvoice"]);
                $user    = $datos["User"];
                $bill    = $datos["Salesinvoice"]["prospective_user_id"];
                $factura = $datos["Salesinvoice"]["bill_code"];


                $subject = "Cambio en la factura ".$factura;

                try {
                    $options = array(
                        'to'        => $user["email"],
                        'template'  => 'change_document',
                        'subject'   => $subject,
                        'vars'      => array(
                            "flujo"   => $bill,
                            "factura" => $factura,
                            "estado"  => $this->request->data["type"],
                            "mensaje" => $this->request->data["message"],
                        )
                    );
                    
                    $this->sendMail($options);
                } catch (Exception $e) {
                    
                }

                $estado     = $this->request->data["type"];
                $mensaje    = $this->request->data["message"];

                if ($estado == 1) {
                    $concepto = "Aprobada";
                }elseif ($estado == 2) {
                    $concepto = "Aprobada con mensaje previo";
                }else{
                    $concepto = "Rechazada";
                }

                try {
                    $descNotification = "Cambio en la factura ".$factura."<br> <p>Se ha hecho un cambio en la factura <b>".$factura."</b> por el concepto de: <b>".$concepto."</b>.</p>";
                    if (in_array($estado, [2,3])) {
                        $descNotification.= " <p><br>Se ha enviado el siguiente mensaje por parte de gerencía.</p><p style='text-align: center'><b>".$mensaje."</b></p>"; 
                    }
                    $this->saveManagesUser($descNotification,24,$user["id"],null,null,Router::url("/",true)."prospectiveUsers/index/?q=".$bill,1);
                } catch (Exception $e) {
                    
                }
            }
        }

        $this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente.'),'Flash/success');

    }

    public function facturas_bloqueadas($return = false) {
        $this->loadModel("Salesinvoice");
        $this->ProspectiveUser->hasAndBelongsToMany = [];
        // $this->ProspectiveUser->hasMany = [];
        $this->ProspectiveUser->unBindModel(["belongsTo"=>["Import"]]);
        $prospective_users = $this->ProspectiveUser->findAllByLocked(1);
        $salesinvoices     = $this->Salesinvoice->findAllByLocked(1);
        $datosFinales      = [];
        if (!empty($prospective_users)) {
            foreach ($prospective_users as $key => $value) {
                $datosFinales[trim($value["ProspectiveUser"]["bill_code"])] = [];
                $factValue = (array) json_decode($value["ProspectiveUser"]["bill_text"]);
                if (!isset($factValue["datos_factura"]->Identificacion)) {
                    $codeParam            = explode(" ", $value["ProspectiveUser"]["bill_code"]);
                    if (count($codeParam) == 2) {
                        $newData              = (array) $this->getDataDocument(["number"=>trim($codeParam[1]), "prefijo"=> trim($codeParam[0]) ]);
                        if (!empty($newData)) {
                            $prospective_users[$key]["ProspectiveUser"]["bill_text"] = json_encode($newData);
                        }
                    }
                }

                $prospective_users[$key]["ProspectiveUser"]["valor"] = $this->ProspectiveUser->FlowStage->valor_latest_regystri_state_cotizado_flujo_id($value["ProspectiveUser"]["id"]);
                $flowstage_quotatiob_id     = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($value["ProspectiveUser"]["id"]);
                $flowstageDatos             = $this->ProspectiveUser->FlowStage->get_data($flowstage_quotatiob_id);
                $cotiacion_id               = $flowstageDatos['FlowStage']['document'];
                $numero_productos           = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->total_data_quotation_products($cotiacion_id);
                $prospective_users[$key]["ProspectiveUser"]["totalProds"] = $numero_productos;
                $prospective_users[$key]["Valores"] = $this->getValueFact($factValue,$value["ProspectiveUser"]["id"]);
            }
        }

        if (!empty($salesinvoices)) {
            foreach ($salesinvoices as $key => $value) {
                $datosFinales[trim($value["Salesinvoice"]["bill_code"])] = [];
                $factValue = (array) json_decode($value["Salesinvoice"]["bill_text"]);
                if (!isset($factValue["datos_factura"]->Identificacion)) {
                    $codeParam            = explode(" ", $value["Salesinvoice"]["bill_code"]);
                    if (count($codeParam) == 2) {
                        $newData              = (array) $this->getDataDocument(["number"=>trim($codeParam[1]), "prefijo"=> trim($codeParam[0]) ]);
                        if (!empty($newData)) {
                            $salesinvoices[$key]["Salesinvoice"]["bill_text"] = json_encode($newData);
                        }
                    }
                }

                $salesinvoices[$key]["ProspectiveUser"]["valor"] = $this->ProspectiveUser->FlowStage->valor_latest_regystri_state_cotizado_flujo_id($value["ProspectiveUser"]["id"]);
                $flowstage_quotatiob_id     = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($value["ProspectiveUser"]["id"]);
                $flowstageDatos             = $this->ProspectiveUser->FlowStage->get_data($flowstage_quotatiob_id);
                $cotiacion_id               = $flowstageDatos['FlowStage']['document'];
                $numero_productos           = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->total_data_quotation_products($cotiacion_id);
                $salesinvoices[$key]["ProspectiveUser"]["totalProds"] = $numero_productos;
                $salesinvoices[$key]["Valores"] = $this->getValueFact($factValue,$value["ProspectiveUser"]["id"]);
            }
        }


        foreach ($prospective_users as $key => $value) {
            $value["Salesinvoice"] = [];
            $value["OTROS"] = $this->getOthersFacts($value["ProspectiveUser"]["id"],trim($value["ProspectiveUser"]["bill_code"]));
            $datosFinales[trim($value["ProspectiveUser"]["bill_code"])][] = $value;
        }

        foreach ($salesinvoices as $key => $value) {
            $value["OTROS"] = $this->getOthersFacts($value["ProspectiveUser"]["id"],trim($value["Salesinvoice"]["bill_code"]));
            $datosFinales[trim($value["Salesinvoice"]["bill_code"])][] = $value;
        }


        if($return){
            return $datosFinales;
        }

        $this->set(compact("datosFinales")); 
    }

    

    private function discountInventoryFromWo($products,$flujo){

        $id_etapa_cotizado          = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($flujo);
        $datosFlowstage             = $this->ProspectiveUser->FlowStage->get_data($id_etapa_cotizado);
        if (is_numeric($datosFlowstage['FlowStage']['document'])) {
            $quotation = $datosFlowstage['FlowStage']['document'];
            $this->loadModel("Product");
            foreach ($products as $key => $value) {
                $productId = $this->Product->field("id",["LOWER(part_number)" => strtolower($value->CódigoInventario) ]);
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
                    $productData[0]["QuotationsProduct"]["biiled"] = 1;
                    $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->save($productData[0]["QuotationsProduct"]);
                }elseif (count($productData) > 1 && $totalQuantity == intval($value->Cantidad)) {
                    foreach ($productData as $keyProduct => $valueProduct) {
                        $valueProduct["QuotationsProduct"]["biiled"] = 1;
                        $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->save($productData["QuotationsProduct"]);
                    }
                }else{
                    foreach ($productData as $keyProduct => $valueProduct) {
                        if ($valueProduct["QuotationsProduct"]["quantity"] == intval($value->Cantidad)) {
                            $valueProduct["QuotationsProduct"]["biiled"] = 1;
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
                        $valueBloqueo["ProductsLock"]["state"] = 2;
                        $valueBloqueo["ProductsLock"]["unlock_date"] = date("Y-m-d H:i:s");
                        $this->ProductsLock->save($valueBloqueo);
                    }
                }              
            }

        }
    }

	public function saveBillInformation(){
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			if ($this->request->data['ProspectiveUser']['bill_file']['name'] != '') {
				$documento 												= $this->loadDocumentPdf($this->request->data['ProspectiveUser']['bill_file'],'flujo/facturas');
				$this->request->data['ProspectiveUser']['bill_file'] 	= $this->Session->read('documentoModelo');
			} else {
				$documento = 4;
			}
			if ($documento == 1) {				
				$this->request->data["ProspectiveUser"]["bill_value"] = $this->replaceText($this->request->data["ProspectiveUser"]["bill_value"],".", "");
                $this->ProspectiveUser->recursive = -1;
				$prospective = $this->ProspectiveUser->findById($this->request->data['ProspectiveUser']['id']);

                if(!empty($prospective["ProspectiveUser"]["bill_code"])){
                    $this->loadModel("Salesinvoice");
                    $salesinvoiceData = $this->request->data["ProspectiveUser"];
                    $salesinvoiceData["prospective_user_id"] = $salesinvoiceData["id"];
                    $salesinvoiceData["user_id"] = $salesinvoiceData["bill_user"];
                    unset($salesinvoiceData["id"]);
                    $this->Salesinvoice->create();
                    $this->Salesinvoice->save($salesinvoiceData);
                    if( (isset($this->request->data["warehouse_bog"]) && !empty($this->request->data["warehouse_bog"])) || isset($this->request->data["warehouse_med"]) && !empty($this->request->data["warehouse_med"]) ){
                        $this->discountInventory($this->request->data);
                    }
                }else{
                    $prospective["ProspectiveUser"]["bill_value"]       = floatval($this->request->data["ProspectiveUser"]["bill_value"]);
                    $prospective["ProspectiveUser"]["bill_value_iva"]   = floatval($this->request->data["ProspectiveUser"]["bill_value_iva"]);
                    $prospective["ProspectiveUser"]["bill_code"]        = $this->request->data["ProspectiveUser"]["bill_code"];
                    $prospective["ProspectiveUser"]["bill_user"]        = $this->request->data["ProspectiveUser"]["bill_user"];
                    $prospective["ProspectiveUser"]["bill_date"]        = $this->request->data["ProspectiveUser"]["bill_date"];
                    $prospective["ProspectiveUser"]["bill_file"]        = $this->request->data["ProspectiveUser"]["bill_file"];
                    $prospective["ProspectiveUser"]["status_bill"]      = 0;
                    $prospective["ProspectiveUser"]["updated"]          = date("Y-m-d H:i:s");
                    if ($this->ProspectiveUser->save($prospective)) {
                        $this->discountInventory($this->request->data);
                        $this->Session->setFlash(__('Los datos de la factura fueron guardados correctamente'),'Flash/success'); 
                    }
                }

                if(isset($this->request->data["ProspectiveUser"]["qt_noiva"])){

                    if( ( $this->request->data["ProspectiveUser"]["qt_noiva"] != $this->request->data["ProspectiveUser"]["bill_value"] ) || ( $this->request->data["ProspectiveUser"]["qt_iva"] != $this->request->data["ProspectiveUser"]["bill_value_iva"] ) ){

                        $this->loadModel("User");
                        $users = $this->User->role_gerencia_user();

                        foreach ($users as $key => $value) {
                            $emails[] = $value["User"]["email"];
                        }

                        $subject = "Diferencia de valores cotizados y facturados CRM ".$prospective["ProspectiveUser"]["id"];

                        $options = array(
                            'to'        => $emails,
                            'template'  => 'diference',
                            'subject'   => $subject,
                            'vars'      => array(
                                "valorNoIvaFactura" => $this->request->data["ProspectiveUser"]["bill_value"],
                                "valorIvaFactura" => $this->request->data["ProspectiveUser"]["bill_value_iva"],
                                "valorNoIvaQT" => $this->request->data["ProspectiveUser"]["qt_noiva"],
                                "valorIvaQt" => $this->request->data["ProspectiveUser"]["qt_iva"],
                                "flujo"  => $prospective["ProspectiveUser"]["id"]
                            )
                        );
                        
                        $this->sendMail($options);

                    }

                }

				return $this->request->data['ProspectiveUser']['id'];
			} else {
				return $documento;
			}
		}

	}

	private function discountInventory($datos){
        $this->loadModel("ProductsLock");   
        if(isset($datos["warehouse_bog"]) && !empty($datos["warehouse_bog"])){
            foreach ($datos["warehouse_bog"] as $key => $value) {
                $productData = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->findById($key);
                $productData["QuotationsProduct"]["biiled"] = 1;
                    $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->save($productData["QuotationsProduct"]);

                $this->loadModel("Product");

                $product = array("Product" => $productData["Product"]);

                $productInventoryData = $this->getTotalInventoryProduct($productData["Product"]["id"]);
                
                if( (intval($productInventoryData["quantity_bog"]) - intval($productData["QuotationsProduct"]["quantity"]) < 0 ) ){
                    // continue;
                }

                $product["Product"]["quantity_bog"] = intval($product["Product"]["quantity_bog"]) - intval($productData["QuotationsProduct"]["quantity"]);
                $this->loadModel("Inventory");
                $this->Inventory->Product->save(array("Product" => $product["Product"]));
                $inventory = array(
                    "Inventory" => array(
                        "product_id"    => $product["Product"]["id"],
                        "quantity"      => $productData["QuotationsProduct"]["quantity"],
                        "warehouse"     => "Bogotá",
                        "type"          => Configure::read("TYPES_MOVEMENT.SALIDA_INVENTARIO"),
                        "type_movement" => Configure::read("INVENTORY_TYPE.SALIDA_VENTA_NORMAL"),
                        "reason"        => Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.SALIDA_VENTA_NORMAL")),
                        "user_id"       => AuthComponent::user("id"),
                        "prospective_user_id" => $datos["ProspectiveUser"]["id"]
                    )
                );
                $this->Inventory->create();
                $this->Inventory->save($inventory);

                $bloqueos = $this->ProductsLock->find("all",["recursive" => -1, "conditions" => ['ProductsLock.state' => 1, 'ProductsLock.product_id' => $product["Product"]["id"], 'ProductsLock.prospective_user_id' => $datos["ProspectiveUser"]["id"]]]);

                if(!empty($bloqueos)){
                    foreach ($bloqueos as $keyBloqueo => $valueBloqueo) {
                        $valueBloqueo["ProductsLock"]["state"] = 2;
                        $valueBloqueo["ProductsLock"]["unlock_date"] = date("Y-m-d H:i:s");
                        $this->ProductsLock->save($valueBloqueo);
                    }
                }              

            }
        }

        if(isset($datos["warehouse_med"]) && !empty($datos["warehouse_med"])){
            foreach ($datos["warehouse_med"] as $key => $value) {
                $productData = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->findById($key);
                $productData["QuotationsProduct"]["biiled"] = 1;
                $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->save($productData["QuotationsProduct"]);

                $this->loadModel("Product");

                $product = array("Product" => $productData["Product"]);

                $productInventoryData = $this->getTotalInventoryProduct($productData["Product"]["id"]);

                if( (intval($productInventoryData["quantity"]) - intval($productData["QuotationsProduct"]["quantity"]) < 0 ) ){
                    // continue;
                }                    

                $product["Product"]["quantity"] = intval($product["Product"]["quantity"]) - intval($productData["QuotationsProduct"]["quantity"]);

                $this->loadModel("Inventory");

                $this->Inventory->Product->save(array("Product" => $product["Product"]));
                $inventory = array(
                    "Inventory" => array(
                        "product_id"    => $product["Product"]["id"],
                        "quantity"      => $productData["QuotationsProduct"]["quantity"],
                        "warehouse"     => "Medellín",
                        "type"          => Configure::read("TYPES_MOVEMENT.SALIDA_INVENTARIO"),
                        "type_movement" => Configure::read("INVENTORY_TYPE.SALIDA_VENTA_NORMAL"),
                        "reason"        => Configure::read("INVENTORY_TYPE_REASON.".Configure::read("INVENTORY_TYPE.SALIDA_VENTA_NORMAL")),
                        "user_id"       => AuthComponent::user("id"),
                        "prospective_user_id" => $datos["ProspectiveUser"]["id"]
                    )
                );

                $this->Inventory->create();
                $this->Inventory->save($inventory);
                
                $bloqueos = $this->ProductsLock->find("all",["recursive" => -1, "conditions" => ['ProductsLock.state' => 1, 'ProductsLock.product_id' => $product["Product"]["id"], 'ProductsLock.prospective_user_id' => $datos["ProspectiveUser"]["id"]]]);
                if(!empty($bloqueos)){
                    foreach ($bloqueos as $keyBloqueo => $valueBloqueo) {
                        $valueBloqueo["ProductsLock"]["state"] = 2;
                        $valueBloqueo["ProductsLock"]["unlock_date"] = date("Y-m-d H:i:s");
                        $this->ProductsLock->save($valueBloqueo);
                    }
                }

            }
        }
	}

	public function filtro_null_index(){
		$conditions					= array();
		$count_asignado 			= $this->ProspectiveUser->count_flow_asignado($conditions);
	    $count_contactado 			= $this->ProspectiveUser->count_flow_contactado($conditions);
	    $count_cotizado 			= $this->ProspectiveUser->count_flow_cotizado($conditions);
	    $count_negociado 			= $this->ProspectiveUser->count_flow_negociado($conditions);
	    $count_pagado 				= $this->ProspectiveUser->count_flow_pagado($conditions);
	    $count_despachado 			= $this->ProspectiveUser->count_flow_despachado($conditions);
	    $count_cancelado 			= $this->ProspectiveUser->count_flow_cancelado($conditions);
	    $count_todo_habilitado 		= $count_asignado + $count_contactado + $count_cotizado + $count_negociado + $count_pagado + $count_despachado;
	    $count_terminados 			= $this->ProspectiveUser->count_flow_terminado($coditionsC = array());
	    $usuariosAsesores 		 	= $this->ProspectiveUser->User->role_asesor_comercial_users_all_true();
	    $this->set(compact('count_asignado','count_contactado','count_cotizado','count_negociado','count_pagado','count_despachado','count_todo_habilitado','count_cancelado','count_terminados','usuariosAsesores'));
	}

	public function adviser(){
        $this->validateTimes(true);
		$get 						= $this->request->query;
		$conditionsUser 			= array('ProspectiveUser.user_id' => AuthComponent::user('id'),'ProspectiveUser.type' => 0);
		if (!empty($get)) {
			if (isset($get['q'])) {
				$conditions 		= $this->searchUser($get['q'],$conditions = array('ProspectiveUser.user_id' => AuthComponent::user('id'),'ProspectiveUser.type' => 0)); 
			} else {
				if (isset($get['filterEtapa'])) {
					$conditions 	= $this->filterUser($get['filterEtapa'],$conditions1 = array('ProspectiveUser.user_id' => AuthComponent::user('id'),'ProspectiveUser.type' => 0));
				}
			}
            if(isset($get["fechaInicioReporte"]) && isset($get["fechaFinReporte"])){
                $conditions["DATE(ProspectiveUser.created) >="] = $get["fechaInicioReporte"];
                $conditions["DATE(ProspectiveUser.created) <="] = $get["fechaFinReporte"];
                $this->set("fechaInicioReporte",$get["fechaInicioReporte"]);
                $this->set("fechaFinReporte",$get["fechaFinReporte"]);
            }
            $conditions["ProspectiveUser.user_id"] = AuthComponent::user('id');
		} else {
			$conditions 		= array('ProspectiveUser.user_id' => AuthComponent::user('id'),'ProspectiveUser.state_flow <' => Configure::read('variables.control_flujo.flujo_cancelado'),'ProspectiveUser.type' => 0);
		}

        $noPayment = $this->ProspectiveUser->getNoSend($conditions,$get);
        if(isset($get["filterEtapa"]) && $get["filterEtapa"] == 56 && !empty($noPayment)){
            $conditions["ProspectiveUser.id"] = $noPayment;
        }

		$recursive				= 1;
		$limit					= 6;
		$order					= array('ProspectiveUser.id' => 'desc');
		$this->paginate 		= array(
								        'limit' 		=> $limit,
								        'recursive' 	=> $recursive,
								        'order' 		=> $order,
								        'conditions'	=> $conditions
								    );
		$this->ProspectiveUser->unBindModel(array("belongsTo" => array("User","ContacsUser","ClientsNatural"),"hasMany" => array("FlowStage","FlowStagesProduct","AtentionTime")));
        try {
	       $prospectiveUsers 		= $this->paginate('ProspectiveUser');            
        } catch (Exception $e) {
            $prospectiveUsers       = array();
        }
	    // if (count($prospectiveUsers) < 1) {
	    // 	$this->redirect(array('action' => 'filtro_null_adviser'));
	    // }
	    $count_asignado 			= $this->ProspectiveUser->count_flow_asignado($conditionsUser);
	    $count_contactado 			= $this->ProspectiveUser->count_flow_contactado($conditionsUser);
	    $count_cotizado 			= $this->ProspectiveUser->count_flow_cotizado($conditionsUser);
	    $count_negociado 			= $this->ProspectiveUser->count_flow_negociado($conditionsUser);
	    $count_pagado 				= $this->ProspectiveUser->count_flow_pagado($conditionsUser);
        $count_pagadoNoDes          = count($noPayment);
	    $count_despachado 			= $this->ProspectiveUser->count_flow_despachado($conditionsUser);
	    $count_cancelado 			= $this->ProspectiveUser->count_flow_cancelado($conditionsUser);
	    $count_terminados 			= $this->ProspectiveUser->count_flow_terminado($conditionsUser);
	    $count_todo_habilitado 		= $count_asignado + $count_contactado + $count_cotizado + $count_negociado + $count_pagado + $count_despachado;
	    $usuariosAsesores          = $this->ProspectiveUser->User->role_asesor_comercial_users_all_true(true);
        $usuarios_asesores          = $this->ProspectiveUser->User->role_asesor_comercial_user_true(true);
	    $this->set(compact('prospectiveUsers','count_asignado','count_contactado','count_cotizado','count_negociado','count_pagado','count_despachado','count_cancelado','count_todo_habilitado','count_terminados','usuarios_asesores','count_pagadoNoDes'));
	}

	public function filtro_null_adviser(){
		$conditionsUser 			= array('ProspectiveUser.user_id' => AuthComponent::user('id'),'ProspectiveUser.type' => 0);
		$count_asignado 			= $this->ProspectiveUser->count_flow_asignado($conditionsUser);
	    $count_contactado 			= $this->ProspectiveUser->count_flow_contactado($conditionsUser);
	    $count_cotizado 			= $this->ProspectiveUser->count_flow_cotizado($conditionsUser);
	    $count_negociado 			= $this->ProspectiveUser->count_flow_negociado($conditionsUser);
	    $count_pagado 				= $this->ProspectiveUser->count_flow_pagado($conditionsUser);
	    $count_despachado 			= $this->ProspectiveUser->count_flow_despachado($conditionsUser);
	    $count_cancelado 			= $this->ProspectiveUser->count_flow_cancelado($conditionsUser);
	    $count_terminados 			= $this->ProspectiveUser->count_flow_terminado($conditionsUser);
	    $count_todo_habilitado 		= $count_asignado + $count_contactado + $count_cotizado + $count_negociado + $count_pagado + $count_despachado;
	    $this->set(compact('count_asignado','count_contactado','count_cotizado','count_negociado','count_pagado','count_despachado','count_todo_habilitado','count_cancelado','count_terminados'));
	}

    public function update_flow_gest() {
        $this->autoRender = false;

        $datosNota['ProgresNote']['description']                = $this->request->data["ProgresNote"]['description'];
        $datosNota['ProgresNote']['etapa']                      = $this->request->data["ProgresNote"]['etapa'];
        $datosNota['ProgresNote']['prospective_users_id']       = $this->request->data["ProgresNote"]['flujo_id'];
        $datosNota['ProgresNote']['user_id']                    = AuthComponent::user('id');

        if (isset($this->request->data['ProgresNote']['image']) && !empty($this->request->data['ProgresNote']['image']["name"])) {
            $documento = $this->loadPhoto($this->request->data['ProgresNote']['image'],'flujo/contactado');
            $datosNota['ProgresNote']['image'] = $this->Session->read('imagenModelo');                
        }

        $type = $this->request->data["ProgresNote"]["type"];
        unset($this->request->data["ProgresNote"]["type"]);
        $infoFlow = $this->ProspectiveUser->findById($this->request->data["ProgresNote"]["flujo_id"]);

        if ($type == "alert") {
            $infoFlow["ProspectiveUser"]["alert_one"]        = 1;
            $infoFlow["ProspectiveUser"]["date_final_alert"] = date("Y-m-d",strtotime("+".Configure::read("DIAS_NOTIFY_TWO")." day"));
            $infoFlow["ProspectiveUser"]["notified"]         = 0;
        }elseif ($type == "alert_two") {
            $infoFlow["ProspectiveUser"]["alert_two"]     = 1;
            $infoFlow["ProspectiveUser"]["date_prorroga"] = date("Y-m-d",strtotime("+".Configure::read("DIAS_PRORROGA_ONE")." day"));
            $infoFlow["ProspectiveUser"]["notified"]      = 0;
        }elseif ($type == "prorroga") {
            $infoFlow["ProspectiveUser"]["prorroga_one"]    = 1;
            $infoFlow["ProspectiveUser"]["notified"]        = 0;
            $infoFlow["ProspectiveUser"]["valid"]           = 1;

            $infoFlow["ProspectiveUser"]["prorroga_one"]    = 0;
            $infoFlow["ProspectiveUser"]["notified"]        = 0;
            $infoFlow["ProspectiveUser"]["date_prorroga"]   = $this->request->data["ProgresNote"]["date_prorroga_final"];
        }

        $infoFlow["ProspectiveUser"]["deadline_notified"] = null;
    
        $this->Session->setFlash('Gestión realizada correctamente', 'Flash/success');
        $this->ProspectiveUser->save($infoFlow["ProspectiveUser"]);
        $this->ProspectiveUser->ProgresNote->save($datosNota);


        $this->redirect(["action" => "index", "?" => ["q" => $infoFlow["ProspectiveUser"]["id"] ] ]);

    }

	public function add_nota(){
		$this->layout   			= false;
        if ($this->request->is('ajax')) {
        	$flujo_id 				= $this->request->data['flujo_id'];
        	$etapa 					= $this->request->data['etapa'];
			$this->set(compact('flujo_id','etapa'));
        }
	}

	public function saveNotas(){
		$this->autoRender   								= false;
        $datos['ProgresNote']['description'] 				= $this->request->data['description'];
        $datos['ProgresNote']['etapa'] 						= $this->request->data['etapa'];
        $datos['ProgresNote']['prospective_users_id'] 		= $this->request->data['flujo_id'];
        $datos['ProgresNote']['user_id'] 					= AuthComponent::user('id');
		$this->ProspectiveUser->ProgresNote->save($datos);
		return true;
	}

	public function list_notas(){
		$this->layout   		= false;
        if ($this->request->is('ajax')) {
        	$flujo_id 			= $this->request->data['flujo_id'];
        	$datos 				= $this->ProspectiveUser->ProgresNote->get_notes_flujo($flujo_id);
			$this->set(compact('datos'));
        }
	}

	public function updateuserAsignado($call_action = false){
		$this->autoRender   								= false;
        if ($this->request->is('ajax') || $this->request->is("post") || $call_action ) {
            $old_user                                       = isset($this->request->data["user_id"]) ? $this->request->data["user_id"] : AuthComponent::user("id");
            if (AuthComponent::user("email") == "ventas@kebco.co") {
                $old_user = 112;
            }

			$flujo_id 										= $this->request->data['flujo_id'];
			$user_id 										= $this->request->data['asesor'];
			$datosP['ProspectiveUser']['id'] 				= $flujo_id;
			$datosP['ProspectiveUser']['user_id'] 			= $user_id;
			$datosUserSession 								= $this->ProspectiveUser->User->get_data($old_user);
			$datosUserAsesor 								= $this->ProspectiveUser->User->get_data($user_id);
			$datosF['FlowStage']['state_flow']				= Configure::read('variables.nombre_flujo.asignado_flujo_proceso');
			$datosF['FlowStage']['prospective_users_id']	= $flujo_id;
			$datosF['FlowStage']['description']				= 'El asesor '.$datosUserSession['User']['name'].' asigno al asesor '.$datosUserAsesor['User']['name'].' para el flujo en proceso';

            $dataFlowInfo = $this->ProspectiveUser->find("first",["recursive" => -1, "conditions" => ["id" => $flujo_id ]  ]);

            if ( !is_null($dataFlowInfo["ProspectiveUser"]["user_lose"]) && $dataFlowInfo["ProspectiveUser"]["user_lose"] == $user_id) {
                $datosP["ProspectiveUser"]["returned"] = 1;
                $datosP["ProspectiveUser"]["user_lose"] = $old_user;
            }

			$this->ProspectiveUser->FlowStage->create();
			if ($this->ProspectiveUser->FlowStage->save($datosF)){
				$this->ProspectiveUser->save($datosP);
				$this->saveDataLogsUser(12,'ProspectiveUser',$flujo_id);
				$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.asesor_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosUserAsesor['User']['id'],$flujo_id,$old_user,$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
				$url 		= 'prospectiveUsers/adviser?q='.$flujo_id;
				$options = array(
					'to'		=> $datosUserAsesor['User']['email'],
					'template'	=> 'modify_adviser',
					'subject'	=> 'Te han asignado un flujo en proceso',
					'vars'		=> array('datosUserAsesor' => $datosUserAsesor, 'datosUserSession' => $datosUserSession,'url' => $url),
				);
				$this->sendMail($options);
			}
        }
	}

	public function adviser_dashboard($user_id = null){
		$this->layout 		= 'agenda';

        $user_id = is_null($user_id) ? AuthComponent::user("id") : $user_id;

        $this->loadModel("Quotation");

		$conditions 		= array('ProspectiveUser.state_flow' => 3, 'DATE(ProspectiveUser.created) >=' => date("Y-m-d", strtotime("-30 days")), 'DATE(ProspectiveUser.created) <=' => date("Y-m-d"),"user_id" => $user_id );

        $prospectiveUsers   = $this->ProspectiveUser->find("all",["conditions" => $conditions, "recursive" => -1, "limit" => 50,  ]);
        $prospectos         = array();

        $this->loadModel("Config");
        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $this->set("trmActual",$trmActual);
        $this->set("ajuste",$config["Config"]["ajusteTrm"]);

        foreach ($prospectiveUsers as $key => $value) {
            $total = 0;
            $id_etapa_cotizado          = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($value["ProspectiveUser"]["id"]);
            $datosFlowstage             = $this->ProspectiveUser->FlowStage->field("document",["id"=>$id_etapa_cotizado]);

            if (is_numeric($datosFlowstage)) {

                $productosCotizacion     = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->find("all",["conditions" => ["quotation_id" => $datosFlowstage], "fields" => ["price","currency","quantity"] ]);

                foreach ($productosCotizacion as $key => $quotationProduct) {
                    $precio = $quotationProduct["QuotationsProduct"]["currency"] == "usd" ? $quotationProduct["QuotationsProduct"]["price"] * $trmActual : doubleval($quotationProduct["QuotationsProduct"]["price"]);
                    $total += ($precio * $quotationProduct["QuotationsProduct"]["quantity"]);
                }
                $value["ProspectiveUser"]["total"] = $total;
                $value["ProspectiveUser"]["cotizacion"] = $datosFlowstage;
                $prospectos[] = $value["ProspectiveUser"];
                
            }
        }

        usort($prospectos, function($a, $b) {
            return $b['total'] - $a['total'];
        });

	    $this->set(compact('prospectos'));
	}

    public function clearIndicativePhone($phone){
        $indicativos_america = array(
            "Antigua y Barbuda" => "+1-268",
            "Argentina" => "+54",
            "Bahamas" => "+1-242",
            "Barbados" => "+1-246",
            "Belice" => "+501",
            "Bolivia" => "+591",
            "Brasil" => "+55",
            "Canadá" => "+1",
            "Chile" => "+56",
            "Colombia" => "+57",
            "Costa Rica" => "+506",
            "Cuba" => "+53",
            "Dominica" => "+1-767",
            "República Dominicana" => "+1-809, +1-829, +1-849",
            "Ecuador" => "+593",
            "El Salvador" => "+503",
            "Granada" => "+1-473",
            "Guatemala" => "+502",
            "Guyana" => "+592",
            "Haití" => "+509",
            "Honduras" => "+504",
            "Jamaica" => "+1-876",
            "México" => "+52",
            "Nicaragua" => "+505",
            "Panamá" => "+507",
            "Paraguay" => "+595",
            "Perú" => "+51",
            "San Cristóbal y Nieves" => "+1-869",
            "Santa Lucía" => "+1-758",
            "San Vicente y las Granadinas" => "+1-784",
            "Surinam" => "+597",
            "Trinidad y Tobago" => "+1-868",
            "Estados Unidos" => "+1",
            "Uruguay" => "+598",
            "Venezuela" => "+58"
            // Puedes agregar más indicativos de otros países según sea necesario
        );

        $indicativos_america = array_values($indicativos_america);
        return str_replace($indicativos_america, "",$phone);
    }

    public function asign_customer_flujo_especial(){
        $this->autoRender = false;

        $this->ProspectiveUser->recursive = -1;

        $prospecto = $this->ProspectiveUser->findById($this->request->data["id"]);
        $customer  = [];

        if($this->request->data["contacto"] == "0"){
            $prospecto["ProspectiveUser"]["clients_natural_id"] = $this->request->data["cliente"];
            $prospecto["ProspectiveUser"]["contacs_users_id"]   = 0;

            if(in_array(AuthComponent::user("role"), ["Gerente General","Logística"])){
                $this->ProspectiveUser->save($prospecto);
            }

            $customer = $this->ProspectiveUser->ClientsNatural->findById($this->request->data["cliente"]);
            if(!empty($customer)){
                $customer = $customer["ClientsNatural"];
            }
        }else{
            $prospecto["ProspectiveUser"]["contacs_users_id"] = $this->request->data["contacto"];
            $prospecto["ProspectiveUser"]["clients_natural_id"] = null;
            
            if(in_array(AuthComponent::user("role"), ["Gerente General","Logística"])){
                $this->ProspectiveUser->save($prospecto);
            }
            
            $customer = $this->ProspectiveUser->ContacsUser->findById($this->request->data["contacto"]);
            if(!empty($customer)){
                $customer = $customer["ContacsUser"];
            }
        }

        if(!empty($prospecto["ProspectiveUser"]["phone_customer"])){

            $phoneCustomer = $this->clearIndicativePhone($prospecto["ProspectiveUser"]["phone_customer"]);
            $posPhoneOne   = strpos($customer["telephone"], $phoneCustomer);
            $posPhoneTwo   = strpos($customer["cell_phone"], $phoneCustomer);

            if( $posPhoneOne === false &&  $posPhoneTwo === false ){
                $this->Session->setFlash(__('El cliente asociado no coincide con el celular del chat'),'Flash/error');
            }else{
                $this->ProspectiveUser->save($prospecto);
                $this->Session->setFlash(__('Flujo asignado con exíto'),'Flash/success');
            }
        }else{
            $this->ProspectiveUser->save($prospecto);
                $this->Session->setFlash(__('Flujo asignado con exíto'),'Flash/success');
        }        
    }

	public function get_data_natural(){
		$this->layout 			= false;
		if ($this->request->is('ajax')) {
			$flujo_id 			= $this->request->data['flujo_id'];
			$datosP				= $this->ProspectiveUser->get_data($this->request->data['flujo_id']);
			$datos 				= $this->ProspectiveUser->ClientsNatural->get_data($datosP['ProspectiveUser']['clients_natural_id']);
			$this->set(compact('datos','datosP','flujo_id'));
		}
	}

    public function receipts() {

    }

    public function info_receipts(){
        $this->layout = false;
        $this->loadModel("Receipt");
        $this->Receipt->unBindModel(array("belongsTo"=>"ProspectiveUser"));
        $recibosCaja  = $this->Receipt->findAllByProspectiveUserId($this->request->data['id']);
        $datosProspecto = $this->ProspectiveUser->findById($this->request->data['id']);

        $totalActual    = is_null($this->Receipt->field("SUM(total) as totalNoIva",array("prospective_user_id"=> $this->request->data['id']))) ? 0 : $this->Receipt->field("SUM(total) as totalNoIva",array("prospective_user_id"=> $this->request->data['id']));
            $idFlowstage                    = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($this->request->data['id']);
            $valorQuotation                 = $this->ProspectiveUser->FlowStage->valor_latest_regystri_state_cotizado_flujo_id($this->request->data['id']);

        $this->set(compact("recibosCaja","datosProspecto","totalActual","valorQuotation"));
    }


	public function get_data_juridica(){
		$this->layout 			= false;
		if ($this->request->is('ajax')) {
			$flujo_id 			= $this->request->data['flujo_id'];
			$datos				= $this->ProspectiveUser->get_data($this->request->data['flujo_id']);
			$datosContacto 		= $this->ProspectiveUser->ContacsUser->get_data($datos['ProspectiveUser']['contacs_users_id']);
			$datosEmpresa		= $this->ProspectiveUser->ContacsUser->ClientsLegal->get_data_modelos($datosContacto['ContacsUser']['clients_legals_id']);
			$this->set(compact('datosEmpresa','datos','documentosList','flujo_id'));
		}
	}

    public function bill_information_list(){
        $this->layout = false;
        $this->loadModel("Salesinvoice");
        $otrasFacturas                  = $this->Salesinvoice->findAllByProspectiveUserId($this->request->data['id']);
        $this->ProspectiveUser->recursive = -1;
        $datos = $this->ProspectiveUser->findById($this->request->data["id"]);

        $last  = $this->ProspectiveUser->FlowStage->find("first",["conditions" => ["FlowStage.prospective_users_id" => $this->request->data["id"]], "recursive" => -1, "order" => ["id" => "DESC"] ]);

        $this->set(compact("datos","otrasFacturas","last"));
    }

	public function get_etapa(){
		$this->layout 	= false;
		if ($this->request->is('ajax')) {
			 
            if(!isset($this->request->data['flujo_id'])){
                return false; 
            }
			$this->loadModel("Receipt");
            $this->loadModel("Salesinvoice");
            $this->Receipt->unBindModel(array("belongsTo"=>"ProspectiveUser"));
			$this->Salesinvoice->unBindModel(array("belongsTo"=>"ProspectiveUser"));
            $modal                          = isset($this->request->data["modal"]) ? true : false;
            $recibosCaja                    = $this->Receipt->findAllByProspectiveUserId($this->request->data['flujo_id']);
			$otrasFacturas					= $this->Salesinvoice->findAllByProspectiveUserId($this->request->data['flujo_id']);
			$totalActual	= is_null($this->Receipt->field("SUM(total) as totalNoIva",array("prospective_user_id"=> $this->request->data['flujo_id']))) ? 0 : $this->Receipt->field("SUM(total) as totalNoIva",array("prospective_user_id"=> $this->request->data['flujo_id']));
            $idFlowstage                    = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($this->request->data['flujo_id']);
			$valorQuotation 				= $this->ProspectiveUser->FlowStage->valor_latest_regystri_state_cotizado_flujo_id($this->request->data['flujo_id']);
			$datosProspecto					= $this->ProspectiveUser->get_data($this->request->data['flujo_id']);
			$datos							= $this->ProspectiveUser->FlowStage->get_data_bussines($this->request->data['flujo_id']);
			$idLatestRegystri 				= $this->ProspectiveUser->FlowStage->id_latest_regystri($this->request->data['flujo_id'],$datosProspecto["ProspectiveUser"]["state_flow"]);
			$documentosList					= array();
			$latsetCotizado 				= '';
			$estadosNoPermitidos 			= array(Configure::read('variables.control_flujo.flujo_cancelado'),Configure::read('variables.control_flujo.flujo_no_valido'));

            $idFlowstage            = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($this->request->data['flujo_id']);

            $totalParaIva           = 0;
            $totalCop               = 0;

            $fecha_cotizacion       = null;

            if (!is_null($idFlowstage)) {
                $datosFlowstage         = $this->ProspectiveUser->FlowStage->get_data($idFlowstage);
                $produtosCotizacion     = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->findAllByQuotationId($datosFlowstage['FlowStage']['document']);
                $fecha_cotizacion       = $datosFlowstage["FlowStage"]["created"];
                foreach ($produtosCotizacion as $key => $value) {
                    if($value["QuotationsProduct"]["currency"] == "cop"){
                        $totalCop += ($value["QuotationsProduct"]["price"]*$value["QuotationsProduct"]["quantity"]);
                        if ($value["QuotationsProduct"]["iva"] == 1) {
                            $totalParaIva += ($value["QuotationsProduct"]["price"]*$value["QuotationsProduct"]["quantity"]);                            
                        }                           
                    }
                }
            }
            

            $valoresPagados         = $this->ProspectiveUser->FlowStage->find("list",["fields"=>["id","valor"], "conditions" => ["state_flow" => "Pagado", "payment_verification" => 1, "prospective_users_id" => $this->request->data['flujo_id'] ] ]);

            $totalPagado            = array_sum($valoresPagados);

			if (!in_array($datosProspecto['ProspectiveUser']['state_flow'], $estadosNoPermitidos)) {
				if ($datosProspecto['ProspectiveUser']['state_flow'] > Configure::read('variables.control_flujo.flujo_asignado')) {
					$documentosList 		= $this->ProspectiveUser->FlowStage->Quotation->all_data_prospective($this->request->data['flujo_id']);
				}
				if ($datosProspecto['ProspectiveUser']['state_flow'] > Configure::read('variables.control_flujo.flujo_contactado')) {
					$latsetCotizado = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($this->request->data['flujo_id']);
					$idEstadoContacdo = $this->ProspectiveUser->FlowStage->id_flow_bussines_contactado($this->request->data['flujo_id']);
				}
			}


            if(!is_null($fecha_cotizacion)){
                $fecha_cotizacion = date("Y-m-d",$fecha_cotizacion." +15 day");
            }
            
			$this->set(compact('datos','documentosList','latsetCotizado','datosProspecto','idEstadoContacdo','idLatestRegystri','recibosCaja','valorQuotation','totalActual','otrasFacturas','idFlowstage','valoresPagados','totalCop','totalParaIva','totalPagado','modal','fecha_cotizacion'));
		}
	}

    public function reloadFlujo(){
        $this->autoRender                                   = false;
        if ($this->request->is('ajax') && isset($this->request->data["id"])) {

            $this->ProspectiveUser->recursive               = -1;
            $datos = $this->ProspectiveUser->findById($this->request->data["id"]);

            $datos['ProspectiveUser']['state_flow']         = $datos['ProspectiveUser']['description'];
            $datos['ProspectiveUser']['description']        = '';
            $datos['ProspectiveUser']['state']              = 1;

            if (isset($this->request->data["restore"])) {
                $datos['ProspectiveUser']['losed']              = 0;
                $datos['ProspectiveUser']['remarketing']        = 0;
                $datos['ProspectiveUser']['date_lose']          = null;
            }

            $this->ProspectiveUser->save($datos["ProspectiveUser"]);
            return $this->request->data['id'];
        }
    }

    public function reasingar_no_gest() {
        $this->autoRender = false;

        $user_id = $this->request->data["ProspectiveUser"]["user_id"];
        $flows   = explode(",", $this->request->data["ProspectiveUser"]["id"]);

        foreach ($flows as $key => $id) {
            $this->ProspectiveUser->recursive = -1;
            $data = $this->ProspectiveUser->findById($id);
            $data["ProspectiveUser"]["state"]           = 1;
            $data['ProspectiveUser']['state_flow']      = is_numeric($data['ProspectiveUser']['description']) ? $data['ProspectiveUser']['description'] : 3;
            $data['ProspectiveUser']['description']     = '';
            $data['ProspectiveUser']['remarketing']    = 1;
            $this->ProspectiveUser->save($data);
            $this->updateUserSet($data["ProspectiveUser"]["user_id"],$user_id,$id, true);
        }
        $this->Session->setFlash(__('Flujos reasignados correctamente.'),'Flash/success');
        $this->redirect(["action"=>"panel_gest"]);
    }

    public function cancel_finish(){
        $this->autoRender                                   = false;
        if ($this->request->is('ajax')) {

            $this->ProspectiveUser->recursive               = -1;
            $datos = $this->ProspectiveUser->findById($this->request->data["id"]);

            $datos['ProspectiveUser']['state_flow']         = 9;
            $datos['ProspectiveUser']['description']        = 'Cancelado por gestión | aprobado por gerencia';
            $datos['ProspectiveUser']['state']              = 1;
            $this->ProspectiveUser->save($datos);
            return $this->request->data['id'];
        }
    }

	public function reactivarFlujo(){
		$this->autoRender   								= false;
        if ($this->request->is('ajax')) {
        	$datos['ProspectiveUser']['id'] 				= $this->request->data['flujo_id'];
			$datos['ProspectiveUser']['description'] 		= '';
			$datos['ProspectiveUser']['state_flow'] 		= Configure::read('variables.control_flujo.flujo_contactado');
			$this->ProspectiveUser->save($datos);
			return $this->request->data['flujo_id'];
        }
	}

    public function deleteInventoryVenta(){
        $this->autoRender = false;
        $this->loadModel("Wo");
        $idProduct = $this->request->data["id"];
        $this->Wo->recursive = -1;
        $allVentas = $this->Wo->findAllByProductIdAndState($idProduct,1);
        foreach ($allVentas as $key => $value) {
            $value["Wo"]["state"] = 0;
            $value["Wo"]["modified"] = date("Y-m-d");
            $this->Wo->save($value);
        }
    }

    public function deleteInventoryVentaMasive(){
        $this->autoRender = false;
        $this->loadModel("Wo");
        $this->Wo->recursive = -1;
        $idsProduct = $this->request->data["ids"];
        $allVentas  = $this->Wo->findAllByProductIdAndState($idsProduct,1);

        foreach ($allVentas as $key => $value) {
            $value["Wo"]["state"] = 0;
            $value["Wo"]["deleted_at"] = date("Y-m-d");
            $this->Wo->save($value);
        }
    }

    private function sincronize_wos($dateIni,$dateEnd){
        $this->loadModel("Wo");
        $whoIds = $this->Wo->find("list",["fields"=>["id","id"],"conditions" => ["Fecha >=" => $dateIni, "Fecha <=" => $dateEnd ] ]);
        $datos = $this->postWoApi(["ids"=> $this->encrypt(implode(",", $whoIds)) ],"ventas_inventario");
        if (!empty($datos)) {
            $datos = $this->object_to_array($datos);
            $this->Wo->saveAll($datos);
        }
    }

	public function import_ventas() {
		$this->validateRoleImports();

        $this->loadModel("Wo");

		$dateIni = date("Y-m-d",strtotime("-60 day"));
		$dateEnd = date('Y-m-d');
		$this->set("dateIni",$dateIni);
		$this->set("dateEnd",$dateEnd);  

        $flujoSearch = "";
        $inventory   = "";
        $flows       = [];     

        $this->sincronize_wos($dateIni,$dateEnd);

        $query = $this->request->query;

        if (!empty($query)) {

            $inventory      = isset($query["inventory"]) ? $query["inventory"] : "";
            $flujoSearch    = isset($query["flujoSearch"]) ? $query["flujoSearch"] : "";
            $conditions     = ["Fecha >=" => $dateIni, "Fecha <=" => $dateEnd, "Wo.state" => 1,"Wo.product_id !="=>null, "Wo.brand_id !="=> null  ];

            if ($flujoSearch != "" && $flujoSearch == 0 ) {
                $conditions[] = "transito(Product.id) = 0";
            }

            if ($inventory != '') {
                $datos = $this->postWoApi(["number"=> $inventory ],"ref_inventory");  
                if (!empty($datos)) {
                    $datos = $this->object_to_array($datos);
                    $datos = Set::extract($datos,'{n}.Referencia');
                    $conditions["Product.part_number"] = $datos;
                }            
            }

            $whos        = $this->Wo->find("all",["fields"=>["Wo.Cantidad","Wo.brand_id","Wo.product_id","Wo.Referencia","Product.img","Product.name","Product.notes","transito(Product.id) transito"],"conditions" => $conditions ]);

            if (!empty($whos)) {
                $referencias = array();
                try {
                    $this->loadModel("Quotation");
                    $actualProds = $this->Quotation->QuotationsProduct->Product->find("list",["fields" => ["id","id"], "conditions" => 
                        [
                            "OR" => [ ["Product.category_id"=>[1257,631,1369]], ["Product.reorder >" => 0, "Product.min_stock" => 0,]  ], 
                             
                        ] 
                    ] );
                    

                    foreach ($whos as $key => $value) {
                        if (!isset( $flows[ $value["Wo"]["brand_id"] ][ $value["Wo"]["product_id"] ] )) {
                            $flows[ $value["Wo"]["brand_id"] ][ $value["Wo"]["product_id"] ] = [ 
                                "QuantityFinal" => $value["Wo"]["Cantidad"], "name" => $value["Product"]["name"],
                                "comment" => $value["Product"]["notes"], "part" => $value["Wo"]["Referencia"],"brand_id"=>$value["Wo"]["brand_id"],
                                "img" => $value["Product"]["img"],
                            ];
                        }else{
                           $flows[ $value["Wo"]["brand_id"] ][ $value["Wo"]["product_id"] ]["QuantityFinal"] += $value["Wo"]["Cantidad"];
                        }
                    }

                    if (!empty($flows)) {
                        foreach ($flows as $key => $products) {
                            $referencias = array_merge($referencias, Set::extract($products,"{n}.part") );
                        }
                        $products = [];
                        foreach ($referencias as $key => $value) {
                            $products[] = ["Product" => ["part_number" => $value] ];
                        }
                        $partsData  = $this->getValuesProductsWo($products);
                    }


                } catch (Exception $e) {
                 $flows       = array();         
                    $partsData   = array(); 
                    $actualProds = []; 
                }
            }
        }
		
        $this->set("inventory",$inventory);
        $this->set("flujoSearch",$flujoSearch);

		$this->set(compact("flows",'partsData','actualProds'));
	}

    public function get_ventas($product_id){
        $this->layout   = false;
        $this->loadModel("Wo");
        $ventas         = $this->Wo->findAllByProductIdAndState($product_id,1);
        $producto       = end($ventas);
        $this->set("producto", $producto["Product"]);
        $this->set("ventas", $ventas);
    }

    public function calculate_cost(){
        $this->layout = false;
        $this->loadModel("Config");

        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $factorImport   = $config["Config"]["factorUSA"];

        $cantidadProducto   = $this->request->data["cantidadProducto"];
        $currency           = $this->request->data["currency"];

        $this->set(compact("trmActual","factorImport","cantidadProducto","currency"));
    }

	public function request_import_brands($internacional = null){
		$this->validateRoleImports();
        $this->loadModel("ImportRequest");
        $this->loadModel("Config");
        $this->loadModel("Notes");
        $this->loadModel("Brand");
		$this->loadModel("Reject");

        $config         = $this->Config->findById(1);
        $trmActual      = $config["Config"]["trm"]+$config["Config"]["ajusteTrm"];
        $factorImport   = $config["Config"]["factorUSA"];

		$requests        = $this->ImportRequest->getImportRequestBrands($internacional);

        $products        = $this->ImportRequest->getImportRequestProducts($internacional);
        $inventioWo      = $this->getValuesProductsWo($products);

        $this->Brand->recursive = -1;

        foreach ($requests as $key => $value) {
            $requests[$key]["Brand"]["children"] = $this->Brand->findAllByBrandId($value["Brand"]["id"]);
        }
        $totalRechazo = $this->Reject->find("count");

        $notas = $this->Notes->find("list",array("conditions" => array("type" => 4))); 

		$this->set(compact("requests","trmActual","factorImport","notas","totalRechazo","internacional","inventioWo"));
	}

	public function request_import_brands_email(){

		$this->autoRender = false;
        $this->loadModel("ImportRequest");
		$this->loadModel("Informe");

		$requests = $this->ImportRequest->getImportRequestBrands();

		if (empty($requests)) {
			return false;
		}

        $this->loadModel("Informe");

        $dataInforme = ["Informe" => [ "type" => "request_import_brands_email","datos" => json_encode($requests), "total" => count($requests), "fecha" => date("Y-m-d") ] ];

        $this->Informe->create();
        $this->Informe->save($dataInforme);

		$usersRoleLogistica     = $this->ProspectiveUser->User->role_logistica_user();
		// $usersGerencia          = $this->ProspectiveUser->User->role_gerencia_user();

		$users = array_merge($usersRoleLogistica);
		foreach ($users as $value) {
    		$options = array(
						'to'		=> $value['User']['email'],
						'template'	=> 'request_import_pending',
						'subject'	=> 'Solicitudes pendientes de envio a proveedor - '.date("d-m-Y"),
						'vars'		=> array("requests" => $requests),
					);
    		$this->sendMail($options);
    	}
	}

	public function nameProspectiveContact($prospective_id){
    	$datos 				= $this->ProspectiveUser->get_data($prospective_id);
		if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
			$datosC 		= $this->ProspectiveUser->ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
			$nombre 		= $datosC['ClientsLegal']['name'].' - '.$datosC['ContacsUser']['name'];
		} else {
			$datosC 		= $this->ProspectiveUser->ClientsNatural->get_data($datos['ProspectiveUser']['clients_natural_id']);
			$nombre 		= $datosC['ClientsNatural']['name'];
		}
		return $nombre;
    }

	public function addLegalProspective(){
        $this->autoRender   = false;
        if ($this->request->is('ajax')) {
            $datos['ProspectiveUser']['contacs_users_id']       		= $this->request->data['contact'];
            $datos['ProspectiveUser']['origin']                 		= $this->request->data['origin'];
            $datos['ProspectiveUser']['user_receptor']               	= AuthComponent::user('id');
            if ($this->request->data['flujo_no_seleccionado'] == 1) {
            	$datos['ProspectiveUser']['state_flow'] 				= Configure::read('variables.control_flujo.flujo_no_valido');
            	$datos['ProspectiveUser']['user_id']                	= AuthComponent::user('id');
            	$origen							                 		= $this->request->data['origin'];
            } else {
            	$datos['ProspectiveUser']['state_flow']             	= Configure::read('variables.control_flujo.flujo_asignado');
            	$datos['ProspectiveUser']['user_id']                	= $this->request->data['user_id'];
            }
            $description                                        		= $this->request->data['description'];
            $reason                                             		= $this->request->data['reason'];
            $datos["ProspectiveUser"]["time_contact"]                   = $this->calculateHoursGest( Configuration::get_flow("hours_contact") );
            // $datos['ProspectiveUser']['id']             				= $this->ProspectiveUser->new_row_model() + 1;
            $this->ProspectiveUser->create();
            if ($this->ProspectiveUser->save($datos)) {
                $flujo_id                                       = $this->ProspectiveUser->id;
                $this->saveDataLogsUser(2,'ProspectiveUser',$flujo_id);
	            if ($this->request->data['flujo_no_seleccionado'] == 0) {
	            	$datosUsu 									= $this->ProspectiveUser->User->get_data($datos['ProspectiveUser']['user_id']);
                	$datosContacto 								= $this->ProspectiveUser->ContacsUser->get_data($this->request->data['contact']);
	            	$this->saveStagesFlow($flujo_id,$description,$reason,Configure::read('variables.nombre_flujo.flujo_asignado'));
	            	$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datos['ProspectiveUser']['user_id'],$flujo_id,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
	            	$this->addProspectiveAtentionTime($flujo_id);
	                $this->saveAtentionTimeFlujoEtapasLimitTime($flujo_id,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
	                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$reason,$description,$datosContacto['ContacsUser']['name'],$datosContacto['ContacsUser']['telephone'],$datosContacto['ContacsUser']['cell_phone'],$datosContacto['ContacsUser']['email'],$datosContacto['ContacsUser']['city'],'prospectiveUsers/adviser?q='.$flujo_id);	                
	                return $flujo_id;
	            } else {
	            	$this->saveStagesFlow($flujo_id,$description,$reason,Configure::read('variables.nombre_flujo.flujo_no_valido'),$origen);
	            	return false;
	            }
            }
        }
    }

    public function addNaturalProspective(){
        $this->autoRender   = false;
        if ($this->request->is('ajax')) {
            $datos['ProspectiveUser']['clients_natural_id']     		= $this->request->data['contact'];
            $datos['ProspectiveUser']['origin']                 		= $this->request->data['origin'];
            $datos['ProspectiveUser']['user_receptor']               	= AuthComponent::user('id');
            $origen							                 			= $this->request->data['origin'];
            if ($this->request->data['flujo_no_seleccionado'] == 1) {
            	$datos['ProspectiveUser']['state_flow'] 				= Configure::read('variables.control_flujo.flujo_no_valido');
            	$datos['ProspectiveUser']['user_id']                	= AuthComponent::user('id');            	
            } else {
            	$datos['ProspectiveUser']['state_flow']             	= Configure::read('variables.control_flujo.flujo_asignado');
            	$datos['ProspectiveUser']['user_id']                	= $this->request->data['user_id'];
            }
            $description                                        		= $this->request->data['description'];
            $reason                                             		= $this->request->data['reason'];
            $datos["ProspectiveUser"]["description"]					= $description;
            $datos["ProspectiveUser"]["origin"]							= $this->request->data['origin'];
            $datos["ProspectiveUser"]["time_contact"]                   = $this->calculateHoursGest( Configuration::get_flow("hours_contact") );
            // $datos['ProspectiveUser']['id']             				= $this->ProspectiveUser->new_row_model() + 1;
            $this->ProspectiveUser->create();
            if ($this->ProspectiveUser->save($datos)) {
                $flujo_id                                       		= $this->ProspectiveUser->id;
                $this->saveDataLogsUser(2,'ProspectiveUser',$flujo_id);
	            if ($this->request->data['flujo_no_seleccionado'] == 0) {
	            	$datosUsu 									= $this->ProspectiveUser->User->get_data($datos['ProspectiveUser']['user_id']);
                	$datosContacto 								= $this->ProspectiveUser->ClientsNatural->get_data($this->request->data['contact']);
	            	$this->saveStagesFlow($flujo_id,$description,$reason,Configure::read('variables.nombre_flujo.flujo_asignado'),$origen);
	            	$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.flujo_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datos['ProspectiveUser']['user_id'],$flujo_id,Configure::read('variables.nombre_flujo.flujo_asignado'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
	            	$this->addProspectiveAtentionTime($flujo_id);
	                $this->saveAtentionTimeFlujoEtapasLimitTime($flujo_id,'limit_contactado_date','limit_contactado_time',Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'));
	                $this->sendMailAsesorAsignado($datosUsu['User']['email'],$datosUsu['User']['name'],$reason,$description,$datosContacto['ClientsNatural']['name'],$datosContacto['ClientsNatural']['telephone'],$datosContacto['ClientsNatural']['cell_phone'],$datosContacto['ClientsNatural']['email'],$datosContacto['ClientsNatural']['city'],'prospectiveUsers/adviser?q='.$flujo_id);
	                return $flujo_id;
	            } else {
	            	$this->saveStagesFlow($flujo_id,$description,$reason,Configure::read('variables.nombre_flujo.flujo_no_valido'),$origen);
	            	return false;
	            }
            }
        }
    }

    public function sendMailAsesorAsignado($emailUsuario,$nameUsurio,$reason,$description,$nameClient,$telefonoClient,$cellClient,$emailClient,$cityClient,$url){

        $from_bot = 0;

        $partsUrl = explode("=", $url);

        if($partsUrl == 2 && is_numeric($partsUrl[1])){
            $from_bot = 1;
        }

    	$options = array(
			'to'		=> $emailUsuario,
			'template'	=> 'customer_assigned',
			'subject'	=> '¡'.'Te han asignado un nuevo Prospecto, Asunto: '.strip_tags($reason).'!',
			'vars'		=> array('name' => $nameUsurio,'requerimiento' => strip_tags($description), 'nameClient' => $nameClient,'telephoneClient' => $telefonoClient,'cellClient' => $cellClient,'emailClient' => $emailClient, 'cityClient' => $cityClient, 'url' => $url,"from_bot"=>$from_bot),
		);
		$this->sendMail($options);
    }

    public function addProspectiveAtentionTime($flujo_id){
    	$datos['AtentionTime']['prospective_users_id'] 	= $flujo_id;
    	$datos['AtentionTime']['asignado_date'] 		= date('Y-m-d');
    	$datos['AtentionTime']['asignado_time'] 		= date('H:i:s');
        // $datos['AtentionTime']['id']                    = $this->ProspectiveUser->AtentionTime->new_row_model() + 1;
    	$this->ProspectiveUser->AtentionTime->create();
    	$this->ProspectiveUser->AtentionTime->save($datos);
    }

    public function updateStateFinishProspective(){
    	$this->autoRender  					= false;
        if ($this->request->is('ajax')) {
        	$flowstage_quotatiob_id 	= $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($this->request->data['flujo_id']);
        	$flowstageDatos 			= $this->ProspectiveUser->FlowStage->get_data($flowstage_quotatiob_id);
        	$cotiacion_id 				= $flowstageDatos['FlowStage']['document'];
        	$numero_productos 			= $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->row_data_quotation_products($cotiacion_id);
        	$count_products_despachados = $this->ProspectiveUser->FlowStage->find_state_despachado_total_products($this->request->data['flujo_id']);
            // if($count_products_despachados < $numero_productos){
            //    $count_products_despachados = $numero_productos+1; 
            // }

        	if ($count_products_despachados >= $numero_productos) {

                $this->loadModel("ProductsLock");

                try {
                    $this->ProductsLock->updateAll( ["ProductsLock.state" => 2], ["ProductsLock.prospective_user_id" => $this->request->data['flujo_id']  ] );
                } catch (Exception $e) {
                    
                }

                $datos = $this->ProspectiveUser->get_data($this->request->data['flujo_id']);
	        	$this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.pedido_entregado'),Configure::read('variables.Gestiones_horas_habiles.envio_confirmado'),$datos['ProspectiveUser']['user_id'],$this->request->data['flujo_id'],Configure::read('variables.nombre_flujo.pedido_entregado'),$this->webroot.'prospectiveUsers/adviser?q='.$this->request->data['flujo_id']);
	    		$this->updateStateProspectiveFlow($this->request->data['flujo_id'],Configure::read('variables.control_flujo.flujo_finalizado'));
        		$datos = $this->ProspectiveUser->get_data($this->request->data['flujo_id']);
				$this->saveAtentionTimeFlujoEtapas($this->request->data['flujo_id'],'confirm_delivery_date','confirm_delivery_time','comfir_delivery');

                $datos["ProspectiveUser"]["send_final"] = 0;
                $datos["ProspectiveUser"]["send_day"] = date("Y-m-d", strtotime("+5 day"));
                $this->ProspectiveUser->save($datos);

                if ($datos["ProspectiveUser"]["type"] > 0) {
                    $this->loadModel("TechnicalService");
                    $this->TechnicalService->recursive = -1;
                    $servicio = $this->TechnicalService->findById($datos["ProspectiveUser"]["type"]);
                    $servicio["TechnicalService"]["real_state"] = 2;
                    $this->TechnicalService->save($servicio);
                }

				// $this->sendEmailClientOrderDelivred($datos['ProspectiveUser']['contacs_users_id'],$datos['ProspectiveUser']['clients_natural_id'],$this->request->data['flujo_id']);
 
				return true;
        	} else {
        		return false;
        	}
    	}
    }

    public function send_mail_finish(){
        $this->autoRender = false;
        $prospectives = $this->ProspectiveUser->findAllBySendFinalAndSendDay(0,date("Y-m-d"));
        foreach ($prospectives as $key => $datos) {
            $datos["ProspectiveUser"]["send_final"] = 1;
            $this->ProspectiveUser->save($datos);
            $this->sendEmailClientOrderDelivred($datos['ProspectiveUser']['contacs_users_id'],$datos['ProspectiveUser']['clients_natural_id'],$datos['ProspectiveUser']['id']);
        }

    }


    public function sendEmailClientOrderDelivred($contacs_users_id,$clients_natural_id,$flujo_id){
    	$datosCliente 			= $this->findDataCliente($contacs_users_id,$clients_natural_id);
    	$emailCliente[] 		= $this->findEmailCliente($contacs_users_id,$clients_natural_id);
    	$copias_email 			= $this->ProspectiveUser->FlowStage->copias_email_despachado($flujo_id);
        $this->loadModel("FlowStage");

        $id_etapa_cotizado      = $this->FlowStage->id_latest_regystri_state_cotizado($flujo_id);
        $id_etapa_pagado        = $this->FlowStage->id_latest_regystri_state_pagado($flujo_id);
        $datos                  = $this->FlowStage->get_data($id_etapa_cotizado);

        $produtosCotizacion     = [];

        if (is_numeric($datos['FlowStage']['document'])) {
            $produtosCotizacion     = $this->FlowStage->Quotation->QuotationsProduct->get_data_quotation($datos['FlowStage']['document']);
            $validateTypeCotizacion = '';
            $produtosCotizacion = Set::sort($produtosCotizacion, '{n}.QuotationsProduct.quantity', 'asc');

        }

    	if ($copias_email != '') {
    		$emails 			= explode(',',$copias_email);
			if (isset($emails[0])) {
				$emailCliente 		= array_merge($emails, $emailCliente);
			} else {
				$emailCliente[] 	= $copias_email;
			}
    	}
		$options = array(
			'to'		=> $emailCliente,
			'template'	=> 'order_delivered',
			'subject'	=> 'Confirmación de entrega del pedido',
			'vars'		=> array("flujo" => $flujo_id, "productos" => $produtosCotizacion),
		);
		$this->sendMail($options);
    }

    public function updateStatePagadoProspective(){
    	$this->autoRender   = false;
        if ($this->request->is('ajax')) {
        	$this->updateStateProspectiveFlow($this->request->data['flujo_id'],Configure::read('variables.control_flujo.flujo_pagado'));
    		$this->messageUserRoleCotizacion($this->request->data['flujo_id'],'total');
    		$id_flowStage 		= $this->ProspectiveUser->FlowStage->id_flow_bussines_latses_pagado($this->request->data['flujo_id']);
    		$this->updateFlowStageState($id_flowStage,2);
    		$this->updateFlowStagePaymentVerification($id_flowStage,0);
    		$this->reloadInformationSale($id_flowStage);
    	}
    }

    public function reloadInformationSale($id_flowStage){
    	$datos 												= $this->ProspectiveUser->FlowStage->get_data($id_flowStage);
    	$datosNew 											= array();
    	$datosNew['FlowStage']['document']		 			= $datos['FlowStage']['document'];
    	$datosNew['FlowStage']['valor'] 					= $datos['FlowStage']['valor'];
    	$datosNew['FlowStage']['payment'] 					= $datos['FlowStage']['payment'];
    	$datosNew['FlowStage']['type_pay']	 				= $datos['FlowStage']['type_pay'];
    	$datosNew['FlowStage']['state_flow']				= Configure::read('variables.nombre_flujo.flujo_pagado');
    	$datosNew['FlowStage']['state']						= 2;
    	$datosNew['FlowStage']['prospective_users_id'] 		= $datos['FlowStage']['prospective_users_id'];
    	$this->ProspectiveUser->FlowStage->create();
    	$this->ProspectiveUser->FlowStage->save($datosNew);
    	return true;
    }

    public function quotes_sent(){
		$this->loadModel('FlowStage');
		$q = $this->request->query;

        $conditions['FlowStage.state_flow'] = Configure::read('variables.nombre_flujo.flujo_cotizado');

        if (AuthComponent::user("role") == "Asesor Externo") {
            $conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
        }

		if (!empty($q)) {
            if(!empty($q["txt_buscador_flujo"])){
                $conditions["ProspectiveUser.id"] = $q["txt_buscador_flujo"];
            }

            if(!empty($q["txt_buscador_fecha"])){
                $conditions["DATE(FlowStage.created)"] = $q["txt_buscador_fecha"];
            }
            if(!empty($q["txt_asesor"])){
                $conditions["ProspectiveUser.user_id"] = $q["txt_asesor"];
            }
            if(!empty($q["txt_text"])){
                $conditions["OR"] = [
                    "LOWER(Quotation.codigo) LIKE" => '%'.
                    strtolower($q['txt_text']).'%',
                    "LOWER(Quotation.name) LIKE" => '%'.
                    strtolower($q['txt_text']).'%',
                ];
            }
            if(!empty($q["txt_buscador_cliente"])){
                $pos = strpos($q["txt_buscador_cliente"],"LEGAL");
                if($pos === false){
                    $conditions["ProspectiveUser.clients_natural_id"] = str_replace("_NATURAL", "", $q["txt_buscador_cliente"]);
                }else{
                    $id_legal = str_replace("_LEGAL", "", $q["txt_buscador_cliente"]);
                    $this->loadModel("ContacsUser");
                    $this->ContacsUser->recursive = -1;
                    $contactos = $this->ContacsUser->findAllByClientsLegalsId($id_legal);
                    if(!empty($id_legal) && !empty($contactos)){
                        $contactos  = Set::extract($contactos, "{n}.ContacsUser.id");
                        $conditions["ProspectiveUser.contacs_users_id"] = $contactos;
                    }else{
                        $conditions["ProspectiveUser.id"] = 0;
                    }
                }
            }
            if (AuthComponent::user("role") == "Asesor Externo") {
                $conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
            }
			// $conditions				=   array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'),
			// 								'OR' => array(
			// 						            'FlowStage.codigoQuotation LIKE' 	=> '%'.$get['q'].'%'
			// 						        )
			// 							);

		} else {
			$conditions 			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_cotizado'));
            if (AuthComponent::user("role") == "Asesor Externo") {
                $conditions["ProspectiveUser.user_id"] = AuthComponent::user("id");
            }
		}
		$fields 					= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.contacs_users_id','ProspectiveUser.clients_natural_id');
		$order						= array('FlowStage.id' => 'desc');

        $this->paginate 			= array(
							        	'limit' 		=> 30,
							        	'conditions' 	=> $conditions,
							        	'fields' 		=> $fields,
							        	'order' 		=> $order
							    	);
		$cotizaciones_enviadas 		= $this->paginate('FlowStage');
    	$this->set(compact('cotizaciones_enviadas'));

        $clientsLegals      = $this->getClientsLegals();
        $clientsNaturals    = $this->getClientsNaturals();
        $usuarios           = AuthComponent::user("role") == "Asesor Externo" ? [AuthComponent::user("id") => AuthComponent::user("name")] : ["Usuarios KEBCO" => $this->ProspectiveUser->User->role_asesor_comercial_user_true(), "Asesores Externos" => $this->ProspectiveUser->User->find("list",["conditions" => ["User.role" => "Asesor Externo","User.state" => 1 ] ]) ] ;

        $this->set("clientsLegals",$clientsLegals);
        $this->set("clientsNaturals",$clientsNaturals);
        $this->set("usuarios",$usuarios);

        if(!empty($this->request->query)){
            $this->set("q", $this->request->query);
        }
    }

    public function aprovee_cancel() {
        $this->loadModel("User");

        if(!in_array(AuthComponent::user("role"), ["Gerente General", "Logística"]) ){
            $this->redirect(array("controller"=>"prospective_users","action" => "index"));
        }

        $this->loadModel("Approve");
        $this->loadModel("Config");

        $users = $this->User->field("users", ["id" => AuthComponent::user("id")] );

        $cotizaciones  = $this->Approve->find("all",["conditions" => ["Approve.state" => 0, "Approve.type_aprovee !=" => [0,1]] ]);
        $users = [];

        $cotizaciones_enviadas = [];

        foreach ($cotizaciones as $key => $value) {           
            if(!in_array($value["Approve"]["user_id"], $users)){
                $users[] = $value["Approve"]["user_id"];
            }
            $cotizaciones_enviadas[$value["Approve"]["user_id"]][] = $value;
        }

        // echo "<pre>";
        // var_dump($cotizaciones);
        // die;

        $this->set(compact('cotizaciones_enviadas','users'));
    }

    public function locked_flows() {
        if (AuthComponent::user("role") != "Gerente General") {
            $this->redirect(["action"=>"index"]);
        }
    }

    public function config_shipping() {
        if ( !in_array(AuthComponent::user("role"), ["Gerente General","Logística"]) ) {
            $this->redirect(["action"=>"index"]);
        }

        $this->loadModel("ConfigFlow");

        if ($this->request->is("post") || $this->request->is("put")) {
            if ($this->ConfigFlow->save($this->request->data)) {
                $this->Session->setFlash(__('Configuración guardada correctamente'),'Flash/success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Configuración no fue guardada correctamente'),'Flash/error');
            }
        }else{
            $this->request->data = $this->ConfigFlow->findById(1);
        }
        $users           = $this->ProspectiveUser->User->role_asesor_comercial_user_true(true);
        $this->set("users",$users);
    }

    public function autorization() {

        $user_ids           = $this->ProspectiveUser->find("list",["fields"=>["user_id","user_id"], "conditions" => ["type"=>0,"state_flow" => 1, "time_contact <" => date("Y-m-d H:i:s") ], 
         ]);


        $user_ids_qt           = $this->ProspectiveUser->find("list",["fields"=>["user_id","user_id"], "conditions" => ["type"=>0,"state_flow" => 2, "time_quotation <" => date("Y-m-d H:i:s")], 
        ]);

        $user_ids              = array_merge($user_ids,$user_ids_qt);

        $usuarios           = $this->ProspectiveUser->User->find("list",["conditions"=>["id"=>$user_ids]]);

        if ($this->request->is("post")) {
            $data  = $this->request->data;
            $flows = $this->ProspectiveUser->find("list",["fields"=>["id","id"], "conditions" => ["user_id" => $data["ProspectiveUser"]["user_id"],"type"=>0,"state_flow" => 1, "time_contact <=" => date("Y-m-d H:i:s")   ]  ]);

            $flows_add = $this->ProspectiveUser->find("list",["fields"=>["id","id"], "conditions" => ["user_id" => $data["ProspectiveUser"]["user_id"],"type"=>0,"state_flow" => 2, "time_quotation <=" => date("Y-m-d H:i:s")]  ]  );

            $flows = array_merge($flows,$flows_add);
            foreach ($flows as $key => $id) {

                $this->ProspectiveUser->save(
                    ["id" => $id, "time_contact" => str_replace("T", " ", $data["ProspectiveUser"]["time_auth"]), "time_quotation" => str_replace("T", " ", $data["ProspectiveUser"]["time_auth"]) ]
                );

                $etapa = $this->ProspectiveUser->field("state_flow",["id" => $id ]);

                switch ($etapa) {
                    case '1':
                        $etapa = "Asignado";
                        break;
                    case '2':
                        $etapa = "Contactado";
                        break;
                }

                $datos['ProgresNote']['description']                = $data["ProspectiveUser"]["message"];
                $datos['ProgresNote']['etapa']                      = $etapa;
                $datos['ProgresNote']['prospective_users_id']       = $id;
                $datos['ProgresNote']['user_id']                    = AuthComponent::user('id');
                $this->ProspectiveUser->ProgresNote->create();
                $this->ProspectiveUser->ProgresNote->save($datos);
            }

            $this->Session->setFlash(__('Tiempo actualizado correctamente'),'Flash/success');
            $this->redirect(["action"=>"index"]);

        }

        $this->set(compact('usuarios'));
    }

    public function terminar(){
        $this->autoRender = false;
        $id = $this->request->data["id"];
        $datos['ProgresNote']['description']                = "El usuario ".AuthComponent::user("name"). " terminó  el flujo";
        $datos['ProgresNote']['etapa']                      = "Flujo en proceso";
        $datos['ProgresNote']['prospective_users_id']       = $id;
        $datos['ProgresNote']['user_id']                    = AuthComponent::user('id');
        $this->ProspectiveUser->ProgresNote->create();
        $this->ProspectiveUser->ProgresNote->save($datos);

        $this->ProspectiveUser->save(["id" => $id, "state_flow" => 8 ]);
        $this->Session->setFlash(__('Flujo actualizado correctamente'),'Flash/success');
    }

    public function aprovee(){

        $this->loadModel("User");

        $usersApprovePermission = $this->User->find("list",["fields" => ["id","id"],"conditions" => ["User.users !=" => NULL] ]);

        

        if(is_null($usersApprovePermission) || !in_array(AuthComponent::user("id"), $usersApprovePermission)){
            $this->redirect(array("controller"=>"prospective_users","action" => "index"));
        }

        $this->loadModel("Approve");
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

        $cotizaciones  = $this->Approve->find("all",["conditions" => ["Approve.state" => 0, "Approve.user_id" => $users, "Approve.type_aprovee" => 1] ]);

        $users = [];

        $cotizaciones_enviadas = [];

        foreach ($cotizaciones as $key => $value) {           
            $value["otrasCliente"] = $this->get_quotations($value["Quotation"]["id"]);
            if(!in_array($value["Approve"]["user_id"], $users)){
                $users[] = $value["Approve"]["user_id"];
            }
            $cotizaciones_enviadas[$value["Approve"]["user_id"]][] = $value;
        }

        // echo "<pre>";
        // var_dump($cotizaciones);
        // die;

        $this->set(compact('cotizaciones_enviadas','users'));

    }

    public function get_quotations($id){
        $quotations              = [];
        $quotationsCliente       = [];
        $idsClientes             = [];

        $this->loadModel("Quotation");
        $this->loadModel("QuotationsProduct");
        $this->loadModel("ContacsUser");

        $products = $this->QuotationsProduct->find("list",["conditions"=>["quotation_id"=>$id],"fields" => ["product_id","product_id"] ]);
        $datos    = $this->ProspectiveUser->find("first",["recursive" => -1, "conditions" => ["id" => $this->Quotation->field("prospective_users_id",["id"=>$id]) ] ]);

        if (!empty($products) && !empty($datos)) {
            

            $conditions = ["OR" => [ ["valid" => 1, "state_flow" => 2] , "state_flow" => [3,4] ], 'DATE(ProspectiveUser.created) >=' => date("Y-m-d",strtotime("-3 day")),'DATE(ProspectiveUser.created) <=' => date("Y-m-d") ];

            $conditions["user_id !="] = $datos["ProspectiveUser"]["user_id"];

            if ($datos['ProspectiveUser']['contacs_users_id'] > 0) {
                $datosC  = $this->Quotation->FlowStage->ProspectiveUser->ContacsUser->get_data_modelos($datos['ProspectiveUser']['contacs_users_id']);
                $conditions["contacs_users_id"] = $this->ContacsUser->find("list",["fields"=>["id","id"],"conditions"=>["clients_legals_id"=> $datosC["ClientsLegal"]["id"] ]]);
            } else {
                $cliente = $datos['ProspectiveUser']['clients_natural_id'];
                $conditions["clients_natural_id"] = $cliente;
            }

            $idsProspectosClientes = $this->ProspectiveUser->find("list",["fields"=>["id","id"],"conditions" => $conditions ]);
            $idsQtClientes         = $this->Quotation->find("list",["fields" => ["id","id"], "conditions" => ["prospective_users_id" => $idsProspectosClientes]  ]);
            $quotationsCliente     = $this->QuotationsProduct->find("all",["limit" => 10, "conditions" => ["quotation_id" => $idsQtClientes, "product_id" => $products ], "order" => ["Quotation.created" => "DESC"], "group" => ["QuotationsProduct.quotation_id"]  ]);

            if (!empty($quotationsCliente)) {
                foreach ($quotationsCliente as $key => $value) {
                    if ($value["Quotation"]["id"] == $id || $value["Quotation"]["prospective_users_id"] == $datos["ProspectiveUser"]["id"] || $datos["ProspectiveUser"]["user_id"] == $value["ProspectiveUser"]["user_id"] ) {
                        unset($quotationsCliente[$key]);
                    }
                }
            }

        }
        return $quotationsCliente;
    }

    public function verify_payment(){
    	$this->validateRolePayments();
		$flujosVerify 			= $this->ProspectiveUser->FlowStage->flujos_verify();
		$this->set(compact('flujosVerify'));
	}

    public function verify_payment_tienda(){
        $this->validateRolePayments();
        $flujosVerify           = $this->ProspectiveUser->FlowStage->flujos_verify_tienda();
        $this->set(compact('flujosVerify'));
    }

    public function change_reject(){
        $this->autoRender = false;
        $data = ["ProspectiveUser" => [
            "id" => $this->request->data["id"],
            "rejected" => $this->request->data["2"],
        ]];

        $this->ProspectiveUser->save($data);
    }

	public function verify_payment_credito(){
		$this->validateRolePayments();
		$flujosVerify 			= $this->ProspectiveUser->FlowStage->flujos_verify_payment_credito();
		$this->set(compact('flujosVerify'));
	}

    public function aprobar_creditos() {
        if(AuthComponent::user("role") !=  'Gerente General'){
            return $this->redirect(["action"=>"index"]);
        } 
        $this->validateRolePayments();
        $flujosVerify           = $this->ProspectiveUser->FlowStage->flujos_verify_payment_credito();
        $this->set(compact('flujosVerify'));
    }



    public function history_credit(){

        $order          = array('FlowStage.id' => 'desc');
        $fields         = array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.id','ProspectiveUser.type','ProspectiveUser.contacs_users_id');
        $conditions     = array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'),'FlowStage.payment' => 'Crédito');

        $clientes = [];

        if(!empty($this->request->query)){
            $q = $this->request->query;

            if(!empty($q["txt_buscador_cliente"])){
                $pos = strpos($q["txt_buscador_cliente"],"LEGAL");
                if($pos === false){
                    $this->loadModel("ClientsNatural");
                    $conditions["ProspectiveUser.clients_natural_id"] = str_replace("NATURAL", "", $q["txt_buscador_cliente"]);
                    $clientes = [ $q["txt_buscador_cliente"] => $this->ClientsNatural->field("name",["id"=>$q["txt_buscador_cliente"]]) ];
                }else{
                    $id_legal = str_replace("LEGAL", "", $q["txt_buscador_cliente"]);
                    $this->loadModel("ContacsUser");
                    $this->ContacsUser->recursive = -1;
                    $contactos = $this->ContacsUser->findAllByClientsLegalsId($id_legal);
                    if(!empty($id_legal)){
                        $contactos  = Set::extract($contactos, "{n}.ContacsUser.id");
                        $conditions["ProspectiveUser.contacs_users_id"] = $contactos;
                    }
                    $this->loadModel("ClientsLegal");
                    $clientes = [ $q["txt_buscador_cliente"] => $this->ClientsLegal->field("name",["id"=>$q["txt_buscador_cliente"]]) ];
                }
            }

            if(!empty($q["txt_buscador_flujo"])){
                $conditions["ProspectiveUser.id"] = $q["txt_buscador_flujo"];
            }

            if (!empty($q["txt_buscador_fecha"])) {
                $conditions["FlowStage.date_verification"] = $q["txt_buscador_fecha"];
            }
        }


        // $flujosVerifyDays          = $this->ProspectiveUser->FlowStage->flujos_verify_payment_credito_days();
        $this->Paginator->settings = compact('conditions','fields','order');
        $this->loadModel("FlowStage");
        $flujosVerifyDays = $this->Paginator->paginate("FlowStage");
        if(!empty($this->request->query)){
            $this->set("q", $this->request->query);
        }

        $this->set(compact('flujosVerifyDays','clientes'));
    }

	public function pending_dispatches($new = null){
		// $this->validateRoleDispatches();
		$pendingDispatches 		= $this->ProspectiveUser->FlowStage->pending_dispatches($new);
		$this->set(compact('pendingDispatches','new'));
	}

	public function payment_false(){
		$this->validateRolePayments();
		$paymentFalse 			= $this->ProspectiveUser->FlowStage->find_state_pagado_false();
		$this->set(compact('paymentFalse'));
	}

    public function panel_bloqueos_transito(){
        $partsData = [];
        $marcas = [];
        $bloqueos = [];
        try {
            $sql   = "SELECT SUM(inventories.quantity) total,inventories.product_id,products_locks.quantity_back,
                        products.part_number, products_locks.prospective_user_id, products.name,products.img, products.brand, products.brand_id, products.notes
                        FROM inventories 
                        INNER JOIN products_locks ON products_locks.product_id = inventories.product_id
                        INNER JOIN products ON products.id = inventories.product_id
                        WHERE warehouse = 'Transito' 
                        AND inventories.state = 1 
                        AND import_id IS NOT NULL
                        AND products_locks.prospective_user_id IS NOT NULL  
                        AND products_locks.state = 1 
                        GROUP BY products_locks.product_id, products_locks.prospective_user_id";
            $datos = $this->ProspectiveUser->query($sql);

            $referencias = [];
            
            if (!empty($datos)) {
                $marcas = [];
                $bloqueos = [];
                foreach ($datos as $key => $value) {
                    if (!array_key_exists($value["products"]["brand_id"], $marcas)) {
                        $marcas[$value["products"]["brand_id"]] = $value["products"]["brand"];
                    }
                    $bloqueos[$value["products"]["brand_id"]][] = $value;
                    if (!in_array($value["products"]["part_number"], $referencias)) {
                        $referencias[] = ["Product" => ["part_number" => $value["products"]["part_number"]] ];
                    }
                }
                $partsData              = $this->getValuesProductsWo($referencias);
            }

        } catch (Exception $e) {
            $bloqueos = [];
            $partsData = [];
            $marcas = [];
        }
        $this->set("bloqueos",$bloqueos);
        $this->set("partsData",$partsData);
        $this->set("marcas",$marcas);
    }

    public function change_valor(){
        $this->autoRender = false;
        $this->ProspectiveUser->save(
            ["ProspectiveUser" => ["id" => $this->request->data["id"],"value_minus"=>$this->request->data["value"] ] ]
        );

        // throw new CakeException('Errror');
    }

    public function payment_datafono() {
        $this->validateRolePayments();
        try {
            $this->loadModel("FlowStage");
            // $order           = array('FlowStage.id' => 'desc');
            // $fields      = array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.id','ProspectiveUser.type');
            // $conditions  = array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
            // $this->Paginator->settings = compact('conditions','fields','order');
            // $paymentTrue = $this->Paginator->paginate("FlowStage");
            $dateSixMonth = date("Y-m-d", strtotime('-6 month'));
            $this->FlowStage->recursive = -1;
            $sqlQuery = "SELECT 
                            FlowStage.id,FlowStage.modified, User.name usuario, 
                            ProspectiveUser.type, ProspectiveUser.id,ProspectiveUser.contacs_users_id, ProspectiveUser.value_minus, ClientsNatural.name, 
                            ClientsLegal.name,  FlowStage.valor, FlowStage.payment, FlowStage.document, FlowStage.type_pay, FlowStage.identificator
                        FROM flow_stages as FlowStage
                        INNER JOIN prospective_users as ProspectiveUser ON FlowStage.prospective_users_id = ProspectiveUser.id
                        INNER JOIN users as User ON ProspectiveUser.user_id = User.id 
                        LEFT JOIN clients_naturals AS ClientsNatural ON ClientsNatural.id = ProspectiveUser.clients_natural_id
                        LEFT JOIN contacs_users ON contacs_users.id = ProspectiveUser.contacs_users_id
                        LEFT JOIN clients_legals as ClientsLegal ON ClientsLegal.id = contacs_users.clients_legals_id
                        WHERE
                        payment_verification = 1 
                        AND FlowStage.state_flow = 'Pagado' AND FlowStage.payment = 'Datáfono' AND DATE(FlowStage.created) >= '${dateSixMonth}'
                        ORDER BY FlowStage.id DESC";
            $paymentTrue = $this->FlowStage->query($sqlQuery);

            if(!empty($paymentTrue)){
                foreach ($paymentTrue as $key => $value) {
                    $paymentTrue[$key]["ProspectiveUser"]["valor"] = $this->ProspectiveUser->FlowStage->valor_latest_regystri_state_cotizado_flujo_id($value["ProspectiveUser"]["id"]);
                }
            }

        } catch (Exception $e) {
            $paymentTrue = array();
        }
        $this->set(compact('paymentTrue'));
    }

	public function payment_true(){
		$this->validateRolePayments();
		try {
			$this->loadModel("FlowStage");
			// $order			= array('FlowStage.id' => 'desc');
			// $fields 		= array('FlowStage.*','ProspectiveUser.user_id','ProspectiveUser.id','ProspectiveUser.type');
			// $conditions 	= array('FlowStage.payment_verification' => 1,'FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_pagado'));
			// $this->Paginator->settings = compact('conditions','fields','order');
			// $paymentTrue = $this->Paginator->paginate("FlowStage");
            $dateSixMonth = date("Y-m-d", strtotime('-6 month'));
			$this->FlowStage->recursive = -1;
			$sqlQuery = "SELECT 
							FlowStage.id,FlowStage.modified, User.name usuario, 
							ProspectiveUser.type, ProspectiveUser.id,ProspectiveUser.contacs_users_id, ClientsNatural.name, 
							ClientsLegal.name,  FlowStage.valor, FlowStage.payment, FlowStage.document, FlowStage.type_pay, FlowStage.identificator
						FROM flow_stages as FlowStage
						INNER JOIN prospective_users as ProspectiveUser ON FlowStage.prospective_users_id = ProspectiveUser.id
						INNER JOIN users as User ON ProspectiveUser.user_id = User.id 
						LEFT JOIN clients_naturals AS ClientsNatural ON ClientsNatural.id = ProspectiveUser.clients_natural_id
						LEFT JOIN contacs_users ON contacs_users.id = ProspectiveUser.contacs_users_id
						LEFT JOIN clients_legals as ClientsLegal ON ClientsLegal.id = contacs_users.clients_legals_id
						WHERE
						payment_verification = 1 
						AND FlowStage.state_flow = 'Pagado' AND DATE(FlowStage.created) >= '${dateSixMonth}'
						ORDER BY FlowStage.id DESC";
			$paymentTrue = $this->FlowStage->query($sqlQuery);
		} catch (Exception $e) {
			$paymentTrue = array();
		}
		$this->set(compact('paymentTrue'));
	}

	public function status_dispatches($new = null){
		// $this->validateRoleDispatches();
		$flujoStateDepachado 			= $this->ProspectiveUser->FlowStage->find_state_despachado($new);
		$this->set(compact('flujoStateDepachado','new'));
	}

	public function status_dispatches_finish(){
		// $this->validateRoleDispatches();
		$this->loadModel('FlowStage');
		$get 						= $this->request->query;
		if (!empty($get)) {
			$conditions				= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_despachado'),
											'ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_finalizado'),
											'OR' => array(
									            'FlowStage.prospective_users_id LIKE' 	=> '%'.$get['q'].'%'
									        )
										);

		} else {
			$conditions 			= array('FlowStage.state_flow' => Configure::read('variables.nombre_flujo.flujo_despachado'),'ProspectiveUser.state_flow' => Configure::read('variables.control_flujo.flujo_finalizado'));
		}
		$recursive = 0;
		$order						= array('FlowStage.id' => 'desc');
		$this->paginate 			= array(
							        	'limit' 		=> 20,
							        	'conditions' 	=> $conditions,
							        	'order' 		=> $order,
							        	'recursive' 	=> $recursive
							    	);
		$flujoStateDepachado 		= $this->paginate('FlowStage');
		$this->set(compact('flujoStateDepachado'));
	}

    public function return_panel() {
        $prospectos = $this->ProspectiveUser->find("all",["conditions" => ["ProspectiveUser.state" => 4, "ProspectiveUser.bill_file !=" => null]]);
        $this->set("prospectos",$prospectos);

    }

    public function return_request(){
        $this->autoRender = false;
        if (AuthComponent::user("role") != "Gerente General") {
            $this->ProspectiveUser->recursive = -1;
            $prospecto = $this->ProspectiveUser->findById($this->request->data["flujo_id"]);
            $data = ["ProspectiveUser"=>["modified"=>date("Y-m-d H:i:s"),"id"=>$this->request->data["flujo_id"],"state" => 2,"bill_file" => $this->request->data["rason"]."|".AuthComponent::user("id")."|".$prospecto["ProspectiveUser"]["state"], ]];
            $this->ProspectiveUser->save($data);
            $this->Session->setFlash(__('Flujo envio para validación. <br> Te recomendamos no continuar actualizando el flujo ya que de ser aprobado se perderá lo que hagas.'),'Flash/success');
        }else{
            $this->return_flow();
        }
    }

    public function approve_return($state, $id, $reason) {
        $this->autoRender = false;

        if ($state == 1) {
            $data = ["ProspectiveUser"=>["id"=>$id,"state_flow" => 2, "bill_code" => null, "bill_user" => null, "bill_text" => null, "bill_date" => null,"bill_state" => 1,"state" => $state, "bill_file" => null ]];
            $datos['ProgresNote']['description']                = base64_decode($reason);
            $datos['ProgresNote']['etapa']                      = "";
            $datos['ProgresNote']['prospective_users_id']       = $id;
            $datos['ProgresNote']['user_id']                    = AuthComponent::user('id');
            $this->ProspectiveUser->ProgresNote->save($datos);
            $this->ProspectiveUser->save($data);
            $this->loadModel("Quotation");
            $this->Quotation->updateAll(["Quotation.state" => 1],["Quotation.prospective_users_id" => $id ]);
            $this->Session->setFlash(__('Flujo se devolvió correctamente.'),'Flash/success');
        }else{
            $data = ["ProspectiveUser"=>["id"=>$id,"state" => $state, "bill_file" => null ]];
            $this->Session->setFlash(__('Flujo se rechazó correctamente.'),'Flash/success');
            $this->ProspectiveUser->save($data);
        }
        $this->redirect(["action" => "return_panel"]);
    }

    public function return_flow(){
        $this->autoRender = false;
        $data = ["ProspectiveUser"=>["id"=>$this->request->data["flujo_id"],"state_flow" => 2, "bill_code" => null, "bill_user" => null, "bill_text" => null, "bill_date" => null,"bill_state" => 1, "date_quotation" => null, "time_quotation" => $this->calculateHoursGest( Configuration::get_flow("hours_quotation") ), "date_alert"=>null ]];
        $datos['ProgresNote']['description']                = $this->request->data['rason'];
        $datos['ProgresNote']['etapa']                      = $this->request->data['state'];
        $datos['ProgresNote']['prospective_users_id']       = $this->request->data['flujo_id'];
        $datos['ProgresNote']['user_id']                    = AuthComponent::user('id');
        $this->ProspectiveUser->ProgresNote->save($datos);
        $this->ProspectiveUser->save($data);
        $this->loadModel("Quotation");
        $this->Quotation->updateAll(["Quotation.state" => 1],["Quotation.prospective_users_id" => $this->request->data["flujo_id"] ]);
        $this->Session->setFlash(__('Flujo se devolvió correctamente.'),'Flash/success');
    }

    public function create_quotation() {
        $this->autoRender = false;

        $flujo_id   = $this->request->data["flujo_id"];
        $rason      = $this->request->data["rason"];
        $flow_stage = $this->request->data["flow_stage"];

        $this->loadModel("FlowStage");
        $this->loadModel("Approve");

        $datosAprovee = ["Approve" => [
            "flujo_id" => $flujo_id,
            "flowstage_id" => $flow_stage,
            "quotation_id" => 0,
            "copias_email" => $rason,
            "inlineRadioOptions" => 0,
            "type_aprovee" => 4,
            "user_id" => AuthComponent::user("id"),
        ] ];
        $this->Approve->create();
        $this->Approve->save($datosAprovee);

        $dataProspecto = ["ProspectiveUser" => [ "id" => $flujo_id, "valid" => 1 ] ];

        $this->FlowStage->ProspectiveUser->save($dataProspecto);
        $this->Session->setFlash(__('La creación de otra cotización deberá ser aprobada por un administrador.'),'Flash/success');
        return $this->request->data['flujo_id'];
    }

	public function updateStateCanceladoProspective(){
        $this->autoRender   = false;
        $this->loadModel("ProductsLock");
        $this->loadModel("FlowStage");

        if ($this->request->is('ajax') || $this->request->is('post')) {
	        $datosP['ProspectiveUser']['id']                = $this->request->data['flujo_id'];
	        $datosP['ProspectiveUser']['description']       = $this->request->data['rason'];
	        $datosP['ProspectiveUser']['state_flow']        = Configure::read('variables.control_flujo.flujo_cancelado');

            if ($this->ProspectiveUser->field("remarketing",["id"=>$this->request->data['flujo_id']]) != 1 && $this->ProspectiveUser->field("state_flow",["id"=>$this->request->data['flujo_id']]) >= 1 ) {

                $this->loadModel("Approve");

                $datosAprovee = ["Approve" => [
                    "flujo_id" => $this->request->data['flujo_id'],
                    "flowstage_id" => $this->request->data['flujo_id'],
                    "quotation_id" => 0,
                    "copias_email" => $this->request->data['rason'],
                    "inlineRadioOptions" => 0,
                    "type_aprovee" => 3,
                    "user_id" => AuthComponent::user("id") ? AuthComponent::user("id") : $this->ProspectiveUser->field("user_id",["id"=>$this->request->data['flujo_id']]) ,
                ] ];

                $this->Approve->create();
                $this->Approve->save($datosAprovee);

                $dataProspecto = ["ProspectiveUser" => [ "id" => $this->request->data['flujo_id'], "valid" => 1 ] ];

                $this->FlowStage->ProspectiveUser->save($dataProspecto);
                $this->Session->setFlash(__('La cancelación del flujo deberá ser aprobada por un administrador.'),'Flash/success');
                return $this->request->data['flujo_id'];
            }else{
                $bloqueos = $this->ProductsLock->find("all",["recursive" => -1, "conditions" => ['ProductsLock.state' => 1, 'ProductsLock.prospective_user_id' => $datosP["ProspectiveUser"]["id"] ]]);
                if(!empty($bloqueos)){
                    foreach ($bloqueos as $keyBloqueo => $valueBloqueo) {
                        $valueBloqueo["ProductsLock"]["state"] = 2;
                        $valueBloqueo["ProductsLock"]["unlock_date"] = date("Y-m-d H:i:s");
                        $this->ProductsLock->save($valueBloqueo);
                    }
                }


                $this->ProspectiveUser->save($datosP);
                $datosFlow = $this->ProspectiveUser->find("first",["conditions"=> ["ProspectiveUser.id" => $datosP["ProspectiveUser"]["id"] ],"recursive" => -1 ]);

                if ($datosFlow["ProspectiveUser"]["type"] > 0) {
                    $this->loadModel("TechnicalService");
                    $this->TechnicalService->recursive = -1;
                    $servicio = $this->TechnicalService->findById($datosFlow["ProspectiveUser"]["type"]);
                    $servicio["TechnicalService"]["real_state"] = 3;
                    $this->TechnicalService->save($servicio);
                }

                $this->saveDataLogsUser(11,'ProspectiveUser',$this->request->data['flujo_id']);
            }

            
	    }else{
            var_dump("lklasklasas");
        }
    }

    public function report_adviser($report = null){
        if (!is_null($report)) {
            $this->layout = "agenda";
        }
    	$this->validateDatesForReports();
    	$datos 												= array();
    	$get 												= $this->request->query;
    	$get["date_ini"] = $get["ini"];
    	$get["date_fin"] = $get["end"];
    	$get["find"] 	 = isset($get["find"]) ? $get["find"] : "";
    	$datos 							= array();
    	if (!empty($get)) {
    		$ids_flujos_asignados 		= $this->ProspectiveUser->ids_prospetives_range_date($get['date_ini'],$get['date_fin']);
    		$ids_flujos_proceso			= $this->ProspectiveUser->ids_flujos_proceso_rango_fechas($get['date_ini'],$get['date_fin']);
    		switch ($get['find']) {
    			case 'flujos_asignados':
    				$datos 				= $this->ProspectiveUser->all_flujos_state_asignado($get['date_ini'],$get['date_fin']);
    				$title 				= "Flujos asignados";
    				break;
    			case 'flujos_proceso':
    				$datos 				= $this->ProspectiveUser->all_flujos_proceso_rango_fechas($get['date_ini'],$get['date_fin']);
    				$title 				= "Flujos en proceso";
    				break;
    			case 'flujos_cotizados':
    				$ids_prospectives 	= $this->ProspectiveUser->FlowStage->ids_flujos_cotizados_rango_fechas($get['date_ini'],$get['date_fin'],$ids_flujos_proceso);
    				if (isset($ids_prospectives["prospective_id"][0])) {
    					$datos 			= $this->ProspectiveUser->list_prodpective_id($ids_prospectives['prospective_id']);
    				} else {
    					$datos 			= array();
    				}
    				$title 				= "Flujos cotizados";
    				break;
    			case 'flujos_cancelados':
    				$datos 				= $this->ProspectiveUser->all_flujos_cancelados_rango_fechas($get['date_ini'],$get['date_fin']);
    				$title 				= "Flujos cancelados";
    				break;
    			case 'flujos_completados':
    				$datos 				= $this->ProspectiveUser->all_flujos_terminados_rango_fechas($get['date_ini'],$get['date_fin']);
    				$title 				= "Flujos completados";
    				break;
    			case 'flujos_totales':
    				$datos 				= $this->ProspectiveUser->all_prospetives_range_date($get['date_ini'],$get['date_fin']);
    				$title 				= "Flujos totales";
    				break;
    			case 'flujos_demorados':
    				$datos 				= $this->ProspectiveUser->AtentionTime->all_flujos_demorados_rango_fechas($get['date_ini'],$get['date_fin'],$ids_flujos_asignados);
    				$title 				= "Flujos demorados";
    				break;
    			case 'flujos_no_validos':
    				$title 				= "Flujos no válidos";
    				$datos 				= $this->ProspectiveUser->all_flujos_no_validos_rango_fechas($get['date_ini'],$get['date_fin']);
    				break;

    			default:
    				$title 				= "";
    				$datos 				= array();
    				break;
    		}
    	}

		$usuarios 		 				= $this->ProspectiveUser->User->role_asesor_comercial_user_true();
		$this->set(compact('usuarios','datos','title','report'));
    }

    public function get_flows(){
        $this->layout = false;
        $fecha_ini                                  = $this->request->data['date_inicio'];
        $fecha_fin                                  = $this->request->data['date_fin'];
        $type                                       = $this->request->data['type'];

        $conditions = ["DATE(ProspectiveUser.created) >="=>$fecha_ini,"DATE(ProspectiveUser.created) <=" => $fecha_fin];

        switch ($type) {
            case 'completed':
                $conditions['ProspectiveUser.state_flow'] =Configure::read('variables.control_flujo.flujo_finalizado');
                break;
            case 'assigned':
                $conditions['ProspectiveUser.state_flow'] =Configure::read('variables.control_flujo.flujo_asignado');
                break;
            case 'proccess':
                $conditions['ProspectiveUser.state_flow <'] =Configure::read('variables.control_flujo.flujo_cancelado');
                break;
            case 'quotation':
                $ids_flujos_asignados    = $this->ProspectiveUser->ids_prospetives_range_date($fecha_ini,$fecha_fin);
                $idsCotizados            = $this->ProspectiveUser->FlowStage->count_flujos_cotizados_rango_fechas($fecha_ini,$fecha_fin,$ids_flujos_asignados,true);
                if (!empty($idsCotizados)) {
                    $conditions['ProspectiveUser.id'] = $idsCotizados;
                }else{
                    $conditions['ProspectiveUser.id'] = 0;
                }
                break;
        }
        $datos = $this->ProspectiveUser->find("all",compact("conditions"));

        
        foreach ($datos as $key => $value) {
            $total = 0;
            $id_etapa_cotizado          = $this->ProspectiveUser->FlowStage->id_latest_regystri_state_cotizado($value["ProspectiveUser"]["id"]);
            $datosFlowstage             = $this->ProspectiveUser->FlowStage->field("document",["id"=>$id_etapa_cotizado]);

            if (is_numeric($datosFlowstage)) {

                $productosCotizacion     = $this->ProspectiveUser->FlowStage->Quotation->QuotationsProduct->find("all",["conditions" => ["quotation_id" => $datosFlowstage], "fields" => ["price","currency","quantity"] ]);

                foreach ($productosCotizacion as $keyPro => $quotationProduct) {
                    $precio = $quotationProduct["QuotationsProduct"]["currency"] == "usd" ? $quotationProduct["QuotationsProduct"]["price"] * $trmActual : doubleval($quotationProduct["QuotationsProduct"]["price"]);
                    $total += ($precio * $quotationProduct["QuotationsProduct"]["quantity"]);
                }
                $datos[$key]["ProspectiveUser"]["total"] = $total;
                $datos[$key]["ProspectiveUser"]["cotizacion"] = $datosFlowstage;
                
            }else{
                $datos[$key]["ProspectiveUser"]["total"] = 0;
            }
        }

        $this->set("datos",$datos);

    }

    public function data_date_company(){
    	$this->layout 			= false;
    	if ($this->request->is('ajax')) {
    		$fecha_ini 									= $this->request->data['date_inicio'];
    		$fecha_fin 									= $this->request->data['date_fin'];
    		$countFlujosTotalesRangoFechas 				= $this->ProspectiveUser->count_prospetives_range_date($fecha_ini,$fecha_fin);
    		$ids_flujos_asignados 						= $this->ProspectiveUser->ids_prospetives_range_date($fecha_ini,$fecha_fin);
    		$countFlujosAsignadosRangoFechas 			= $this->ProspectiveUser->count_flujos_state_asignado($fecha_ini,$fecha_fin);
	    	$countFlujosProcesoRangoFechas 				= $this->ProspectiveUser->count_flujos_proceso_rango_fechas($fecha_ini,$fecha_fin);
	    	$ids_flujos_proceso							= $this->ProspectiveUser->ids_flujos_proceso_rango_fechas($fecha_ini,$fecha_fin);
			$countFlujosCanceladosRangoFechas 			= $this->ProspectiveUser->count_flujos_cancelados_rango_fechas($fecha_ini,$fecha_fin);
			$countFlujosNoValidos 						= $this->ProspectiveUser->count_flujos_no_validos_rango_fechas($fecha_ini,$fecha_fin);
			$countFlujosTerminadosRangoFechas 			= $this->ProspectiveUser->count_flujos_terminados_rango_fechas($fecha_ini,$fecha_fin);
			$demoraFlujosContactadoRangoFechas 			= $this->ProspectiveUser->AtentionTime->time_demora_contactado_rango_fechas($fecha_ini,$fecha_fin);
			$countFlujosCotizadosRangoFechas 			= $this->ProspectiveUser->FlowStage->count_flujos_cotizados_rango_fechas($fecha_ini,$fecha_fin,$ids_flujos_asignados);
			$demoraFlujosCotizarRangoFechas 			= $this->ProspectiveUser->AtentionTime->time_demora_cotizado_rango_fechas($this->request->data['date_inicio'],$this->request->data['date_fin']);
			$countFlujosRetrasoRangoFechas 				= $this->ProspectiveUser->AtentionTime->count_flujos_demorados_rango_fechas($fecha_ini,$fecha_fin,$ids_flujos_asignados);
			if ($countFlujosRetrasoRangoFechas > 0) {
				$totalHorasDemoraFlujosRangoFechas 		= (int) $demoraFlujosCotizarRangoFechas + (int) $demoraFlujosContactadoRangoFechas;
			} else {
				$totalHorasDemoraFlujosRangoFechas	 	= 0;
			}
			if ($countFlujosTerminadosRangoFechas > 0) {
				$porcentajeFlujosCompletadosRangoFechas 	= ($countFlujosTerminadosRangoFechas / (int) $countFlujosTotalesRangoFechas) * 100;
			} else {
				$porcentajeFlujosCompletadosRangoFechas 	= 0;
			}
			if ($countFlujosCotizadosRangoFechas > 0) {
				$porcentajeFlujosCotizadosRangoFecha 		= ( $countFlujosCotizadosRangoFechas / $countFlujosProcesoRangoFechas ) * 100;
			} else {
				$porcentajeFlujosCotizadosRangoFecha 		= 0;
			}
			if ($countFlujosCanceladosRangoFechas > 0) {
				$porcentajeFlujosCanceladosRangoFecha		= ( $countFlujosCanceladosRangoFechas / $countFlujosTotalesRangoFechas ) * 100;
			} else {
				$porcentajeFlujosCanceladosRangoFecha 		= 0;
			}
    		$this->set(compact('countFlujosAsignadosRangoFechas','countFlujosProcesoRangoFechas','countFlujosCanceladosRangoFechas','countFlujosTerminadosRangoFechas','totalHorasDemoraFlujosRangoFechas','demoraFlujosCotizarRangoFechas','demoraFlujosContactadoRangoFechas','countFlujosRetrasoRangoFechas','countFlujosTotalesRangoFechas','porcentajeFlujosCompletadosRangoFechas','countFlujosCotizadosRangoFechas','porcentajeFlujosCotizadosRangoFecha','porcentajeFlujosCanceladosRangoFecha','countFlujosNoValidos'));
    	}
    }

    public function getInfoEfectivity(){
    	$dataByUser = array();
    	$this->autoRender = false;
    	$fecha_ini = $this->request->data["fecha_ini"];
    	$fecha_fin = $this->request->data["fecha_end"];
    	$usuarios 		 		= $this->ProspectiveUser->User->role_asesor_comercial_users_all_true();
    	$totalVentas = 0;
    	foreach ($usuarios as $key => $value) {
    		$ids_flujos_asignados 	= $this->ProspectiveUser->FlowStage->consult_idsFlujos_empresa_flujos_asignado_asesor($fecha_ini,$fecha_fin,$value["User"]["id"]);
    		$cotizados 				= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_cotizados($fecha_ini,$fecha_fin,$value["User"][
    			"id"],$ids_flujos_asignados);
    		$ventas 				= $this->ProspectiveUser->getSalesCount($fecha_ini,$fecha_fin, $value["User"]["id"]);
    		// $this->ProspectiveUser->FlowStage->consult_information_user_flujos_ventas($fecha_ini,$fecha_fin,$value["User"]["id"],$ids_flujos_asignados);
    		$totalVentas+= $ventas;

    		$datos = new StdClass();

    		$datos->name = $this->getName($value["User"]["name"]);
    		$datos->y = intval($ventas);
    		$datos->efectividad = $cotizados == 0 ? 0 : floatval(bcdiv(( ($ventas/$cotizados)*100 ), '1', 2));
    		$datos->ventas = intval($ventas);
    		$datos->cotizados = $cotizados;

    		$dataByUser[] = $datos;
    	}
    	return json_encode(array("total" => $totalVentas, "datos" => $dataByUser));
    }

    public function getInfoQuotation(){
    	$dataByUser = array();
    	$series = array();
    	$drilDown = new StdClass();
    	$drilDown->series = array();
    	$this->autoRender = false;
    	$fecha_ini = $this->request->data["fecha_ini"];
    	$fecha_fin = $this->request->data["fecha_end"];
    	$usuarios 		 		= $this->ProspectiveUser->User->role_asesor_comercial_users_all_true();
    	$total = 0;

    	foreach ($usuarios as $key => $value) {
    		$ids_flujos_asignados 	= $this->ProspectiveUser->FlowStage->consult_idsFlujos_empresa_flujos_asignado_asesor($fecha_ini,$fecha_fin,$value["User"]["id"]);
    		$cotizados 				= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_cotizadosByDay($fecha_ini,$fecha_fin,$value["User"][
    			"id"],$ids_flujos_asignados);
    		$total+=$cotizados["total"];
    		$datos = new StdClass();

    		$datos->name = $this->getName($value["User"]["name"]);
    		$datos->y = intval($cotizados["total"]);
    		$datos->drilldown = md5($value["User"]["id"]);
    		$series[] = $datos;

    		if(!empty($cotizados["fecha"])){

    			$datosFecha = new StdClass();
    			$datosFecha->name = $this->getName($value["User"]["name"]);
    			$datosFecha->id = md5($value["User"]["id"]);
    			$datosFecha->data = array();

    			foreach ($cotizados["fecha"] as $fecha => $totalFecha) {
    				$fechas = new StdClass();
    				$fechas->name = $fecha;
    				$fechas->y = $totalFecha;
    				$fechas->drilldown = $fecha."-".md5($value["User"]["id"]);
    				$datosFecha->data[] = $fechas;
    			}
    			$drilDown->series[] = $datosFecha;

    			foreach ($cotizados["fecha"] as $fecha => $totalFecha) {


    				if(!empty($cotizados["cliente"])){

		    			$datosCliente = new StdClass();
		    			$datosCliente->name = $fecha;
		    			$datosCliente->id 	= $fecha."-".md5($value["User"]["id"]);
		    			$datosCliente->data = array();

		    			foreach ($cotizados["cliente"][$fecha] as $cliente => $totalFecha) {
		    				$fechasClient = new StdClass();
		    				$fechasClient->name = $cliente;
		    				$fechasClient->y 	= $totalFecha;
		    				$datosCliente->data[] = $fechasClient;
		    			}
		    			$drilDown->series[] = $datosCliente;
		    			
		    		}
    			}

    		}


    	}
    	return json_encode(array("series" => $series, "drilldown" => $drilDown->series,"total" => $total));
    }

    public function getInfoProspect(){
    	$dataByUser = array();
    	$this->autoRender = false;
    	$fecha_ini = $this->request->data["fecha_ini"];
    	$fecha_fin = $this->request->data["fecha_end"];
    	$usuarios 		 		= $this->ProspectiveUser->User->role_asesor_comercial_users_all_true();
    	$totalAsignados = 0;
    	foreach ($usuarios as $key => $value) {
    		$ids_flujos_asignados 	= $this->ProspectiveUser->FlowStage->consult_idsFlujos_empresa_flujos_asignado_asesor($fecha_ini,$fecha_fin,$value["User"]["id"]);
    		$asignados 				= $this->ProspectiveUser->FlowStage->count_information_empresa_flujos_asignado_user($fecha_ini,$fecha_fin,$value["User"]["id"],$ids_flujos_asignados);
    		$contactados 			= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_contactados($fecha_ini,$fecha_fin,$value["User"][
    			"id"],$ids_flujos_asignados);
    		$totalAsignados+= $asignados;

    		$datos = new StdClass();

    		$datos->name = $this->getName($value["User"]["name"]);
    		$datos->y = $asignados;
    		$datos->contactados = $contactados;

    		$dataByUser[] = $datos;
    	}
    	return json_encode(array("total" => $totalAsignados, "datos" => $dataByUser));
    }

    public function getInfoDemoras(){
    	$this->autoRender 	= false;
    	$series 			= array();
    	$demorasContactar 	= array();
    	$demorasCotizar 	= array();
    	$categories 		= array();
    	$fecha_ini 			= $this->request->data["fecha_ini"];
    	$fecha_fin 			= $this->request->data["fecha_end"];
    	$usuarios 		 		= $this->ProspectiveUser->User->role_asesor_comercial_users_all_true();
    	$totalDemoras = 0;
    	foreach ($usuarios as $key => $value) {
    		$ids_flujos_asignados 	= $this->ProspectiveUser->FlowStage->consult_idsFlujos_empresa_flujos_asignado_asesor($fecha_ini,$fecha_fin,$value["User"]["id"]);
    		$demora_contactar 					= $this->ProspectiveUser->AtentionTime->time_demora_contactado_rango_fechas_user($fecha_ini,$fecha_fin,$value["User"]["id"],$ids_flujos_asignados);
			$demora_cotizar 					= $this->ProspectiveUser->AtentionTime->time_demora_cotizado_rango_fechas_user($fecha_ini,$fecha_fin,$value["User"]["id"],$ids_flujos_asignados);
    		$totalDemoras+= $demora_contactar;
    		$totalDemoras+= $demora_cotizar;

    		$demorasContactar[] = $demora_contactar;
    		$demorasCotizar[] 	= $demora_cotizar;

    		$categories[] = $this->getName($value["User"]["name"]);
    	}

    	$cotizadosObj = new StdClass();
    	$cotizadosObj->name = "Horas de demora en cotizar";
    	$cotizadosObj->data = $demorasCotizar;

    	$contactadosObj = new StdClass();
    	$contactadosObj->name = "Horas de demora en contactar";
    	$contactadosObj->data = $demorasContactar;

    	$series[] = $contactadosObj;
    	$series[] = $cotizadosObj;

    	return json_encode(array("total" => $totalDemoras, "categories" => $categories,"series" => $series));
    }

    public function getInfoVentas(){
    	$dataByUser = array();
    	$this->autoRender = false;
    	$fecha_ini = $this->request->data["fecha_ini"];
    	$fecha_fin = $this->request->data["fecha_end"];
    	$this->loadModel("FlowStage");
    	$usuarios  		= $this->ProspectiveUser->User->role_asesor_comercial_users_all_true();
    	
    	$totalVentasFinal = 0;
        $this->loadModel("Salesinvoice");
    	foreach ($usuarios as $key => $value) {
    		
            $salesNormal    = $this->ProspectiveUser->getSales( $fecha_ini,$fecha_fin, $value["User"]["id"] );
            $salesAditional = $this->Salesinvoice->getSales( $fecha_ini,$fecha_fin, $value["User"]["id"]);

            $totalVentas    = $salesNormal+$salesAditional;
			$totalVentasFinal+=$totalVentas;
			$datos = new StdClass();

    		$datos->name = $this->getName($value["User"]["name"]);
    		$datos->y = floatval($totalVentas);

    		$dataByUser[] = $datos;
    		
    	}
    	return json_encode(array("total" => $totalVentasFinal, "datos" => $dataByUser));
    }

    private function setDetailsData($detalles){
        $datosMes    = [];
        $datosDia    = [];
        $datosMesDev = [];
        $datosDiaDev = [];
        $labelsMeses = [];
        $labelsDias  = [];


        if (!empty($detalles)) {
            $usuarios = [];
            $meses    = [];
            $dias     = [];
            foreach ($detalles as $key => $value) {
                // $value->mes = str_pad($input, 10, "-=", STR_PAD_LEFT);
                if (!array_key_exists($value->Identificacion, $usuarios)) {
                    $usuarios[$value->Identificacion] = $value->NombreAsesor;
                }
                if (!array_key_exists($value->anio."-".$value->mes, $meses)) {
                    $meses[$value->anio."-".$value->mes] = $value->anio."-".$value->mes;
                }
                if (!array_key_exists($value->anio."-".$value->mes."-".$value->dia, $dias)) {
                    $dias[$value->anio."-".$value->mes."-".$value->dia] = $value->anio."-".$value->mes."-".$value->dia;
                }
            }

            foreach ($usuarios as $identificacion => $nombre) {
                foreach ($meses as $key => $mes) {
                    $datosMes[$nombre][$mes] = 0;
                }
                foreach ($dias as $key => $dia) {
                    $datosDia[$nombre][$dia] = 0;
                }
            }

            $datosMesDev = $datosMes;
            $datosDiaDev = $datosDia;

            foreach ($detalles as $key => $value) {
                $datosMes[$value->NombreAsesor][$value->anio."-".$value->mes] += floatval($value->Ventas);
                $datosDia[$value->NombreAsesor][$value->anio."-".$value->mes."-".$value->dia] += floatval($value->Ventas);

                $datosMesDev[$value->NombreAsesor][$value->anio."-".$value->mes] += floatval($value->Devoluciones);
                $datosDiaDev[$value->NombreAsesor][$value->anio."-".$value->mes."-".$value->dia] += floatval($value->Devoluciones);
            }

            $datosMesCop = $datosMes;
            $datosDiaCop = $datosDia;
            $datosMesDevCop = $datosMesDev;
            $datosDiaDevCop = $datosDiaDev;        

            $labelsMeses = array_values($meses);
            $labelsDias  = array_values($dias);

            $datosMes = $datosDia = $datosMesDev = $datosDiaDev = [];
            
            foreach ($usuarios as $identificacion => $name) {
                $serieMes       = new stdClass();
                $serieMes->name = $name;
                $serieMes->data = [];

                foreach ($datosMesCop[$name] as $key => $value) {
                    $serieMes->data[] = $value;
                }

                $serieDia       = new stdClass();
                $serieDia->name = $name;
                $serieDia->data = [];

                foreach ($datosDiaCop[$name] as $key => $value) {
                    $serieDia->data[] = $value;
                }

                $serieMesDev       = new stdClass();
                $serieMesDev->name = $name;
                $serieMesDev->data = [];

                foreach ($datosMesDevCop[$name] as $keyDev => $valueDev) {
                    $serieMesDev->data[] = $valueDev;
                }

                $serieDiaDev       = new stdClass();
                $serieDiaDev->name = $name;
                $serieDiaDev->data = [];

                foreach ($datosDiaDevCop[$name] as $key => $value) {
                    $serieDiaDev->data[] = $value;
                }
                $datosMes[] = $serieMes;   
                $datosDia[] = $serieDia;  
                $datosMesDev[] = $serieMesDev;   
                $datosDiaDev[] = $serieDiaDev; 
            }
        }


        $this->set(compact("datosMes","datosDia","labelsMeses","labelsDias","datosMesDev","datosDiaDev"));
    }

    public function getInfoByEmployee($ventas){
        $totalVentas = 0;
        $vendedores  = [];
        $totalVendedores = [];
        if (!empty($ventas->listado)) {
            foreach ($ventas->listado as $key => $value) {
                if (AuthComponent::user("role") == "Asesor Externo") {
                    if ($value->IdVendedor != AuthComponent::user("identification")) {
                        continue;
                    }else{
                        $totalVentas += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos); 
                    }
                }else{
                    $totalVentas += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);
                }
                if (!array_key_exists(strval($value->IdVendedor), $vendedores)) {
                    $vendedores[strval($value->IdVendedor)] = $value->NombreVendedor;
                }

                if (!array_key_exists($value->IdVendedor, $totalVendedores)) {
                    $totalVendedores[$value->IdVendedor] = floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);
                }else{
                    $totalVendedores[$value->IdVendedor] += floatval(is_null($value->Total_Venta) ? 0 : $value->Total_Venta )  - floatval(is_null($value->Total_Descuentos) ? 0 : $value->Total_Descuentos);
                }
                
            }
        }
        arsort($totalVendedores);
        return compact("totalVendedores","vendedores","totalVentas");
    }

    public function getEfetivityByUser($fecha_inicio,$fecha_fin,$user_id, $noLast = false){

        if (!$noLast) {            
            $lastMont               = date("m",strtotime($fecha_inicio. ' -1 month'));
            $lastYear               = date("Y",strtotime($fecha_inicio. ' -1 month'));

            $fecha_inicio           = date($lastYear.'-'.$lastMont.'-01');
            $fecha_fin              = date($lastYear.'-m-t',strtotime($fecha_inicio));
            $discardFlows           = $this->ProspectiveUser->find("all",["conditions"=>["origin"=>"Robot","DATE(ProspectiveUser.created) <="=> $fecha_fin, "DATE(ProspectiveUser.created) >=" => $fecha_inicio, "ProspectiveUser.user_id" => $user_id, "ProspectiveUser.state_flow" => [1,2,3] ] ]);


            if($fecha_inicio >= '2025-07-01'){
                $discardFlows           = [];
            }



        }else{
            $discardFlows           = $this->ProspectiveUser->find("all",["conditions"=>["origin"=>"Robot","DATE(ProspectiveUser.created) <="=> $fecha_fin, "DATE(ProspectiveUser.created) >=" => $fecha_inicio, "ProspectiveUser.user_id" => $user_id, "ProspectiveUser.state_flow" => [1,2,3] ] ]);
            if($fecha_inicio >= '2025-07-01'){
                $discardFlows           = [];
            }
        }

        if(empty($discardFlows)){
            $discardFlows = 0;
        }else{
            $copyDiscard = $discardFlows;
            $discardFlows = [];

            foreach ($copyDiscard as $key => $value) {
                if($value["ProspectiveUser"]["state_flow"] == !empty($value["ProgresNote"])){
                    $discardFlows[] = $value["ProspectiveUser"]["id"];
                }
            }
            if(empty($discardFlows)){
                $discardFlows = 0;
            }
        }

        $totalAssginedFinal     = $this->ProspectiveUser->find("list",["conditions"=>["DATE(ProspectiveUser.created) <="=> $fecha_fin, "DATE(ProspectiveUser.created) >=" => $fecha_inicio, "user_id" => $user_id, "id !=" => $discardFlows  ], "fields" => ["id","id"] ]);

        $totalLose              = $this->ProspectiveUser->find("list",["conditions"=>["DATE(ProspectiveUser.created) <="=> $fecha_fin, "DATE(ProspectiveUser.created) >=" => $fecha_inicio, "user_lose" => $user_id, "ProspectiveUser.id !="=> $totalAssginedFinal ],"fields" => ["id","id"]]);


        $numCancelados        = $this->ProspectiveUser->find("count",["conditions" => ["ProspectiveUser.user_id" => $user_id, "ProspectiveUser.id" => ($totalAssginedFinal) , "DATE(ProspectiveUser.created) >="=> $fecha_inicio, "DATE(ProspectiveUser.created) <=" => $fecha_fin, "ProspectiveUser.state_flow" => [7,9] ] ]);

        $numCotizados           = $this->ProspectiveUser->FlowStage->consult_information_user_flujos_cotizados_new($fecha_inicio,$fecha_fin,$user_id,$totalAssginedFinal);
        
        $numCotizados           = $numCotizados+count($totalLose);


        $allData                = $this->ProspectiveUser->find("all",["conditions" => ["ProspectiveUser.user_id" => $user_id, "ProspectiveUser.id" => $totalAssginedFinal, "ProspectiveUser.bill_text !="=>null ] ]);
            
        $infoFinal = $this->extractInformationFromFlows($allData,$fecha_fin);

        $percentCancel = $this->ProspectiveUser->User->field("percent_deleted",["id"=>$user_id]);

        $totalPermision = $percentCancel / 100;

        $totalByCancel  = round((count($totalAssginedFinal) + count($totalLose)) * $totalPermision);

        if($numCancelados > $totalByCancel){
            $numCancelados = $totalByCancel;
        }


        if ($numCotizados <= 0 || is_null($numCotizados)) {
            $numCotizados = 0;
            $efectividad  = 0;
            $numVentas    = 0;
        }else{
            // $efectividad = round($infoFinal["numberVentas"]/$numCotizados*100,2);
            $efectividad = round($infoFinal["numberVentas"]/ (count($totalAssginedFinal) - $numCancelados) *100,2);
            $numVentas   = $infoFinal["numberVentas"];
        }

        $totalAsignados  = count($totalAssginedFinal) - $numCancelados;

        $totalLose       = count($totalLose);

        return compact('totalAsignados','numCotizados','efectividad','numVentas','finalIdsAssined','totalLose');
    }

    public function cancelaciones_flujo()
    {
        $this->validateDatesForReports();
        $ini = $this->request->query["ini"];
        $end = $this->request->query["end"];

        $flows_range_days = $this->ProspectiveUser->find("all",["conditions" => [
            "DATE(ProspectiveUser.created) >=" => $ini,
            "DATE(ProspectiveUser.created) <=" => $end,
            "ProspectiveUser.user_id !=" => 0
        ] ]);


        $usuarios       = [];
        $states         = [0,9,7,10];
        $ventas         = [];
        $remarketing    = [];
        $finalData      = [];
        $fechas         = [];
        $fechas_total   = [];
        $fechas_arr     = [];

        foreach ($flows_range_days as $key => $value) {
            if (!array_key_exists($value["User"]["id"],$usuarios)) {
                $usuarios[$value["User"]["id"]] = $value["User"]["name"];
            }
            $finalData[$value["User"]["id"]] = [];
            if (!is_null($value["ProspectiveUser"]["date_lose"]) && !in_array($value["ProspectiveUser"]["date_lose"], $fechas) ) {
                $fechas[] = $value["ProspectiveUser"]["date_lose"];
                $fechas_total[$value["ProspectiveUser"]["date_lose"]] = 0;
            }
        }

        foreach ($finalData as $user_id => $value) {
            foreach ($states as $key => $state) {
                $finalData[$user_id][$state] = 0;
            }
            foreach ($fechas as $key => $fecha) {
                $fechas_arr[$user_id][$fecha] = 0;
            }
        }



        foreach ($flows_range_days as $key => $value) {

            if(in_array($value["ProspectiveUser"]["state_flow"], $states)){
                $finalData[$value["User"]["id"]][$value["ProspectiveUser"]["state_flow"]] += 1;
            }else{
                $finalData[$value["User"]["id"]][0] += 1;
            }


            if (!is_null($value["ProspectiveUser"]["user_lose"]) && $value["ProspectiveUser"]["user_lose"] != 123 ) {
                if ($value["ProspectiveUser"]["user_lose"] != $value["ProspectiveUser"]["user_id"] && $value["ProspectiveUser"]["returned"] != 1 ) {
                    $finalData[$value["ProspectiveUser"]["user_lose"]][10] += 1;  
                    if (!is_null($value["ProspectiveUser"]["date_lose"])) {
                        $fechas_arr[$value["ProspectiveUser"]["user_lose"]][$value["ProspectiveUser"]["date_lose"]]+=1;                                              
                        $fechas_total[$value["ProspectiveUser"]["date_lose"]]+=1;                                              
                    }   
                }
            }
        }

        $this->set(compact("usuarios","finalData","remarketing","ventas","fechas_arr","fechas","fechas_total"));

    }

    public function gestion_flujos()
    {
        $this->validateDatesForReports();
        $ini = $this->request->query["ini"];
        $end = $this->request->query["end"];

        $flows_range_days = $this->ProspectiveUser->find("all",["conditions" => [
            "DATE(ProspectiveUser.created) >=" => $ini,
            "DATE(ProspectiveUser.created) <=" => $end,
            "ProspectiveUser.user_id !=" => 0
        ] ]);


        $usuarios       = [];
        $states         = [1,2,3,4,5,6,7,8,9,10];
        $ventas         = [];
        $remarketing    = [];
        $finalData      = [];
        $fechas         = [];
        $fechas_total   = [];
        $fechas_arr     = [];
        $totalDentro    = [];
        $totalFuera     = [];
        $totalRobot     = [];
        $totalVentasRobot    = [];
        $totalFactRobot    = [];

        foreach ($flows_range_days as $key => $value) {
            if (!array_key_exists($value["User"]["id"],$usuarios)) {
                $usuarios[$value["User"]["id"]] = $value["User"]["name"];
            }
            $finalData[$value["User"]["id"]] = [];
            $totalRobot[$value["User"]["id"]] = 0;
            $totalVentasRobot[$value["User"]["id"]] = 0;
            $totalFactRobot[$value["User"]["id"]] = 0;
            $totalFuera[$value["User"]["id"]] = 0;
            $totalDentro[$value["User"]["id"]] = 0;
            $remarketing[$value["User"]["id"]] = 0;
            $ventas[$value["User"]["id"]] = 0;
            if (!is_null($value["ProspectiveUser"]["date_lose"]) && !in_array($value["ProspectiveUser"]["date_lose"], $fechas) ) {
                $fechas[] = $value["ProspectiveUser"]["date_lose"];
                $fechas_total[$value["ProspectiveUser"]["date_lose"]] = 0;
            }
        }

        foreach ($finalData as $user_id => $value) {
            foreach ($states as $key => $state) {
                $finalData[$user_id][$state] = 0;
            }
            foreach ($fechas as $key => $fecha) {
                $fechas_arr[$user_id][$fecha] = 0;
            }
        }



        foreach ($flows_range_days as $key => $value) {
            $finalData[$value["User"]["id"]][$value["ProspectiveUser"]["state_flow"]] += 1;
            if ($value["ProspectiveUser"]["origin"] == "Marketing" || $value["ProspectiveUser"]["origin"] == "Remarketing") {
                $remarketing[$value["User"]["id"]] += 1;
            }

            if($value["ProspectiveUser"]["origin"] == "Robot"){
                $totalRobot[$value["User"]["id"]]+=1;
            }

            if (!is_null($value["ProspectiveUser"]["bill_text"])) {
                $ventas[$value["User"]["id"]] += 1;
            }

            if (!is_null($value["ProspectiveUser"]["bill_text"]) && $value["ProspectiveUser"]["origin"] == "Robot") {
                $totalVentasRobot[$value["User"]["id"]] += 1;
                $totalFactRobot[$value["User"]["id"]] += intval($value["ProspectiveUser"]["bill_value"]);
            }

            $getOutIn = $this->setOutInDate($value["ProspectiveUser"]["created"]);

            if($getOutIn){
               $totalFuera[$value["User"]["id"]] += 1;
            }else{
               $totalDentro[$value["User"]["id"]] += 1;                
            }

            if (!is_null($value["ProspectiveUser"]["user_lose"]) && $value["ProspectiveUser"]["user_lose"] != 123 ) {
                if ($value["ProspectiveUser"]["user_lose"] != $value["ProspectiveUser"]["user_id"] && $value["ProspectiveUser"]["returned"] != 1 ) {
                    $finalData[$value["ProspectiveUser"]["user_lose"]][10] += 1;  
                    if (!is_null($value["ProspectiveUser"]["date_lose"])) {
                        $fechas_arr[$value["ProspectiveUser"]["user_lose"]][$value["ProspectiveUser"]["date_lose"]]+=1;                                              
                        $fechas_total[$value["ProspectiveUser"]["date_lose"]]+=1;                                              
                    }   
                }
            }
        }

        $this->set(compact("usuarios","finalData","remarketing","ventas","fechas_arr","fechas","fechas_total","totalFuera","totalDentro","totalRobot","totalVentasRobot","totalFactRobot"));

    }


    public function setOutInDate($fecha){
        $newHour = date("H",strtotime($fecha));
        $newMin = date("i",strtotime($fecha));
        $newDay = date("D",strtotime($fecha));

        $fuera = false;
        if(in_array(date("Y-m-d",strtotime($fecha)), Configure::read("variables.diasFestivos"))){
            $fuera = true;
        }elseif($newDay == "Sat"){
            if($newHour > 11 || $newHour < 8){
                $fuera = true;
            }else{
                $fuera = false;
            }
        }elseif($newDay == 'Sun'){
            $fuera = true;
        }elseif($newHour < 8){
            $fuera = true;
        }elseif($newHour >= 18){
            $fuera = true;
        }

        return $fuera;
    }

    public function ventas_report() {
        $this->validateDatesForReports();
        $ventas             = $this->postWoApi(["ini" => $this->request->query["ini"], "end" => $this->request->query["end"] ],"documents");
        $ventasMesActual    = $this->postWoApi(["ini" => date("Y-m-01"), "end" => date("Y-m-t") ],"documents");
        $detalles           = $this->postWoApi(["ini" => $this->request->query["ini"], "end" => $this->request->query["end"] ],"details");

        $this->setDetailsData($detalles);
        $totalVentasRango   = 0;
        $totalVentasPeriodo = 0;
        $ventasRango = $this->getInfoByEmployee($ventas);
        $ventasMes   = $this->getInfoByEmployee($ventasMesActual);
        $totalVentasRango = $ventasRango["totalVentas"];
        $totalVentasMes   = $ventasMes["totalVentas"];
        $vendedores  = isset($ventasRango["vendedores"]) && !empty($ventasRango["vendedores"]) ? $ventasRango["vendedores"] : [];

        if (isset($ventasMes["vendedores"]) && !empty($ventasMes["vendedores"])) {
            foreach ($ventasMes["vendedores"] as $key => $value) {
                if (!array_key_exists($key, $vendedores)) {
                    $vendedores[$key] = $value;
                }
            }
        }

        $dataFinal = [];
        $this->loadModel("Goal");

        foreach ($vendedores as $identification => $full_name) {
            $nameParts = explode(" ", $full_name);
            $meta      = $this->Goal->field(date("m"), [ "year" => date("Y"), "name" => $nameParts[0]." ".$nameParts[1] ] );
            $user_id   = $this->Goal->field('user_id', [ "year" => date("Y"), "name" => $nameParts[0]." ".$nameParts[1] ] );
            $metaDiv   = $meta;
            if (is_null($meta) || empty($meta)) {
                $meta    = 0;
                $metaDiv = 1;
                $user_id = 0;
            }

            $totalMes  = isset($ventasMes["totalVendedores"][$identification]) ? ($ventasMes["totalVendedores"][$identification]) : 0;

            $dataEfPer = $user_id != 0 ? $this->getEfetivityByUser($this->request->query["ini"],$this->request->query["end"],$user_id,true) : 0;

            $dataFinal[] = [
                "name" => $full_name,
                "meta_mes" => $meta,
                "total_periodo" => isset($ventasRango["totalVendedores"][$identification]) ? ($ventasRango["totalVendedores"][$identification]) : 0,
                "total_mes" => $totalMes,
                "cumplimiento" => round(($totalMes / floatval($metaDiv) )  * 100,2),
                "efectividad_periodo" => $dataEfPer == 0 ? 0 : $dataEfPer["efectividad"],
                "efectividad_mes" => $user_id != 0 ? $this->getEfetivityByUser(date("Y-m-01"), date("Y-m-t"),$user_id,true)['efectividad'] : 0,
                "asignados_periodo" => is_null($dataEfPer['totalAsignados']) ? 0 : $dataEfPer['totalAsignados'],
                "cotizados_periodo" => is_null($dataEfPer['numCotizados']) ? 0 : $dataEfPer['numCotizados'],
                "num_ventas" => is_null($dataEfPer['numVentas']) ? 0 : $dataEfPer['numVentas'],
            ];

        }

        $this->set(compact("totalVentasRango","dataFinal",'totalVentasMes','ventas'));
    }

    public function report_advisers(){
    	$this->validateDatesForReports();
    	$usuarios 		 				= $this->ProspectiveUser->User->role_asesor_comercial_user_true();
    	$num_usuarios	 				= count($usuarios);
    	$this->set(compact('usuarios','num_usuarios'));
    }

    public function consult_informe_asesores(){
    	$this->layout 				= false;
		if ($this->request->is('ajax')) {
			$position 											= $this->request->data['POSITION'];
			$user_id 											= $this->request->data['user_id'];
			$fecha_ini 											= $this->request->data['fecha_ini'];
			$fecha_fin 											= $this->request->data['fecha_fin'];
			$dias_date_data 									= array();
    		$datos_asesor 										= $this->ProspectiveUser->User->get_data($user_id);
    		$dias_date_data['name_asesor']						= $datos_asesor['User']['name'];
    		$dias_date_data['img_asesor']						= $datos_asesor['User']['img'];
    		$ids_flujos_asignados 								= $this->ProspectiveUser->FlowStage->consult_idsFlujos_empresa_flujos_asignado_asesor($fecha_ini,$fecha_fin,$user_id);
    		$dias_date_data['asignados']						= $this->ProspectiveUser->FlowStage->count_information_empresa_flujos_asignado_user($fecha_ini,$fecha_fin,$user_id,$ids_flujos_asignados);
    		$dias_date_data['contactados']						= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_contactados($fecha_ini,$fecha_fin,$user_id,$ids_flujos_asignados);
    		$dias_date_data['cotizados']						= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_cotizados($fecha_ini,$fecha_fin,$user_id,$ids_flujos_asignados);
    		$dias_date_data['ventas']							= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_ventas($fecha_ini,$fecha_fin,$user_id,$ids_flujos_asignados);
    		if ($dias_date_data['asignados'] <= 0) {
    			$dias_date_data['efectividad'] 					= 0;
    		} else {
    			if ($dias_date_data['ventas'] > 0) {
    				$dias_date_data['efectividad']				= ($dias_date_data['ventas'] * 100) / $dias_date_data['asignados'];
    			} else {
    				$dias_date_data['efectividad'] 				= 0;
    			}
    		}
    		$demoraFlujosContactadoRangoFechas 					= $this->ProspectiveUser->AtentionTime->time_demora_contactado_rango_fechas_user($fecha_ini,$fecha_fin,$user_id,$ids_flujos_asignados);
			$demoraFlujosCotizarRangoFechas 					= $this->ProspectiveUser->AtentionTime->time_demora_cotizado_rango_fechas_user($fecha_ini,$fecha_fin,$user_id,$ids_flujos_asignados);
    		if ($demoraFlujosContactadoRangoFechas > 0) {
    			$dias_date_data['demora_contactar'] 			= $demoraFlujosContactadoRangoFechas;
    		} else {
    			$dias_date_data['demora_contactar'] 			= 0;
    		}
			if ($demoraFlujosCotizarRangoFechas > 20) {
				$dias_date_data['demora_cotizar'] 				= $demoraFlujosCotizarRangoFechas;
			} else {
				$dias_date_data['demora_cotizar'] 				= 0;
			}
			$this->set(compact('dias_date_data','position'));
		}
    }

    public function report_user(){
    	$this->layout 			= false;
		if ($this->request->is('ajax')) {
			$user_id 			= $this->request->data['user_id'];
			$date_inicio 		= $this->request->data['date_inicio'];
			$date_fin 			= $this->request->data['date_fin'];
			$datos 				= $this->ProspectiveUser->AtentionTime->get_atentions_user_rango_fechas($user_id,$date_inicio,$date_fin);
			$user               = $this->ProspectiveUser->User->findById($user_id);
			$this->set(compact('datos','user'));
		}
    }

    public function consultProspectosContactadosDaysUser(){
    	$this->autoRender 			= false;
		if ($this->request->is('ajax')) {
	    	$fecha_inicio 				= $this->request->data['fecha_inicio'];
	    	$fecha_fin 					= $this->request->data['fecha_fin'];
			$user_id 					= $this->request->data['user_id'];
			$state 						= $this->request->data['state'];
			$dias_transcurridos 		= $this->request->data['dias'];
			$ids_flujos_asignados 		= $this->ProspectiveUser->FlowStage->consult_idsFlujos_user_flujos_asignado($fecha_inicio,$fecha_fin,$user_id);
			$fechasArray 				= array();
			for ($i=0; $i < $dias_transcurridos; $i++) { 
				$nuevafecha 		= strtotime ( '+'.$i.' day' , strtotime ( $fecha_inicio ) ) ;
				$nuevafecha 		= date ( 'Y-m-j' , $nuevafecha );
				$fechasArray[] 		= $this->ProspectiveUser->FlowStage->consult_number_data_day_prospectos_contactados($nuevafecha,$user_id,$state,$ids_flujos_asignados);
			}
			return json_encode($fechasArray);
		}
    }

    public function dateDaysGraficaRangeFechas(){
    	$this->autoRender 			= false;
		if ($this->request->is('ajax')) {
	    	$fecha_inicio 				= $this->request->data['fecha_inicio'];
			$dias_transcurridos 		= $this->request->data['dias'];
			$fechasArray 				= array();
			for ($i=0; $i <= $dias_transcurridos; $i++) { 
				$nuevafecha 		= strtotime ( '+'.$i.' day' , strtotime ( $fecha_inicio ) ) ;
				$fechasArray[] 		= date ( 'Y-m-d' , $nuevafecha );
			}
			return json_encode($fechasArray);
		}
    }

    public function consultNumberVentasDayUser(){
    	$this->autoRender 			= false;
		if ($this->request->is('ajax')) {
	    	$fecha_inicio 				= $this->request->data['fecha_inicio'];
	    	$fecha_fin 					= $this->request->data['fecha_fin'];
			$user_id 					= $this->request->data['user_id'];
			$dias_transcurridos 		= $this->request->data['dias'];
			$ids_flujos_asignados 		= $this->ProspectiveUser->FlowStage->consult_idsFlujos_user_flujos_asignado($fecha_inicio,$fecha_fin,$user_id);
			$fechasArray 				= array();
			for ($i=0; $i < $dias_transcurridos; $i++) { 
				$nuevafecha 		= strtotime ( '+'.$i.' day' , strtotime ( $fecha_inicio ) ) ;
				$nuevafecha 		= date ( 'Y-m-j' , $nuevafecha );
				$fechasArray[] 		= intval($this->ProspectiveUser->getSalesCount($nuevafecha,$nuevafecha, $user_id));
			}
			return json_encode($fechasArray);
		}
    }

    public function report_customer_new(){
    	$this->validateDatesForReports();
    	$datos 												= array();
    	$get 												= $this->request->query;
    	$get["fecha_ini"] = $get["ini"];
    	$get["fecha_fin"] = $get["end"];
    	if (!empty($get)) {
	    	$list_ids_clientes_nuevos_naturales 			= $this->ProspectiveUser->list_ids_range_date_group_cliente_natural($get['fecha_ini'],$get['fecha_fin']);
	    	$list_ids_clientes_nuevos_juridicos 			= $this->ProspectiveUser->lits_ids_range_date_group_cliente_juridico($get['fecha_ini'],$get['fecha_fin']);
	    	$lista_clientes_nuevos 							= array_merge($list_ids_clientes_nuevos_naturales,$list_ids_clientes_nuevos_juridicos);
	    	$countClientesNuevos 							= count($lista_clientes_nuevos);
	    	$datos 											= array();
	    	$get["find"] = isset($get["find"]) ? $get["find"] : "";
    		switch ($get['find']) {
    			case 'flujos_totales':
    				$datos 									= $this->ProspectiveUser->all_data_flujos_ids($lista_clientes_nuevos);
    				break;
    			case 'flujos_vendidos':
    				$ventas_realizadas_fecha 				= $this->ProspectiveUser->FlowStage->payment_verification_sales($get['fecha_ini'],$get['fecha_fin']);
			    	$array_ventas_clientes_nuevos 			= array();
                    if(!empty($array_ventas_clientes_nuevos)){                        
    			    	foreach ($ventas_realizadas_fecha as $valueVentas) {
    						for ($i=0; $i < $countClientesNuevos; $i++) {
    							if ($valueVentas == $lista_clientes_nuevos[$i]) {
    								$array_ventas_clientes_nuevos[] = $valueVentas;
    								$i = $i + $countClientesNuevos;
    							}
    						}
    					}
                    }
    				$datos 									= $this->ProspectiveUser->all_data_flujos_ids($array_ventas_clientes_nuevos);
    				break;
    		}
    	}
    	$this->set(compact('datos'));
    }

    public function report_customer_new_data(){
    	$this->layout 							= false;
		if ($this->request->is('ajax')) {
			$list_ids_clientes_nuevos_naturales 			= $this->ProspectiveUser->list_ids_range_date_group_cliente_natural($this->request->data['fecha_ini'],$this->request->data['fecha_fin']);
			$list_ids_clientes_nuevos_juridicos 			= $this->ProspectiveUser->lits_ids_range_date_group_cliente_juridico($this->request->data['fecha_ini'],$this->request->data['fecha_fin']);
			$lista_clientes_nuevos 							= array_merge($list_ids_clientes_nuevos_naturales,$list_ids_clientes_nuevos_juridicos);
			$countClientesNuevos 							= count($lista_clientes_nuevos);
			$ventas_realizadas_fecha 						= $this->ProspectiveUser->FlowStage->payment_verification_sales($this->request->data['fecha_ini'],$this->request->data['fecha_fin']);
			$array_ventas_clientes_nuevos 					= array();
			if(!is_null($ventas_realizadas_fecha) && !empty($ventas_realizadas_fecha)){
				foreach ($ventas_realizadas_fecha as $valueVentas) {
					for ($i=0; $i < $countClientesNuevos; $i++) {
						if ($valueVentas == $lista_clientes_nuevos[$i]) {
							$array_ventas_clientes_nuevos[] = $valueVentas;
							$i = $i + $countClientesNuevos;
						}
					}
				}
			}
			$totalVentas 									= 0;
			$countVentasClientesNuevos 						= count($array_ventas_clientes_nuevos);
			if ($countVentasClientesNuevos != 0) {
				$totalVerificadosFlujos 					= $this->ProspectiveUser->FlowStage->calculate_total_sales($array_ventas_clientes_nuevos);
				$totalVentas 								= 0;
				for ($i=0; $i < count($totalVerificadosFlujos); $i++) { 
					$totalVentas 							= (int)$totalVerificadosFlujos[$i]['priceQuotation'] + $totalVentas;
				}
			}
			$this->set(compact('totalVentas','countVentasClientesNuevos','countClientesNuevos'));
		}
    }

    public function report_date_flujos(){
    	$this->validateDatesForReports();
    	$usuarios 		 		= $this->ProspectiveUser->User->role_asesor_comercial_users_all_true(true);

        $dataUsers              = $usuarios;
        $usuarios               = [];

        foreach ($dataUsers as $key => $value) {
            $usuarios[$value["User"]["id"]] = $value["User"]["name"];
        }

    	$num_usuarios	 		= count($usuarios);

        $fecha_ini              = $this->request->query['ini'];
        $fecha_fin              = $this->request->query['end'];
        $usersSelecteds         = isset($this->request->query['users']) ? $this->request->query['users'] : [];
        $origin                 = isset($this->request->query['origin']) ? $this->request->query['origin'] : [];

        $countFlujosNoValidos   = $this->ProspectiveUser->count_flujos_no_validos_rango_fechas($fecha_ini,$fecha_fin, $usersSelecteds, $origin);
        $countFlujosValidos     = $this->ProspectiveUser->count_flujos_validos_rango_fechas($fecha_ini,$fecha_fin, $usersSelecteds, $origin);
        $datos                  = $this->ProspectiveUser->all_prospetives_range_date($fecha_ini,$fecha_fin, $usersSelecteds, $origin);
        $dataPrint              = $this->dataByFlujo($datos);

        $ventas                 = $this->postWoApi(["ini" => date("Y-m-d", strtotime($fecha_ini)), "end" => date("Y-m-d", strtotime($fecha_fin)) ],"documents");
        $totalesEstado          = [];
        $prospectosNaturales    = [];
        $prospectosJuridicos    = [];
         if(!empty($datos)){
            $prospectosNaturales    = Set::extract($datos,"{n}.ProspectiveUser");
            $prospectosJuridicos    = Set::extract($datos,"{n}.ProspectiveUser");

            $totalFacturado         = Set::extract($datos,"{n}.ProspectiveUser.bill_value");
            $totalFacturado         = array_sum($totalFacturado);

            $copiaNaturales         = $prospectosNaturales;
            $prospectosNaturales    = [];
            foreach ($copiaNaturales as $key => $value) {
                if($value["clients_natural_id"] != 0 && !is_null($value["clients_natural_id"])  ){
                    $prospectosNaturales[] = $value["clients_natural_id"];
                }

                $value["state_flow"] = $value["state_flow"] == 9 ? 7 : $value["state_flow"];

                if (!array_key_exists($value["state_flow"], $totalesEstado)) {
                    $totalesEstado[$value["state_flow"]] = 1;
                }else{
                    $totalesEstado[$value["state_flow"]] += 1;
                }
                $flujosAsesor[$value["user_id"]][$value["state_flow"]] = 0;
            }

            $copiaJuridicos         = $prospectosJuridicos;
            $prospectosJuridicos    = [];
            foreach ($copiaJuridicos as $key => $value) {
                $value["state_flow"] = $value["state_flow"] == 9 ? 7 : $value["state_flow"];
                if($value["contacs_users_id"] != 0 ){
                    $prospectosJuridicos[] = $value["contacs_users_id"];
                }

                $flujosAsesor[$value["user_id"]][$value["state_flow"]] += 1;

            }
        }
        arsort($totalesEstado);

        $clientesNaturalesViejos = $this->ProspectiveUser->ClientsNatural->find("list",["conditions" => ["created <" => $fecha_ini, "id" => $prospectosNaturales  ] ]);

        $contactosViejos         = $this->ProspectiveUser->ContacsUser->find("list",["conditions" => ["created <" => $fecha_ini, "id" => $prospectosJuridicos  ] ]);

        $totalNaturalesNuevos    = (count($prospectosNaturales) - count($clientesNaturalesViejos)) < 0 ? 0 : count($prospectosNaturales) - count($clientesNaturalesViejos);

        $totalContactosNuevos    = (count($prospectosJuridicos) - count($contactosViejos)) < 0 ? 0 : count($prospectosJuridicos) - count($contactosViejos);

        $faltantes               = count($datos) - $totalNaturalesNuevos - count($clientesNaturalesViejos) - $totalContactosNuevos - count($contactosViejos);

        $datosClientes           = [["CLIENTES NATURALES NUEVOS", $totalNaturalesNuevos],["CLIENTES NATURALES YA EXISTENTES",count($clientesNaturalesViejos)],["EMPRESAS NUEVAS",$totalContactosNuevos],["EMPRESAS YA EXISTENTES",count($contactosViejos)],["CLIENTES DESCONOCIDOS",$faltantes]];

        if (!empty($ventas) && isset($ventas->valores)) {
            $totalVentas = $ventas->valores[0]->Ventas;
        }

        $origenes = array_merge(["Tienda" => "Tienda", "Servicio técnico" => "Servicio técnico"],Configure::read("variables.origenContact"));

        if (empty($origin)) {
            $origenes = array_merge($origenes,["Landing"=>"landing"]);
            $origenes = array_merge($origenes,["landing"=>"Landing"]);
            $origin = $origenes;
        }
        if (empty($usersSelecteds)) {
            $usersSelecteds = array_keys($usuarios);
        }

        if (!empty($flujosAsesor)) {
            $copyAsesor = $flujosAsesor;
            $flujosAsesor = [];
            foreach ($copyAsesor as $key => $value) {
                $flujosAsesor[] = [ $this->getFirstName($usuarios[$key]), array_sum($value) ];
            }
        }

        if (!empty($ventas) && isset($ventas->valores)) {
            $totalVentas = $ventas->valores[0]->Ventas;
        }


    	$this->set(compact('num_usuarios','usuarios','dataPrint','origin','origenes','usersSelecteds','datosClientes','datos','flujosAsesor','totalVentas'));
    }

    public function finde_state_flujo($estado){
        $texto = '';
        switch ($estado) {
            case '0':
                $texto = 'Total flujos';
                break;
            case '1':
                $texto = 'Asignado';
                break;
            case '2':
                $texto = 'Contactado';
                break;
            case '3':
                $texto = 'Cotizado';
                break;
            case '4':
                $texto = 'Negociado';
                break;
            case '5':
                $texto = 'Pagado';
                break;
            case '6':
                $texto = 'Despachado';
                break;
            case '7':
                $texto = 'Cancelado';
                break;
            case '8':
                $texto = 'Terminado';
                break;
            case '9':
                $texto = 'Cancelado';
                break;
        }
        return $texto;
    }

    private function getFirstName($name){
        $texto = explode(" ", $name);
        if (isset($texto[1])) {
            $texto = $texto[0];
        }else{
            $texto = $name;
        }
        return $texto;
    }

    private function dataByFlujo($datos){
        $dataByOrigen   = [];
        $dataByState    = [0=> count($datos), "1"=>0,"2"=>0,"3"=>0,"4"=>0,"5"=>0,"6"=>0,"7"=>0,"8"=>0];
        $origenes       = Configure::read("variables.origenContact");
        $origenes["Tienda"] = "Tienda";
        $origenes["Servicio técnico"] = "Servicio técnico";

        foreach ($origenes as $key => $value) {
            $dataByOrigen[strtolower($key)] = 0;
        }

        if (!empty($datos)) {
            foreach ($datos as $key => $value) {
                $dataByOrigen[strtolower($value["ProspectiveUser"]["origin"])]+=1;
                $state_flow = in_array($value["ProspectiveUser"]["state_flow"], [7,9]) ? 7 : $value["ProspectiveUser"]["state_flow"];
                $dataByState[$state_flow]+=1;

            } 
            $copyOrigin = $dataByOrigen;
            $dataByOrigen = [];
            foreach ($copyOrigin as $key => $value) {
                $dataByOrigen[] = [strtoupper($key),$value];
            }
            $copyDataState = $dataByState;
            $dataByState = [];
            foreach ($copyDataState as $key => $value) {
                $dataByState[] = [strtoupper($this->finde_state_flujo($key)), $value ];
            }
        }
        return compact("dataByState","dataByOrigen");
    }

    public function report_date_advisers_flujos_data(){
        $this->layout                   = false;
        if ($this->request->is('ajax')) {

            $fecha_ini              = $this->request->data['date_inicio'];
            $fecha_fin              = $this->request->data['date_fin'];
            $usuarios               = isset($this->request->data['users']) ? $this->request->data['users'] : [];
            $origin                 = isset($this->request->data['origin']) ? $this->request->data['origin'] : [];

            $countFlujosNoValidos   = $this->ProspectiveUser->count_flujos_no_validos_rango_fechas($fecha_ini,$fecha_fin, $usuarios, $origin);
            $countFlujosValidos     = $this->ProspectiveUser->count_flujos_validos_rango_fechas($fecha_ini,$fecha_fin, $usuarios, $origin);
            $datos                  = $this->ProspectiveUser->all_prospetives_range_date($fecha_ini,$fecha_fin, $usuarios, $origin);

            

            $dataPrnt               = $this->dataByFlujo($datos);

            $ventas              = $this->postWoApi(["ini" => date("Y-m-d", strtotime($fecha_ini)), "end" => date("Y-m-d", strtotime($fecha_fin)) ],"documents");

            if (!empty($ventas) && isset($ventas->valores)) {
                $totalVentas = $ventas->valores[0]->Ventas;
            }


            if (!empty($origin) && (in_array("Landing", $origin) || in_array("landing", $origin) ) ) {
                $landing_prospective_total = $this->ProspectiveUser->landing_prospective_total($fecha_ini,$fecha_fin, $usuarios);
            }else{
                $landing_prospective_total = [];
            }

            $prospectosNaturales    = [];
            $prospectosJuridicos    = [];
            $totalFacturado         = 0;
            $totalesEstado          = [];
            $flujosAsesor           = [];

            if(!empty($datos)){
                $prospectosNaturales    = Set::extract($datos,"{n}.ProspectiveUser");
                $prospectosJuridicos    = Set::extract($datos,"{n}.ProspectiveUser");

                $totalFacturado         = Set::extract($datos,"{n}.ProspectiveUser.bill_value");
                $totalFacturado         = array_sum($totalFacturado);

                $copiaNaturales         = $prospectosNaturales;
                $prospectosNaturales    = [];
                foreach ($copiaNaturales as $key => $value) {
                    if($value["clients_natural_id"] != 0 && !is_null($value["clients_natural_id"])  ){
                        $prospectosNaturales[] = $value["clients_natural_id"];
                    }

                    $value["state_flow"] = $value["state_flow"] == 9 ? 7 : $value["state_flow"];

                    if (!array_key_exists($value["state_flow"], $totalesEstado)) {
                        $totalesEstado[$value["state_flow"]] = 1;
                    }else{
                        $totalesEstado[$value["state_flow"]] += 1;
                    }
                    $flujosAsesor[$value["user_id"]][$value["state_flow"]] = 0;
                }

                $copiaJuridicos         = $prospectosJuridicos;
                $prospectosJuridicos    = [];
                foreach ($copiaJuridicos as $key => $value) {
                    $value["state_flow"] = $value["state_flow"] == 9 ? 7 : $value["state_flow"];
                    if($value["contacs_users_id"] != 0 ){
                        $prospectosJuridicos[] = $value["contacs_users_id"];
                    }

                    $flujosAsesor[$value["user_id"]][$value["state_flow"]] += 1;

                }
            }
            arsort($totalesEstado);

            $clientesNaturalesViejos = $this->ProspectiveUser->ClientsNatural->find("list",["conditions" => ["created <" => $fecha_ini, "id" => $prospectosNaturales  ] ]);

            $contactosViejos         = $this->ProspectiveUser->ContacsUser->find("list",["conditions" => ["created <" => $fecha_ini, "id" => $prospectosJuridicos  ] ]);

            $totalNaturalesNuevos    = (count($prospectosNaturales) - count($clientesNaturalesViejos)) < 0 ? 0 : count($prospectosNaturales) - count($clientesNaturalesViejos);

            $totalContactosNuevos    = (count($prospectosJuridicos) - count($contactosViejos)) < 0 ? 0 : count($prospectosJuridicos) - count($contactosViejos);

            $users                  = array();

            if(!empty($datos)){
                $dataUsers              = Set::extract($datos,"{n}.ProspectiveUser.user_id");
                $users                  = array_unique($dataUsers);
            }

            $this->set(compact('datos','fecha_ini','fecha_fin','countFlujosNoValidos','countFlujosValidos',"users","totalNaturalesNuevos","totalContactosNuevos","clientesNaturalesViejos","contactosViejos",'landing_prospective_total','origin','totalFacturado','totalesEstado','flujosAsesor'));
        }
    }

    public function report_date_flujos_data(){
    	$this->layout 					= false;
		if ($this->request->is('ajax')) {

            $usuarios               = isset($this->request->data['users']) ? $this->request->data['users'] : [];
            $origin                 = isset($this->request->data['origin']) ? $this->request->data['origin'] : [];

			$options_reporte		= array('1' => 'Flujos','2' => 'Clientes nuevos (Flujos - Naturales)','3' => 'Clientes nuevos (Flujos - Juridicos)');
	    	$datos 					= $this->ProspectiveUser->all_prospetives_range_date($this->request->data['date_inicio'],$this->request->data['date_fin'],$usuarios,$origin);
	    	$this->set(compact('datos','options_reporte'));
	    }
    }

    public function consultProspectosWeekUser(){
    	$this->autoRender 					= false;
		if ($this->request->is('ajax')) {
			$user_id 			= $this->request->data['user_id'];
			$dia_num 			= 1;
			$dias_date_data 	= array();
			for ($i=0; $i < 5; $i++) {
				$caduca = date("D",strtotime('-'.$dia_num.' day'));
	            if ($caduca == "Sun"){  
	            	$i = $i + 5;
	            } else {
	                $fecha 				= date('Y-m-d', strtotime('-'.$dia_num.' day'));
	            	$dias_date_data[$i]	= $this->ProspectiveUser->FlowStage->consult_information_user_prospectives_week($fecha,$user_id);
	                $dia_num = $dia_num + 1;
	            }
			}
			for ($j=0; $j < 5; $j++) { 
				if (!isset($dias_date_data[$j])) {
					$dias_date_data[$j] = 0;
				}
			}
			$dias_date_data[5] = $this->request->data['POSITION'];
			return json_encode($dias_date_data);
		}
    }

    public function consultProspectosRangoFechaUser(){
    	$this->autoRender 					= false;
		if ($this->request->is('ajax')) {
			$user_id 			= $this->request->data['user_id'];
			$num_dias 			= $this->request->data['dias'];
			$dia_num 			= 0;
			$dias_date_data 	= array();
			for ($i=0; $i < $num_dias; $i++) {
                $fecha 				= date('Y-m-d', strtotime('-'.$dia_num.' day'));
            	$dias_date_data[$i]	= $this->ProspectiveUser->FlowStage->consult_information_user_prospectives_week($fecha,$user_id);
                $dia_num = $dia_num + 1;
			}
			$dias_date_data[$num_dias] = $this->request->data['POSITION'];
			return json_encode($dias_date_data);
		}
    }

    public function informe_mercadeo(){
        $this->validateDatesForReports();

        $this->loadModel("SbLinea");
        $fecha_ini = $this->request->query["ini"];
        $fecha_fin = $this->request->query["end"];

        $lineas_names = $this->SbLinea->find("all",["fields"=>["DISTINCT  linea"]]);

        $lineas       = [];
        $fechas       = [];


        foreach ($lineas_names as $key => $value) {
            $lineas[ trim($value["SbLinea"]["linea"]) ] = 0;
        }

        $datos = $this->SbLinea->find("all",["conditions" => ["DATE(date_creation) >="=>$fecha_ini, "DATE(date_creation) <="=>$fecha_fin] ]);

        if(!empty($datos)){
            foreach ($datos as $key => $value) {
                $linea = trim($value["SbLinea"]["linea"]);
                if(!isset($lineas[$linea])){
                    $lineas[$linea] = 1;
                }else{
                    $lineas[$linea] += 1;
                }
                $fecha = date("Y-m-d",strtotime($value["SbLinea"]["date_creation"]));
                if (!isset($fechas[$fecha][$linea])) {
                    $fechas[$fecha][$linea] = 1;
                }else {
                    $fechas[$fecha][$linea] ++;
                }
            }
        }

        // var_dump($lineas);
        // var_dump($fechas);


        $this->set(compact("lineas","fechas"));

    }
    
	public function report_management(){
		$this->validateDatesForReports();
        // $ventas   = $this->postWoApi(["ini" => $this->request->query["ini"], "end" => $this->request->query["end"] ],"documents");
		$usuarios 		 		= $this->ProspectiveUser->User->find("list",["fields" => ["id","name"], "conditions" => ["identification !="=> NULL, "identification !=" =>'', 'id' => [3,5,7,11,13,49,122,123,125,126,127] ] ]);
		$this->set(compact('usuarios'));
	}

    private function getValuesUsers($ventas){
        $ventasFinal    = 0;
        $devoluciones   = 0;
        $costo          = 0;
        $indice         = 0;
        $totalDevoluciones = 0;
        $totalVentas       = 0;
        $dates             = [];
        $datesTotal        = [];

        if (!empty($ventas)) {

            foreach ($ventas->listado as $key => $value) {
                $ventasByFact = 0;
                if (intval($value->Total_Descuentos) > 0) {
                    $devoluciones += intval($value->Total_Descuentos);
                    $totalDevoluciones++;
                }else{            
                    $fecha = date("Y-m-d",strtotime($value->Fecha));

                    if (isset($value->Valores->productos_factura)) {
                        foreach ($value->Valores->productos_factura as $keyProduct => $product) {
                            $ventasFinal += ($product->Cantidad * $product->Precio);
                            $ventasByFact += ($product->Cantidad * $product->Precio);
                            $costo += ($product->Cantidad * $product->costo);
                        }
                        $totalVentas++;
                        if (array_key_exists($fecha, $dates)) {
                            $dates[$fecha]      += 1;
                            $datesTotal[$fecha] += intval($ventasByFact);
                        }else{
                            $dates[$fecha]      =  1;
                            $datesTotal[$fecha] =  intval($ventasByFact);
                        }
                    }
                }
            }
        }

        $costo = $costo == 0 ? 1 : $costo;

        $indice = round( ( $ventasFinal/$costo ) ,2);

        return compact("ventasFinal","devoluciones","costo","indice","totalDevoluciones","totalVentas","dates","datesTotal");
    }

    private function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while( $current <= $last ) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    public function ItIsSunday($date)
    {
        $datesHolidays = ["2022-01-01","2022-01-10","2022-03-21","2022-04-14","2022-04-15","2022-05-01","2022-05-30","2022-06-20","2022-06-27","2022-07-04","2022-07-20","2022-08-07","2022-08-15","2022-10-17","2022-11-07","2022-11-14","2022-12-08","2022-12-25","2023-01-01","2023-01-06","2023-01-09","2023-03-19","2023-03-20","2023-04-06","2023-04-07","2023-05-01","2023-05-22","2023-06-12","2023-06-19","2023-06-29","2023-07-03","2023-07-20","2023-08-07","2023-08-15","2023-08-21","2023-10-12","2023-10-16","2023-11-01","2023-11-06","2023-11-11","2023-11-13","2023-12-08","2023-12-25","2024-01-01","2024-01-06","2024-01-08","2024-03-19","2024-03-25","2024-03-28","2024-03-29","2024-05-01","2024-05-13","2024-06-03","2024-06-10","2024-06-29","2024-07-01","2024-07-20","2024-08-07","2024-08-15","2024-08-19","2024-10-12","2024-10-14","2024-11-01","2024-11-04","2024-11-11","2024-12-08","2024-12-25","2025-01-01","2025-01-06","2025-03-19","2025-03-24","2025-04-17","2025-04-18","2025-05-01","2025-06-02","2025-06-23","2025-06-29","2025-06-30","2025-06-30","2025-07-20","2025-08-07","2025-08-15","2025-08-18","2025-10-12","2025-10-13","2025-11-01","2025-11-03","2025-11-11","2025-11-17","2025-12-08","2025-12-25"];
        $strtotimeDate = strtotime($date);
        $saturdayDate = date("l", $strtotimeDate);
        $dateResponse = strtolower($saturdayDate);
        return $dateResponse == "sunday" || in_array($date, $datesHolidays) ? true : false;
    }

    public function getDataByFact($value,$fecha_fin){
        $valorFinal = 0;
        $costo      = 0;
        $actual     = true;

        $fecha      = date("Y-m-d",strtotime($value["datos_factura"]["Fecha"]));

        if (strtotime($fecha) > strtotime($fecha_fin)) {
            $actual = false;
        }

        foreach ($value["productos_factura"] as $keyProduct => $product) {
            $valorFinal += ($product["Cantidad"] * $product["Precio"]);
            $costo      += ($product["Cantidad"] * $product["costo"]);
        }

        return compact("valorFinal","costo","actual");
    }

    public function extractInformationFromFlows($flowsFacts,$fecha_fin){

        $numberVentas       = 0;
        $numberMesNow       = 0;
        $numberMesFuture    = 0;
        $totalValorVentas   = 0;
        $totalCostoVentas   = 0;

        foreach ($flowsFacts as $key => $flowInfo) {

            if ($flowInfo["ProspectiveUser"]["locked"] == 0) {
                $value      = $this->object_to_array(json_decode($flowInfo["ProspectiveUser"]["bill_text"]));
                $infoByFact = $this->getDataByFact($value,$fecha_fin);
                $numberVentas+=1;
                if ($infoByFact["actual"]) {
                    $numberMesNow++;
                }else{
                    $numberMesFuture++;
                }
                $totalValorVentas+= $infoByFact["valorFinal"];
                $totalCostoVentas+= $infoByFact["costo"];
            }

            if (!empty($flowInfo["Salesinvoice"])) {
                foreach ($flowInfo["Salesinvoice"] as $keySales => $valueSales) {
                    if ($valueSales["locked"] == 0) {
                        $value      = $this->object_to_array(json_decode($valueSales["bill_text"]));
                        $infoByFact = $this->getDataByFact($value,$fecha_fin);
                        $numberVentas+=1;
                        if ($infoByFact["actual"]) {
                            $numberMesNow++;
                        }else{
                            $numberMesFuture++;
                        }
                        $totalValorVentas+= $infoByFact["valorFinal"];
                        $totalCostoVentas+= $infoByFact["costo"];
                    }
                }
            }
        }

        return compact("numberVentas","numberMesNow","numberMesFuture","totalValorVentas","totalCostoVentas");

    }

    public function consult_information_user_flujos_data_grafica(){
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $fecha_inicio           = $this->request->data['fecha_inicio'];
            $fecha_fin              = $this->request->data['fecha_fin'];
            $user_id                = $this->request->data['user_id'];

            $numAsignados           = $this->ProspectiveUser->FlowStage->consult_information_user_flujos_asignado($fecha_inicio,$fecha_fin,$user_id);
            $ids_flujos_asignados   = $this->ProspectiveUser->FlowStage->consult_idsFlujos_user_flujos_asignado($fecha_inicio,$fecha_fin,$user_id);
            $detailDays             = $this->ProspectiveUser->FlowStage->consult_information_user_flujos_cotizados_detail($fecha_inicio,$fecha_fin,$user_id,$ids_flujos_asignados);

            $arrDays                = $this->date_range($fecha_inicio,$fecha_fin);
        }
    }

	public function consult_information_user_flujos(){
    	$this->layout 					= false;
		if ($this->request->is('ajax')) {

			$fecha_inicio 			= $this->request->data['fecha_inicio'];
            $fecha_inicio_com       = $this->request->data['fecha_inicio_com'];
            $fecha_fin              = $this->request->data['fecha_fin'];
			$fecha_fin_com 			= $this->request->data['fecha_fin_com'];
			$user_id 				= $this->request->data['user_id'];
			$numAsignados 			= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_asignado($fecha_inicio,$fecha_fin,$user_id);
			$ids_flujos_asignados 	= $this->ProspectiveUser->FlowStage->consult_idsFlujos_user_flujos_asignado($fecha_inicio,$fecha_fin,$user_id,false);

            $finalIdsAssined        = empty($ids_flujos_asignados) ? [] : Set::extract($ids_flujos_asignados,"{n}.FlowStage.prospective_users_id");

			$numContactados 		= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_contactados($fecha_inicio,$fecha_fin,$user_id,$ids_flujos_asignados);
            $numNegociados          = $this->ProspectiveUser->FlowStage->consult_information_user_flujos_negociados($fecha_inicio,$fecha_fin,$user_id,$ids_flujos_asignados);
            $numPagados             = $this->ProspectiveUser->FlowStage->consult_information_user_flujos_pagados($fecha_inicio,$fecha_fin,$user_id,$ids_flujos_asignados);
            $totalFlujos            = $this->ProspectiveUser->total_flujos_by_user($fecha_inicio,$fecha_fin,$user_id);

			$numCotizados 			= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_cotizados($fecha_inicio,$fecha_fin,$user_id,$ids_flujos_asignados);
            $detailDays             = $this->ProspectiveUser->FlowStage->consult_information_user_flujos_cotizados_detail($fecha_inicio,$fecha_fin,$user_id,$ids_flujos_asignados);

            $arrDays                = $this->date_range($fecha_inicio,$fecha_fin);

            $totalAssginedFinal     = $this->ProspectiveUser->find("list",["conditions"=>["DATE(ProspectiveUser.created) <="=> $fecha_fin, "DATE(ProspectiveUser.created) >=" => $fecha_inicio, "user_id" => $user_id ], "fields" => ["id","id"] ]);

            $totalLose              = $this->ProspectiveUser->find("count",["conditions"=>["DATE(ProspectiveUser.created) <="=> $fecha_fin, "DATE(ProspectiveUser.created) >=" => $fecha_inicio, "user_lose" => $user_id, "ProspectiveUser.id !="=> $totalAssginedFinal ]]);

            $totalDays              = 0;

            foreach ($arrDays as $key => $fecha) {
                $domingo = $this->ItIsSunday($fecha);
                if (isset($detailDays[$fecha]) && $detailDays[$fecha] > 0 && $domingo) {
                    $totalDays++;
                }elseif(!$domingo){
                    $totalDays++;
                }
            }

            $totalDays              = $totalDays == 0 ? 1 : $totalDays;

            $pagados                = $this->ProspectiveUser->FlowStage->find("count",["fields" => ["FlowStage.prospective_users_id"], "joins" => [['table' => 'prospective_users','alias' => 'PS','type' => 'INNER','conditions' => array('PS.id = FlowStage.prospective_users_id AND FlowStage.payment_verification = 1')]], "conditions" => array('ProspectiveUser.created >=' => $fecha_inicio,'ProspectiveUser.created <=' => $fecha_fin,'ProspectiveUser.user_id' => $user_id), "group" => ["FlowStage.prospective_users_id"] ]);

			$demoraContactado 		= $this->ProspectiveUser->AtentionTime->time_demora_contactado($fecha_inicio,$fecha_fin,$user_id,false);
			$demoraCotizar 			= ($this->ProspectiveUser->AtentionTime->time_demora_cotizado($fecha_inicio,$fecha_fin,$user_id,false));

            $this->ProspectiveUser->unBindModel([
                "hasMany" => array_keys($this->ProspectiveUser->hasMany),
                "belongsTo" => array_keys($this->ProspectiveUser->belongsTo),
                "hasAndBelongsToMany" => array_keys($this->ProspectiveUser->hasAndBelongsToMany),
            ]);

            $this->ProspectiveUser->setOtrasFacturas();

            $ids_flujos_asignados = Set::extract($ids_flujos_asignados,"{n}.FlowStage.prospective_users_id");
            $numCancelados        = $this->ProspectiveUser->find("count",["conditions" => ["ProspectiveUser.user_id" => $user_id, "ProspectiveUser.id" => $ids_flujos_asignados, "DATE(created) >="=> $fecha_inicio, "DATE(created) <=" => $fecha_fin, "ProspectiveUser.state_flow" => [7,9] ] ]);
            $allData              = $this->ProspectiveUser->find("all",["conditions" => ["ProspectiveUser.user_id" => $user_id, "ProspectiveUser.id" => $ids_flujos_asignados, "ProspectiveUser.bill_text !="=>null ] ]);
            
            $infoFinal            = $this->extractInformationFromFlows($allData,$fecha_fin);

            $numAsignados         = count($ids_flujos_asignados);

            $efectividad          = round($infoFinal["numberVentas"]/(count($finalIdsAssined) - $numCancelados)  *100,2);

            if (count($user_id) == 1) {
                $datosComisiones = $this->calculateFunctionByUserInterno($user_id[0],$fecha_inicio,$fecha_fin);   

                $this->loadModel("Effectivity");

                $percentajeDb = $this->Effectivity->find("first",["conditions"=>["Effectivity.min <=" => $efectividad, "Effectivity.max >" => $efectividad,"Effectivity.user_id" => $user_id[0] ], "recursive" => -1]);
                $porcentajes  = $this->Effectivity->findAllByUserId($user_id[0]);

                $this->set("datosComisiones",$datosComisiones);
                $this->set("percentajeDb",$percentajeDb);
                $this->set("porcentajes",$porcentajes);

            }

			$this->set(compact('numAsignados','numContactados','numCotizados','numVentas','valorVentas','demoraContactado','demoraCotizar','datosVentas','fecha_inicio','fecha_fin','totalFlujos','pagados','user_id','numCancelados','numPagados','numNegociados','arrDays','detailDays','infoFinal','totalDays','efectividad','totalLose','finalIdsAssined'));
		}
    }

	public function data_send_informe_adviser(){
		$this->layout 					= false;
		if ($this->request->is('ajax')) {
			$fecha_inicio 		= $this->request->data['fecha_inicio'];
			$fecha_fin 			= $this->request->data['fecha_fin'];
	    	$this->set(compact('fecha_inicio','fecha_fin'));
		}
	}

	public function sendInformeAdviser(){
		$this->autoRender   	= false;
		if ($this->request->is('ajax')) {
			$fecha_inicio 		= $this->request->data['fecha_inicio'];
			$fecha_fin 			= $this->request->data['fecha_fin'];
			$user_id 			= $this->request->data['user_id'];
			$datosUser 			= $this->ProspectiveUser->User->get_data($user_id);
			$numAsignados 		= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_asignado($fecha_inicio,$fecha_fin,$user_id);
			$numContactados 	= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_contactados($fecha_inicio,$fecha_fin,$user_id);
			$numCotizados 		= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_cotizados($fecha_inicio,$fecha_fin,$user_id);
			$valorCotizado		= $this->ProspectiveUser->FlowStage->consult_valor_user_flujos_cotizados($fecha_inicio,$fecha_fin,$user_id);
			$numVentas 			= $this->ProspectiveUser->FlowStage->consult_information_user_flujos_ventas($fecha_inicio,$fecha_fin,$user_id);
			$valorVentas 		= $this->ProspectiveUser->FlowStage->consult_valor_user_flujos_ventas($fecha_inicio,$fecha_fin,$user_id);
			$demoraContactado 	= $this->ProspectiveUser->AtentionTime->time_demora_contactado($fecha_inicio,$fecha_fin,$user_id);
			$demoraCotizar 		= $this->ProspectiveUser->AtentionTime->time_demora_cotizado($fecha_inicio,$fecha_fin,$user_id);
			$observation 		= $this->request->data['observation'];
			$email_defecto 		= Configure::read('variables.emails_defecto');
			$emailCliente 		= array();
			$emailCliente[0] 	= $datosUser['User']['email'];
			$emailCliente 		= array_merge($email_defecto, $emailCliente);
			$options = array(
				'to'		=> $emailCliente,
				'template'	=> 'send_informe_adviser',
				'subject'	=> 'Te han enviado un informe',
				'vars'		=> array('numAsignados' => $numAsignados,'numContactados' => $numContactados,'numCotizados' => $numCotizados,'valorCotizado' => $valorCotizado,'numVentas' => $numVentas,'valorVentas' => $valorVentas,'demoraContactado' => $demoraContactado,'demoraCotizar' => $demoraCotizar,'observation' => $observation),
			);
			$this->sendMail($options);
		}
	}

    

    public function report_date_advisers_flujos_count(){
    	$this->autoRender   = false;
		if ($this->request->is('ajax')) {
	    	$datos 			= $this->ProspectiveUser->all_prospetives_group_user($this->request->data['date_inicio'],$this->request->data['date_fin']);
	    	return count($datos);
	    }
    }

    public function find_data_etapa_flujo(){
    	$this->layout 					= false;
		if ($this->request->is('ajax')) {
			$atendidoRequerimiento 		= $this->request->data['atendido'];
			$datos = $this->ProspectiveUser->User->Manage->find_time_limit_prospecto_etapa($this->request->data['flujo_id'],$this->request->data['etapa']);
			$this->set(compact('datos','atendidoRequerimiento'));
		}
    }

    public function reportGerenciaTimeAsesoresAtender(){
    	$datos_busqueda_invalid 				= array();
    	$flujos_destiempo_contactado 			= $this->timeInvalideProspectoContactado();
    	if (count($flujos_destiempo_contactado) > 0) {
    		array_push($datos_busqueda_invalid,$flujos_destiempo_contactado);
    	}
    	$flujos_destiempo_cotizado 				= $this->timeInvalideProspectoCotizado();
    	if (count($flujos_destiempo_cotizado) > 0) {
    		array_push($datos_busqueda_invalid,$flujos_destiempo_cotizado);
    	}
    	return $datos_busqueda_invalid;
    }

    public function timeInvalideProspectoContactado(){
    	$this->autoRender   	= false;
    	$datos 					= $this->ProspectiveUser->AtentionTime->get_atentions();
    	$flujos_destiempo 		= array();
    	$i 						= 0;
    	foreach ($datos as $value) {
    		$count_data_flujo = $this->ProspectiveUser->User->Manage->count_time_limit_prospecto_etapa($value['AtentionTime']['prospective_users_id'],Configure::read('variables.nombre_flujo.flujo_contactado'));
    		if ($count_data_flujo > 0) {
    			$tiempo_limite_array 			= $this->ProspectiveUser->User->Manage->find_time_limit_prospecto_etapa($value['AtentionTime']['prospective_users_id'],Configure::read('variables.nombre_flujo.flujo_contactado'));
	    		$tiempo_limite_time_array       = split(":",$tiempo_limite_array['Manage']['time_end']);
	    		$tiempo_limite_date_array       = split("-",$tiempo_limite_array['Manage']['date']);
	    		$tiempo_atendido_time_array     = split(":",$value['AtentionTime']['contactado_time']);
	    		$tiempo_atendido_date_array     = split("-",$value['AtentionTime']['contactado_date']);
	    		if ((int) $tiempo_limite_date_array[0] < (int) $tiempo_atendido_date_array[0] || (int) $tiempo_limite_date_array[1] < (int) $tiempo_atendido_date_array[1] || (int) $tiempo_limite_date_array[2] < (int) $tiempo_atendido_date_array[2]) {
	    			$flujos_destiempo[$i]['AtentionTime']['prospective_users_id'] 	= $value['AtentionTime']['prospective_users_id'];
	    			$flujos_destiempo[$i]['AtentionTime']['cliente'] 				= $this->nameProspectiveContact($value['AtentionTime']['prospective_users_id']);
	    			$flujos_destiempo[$i]['AtentionTime']['requerimiento'] 			= $this->ProspectiveUser->FlowStage->find_reason_prospective($value['AtentionTime']['prospective_users_id']);
	    			$flujos_destiempo[$i]['AtentionTime']['tiempo_atendido'] 		= $value['AtentionTime']['contactado_date'].' '.$value['AtentionTime']['contactado_time'];
	    			$flujos_destiempo[$i]['AtentionTime']['tiempo_limite'] 			= $tiempo_limite_array['Manage']['date'].' '.$tiempo_limite_array['Manage']['time_end'];
	    			$flujos_destiempo[$i]['AtentionTime']['etapa'] 					= Configure::read('variables.nombre_flujo.flujo_contactado');
	    			$i++;
	    		} else {
	    			if ((int) $tiempo_limite_time_array[0] < (int) $tiempo_atendido_time_array[0] && (int) $tiempo_limite_date_array[2] == (int) $tiempo_atendido_date_array[2]) {
	    				$flujos_destiempo[$i]['AtentionTime']['prospective_users_id'] 	= $value['AtentionTime']['prospective_users_id'];
	    				$flujos_destiempo[$i]['AtentionTime']['cliente'] 				= $this->nameProspectiveContact($value['AtentionTime']['prospective_users_id']);
	    				$flujos_destiempo[$i]['AtentionTime']['requerimiento'] 			= $this->ProspectiveUser->FlowStage->find_reason_prospective($value['AtentionTime']['prospective_users_id']);
		    			$flujos_destiempo[$i]['AtentionTime']['tiempo_atendido'] 		= $value['AtentionTime']['contactado_date'].' '.$value['AtentionTime']['contactado_time'];
		    			$flujos_destiempo[$i]['AtentionTime']['tiempo_limite'] 			= $tiempo_limite_array['Manage']['date'].' '.$tiempo_limite_array['Manage']['time_end'];
		    			$flujos_destiempo[$i]['AtentionTime']['etapa'] 					= Configure::read('variables.nombre_flujo.flujo_contactado');
		    			$i++;
	    			}
	    		}
    		}
    	}
    	return $flujos_destiempo;
    }

    public function timeInvalideProspectoCotizado(){
    	$this->autoRender   	= false;
    	$datos 					= $this->ProspectiveUser->AtentionTime->get_atentions();
    	$flujos_destiempo 		= array();
    	$i 						= 0;
    	foreach ($datos as $value) {
    		$count_data_flujo = $this->ProspectiveUser->User->Manage->count_time_limit_prospecto_etapa($value['AtentionTime']['prospective_users_id'],Configure::read('variables.nombre_flujo.flujo_cotizado'));
    		if ($count_data_flujo > 0) {
	    		$tiempo_limite_array 			= $this->ProspectiveUser->User->Manage->count_time_limit_prospecto_etapa($value['AtentionTime']['prospective_users_id'],Configure::read('variables.nombre_flujo.flujo_cotizado'));
	    		$tiempo_limite_time_array       = split(":",$tiempo_limite_array['Manage']['time_end']);
	    		$tiempo_limite_date_array       = split("-",$tiempo_limite_array['Manage']['date']);
	    		$tiempo_atendido_time_array     = split(":",$value['AtentionTime']['cotizado_time']);
	    		$tiempo_atendido_date_array     = split("-",$value['AtentionTime']['cotizado_date']);
	    		if ((int) $tiempo_limite_date_array[0] < (int) $tiempo_atendido_date_array[0] || (int) $tiempo_limite_date_array[1] < (int) $tiempo_atendido_date_array[1] || (int) $tiempo_limite_date_array[2] < (int) $tiempo_atendido_date_array[2]) {

	    			$flujos_destiempo[$i]['AtentionTime']['prospective_users_id'] 	= $value['AtentionTime']['prospective_users_id'];
	    			$flujos_destiempo[$i]['AtentionTime']['cliente'] 				= $this->nameProspectiveContact($value['AtentionTime']['prospective_users_id']);
	    			$flujos_destiempo[$i]['AtentionTime']['requerimiento'] 			= $this->ProspectiveUser->FlowStage->find_reason_prospective($value['AtentionTime']['prospective_users_id']);
	    			$flujos_destiempo[$i]['AtentionTime']['tiempo_atendido'] 		= $value['AtentionTime']['cotizado_date'].' '.$value['AtentionTime']['cotizado_time'];
	    			$flujos_destiempo[$i]['AtentionTime']['tiempo_limite'] 			= $tiempo_limite_array['Manage']['date'].' '.$tiempo_limite_array['Manage']['time_end'];
	    			$flujos_destiempo[$i]['AtentionTime']['etapa'] 					= Configure::read('variables.nombre_flujo.flujo_cotizado');
	    			$i++;
	    		} else {
	    			if ((int) $tiempo_limite_time_array[0] < (int) $tiempo_atendido_time_array[0] && (int) $tiempo_limite_date_array[2] == (int) $tiempo_atendido_date_array[2]) {
	    				$flujos_destiempo[$i]['AtentionTime']['prospective_users_id'] 	= $value['AtentionTime']['prospective_users_id'];
	    				$flujos_destiempo[$i]['AtentionTime']['cliente'] 				= $this->nameProspectiveContact($value['AtentionTime']['prospective_users_id']);
	    				$flujos_destiempo[$i]['AtentionTime']['requerimiento'] 			= $this->ProspectiveUser->FlowStage->find_reason_prospective($value['AtentionTime']['prospective_users_id']);
		    			$flujos_destiempo[$i]['AtentionTime']['tiempo_atendido'] 		= $value['AtentionTime']['cotizado_date'].' '.$value['AtentionTime']['cotizado_time'];
		    			$flujos_destiempo[$i]['AtentionTime']['tiempo_limite'] 			= $tiempo_limite_array['Manage']['date'].' '.$tiempo_limite_array['Manage']['time_end'];
		    			$flujos_destiempo[$i]['AtentionTime']['etapa'] 					= Configure::read('variables.nombre_flujo.flujo_cotizado');
		    			$i++;
	    			}
	    		}
	    	}
    	}
    	return $flujos_destiempo;
    }

    public function verify_payments_payments(){
    	$this->validateRolePayments();
    	$payments = $this->ProspectiveUser->FlowStage->flujos_verify_abonos();
    	$this->set(compact('payments'));
    }

    public function information_dispatches($new = null){
    	// $this->validateRoleDispatches();
    	// $datos 		            = $this->ProspectiveUser->FlowStage->information_dispatches_order($new);
        // $pendingDispatches      = $this->ProspectiveUser->FlowStage->pending_dispatches();
        $datos                  = [];
        $pendingDispatches      = [];
    	$this->set(compact('datos','flows','pendingDispatches','new'));
    }

    public function validateTypeClient(){
    	$this->autoRender 				= false;
		if ($this->request->is('ajax')) {
			$datos 						= $this->ProspectiveUser->get_data($this->request->data['flujo_id']);
			return $datos['ProspectiveUser']['contacs_users_id'];
		}
    }

	// Datos de los flujos para el reporte
    public function export_file_data_report_flujos(){
        $this->layout         			= false;
        if ($this->request->is('ajax')) {
        	$type_reporte 	= $this->request->data['reporte_flujo'];
        	switch ($type_reporte) {
        		case '1':
        			$datos 			= $this->ProspectiveUser->all_prospetives_range_date($this->request->data['date_inicio'],$this->request->data['date_fin']);
        			break;
        		case '2':
        			$datos 			= $this->ProspectiveUser->all_prospetives_range_date_group_cliente_natural($this->request->data['date_inicio'],$this->request->data['date_fin']);
        			break;
        		case '3':
        			$datos 			= $this->ProspectiveUser->all_prospetives_range_date_group_cliente_juridico($this->request->data['date_inicio'],$this->request->data['date_fin']);
        			break;
        	}
	    	$this->set(compact('datos','type_reporte'));
	    }
    }

    
    


    // Tareas programadas
    public function timeManagementFlujosAttended(){
    	$this->autoRender 			= false;

        $flujosSinContactar = $this->ProspectiveUser->AtentionTime->find("all",["conditions" => ["concat(limit_contactado_date, ' ', limit_contactado_time) <=" => date("Y-m-d H:i:s"), "ProspectiveUser.state_flow" => 1, "ProspectiveUser.remarketing" => 0,"type" => 0 ] ]);

        if (!empty($flujosSinContactar)) {
            
            $this->loadModel("Config");
            $user_id = $this->Config->field("user_remarketing",["id" => 1]);
            $user_id = empty($user_id) ? 2 : $user_id;

            foreach ($flujosSinContactar as $key => $value) {
                
                $flujo_id                                       = $value["ProspectiveUser"]["id"];
                $datosP['ProspectiveUser']['id']                = $flujo_id;
                $datosP['ProspectiveUser']['user_id']           = $user_id;
                $datosP['ProspectiveUser']['remarketing']       = "1";
                $datosUserSession                               = $this->ProspectiveUser->User->get_data(112);
                $datosUserAsesor                                = $this->ProspectiveUser->User->get_data($user_id);
                $datosF['FlowStage']['state_flow']              = Configure::read('variables.nombre_flujo.asignado_flujo_proceso');
                $datosF['FlowStage']['prospective_users_id']    = $flujo_id;
                $datosF['FlowStage']['description']             = 'El asesor '.$datosUserSession['User']['name'].' asigno al asesor '.$datosUserAsesor['User']['name'].' para el flujo en proceso';
                $this->ProspectiveUser->FlowStage->create();
                if ($this->ProspectiveUser->FlowStage->save($datosF)){
                    $this->ProspectiveUser->save($datosP);
                    $this->saveDataLogsUser(12,'ProspectiveUser',$flujo_id);
                    $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.asesor_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosUserAsesor['User']['id'],$flujo_id,AuthComponent::user('id'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
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

        }

        $flujosSinCotizar = $this->ProspectiveUser->AtentionTime->find("all",["conditions" => ["concat(limit_cotizado_date, ' ', limit_cotizado_time) <=" => date("Y-m-d H:i:s"), "ProspectiveUser.state_flow" => 1, "ProspectiveUser.remarketing" => 0,"type" => 0 ] ]);

        if (!empty($flujosSinCotizar)) {
            
            $this->loadModel("Config");
            $user_id = $this->Config->field("user_remarketing",["id" => 1]);
            $user_id = empty($user_id) ? 2 : $user_id;

            foreach ($flujosSinCotizar as $key => $value) {
                
                $flujo_id                                       = $value["ProspectiveUser"]["id"];
                $datosP['ProspectiveUser']['id']                = $flujo_id;
                $datosP['ProspectiveUser']['user_id']           = $user_id;
                $datosP['ProspectiveUser']['remarketing']       = "1";
                $datosUserSession                               = $this->ProspectiveUser->User->get_data(112);
                $datosUserAsesor                                = $this->ProspectiveUser->User->get_data($user_id);
                $datosF['FlowStage']['state_flow']              = Configure::read('variables.nombre_flujo.asignado_flujo_proceso');
                $datosF['FlowStage']['prospective_users_id']    = $flujo_id;
                $datosF['FlowStage']['description']             = 'El asesor '.$datosUserSession['User']['name'].' asigno al asesor '.$datosUserAsesor['User']['name'].' para el flujo en proceso';
                
                $this->ProspectiveUser->FlowStage->create();
                if ($this->ProspectiveUser->FlowStage->save($datosF)){
                    $this->ProspectiveUser->save($datosP);
                    $this->saveDataLogsUser(12,'ProspectiveUser',$flujo_id);
                    $this->saveManagesUser(Configure::read('variables.Gestiones_descripcion.asesor_asignado'),Configure::read('variables.Gestiones_horas_habiles.flujo_asignado'),$datosUserAsesor['User']['id'],$flujo_id,AuthComponent::user('id'),$this->webroot.'prospectiveUsers/adviser?q='.$flujo_id);
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

        }

		return true;
	}





	// Modulo importaciones
    public function order_import(){

        $this->loadModel('Import');
        $conditions                 = array('Import.state' => Configure::read('variables.importaciones.proceso'), "Import.send_provider" => 1,'Import.description !=' => '');

        if (!empty($this->request->query) && isset($this->request->query["q"])) {
            $get = $this->request->query["q"];
            $conditions[] = array( "OR" => array(
                    "LOWER(Import.code_import) LIKE" =>  '%'.mb_strtolower($get).'%',
                    "LOWER(Import.description) LIKE"  =>  '%'.mb_strtolower($get).'%',
                )
            );
        }

        $order                      = array('Import.id' => 'desc');
        $this->paginate             = array(
                                        'limit'         => 20,
                                        'conditions'    => $conditions,
                                        'order'         => $order,
                                    );
        $importacionesCotizacion              = $this->paginate('Import');

    	$this->set(compact('importacionesCotizacion','importacionesSolicitudAsesor'));
    }

    public function import_finalizadas(){
    	$this->loadModel('Import');

    	$order					= array('Import.id' => 'desc');
		$conditions 			= array('Import.description !=' => '','Import.state' => Configure::read('variables.importaciones.finalizadas'));

        if (!empty($this->request->query) && isset($this->request->query["q"])) {
            $get = $this->request->query["q"];
            $conditions[] = array( "OR" => array(
                    "LOWER(Import.code_import) LIKE" =>  '%'.mb_strtolower($get).'%',
                    "LOWER(Import.description) LIKE"  =>  '%'.mb_strtolower($get).'%',
                )
            );
        }
        $conditions = $this->getImportsExternas($conditions);
		$this->paginate             = array(
                                        'limit'         => 20,
                                        'conditions'    => $conditions,
                                        'order'         => $order,
                                    );
		$importacionesSolicitudAsesor = $this->Paginator->paginate("Import");

    	$this->set(compact('importacionesCotizacion','importacionesSolicitudAsesor'));
    }

    public function my_import(){
    	$this->loadModel('ImportRequestsDetail');
    	$get 						= $this->request->query;
		if (!empty($get)) {
			$conditions 			= array('ImportRequestsDetail.user_id' 						=> AuthComponent::user('id'),
											'OR' => array(
									            'Import.code_import LIKE' 			=> '%'.$get['q'].'%'
									        )
										);

		} else {
			$conditions 			= array('ImportRequestsDetail.user_id' => AuthComponent::user('id')
										);
		}
		$order						= array('ImportRequestsDetail.id' => 'desc');
		$this->paginate 			= array(
							        	'limit' 		=> 10,
							        	'conditions' 	=> $conditions,
							        	'order' 		=> $order,
                                        'recursive'     => 2
							    	);
        $this->ImportRequestsDetail->unBindModel(["belongsTo"=>["User","ProspectiveUser"],"hasAndBelongsToMany"=>["Product"]]);
		$importaciones 				= $this->paginate('ImportRequestsDetail');
    	$this->set(compact('importaciones'));
    }

    public function add_import($modal = null){
    	$this->loadModel('Import');
        $this->set("modal",$modal);
        // $categories = $this->getCagegoryData();
        // $categoriesSelect = $this->getEstructure(0,$categories);
        $editProducts = $this->validateEditProducts();
        $addProducts = $this->validateAddProducts();
        $categoriesInfoFinal = $this->getCagegoryData();
        $this->set(compact("categoriesSelect","editProducts","addProducts","categoriesInfoFinal"));
    	$this->deleteCacheImportaciones($modal);
    }

    private function updateData($conditions){
        $this->loadModel('Import');
        $this->Import->unBindModel(["belongsTo" => ["User","Brand"],"hasOne" => ["ImportRequest"],"hasAndBelongsToMany" => ["Flujos","Product"],"hasMany" => ["ProspectiveUser"] ]);
        $allData = $this->Import->find("all",["conditions"=>$conditions]);
        if (!empty($allData)) {
            foreach ($allData as $key => $value) {
                if (empty($value["ImportProduct"])) {
                    $value["Import"]["state"] = 4;
                    $value["Import"]["motivo"] = "Cancelado por el sistema por duplicidad";
                    $value["Import"]["updated"] = date("Y-m-d H:i:s");
                    $this->Import->save($value["Import"]);
                }
            }
        }
    }

    private function getImportsExternas($conditions = array()){
        if (in_array(AuthComponent::user("role"), ["Gerente General","Logística"])) {
            return $conditions;
        }
        $this->loadModel("ImportRequestsDetail");
        $this->ImportRequestsDetail->unBindModel(["hasAndBelongsToMany"=>["Product"]]);
        $imports = $this->ImportRequestsDetail->find("all",["fields" => ["ImportRequest.import_id"], "conditions" => ["ImportRequestsDetail.user_id" => AuthComponent::user("id"),"ImportRequest.import_id !=" => null  ] ]);
        if (!empty($imports)) {
            $imports = Set::extract($imports,"{n}.ImportRequest.import_id");
        }else{
            $imports = 0;
        }
        $conditions["Import.id"] = $imports;
        return $conditions;
    }

    public function compras_nacionales(){
        $this->loadModel('Import');
        $conditions = [];

        $conditions                 = array(
                                        'ImportRequest2.type_money' => 'cop',
                                        'Import.state' => [1,Configure::read('variables.importaciones.solicitud')]
                                    );

        if (!empty($this->request->query) && isset($this->request->query["q"])) {
            $get = $this->request->query["q"];
            $conditions[] = array( "OR" => array(
                    "LOWER(Import.code_import) LIKE" =>  '%'.mb_strtolower($get).'%',
                    "LOWER(Import.description) LIKE"  =>  '%'.mb_strtolower($get).'%'
                )
            );
        }
        $order                      = array('Import.id' => 'desc');
        $this->paginate             = array(
                                        "joins" => [
                                            array(
                                                'table' => 'import_requests',
                                                'alias' => 'ImportRequest2',
                                                'type' => 'INNER',
                                                'conditions' => array(
                                                    'Import.id = ImportRequest2.import_id '
                                                )
                                            ),
                                        ],
                                        'limit'         => 20,
                                        'conditions'    => $conditions,
                                        'order'         => $order,
                                        'group'         => ["Import.id"],
                                    );
        $importaciones              = $this->paginate('Import');

        $this->set(compact('importaciones'));
    }

    public function imports_revisions(){
    	$this->loadModel('Import');
		$conditions 				= array(
            "OR" => array(
                array(
                    "Import.send_provider" => 0, "Import.state !=" => array(2,4),  
                ),
                    'Import.state' => Configure::read('variables.importaciones.solicitud')
            )
        );


        $this->updateData($conditions);       
        
        $conditions = $this->getImportsExternas($conditions);

        if (!empty($this->request->query) && isset($this->request->query["q"])) {
            $get = $this->request->query["q"];
            $conditions[] = array( "OR" => array(
                    "LOWER(Import.code_import) LIKE" =>  '%'.mb_strtolower($get).'%',
                    "LOWER(Import.description) LIKE"  =>  '%'.mb_strtolower($get).'%'
                )
            );
        }
		$order						= array('Import.id' => 'desc');
		$this->paginate 			= array(
							        	'limit' 		=> 20,
							        	'conditions' 	=> $conditions,
							        	'order' 		=> $order,
                                        'group'         => ["Import.id"],
							    	);
		$importaciones 				= $this->paginate('Import');

    	$this->set(compact('importaciones'));
    }

    public function updateStateApproved(){
    	$this->autoRender 				= false;
    	$this->loadModel('Import');
		if ($this->request->is('ajax')) {
			$import_id 					= $this->request->data['import_id'];
	    	$this->Import->update_state_approved($import_id);
	    	$this->Session->setFlash(__('La solicitud fue aprovada correctamente'),'Flash/success');
	    	return true;
	    }
    }

    public function rejectOnlyProduct(){
        $this->autoRender = false;
        $this->loadModel("ImportRequest");
        $this->loadModel("ImportRequestsDetail");
        $this->loadModel("ImportRequestsDetailsProduct");

        $requestBrand = $this->ImportRequest->findByImportIdAndState($this->request->data["importId"],2);
        if(empty($requestBrand)){
            return false;
        }

        $this->ImportRequest->Import->ImportProduct->recursive = -1;
        $productImport = $this->ImportRequest->Import->ImportProduct->findByImportIdAndProductId($this->request->data["importId"], $this->request->data["id"]);

        $this->loadModel("Reject");

        $reject = $productImport["ImportProduct"];
        $reject["reason"] = $this->request->data["razon"];
        $reject["user_id"] = AuthComponent::user("id");
        unset($reject["id"],$reject["created"],$reject["modified"]);

        $this->Reject->create();
        $this->Reject->save($reject);

        $imporRequestDetails = $this->ImportRequestsDetail->findAllByImportRequestId($requestBrand["ImportRequest"]["id"]);

        if(!empty($imporRequestDetails)){
            $imporRequestDetails = Set::extract($imporRequestDetails,"{n}.ImportRequestsDetail.id");
        }

        $details             = $this->ImportRequestsDetailsProduct->findAllByProductIdAndImportRequestsDetailId($this->request->data["id"],$imporRequestDetails);


        $imporRequestDetailsFinalIds = Set::extract($details, "{n}.ImportRequestsDetailsProduct.import_requests_detail_id");
        $imporRequestDetailsFinals   = $this->ImportRequest->ImportRequestsDetail->findAllById($imporRequestDetailsFinalIds);

        $this->ImportRequest->recursive = -1;
        $activeRequestBrand = $this->ImportRequest->findByBrandIdAndState($requestBrand["ImportRequest"]["brand_id"],"1");

        if(empty($activeRequestBrand)){
            $newRequest                 = $requestBrand["ImportRequest"];
            $newRequest["import_id"]    = null;
            $newRequest["state"]        = 1;
            unset($newRequest["id"]);
            unset($newRequest["created"]);
            unset($newRequest["modified"]);
            $this->ImportRequest->create();
            $this->ImportRequest->save($newRequest);
            $requestId = $this->ImportRequest->id;
        }else{
            $requestId = $activeRequestBrand["ImportRequest"]["id"];
        }

        foreach ($imporRequestDetailsFinals as $key => $value) {
            $value["ImportRequestsDetail"]["import_request_id"] = $requestId;
            $value["ImportRequestsDetail"]["state"] = 1;
            $this->ImportRequest->ImportRequestsDetail->save($value["ImportRequestsDetail"]);
        }

        $this->ImportRequest->Import->ImportProduct->id = $productImport["ImportProduct"]["id"];
        $this->ImportRequest->Import->ImportProduct->delete();
        $this->Session->setFlash(__('Producto eliminado con éxito.'),'Flash/success');
    }

    private function returnToBrandRequest($importId, $state = "1"){
    	$this->loadModel("ImportRequest");

    	$requestBrand = $this->ImportRequest->findByImportIdAndState($importId,2);

    	if(empty($requestBrand)){
    		return false;
    	}


    	$this->ImportRequest->recursive = -1;
    	$activeRequestBrand = $this->ImportRequest->findByBrandIdAndStateAndInternacionalAndType($requestBrand["ImportRequest"]["brand_id"],$state,$requestBrand["ImportRequest"]["internacional"],$requestBrand["ImportRequest"]["type"]);


        $idsProspectives = array();

        foreach ($requestBrand["ImportRequestsDetail"] as $key => $value) {
            if(!empty($value["prospective_user_id"])){
                $idsProspectives[] = $value["prospective_user_id"];
            }
        }

        $this->loadModel("Inventory");

        $this->Inventory->updateAll(
            [
                "Inventory.state" => 4,
                "Inventory.reason_reject"=>"'".$requestBrand["Import"]["motivo"]."'"
            ],
            ["Inventory.import_id" => $importId] 
        );

        if(!empty($idsProspectives)){

            $this->loadModel("ProductsLock");

            $todosLosQuantitiies = $this->ProductsLock->find("all",["conditions" => [

                'ProductsLock.prospective_user_id' => $idsProspectives, 
                'ProductsLock.quantity_back > ' => 0, 

                ] 
            ]);

            if(!empty($todosLosQuantitiies)){
                foreach ($todosLosQuantitiies as $key => $value) {
                    $this->loadModel("Product");
                    $product = $this->Product->find("first",["conditions" => ["Product.id" => $value["ProductsLock"]["product_id"] ], "recursive" => -1]);

                    if($product["Product"]["quantity_back"]- $value["ProductsLock"]["quantity_back"] >= 0 ){
                        $product["Product"]["quantity_back"]-=$value["ProductsLock"]["quantity_back"];
                    }else{
                        $value["ProductsLock"]["quantity_back"] = 0;
                    }

                    $this->Product->save($product);
                }
            }

            $this->ProductsLock->updateAll(
                array('ProductsLock.state' => 0),
                array(
                    'ProductsLock.prospective_user_id' => $idsProspectives, 
                    'ProductsLock.quantity_back > ' => 0, 
                    )
            );

        }

    	if(empty($activeRequestBrand)){
    		$this->ImportRequest->id 					= $requestBrand["ImportRequest"]["id"];
    		$requestBrand["ImportRequest"]["state"] 	= 1;
    		$requestBrand["ImportRequest"]["import_id"] = null;
    		$this->ImportRequest->save($requestBrand["ImportRequest"]);
            $this->ImportRequest->ImportRequestsDetail->updateAll(
                ["ImportRequestsDetail.state" => 1],
                ["ImportRequestsDetail.import_request_id" => $requestBrand["ImportRequest"]["id"] ] 
            );
    	}else{
            $this->ImportRequest->ImportRequestsDetail->recursive = -1;
            $detailsRequests = $this->ImportRequest->ImportRequestsDetail->findAllByImportRequestId($requestBrand["ImportRequest"]["id"]);
    		foreach ($detailsRequests as $key => $value) {
    			$this->ImportRequest->ImportRequestsDetail->id = $value["ImportRequestsDetail"]["id"];
    			$value["ImportRequestsDetail"]["import_request_id"] = $activeRequestBrand["ImportRequest"]["id"];
                $value["ImportRequestsDetail"]["state"] = 1;
    			$this->ImportRequest->ImportRequestsDetail->save($value);
    		}
    	}

    }

    public function updateStateRejectec(){
    	$this->autoRender 				= false;
    	$this->loadModel('Import');
		if ($this->request->is('ajax')) {
			$import_id 							= $this->request->data['import_id'];

            $datos['Import']['motivo']          = $this->request->data['motivo'];
			$datos['Import']['id']	 			= $import_id;
			$state 			                    = empty($this->request->data['state']) ? 1 : $this->request->data['state'];
	    	$this->Import->save($datos);
			$this->returnToBrandRequest($import_id,$state);
	    	$this->Import->update_state_rejectec($import_id);
	    	return true;
	    }
    }

    public function imports_approved(){
    	$this->loadModel('Import');
    	$conditions 				= array('Import.description !=' => '',
										'Import.state' => 1, "Import.send_provider" => 1
									);


        $idsDataFilters = $this->Import->find("list",["fields"=>["id","id"],"conditions" => $conditions]);

        foreach ($idsDataFilters as $key => $value) {
            $total = $this->Import->ImportProduct->find("count",["conditions" => ["ImportProduct.import_id" => $value, "ImportProduct.state_import" => 7]  ]);
            $totalProducts = $this->Import->ImportProduct->find("count",["conditions" => ["ImportProduct.import_id" => $value,]  ]);

            if ($total == $totalProducts) {
                $this->Import->updateAll(["Import.state" => 2],["Import.id" => $value]);
            }
        }

        if (!empty($this->request->query) && isset($this->request->query["q"])) {
            $get = $this->request->query["q"];
            $conditions[] = array( "OR" => array(
                    "LOWER(Import.code_import) LIKE" =>  '%'.mb_strtolower($get).'%',
                    "LOWER(Import.description) LIKE"  =>  '%'.mb_strtolower($get).'%',
                    "LOWER(Import.purchase_order) LIKE"  =>  '%'.mb_strtolower($get).'%',
                )
            );
        }

        $conditions = $this->getImportsExternas($conditions);

		$order						= array('Import.id' => 'desc');
		$this->paginate 			= array(
							        	'limit' 		=> 20,
							        	'conditions' 	=> $conditions,
							        	'order' 		=> $order,
                                        'group'         => ["Import.id"],
							    	);
		$importaciones 				= $this->paginate('Import');
    	$this->set(compact('importaciones'));
    }

    public function imports_rejected(){
    	$this->loadModel('Import');
    	$conditions 				= array('Import.state' => Configure::read('variables.importaciones.rechazado'));


        $conditionsData                 = array(
            "OR" => array(
                array(
                    "Import.send_provider" => 0, "Import.state !=" => array(2,4),  ),
                    'Import.state' => Configure::read('variables.importaciones.solicitud')
            )
        );

        $this->updateData($conditionsData);   

        if (!empty($this->request->query) && isset($this->request->query["q"])) {
            $get = $this->request->query["q"];
            $conditions[] = array( "OR" => array(
                    "LOWER(Import.code_import) LIKE" =>  '%'.mb_strtolower($get).'%',
                    "LOWER(Import.description) LIKE"  =>  '%'.mb_strtolower($get).'%',
                    "LOWER(Import.motivo) LIKE"  =>  '%'.mb_strtolower($get).'%'
                )
            );
        }
        $conditions = $this->getImportsExternas($conditions);
		$order						= array('Import.id' => 'desc');
		$this->paginate 			= array(
							        	'limit' 		=> 20,
							        	'conditions' 	=> $conditions,
							        	'order' 		=> $order,
							    	);
		$importaciones 				= $this->paginate('Import');
    	$this->set(compact('importaciones'));
    }

}