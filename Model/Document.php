<?php
App::uses('AppModel', 'Model');
/**
 * Document Model
 *
 * @property Carpeta $Carpeta
 */
class Document extends AppModel {

	public $actsAs = array(
	   'Upload.Upload' => array(
	         'file' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}documents{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	         'image' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}documents{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	     )
	);

	public $validate = array(
		'type' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'carpeta_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'state' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Carpeta' => array(
			'className' => 'Carpeta',
			'foreignKey' => 'carpeta_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
