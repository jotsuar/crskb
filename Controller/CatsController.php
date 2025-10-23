<?php
App::uses('AppController', 'Controller');
/**
 * Cats Controller
 *
 * @property Cat $Cat
 * @property PaginatorComponent $Paginator
 */
class CatsController extends AppController {

	public $components = array('Paginator');

	public function setGeneralCategory(){
		$this->autoRender = false;
		$active_users = $this->Cat->User->find("list",["conditions"=>["User.state" => 1 ]]);

		foreach ($active_users as $user_id => $value) {
			$cat_general = $this->Cat->field("id",["user_id"=>$user_id,"general" => 1]);
			if (empty($cat_general) || $cat_general == false) {
				$this->Cat->create();
				$this->Cat->save(["name" => "General", "description" => "Categoría general para todos los compromisos","general" => 1, "user_id" => $user_id ]);
			}
		}
	}

	public function get_cats_user() {
		$this->layout = false;
		$cats = $this->Cat->find("list",["conditions"=>["Cat.state" => 1, "Cat.user_id" => $this->request->data["user_id"] ]]);
		$this->set(compact("cats"));
	}

	public function index() {
		$this->Cat->recursive = 0;

		$get 		   = isset($this->request->query["q"]) && !empty($this->request->query["q"]) ? $this->request->query : array() ;
		if(!empty($get["q"]) ){
			$conditions	= array('OR' => 
				array(
				        'LOWER(Cat.name) LIKE' 			=> '%'.mb_strtolower($get['q']).'%',
				        'LOWER(Cat.description) LIKE'	=> '%'.mb_strtolower($get['q']).'%'
				    )
			);
    	}else{
    		$conditions = [];
    	}
    	$conditions["Cat.user_id"] = AuthComponent::user("id");

		$this->paginate 			= array(
										'order' 		=> ["Cat.created" => "DESC"],
							        	'limit' 		=> 10,
							        	'conditions' 	=> $conditions,
							    	);
		$this->set('cats', $this->Paginator->paginate());
	}

	public function view($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Cat->exists($id)) {
			throw new NotFoundException(__('Invalid cat'));
		}
		$options = array('conditions' => array('Cat.' . $this->Cat->primaryKey => $id));
		$this->set('cat', $this->Cat->find('first', $options));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Cat->create();
			if ($this->Cat->save($this->request->data)) {
				$this->Session->setFlash(__('La información fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La información no fue guardada correctamente'),'Flash/error');
			}
		}
		$users = $this->Cat->User->find('list');
		$this->set(compact('users'));
	}

	public function edit($id = null) {
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Cat->exists($id)) {
			throw new NotFoundException(__('Invalid cat'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Cat->save($this->request->data)) {
				$this->Session->setFlash(__('La información fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La información no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Cat.' . $this->Cat->primaryKey => $id));
			$this->request->data = $this->Cat->find('first', $options);
		}
		$users = $this->Cat->User->find('list');
		$this->set(compact('users'));
	}

	public function delete($id = null) {
		$this->Cat->id = $id;
		if (!$this->Cat->exists()) {
			throw new NotFoundException(__('Invalid cat'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Cat->delete()) {
			$this->Flash->success(__('The cat has been deleted.'));
		} else {
			$this->Flash->error(__('The cat could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
