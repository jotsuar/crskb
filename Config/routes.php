<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

	Router::connect('/', array('controller' => 'pages', 'action' => 'intro'));
	Router::connect('/null', array('controller' => 'ProspectiveUsers', 'action' => 'filtro_null_index'));
	Router::connect('/null_user', array('controller' => 'ProspectiveUsers', 'action' => 'filtro_null_adviser'));
	Router::connect('/crear-pqrs', array('controller' => 'Pqrs', 'action' => 'add'));
	Router::connect('/gestion-pqrs', array('controller' => 'Pqrs', 'action' => 'admin'));
	Router::connect('/newsletter_down/*', array('controller' => 'products', 'action' => 'unsubscribe'));



	CakePlugin::routes();

	require CAKE . 'Config' . DS . 'routes.php';