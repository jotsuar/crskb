<?php
App::uses('AppModel', 'Model');
/**
 * CarpetaDetalle Model
 *
 * @property Carpeta $Carpeta
 * @property Document $Document
 * @property Blog $Blog
 */
class CarpetaDetalle extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
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
		),
		'Document' => array(
			'className' => 'Document',
			'foreignKey' => 'document_id',
			'conditions' => ["Document.state" => 1],
			'fields' => '',
			'order' => ''
		),
		'Blog' => array(
			'className' => 'Blog',
			'foreignKey' => 'blog_id',
			'conditions' => ["Blog.state"=>1],
			'fields' => '',
			'order' => ''
		)
	);
}
