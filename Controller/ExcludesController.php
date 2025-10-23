<?php
App::uses('AppController', 'Controller');
/**
 * Excludes Controller
 *
 * @property Exclude $Exclude
 * @property PaginatorComponent $Paginator
 */
class ExcludesController extends AppController {

	public function beforeFilter() {
        parent::beforeFilter();
        if (!AuthComponent::user("id") || !in_array(AuthComponent::user("email"), ["jotsuar@gmail.com", "gerencia@almacendelpintor.com",'logistica@kebco.co'])  ) {
        	$this->redirect(["action"=>"index",'controller' => 'prospective_users']);	
        }
    }
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
		$this->Exclude->recursive = 0;
		$this->paginate 		  = ["conditions" => [],"order"=>["Exclude.created"=>"DESC"]];
		$this->set('excludes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Exclude->exists($id)) {
			throw new NotFoundException(__('Invalid exclude'));
		}
		$options = array('conditions' => array('Exclude.' . $this->Exclude->primaryKey => $id));
		$this->set('exclude', $this->Exclude->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel("Assist");
		$users = $this->Exclude->User->find('list',["conditions"=>["User.id"=>$this->Assist->find("list",["fields"=>["user_id","user_id"]])]]);

		if ($this->request->is('post')) {

			if(empty($this->request->data["Exclude"]["user_id"])){
				foreach ($users as $user_id => $value) {
					$this->request->data["Exclude"]["user_id"] = $user_id;
					$this->Exclude->create();
					if ($this->Exclude->save($this->request->data)) {
						$this->Session->setFlash(__('La autorización fue guardada correctamente'),'Flash/success');
						
					} else {
						$this->Session->setFlash(__('La autorización no fue guardada correctamente'),'Flash/error');
					}
				}
				return $this->redirect(array('action' => 'index'));
			}else{
				$this->Exclude->create();
				if ($this->Exclude->save($this->request->data)) {
					$this->Session->setFlash(__('La autorización fue guardada correctamente'),'Flash/success');
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('La autorización no fue guardada correctamente'),'Flash/error');
				}
			}

			
		}
		
		$this->set(compact('users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {

		$this->loadModel("Assist");
		$users = $this->Exclude->User->find('list',["conditions"=>["User.id"=>$this->Assist->find("list",["fields"=>["user_id","user_id"]])]]);
		if (!$this->Exclude->exists($id)) {
			throw new NotFoundException(__('Invalid exclude'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Exclude->save($this->request->data)) {
				$this->Session->setFlash(__('La autorización fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La autorización no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Exclude.' . $this->Exclude->primaryKey => $id));
			$this->request->data = $this->Exclude->find('first', $options);
		}
		$this->set(compact('users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->Exclude->exists($id)) {
			throw new NotFoundException(__('Invalid exclude'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Exclude->delete($id)) {
			$this->Flash->success(__('The exclude has been deleted.'));
		} else {
			$this->Flash->error(__('The exclude could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
