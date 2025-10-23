<?php
App::uses('AppController', 'Controller');
/**
 * Certificados Controller
 *
 * @property Certificado $Certificado
 * @property PaginatorComponent $Paginator
 */
class CertificadosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Certificado->recursive = 0;
		$this->set('certificados', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Certificado->exists($id)) {
			throw new NotFoundException(__('Invalid certificado'));
		}
		$options = array('conditions' => array('Certificado.' . $this->Certificado->primaryKey => $id));
		$this->set('certificado', $this->Certificado->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($type, $customer_id) {
		if ($this->request->is('post')) {
			$this->Certificado->create();

			$nombre         = isset($this->request->data["Certificado"]["name"]) && $this->request->data["Certificado"]["name"] != '' ?  $this->request->data["Certificado"]["name"] : ''; 
	        $identification = isset($this->request->data["Certificado"]["identification"]) && $this->request->data["Certificado"]["identification"] != '' ?  $this->request->data["Certificado"]["identification"] : ''; 
	        $course         = isset($this->request->data["Certificado"]["course"]) && $this->request->data["Certificado"]["course"] != '' ?  $this->request->data["Certificado"]["course"] : ''; 
	        $city_date      = isset($this->request->data["Certificado"]["city_date"]) && $this->request->data["Certificado"]["city_date"] != '' ?  $this->request->data["Certificado"]["city_date"] : ''; 

			$image     = $this->get_image_certificate($nombre, $identification, $course, $city_date);
			$imagename = time();
        
            imagepng($image,WWW_ROOT.'certificados'.DS.'diplomas'.DS.$imagename.'.png');

            $this->request->data["Certificado"]["imagename"] = $imagename.'.png';

			if ($this->Certificado->save($this->request->data)) {
				$this->Session->setFlash('Certificado creado','Flash/success');
				return $this->redirect(array('action' => 'view',"controller" => $type == 'natural' ? 'ClientsNaturals' : 'ClientsLegals', $this->encryptString($customer_id) ));
			} else {
				$this->Session->setFlash('Certificado no fue creado creado','Flash/error');
			}
		}

		if($type == "natural"){
			$this->Certificado->ClientsNatural->recursive = -1;
			$customer = $this->Certificado->ClientsNatural->findById($customer_id);
		}else{
			$this->Certificado->ClientsLegal->recursive = -1;
			$customer = $this->Certificado->ClientsLegal->findById($customer_id);
		}

		$this->set(compact('customer','type','customer_id'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Certificado->exists($id)) {
			throw new NotFoundException(__('Invalid certificado'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Certificado->save($this->request->data)) {
				$this->Flash->success(__('The certificado has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The certificado could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Certificado.' . $this->Certificado->primaryKey => $id));
			$this->request->data = $this->Certificado->find('first', $options);
		}
		$clientsNaturals = $this->Certificado->ClientsNatural->find('list');
		$clientsLegals = $this->Certificado->ClientsLegal->find('list');
		$this->set(compact('clientsNaturals', 'clientsLegals'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Certificado->exists($id)) {
			throw new NotFoundException(__('Invalid certificado'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Certificado->delete($id)) {
			$this->Flash->success(__('The certificado has been deleted.'));
		} else {
			$this->Flash->error(__('The certificado could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
