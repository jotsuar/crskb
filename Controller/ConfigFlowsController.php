<?php
App::uses('AppController', 'Controller');
/**
 * ConfigFlows Controller
 *
 * @property ConfigFlow $ConfigFlow
 * @property PaginatorComponent $Paginator
 */
class ConfigFlowsController extends AppController {

	public $components = array('Paginator');



	public function edit($id = null) {
		$id = 1;
		if (!$this->ConfigFlow->exists($id)) {
			throw new NotFoundException(__('Invalid config flow'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ConfigFlow->save($this->request->data)) {
				$this->Session->setFlash(__('Configuración guardada correctamente'),'Flash/success');
				return $this->redirect(array('action' => 'locked_flows','controller'=>"prospective_users"));
			} else {
				$this->Session->setFlash(__('Configuración no fue guardada correctamente'),'Flash/error');
			}
		} else {
			$options = array('conditions' => array('ConfigFlow.' . $this->ConfigFlow->primaryKey => $id));
			$this->request->data = $this->ConfigFlow->find('first', $options);
		}
	}

}
