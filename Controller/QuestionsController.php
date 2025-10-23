<?php
App::uses('AppController', 'Controller');
/**
 * Questions Controller
 *
 * @property Question $Question
 * @property PaginatorComponent $Paginator
 */
class QuestionsController extends AppController {

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
		$this->Question->recursive = 0;
		$this->set('questions', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Question->exists($id)) {
			throw new NotFoundException(__('Invalid question'));
		}
		$options = array('conditions' => array('Question.' . $this->Question->primaryKey => $id));
		$this->set('question', $this->Question->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Question->create();
			if ($this->Question->save($this->request->data)) {
				$this->Session->setFlash(__('La pregunta fue guardada correctamente'),'Flash/success');
				return $this->redirect(Router::url("/",true)."quizzes/edit/".$this->encryptString($this->request->data["Question"]["quiz_id"])."#content");
			} else {
				$this->Session->setFlash(__('La pregunta no fue guardada correctamente'),'Flash/error');
			}
		}
		$quizzes = $this->Question->Quiz->find('list');
		$this->set(compact('quizzes'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->layout = false;
		$id 	= $this->desencriptarCadena($id);
		if (!$this->Question->exists($id)) {
			throw new NotFoundException(__('Invalid question'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Question->save($this->request->data)) {
				$this->Session->setFlash(__('La pregunta fue guardada correctamente'),'Flash/success');
				return $this->redirect(Router::url("/",true)."quizzes/edit/".$this->encryptString($this->request->data["Question"]["quiz_id"])."#content");
			} else {
				$this->Session->setFlash(__('La pregunta no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Question.' . $this->Question->primaryKey => $id));
			$this->request->data = $this->Question->find('first', $options);
		}
		$totalAnswers = $this->Question->Answer->find("count",["conditions" => ["Answer.question_id" => $id, "Answer.state" => 1] ]);
		$this->set(compact('quizzes','totalAnswers'));
 	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Question->id = $id;
		if (!$this->Question->exists()) {
			throw new NotFoundException(__('Invalid question'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Question->delete()) {
			$this->Flash->success(__('The question has been deleted.'));
		} else {
			$this->Flash->error(__('The question could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function borrar($id = null,$quiz = null) {
		$this->autoRender = false;
		if (!$this->request->is("ajax")) {
			return false;
		}
		$id 	= $this->desencriptarCadena($id);
		$question = $this->Question->findById($id);
		$question["Question"]["state"] = 0; 
		$question["Question"]["modified"] = date("Y-m-d H:i:s"); 
		$this->Question->save($question["Question"]);
	}

}
