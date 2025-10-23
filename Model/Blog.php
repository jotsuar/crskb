<?php
App::uses('AppModel', 'Model');
/**
 * Blog Model
 *
 * @property Carpeta $Carpeta
 */
class Blog extends AppModel {


	public $actsAs = array(
	   'Upload.Upload' => array(
	        'imagen_1' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}blogs{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	        ),
	        'imagen_2' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}blogs{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	        ),
	        'imagen_3' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}blogs{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	        ),
	        'archivo_1' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}documents{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	        ),
	        'archivo_2' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}documents{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	        ),
	        'archivo_3' => array(
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
		'description' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
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
