<?php
App::uses('AppController', 'Controller');
/**
 * Answers Controller
 *
 * @property Answer $Answer
 * @property PaginatorComponent $Paginator
 */
class AnswersController extends AppController {

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
		$this->Answer->recursive = 0;
		$this->set('answers', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Answer->exists($id)) {
			throw new NotFoundException(__('Invalid answer'));
		}
		$options = array('conditions' => array('Answer.' . $this->Answer->primaryKey => $id));
		$this->set('answer', $this->Answer->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Answer->Question->recursive = -1;
			$question = $this->Answer->Question->findById($this->request->data["Answer"]["question_id"]);
			$this->Answer->create();
			if ($this->Answer->save($this->request->data)) {
				$this->Session->setFlash(__('La respuesta fue guardada correctamente'),'Flash/success');
				return $this->redirect(Router::url("/",true)."quizzes/edit/".$this->encryptString($question["Question"]["quiz_id"])."#content");
			} else {
				$this->Session->setFlash(__('La respuesta fue guardada correctamente'),'Flash/error');
			}
		}
		else{
			$this->redirect("/");
		}
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
		$id 	 = $this->desencriptarCadena($id);
		$options = array('conditions' => array('Answer.' . $this->Answer->primaryKey => $id));
		$answer  = $this->Answer->find('first', $options);
		$question = $this->Answer->Question->findById($answer["Answer"]["question_id"]);
		if (!$this->Answer->exists($id)) {
			throw new NotFoundException(__('Invalid answer'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Answer->save($this->request->data)) {
				$this->Session->setFlash(__('La respuesta fue guardada correctamente'),'Flash/success');
				return $this->redirect(Router::url("/",true)."quizzes/edit/".$this->encryptString($question["Question"]["quiz_id"])."#content");
			} else {
				$this->Session->setFlash(__('La respuesta fue guardada correctamente'),'Flash/error');
			}
		} else {
			
			$this->request->data = $answer;
		}
		$this->set(compact('question'));
	}

	public function borrar($id = null,$quiz = null) {
		$this->autoRender = false;
		if (!$this->request->is("ajax")) {
			return false;
		}
		$id 	= $this->desencriptarCadena($id);
		$answer = $this->Answer->findById($id);
		$answer["Answer"]["state"] = 0; 
		$answer["Answer"]["modified"] = date("Y-m-d H:i:s"); 
		$this->Answer->save($answer);
	}
}
