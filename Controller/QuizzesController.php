<?php
App::uses('AppController', 'Controller');
/**
 * Quizzes Controller
 *
 * @property Quiz $Quiz
 * @property PaginatorComponent $Paginator
 */
class QuizzesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('respond','gracias');
    }

	public function index() {
		$this->validateRoleAdmin();
		$this->Quiz->recursive = 0;
		$this->set('quizzes', $this->Paginator->paginate());
	}


	public function gracias(){

	}

	public function view($id = null) {
		$this->validateRoleAdmin();
		$id 	= $this->desencriptarCadena($id);
		if (!$this->Quiz->exists($id)) {
			throw new NotFoundException(__('Invalid quiz'));
		}
		$options = array('conditions' => array('Quiz.' . $this->Quiz->primaryKey => $id));
		$this->set('quiz', $this->Quiz->find('first', $options));
		$questions = $this->Quiz->Question->findAllByQuizIdAndState($id,1);
		$questionsList = $this->Quiz->Question->find("list",["fields" => ["id","id"],"conditions" => ["Question.state" => 1, "Question.quiz_id" => $id ] ]);

		$this->loadModel("Result");

		$results = $this->Result->findAllByQuestionId($questionsList);

		$this->set("questions",$questions);
		$this->set("results",$results);
	}


	public function respond($id = null) {
		$valid = false;
		$id 	= $this->desencriptarCadena($id);
		if (!$this->Quiz->exists($id)) {
			throw new NotFoundException(__('Invalid quiz'));
		}

		if($this->request->is("post")){
			$this->loadModel("Result");

			$email 		= isset($this->request->data["Result"]["email"]) ?  $this->request->data["Result"]["email"] : null ;
			$user_id 	= isset($this->request->data["Result"]["user_id"]) ?  $this->request->data["Result"]["user_id"] : null ;

			unset($this->request->data["Result"]["email"]);
			unset($this->request->data["Result"]["user_id"]);

			foreach ($this->request->data["Result"] as $key => $value) {

				$dataResult = ["Result" => ["user_id" => $user_id, "email" => $email, ] ];

				$posR = strpos($key, "R");
				$posT = strpos($key, "T");

				if($posR === false && $posT === false && !is_array($value)){
					$dataResult["Result"]["question_id"] = $key;
					$dataResult["Result"]["answer_id"] = $value;				
					$dataResult["Result"]["response"] = $this->Quiz->Question->Answer->field("title",["id" => $value]);				
				}else{
					$dataResult["Result"]["question_id"] = $key;
					$dataResult["Result"]["answer_id"]   = 0;
					if (is_array($value)) {
						$resps = [];
						foreach ($value as $key => $responses) {
							$resps[] = $this->Quiz->Question->Answer->field("title",["id" => $value]);
						}
						$dataResult["Result"]["response"] = implode(", ", $resps);
					}elseif ($posR === false) {
						$partsR = explode("T", $key);
						$dataResult["Result"]["question_id"] = $partsR[0];
						$dataResult["Result"]["answer_id"]   = $partsR[1];
						$dataResult["Result"]["response"] 	 = $value;	
					}else{
						$partsR = explode("R", $key);
						$dataResult["Result"]["question_id"] = $partsR[0];
						$dataResult["Result"]["answer_id"]   = $partsR[1];
						$dataResult["Result"]["response"] 	 = $value;
					}
				}
				$this->Result->create();
				$this->Result->save($dataResult);

			}
			$this->redirect(["action" => "gracias"]);
		}

		$options = array('conditions' => array('Quiz.' . $this->Quiz->primaryKey => $id, "Quiz.date_ini <=" => date("Y-m-d"), "Quiz.date_end >=" =>  date("Y-m-d")  ));
		$quiz = $this->Quiz->find('first', $options);

		if( (AuthComponent::user("id") && $quiz["Quiz"]["type"] == 0) || (!AuthComponent::user("id") && $quiz["Quiz"]["type"] == 1) ){
			$valid = true;
		}

		if (empty($quiz)) {
			$valid = false;
		}



		$this->set('quiz', $quiz );
		$this->set('valid', $valid );
		$questions = $this->Quiz->Question->findAllByQuizIdAndState($id,1);
		$this->set("questions",$questions);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->validateRoleAdmin();
		if ($this->request->is('post')) {
			$this->Quiz->create();
			if ($this->Quiz->save($this->request->data)) {
				$this->Session->setFlash(__('La encuesta fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'edit',$this->encryptString($this->Quiz->id)));
			} else {
				$this->Session->setFlash(__('La encuesta no fue guardada correctamente'),'Flash/error');
			}
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
		$this->validateRoleAdmin();
		$id 				= $this->desencriptarCadena($id);
		if (!$this->Quiz->exists($id)) {
			throw new NotFoundException(__('Invalid quiz'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Quiz->save($this->request->data)) {
				$this->Session->setFlash(__('La encuesta fue guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La encuesta no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('Quiz.' . $this->Quiz->primaryKey => $id));
			$this->request->data = $this->Quiz->find('first', $options);
		}
		$questions = $this->Quiz->Question->findAllByQuizIdAndState($id,1);
		$this->set("questions",$questions);

	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Quiz->id = $id;
		if (!$this->Quiz->exists()) {
			throw new NotFoundException(__('Invalid quiz'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Quiz->delete()) {
			$this->Flash->success(__('The quiz has been deleted.'));
		} else {
			$this->Flash->error(__('The quiz could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
